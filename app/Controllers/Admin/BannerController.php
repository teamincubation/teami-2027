<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class BannerController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    /**
     * List all banners.
     */
    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM banners ORDER BY display_location ASC, display_order ASC");
        $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/banners/index', [
            'title' => 'Banner Management | Admin',
            'banners' => $banners,
            'active' => 'banners'
        ], 'admin');
    }

    /**
     * Show create banner form.
     */
    public function create(): void {
        $this->render('admin/banners/create', [
            'title' => 'Add Banner | Admin',
            'active' => 'banners'
        ], 'admin');
    }

    /**
     * Store new banner.
     */
    public function store(): void {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $cta_label = trim($_POST['cta_label'] ?? '');
        $cta_url = trim($_POST['cta_url'] ?? '');
        $display_location = trim($_POST['display_location'] ?? 'home_hero');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];

        // Validation
        if (empty($_FILES['desktop_image']['name'])) {
            $errors[] = "Desktop Image is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/banners/create');
        }

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            
            // Upload Desktop Image
            $desktopImage = $this->storage->store($_FILES['desktop_image'], 'banners', $allowedMimes);
            
            // Upload Mobile Image if provided
            $mobileImage = null;
            if (!empty($_FILES['mobile_image']['name'])) {
                $mobileImage = $this->storage->store($_FILES['mobile_image'], 'banners', $allowedMimes);
            }

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO banners (title, subtitle, desktop_image, mobile_image, cta_label, cta_url, display_location, display_order, active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                empty($title) ? null : $title,
                empty($subtitle) ? null : $subtitle,
                $desktopImage,
                $mobileImage,
                empty($cta_label) ? null : $cta_label,
                empty($cta_url) ? null : $cta_url,
                $display_location,
                $display_order,
                $active
            ]);

            $_SESSION['flash_success'] = "Banner slide created successfully.";
            $this->redirect('/admin/banners');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Upload failed: " . $e->getMessage()];
            $this->redirect('/admin/banners/create');
        }
    }

    /**
     * Show edit banner form.
     */
    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$banner) {
            $_SESSION['flash_error'] = "Banner slide not found.";
            $this->redirect('/admin/banners');
        }

        $this->render('admin/banners/edit', [
            'title' => 'Edit Banner | Admin',
            'banner' => $banner,
            'active' => 'banners'
        ], 'admin');
    }

    /**
     * Update banner.
     */
    public function update(string $id): void {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $cta_label = trim($_POST['cta_label'] ?? '');
        $cta_url = trim($_POST['cta_url'] ?? '');
        $display_location = trim($_POST['display_location'] ?? 'home_hero');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$banner) {
            $_SESSION['flash_error'] = "Banner slide not found.";
            $this->redirect('/admin/banners');
        }

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $desktopImage = $banner['desktop_image'];
            $mobileImage = $banner['mobile_image'];

            // Handle Desktop Image replacement
            if (!empty($_FILES['desktop_image']['name'])) {
                $desktopImage = $this->storage->store($_FILES['desktop_image'], 'banners', $allowedMimes);
                if (!empty($banner['desktop_image'])) {
                    $this->storage->delete($banner['desktop_image']);
                }
            }

            // Handle Mobile Image replacement
            if (!empty($_FILES['mobile_image']['name'])) {
                $mobileImage = $this->storage->store($_FILES['mobile_image'], 'banners', $allowedMimes);
                if (!empty($banner['mobile_image'])) {
                    $this->storage->delete($banner['mobile_image']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE banners 
                SET title = ?, subtitle = ?, desktop_image = ?, mobile_image = ?, cta_label = ?, cta_url = ?, display_location = ?, display_order = ?, active = ?
                WHERE id = ?
            ");
            $stmtUpdate->execute([
                empty($title) ? null : $title,
                empty($subtitle) ? null : $subtitle,
                $desktopImage,
                $mobileImage,
                empty($cta_label) ? null : $cta_label,
                empty($cta_url) ? null : $cta_url,
                $display_location,
                $display_order,
                $active,
                intval($id)
            ]);

            $_SESSION['flash_success'] = "Banner slide updated successfully.";
            $this->redirect('/admin/banners');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Update failed: " . $e->getMessage()];
            $this->redirect('/admin/banners/edit/' . $id);
        }
    }

    /**
     * Delete banner.
     */
    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([intval($id)]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            // Delete actual images
            if (!empty($banner['desktop_image'])) {
                $this->storage->delete($banner['desktop_image']);
            }
            if (!empty($banner['mobile_image'])) {
                $this->storage->delete($banner['mobile_image']);
            }

            // Delete database row
            $stmtDel = $db->prepare("DELETE FROM banners WHERE id = ?");
            $stmtDel->execute([intval($id)]);

            $_SESSION['flash_success'] = "Banner slide deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Banner slide not found.";
        }

        $this->redirect('/admin/banners');
    }
}
