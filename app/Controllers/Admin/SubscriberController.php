<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use PDO;

class SubscriberController extends BaseController {

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
        $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/subscribers/index', [
            'title' => 'Newsletter Subscribers | Admin',
            'subscribers' => $subscribers,
            'active' => 'subscribers'
        ], 'admin');
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM newsletter_subscribers WHERE id = ?");
        $stmt->execute([intval($id)]);
        $sub = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($sub) {
            $stmtDel = $db->prepare("DELETE FROM newsletter_subscribers WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Subscriber deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Subscriber not found.";
        }

        $this->redirect('/admin/subscribers');
    }
}
