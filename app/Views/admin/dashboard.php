<!-- Dashboard Stats Grid -->
<div class="stats-grid">
    <!-- Stat Card 1 -->
    <div class="stat-card card-primary">
        <div class="stat-header">
            <span class="stat-title">Active Projects</span>
            <div class="stat-icon icon-primary"><i class="fa-solid fa-hand-holding-heart"></i></div>
        </div>
        <div class="stat-value"><?= intval($stats['projects'] ?? 0) ?></div>
        <div class="stat-footer">
            <a href="/admin/projects" class="stat-action">Manage Projects <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="stat-card card-secondary">
        <div class="stat-header">
            <span class="stat-title">NGO Events</span>
            <div class="stat-icon icon-secondary"><i class="fa-regular fa-calendar-check"></i></div>
        </div>
        <div class="stat-value"><?= intval($stats['events'] ?? 0) ?></div>
        <div class="stat-footer">
            <a href="/admin/events" class="stat-action">Schedule Events <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="stat-card card-purple">
        <div class="stat-header">
            <span class="stat-title">Campaigns Run</span>
            <div class="stat-icon icon-purple"><i class="fa-solid fa-bullhorn"></i></div>
        </div>
        <div class="stat-value"><?= intval($stats['campaigns'] ?? 0) ?></div>
        <div class="stat-footer">
            <a href="/admin/campaigns" class="stat-action">Launch Campaigns <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="stat-card card-green">
        <div class="stat-header">
            <span class="stat-title">Certificates Issued</span>
            <div class="stat-icon icon-green"><i class="fa-solid fa-award"></i></div>
        </div>
        <div class="stat-value"><?= intval($stats['certificates'] ?? 0) ?></div>
        <div class="stat-footer">
            <a href="/admin/certificates" class="stat-action">Lookup System <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<!-- Pending Actions and Alerts Section -->
<div class="alerts-section">
    <div class="section-title">
        <i class="fa-solid fa-circle-exclamation" style="color: #d97706; margin-right: 0.5rem;"></i>
        Pending Administrative Tasks
    </div>
    <div class="alerts-grid">
        <!-- Task 1: Internship Applications -->
        <div class="task-box">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="task-icon icon-amber"><i class="fa-solid fa-file-contract"></i></div>
                <div>
                    <h3 class="task-title">Pending Incubant (Internship) Applications</h3>
                    <p class="task-desc">There are currently <strong><?= intval($stats['pending_internships'] ?? 0) ?></strong> new internship applications awaiting supervisor review.</p>
                </div>
            </div>
            <a href="/admin/incubants" class="task-btn bg-amber">Review Internships</a>
        </div>

        <!-- Task 2: Volunteer Applications -->
        <div class="task-box">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="task-icon icon-blue"><i class="fa-solid fa-user-plus"></i></div>
                <div>
                    <h3 class="task-title">Pending Volunteer Applications</h3>
                    <p class="task-desc">There are currently <strong><?= intval($stats['pending_volunteers'] ?? 0) ?></strong> volunteer signups requiring role matching.</p>
                </div>
            </div>
            <a href="/admin/incubants" class="task-btn bg-blue">Match Volunteers</a>
        </div>

        <!-- Task 3: Unread Enquiries -->
        <div class="task-box">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="task-icon icon-rose"><i class="fa-regular fa-envelope"></i></div>
                <div>
                    <h3 class="task-title">Unread Contact Form Enquiries</h3>
                    <p class="task-desc">There are <strong><?= intval($stats['unread_enquiries'] ?? 0) ?></strong> enquiries from the public contact forms that are unread.</p>
                </div>
            </div>
            <a href="/admin/enquiries" class="task-btn bg-rose">Read Enquiries</a>
        </div>
    </div>
</div>

<!-- Logs & History Grid -->
<div class="history-grid">
    <!-- Audit Logs Card -->
    <div class="history-card">
        <div class="history-header">
            <div class="history-title-box">
                <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i>
                <h2 class="history-title">Recent Security Audit Logs</h2>
            </div>
            <a href="/admin/audit-logs" class="history-link">View All</a>
        </div>
        
        <?php if (empty($recentAuditLogs)): ?>
            <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.95rem;">
                No audit logs recorded yet.
            </div>
        <?php else: ?>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Admin User</th>
                            <th>IP Address</th>
                            <th>Details</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentAuditLogs as $log): ?>
                            <tr>
                                <td>
                                    <span class="action-tag <?= str_contains(strtolower($log['action']), 'fail') || str_contains(strtolower($log['action']), 'lock') ? 'tag-danger' : 'tag-success' ?>">
                                        <?= htmlspecialchars($log['action']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="admin-profile-cell">
                                        <span class="profile-avatar-mini"><?= strtoupper(substr($log['email'] ?? 'A', 0, 1)) ?></span>
                                        <div>
                                            <div style="font-weight: 500; font-size: 0.85rem;"><?= htmlspecialchars($log['full_name'] ?? 'System Process') ?></div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($log['email'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                <td class="details-cell" title="<?= htmlspecialchars($log['details'] ?? '') ?>"><?= htmlspecialchars($log['details'] ?? '') ?></td>
                                <td style="font-size: 0.8rem; white-space: nowrap;"><?= date('d M Y, h:i A', strtotime($log['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Verification Attempts Card -->
    <div class="history-card">
        <div class="history-header">
            <div class="history-title-box">
                <i class="fa-solid fa-list-check" style="color: var(--secondary);"></i>
                <h2 class="history-title">Recent Certificate Queries</h2>
            </div>
            <span style="font-size: 0.85rem; color: var(--text-muted);">Public Portal Log</span>
        </div>

        <?php if (empty($recentVerifications)): ?>
            <div style="padding: 2rem; text-align: center; color: var(--text-muted); font-size: 0.95rem;">
                No certificate lookup queries logged yet.
            </div>
        <?php else: ?>
            <ul class="query-list">
                <?php foreach ($recentVerifications as $vlog): ?>
                    <li class="query-item">
                        <div class="query-left">
                            <span class="query-icon <?= $vlog['status'] === 'found' ? 'icon-success' : 'icon-danger' ?>">
                                <?php if ($vlog['status'] === 'found'): ?>
                                    <i class="fa-solid fa-check"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-xmark"></i>
                                <?php endif; ?>
                            </span>
                            <div>
                                <div style="font-weight: 600; font-size: 0.9rem;">
                                    Searched: <code><?= htmlspecialchars($vlog['certificate_number']) ?></code>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    IP: <code><?= htmlspecialchars($vlog['ip_address']) ?></code>
                                </div>
                            </div>
                        </div>
                        <div class="query-right">
                            <span class="query-status-label <?= $vlog['status'] === 'found' ? 'label-success' : 'label-danger' ?>">
                                <?= $vlog['status'] === 'found' ? 'Verified' : 'Not Found' ?>
                            </span>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">
                                <?= date('h:i A, d M', strtotime($vlog['verified_at'])) ?>
                            </span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Stats Layout */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(38, 181, 209, 0.08);
        border-color: var(--border-active);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: transparent;
    }

    .card-primary::after { background: var(--primary); }
    .card-secondary::after { background: var(--secondary); }
    .card-purple::after { background: #a855f7; }
    .card-green::after { background: #22c55e; }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.1rem;
    }

    .icon-primary { background: rgba(38, 181, 209, 0.08); color: var(--primary); }
    .icon-secondary { background: rgba(14, 165, 233, 0.08); color: var(--secondary); }
    .icon-purple { background: rgba(168, 85, 247, 0.08); color: #a855f7; }
    .icon-green { background: rgba(34, 197, 94, 0.08); color: #22c55e; }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }

    .stat-footer {
        border-top: 1px solid var(--border-glow);
        padding-top: 0.75rem;
        margin-top: auto;
    }

    .stat-action {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: var(--transition);
    }

    .stat-card:hover .stat-action {
        color: var(--text-main);
    }

    /* Pending Actions */
    .alerts-section {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    .alerts-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .alerts-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .task-box {
        background: var(--bg-base);
        border: 1px solid var(--border-glow);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 1.25rem;
        transition: var(--transition);
    }

    .task-box:hover {
        border-color: var(--border-active);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .task-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .icon-amber { background: var(--pastel-orange); color: #d97706; }
    .icon-blue { background: var(--pastel-blue); color: var(--secondary); }
    .icon-rose { background: var(--pastel-pink); color: #e11d48; }

    .task-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .task-desc {
        font-size: 0.825rem;
        color: var(--text-muted);
        line-height: 1.4;
    }

    .task-btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .bg-amber { background: #fef3c7; color: #d97706; }
    .bg-amber:hover { background: #fde68a; }
    .bg-blue { background: #e0f2fe; color: #0369a1; }
    .bg-blue:hover { background: #bae6fd; }
    .bg-rose { background: #ffe4e6; color: #be123c; }
    .bg-rose:hover { background: #fecdd3; }

    /* History Logs Grid */
    .history-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1100px) {
        .history-grid {
            grid-template-columns: 3fr 2fr;
        }
    }

    .history-card {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        display: flex;
        flex-direction: column;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-glow);
        padding-bottom: 0.75rem;
    }

    .history-title-box {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .history-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .history-link {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
        transition: var(--transition);
    }

    .history-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Tables */
    .table-container {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid var(--border-glow);
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.875rem;
    }

    .dashboard-table th {
        background: #fafbfc;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-glow);
    }

    .dashboard-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-glow);
        vertical-align: middle;
    }

    .dashboard-table tr:last-child td {
        border-bottom: none;
    }

    .action-tag {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .tag-success { background: #dcfce7; color: #15803d; }
    .tag-danger { background: #ffe4e6; color: #b91c1c; }

    .admin-profile-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-avatar-mini {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--pastel-blue);
        color: #0369a1;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .details-cell {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.825rem;
        color: var(--text-muted);
    }

    /* Query list */
    .query-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .query-item {
        background: var(--bg-base);
        border: 1px solid var(--border-glow);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .query-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .query-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.85rem;
    }

    .icon-success { background: #dcfce7; color: #15803d; }
    .icon-danger { background: #ffe4e6; color: #b91c1c; }

    .query-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.25rem;
    }

    .query-status-label {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .label-success { background: #22c55e; color: #ffffff; }
    .label-danger { background: #ef4444; color: #ffffff; }
</style>
