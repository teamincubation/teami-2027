<div class="actions-header" style="margin-bottom: 2rem;">
    <p style="color: var(--text-muted); font-size: 0.95rem;">Review and reply to messages sent by visitors through the public contact forms.</p>
</div>

<div class="crud-card">
    <?php if (empty($enquiries)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-regular fa-envelope-open" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No contact inquiries received yet.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Mobile</th>
                        <th>Received At</th>
                        <th style="width: 120px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enquiries as $item): ?>
                        <tr style="background-color: <?= $item['status'] === 'unread' ? '#f8fafc' : 'inherit' ?>;">
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <strong style="color: var(--text-main); font-weight: <?= $item['status'] === 'unread' ? '700' : '600' ?>;"><?= htmlspecialchars($item['name']) ?></strong>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($item['email']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.925rem; font-weight: <?= $item['status'] === 'unread' ? '600' : '500' ?>; color: var(--text-main);"><?= htmlspecialchars($item['subject']) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($item['mobile'] ?? 'N/A') ?></span>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted);">
                                <?= date('d M Y, h:i A', strtotime($item['created_at'])) ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge status-<?= htmlspecialchars($item['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($item['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="/admin/enquiries/view/<?= $item['id'] ?>" class="btn-builder" style="text-decoration:none; padding: 0.4rem 0.8rem; font-size:0.8rem;"><i class="fa-regular fa-envelope-open" style="margin-right: 0.3rem;"></i> View & Reply</a>
                                    <form action="/admin/enquiries/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry?');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Enquiry">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
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
    .btn-builder {
        background-color: #f1f5f9;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 6px;
        border: 1px solid var(--border-glow);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
    }
    .btn-builder:hover {
        background-color: var(--pastel-blue);
        color: #0369a1;
        border-color: var(--border-active);
    }
    .status-indicator-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-unread { background-color: var(--pastel-pink); color: #991b1b; }
    .status-read { background-color: var(--pastel-blue); color: #0369a1; }
    .status-replied { background-color: var(--pastel-green); color: #166534; }

    .action-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        transition: var(--transition);
        background: #f1f5f9;
        color: var(--text-muted);
    }
    .delete-btn:hover {
        background-color: var(--pastel-pink);
        color: #ef4444;
    }
</style>
