<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use PDO;

class TimelineController extends BaseController {

    /**
     * List all milestones.
     */
    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT * FROM legacy_milestones ORDER BY year DESC, display_order ASC");
        $milestones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/timelines/index', [
            'title' => 'Timeline Management | Admin',
            'milestones' => $milestones,
            'active' => 'timelines'
        ], 'admin');
    }

    /**
     * Show create milestone form.
     */
    public function create(): void {
        $this->render('admin/timelines/create', [
            'title' => 'Add Milestone | Admin',
            'active' => 'timelines'
        ], 'admin');
    }

    /**
     * Store new milestone.
     */
    public function store(): void {
        $year = intval($_POST['year'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $impact_stats = trim($_POST['impact_stats'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if ($year < 2000 || $year > 2100) {
            $errors[] = "Please enter a valid year between 2000 and 2100.";
        }
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($description)) {
            $errors[] = "Description is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/timelines/create');
        }

        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            INSERT INTO legacy_milestones (year, title, description, impact_stats, display_order, active)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$year, $title, $description, $impact_stats, $display_order, $active]);

        $_SESSION['flash_success'] = "Milestone added successfully.";
        $this->redirect('/admin/timelines');
    }

    /**
     * Show edit milestone form.
     */
    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM legacy_milestones WHERE id = ?");
        $stmt->execute([intval($id)]);
        $milestone = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$milestone) {
            $_SESSION['flash_error'] = "Milestone not found.";
            $this->redirect('/admin/timelines');
        }

        $this->render('admin/timelines/edit', [
            'title' => 'Edit Milestone | Admin',
            'milestone' => $milestone,
            'active' => 'timelines'
        ], 'admin');
    }

    /**
     * Update milestone.
     */
    public function update(string $id): void {
        $year = intval($_POST['year'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $impact_stats = trim($_POST['impact_stats'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 0);
        $active = isset($_POST['active']) ? 1 : 0;

        $errors = [];
        if ($year < 2000 || $year > 2100) {
            $errors[] = "Please enter a valid year between 2000 and 2100.";
        }
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($description)) {
            $errors[] = "Description is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/timelines/edit/' . $id);
        }

        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            UPDATE legacy_milestones 
            SET year = ?, title = ?, description = ?, impact_stats = ?, display_order = ?, active = ?
            WHERE id = ?
        ");
        $stmt->execute([$year, $title, $description, $impact_stats, $display_order, $active, intval($id)]);

        $_SESSION['flash_success'] = "Milestone updated successfully.";
        $this->redirect('/admin/timelines');
    }

    /**
     * Delete milestone.
     */
    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("DELETE FROM legacy_milestones WHERE id = ?");
        $stmt->execute([intval($id)]);

        $_SESSION['flash_success'] = "Milestone deleted successfully.";
        $this->redirect('/admin/timelines');
    }
}
