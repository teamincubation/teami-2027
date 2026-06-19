<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage partners, sponsors, and academic institutions displayed in your landing page carousel.</p>
    </div>
    <a href="/admin/partners/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Add Partner</a>
</div>

<div class="crud-card">
    <?php if (empty($partners)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-handshake" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No partners added yet. Click "Add Partner" to set up your first associate.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Logo</th>
                        <th>Partner Name</th>
                        <th>Category</th>
                        <th>Website</th>
                        <th style="width: 100px; text-align: center;">Display Order</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $item): ?>
                        <tr>
                            <td>
                                <div class="logo-preview-box">
                                    <img src="<?= media_url($item['logo']) ?>" alt="Logo" class="logo-thumb">
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['name']) ?></strong>
                                    <?php if ($item['featured']): ?>
                                        <span class="featured-badge" style="width: fit-content; font-size: 0.7rem;"><i class="fa-solid fa-star"></i> Featured</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="category-tag"><?= htmlspecialchars($item['category'] ?? 'General') ?></span>
                            </td>
                            <td>
                                <?php if (!empty($item['website'])): ?>
                                    <a href="<?= htmlspecialchars($item['website']) ?>" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 0.85rem;"><i class="fa-solid fa-up-right-from-square"></i> Visit Website</a>
                                <?php else: ?>
                                    <span style="color: #cbd5e1; font-size: 0.85rem;">None</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <code><?= intval($item['display_order']) ?></code>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($item['active']): ?>
                                    <span class="status-indicator-badge status-published">Active</span>
                                <?php else: ?>
                                    <span class="status-indicator-badge status-draft">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="/admin/partners/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Partner">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/partners/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner?');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Partner">
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
    .logo-preview-box {
        width: 60px;
        height: 60px;
        background: #f8fafc;
        border-radius: 50%;
        border: 1px solid var(--border-glow);
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px;
    }
    .logo-thumb {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .category-tag {
        display: inline-block;
        background: var(--pastel-teal);
        color: #0891b2;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid rgba(38, 181, 209, 0.1);
    }
    .featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: var(--pastel-yellow);
        color: #b45309;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        border: 1px solid rgba(217, 119, 6, 0.15);
    }
    .status-indicator-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-published { background-color: var(--pastel-green); color: #166534; }
    .status-draft { background-color: var(--pastel-yellow); color: #b45309; }

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
