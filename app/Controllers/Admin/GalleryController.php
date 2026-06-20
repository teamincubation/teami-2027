<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class GalleryController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    /**
     * List all gallery items.
     */
    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM gallery ORDER BY display_order ASC, created_at DESC");
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/gallery/index', [
            'title' => 'Photo Gallery Management | Admin',
            'photos' => $photos,
            'active' => 'gallery'
        ], 'admin');
    }

    /**
     * Show create form.
     */
    public function create(): void {
        $this->render('admin/gallery/create', [
            'title' => 'Add Photo to Gallery | Admin',
            'active' => 'gallery'
        ], 'admin');
    }

    /**
     * Store new gallery item.
     */
    public function store(): void {
        $title = trim($_POST['title'] ?? '');
        $caption = trim($_POST['caption'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if (empty($_FILES['image']['name'])) {
            $errors[] = "Image file is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/gallery/create');
        }

        try {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $imagePath = $this->storage->store($_FILES['image'], 'gallery', $allowedMimes);

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO gallery (title, caption, image_path, display_order, active)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                empty($title) ? null : $title,
                empty($caption) ? null : $caption,
                $imagePath,
                $display_order,
                $active
            ]);

            $_SESSION['flash_success'] = "Image uploaded to gallery successfully.";
            $this->redirect('/admin/gallery');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Upload failed: " . $e->getMessage()];
            $this->redirect('/admin/gallery/create');
        }
    }

    /**
     * Show edit form.
     */
    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([intval($id)]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$photo) {
            $_SESSION['flash_error'] = "Gallery item not found.";
            $this->redirect('/admin/gallery');
        }

        $this->render('admin/gallery/edit', [
            'title' => 'Edit Gallery Photo | Admin',
            'photo' => $photo,
            'active' => 'gallery'
        ], 'admin');
    }

    /**
     * Update gallery item.
     */
    public function update(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([intval($id)]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$photo) {
            $_SESSION['flash_error'] = "Gallery item not found.";
            $this->redirect('/admin/gallery');
        }

        $title = trim($_POST['title'] ?? '');
        $caption = trim($_POST['caption'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        try {
            $imagePath = $photo['image_path'];

            // Replace image if uploaded
            if (!empty($_FILES['image']['name'])) {
                $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                $imagePath = $this->storage->store($_FILES['image'], 'gallery', $allowedMimes);
                
                // Delete old image
                if (!empty($photo['image_path'])) {
                    $this->storage->delete($photo['image_path']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE gallery 
                SET title = ?, caption = ?, image_path = ?, display_order = ?, active = ?
                WHERE id = ?
            ");
            $stmtUpdate->execute([
                empty($title) ? null : $title,
                empty($caption) ? null : $caption,
                $imagePath,
                $display_order,
                $active,
                intval($id)
            ]);

            $_SESSION['flash_success'] = "Gallery item updated successfully.";
            $this->redirect('/admin/gallery');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Update failed: " . $e->getMessage()];
            $this->redirect('/admin/gallery/edit/' . $id);
        }
    }

    /**
     * Delete gallery item.
     */
    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([intval($id)]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photo) {
            if (!empty($photo['image_path'])) {
                $this->storage->delete($photo['image_path']);
            }
            $stmtDel = $db->prepare("DELETE FROM gallery WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Gallery photo deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Gallery photo not found.";
        }

        $this->redirect('/admin/gallery');
    }
}
