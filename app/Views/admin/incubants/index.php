<div class="actions-header" style="margin-bottom: 2rem;">
    <p style="color: var(--text-muted); font-size: 0.95rem;">Review and process candidate applications for internships and volunteer roles.</p>
</div>

<div class="forms-split-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <!-- Internship Applications -->
    <div class="crud-card">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-glow); background: #fafbfc;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><i class="fa-solid fa-user-graduate" style="margin-right: 0.5rem; color: var(--primary);"></i> Internship Applications</h3>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Opportunity</th>
                        <th>Mobile</th>
                        <th>Submitted At</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($internships)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No internship applications submitted yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($internships as $item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <strong style="color: var(--text-main);"><?= htmlspecialchars($item['full_name'] ?: 'Pending Profile') ?></strong>
                                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($item['email']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 0.9rem; color: var(--text-main);"><?= htmlspecialchars($item['opportunity_title']) ?></span>
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($item['mobile'] ?? 'N/A') ?></span>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?= date('d M Y, h:i A', strtotime($item['created_at'])) ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-indicator-badge status-<?= strtolower(str_replace(' ', '-', $item['status'])) ?>">
                                        <?= htmlspecialchars($item['status']) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/admin/incubants/view-internship/<?= $item['id'] ?>" class="btn-builder" style="text-decoration:none;"><i class="fa-regular fa-eye" style="margin-right: 0.4rem;"></i> Review Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Volunteer Applications -->
    <div class="crud-card" style="margin-top: 1rem;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-glow); background: #fafbfc;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><i class="fa-solid fa-handshake-angle" style="margin-right: 0.5rem; color: var(--secondary);"></i> Volunteer Signups</h3>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Mobile</th>
                        <th>Submitted At</th>
                        <th style="width: 150px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($volunteers)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No volunteer applications submitted yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($volunteers as $item): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <strong style="color: var(--text-main);"><?= htmlspecialchars($item['full_name'] ?: 'Pending Profile') ?></strong>
                                        <span style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($item['email']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($item['mobile'] ?? 'N/A') ?></span>
                                </td>
                                <td style="font-size: 0.8rem; color: var(--text-muted);">
                                    <?= date('d M Y, h:i A', strtotime($item['created_at'])) ?>
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-indicator-badge status-<?= strtolower($item['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($item['status'])) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/admin/incubants/view-volunteer/<?= $item['id'] ?>" class="btn-builder" style="text-decoration:none;"><i class="fa-regular fa-eye" style="margin-right: 0.4rem;"></i> Review Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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
    .category-tag {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        border: 1px solid rgba(38, 181, 209, 0.1);
    }
    .btn-builder {
        background-color: #f1f5f9;
        color: var(--text-main);
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        border: 1px solid var(--border-glow);
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
    .status-submitted { background-color: var(--pastel-blue); color: #0369a1; }
    .status-under-review { background-color: var(--pastel-yellow); color: #b45309; }
    .status-shortlisted { background-color: var(--pastel-purple); color: #6b21a8; }
    .status-selected, .status-approved { background-color: var(--pastel-green); color: #166534; }
    .status-rejected { background-color: var(--pastel-pink); color: #991b1b; }
</style>
