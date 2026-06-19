<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use App\Services\FileStorage;
use Exception;
use PDO;

class EventController extends BaseController {

    private FileStorage $storage;

    public function __construct() {
        $this->storage = new FileStorage();
    }

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("
            SELECT e.*, p.name as project_name 
            FROM events e 
            LEFT JOIN projects p ON e.project_id = p.id 
            WHERE e.deleted_at IS NULL 
            ORDER BY e.start_date DESC
        ");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/events/index', [
            'title' => 'Events Management | Admin',
            'events' => $events,
            'active' => 'events'
        ], 'admin');
    }

    public function create(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("SELECT id, name FROM projects WHERE deleted_at IS NULL ORDER BY name ASC");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/events/create', [
            'title' => 'Add Event | Admin',
            'projects' => $projects,
            'active' => 'events'
        ], 'admin');
    }

    public function store(): void {
        $title = trim($_POST['title'] ?? '');
        $project_id = !empty($_POST['project_id']) ? intval($_POST['project_id']) : null;
        $event_type = trim($_POST['event_type'] ?? 'UPCOMING');
        $description = trim($_POST['description'] ?? '');
        $venue = trim($_POST['venue'] ?? '');
        $online_platform = trim($_POST['online_platform'] ?? '');
        $meeting_link = trim($_POST['meeting_link'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $start_date = trim($_POST['start_date'] ?? '');
        $end_date = trim($_POST['end_date'] ?? '');
        $reg_open_date = !empty($_POST['reg_open_date']) ? trim($_POST['reg_open_date']) : null;
        $reg_close_date = !empty($_POST['reg_close_date']) ? trim($_POST['reg_close_date']) : null;
        $seat_limit = !empty($_POST['seat_limit']) ? intval($_POST['seat_limit']) : null;
        $waiting_list_enabled = isset($_POST['waiting_list_enabled']) ? 1 : 0;
        $is_free = isset($_POST['is_free']) ? 1 : 0;
        $fee = !empty($_POST['fee']) ? floatval($_POST['fee']) : 0.00;
        $eligibility = trim($_POST['eligibility'] ?? '');
        $certificate_eligible = isset($_POST['certificate_eligible']) ? 1 : 0;
        $coordinators = trim($_POST['coordinators'] ?? '');
        $speakers = trim($_POST['speakers'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');

        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($start_date)) {
            $errors[] = "Start Date/Time is required.";
        }
        if (empty($end_date)) {
            $errors[] = "End Date/Time is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/events/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-')) . '-' . time();

        try {
            $bannerPath = null;
            if (!empty($_FILES['banner']['name'])) {
                $bannerPath = $this->storage->store($_FILES['banner'], 'events', ['image/jpeg', 'image/png', 'image/webp']);
            }

            $db = BaseModel::getConnection();
            $stmt = $db->prepare("
                INSERT INTO events (
                    project_id, title, slug, event_type, description, banner, venue, online_platform, 
                    meeting_link, location, start_date, end_date, reg_open_date, reg_close_date, 
                    seat_limit, waiting_list_enabled, is_free, fee, eligibility, certificate_eligible, 
                    coordinators, speakers, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $project_id, $title, $slug, $event_type, $description, $bannerPath, $venue, $online_platform,
                $meeting_link, $location, $start_date, $end_date, $reg_open_date, $reg_close_date,
                $seat_limit, $waiting_list_enabled, $is_free, $fee, $eligibility, $certificate_eligible,
                $coordinators, $speakers, $status
            ]);

            $_SESSION['flash_success'] = "Event created successfully.";
            $this->redirect('/admin/events');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error saving event: " . $e->getMessage()];
            $this->redirect('/admin/events/create');
        }
    }

    public function edit(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['flash_error'] = "Event not found.";
            $this->redirect('/admin/events');
        }

        $stmtProj = $db->query("SELECT id, name FROM projects WHERE deleted_at IS NULL ORDER BY name ASC");
        $projects = $stmtProj->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/events/edit', [
            'title' => 'Edit Event | Admin',
            'event' => $event,
            'projects' => $projects,
            'active' => 'events'
        ], 'admin');
    }

    public function update(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            $_SESSION['flash_error'] = "Event not found.";
            $this->redirect('/admin/events');
        }

        $title = trim($_POST['title'] ?? '');
        $project_id = !empty($_POST['project_id']) ? intval($_POST['project_id']) : null;
        $event_type = trim($_POST['event_type'] ?? 'UPCOMING');
        $description = trim($_POST['description'] ?? '');
        $venue = trim($_POST['venue'] ?? '');
        $online_platform = trim($_POST['online_platform'] ?? '');
        $meeting_link = trim($_POST['meeting_link'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $start_date = trim($_POST['start_date'] ?? '');
        $end_date = trim($_POST['end_date'] ?? '');
        $reg_open_date = !empty($_POST['reg_open_date']) ? trim($_POST['reg_open_date']) : null;
        $reg_close_date = !empty($_POST['reg_close_date']) ? trim($_POST['reg_close_date']) : null;
        $seat_limit = !empty($_POST['seat_limit']) ? intval($_POST['seat_limit']) : null;
        $waiting_list_enabled = isset($_POST['waiting_list_enabled']) ? 1 : 0;
        $is_free = isset($_POST['is_free']) ? 1 : 0;
        $fee = !empty($_POST['fee']) ? floatval($_POST['fee']) : 0.00;
        $eligibility = trim($_POST['eligibility'] ?? '');
        $certificate_eligible = isset($_POST['certificate_eligible']) ? 1 : 0;
        $coordinators = trim($_POST['coordinators'] ?? '');
        $speakers = trim($_POST['speakers'] ?? '');
        $status = trim($_POST['status'] ?? 'draft');

        $errors = [];
        if (empty($title)) {
            $errors[] = "Title is required.";
        }
        if (empty($start_date)) {
            $errors[] = "Start Date/Time is required.";
        }
        if (empty($end_date)) {
            $errors[] = "End Date/Time is required.";
        }

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/admin/events/edit/' . $id);
        }

        try {
            $bannerPath = $event['banner'];
            if (!empty($_FILES['banner']['name'])) {
                $bannerPath = $this->storage->store($_FILES['banner'], 'events', ['image/jpeg', 'image/png', 'image/webp']);
                if (!empty($event['banner'])) {
                    $this->storage->delete($event['banner']);
                }
            }

            $stmtUpdate = $db->prepare("
                UPDATE events 
                SET project_id = ?, title = ?, event_type = ?, description = ?, banner = ?, venue = ?, 
                    online_platform = ?, meeting_link = ?, location = ?, start_date = ?, end_date = ?, 
                    reg_open_date = ?, reg_close_date = ?, seat_limit = ?, waiting_list_enabled = ?, 
                    is_free = ?, fee = ?, eligibility = ?, certificate_eligible = ?, coordinators = ?, 
                    speakers = ?, status = ?
                WHERE id = ?
            ");

            $stmtUpdate->execute([
                $project_id, $title, $event_type, $description, $bannerPath, $venue,
                $online_platform, $meeting_link, $location, $start_date, $end_date,
                $reg_open_date, $reg_close_date, $seat_limit, $waiting_list_enabled,
                $is_free, $fee, $eligibility, $certificate_eligible, $coordinators,
                $speakers, $status, intval($id)
            ]);

            $_SESSION['flash_success'] = "Event updated successfully.";
            $this->redirect('/admin/events');
        } catch (Exception $e) {
            $_SESSION['flash_errors'] = ["Error updating event: " . $e->getMessage()];
            $this->redirect('/admin/events/edit/' . $id);
        }
    }

    public function delete(string $id): void {
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([intval($id)]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            $stmtDel = $db->prepare("UPDATE events SET deleted_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmtDel->execute([intval($id)]);
            $_SESSION['flash_success'] = "Event deleted successfully.";
        } else {
            $_SESSION['flash_error'] = "Event not found.";
        }

        $this->redirect('/admin/events');
    }
}
