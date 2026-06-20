<style>
    .gallery-hero {
        padding: 5rem 2rem 4rem 2rem;
        background: linear-gradient(135deg, rgba(38, 181, 209, 0.05) 0%, rgba(14, 165, 233, 0.05) 100%);
        border-bottom: 1px solid var(--border-glow);
        text-align: center;
    }

    .gallery-hero h2 {
        font-size: 2.75rem;
        color: var(--text-main);
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #0f172a 40%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .gallery-hero p {
        color: var(--text-muted);
        font-size: 1.15rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .gallery-container {
        max-width: 1200px;
        margin: 4rem auto;
        padding: 0 2rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.5rem;
        }
    }

    .gallery-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(38, 181, 209, 0.15);
        border-color: var(--border-active);
    }

    .gallery-img-wrapper {
        position: relative;
        padding-bottom: 75%; /* 4:3 Aspect Ratio */
        overflow: hidden;
        background-color: #f1f5f9;
    }

    .gallery-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .gallery-card:hover .gallery-img {
        transform: scale(1.06);
    }

    .gallery-card-info {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .gallery-card-title {
        font-size: 1.05rem;
        color: var(--text-main);
        font-weight: 600;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }

    .gallery-card-caption {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Lightbox Modal */
    .lightbox-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lightbox-modal.show {
        display: flex;
        opacity: 1;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 80%;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .lightbox-img {
        max-width: 100%;
        max-height: 70vh;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        border: 2px solid rgba(255, 255, 255, 0.1);
        object-fit: contain;
    }

    .lightbox-details {
        text-align: center;
        color: #ffffff;
        margin-top: 1.5rem;
        max-width: 600px;
    }

    .lightbox-title {
        font-size: 1.35rem;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #ffffff, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .lightbox-caption {
        font-size: 0.95rem;
        color: #cbd5e1;
        line-height: 1.6;
    }

    .lightbox-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: #ffffff;
        font-size: 2rem;
        cursor: pointer;
        transition: var(--transition);
        background: none;
        border: none;
    }

    .lightbox-close:hover {
        color: var(--primary);
        transform: scale(1.1);
    }
</style>

<div class="gallery-hero">
    <h2>Photo Gallery</h2>
    <p>Glimpses of our legacy, training programs, central university coaching camps, and community service initiatives.</p>
</div>

<div class="gallery-container">
    <?php if (empty($photos)): ?>
        <div style="text-align: center; padding: 4rem 2rem; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-glow);">
            <i class="fa-solid fa-images" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h4 style="color: var(--text-main); margin-bottom: 0.5rem;">No Photos Available</h4>
            <p style="color: var(--text-muted);">We are currently updating our archive. Please check back later.</p>
        </div>
    <?php else: ?>
        <div class="gallery-grid">
            <?php foreach ($photos as $photo): ?>
                <div class="gallery-card" data-aos="fade-up" onclick="openLightbox('<?= media_url($photo['image_path']) ?>', '<?= htmlspecialchars($photo['title'] ?? '') ?>', '<?= htmlspecialchars($photo['caption'] ?? '') ?>')">
                    <div class="gallery-img-wrapper">
                        <img src="<?= media_url($photo['image_path']) ?>" alt="<?= htmlspecialchars($photo['title'] ?? 'Gallery Image') ?>" class="gallery-img" loading="lazy">
                    </div>
                    <?php if (!empty($photo['title']) || !empty($photo['caption'])): ?>
                        <div class="gallery-card-info">
                            <?php if (!empty($photo['title'])): ?>
                                <h4 class="gallery-card-title"><?= htmlspecialchars($photo['title']) ?></h4>
                            <?php endif; ?>
                            <?php if (!empty($photo['caption'])): ?>
                                <p class="gallery-card-caption"><?= htmlspecialchars($photo['caption']) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Lightbox Modal Box -->
<div class="lightbox-modal" id="lightboxModal" onclick="closeLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="lightbox-close" onclick="closeLightbox(event)"><i class="fa-solid fa-xmark"></i></button>
        <img src="" alt="" class="lightbox-img" id="lightboxImg">
        <div class="lightbox-details">
            <h4 class="lightbox-title" id="lightboxTitle"></h4>
            <p class="lightbox-caption" id="lightboxCaption"></p>
        </div>
    </div>
</div>

<script>
    function openLightbox(imgSrc, title, caption) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImg');
        const titleEl = document.getElementById('lightboxTitle');
        const captionEl = document.getElementById('lightboxCaption');

        img.src = imgSrc;
        img.alt = title || 'Gallery Image';
        titleEl.textContent = title || '';
        captionEl.textContent = caption || '';

        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        const modal = document.getElementById('lightboxModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
        document.body.style.overflow = '';
    }

    // Close lightbox on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLightbox();
        }
    });
</script>
