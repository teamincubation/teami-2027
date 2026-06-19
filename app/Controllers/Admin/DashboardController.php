<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use PDO;

class DashboardController extends BaseController {

    /**
     * Display the Admin Dashboard.
     */
    public function index(): void {
        $db = BaseModel::getConnection();

        // 1. Fetch counts for dashboard stats cards
        $stats = [
            'users' => $this->getCount($db, 'users'),
            'projects' => $this->getCount($db, 'projects'),
            'events' => $this->getCount($db, 'events'),
            'campaigns' => $this->getCount($db, 'campaigns'),
            'certificates' => $this->getCount($db, 'certificates'),
            'pending_internships' => $this->getCount($db, 'internship_applications', "status = 'Submitted'"),
            'pending_volunteers' => $this->getCount($db, 'volunteer_applications', "status = 'submitted'"),
            'unread_enquiries' => $this->getCount($db, 'contact_enquiries', "status = 'unread'")
        ];

        // 2. Fetch recent audit logs (last 10)
        $auditLogsStmt = $db->query("
            SELECT al.*, p.full_name, u.email 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            ORDER BY al.created_at DESC 
            LIMIT 10
        ");
        $recentAuditLogs = $auditLogsStmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Fetch recent certificate verifications (last 10)
        $verificationLogsStmt = $db->query("
            SELECT * 
            FROM certificate_verification_logs 
            ORDER BY verified_at DESC 
            LIMIT 10
        ");
        $recentVerifications = $verificationLogsStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard | Team Incubation',
            'stats' => $stats,
            'recentAuditLogs' => $recentAuditLogs,
            'recentVerifications' => $recentVerifications,
            'active' => 'dashboard'
        ], 'admin'); // Using admin layout
    }

    /**
     * Helper to get count of rows in a table.
     */
    private function getCount(PDO $db, string $table, ?string $where = null): int {
        $sql = "SELECT COUNT(*) FROM `{$table}`";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $stmt = $db->query($sql);
        return (int) $stmt->fetchColumn();
    }
}
