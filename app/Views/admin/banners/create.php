<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/banners" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Banners</a>
</div>

<div class="form-card">
    <form action="/admin/banners/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Banner Title -->
            <div class="form-group">
                <label for="title">Slide Title</label>
                <input type="text" id="title" name="title" placeholder="e.g. Welcome to Team Incubation" class="form-control">
                <span class="help-text">Heading text overlaid on the banner. Can be left blank.</span>
            </div>

            <!-- Display Location -->
            <div class="form-group">
                <label for="display_location">Display Location <span class="required">*</span></label>
                <select id="display_location" name="display_location" required class="form-control">
                    <option value="home_hero">Home Page Hero Slider</option>
                    <option value="projects">Projects Section Banner</option>
                    <option value="events">Events Section Banner</option>
                    <option value="certificates">Certificates Search Header</option>
                </select>
                <span class="help-text">Where this graphic should be rendered.</span>
            </div>

            <!-- Subtitle/Description -->
            <div class="form-group full-width">
                <label for="subtitle">Subtitle / Supporting Text</label>
                <textarea id="subtitle" name="subtitle" rows="3" placeholder="A brief sentence supporting the main title..." class="form-control"></textarea>
                <span class="help-text">Smaller caption text under the title.</span>
            </div>

            <!-- Desktop Image Upload -->
            <div class="form-group">
                <label for="desktop_image">Desktop Banner Image (1920x650 px) <span class="required">*</span></label>
                <input type="file" id="desktop_image" name="desktop_image" required accept="image/jpeg,image/png,image/webp" class="form-control file-input">
                <span class="help-text">Landscape graphic displayed on desktop layouts. Max 5MB.</span>
            </div>

            <!-- Mobile Image Upload -->
            <div class="form-group">
                <label for="mobile_image">Mobile Banner Image (600x800 px)</label>
                <input type="file" id="mobile_image" name="mobile_image" accept="image/jpeg,image/png,image/webp" class="form-control file-input">
                <span class="help-text">Portrait layout graphic optimized for mobile viewports. Optional.</span>
            </div>

            <!-- CTA Label -->
            <div class="form-group">
                <label for="cta_label">CTA Button Label</label>
                <input type="text" id="cta_label" name="cta_label" placeholder="e.g. Learn More / Join Us" class="form-control">
                <span class="help-text">Label for the click action button. Left blank if no CTA button is needed.</span>
            </div>

            <!-- CTA URL -->
            <div class="form-group">
                <label for="cta_url">CTA Button Link URL</label>
                <input type="text" id="cta_url" name="cta_url" placeholder="e.g. /projects or https://..." class="form-control">
                <span class="help-text">Target URL path or external link for the CTA click action.</span>
            </div>

            <!-- Display Order -->
            <div class="form-group">
                <label for="display_order">Display Order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="0" class="form-control">
                <span class="help-text">Sorting priority (lower values slide in first).</span>
            </div>

            <!-- Active Status -->
            <div class="form-group" style="display: flex; align-items: center; padding-top: 1.75rem;">
                <label class="switch-container">
                    <input type="checkbox" id="active" name="active" checked value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Publish/Active</span>
                </label>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/banners" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Upload Banner <i class="fa-solid fa-cloud-arrow-up" style="margin-left: 0.5rem;"></i></button>
        </div>
    </form>
</div>

<style>
    .back-link {
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-link:hover {
        color: var(--primary);
    }

    .form-card {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        padding: 2.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
        .full-width {
            grid-column: span 2;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-glow);
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: var(--text-main);
        outline: none;
        background-color: #fafbfc;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary);
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(38, 181, 209, 0.12);
    }

    .file-input {
        padding: 0.6rem 1rem;
        background: #fafbfc;
        cursor: pointer;
    }

    .help-text {
        font-size: 0.775rem;
        color: var(--text-muted);
    }

    /* Switch slider design */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        user-select: none;
    }

    .switch-container input {
        display: none;
    }

    .switch-slider {
        position: relative;
        width: 44px;
        height: 24px;
        background-color: #cbd5e1;
        border-radius: 9999px;
        transition: var(--transition);
    }

    .switch-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        left: 3px;
        bottom: 3px;
        background-color: #ffffff;
        border-radius: 50%;
        transition: var(--transition);
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .switch-container input:checked + .switch-slider {
        background-color: var(--primary);
    }

    .switch-container input:checked + .switch-slider::before {
        transform: translateX(20px);
    }

    .form-actions-bar {
        margin-top: 2.5rem;
        border-top: 1px solid var(--border-glow);
        padding-top: 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: var(--text-muted);
        text-decoration: none;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        border: 1px solid transparent;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: var(--text-main);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        padding: 0.7rem 1.75rem;
        border-radius: 8px;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(38, 181, 209, 0.25);
        transition: var(--transition);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(38, 181, 209, 0.4);
        filter: brightness(1.05);
    }
</style>
