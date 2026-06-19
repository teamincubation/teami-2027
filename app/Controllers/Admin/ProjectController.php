<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class ProjectController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    /**
     * List all projects.
     */
    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM projects WHERE deleted_at IS NULL ORDER BY created_at DESC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/projects/index', [
            'title' => 'Projects CMS | Admin',
            'projects' => $projects,
            'active' => 'projects'
        ], 'admin');
    }

    /**
     * Show create form.
     */
    public function create(): void {
        $this->render('admin/projects/create', [
            'title' => 'Create Project | Admin',
            'active' => 'projects'
        ], 'admin');
    }

    /**
     * Store new project.
     */
    public function store(): void {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $full_description = trim($_POST['full_description'] ?? '');
        $objectives = trim($_POST['objectives'] ?? '');
        $target_beneficiaries = trim($_POST['target_beneficiaries'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $start_date = trim($_POST['start_date'] ?? '');
        $end_date = trim($_POST['end_date'] ?? '');
        $status = $_POST['status'] ?? 'planning';
        $featured = isset($_POST['featured']) ? 1 : 0;

        $errors = [];
        if (empty($name)) {
            $errors[] = "Project name is required.";
        }
        if (empty($short_description)) {
            $errors[] = "Short description is required.";
        }
        if (empty($full_description)) {
            $errors[] = "Full description is required.";
        }

        // Auto-generate slug from name
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $db = BaseModel::getConnection();
        // Check slug uniqueness
        $stmtCheck = $db->prepare("SELECT COUNT(*) FROM projects WHERE slug = ? AND deleted_at IS NULL");
        $stmtCheck->execute([$slug]);
        if ($stmtCheck->fetchColumn() > 0) {
            $slug = $slug . '-' . time();
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/projects/create');
        }

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $bannerImage = null;
            $featuredImage = null;

            if (!empty($_FILES['banner_image']['name'])) {
                $bannerImage = $this->storage->store($_FILES['banner_image'], 'projects', $allowedMimes);
            }
            if (!empty($_FILES['featured_image']['name'])) {
                $featuredImage = $this->storage->store($_FILES['featured_image'], 'projects', $allowedMimes);
            }

            $stmt = $db->prepare("
                INSERT INTO projects (name, slug, category, short_description, full_description, objectives, target_beneficiaries, location, start_date, end_date, status, banner_image, featured_image, featured, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name,
                $slug,
                empty($category) ? null : $category,
                $short_description,
                $full_description,
                empty($objectives) ? null : $objectives,
                empty($target_beneficiaries) ? null : $target_beneficiaries,
                empty($location) ? null : $location,
                empty($start_date) ? null : $start_date,
                empty($end_date) ? null : $end_date,
                $status,
                $bannerImage,
                $featuredImage,
                $featured,
                auth_user()['id'] ?? null
            ]);

            $_SESSION['flash_success'] = "Project created successfully.";
            $this->redirect('/admin/projects');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Save failed: " . $e->getMessage()];
            $this->redirect('/admin/projects/create');
        }
    }

    /**
     * Show edit form.
     */
    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            $_SESSION['flash_error'] = "Project not found.";
            $this->redirect('/admin/projects');
        }

        $this->render('admin/projects/edit', [
            'title' => 'Edit Project | Admin',
            'project' => $project,
            'active' => 'projects'
        ], 'admin');
    }

    /**
     * Update project.
     */
    public function update(string $id): void {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $short_description = trim($_POST['short_description'] ?? '');
        $full_description = trim($_POST['full_description'] ?? '');
        $objectives = trim($_POST['objectives'] ?? '');
        $target_beneficiaries = trim($_POST['target_beneficiaries'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $start_date = trim($_POST['start_date'] ?? '');
        $end_date = trim($_POST['end_date'] ?? '');
        $status = $_POST['status'] ?? 'planning';
        $featured = isset($_POST['featured']) ? 1 : 0;

        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$project) {
            $_SESSION['flash_error'] = "Project not found.";
            $this->redirect('/admin/projects');
        }

        $errors = [];
        if (empty($name)) {
            $errors[] = "Project name is required.";
        }
        if (empty($short_description)) {
            $errors[] = "Short description is required.";
        }
        if (empty($full_description)) {
            $errors[] = "Full description is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/projects/edit/' . $id);
        }

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $bannerImage = $project['banner_image'];
            $featuredImage = $project['featured_image'];

            // Replace banner
            if (!empty($_FILES['banner_image']['name'])) {
                $bannerImage = $this->storage->store($_FILES['banner_image'], 'projects', $allowedMimes);
                if (!empty($project['banner_image'])) {
                    $this->storage->delete($project['banner_image']);
                }
            }

            // Replace featured image
            if (!empty($_FILES['featured_image']['name'])) {
                $featuredImage = $this->storage->store($_FILES['featured_image'], 'projects', $allowedMimes);
                if (!empty($project['featured_image'])) {
                    $this->storage->delete($project['featured_image']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE projects 
                SET name = ?, category = ?, short_description = ?, full_description = ?, objectives = ?, target_beneficiaries = ?, location = ?, start_date = ?, end_date = ?, status = ?, banner_image = ?, featured_image = ?, featured = ?
                WHERE id = ?
            ");
            $stmtUpdate->execute([
                $name,
                empty($category) ? null : $category,
                $short_description,
                $full_description,
                empty($objectives) ? null : $objectives,
                empty($target_beneficiaries) ? null : $target_beneficiaries,
                empty($location) ? null : $location,
                empty($start_date) ? null : $start_date,
                empty($end_date) ? null : $end_date,
                $status,
                $bannerImage,
                $featuredImage,
                $featured,
                intval($id)
            ]);

            $_SESSION['flash_success'] = "Project updated successfully.";
            $this->redirect('/admin/projects');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Update failed: " . $e->getMessage()];
            $this->redirect('/admin/projects/edit/' . $id);
        }
    }

    /**
     * Delete project (soft delete).
     */
    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            UPDATE projects 
            SET deleted_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $stmt->execute([intval($id)]);

        $_SESSION['flash_success'] = "Project archived successfully.";
        $this->redirect('/admin/projects');
    }
}
