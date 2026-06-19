<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Portal | Team Incubation') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-base: #f1f5f9; /* Slate 100 background */
            --bg-surface: rgba(255, 255, 255, 0.85); /* Glassmorphic white */
            --bg-card: #ffffff; /* Pure white */
            --primary: #26b5d1; /* Turquoise primary */
            --primary-glow: rgba(38, 181, 209, 0.06);
            --primary-hover: #1da3bd;
            --secondary: #0ea5e9; /* Sky blue secondary */
            --text-main: #0f172a; /* Slate 900 */
            --text-muted: #64748b; /* Slate 500 */
            --border-glow: rgba(38, 181, 209, 0.12); /* Soft turquoise borders */
            --border-active: rgba(38, 181, 209, 0.35);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            /* Pastel Color Palette */
            --pastel-blue: #e0f2fe;    /* Sky blue pastel */
            --pastel-teal: #e6fcff;    /* Cyan pastel */
            --pastel-green: #dcfce7;   /* Mint/Green pastel */
            --pastel-yellow: #fef9c3;  /* Yellow pastel */
            --pastel-orange: #ffedd5;  /* Peach/Orange pastel */
            --pastel-purple: #f3e8ff;  /* Lavender/Purple pastel */
            --pastel-pink: #ffe4e6;    /* Rose/Pink pastel */

            --sidebar-width: 270px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            background-image: 
                radial-gradient(at 0% 0%, rgba(38, 181, 209, 0.03) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.03) 0px, transparent 50%);
        }

        /* Layout Architecture */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--border-glow);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.01);
        }

        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0; /* Prevents flex children from breaking parent layout */
            transition: var(--transition);
        }

        /* Sidebar Logo & Branding */
        .sidebar-brand {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-glow);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
        }

        /* Sidebar Navigation */
        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 1rem;
            list-style: none;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .menu-header {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 1rem 0 0.5rem 1rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.925rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .menu-link:hover {
            color: var(--primary);
            background: rgba(38, 181, 209, 0.05);
            transform: translateX(4px);
        }

        .menu-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 12px rgba(38, 181, 209, 0.2);
            font-weight: 600;
        }

        .menu-link.active i {
            color: #ffffff;
        }

        .menu-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }

        /* Sidebar User Profile */
        .sidebar-user {
            padding: 1.25rem;
            border-top: 1px solid var(--border-glow);
            background: #fafbfc;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .logout-btn {
            color: var(--text-muted);
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: var(--transition);
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #ef4444;
            background: #fee2e2;
        }

        /* Top Header Navigation */
        .admin-header {
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-glow);
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .toggle-sidebar {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-main);
            cursor: pointer;
        }

        .header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .status-badge {
            background: var(--pastel-green);
            color: #166534;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background-color: #15803d;
            border-radius: 50%;
            display: inline-block;
        }

        /* Main Content Grid */
        .workspace {
            padding: 2rem;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* Compact Dashboard Footer */
        .admin-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-glow);
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
            background: #ffffff;
        }

        /* Responsive Architecture */
        @media (max-width: 992px) {
            .admin-sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .admin-sidebar.open {
                left: 0;
            }
            .admin-main {
                margin-left: 0;
            }
            .toggle-sidebar {
                display: block;
            }
        }

        /* Styled Alerts */
        .alert-box {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.925rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: fadeIn 0.4s ease-out;
        }
        
        .alert-success {
            background: var(--pastel-green);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: var(--pastel-pink);
            border: 1px solid #fecdd3;
            color: #991b1b;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Collapsible Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <a href="/admin/dashboard" class="sidebar-brand">
            <img src="/images/logo.png" alt="Team Incubation Logo" style="height: 40px; width: auto; display: block;">
            <div style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800;">
                Teami<span style="color: var(--primary);">Portal</span>
            </div>
        </a>
        
        <ul class="sidebar-menu">
            <span class="menu-header">Dashboard</span>
            <li>
                <a href="/admin/dashboard" class="menu-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-line"></i> Summary View
                </a>
            </li>

            <span class="menu-header">NGO Modules</span>
            <li>
                <a href="/admin/timelines" class="menu-link <?= ($active ?? '') === 'timelines' ? 'active' : '' ?>">
                    <i class="fa-solid fa-timeline"></i> Dynamic Timelines
                </a>
            </li>
            <li>
                <a href="/admin/banners" class="menu-link <?= ($active ?? '') === 'banners' ? 'active' : '' ?>">
                    <i class="fa-solid fa-images"></i> Hero Banners
                </a>
            </li>
            <li>
                <a href="/admin/projects" class="menu-link <?= ($active ?? '') === 'projects' ? 'active' : '' ?>">
                    <i class="fa-solid fa-hand-holding-heart"></i> Projects CMS
                </a>
            </li>
            <li>
                <a href="/admin/events" class="menu-link <?= ($active ?? '') === 'events' ? 'active' : '' ?>">
                    <i class="fa-regular fa-calendar-check"></i> Events & Calendar
                </a>
            </li>
            <li>
                <a href="/admin/campaigns" class="menu-link <?= ($active ?? '') === 'campaigns' ? 'active' : '' ?>">
                    <i class="fa-solid fa-bullhorn"></i> Campaigns CMS
                </a>
            </li>
            <li>
                <a href="/admin/partners" class="menu-link <?= ($active ?? '') === 'partners' ? 'active' : '' ?>">
                    <i class="fa-solid fa-handshake"></i> Partners Carousel
                </a>
            </li>

            <span class="menu-header">Forms & Applications</span>
            <li>
                <a href="/admin/forms" class="menu-link <?= ($active ?? '') === 'forms' ? 'active' : '' ?>">
                    <i class="fa-solid fa-list-check"></i> Form Builder
                </a>
            </li>
            <li>
                <a href="/admin/incubants" class="menu-link <?= ($active ?? '') === 'incubants' ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-graduate"></i> Incubant Portal
                </a>
            </li>

            <span class="menu-header">Certificates</span>
            <li>
                <a href="/admin/certificates" class="menu-link <?= ($active ?? '') === 'certificates' ? 'active' : '' ?>">
                    <i class="fa-solid fa-award"></i> Certificate Portal
                </a>
            </li>
            <li>
                <a href="/admin/certificates/import" class="menu-link <?= ($active ?? '') === 'cert_import' ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-import"></i> Bulk Importer
                </a>
            </li>

            <span class="menu-header">Communication & System</span>
            <li>
                <a href="/admin/enquiries" class="menu-link <?= ($active ?? '') === 'enquiries' ? 'active' : '' ?>">
                    <i class="fa-regular fa-envelope-open"></i> Contact Enquiries
                </a>
            </li>
            <li>
                <a href="/admin/subscribers" class="menu-link <?= ($active ?? '') === 'subscribers' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users"></i> Subscribers
                </a>
            </li>
            <li>
                <a href="/admin/audit-logs" class="menu-link <?= ($active ?? '') === 'audit_logs' ? 'active' : '' ?>">
                    <i class="fa-solid fa-shield-halved"></i> Security Audit Logs
                </a>
            </li>
        </ul>

        <?php $currUser = auth_user(); ?>
        <div class="sidebar-user">
            <div class="user-avatar">
                <?= strtoupper(substr($currUser['email'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="user-details">
                <p class="user-name"><?= htmlspecialchars(session('user_profile')['full_name'] ?? $currUser['email'] ?? 'Administrator') ?></p>
                <p class="user-role"><?= htmlspecialchars($currUser['role_name'] ?? 'Super Admin') ?></p>
            </div>
            <a href="/admin/logout" class="logout-btn" title="Sign Out">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Workspace Container -->
    <div class="admin-main">
        <!-- Top bar sticky header -->
        <header class="admin-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="toggle-sidebar" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="header-title"><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></h1>
            </div>

            <div class="header-right">
                <span class="status-badge">
                    <span class="status-dot"></span> Online Local
                </span>
                
                <!-- Display time -->
                <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem;" id="headerClock">
                    <i class="fa-regular fa-clock" style="color: var(--primary);"></i>
                    <span><?= date('d M Y, h:i A') ?></span>
                </div>
            </div>
        </header>

        <!-- Workspace main scroll wrapper -->
        <main class="workspace">
            <!-- Flash Alerts inside Layout Workspace -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert-box alert-success">
                    <i class="fa-solid fa-circle-check" style="margin-top: 0.15rem;"></i>
                    <div><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert-box alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="margin-top: 0.15rem;"></i>
                    <div><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_errors'])): ?>
                <div class="alert-box alert-error">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-top: 0.15rem;"></i>
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <?php foreach ($_SESSION['flash_errors'] as $err): ?>
                            <span><?= htmlspecialchars($err) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php unset($_SESSION['flash_errors']); ?>
            <?php endif; ?>

            <!-- View content gets injected here -->
            <?= $content ?>
        </main>

        <footer class="admin-footer">
            <p>&copy; 2026 Team Incubation Portal. All rights reserved. Dev Environment.</p>
        </footer>
    </div>

    <!-- Mobile Navigation Toggle Script -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('open');
            });
        }

        // Close sidebar when clicking outside on mobile devices
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && e.target !== sidebarToggle && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html>
