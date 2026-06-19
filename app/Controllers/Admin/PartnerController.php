<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class PartnerController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM partners ORDER BY display_order ASC, created_at DESC");
        $partners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/partners/index', [
            'title' => 'Partners Management | Admin',
            'partners' => $partners,
            'active' => 'partners'
        ], 'admin');
    }

    public function create(): void {
        $this->render('admin/partners/create', [
            'title' => 'Add Partner | Admin',
            'active' => 'partners'
        ], 'admin');
    }

    public function store(): void {
        $name = trim($_POST['name'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $collaboration_start = !empty($_POST['collaboration_start']) ? trim($_POST['collaboration_start']) : null;
        $collaboration_end = !empty($_POST['collaboration_end']) ? trim($_POST['collaboration_end']) : null;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if (empty($name)) {
            $errors[] = "Partner Name is required.";
        }
        if (empty($_FILES['logo']['name'])) {
            $errors[] = "Partner Logo is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/partners/create');
        }

        try {
            $logoPath = $this->storage->store($_FILES['logo'], 'partners', ['image/jpeg', 'image/png', 'image/webp']);

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO partners (
                    name, logo, website, category, description, 
                    collaboration_start, collaboration_end, featured, display_order, active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $name, $logoPath, empty($website) ? null : $website, empty($category) ? null : $category, 
                empty($description) ? null : $description, $collaboration_start, $collaboration_end, 
                $featured, $display_order, $active
            ]);

            $_SESSION['flash_success'] = "Partner added successfully.";
            $this->redirect('/admin/partners');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error adding partner: " . $e->getMessage()];
            $this->redirect('/admin/partners/create');
        }
    }

    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM partners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$partner) {
            $_SESSION['flash_error'] = "Partner not found.";
            $this->redirect('/admin/partners');
        }

        $this->render('admin/partners/edit', [
            'title' => 'Edit Partner | Admin',
            'partner' => $partner,
            'active' => 'partners'
        ], 'admin');
    }

    public function update(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM partners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$partner) {
            $_SESSION['flash_error'] = "Partner not found.";
            $this->redirect('/admin/partners');
        }

        $name = trim($_POST['name'] ?? '');
        $website = trim($_POST['website'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $collaboration_start = !empty($_POST['collaboration_start']) ? trim($_POST['collaboration_start']) : null;
        $collaboration_end = !empty($_POST['collaboration_end']) ? trim($_POST['collaboration_end']) : null;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if (empty($name)) {
            $errors[] = "Partner Name is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/partners/edit/' . $id);
        }

        try {
            $logoPath = $partner['logo'];
            if (!empty($_FILES['logo']['name'])) {
                $logoPath = $this->storage->store($_FILES['logo'], 'partners', ['image/jpeg', 'image/png', 'image/webp']);
                if (!empty($partner['logo'])) {
                    $this->storage->delete($partner['logo']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE partners 
                SET name = ?, logo = ?, website = ?, category = ?, description = ?, 
                    collaboration_start = ?, collaboration_end = ?, featured = ?, display_order = ?, active = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $name, $logoPath, empty($website) ? null : $website, empty($category) ? null : $category, 
                empty($description) ? null : $description, $collaboration_start, $collaboration_end, 
                $featured, $display_order, $active, intval($id)
            ]);

            $_SESSION['flash_success'] = "Partner updated successfully.";
            $this->redirect('/admin/partners');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error updating partner: " . $e->getMessage()];
            $this->redirect('/admin/partners/edit/' . $id);
        }
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM partners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($partner) {
            if (!empty($partner['logo'])) {
                $this->storage->delete($partner['logo']);
            }
            $stmtDel = $db->prepare("DELETE FROM partners WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Partner deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Partner not found.";
        }

        $this->redirect('/admin/partners');
    }
}
