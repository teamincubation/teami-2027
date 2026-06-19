<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/certificates" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Certificates</a>
</div>

<div class="form-card">
    <h2 style="font-family:'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--text-main);">Bulk CSV Certificate Importer</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Upload a CSV file containing your certificate registry spreadsheet. Existing records matching the certificate number will be updated.</p>

    <!-- CSV Template Instructions -->
    <div style="background-color: var(--pastel-blue); border: 1px solid var(--border-glow); padding: 1.5rem; border-radius: 12px; margin-bottom: 2.5rem; color: #0369a1;">
        <h4 style="font-family: 'Outfit', sans-serif; font-weight: 700; margin-bottom: 0.5rem;"><i class="fa-solid fa-circle-info"></i> CSV Format Guideline</h4>
        <p style="font-size: 0.85rem; line-height: 1.5; margin-bottom: 1rem;">Your uploaded CSV file must contain columns in the following exact order:</p>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left; background: #ffffff; border-radius: 6px; overflow: hidden; border: 1px solid rgba(14, 165, 233, 0.1);">
                <thead>
                    <tr style="background-color: #f1f5f9; color: var(--text-main);">
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 1: Number*</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 2: Name*</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 3: Type Code*</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 4: Issued Date*</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 5: Programme</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 6: PDF Path</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 7: Grade</th>
                        <th style="padding: 6px 12px; border: 1px solid #e2e8f0;">Col 8: Remarks</th>
                    </tr>
                </thead>
                <tbody style="color: var(--text-main);">
                    <tr>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;"><code>TI-2026-INT-102</code></td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">Sara Nisham</td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;"><code>INTERNSHIP</code></td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">2026-06-20</td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">Web Design (3M)</td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">certificates/sara.pdf</td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">Outstanding (A+)</td>
                        <td style="padding: 6px 12px; border: 1px solid #e2e8f0;">Designed web MVC</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p style="font-size: 0.8rem; margin-top: 1rem; opacity: 0.85;">* Required columns. Type codes can be: <code>INTERNSHIP</code>, <code>VOLUNTEER</code>, <code>EVENT_PARTICIPATION</code>, <code>APPRECIATION</code>, <code>COURSE_COMPLETION</code>, <code>EXPERIENCE</code>, <code>RECOMMENDATION</code>.</p>
    </div>

    <form action="/admin/certificates/import" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-grid" style="display:grid; grid-template-columns: 1fr; gap:1.5rem;">
            <div class="form-group" style="max-width: 450px;">
                <label for="csv_file">Select CSV File <span class="required">*</span></label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required class="form-control">
                <span class="help-text">Ensure the file size is under 5MB.</span>
            </div>
        </div>

        <div class="form-actions-bar">
            <a href="/admin/certificates" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Process Import <i class="fa-solid fa-file-import" style="margin-left: 0.5rem;"></i></button>
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
