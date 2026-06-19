<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Team Incubation') ?></title>
    
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
            --bg-base: #f5fafd; /* Ice white background */
            --bg-surface: rgba(255, 255, 255, 0.85); /* Glassmorphic white */
            --bg-card: #ffffff; /* Pure white */
            --primary: #26b5d1; /* Turquoise primary */
            --primary-glow: rgba(38, 181, 209, 0.06);
            --primary-hover: #1da3bd;
            --secondary: #0ea5e9; /* Sky blue secondary */
            --text-main: #1e293b; /* Slate-800 */
            --text-muted: #577399; /* Steel blue muted text */
            --border-glow: rgba(38, 181, 209, 0.12); /* Soft turquoise borders */
            --border-active: rgba(38, 181, 209, 0.35);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            /* Pastel Color Palette */
            --pastel-blue: #e0f2fe;    /* Sky blue pastel */
            --pastel-teal: #e6fcff;    /* Cyan pastel */
            --pastel-green: #e6fcf5;   /* Mint pastel */
            --pastel-yellow: #fefcbf;  /* Cream yellow pastel */
            --pastel-orange: #fff1e6;  /* Peach pastel */
            --pastel-purple: #faf5ff;  /* Lavender pastel */
            --pastel-pink: #fff5f7;    /* Rose pastel */
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
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                radial-gradient(at 0% 0%, rgba(38, 181, 209, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(14, 165, 233, 0.05) 0px, transparent 50%);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* Glassmorphic Header */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-glow);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            transition: var(--transition);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .logo-icon {
            font-size: 1.75rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .logo-text span {
            color: var(--primary);
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: rgba(0, 0, 0, 0.03);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            width: 80%;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .btn-cta {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            text-decoration: none;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(38, 181, 209, 0.25);
            transition: var(--transition);
        }

        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(38, 181, 209, 0.45);
            filter: brightness(1.1);
        }

        /* Main Content wrapper */
        main {
            flex: 1;
            width: 100%;
        }

        /* Premium Footer */
        footer {
            background-color: #f1f5f9;
            border-top: 1px solid var(--border-glow);
            padding: 4rem 2rem 2rem 2rem;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .nav-menu {
                display: none; /* simple responsive fallback */
            }
        }

        .footer-brand p {
            color: var(--text-muted);
            margin-top: 1rem;
            font-size: 0.95rem;
            max-width: 320px;
        }

        .footer-links h4 {
            color: var(--text-main);
            margin-bottom: 1.25rem;
            font-size: 1.1rem;
            position: relative;
        }

        .footer-links h4::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: var(--primary);
        }

        .footer-links ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 4px;
        }

        .footer-socials {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 36px;
            height: 36px;
            background: rgba(0, 0, 0, 0.03);
            color: var(--text-muted);
            border-radius: 50%;
            text-decoration: none;
            transition: var(--transition);
            border: 1px solid var(--border-glow);
        }

        .social-icon:hover {
            background: var(--primary);
            color: #ffffff;
            transform: scale(1.1);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            border-top: 1px solid var(--border-glow);
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .footer-bottom a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-bottom a:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="/" class="logo">
                <img src="/images/logo.png" alt="Team Incubation Logo" style="height: 52px; width: auto; display: block;">
            </a>
            
            <ul class="nav-menu">
                <li><a href="/" class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/about" class="nav-link <?= ($active ?? '') === 'about' ? 'active' : '' ?>">About Us</a></li>
                <li><a href="/verify" class="nav-link <?= ($active ?? '') === 'verify' ? 'active' : '' ?>">Verification</a></li>
                <li><a href="/contact" class="nav-link <?= ($active ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
                <li><a href="/verify" class="btn-cta">Verify Certificate</a></li>
            </ul>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-brand">
                <a href="/" class="logo" style="margin-bottom: 1.5rem; display: inline-block;">
                    <img src="/images/logo.png" alt="Team Incubation Logo" style="height: 44px; width: auto; display: block;">
                </a>
                <p>Nurturing skills, building leadership, and driving positive social changes through volunteering and professional internships.</p>
                <div class="footer-socials">
                    <a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About Us</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Verification</h4>
                <ul>
                    <li><a href="/verify">Verify Certificates</a></li>
                    <li><a href="/verify">Internship Lookup</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Contact Us</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-envelope" style="color: var(--primary); margin-right: 0.5rem;"></i> info@teamincubation.in
                </p>
                <p style="color: var(--text-muted); font-size: 0.95rem;">
                    <i class="fa-solid fa-location-dot" style="color: var(--primary); margin-right: 0.5rem;"></i> Calicut, Kerala, India
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Team Incubation. All Rights Reserved.</p>
            <div style="display: flex; gap: 1.5rem;">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>
