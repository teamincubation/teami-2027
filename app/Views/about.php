<style>
    .about-section {
        max-width: 1000px;
        margin: 6rem auto;
        padding: 0 2rem;
    }

    @media (max-width: 768px) {
        .about-section {
            margin: 3rem auto;
        }
    }

    .about-hero {
        text-align: center;
        margin-bottom: 5rem;
    }

    .about-hero h2 {
        font-size: 2.75rem;
        color: #fff;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #fff 40%, var(--primary-hover));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .about-hero p {
        color: var(--text-muted);
        font-size: 1.15rem;
        max-width: 700px;
        margin: 0 auto;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        margin-bottom: 5rem;
        align-items: center;
    }

    @media (max-width: 768px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
    }

    .about-text h3 {
        font-size: 1.75rem;
        color: #fff;
        margin-bottom: 1.25rem;
    }

    .about-text p {
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        font-size: 1rem;
        line-height: 1.7;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
        border-left: 2px solid var(--border-glow);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.35rem;
        top: 0.25rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: var(--primary);
        border: 4px solid var(--bg-base);
    }

    .timeline-year {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: var(--primary);
        font-size: 1.15rem;
        margin-bottom: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        color: #fff;
        font-size: 1.05rem;
        margin-bottom: 0.5rem;
    }

    .timeline-desc {
        color: var(--text-muted);
        font-size: 0.925rem;
    }
</style>

<div class="about-section">
    <div class="about-hero">
        <h2>Our Journey & Impact</h2>
        <p>Over a decade of enabling community leadership, professional skills, and educational support across sectors.</p>
    </div>
    
    <div class="about-grid">
        <div class="about-text">
            <h3>Vision & Core Philosophy</h3>
            <p>At Team Incubation, we believe true leadership is forged through community service. Our platform bridges the gap between academic education and civic duties by involving candidates in social programs, engineering internships, and community development efforts.</p>
            <p>By empowering youth with both technical expertise and administrative capabilities, we nurture professionals who are socially aware, empathetic, and ready to contribute to a better society.</p>
        </div>
        
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-year">2015</div>
                <div class="timeline-title">Foundation</div>
                <div class="timeline-desc">Established as a volunteer group assisting community initiatives in Calicut.</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-year">2020</div>
                <div class="timeline-title">Skills Integration</div>
                <div class="timeline-desc">Launched structured internship and mentoring programs in technology and management.</div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-year">2026</div>
                <div class="timeline-title">Digital Transformation</div>
                <div class="timeline-desc">Built a secure, centralized student evaluating and certificate verification portal.</div>
            </div>
        </div>
    </div>
</div>
