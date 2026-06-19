<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaseModel;
use PDO;

class AuditLogController extends BaseController {

    public function index(): void {
        $db = BaseModel::getConnection();
        $stmt = $db->query("
            SELECT al.*, p.full_name, u.email 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            LEFT JOIN profiles p ON p.user_id = u.id 
            ORDER BY al.created_at DESC 
            LIMIT 100
        ");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('admin/audit-logs/index', [
            'title' => 'Security Audit Logs | Admin',
            'logs' => $logs,
            'active' => 'audit_logs'
        ], 'admin');
    }
}
