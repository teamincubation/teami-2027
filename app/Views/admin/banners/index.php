<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Configure landing page slides, announcement hero banners, and project/event section graphics.</p>
    </div>
    <a href="/admin/banners/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Add Banner</a>
</div>

<div class="crud-card">
    <?php if (empty($banners)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-images" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No banners found. Click "Add Banner" to upload your first graphic slider.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Preview</th>
                        <th>Title & Details</th>
                        <th>Display Location</th>
                        <th>CTA Link</th>
                        <th style="width: 100px; text-align: center;">Display Order</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $item): ?>
                        <tr>
                            <td>
                                <div class="banner-preview-box">
                                    <img src="<?= media_url($item['desktop_image']) ?>" alt="Desktop Banner" class="banner-thumb">
                                    <?php if (!empty($item['mobile_image'])): ?>
                                        <img src="<?= media_url($item['mobile_image']) ?>" alt="Mobile Banner" class="banner-thumb-mobile" title="Mobile Version Available">
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['title'] ?? 'Untitled Slide') ?></strong>
                                    <span style="color: var(--text-muted); font-size: 0.8rem; line-height: 1.4; max-width: 250px;"><?= htmlspecialchars($item['subtitle'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="location-tag"><code><?= htmlspecialchars($item['display_location']) ?></code></span>
                            </td>
                            <td>
                                <?php if (!empty($item['cta_label'])): ?>
                                    <a href="<?= htmlspecialchars($item['cta_url'] ?? '#') ?>" target="_blank" class="cta-preview-badge">
                                        <i class="fa-solid fa-link" style="font-size: 0.75rem;"></i> <?= htmlspecialchars($item['cta_label']) ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #cbd5e1; font-size: 0.85rem;">None</span>
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
                                    <a href="/admin/banners/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Slide">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/banners/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner? The images will be permanently removed.');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Slide">
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
        position: relative;
        width: 110px;
        height: 60px;
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

    .banner-thumb-mobile {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 25px;
        height: 40px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    .location-tag {
        display: inline-block;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.8rem;
    }

    .cta-preview-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: var(--pastel-teal);
        color: #0891b2;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 1px solid rgba(38, 181, 209, 0.15);
    }

    .cta-preview-badge:hover {
        background: var(--primary);
        color: #ffffff;
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
