<div class="actions-header" style="margin-bottom: 2rem;">
    <p style="color: var(--text-muted); font-size: 0.95rem;">Review the administrative activity log and security audit trails.</p>
</div>

<div class="crud-card">
    <?php if (empty($logs)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-shield-halved" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No audit log trails registered in the system yet.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 150px;">Action / Trigger</th>
                        <th>User Profile</th>
                        <th>IP Address</th>
                        <th>Details</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <span class="action-tag <?= str_contains(strtolower($log['action']), 'fail') || str_contains(strtolower($log['action']), 'lock') || str_contains(strtolower($log['action']), 'delete') ? 'tag-danger' : 'tag-success' ?>">
                                    <?= htmlspecialchars($log['action']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="admin-profile-cell">
                                    <span class="profile-avatar-mini"><?= strtoupper(substr($log['email'] ?? 'A', 0, 1)) ?></span>
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($log['full_name'] ?? 'System Process') ?></div>
                                        <div style="font-size: 0.775rem; color: var(--text-muted);"><?= htmlspecialchars($log['email'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                            <td class="details-cell" title="<?= htmlspecialchars($log['details'] ?? '') ?>"><?= htmlspecialchars($log['details'] ?? '') ?></td>
                            <td style="font-size: 0.85rem; white-space: nowrap;"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
    .crud-card {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        overflow: hidden;
    }
    .table-container {
        overflow-x: auto;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.95rem;
    }
    .admin-table th {
        background: #fafbfc;
        padding: 0.85rem 1.25rem;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-glow);
    }
    .admin-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-glow);
        vertical-align: middle;
    }
    .admin-table tr:hover {
        background-color: #fafbfc;
    }
    .admin-table tr:last-child td {
        border-bottom: none;
    }
    .action-tag {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .tag-success { background: #dcfce7; color: #15803d; }
    .tag-danger { background: #ffe4e6; color: #b91c1c; }

    .admin-profile-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .profile-avatar-mini {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--pastel-blue);
        color: #0369a1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 0.8rem;
    }
    .details-cell {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.875rem;
        color: var(--text-muted);
    }
</style>
