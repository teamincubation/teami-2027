<style>
    .projects-hero {
        padding: 5rem 2rem 4rem 2rem;
        background: linear-gradient(135deg, rgba(38, 181, 209, 0.05) 0%, rgba(14, 165, 233, 0.05) 100%);
        border-bottom: 1px solid var(--border-glow);
        text-align: center;
    }

    .projects-hero h2 {
        font-size: 2.75rem;
        color: var(--text-main);
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #0f172a 40%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .projects-hero p {
        color: var(--text-muted);
        font-size: 1.15rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .projects-container {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2.5rem;
    }

    @media (max-width: 768px) {
        .projects-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }

    .project-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(38, 181, 209, 0.12);
        border-color: var(--border-active);
    }

    .project-banner-wrapper {
        position: relative;
        height: 200px;
        width: 100%;
        background-color: #f1f5f9;
        overflow: hidden;
    }

    .project-banner {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .project-card:hover .project-banner {
        transform: scale(1.05);
    }

    .project-badge-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        color: var(--text-main);
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-glow);
        backdrop-filter: blur(4px);
    }

    .project-body {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .project-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        font-size: 0.825rem;
    }

    .project-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-active { color: #16a34a; }
    .status-active .status-dot { background-color: #16a34a; }
    .status-planning { color: #2563eb; }
    .status-planning .status-dot { background-color: #2563eb; }
    .status-completed { color: #64748b; }
    .status-completed .status-dot { background-color: #64748b; }

    .project-location {
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .project-title {
        font-size: 1.35rem;
        color: var(--text-main);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .project-desc {
        color: var(--text-muted);
        font-size: 0.925rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
        flex-grow: 1;
    }

    .project-footer {
        border-top: 1px solid var(--border-glow);
        padding-top: 1.25rem;
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .project-date {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .btn-detail {
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: var(--transition);
    }

    .btn-detail:hover {
        color: var(--primary-hover);
        transform: translateX(3px);
    }
</style>

<div class="projects-hero">
    <h2>Our Core Projects</h2>
    <p>Explore the flagship programs and targeted campaigns driving systemic educational development, social welfare, and accessibility support.</p>
</div>

<div class="projects-container">
    <?php if (empty($projects)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-glow);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h4 style="color: var(--text-main); margin-bottom: 0.5rem;">No Projects Listed</h4>
            <p style="color: var(--text-muted);">Please check back later or contact us for more information on our initiatives.</p>
        </div>
    <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card" data-aos="fade-up">
                    <div class="project-banner-wrapper">
                        <?php if (!empty($project['banner_image'])): ?>
                            <img src="<?= media_url($project['banner_image']) ?>" alt="<?= htmlspecialchars($project['name']) ?>" class="project-banner">
                        <?php else: ?>
                            <img src="/assets/images/placeholder.jpg" alt="Project Placeholder" class="project-banner">
                        <?php endif; ?>
                        <?php if (!empty($project['category'])): ?>
                            <span class="project-badge-category"><?= htmlspecialchars($project['category']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="project-body">
                        <div class="project-meta">
                            <span class="project-status status-<?= htmlspecialchars($project['status']) ?>">
                                <span class="status-dot"></span>
                                <?= htmlspecialchars($project['status']) ?>
                            </span>
                            <?php if (!empty($project['location'])): ?>
                                <span class="project-location">
                                    <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($project['location']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="project-title"><?= htmlspecialchars($project['name']) ?></h3>
                        <p class="project-desc"><?= htmlspecialchars($project['short_description']) ?></p>
                        
                        <div class="project-footer">
                            <span class="project-date">
                                <i class="fa-regular fa-calendar"></i> Established: <?= date('M Y', strtotime($project['start_date'] ?? 'now')) ?>
                            </span>
                            
                            <a href="/projects/<?= htmlspecialchars($project['slug']) ?>" class="btn-detail">
                                View Details <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
