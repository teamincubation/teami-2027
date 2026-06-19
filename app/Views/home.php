<style>
    /* Hero Section */
    .hero {
        padding: 8rem 2rem 6rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    @media (max-width: 968px) {
        .hero {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 4rem 1.5rem 3rem 1.5rem;
            gap: 3rem;
        }
    }

    .hero-content h1 {
        font-size: 3.5rem;
        line-height: 1.15;
        letter-spacing: -0.03em;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #1e293b 40%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-content p {
        color: var(--text-muted);
        font-size: 1.15rem;
        margin-bottom: 2.5rem;
        max-width: 540px;
    }

    @media (max-width: 968px) {
        .hero-content p {
            margin: 0 auto 2.5rem auto;
        }
    }

    .hero-actions {
        display: flex;
        gap: 1.25rem;
    }

    @media (max-width: 968px) {
        .hero-actions {
            justify-content: center;
        }
    }

    .btn-secondary {
        background: rgba(0, 0, 0, 0.03);
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        text-decoration: none;
        padding: 0.75rem 1.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-glow);
        transition: var(--transition);
    }

    .btn-secondary:hover {
        background: rgba(0, 0, 0, 0.06);
        border-color: var(--primary);
    }

    .hero-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-card {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-glow);
        border-radius: 20px;
        padding: 2.5rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 40px rgba(38, 181, 209, 0.06);
        position: relative;
        overflow: hidden;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(from 0deg at 50% 50%, transparent 60%, var(--primary) 90%, transparent 100%);
        animation: rotate 8s linear infinite;
        opacity: 0.12;
        pointer-events: none;
        z-index: 0;
    }

    @keyframes rotate {
        100% { transform: rotate(360deg); }
    }

    .hero-card-header {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .hero-card-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-glow);
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--primary);
        font-size: 1.5rem;
        border: 1px solid var(--border-active);
    }

    .hero-card-body {
        position: relative;
        z-index: 1;
    }

    .hero-card-line {
        height: 8px;
        background: rgba(38, 181, 209, 0.08);
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .hero-card-line.short {
        width: 60%;
    }

    .hero-card-line.medium {
        width: 80%;
    }

    .hero-card-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--pastel-teal);
        border: 1px solid var(--border-active);
        padding: 0.4rem 0.8rem;
        border-radius: 99px;
        color: var(--primary-dark);
        font-size: 0.825rem;
        font-weight: 600;
        margin-top: 1.5rem;
    }

    /* Stats Section */
    .stats {
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.65);
        border-top: 1px solid var(--border-glow);
        border-bottom: 1px solid var(--border-glow);
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .stats-container {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-family: 'Outfit', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Features/Programs */
    .features {
        padding: 8rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-header h2 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }

    .section-header p {
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto;
        font-size: 1.05rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    @media (max-width: 968px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }
    }

    .feature-card {
        border: 1px solid var(--border-glow);
        padding: 2.5rem;
        border-radius: 16px;
        transition: var(--transition);
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }

    /* Custom Pastel Themes for Feature Cards */
    .feature-card.leadership {
        background: var(--pastel-teal);
        border-color: rgba(38, 181, 209, 0.15);
    }
    
    .feature-card.internships {
        background: var(--pastel-purple);
        border-color: rgba(147, 51, 234, 0.08);
    }

    .feature-card.verification {
        background: var(--pastel-green);
        border-color: rgba(16, 185, 129, 0.08);
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(38, 181, 209, 0.08);
    }

    .feature-card.leadership i {
        color: #0891b2;
    }

    .feature-card.internships i {
        color: #7c3aed;
    }

    .feature-card.verification i {
        color: #059669;
    }

    .feature-card i {
        font-size: 2.25rem;
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    .feature-card h3 {
        font-size: 1.35rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }

    .feature-card p {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Hero Slider Styles */
    .hero-slider-wrapper {
        position: relative;
        height: 520px;
        width: 100%;
        overflow: hidden;
        border-bottom: 1px solid var(--border-glow);
    }

    .hero-slider {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.8s;
        display: flex;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 0 4rem;
        z-index: 1;
    }

    .hero-slide.active {
        opacity: 1;
        visibility: visible;
        z-index: 2;
    }

    .hero-slide-content {
        max-width: 700px;
        margin-left: calc((100vw - 1200px) / 2);
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        transform: translateY(30px);
        opacity: 0;
    }

    @media (max-width: 1280px) {
        .hero-slide-content {
            margin-left: 5%;
        }
    }

    @media (max-width: 768px) {
        .hero-slider-wrapper {
            height: auto;
            min-height: 400px;
        }
        .hero-slide {
            padding: 4rem 1.5rem;
            text-align: center;
            justify-content: center;
        }
        .hero-slide-content {
            margin-left: 0;
        }
    }

    .hero-slide.active .hero-slide-content {
        animation: slideUpActive 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes slideUpActive {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .hero-slide-content h1 {
        font-size: 3.5rem;
        line-height: 1.15;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #0f172a 40%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-slide-content p {
        color: var(--text-muted);
        font-size: 1.15rem;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .slider-dots {
        position: absolute;
        bottom: 25px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 10;
    }

    .slider-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(38, 181, 209, 0.2);
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid rgba(255,255,255,0.5);
    }

    .slider-dot:hover {
        background: var(--primary);
    }

    .slider-dot.active {
        background: var(--primary);
        width: 28px;
        border-radius: 5px;
    }
</style>

<!-- Dynamic Hero Banner / Slider -->
<?php if (!empty($banners)): ?>
    <section class="hero-slider-wrapper">
        <div class="hero-slider">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="hero-slide <?= $index === 0 ? 'active' : '' ?>" style="background-image: linear-gradient(rgba(245, 250, 253, 0.85), rgba(245, 250, 253, 0.96)), url('<?= media_url($banner['desktop_image']) ?>');">
                    <div class="hero-slide-content">
                        <?php if (!empty($banner['title'])): ?>
                            <h1><?= htmlspecialchars($banner['title']) ?></h1>
                        <?php endif; ?>
                        <?php if (!empty($banner['subtitle'])): ?>
                            <p><?= htmlspecialchars($banner['subtitle']) ?></p>
                        <?php endif; ?>
                        <div class="hero-actions">
                            <?php if (!empty($banner['cta_label'])): ?>
                                <a href="<?= htmlspecialchars($banner['cta_url'] ?? '#') ?>" class="btn-cta"><?= htmlspecialchars($banner['cta_label']) ?> <i class="fa-solid fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.85rem;"></i></a>
                            <?php endif; ?>
                            <a href="/about" class="btn-secondary">Learn More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($banners) > 1): ?>
            <div class="slider-dots">
                <?php foreach ($banners as $index => $banner): ?>
                    <span class="slider-dot <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php else: ?>
    <!-- Fallback Hero -->
    <section class="hero">
        <div class="hero-content">
            <h1>Incubating the Leaders of Tomorrow</h1>
            <p>Team Incubation is a community-first NGO platform fostering leadership, civic engagement, volunteering and career-readiness among youths through structured programs.</p>
            <div class="hero-actions">
                <a href="/verify" class="btn-cta" style="padding: 0.8rem 2rem; font-size:1.05rem;">Get Verified</a>
                <a href="/about" class="btn-secondary" style="padding: 0.8rem 2rem; font-size:1.05rem;">Learn More</a>
            </div>
        </div>
        
        <div class="hero-visual" data-aos="fade-left" data-aos-delay="200">
            <div class="hero-card">
                <div class="hero-card-header">
                    <div class="hero-card-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h4 style="font-size:1.15rem; color:var(--text-main);">Digital Certificate</h4>
                        <p style="font-size:0.8rem; color:var(--text-muted);">Verified Secure Registry</p>
                    </div>
                </div>
                <div class="hero-card-body">
                    <div class="hero-card-line medium"></div>
                    <div class="hero-card-line"></div>
                    <div class="hero-card-line short"></div>
                    <div class="hero-card-line"></div>
                    
                    <div class="hero-card-badge">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>Authenticated By Cryptography</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Stats -->
<section class="stats">
    <div class="stats-container">
        <div class="stat-item" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-number">10k+</div>
            <div class="stat-label">Students Guided</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-number">150+</div>
            <div class="stat-label">Social Campaigns</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-number">100%</div>
            <div class="stat-label">Verifiable Registry</div>
        </div>
        <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-number">11+</div>
            <div class="stat-label">Years of Action</div>
        </div>
    </div>
</section>

<!-- Programs -->
<section class="features">
    <div class="section-header" data-aos="fade-up">
        <h2>Our Main Focus Areas</h2>
        <p>Discover how Team Incubation empowers candidates with the skills and volunteer experience required to excel in their career paths.</p>
    </div>
    
    <div class="feature-grid">
        <div class="feature-card leadership" data-aos="fade-up" data-aos-delay="0">
            <i class="fa-solid fa-people-group"></i>
            <h3>Leadership & Voluntarism</h3>
            <p>Engage in active social programs, field campaigns, and event coordination, developing empathy, delegation, and management skills.</p>
        </div>
        
        <div class="feature-card internships" data-aos="fade-up" data-aos-delay="150">
            <i class="fa-solid fa-laptop-code"></i>
            <h3>Professional Internships</h3>
            <p>Work on real-world projects and build production-ready digital tools with mentorship from senior engineers and domain experts.</p>
        </div>
        
        <div class="feature-card verification" data-aos="fade-up" data-aos-delay="300">
            <i class="fa-solid fa-circle-check"></i>
            <h3>Authentic Verification</h3>
            <p>A secure portal for corporate hiring managers, universities, and institutions to immediately verify certificate credentials and lookup student evaluations.</p>
        </div>
    </div>
</section>

<!-- Slider Logic -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const slides = document.querySelectorAll(".hero-slide");
        const dots = document.querySelectorAll(".slider-dot");
        if (slides.length <= 1) return;

        let currentSlide = 0;
        const slideInterval = 6500; // 6.5 seconds

        function showSlide(index) {
            slides[currentSlide].classList.remove("active");
            if (dots.length > 0) {
                dots[currentSlide].classList.remove("active");
            }
            
            currentSlide = index;
            
            slides[currentSlide].classList.add("active");
            if (dots.length > 0) {
                dots[currentSlide].classList.add("active");
            }
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        let autoSlide = setInterval(nextSlide, slideInterval);

        dots.forEach(dot => {
            dot.addEventListener("click", function() {
                clearInterval(autoSlide);
                const index = parseInt(this.getAttribute("data-index"));
                showSlide(index);
                autoSlide = setInterval(nextSlide, slideInterval);
            });
        });
    });
</script>
