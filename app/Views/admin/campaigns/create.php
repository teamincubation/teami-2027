<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/campaigns" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Campaigns</a>
</div>

<div class="form-card">
    <form action="/admin/campaigns/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Campaign Title -->
            <div class="form-group full-width">
                <label for="title">Campaign Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" required placeholder="e.g. Urava Happy Reading Audio Library Drive" class="form-control">
                <span class="help-text">A clear, engaging name for the NGO campaign.</span>
            </div>

            <!-- Campaign Type -->
            <div class="form-group">
                <label for="type">Campaign Type</label>
                <select id="type" name="type" class="form-control">
                    <option value="internal">Internal (Run entirely by Team Incubation)</option>
                    <option value="collaborative">Collaborative (Joint venture with other NGOs)</option>
                </select>
            </div>

            <!-- Collaborating Organizations -->
            <div class="form-group">
                <label for="collaborating_organizations">Collaborating Organizations</label>
                <input type="text" id="collaborating_organizations" name="collaborating_organizations" placeholder="e.g. Ability Centre, Pulikkal" class="form-control">
                <span class="help-text">Leave blank if this is an internal campaign.</span>
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
            </div>

            <!-- Location -->
            <div class="form-group">
                <label for="location">Location / Coverage Area</label>
                <input type="text" id="location" name="location" placeholder="e.g. Malappuram district / Kerala" class="form-control">
            </div>

            <!-- Target Group -->
            <div class="form-group">
                <label for="target_group">Target Beneficiary Group</label>
                <input type="text" id="target_group" name="target_group" placeholder="e.g. Print and visually impaired students" class="form-control">
            </div>

            <!-- Banner Upload -->
            <div class="form-group">
                <label for="banner">Campaign Banner Image</label>
                <input type="file" id="banner" name="banner" accept="image/*" class="form-control">
                <span class="help-text">Recommended: 1200x630px landscape (JPEG/PNG).</span>
            </div>

            <!-- Volunteer Requirements -->
            <div class="form-group">
                <label for="volunteer_requirements">Volunteer Requirements Description</label>
                <input type="text" id="volunteer_requirements" name="volunteer_requirements" placeholder="e.g. Needs 20 audio recorders who can speak Malayalam fluently" class="form-control">
            </div>

            <!-- Goals / Stats -->
            <div class="form-group">
                <label for="target_stat">Target Goal (Metric Value)</label>
                <input type="number" id="target_stat" name="target_stat" min="0" step="1" value="0" class="form-control">
                <span class="help-text">e.g. 500 books to record or 1000 hours.</span>
            </div>

            <div class="form-group">
                <label for="achieved_stat">Achieved / Progress (Metric Value)</label>
                <input type="number" id="achieved_stat" name="achieved_stat" min="0" step="1" value="0" class="form-control">
            </div>

            <!-- Coordinators -->
            <div class="form-group">
                <label for="coordinators">Campaign Coordinators</label>
                <input type="text" id="coordinators" name="coordinators" placeholder="e.g. Salim K., Nisham K." class="form-control">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="upcoming">Upcoming</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <!-- Dynamic Settings -->
            <div class="form-group" style="display: flex; align-items: center; padding-top: 1.75rem;">
                <label class="switch-container">
                    <input type="checkbox" id="featured" name="featured" value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Featured Campaign (Showcase on Landing Page)</span>
                </label>
            </div>

            <!-- Objectives -->
            <div class="form-group full-width">
                <label for="objectives">Objectives (Bullet list or short paragraph)</label>
                <textarea id="objectives" name="objectives" rows="2" placeholder="Describe the direct goals or targets of the drive..." class="form-control"></textarea>
            </div>

            <!-- Detailed Description -->
            <div class="form-group full-width">
                <label for="description">Detailed Description <span class="required">*</span></label>
                <textarea id="description" name="description" required rows="5" placeholder="Detailed details of campaigns, impact statements, partners, schedules..." class="form-control"></textarea>
            </div>

            <!-- Public Report link -->
            <div class="form-group full-width">
                <label for="public_report">Public Report / Drive Outcomes Link</label>
                <input type="url" id="public_report" name="public_report" placeholder="https://docs.google.com/..." class="form-control">
                <span class="help-text">Optional link to a summary pdf report of the campaign's success.</span>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/campaigns" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Create Campaign <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
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
