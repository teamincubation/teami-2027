<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage core NGO projects, educational initiatives, and research programs.</p>
    </div>
    <a href="/admin/projects/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Create Project</a>
</div>

<div class="crud-card">
    <?php if (empty($projects)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-hand-holding-heart" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No projects found. Click "Create Project" to setup your first program.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Banner</th>
                        <th>Project Name</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Timeline</th>
                        <th style="width: 100px; text-align: center;">Featured</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $item): ?>
                        <tr>
                            <td>
                                <div class="banner-preview-box">
                                    <?php if (!empty($item['banner_image'])): ?>
                                        <img src="<?= media_url($item['banner_image']) ?>" alt="Banner" class="banner-thumb">
                                    <?php else: ?>
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; color: #cbd5e1; font-size: 1.25rem;"><i class="fa-regular fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['name']) ?></strong>
                                    <span style="color: var(--text-muted); font-size: 0.8rem; line-height: 1.4; max-width: 250px;"><?= htmlspecialchars($item['short_description'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="category-tag"><?= htmlspecialchars($item['category'] ?? 'General') ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-location-dot" style="margin-right: 0.25rem; color: var(--primary);"></i> <?= htmlspecialchars($item['location'] ?? 'Flexible') ?></span>
                            </td>
                            <td style="font-size: 0.8rem; white-space: nowrap;">
                                <?php if (!empty($item['start_date'])): ?>
                                    <?= date('M Y', strtotime($item['start_date'])) ?> – <?= !empty($item['end_date']) ? date('M Y', strtotime($item['end_date'])) : 'Present' ?>
                                <?php else: ?>
                                    <span style="color: #cbd5e1;">Ongoing</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($item['featured']): ?>
                                    <span class="featured-badge" title="Showcased on Landing Pages"><i class="fa-solid fa-star"></i> Featured</span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1; font-size: 0.85rem;">No</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge status-<?= htmlspecialchars($item['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($item['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="/admin/projects/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Project">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/projects/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to archive this project? It will be hidden from public lists.');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Archive Project">
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

    /* Thumbnail Stack */
    .banner-preview-box {
        width: 100px;
        height: 55px;
        background: #f1f5f9;
        border-radius: 6px;
        border: 1px solid var(--border-glow);
        overflow: hidden;
    }

    .banner-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
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

    .status-planning { background-color: var(--pastel-blue); color: #0369a1; }
    .status-active { background-color: var(--pastel-green); color: #166534; }
    .status-completed { background-color: #e2e8f0; color: #475569; }
    .status-suspended { background-color: var(--pastel-pink); color: #991b1b; }

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
