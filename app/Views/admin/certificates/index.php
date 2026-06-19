<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Look up, issue, and delete authenticated academic and internship certificates.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="/admin/certificates/import" class="btn-builder" style="text-decoration:none; padding: 0.6rem 1rem;"><i class="fa-solid fa-file-import" style="margin-right: 0.5rem;"></i> Bulk CSV Importer</a>
        <a href="/admin/certificates/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Issue Certificate</a>
    </div>
</div>

<div class="crud-card">
    <?php if (empty($certificates)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-award" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No certificates registered in the system. Click "Issue Certificate" or use the CSV Importer.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Certificate No.</th>
                        <th>Holder Name</th>
                        <th>Type</th>
                        <th>Programme / Duration</th>
                        <th>Issued Date</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($certificates as $item): ?>
                        <tr>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <strong style="color: var(--text-main); font-family: monospace; font-size: 0.95rem;"><?= htmlspecialchars($item['certificate_number']) ?></strong>
                                    <?php if (!empty($item['import_batch_id'])): ?>
                                        <span style="font-size: 0.7rem; color: var(--text-muted);">Batch: <?= htmlspecialchars($item['import_batch_id']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['holder_name']) ?></strong>
                            </td>
                            <td>
                                <span class="category-tag"><?= htmlspecialchars($item['type_name']) ?></span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($item['associated_programme'] ?? 'General') ?></span>
                            </td>
                            <td style="font-size: 0.85rem; color: var(--text-muted); white-space: nowrap;">
                                <?= date('d M Y', strtotime($item['issued_date'])) ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge status-<?= strtolower($item['status']) ?>">
                                    <?= htmlspecialchars($item['status']) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <a href="<?= media_url($item['file_path']) ?>" target="_blank" class="action-btn-icon edit-btn" title="View PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                    <a href="/admin/certificates/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Record">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/certificates/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate? This action is permanent.');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Certificate">
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
    .status-indicator-badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .status-active { background-color: var(--pastel-green); color: #166534; }
    .status-revoked { background-color: var(--pastel-pink); color: #991b1b; }
    .status-expired { background-color: #f1f5f9; color: #475569; }

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
    .btn-builder {
        background-color: #f1f5f9;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 8px;
        font-size: 0.9rem;
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
</style>
