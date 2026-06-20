<style>
    .project-detail-hero {
        position: relative;
        height: 400px;
        width: 100%;
        background-color: #0f172a;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
    }

    .project-detail-banner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.55;
    }

    .project-detail-hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 2rem;
        width: 100%;
        color: #ffffff;
    }

    .project-detail-category {
        display: inline-block;
        background: var(--primary);
        color: #ffffff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .project-detail-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
        .project-detail-hero {
            height: 300px;
        }
        .project-detail-title {
            font-size: 2rem;
        }
    }

    .detail-grid {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3.5rem;
        align-items: start;
    }

    @media (max-width: 968px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
    }

    .detail-content {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .detail-content h3 {
        color: var(--text-main);
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        border-left: 4px solid var(--primary);
        padding-left: 0.75rem;
    }

    .rich-text {
        color: var(--text-muted);
        line-height: 1.75;
        font-size: 1.05rem;
    }

    .rich-text p {
        margin-bottom: 1.5rem;
    }

    .detail-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        position: sticky;
        top: 100px;
    }

    .sidebar-widget {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .sidebar-widget h4 {
        color: var(--text-main);
        font-size: 1.15rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-glow);
        padding-bottom: 0.75rem;
    }

    .meta-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
    }

    .meta-icon {
        width: 32px;
        height: 32px;
        background: var(--primary-glow);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.9rem;
    }

    .meta-info h5 {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0;
    }

    .meta-info p {
        font-size: 0.95rem;
        color: var(--text-main);
        font-weight: 500;
        margin: 0;
    }

    .objectives-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .objective-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .objective-item i {
        color: var(--primary);
        margin-top: 0.25rem;
    }

    .btn-back-container {
        max-width: 1200px;
        margin: 2rem auto 0 auto;
        padding: 0 2rem;
    }

    .btn-back {
        text-decoration: none;
        color: var(--text-muted);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        font-size: 0.95rem;
    }

    .btn-back:hover {
        color: var(--primary);
    }
</style>

<div class="project-detail-hero">
    <?php if (!empty($project['featured_image'])): ?>
        <img src="<?= media_url($project['featured_image']) ?>" alt="<?= htmlspecialchars($project['name']) ?>" class="project-detail-banner">
    <?php elseif (!empty($project['banner_image'])): ?>
        <img src="<?= media_url($project['banner_image']) ?>" alt="<?= htmlspecialchars($project['name']) ?>" class="project-detail-banner">
    <?php else: ?>
        <div class="project-detail-banner" style="background: linear-gradient(135deg, var(--primary), var(--secondary));"></div>
    <?php endif; ?>
    
    <div class="project-detail-hero-content">
        <?php if (!empty($project['category'])): ?>
            <span class="project-detail-category"><?= htmlspecialchars($project['category']) ?></span>
        <?php endif; ?>
        <h1 class="project-detail-title"><?= htmlspecialchars($project['name']) ?></h1>
    </div>
</div>

<div class="btn-back-container">
    <a href="/projects" class="btn-back">
        <i class="fa-solid fa-arrow-left-long"></i> Back to Projects
    </a>
</div>

<div class="detail-grid">
    <div class="detail-content" data-aos="fade-up">
        <h3>About the Project</h3>
        <div class="rich-text">
            <?= $project['full_description'] ?>
        </div>
        
        <?php if (!empty($project['target_beneficiaries'])): ?>
            <div style="margin-top: 2.5rem; padding: 1.5rem; background: var(--primary-glow); border-radius: 12px; border: 1px solid var(--border-glow);">
                <h4 style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.5rem; font-family: 'Outfit', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-users" style="color: var(--primary);"></i> Target Beneficiaries
                </h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0; line-height: 1.6;"><?= htmlspecialchars($project['target_beneficiaries']) ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="detail-sidebar" data-aos="fade-up" data-aos-delay="100">
        <div class="sidebar-widget">
            <h4>Project Details</h4>
            <ul class="meta-list">
                <li class="meta-item">
                    <div class="meta-icon"><i class="fa-solid fa-circle-info"></i></div>
                    <div class="meta-info">
                        <h5>Status</h5>
                        <p style="text-transform: capitalize;"><?= htmlspecialchars($project['status']) ?></p>
                    </div>
                </li>
                <?php if (!empty($project['location'])): ?>
                    <li class="meta-item">
                        <div class="meta-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="meta-info">
                            <h5>Location</h5>
                            <p><?= htmlspecialchars($project['location']) ?></p>
                        </div>
                    </li>
                <?php endif; ?>
                <li class="meta-item">
                    <div class="meta-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="meta-info">
                        <h5>Initiation Date</h5>
                        <p><?= date('d M Y', strtotime($project['start_date'] ?? 'now')) ?></p>
                    </div>
                </li>
            </ul>
        </div>
        
        <?php if (!empty($project['objectives'])): ?>
            <div class="sidebar-widget">
                <h4>Core Objectives</h4>
                <ul class="objectives-list">
                    <?php 
                        $objectives = explode("\n", $project['objectives']);
                        foreach ($objectives as $objective):
                            $objective = trim($objective, " \t\n\r\0\x0B•-");
                            if (empty($objective)) continue;
                    ?>
                        <li class="objective-item">
                            <i class="fa-solid fa-circle-check"></i>
                            <span><?= htmlspecialchars($objective) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
