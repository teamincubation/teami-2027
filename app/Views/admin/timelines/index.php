<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage the historical roadmap displayed on the About Us page.</p>
    </div>
    <a href="/admin/timelines/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Add Milestone</a>
</div>

<div class="crud-card">
    <?php if (empty($milestones)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-timeline" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No timeline milestones found. Click "Add Milestone" to create your first entry.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Year</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th style="width: 150px;">Impact Stats</th>
                        <th style="width: 100px; text-align: center;">Display Order</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($milestones as $item): ?>
                        <tr>
                            <td><strong style="color: var(--primary); font-size: 1.1rem;"><?= intval($item['year']) ?></strong></td>
                            <td><strong style="color: var(--text-main);"><?= htmlspecialchars($item['title']) ?></strong></td>
                            <td style="color: var(--text-muted); font-size: 0.875rem; max-width: 300px; line-height: 1.5;"><?= htmlspecialchars($item['description']) ?></td>
                            <td>
                                <?php if (!empty($item['impact_stats'])): ?>
                                    <span class="impact-badge"><i class="fa-solid fa-chart-line"></i> <?= htmlspecialchars($item['impact_stats']) ?></span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1; font-size: 0.85rem;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;"><code><?= intval($item['display_order']) ?></code></td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge <?= $item['active'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $item['active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="/admin/timelines/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Milestone">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/timelines/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this milestone? This action cannot be undone.');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Milestone">
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
    .btn-primary-action {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        text-decoration: none;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(38, 181, 209, 0.2);
        transition: var(--transition);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(38, 181, 209, 0.35);
        filter: brightness(1.05);
    }

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
        font-size: 0.9rem;
    }

    .admin-table th {
        background: #fafbfc;
        padding: 1rem 1.25rem;
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

    .impact-badge {
        background: var(--pastel-blue);
        color: #0369a1;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-indicator-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background-color: var(--pastel-green);
        color: #166534;
    }

    .status-inactive {
        background-color: var(--pastel-pink);
        color: #991b1b;
    }

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

    .edit-btn:hover {
        background-color: var(--pastel-blue);
        color: #0369a1;
    }

    .delete-btn:hover {
        background-color: var(--pastel-pink);
        color: #ef4444;
    }
</style>
