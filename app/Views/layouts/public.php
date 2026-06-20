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
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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

        /* Mobile Menu Styles */
        .mobile-menu-toggle {
            display: none;
            font-size: 1.5rem;
            color: var(--text-main);
            cursor: pointer;
            z-index: 1001;
        }

        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: var(--transition);
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .mobile-nav-menu {
            list-style: none;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .mobile-nav-menu a {
            font-size: 1.5rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            text-decoration: none;
            color: var(--text-main);
            transition: var(--transition);
        }

        .mobile-nav-menu a.active, .mobile-nav-menu a:hover {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .nav-menu {
                display: none;
            }
            .mobile-menu-toggle {
                display: block;
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

        /* WhatsApp Floating Widget */
        .whatsapp-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #25d366;
            color: #ffffff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .whatsapp-widget i {
            font-size: 32px;
        }
        .whatsapp-widget:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
            background-color: #20ba5a;
        }
        /* Pulse Effect */
        .whatsapp-widget::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #25d366;
            border-radius: 50%;
            z-index: -1;
            animation: whatsapp-pulse 2s infinite;
        }
        @keyframes whatsapp-pulse {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }
            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }
        @media (max-width: 768px) {
            .whatsapp-widget {
                bottom: 20px;
                right: 20px;
                width: 50px;
                height: 50px;
            }
            .whatsapp-widget i {
                font-size: 26px;
            }
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
                <li><a href="/projects" class="nav-link <?= ($active ?? '') === 'projects' ? 'active' : '' ?>">Projects</a></li>
                <li><a href="/gallery" class="nav-link <?= ($active ?? '') === 'gallery' ? 'active' : '' ?>">Gallery</a></li>
                <li><a href="/contact" class="nav-link <?= ($active ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
                <li><a href="/verify" class="btn-cta">Verify Certificate</a></li>
            </ul>
            
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <i class="fa-solid fa-bars" id="mobileMenuIcon"></i>
            </div>
        </div>

        <div class="mobile-menu-overlay" id="mobileMenuOverlay">
            <ul class="mobile-nav-menu">
                <li><a href="/" class="<?= ($active ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/about" class="<?= ($active ?? '') === 'about' ? 'active' : '' ?>">About Us</a></li>
                <li><a href="/projects" class="<?= ($active ?? '') === 'projects' ? 'active' : '' ?>">Projects</a></li>
                <li><a href="/gallery" class="<?= ($active ?? '') === 'gallery' ? 'active' : '' ?>">Gallery</a></li>
                <li><a href="/contact" class="<?= ($active ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
                <li style="margin-top: 1rem;"><a href="/verify" class="btn-cta" style="display:inline-block;">Verify Certificate</a></li>
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
                <h4>Explore</h4>
                <ul>
                    <li><a href="/projects">Our Projects</a></li>
                    <li><a href="/gallery">Photo Gallery</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Contact Us</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-envelope" style="color: var(--primary); margin-right: 0.5rem;"></i> <a href="mailto:office@teamincubation.in" style="color: inherit; text-decoration: none;">office@teamincubation.in</a>
                </p>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-phone" style="color: var(--primary); margin-right: 0.5rem;"></i> <a href="tel:+917306198102" style="color: inherit; text-decoration: none;">+91 73061 98102</a>
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
    
    <!-- Floating WhatsApp Widget -->
    <a href="https://wa.me/917306198102?text=Hello%20Team%20Incubation%2C%20I%20have%20a%20query%20regarding" class="whatsapp-widget" target="_blank" rel="noopener noreferrer" title="Chat with us on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ once: true, offset: 50, duration: 800 });

        function toggleMobileMenu() {
            const overlay = document.getElementById('mobileMenuOverlay');
            const icon = document.getElementById('mobileMenuIcon');
            overlay.classList.toggle('active');
            if(overlay.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>
