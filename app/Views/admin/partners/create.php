<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/partners" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Partners</a>
</div>

<div class="form-card">
    <form action="/admin/partners/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Partner Name -->
            <div class="form-group">
                <label for="name">Partner / Organization Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" required placeholder="e.g. Ability Centre, Pulikkal" class="form-control">
                <span class="help-text">Name of the collaborating institute or corporate sponsor.</span>
            </div>

            <!-- Partner Logo -->
            <div class="form-group">
                <label for="logo">Partner Logo Image <span class="required">*</span></label>
                <input type="file" id="logo" name="logo" accept="image/*" required class="form-control">
                <span class="help-text">Recommended: 300x300px square transparent PNG.</span>
            </div>

            <!-- Website -->
            <div class="form-group">
                <label for="website">Website URL</label>
                <input type="url" id="website" name="website" placeholder="https://example.com" class="form-control">
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" placeholder="e.g. CSR Sponsor / Educational Partner" class="form-control">
            </div>

            <!-- Collaboration Start Date -->
            <div class="form-group">
                <label for="collaboration_start">Collaboration Start Date</label>
                <input type="date" id="collaboration_start" name="collaboration_start" class="form-control">
            </div>

            <!-- Collaboration End Date -->
            <div class="form-group">
                <label for="collaboration_end">Collaboration End Date</label>
                <input type="date" id="collaboration_end" name="collaboration_end" class="form-control">
                <span class="help-text">Leave blank if the association is ongoing.</span>
            </div>

            <!-- Display Order -->
            <div class="form-group">
                <label for="display_order">Display Order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="0" class="form-control">
                <span class="help-text">Determines position in the carousel slider.</span>
            </div>

            <!-- Toggle Settings -->
            <div class="form-group" style="display: flex; flex-direction: column; gap: 1rem; padding-top: 1.5rem;">
                <label class="switch-container">
                    <input type="checkbox" id="featured" name="featured" value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Featured Associate</span>
                </label>
                <label class="switch-container">
                    <input type="checkbox" id="active" name="active" checked value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Active in Carousel</span>
                </label>
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description">Partnership Description / Remarks</label>
                <textarea id="description" name="description" rows="3" placeholder="Brief details about the joint initiatives run together..." class="form-control"></textarea>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/partners" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Add Partner <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
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
    .back-link:hover { color: var(--primary); }
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
        .form-grid { grid-template-columns: 1fr 1fr; }
        .full-width { grid-column: span 2; }
    }
    .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
    .form-group label { font-size: 0.9rem; font-weight: 600; color: var(--text-main); }
    .required { color: #ef4444; }
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
    .help-text { font-size: 0.775rem; color: var(--text-muted); }
    .switch-container { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; user-select: none; }
    .switch-container input { display: none; }
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
    .switch-container input:checked + .switch-slider { background-color: var(--primary); }
    .switch-container input:checked + .switch-slider::before { transform: translateX(20px); }
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
    .btn-cancel:hover { background: #e2e8f0; color: var(--text-main); }
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
