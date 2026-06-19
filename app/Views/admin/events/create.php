<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/events" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Events</a>
</div>

<div class="form-card">
    <form action="/admin/events/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Event Title -->
            <div class="form-group full-width">
                <label for="title">Event Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" required placeholder="e.g. Alchemy Career Conclave 2026" class="form-control">
                <span class="help-text">Name of the webinar, seminar, or workshop.</span>
            </div>

            <!-- Associated Project -->
            <div class="form-group">
                <label for="project_id">Associated Project</label>
                <select id="project_id" name="project_id" class="form-control">
                    <option value="">-- No Specific Project --</option>
                    <?php foreach ($projects as $proj): ?>
                        <option value="<?= $proj['id'] ?>"><?= htmlspecialchars($proj['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="help-text">Link this event to an ongoing NGO program.</span>
            </div>

            <!-- Event Type -->
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select id="event_type" name="event_type" class="form-control">
                    <option value="UPCOMING">Upcoming Event (Accepting Registrations)</option>
                    <option value="PAST">Past Event (Archive & Testimonials)</option>
                </select>
                <span class="help-text">Determines layout category.</span>
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label for="start_date">Start Date & Time <span class="required">*</span></label>
                <input type="datetime-local" id="start_date" name="start_date" required class="form-control">
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label for="end_date">End Date & Time <span class="required">*</span></label>
                <input type="datetime-local" id="end_date" name="end_date" required class="form-control">
            </div>

            <!-- Venue -->
            <div class="form-group">
                <label for="venue">Physical Venue</label>
                <input type="text" id="venue" name="venue" placeholder="e.g. Himayathul Hall, Calicut" class="form-control">
                <span class="help-text">Leave blank if this is an online-only event.</span>
            </div>

            <!-- Online Platform -->
            <div class="form-group">
                <label for="online_platform">Online Platform</label>
                <input type="text" id="online_platform" name="online_platform" placeholder="e.g. Zoom / Google Meet" class="form-control">
                <span class="help-text">Specify the meeting host platform.</span>
            </div>

            <!-- Meeting Link -->
            <div class="form-group full-width">
                <label for="meeting_link">Meeting/Stream URL</label>
                <input type="url" id="meeting_link" name="meeting_link" placeholder="https://zoom.us/j/..." class="form-control">
                <span class="help-text">Direct access link for online webinars.</span>
            </div>

            <!-- Location Details -->
            <div class="form-group">
                <label for="location">Geographic Location</label>
                <input type="text" id="location" name="location" placeholder="e.g. Kerala, India" class="form-control">
                <span class="help-text">Used for map or demographic summaries.</span>
            </div>

            <!-- Banner Upload -->
            <div class="form-group">
                <label for="banner">Banner Image</label>
                <input type="file" id="banner" name="banner" accept="image/*" class="form-control">
                <span class="help-text">Recommended: 1200x630px landscape (JPEG/PNG).</span>
            </div>

            <!-- Registration Windows -->
            <div class="form-group">
                <label for="reg_open_date">Registration Opens</label>
                <input type="datetime-local" id="reg_open_date" name="reg_open_date" class="form-control">
            </div>

            <div class="form-group">
                <label for="reg_close_date">Registration Closes</label>
                <input type="datetime-local" id="reg_close_date" name="reg_close_date" class="form-control">
            </div>

            <!-- Seat Limit & Fees -->
            <div class="form-group">
                <label for="seat_limit">Seat Limit</label>
                <input type="number" id="seat_limit" name="seat_limit" min="1" placeholder="e.g. 100 (Blank for Unlimited)" class="form-control">
            </div>

            <div class="form-group">
                <label for="fee">Event Registration Fee (₹)</label>
                <input type="number" id="fee" name="fee" min="0" step="0.01" value="0.00" class="form-control">
                <span class="help-text">Leave at 0.00 for free events.</span>
            </div>

            <!-- Dynamic Settings -->
            <div class="form-group full-width" style="display: flex; flex-wrap: wrap; gap: 2rem; margin-top: 1rem; border-top: 1px solid var(--border-glow); padding-top: 1.5rem;">
                <label class="switch-container">
                    <input type="checkbox" id="waiting_list_enabled" name="waiting_list_enabled" value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Enable Waiting List</span>
                </label>
                <label class="switch-container">
                    <input type="checkbox" id="is_free" name="is_free" checked value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Free Event</span>
                </label>
                <label class="switch-container">
                    <input type="checkbox" id="certificate_eligible" name="certificate_eligible" value="1">
                    <span class="switch-slider"></span>
                    <span style="font-weight: 600; color: var(--text-main); font-size: 0.95rem;">Issues Participation Certificates</span>
                </label>
            </div>

            <!-- Eligibility -->
            <div class="form-group full-width">
                <label for="eligibility">Eligibility Criteria</label>
                <textarea id="eligibility" name="eligibility" rows="2" placeholder="e.g. Final year PG students or freshers..." class="form-control"></textarea>
            </div>

            <!-- Coordinators and Speakers -->
            <div class="form-group">
                <label for="coordinators">Coordinators</label>
                <input type="text" id="coordinators" name="coordinators" placeholder="e.g. Dr. Salim K., Mr. Hashim" class="form-control">
            </div>

            <div class="form-group">
                <label for="speakers">Key Speakers</label>
                <input type="text" id="speakers" name="speakers" placeholder="e.g. Prof. Joseph (IIT-B), Sara Althaf" class="form-control">
            </div>

            <!-- Detailed Description -->
            <div class="form-group full-width">
                <label for="description">Detailed Description</label>
                <textarea id="description" name="description" rows="5" placeholder="Detailed program schedule, instructions..." class="form-control"></textarea>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Publication Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="draft">Draft (Hidden)</option>
                    <option value="published">Published (Live)</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/events" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Create Event <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
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
