<div class="actions-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Organize scheduled workshops, educational webinars, and regional convocations.</p>
    </div>
    <a href="/admin/events/create" class="btn-primary-action"><i class="fa-solid fa-plus" style="margin-right: 0.5rem;"></i> Add Event</a>
</div>

<div class="crud-card">
    <?php if (empty($events)): ?>
        <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
            <i class="fa-regular fa-calendar-check" style="font-size: 3rem; color: var(--border-active); margin-bottom: 1rem; display: block;"></i>
            <p>No events scheduled yet. Click "Add Event" to create one.</p>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 120px;">Banner</th>
                        <th>Event Details</th>
                        <th>Type / Mode</th>
                        <th>Venue / Location</th>
                        <th>Schedule</th>
                        <th style="width: 100px; text-align: center;">Seats</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $item): ?>
                        <tr>
                            <td>
                                <div class="banner-preview-box">
                                    <?php if (!empty($item['banner'])): ?>
                                        <img src="<?= media_url($item['banner']) ?>" alt="Banner" class="banner-thumb">
                                    <?php else: ?>
                                        <div style="display: flex; justify-content: center; align-items: center; height: 100%; color: #cbd5e1; font-size: 1.25rem;"><i class="fa-regular fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <strong style="color: var(--text-main); font-size: 0.95rem;"><?= htmlspecialchars($item['title']) ?></strong>
                                    <?php if (!empty($item['project_name'])): ?>
                                        <span style="color: var(--primary); font-size: 0.75rem; font-weight: 600;"><i class="fa-solid fa-hand-holding-heart"></i> Project: <?= htmlspecialchars($item['project_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="category-tag" style="background-color: <?= $item['event_type'] === 'UPCOMING' ? 'var(--pastel-blue)' : 'var(--pastel-yellow)' ?>; color: <?= $item['event_type'] === 'UPCOMING' ? '#0369a1' : '#b45309' ?>;">
                                    <?= htmlspecialchars($item['event_type']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--text-muted);">
                                    <?php if (!empty($item['online_platform'])): ?>
                                        <i class="fa-solid fa-video" style="margin-right: 0.25rem; color: #0284c7;"></i> <?= htmlspecialchars($item['online_platform']) ?>
                                    <?php else: ?>
                                        <i class="fa-solid fa-location-dot" style="margin-right: 0.25rem; color: #e11d48;"></i> <?= htmlspecialchars($item['venue'] ?: ($item['location'] ?: 'Flexible')) ?>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-main); white-space: nowrap;">
                                <i class="fa-regular fa-clock" style="margin-right: 0.25rem; color: var(--text-muted);"></i>
                                <?= date('d M Y, h:i A', strtotime($item['start_date'])) ?>
                            </td>
                            <td style="text-align: center; font-size: 0.875rem;">
                                <strong><?= $item['seat_limit'] ? intval($item['seat_limit']) : '∞' ?></strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-indicator-badge status-<?= htmlspecialchars($item['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($item['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="/admin/events/edit/<?= $item['id'] ?>" class="action-btn-icon edit-btn" title="Edit Event">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form action="/admin/events/delete/<?= $item['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="action-btn-icon delete-btn" title="Delete Event">
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
    .status-draft { background-color: var(--pastel-yellow); color: #b45309; }
    .status-published { background-color: var(--pastel-green); color: #166534; }
    .status-cancelled { background-color: var(--pastel-pink); color: #991b1b; }
    .status-completed { background-color: #e2e8f0; color: #475569; }

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
