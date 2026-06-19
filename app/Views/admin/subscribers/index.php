<div class="actions-header" style="margin-bottom: 2rem;">
    <p style="color: var(--text-muted); font-size: 0.95rem;">Manage the newsletter distribution registry of verified emails.</p>
</div>

<div class="crud-card">
    <?php if (empty($subscribers)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-users" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No subscribers in the newsletter list yet.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Subscriber Email</th>
                        <th>Subscribed At</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $item): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['email']) ?></strong>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted);">
                                <?= date('d M Y, h:i A', strtotime($item['created_at'])) ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge status-<?= htmlspecialchars($item['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($item['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <form action="/admin/subscribers/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this subscriber from the mailing list?');" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="action-btn-icon delete-btn" title="Remove Subscriber">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
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
    .status-indicator-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-subscribed { background-color: var(--pastel-green); color: #166534; }
    .status-unsubscribed { background-color: var(--pastel-pink); color: #991b1b; }

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
