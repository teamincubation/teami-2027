<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use Exception;
use PDO;

class IncubantController extends BaseController {

    public function index(): void {
        $db = BaseModel::getConnection();

        // Fetch internship applications
        $stmtInterns = $db->query("
            SELECT ia.id, ia.status, ia.created_at, io.title as opportunity_title, 
                   u.email, p.full_name, p.mobile 
            FROM internship_applications ia 
            JOIN internship_opportunities io ON ia.internship_opportunity_id = io.id 
            JOIN users u ON ia.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            ORDER BY ia.created_at DESC
        ");
        $internships = $stmtInterns->fetchAll(PDO::FETCH_ASSOC);

        // Fetch volunteer applications
        $stmtVolunteers = $db->query("
            SELECT va.id, va.status, va.created_at, 
                   u.email, p.full_name, p.mobile 
            FROM volunteer_applications va 
            JOIN users u ON va.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            ORDER BY va.created_at DESC
        ");
        $volunteers = $stmtVolunteers->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/incubants/index', [
            'title' => 'Incubant Portal | Admin',
            'internships' => $internships,
            'volunteers' => $volunteers,
            'active' => 'incubants'
        ], 'admin');
    }

    public function viewInternship(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            SELECT ia.*, io.title as opportunity_title, 
                   u.email, p.full_name, p.mobile, p.gender, p.dob, p.address, p.education, p.institution
            FROM internship_applications ia 
            JOIN internship_opportunities io ON ia.internship_opportunity_id = io.id 
            JOIN users u ON ia.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            WHERE ia.id = ?
        ");
        $stmt->execute([intval($id)]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $_SESSION['flash_error'] = "Application not found.";
            $this->redirect('/admin/incubants');
        }

        $this->render('admin/incubants/view-internship', [
            'title' => 'Internship Application Details',
            'app' => $application,
            'active' => 'incubants'
        ], 'admin');
    }

    public function viewVolunteer(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            SELECT va.*, 
                   u.email, p.full_name, p.mobile, p.gender, p.dob, p.address, p.education, p.institution
            FROM volunteer_applications va 
            JOIN users u ON va.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            WHERE va.id = ?
        ");
        $stmt->execute([intval($id)]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $_SESSION['flash_error'] = "Application not found.";
            $this->redirect('/admin/incubants');
        }

        $this->render('admin/incubants/view-volunteer', [
            'title' => 'Volunteer Application Details',
            'app' => $application,
            'active' => 'incubants'
        ], 'admin');
    }

    public function statusInternship(string $id): void {
        $status = trim($_POST['status'] ?? '');
        $feedback = trim($_POST['supervisor_feedback'] ?? '');
        $rating = !empty($_POST['performance_rating']) ? intval($_POST['performance_rating']) : null;
        $internId = trim($_POST['intern_id'] ?? '');

        if (empty($status)) {
            $_SESSION['flash_error'] = "Status is required.";
            $this->redirect('/admin/incubants/view-internship/' . $id);
        }

        try {
            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                UPDATE internship_applications 
                SET status = ?, supervisor_feedback = ?, performance_rating = ?, intern_id = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $status, 
                empty($feedback) ? null : $feedback, 
                $rating, 
                empty($internId) ? null : $internId, 
                intval($id)
            ]);

            $_SESSION['flash_success'] = "Application status updated successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Failed to update status: " . $e->getMessage();
        }

        $this->redirect('/admin/incubants/view-internship/' . $id);
    }

    public function statusVolunteer(string $id): void {
        $status = trim($_POST['status'] ?? '');

        if (empty($status)) {
            $_SESSION['flash_error'] = "Status is required.";
            $this->redirect('/admin/incubants/view-volunteer/' . $id);
        }

        try {
            $db = BaseModel::getConnection();
            $stmt = $db->prepare("UPDATE volunteer_applications SET status = ? WHERE id = ?");
            $stmt->execute([$status, intval($id)]);

            $_SESSION['flash_success'] = "Application status updated successfully.";
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Failed to update status: " . $e->getMessage();
        }

        $this->redirect('/admin/incubants/view-volunteer/' . $id);
    }
}
