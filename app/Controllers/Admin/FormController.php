<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use Exception;
use PDO;

class FormController extends BaseController {

    public function index(): void {
        $db = BaseModel::getConnection();

        // Fetch events
        $stmtEvents = $db->query("
            SELECT e.id, e.title, COUNT(eq.id) as questions_count 
            FROM events e 
            LEFT JOIN event_questions eq ON e.id = eq.event_id 
            WHERE e.deleted_at IS NULL 
            GROUP BY e.id 
            ORDER BY e.title ASC
        ");
        $events = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);

        // Fetch internship opportunities
        $stmtInternships = $db->query("
            SELECT io.id, io.title, COUNT(iq.id) as questions_count 
            FROM internship_opportunities io 
            LEFT JOIN internship_questions iq ON io.id = iq.internship_opportunity_id 
            GROUP BY io.id 
            ORDER BY io.title ASC
        ");
        $internships = $stmtInternships->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/forms/index', [
            'title' => 'Form Builder | Admin',
            'events' => $events,
            'internships' => $internships,
            'active' => 'forms'
        ], 'admin');
    }

    public function eventQuestions(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT id, title FROM events WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['flash_error'] = "Event not found.";
            $this->redirect('/admin/forms');
        }

        $stmtQ = $db->prepare("SELECT * FROM event_questions WHERE event_id = ? ORDER BY id ASC");
        $stmtQ->execute([intval($id)]);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/forms/questions', [
            'title' => 'Event Form: ' . htmlspecialchars($event['title']),
            'event' => $event,
            'questions' => $questions,
            'type' => 'event',
            'active' => 'forms'
        ], 'admin');
    }

    public function saveEventQuestions(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT id FROM events WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['flash_error'] = "Event not found.";
            $this->redirect('/admin/forms');
        }

        $texts = $_POST['question_text'] ?? [];
        $types = $_POST['question_type'] ?? [];
        $requireds = $_POST['is_required'] ?? [];
        $options = $_POST['options'] ?? [];

        try {
            $db->beginTransaction();

            // Clear previous questions
            $stmtDel = $db->prepare("DELETE FROM event_questions WHERE event_id = ?");
            $stmtDel->execute([intval($id)]);

            // Insert new questions
            $stmtIns = $db->prepare("
                INSERT INTO event_questions (event_id, question_text, question_type, is_required, options) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($texts as $index => $text) {
                $text = trim($text);
                if (empty($text)) continue;

                $qType = $types[$index] ?? 'text';
                $isReq = isset($requireds[$index]) ? 1 : 0;
                $opts = trim($options[$index] ?? '');

                $stmtIns->execute([intval($id), $text, $qType, $isReq, empty($opts) ? null : $opts]);
            }

            $db->commit();
            $_SESSION['flash_success'] = "Form questions saved successfully.";
            $this->redirect('/admin/forms');
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['flash_errors'] = ["Failed to save questions: " . $e->getMessage()];
            $this->redirect('/admin/forms/event/' . $id);
        }
    }

    public function internshipQuestions(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT id, title FROM internship_opportunities WHERE id = ?");
        $stmt->execute([intval($id)]);
        $internship = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$internship) {
            $_SESSION['flash_error'] = "Internship Opportunity not found.";
            $this->redirect('/admin/forms');
        }

        $stmtQ = $db->prepare("SELECT * FROM internship_questions WHERE internship_opportunity_id = ? ORDER BY id ASC");
        $stmtQ->execute([intval($id)]);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/forms/questions', [
            'title' => 'Internship Application Form: ' . htmlspecialchars($internship['title']),
            'internship' => $internship,
            'questions' => $questions,
            'type' => 'internship',
            'active' => 'forms'
        ], 'admin');
    }

    public function saveInternshipQuestions(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT id FROM internship_opportunities WHERE id = ?");
        $stmt->execute([intval($id)]);
        $internship = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$internship) {
            $_SESSION['flash_error'] = "Internship Opportunity not found.";
            $this->redirect('/admin/forms');
        }

        $texts = $_POST['question_text'] ?? [];
        $types = $_POST['question_type'] ?? [];
        $requireds = $_POST['is_required'] ?? [];
        $options = $_POST['options'] ?? [];

        try {
            $db->beginTransaction();

            // Clear previous questions
            $stmtDel = $db->prepare("DELETE FROM internship_questions WHERE internship_opportunity_id = ?");
            $stmtDel->execute([intval($id)]);

            // Insert new questions
            $stmtIns = $db->prepare("
                INSERT INTO internship_questions (internship_opportunity_id, question_text, question_type, is_required, options) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($texts as $index => $text) {
                $text = trim($text);
                if (empty($text)) continue;

                $qType = $types[$index] ?? 'text';
                $isReq = isset($requireds[$index]) ? 1 : 0;
                $opts = trim($options[$index] ?? '');

                $stmtIns->execute([intval($id), $text, $qType, $isReq, empty($opts) ? null : $opts]);
            }

            $db->commit();
            $_SESSION['flash_success'] = "Form questions saved successfully.";
            $this->redirect('/admin/forms');
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['flash_errors'] = ["Failed to save questions: " . $e->getMessage()];
            $this->redirect('/admin/forms/internship/' . $id);
        }
    }
}
