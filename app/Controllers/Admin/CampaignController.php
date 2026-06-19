<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class CampaignController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM campaigns WHERE deleted_at IS NULL ORDER BY created_at DESC");
        $campaigns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/campaigns/index', [
            'title' => 'Campaigns Management | Admin',
            'campaigns' => $campaigns,
            'active' => 'campaigns'
        ], 'admin');
    }

    public function create(): void {
        $this->render('admin/campaigns/create', [
            'title' => 'Add Campaign | Admin',
            'active' => 'campaigns'
        ], 'admin');
    }

    public function store(): void {
        $title = trim($_POST['title'] ?? '');
        $type = trim($_POST['type'] ?? 'internal');
        $collaborating_organizations = trim($_POST['collaborating_organizations'] ?? '');
        $objectives = trim($_POST['objectives'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
        $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
        $location = trim($_POST['location'] ?? '');
        $target_group = trim($_POST['target_group'] ?? '');
        $volunteer_requirements = trim($_POST['volunteer_requirements'] ?? '');
        $target_stat = !empty($_POST['target_stat']) ? floatval($_POST['target_stat']) : 0.00;
        $achieved_stat = !empty($_POST['achieved_stat']) ? floatval($_POST['achieved_stat']) : 0.00;
        $coordinators = trim($_POST['coordinators'] ?? '');
        $status = trim($_POST['status'] ?? 'upcoming');
        $featured = isset($_POST['featured']) ? 1 : 0;
        $public_report = trim($_POST['public_report'] ?? '');

        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($description)) {
            $errors[] = "Description is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/campaigns/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-')) . '-' . time();

        try {
            $bannerPath = null;
            if (!empty($_FILES['banner']['name'])) {
                $bannerPath = $this->storage->store($_FILES['banner'], 'campaigns', ['image/jpeg', 'image/png', 'image/webp']);
            }

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO campaigns (
                    title, slug, type, collaborating_organizations, objectives, description, 
                    start_date, end_date, location, target_group, banner, volunteer_requirements, 
                    target_stat, achieved_stat, coordinators, status, featured, public_report
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $title, $slug, $type, $collaborating_organizations, $objectives, $description,
                $start_date, $end_date, $location, $target_group, $bannerPath, $volunteer_requirements,
                $target_stat, $achieved_stat, $coordinators, $status, $featured, $public_report
            ]);

            $_SESSION['flash_success'] = "Campaign created successfully.";
            $this->redirect('/admin/campaigns');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error creating campaign: " . $e->getMessage()];
            $this->redirect('/admin/campaigns/create');
        }
    }

    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM campaigns WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            $_SESSION['flash_error'] = "Campaign not found.";
            $this->redirect('/admin/campaigns');
        }

        $this->render('admin/campaigns/edit', [
            'title' => 'Edit Campaign | Admin',
            'campaign' => $campaign,
            'active' => 'campaigns'
        ], 'admin');
    }

    public function update(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM campaigns WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$campaign) {
            $_SESSION['flash_error'] = "Campaign not found.";
            $this->redirect('/admin/campaigns');
        }

        $title = trim($_POST['title'] ?? '');
        $type = trim($_POST['type'] ?? 'internal');
        $collaborating_organizations = trim($_POST['collaborating_organizations'] ?? '');
        $objectives = trim($_POST['objectives'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $start_date = !empty($_POST['start_date']) ? trim($_POST['start_date']) : null;
        $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
        $location = trim($_POST['location'] ?? '');
        $target_group = trim($_POST['target_group'] ?? '');
        $volunteer_requirements = trim($_POST['volunteer_requirements'] ?? '');
        $target_stat = !empty($_POST['target_stat']) ? floatval($_POST['target_stat']) : 0.00;
        $achieved_stat = !empty($_POST['achieved_stat']) ? floatval($_POST['achieved_stat']) : 0.00;
        $coordinators = trim($_POST['coordinators'] ?? '');
        $status = trim($_POST['status'] ?? 'upcoming');
        $featured = isset($_POST['featured']) ? 1 : 0;
        $public_report = trim($_POST['public_report'] ?? '');

        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($description)) {
            $errors[] = "Description is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/campaigns/edit/' . $id);
        }

        try {
            $bannerPath = $campaign['banner'];
            if (!empty($_FILES['banner']['name'])) {
                $bannerPath = $this->storage->store($_FILES['banner'], 'campaigns', ['image/jpeg', 'image/png', 'image/webp']);
                if (!empty($campaign['banner'])) {
                    $this->storage->delete($campaign['banner']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE campaigns 
                SET title = ?, type = ?, collaborating_organizations = ?, objectives = ?, description = ?, 
                    start_date = ?, end_date = ?, location = ?, target_group = ?, banner = ?, 
                    volunteer_requirements = ?, target_stat = ?, achieved_stat = ?, coordinators = ?, 
                    status = ?, featured = ?, public_report = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $title, $type, $collaborating_organizations, $objectives, $description,
                $start_date, $end_date, $location, $target_group, $bannerPath,
                $volunteer_requirements, $target_stat, $achieved_stat, $coordinators,
                $status, $featured, $public_report, intval($id)
            ]);

            $_SESSION['flash_success'] = "Campaign updated successfully.";
            $this->redirect('/admin/campaigns');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error updating campaign: " . $e->getMessage()];
            $this->redirect('/admin/campaigns/edit/' . $id);
        }
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM campaigns WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($campaign) {
            $stmtDel = $db->prepare("UPDATE campaigns SET deleted_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Campaign deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Campaign not found.";
        }

        $this->redirect('/admin/campaigns');
    }
}
