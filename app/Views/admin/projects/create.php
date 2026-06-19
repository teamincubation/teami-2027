<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/projects" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Projects</a>
</div>

<div class="form-card">
    <form action="/admin/projects/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Project Name -->
            <div class="form-group">
                <label for="name">Project Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required placeholder="e.g. NEST Remediation / PEPP Prep" class="form-control">
                <span class="help-text">Name of the project initiative.</span>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category / Sector</label>
                <input type="text" id="category" name="category" placeholder="e.g. Remedial Education / Career Guidance" class="form-control">
                <span class="help-text">General classification tag.</span>
            </div>

            <!-- Short Description -->
            <div class="form-group full-width">
                <label for="short_description">Brief Snippet Description <span class="required">*</span></label>
                <input type="text" id="short_description" name="short_description" required placeholder="A short one-sentence hook summary..." class="form-control">
                <span class="help-text">Displayed on grid listings. Keep it brief (under 160 characters).</span>
            </div>

            <!-- Objectives -->
            <div class="form-group">
                <label for="objectives">Core Objectives (Bullet list)</label>
                <textarea id="objectives" name="objectives" rows="4" placeholder="- Objective 1&#10;- Objective 2" class="form-control"></textarea>
                <span class="help-text">Summarize the main goals of the project.</span>
            </div>

            <!-- Target Beneficiaries -->
            <div class="form-group">
                <label for="target_beneficiaries">Target Beneficiaries</label>
                <textarea id="target_beneficiaries" name="target_beneficiaries" rows="4" placeholder="Marginalized school students in Calicut..." class="form-control"></textarea>
                <span class="help-text">Who benefits from this program.</span>
            </div>

            <!-- Full Detailed Description -->
            <div class="form-group full-width">
                <label for="full_description">Full Project Description <span class="required">*</span></label>
                <textarea id="full_description" name="full_description" required rows="8" placeholder="Provide a thorough, formatted description of the program, execution strategy, and outcomes..." class="form-control"></textarea>
                <span class="help-text">Detailed explanation shown on the public project detail page.</span>
            </div>

            <!-- Location -->
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="e.g. Calicut / Hybrid / Remote" class="form-control">
                <span class="help-text">Primary geographical scope.</span>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Project Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="planning">Planning / Draft</option>
                    <option value="active" selected>Active / Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="suspended">Suspended / On Hold</option>
                </select>
                <span class="help-text">Current implementation state.</span>
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
                <span class="help-text">Optional project initiation date.</span>
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
                <span class="help-text">Leave blank if the project is ongoing.</span>
            </div>

            <!-- Banner Image Upload -->
            <div class="form-group">
                <label for="banner_image">Hero Banner Image (1200x500 px)</label>
                <input type="file" id="banner_image" name="banner_image" accept="image/jpeg,image/png,image/webp" class="form-control file-input">
                <span class="help-text">Header banner displayed on detail pages. Max 5MB.</span>
            </div>

            <!-- Featured Image Upload -->
            <div class="form-group">
                <label for="featured_image">Featured Grid Photo (400x300 px)</label>
                <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/webp" class="form-control file-input">
                <span class="help-text">Thumbnail card image. Max 5MB.</span>
            </div>

            <!-- Featured Status Toggle -->
            <div class="form-group" style="display: flex; align-items: center; padding-top: 1.75rem;">
                <label class="switch-container">
                    <input type="checkbox" id="featured" name="featured" value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Showcase as Featured Project</span>
                </label>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/projects" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Create Project <i class="fa-solid fa-circle-check" style="margin-left: 0.5rem;"></i></button>
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
