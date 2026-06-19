<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/incubants" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Portal</a>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert-box alert-error">
            <div><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert-box alert-success">
            <div><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- Detail Card -->
    <div class="crud-card" style="padding: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-glow); padding-bottom: 1.5rem; margin-bottom: 2rem;">
            <div>
                <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--text-main);"><?= htmlspecialchars($app['full_name'] ?: 'Volunteer Details') ?></h2>
                <p style="color: var(--secondary); font-weight: 600; font-size: 0.95rem; margin-top: 0.25rem;"><i class="fa-solid fa-handshake-angle"></i> Candidate Volunteer Signup</p>
            </div>
            <span class="status-indicator-badge status-<?= strtolower($app['status']) ?>">
                Current Status: <?= ucfirst(htmlspecialchars($app['status'])) ?>
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div>
                <div class="detail-label">Email Address</div>
                <div class="detail-value"><?= htmlspecialchars($app['email']) ?></div>
            </div>
            <div>
                <div class="detail-label">Mobile Number</div>
                <div class="detail-value"><?= htmlspecialchars($app['mobile'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="detail-label">Gender / DOB</div>
                <div class="detail-value"><?= htmlspecialchars($app['gender'] ?? 'N/A') ?> / <?= $app['dob'] ? date('d M Y', strtotime($app['dob'])) : 'N/A' ?></div>
            </div>
            <div>
                <div class="detail-label">Languages Spoken</div>
                <div class="detail-value"><?= htmlspecialchars($app['languages'] ?? 'N/A') ?></div>
            </div>
            <div style="grid-column: span 2;">
                <div class="detail-label">Education / Institution</div>
                <div class="detail-value"><?= htmlspecialchars($app['education'] ?? 'N/A') ?> – <?= htmlspecialchars($app['institution'] ?? 'N/A') ?></div>
            </div>
            <div>
                <div class="detail-label">Emergency Contact Name</div>
                <div class="detail-value"><?= htmlspecialchars($app['emergency_contact_name']) ?></div>
            </div>
            <div>
                <div class="detail-label">Emergency Contact Mobile</div>
                <div class="detail-value"><code><?= htmlspecialchars($app['emergency_contact_phone']) ?></code></div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-glow); padding-top: 2rem; margin-bottom: 2rem;">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Areas of Interest</h3>
                    <div style="background-color: #fafbfc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-glow); font-size: 0.95rem; color: var(--text-muted);"><?= nl2br(htmlspecialchars($app['areas_of_interest'] ?? 'N/A')) ?></div>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Skills & Talents</h3>
                    <div style="background-color: #fafbfc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-glow); font-size: 0.95rem; color: var(--text-muted);"><?= nl2br(htmlspecialchars($app['skills'] ?? 'N/A')) ?></div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Time Availability</h3>
                    <div style="background-color: #fafbfc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-glow); font-size: 0.95rem; color: var(--text-muted);"><?= nl2br(htmlspecialchars($app['availability'] ?? 'N/A')) ?></div>
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Preferred NGO Projects</h3>
                    <div style="background-color: #fafbfc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-glow); font-size: 0.95rem; color: var(--text-muted);"><?= nl2br(htmlspecialchars($app['preferred_projects'] ?? 'N/A')) ?></div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Previous Volunteering Experience</h3>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; background-color: #fafbfc; padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-glow);">
                    <?= nl2br(htmlspecialchars($app['previous_experience'] ?? 'None declared.')) ?>
                </p>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-glow); padding-top: 2rem; display: flex; align-items: center; gap: 1.5rem;">
            <?php if (!empty($app['resume_file'])): ?>
                <a href="<?= media_url($app['resume_file']) ?>" target="_blank" class="btn-builder" style="text-decoration:none; background-color: var(--pastel-teal); color: var(--primary-dark); border-color: var(--border-active); font-weight:700;">
                    <i class="fa-solid fa-file-pdf" style="margin-right: 0.5rem; font-size:1.1rem;"></i> View Candidate CV / Credentials
                </a>
            <?php else: ?>
                <span style="font-size: 0.85rem; color: var(--text-muted); italic"><i class="fa-solid fa-circle-info"></i> No resume uploaded. Contact volunteer directly.</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Review Form Card -->
    <div class="crud-card" style="padding: 2.5rem; margin-top: 1rem;">
        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glow); padding-bottom: 0.75rem;"><i class="fa-solid fa-user-pen" style="color: var(--primary);"></i> Match Volunteer</h3>
        
        <form action="/admin/incubants/status-volunteer/<?= $app['id'] ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
                <div class="form-group" style="max-width: 300px;">
                    <label for="status">Application Status <span class="required">*</span></label>
                    <select id="status" name="status" required class="form-control">
                        <option value="submitted" <?= $app['status'] === 'submitted' ? 'selected' : '' ?>>Submitted (Pending review)</option>
                        <option value="approved" <?= $app['status'] === 'approved' ? 'selected' : '' ?>>Approved & Matched</option>
                        <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="submit" class="btn-submit">Update Application Record <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
            </div>
        </form>
    </div>
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
    .crud-card {
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }
    .detail-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .detail-value {
        font-size: 0.975rem;
        color: var(--text-main);
        font-weight: 600;
    }
    .btn-builder {
        background-color: #f1f5f9;
        color: var(--text-main);
        font-weight: 600;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-size: 0.95rem;
        border: 1px solid var(--border-glow);
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
    }
    .btn-builder:hover {
        background-color: var(--pastel-blue);
        color: #0369a1;
        border-color: var(--border-active);
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
    .status-indicator-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .status-submitted { background-color: var(--pastel-blue); color: #0369a1; }
    .status-approved { background-color: var(--pastel-green); color: #166534; }
    .status-rejected { background-color: var(--pastel-pink); color: #991b1b; }
</style>
