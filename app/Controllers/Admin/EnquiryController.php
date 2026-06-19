<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use Exception;
use PDO;

class EnquiryController extends BaseController {

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM contact_enquiries ORDER BY created_at DESC");
        $enquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/enquiries/index', [
            'title' => 'Contact Enquiries | Admin',
            'enquiries' => $enquiries,
            'active' => 'enquiries'
        ], 'admin');
    }

    public function view(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_enquiries WHERE id = ?");
        $stmt->execute([intval($id)]);
        $enquiry = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$enquiry) {
            $_SESSION['flash_error'] = "Enquiry not found.";
            $this->redirect('/admin/enquiries');
        }

        // Mark as read if currently unread
        if ($enquiry['status'] === 'unread') {
            $stmtUpdate = $db->prepare("UPDATE contact_enquiries SET status = 'read' WHERE id = ?");
            $stmtUpdate->execute([intval($id)]);
            $enquiry['status'] = 'read';
        }

        $this->render('admin/enquiries/view', [
            'title' => 'Enquiry: ' . htmlspecialchars($enquiry['subject']),
            'enquiry' => $enquiry,
            'active' => 'enquiries'
        ], 'admin');
    }

    public function reply(string $id): void {
        $replyContent = trim($_POST['reply_content'] ?? '');

        if (empty($replyContent)) {
            $_SESSION['flash_error'] = "Reply message cannot be empty.";
            $this->redirect('/admin/enquiries/view/' . $id);
        }

        try {
            $db = BaseModel::getConnection();
            $stmt = $db->prepare("SELECT * FROM contact_enquiries WHERE id = ?");
            $stmt->execute([intval($id)]);
            $enquiry = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$enquiry) {
                $_SESSION['flash_error'] = "Enquiry not found.";
                $this->redirect('/admin/enquiries');
            }

            // Update database status
            $stmtUpdate = $db->prepare("
                UPDATE contact_enquiries 
                SET status = 'replied', reply_content = ?, replied_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmtUpdate->execute([$replyContent, intval($id)]);

            // Optional: Send/enqueue response email to user
            try {
                $emailQueue = new \App\Services\EmailQueue();
                $subject = "Re: " . $enquiry['subject'];
                $bodyHtml = "<p>Dear " . htmlspecialchars($enquiry['name']) . ",</p>"
                          . "<p>Thank you for contacting Team Incubation NGO. Below is our response to your query:</p>"
                          . "<blockquote style='border-left: 3px solid #0ea5e9; padding-left: 10px; margin: 15px 0; color: #555;'>"
                          . nl2br(htmlspecialchars($replyContent))
                          . "</blockquote>"
                          . "<p>Best regards,<br>Team Incubation Support</p>";
                
                $emailQueue->enqueue($enquiry['email'], $enquiry['name'], $subject, $bodyHtml);
            } catch (Exception $e) {
                // Ignore email queue failure so we don't break the flow
            }

            $_SESSION['flash_success'] = "Reply sent and recorded successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Error sending reply: " . $e->getMessage();
        }

        $this->redirect('/admin/enquiries/view/' . $id);
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_enquiries WHERE id = ?");
        $stmt->execute([intval($id)]);
        $enquiry = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($enquiry) {
            $stmtDel = $db->prepare("DELETE FROM contact_enquiries WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Enquiry deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Enquiry not found.";
        }

        $this->redirect('/admin/enquiries');
    }
}
