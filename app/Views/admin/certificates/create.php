<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/certificates" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Certificates</a>
</div>

<div class="form-card">
    <form action="/admin/certificates/create" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= csrf_field() ?>

        <div class="form-grid">
            <!-- Certificate Number -->
            <div class="form-group">
                <label for="certificate_number">Certificate Number <span class="required">*</span></label>
                <input type="text" id="certificate_number" name="certificate_number" required placeholder="e.g. TI-2026-INT-102" class="form-control">
                <span class="help-text">Direct identifier used for verification lookups.</span>
            </div>

            <!-- Holder Name -->
            <div class="form-group">
                <label for="holder_name">Recipient Full Name <span class="required">*</span></label>
                <input type="text" id="holder_name" name="holder_name" required placeholder="e.g. Sara Nisham" class="form-control">
            </div>

            <!-- Type -->
            <div class="form-group">
                <label for="type_id">Certificate Type <span class="required">*</span></label>
                <select id="type_id" name="type_id" required class="form-control">
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Issued Date -->
            <div class="form-group">
                <label for="issued_date">Issued Date <span class="required">*</span></label>
                <input type="date" id="issued_date" name="issued_date" required class="form-control">
            </div>

            <!-- Programme -->
            <div class="form-group">
                <label for="associated_programme">Programme / Duration</label>
                <input type="text" id="associated_programme" name="associated_programme" placeholder="e.g. Software Engineering (3 Months)" class="form-control">
                <span class="help-text">Describe the tenure, category, or course details.</span>
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="Active">Active (Verified & Accessible)</option>
                    <option value="Revoked">Revoked</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>

            <!-- PDF Upload -->
            <div class="form-group">
                <label for="file">Certificate PDF Document <span class="required">*</span></label>
                <input type="file" id="file" name="file" accept="application/pdf" required class="form-control">
                <span class="help-text">Select the official generated PDF file.</span>
            </div>

            <!-- Grade -->
            <div class="form-group">
                <label for="grade">Evaluation Grade</label>
                <input type="text" id="grade" name="grade" placeholder="e.g. Outstanding (A+) / Excellent" class="form-control">
            </div>

            <!-- Remarks -->
            <div class="form-group full-width">
                <label for="remarks">Remarks / Contribution Details</label>
                <textarea id="remarks" name="remarks" rows="3" placeholder="Briefly describe what this candidate achieved..." class="form-control"></textarea>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/certificates" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Issue Certificate <i class="fa-regular fa-floppy-disk" style="margin-left: 0.5rem;"></i></button>
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
