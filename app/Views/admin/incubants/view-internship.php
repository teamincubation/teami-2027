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
                <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--text-main);"><?= htmlspecialchars($app['full_name'] ?: 'Applicant Details') ?></h2>
                <p style="color: var(--primary); font-weight: 600; font-size: 0.95rem; margin-top: 0.25rem;"><i class="fa-solid fa-graduation-cap"></i> Application for: <?= htmlspecialchars($app['opportunity_title']) ?></p>
            </div>
            <span class="status-indicator-badge status-<?= strtolower(str_replace(' ', '-', $app['status'])) ?>">
                Current Status: <?= htmlspecialchars($app['status']) ?>
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
                <div class="detail-label">Education / Institution</div>
                <div class="detail-value"><?= htmlspecialchars($app['education'] ?? 'N/A') ?> – <?= htmlspecialchars($app['institution'] ?? 'N/A') ?></div>
            </div>
            <div style="grid-column: span 2;">
                <div class="detail-label">Address</div>
                <div class="detail-value"><?= nl2br(htmlspecialchars($app['address'] ?? 'N/A')) ?></div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-glow); padding-top: 2rem; margin-bottom: 2rem;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-bottom: 1rem;">Statement of Purpose (SOP)</h3>
            <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; background-color: #fafbfc; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-glow);">
                <?= nl2br(htmlspecialchars($app['sop'])) ?>
            </p>
        </div>

        <!-- Custom Questionnaire Answers -->
        <?php 
            $answers = !empty($app['answers']) ? json_decode($app['answers'], true) : [];
        ?>
        <?php if (!empty($answers)): ?>
            <div style="border-top: 1px solid var(--border-glow); padding-top: 2rem; margin-bottom: 2rem;">
                <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-bottom: 1rem;">Dynamic Form Answers</h3>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php foreach ($answers as $qText => $ans): ?>
                        <div style="background-color: #fafbfc; padding: 1.25rem; border-radius: 8px; border: 1px solid var(--border-glow);">
                            <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main); margin-bottom: 0.5rem;"><?= htmlspecialchars($qText) ?></div>
                            <div style="font-size: 0.95rem; color: var(--text-muted);">
                                <?php if (is_array($ans)): ?>
                                    <?= implode(', ', array_map('htmlspecialchars', $ans)) ?>
                                <?php elseif (str_starts_with($ans, 'resumes/') || str_starts_with($ans, 'certificates/') || str_starts_with($ans, 'events/')): ?>
                                    <a href="<?= media_url($ans) ?>" target="_blank" style="color: var(--primary); text-decoration:none;"><i class="fa-solid fa-file-arrow-down"></i> View Submitted File</a>
                                <?php else: ?>
                                    <?= nl2br(htmlspecialchars($ans)) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div style="border-top: 1px solid var(--border-glow); padding-top: 2rem; display: flex; align-items: center; gap: 1.5rem;">
            <a href="<?= media_url($app['resume_file']) ?>" target="_blank" class="btn-builder" style="text-decoration:none; background-color: var(--pastel-teal); color: var(--primary-dark); border-color: var(--border-active); font-weight:700;">
                <i class="fa-solid fa-file-pdf" style="margin-right: 0.5rem; font-size:1.1rem;"></i> View Candidate Resume / CV
            </a>
        </div>
    </div>

    <!-- Review Form Card -->
    <div class="crud-card" style="padding: 2.5rem; margin-top: 1rem;">
        <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glow); padding-bottom: 0.75rem;"><i class="fa-solid fa-user-pen" style="color: var(--primary);"></i> Administrative Action</h3>
        
        <form action="/admin/incubants/status-internship/<?= $app['id'] ?>" method="POST" autocomplete="off">
            <?= csrf_field() ?>

            <div class="form-grid" style="display:grid; grid-template-columns:1fr; gap:1.5rem;">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                    <!-- Update Status -->
                    <div class="form-group">
                        <label for="status">Application Status <span class="required">*</span></label>
                        <select id="status" name="status" required class="form-control">
                            <option value="Submitted" <?= $app['status'] === 'Submitted' ? 'selected' : '' ?>>Submitted</option>
                            <option value="Under Review" <?= $app['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                            <option value="Shortlisted" <?= $app['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                            <option value="Interview Scheduled" <?= $app['status'] === 'Interview Scheduled' ? 'selected' : '' ?>>Interview Scheduled</option>
                            <option value="Selected" <?= $app['status'] === 'Selected' ? 'selected' : '' ?>>Selected (Approved)</option>
                            <option value="Onboarding Pending" <?= $app['status'] === 'Onboarding Pending' ? 'selected' : '' ?>>Onboarding Pending</option>
                            <option value="Active" <?= $app['status'] === 'Active' ? 'selected' : '' ?>>Active Intern</option>
                            <option value="On Hold" <?= $app['status'] === 'On Hold' ? 'selected' : '' ?>>On Hold</option>
                            <option value="Completed" <?= $app['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="Rejected" <?= $app['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Assign Intern ID -->
                    <div class="form-group">
                        <label for="intern_id">Intern ID (Registry reference)</label>
                        <input type="text" id="intern_id" name="intern_id" value="<?= htmlspecialchars($app['intern_id'] ?? '') ?>" placeholder="e.g. TI-2026-INT-102" class="form-control">
                        <span class="help-text">Assigned when candidate is selected.</span>
                    </div>

                    <!-- Rating -->
                    <div class="form-group">
                        <label for="performance_rating">Performance Rating</label>
                        <select id="performance_rating" name="performance_rating" class="form-control">
                            <option value="">-- No Rating Yet --</option>
                            <option value="5" <?= $app['performance_rating'] == 5 ? 'selected' : '' ?>>5 - Outstanding (A+)</option>
                            <option value="4" <?= $app['performance_rating'] == 4 ? 'selected' : '' ?>>4 - Excellent (A)</option>
                            <option value="3" <?= $app['performance_rating'] == 3 ? 'selected' : '' ?>>3 - Good (B)</option>
                            <option value="2" <?= $app['performance_rating'] == 2 ? 'selected' : '' ?>>2 - Satisfactory (C)</option>
                            <option value="1" <?= $app['performance_rating'] == 1 ? 'selected' : '' ?>>1 - Unsatisfactory (F)</option>
                        </select>
                    </div>
                </div>

                <!-- Supervisor Feedback -->
                <div class="form-group">
                    <label for="supervisor_feedback">Administrative Feedback / Review Remarks</label>
                    <textarea id="supervisor_feedback" name="supervisor_feedback" rows="3" placeholder="Enter comments or onboarding interview notes..." class="form-control"><?= htmlspecialchars($app['supervisor_feedback'] ?? '') ?></textarea>
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
        font-size: 0.9rem;
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
    .status-under-review { background-color: var(--pastel-yellow); color: #b45309; }
    .status-shortlisted { background-color: var(--pastel-purple); color: #6b21a8; }
    .status-selected, .status-approved, .status-active { background-color: var(--pastel-green); color: #166534; }
    .status-completed { background-color: #e2e8f0; color: #475569; }
    .status-rejected { background-color: var(--pastel-pink); color: #991b1b; }
    .help-text { font-size: 0.75rem; color: var(--text-muted); }
</style>
