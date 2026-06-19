<div class="actions-header" style="margin-bottom: 2rem;">
    <p style="color: var(--text-muted); font-size: 0.95rem;">Build and customize the dynamic input questions for your Event registrations and Internship applications.</p>
</div>

<div class="forms-split-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <!-- Event Forms -->
    <div class="crud-card">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-glow); background: #fafbfc;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><i class="fa-regular fa-calendar-check" style="margin-right: 0.5rem; color: var(--primary);"></i> Event Registration Forms</h3>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th style="width: 150px; text-align: center;">Questions Configured</th>
                        <th style="width: 150px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 2rem;">No events available. Create an event first to customize its questions.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $item): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--text-main);"><?= htmlspecialchars($item['title']) ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="category-tag" style="background-color: <?= $item['questions_count'] > 0 ? 'var(--pastel-green)' : 'var(--pastel-blue)' ?>; color: <?= $item['questions_count'] > 0 ? '#166534' : '#0369a1' ?>;">
                                        <?= intval($item['questions_count']) ?> Questions
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/admin/forms/event/<?= $item['id'] ?>" class="btn-builder" style="text-decoration:none;"><i class="fa-solid fa-gears" style="margin-right: 0.4rem;"></i> Configure Form</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Internship Forms -->
    <div class="crud-card" style="margin-top: 1rem;">
        <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-glow); background: #fafbfc;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--text-main); font-size: 1.1rem;"><i class="fa-solid fa-user-graduate" style="margin-right: 0.5rem; color: var(--secondary);"></i> Internship Application Forms</h3>
        </div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Opportunity Title</th>
                        <th style="width: 150px; text-align: center;">Questions Configured</th>
                        <th style="width: 150px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($internships)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 2rem;">No internship opportunities available.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($internships as $item): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--text-main);"><?= htmlspecialchars($item['title']) ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="category-tag" style="background-color: <?= $item['questions_count'] > 0 ? 'var(--pastel-green)' : 'var(--pastel-blue)' ?>; color: <?= $item['questions_count'] > 0 ? '#166534' : '#0369a1' ?>;">
                                        <?= intval($item['questions_count']) ?> Questions
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="/admin/forms/internship/<?= $item['id'] ?>" class="btn-builder" style="text-decoration:none;"><i class="fa-solid fa-gears" style="margin-right: 0.4rem;"></i> Configure Form</a>
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
</style>
