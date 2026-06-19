<style>
    .verify-section {
        max-width: 800px;
        margin: 6rem auto;
        padding: 0 2rem;
    }

    @media (max-width: 768px) {
        .verify-section {
            margin: 3rem auto;
        }
    }

    .verify-box {
        background: var(--bg-surface);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 15px 35px rgba(38, 181, 209, 0.03);
    }

    .verify-title {
        text-align: center;
        margin-bottom: 2rem;
    }

    .verify-title h2 {
        font-size: 2.25rem;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .verify-title p {
        color: var(--text-muted);
        font-size: 0.975rem;
    }

    .form-group-verify {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 600px) {
        .form-group-verify {
            flex-direction: column;
        }
    }

    .input-verify {
        flex: 1;
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 8px;
        padding: 0.85rem 1.25rem;
        font-size: 1rem;
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        transition: var(--transition);
    }

    .input-verify:focus {
        outline: none;
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(38, 181, 209, 0.15);
    }

    .btn-verify {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 0.85rem 2rem;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(38, 181, 209, 0.2);
    }

    .btn-verify:hover {
        transform: translateY(-1px);
        filter: brightness(1.1);
        box-shadow: 0 4px 15px rgba(38, 181, 209, 0.4);
    }

    .sample-codes {
        background: rgba(38, 181, 209, 0.02);
        border: 1px dashed var(--border-glow);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .sample-code-badge {
        display: inline-block;
        background: rgba(38, 181, 209, 0.06);
        border: 1px solid var(--border-glow);
        padding: 0.2rem 0.6rem;
        border-radius: 4px;
        color: var(--text-main);
        font-weight: 600;
        cursor: pointer;
        margin: 0 0.25rem;
        transition: var(--transition);
    }

    .sample-code-badge:hover {
        background: var(--pastel-teal);
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Result Card */
    .result-card {
        margin-top: 3rem;
        background: var(--pastel-teal);
        border: 1px solid var(--border-active);
        border-radius: 12px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(38, 181, 209, 0.05);
    }

    .result-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, transparent 50%, rgba(38, 181, 209, 0.06) 50%);
        border-top-right-radius: 12px;
    }

    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid var(--border-glow);
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 600px) {
        .result-header {
            flex-direction: column;
            gap: 1rem;
        }
    }

    .badge-verified {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.6);
        color: var(--primary-dark);
        padding: 0.5rem 1rem;
        border-radius: 99px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid var(--border-active);
    }

    .result-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 600px) {
        .result-grid {
            grid-template-columns: 1fr;
        }
    }

    .result-label {
        font-size: 0.825rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .result-value {
        font-size: 1.05rem;
        color: var(--text-main);
        font-weight: 600;
    }

    .result-alert {
        margin-top: 3rem;
        background: rgba(239, 68, 68, 0.03);
        border: 1px solid rgba(239, 68, 68, 0.15);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        color: #991b1b;
    }

    .result-alert i {
        font-size: 1.75rem;
        color: #ef4444;
    }
</style>

<div class="verify-section">
    <div class="verify-box">
        <div class="verify-title">
            <h2>Verify Certificate</h2>
            <p>Enter the student certificate code to verify credentials from the central database.</p>
        </div>
        
        <form method="POST" action="/verify">
            <div class="form-group-verify">
                <input type="text" name="certificate_code" id="certificate_code" class="input-verify" placeholder="e.g. TI-2026-INT-101" required value="<?= htmlspecialchars($code ?? '') ?>">
                <button type="submit" class="btn-verify">
                    <i class="fa-solid fa-search" style="margin-right: 0.5rem;"></i>Verify Now
                </button>
            </div>
        </form>
        
        <div class="sample-codes">
            <span>Demo codes to test: </span>
            <span class="sample-code-badge" onclick="fillCode('TI-2026-INT-101')">TI-2026-INT-101</span>
            <span class="sample-code-badge" onclick="fillCode('TI-2026-VOL-202')">TI-2026-VOL-202</span>
        </div>

        <?php if ($searched): ?>
            <?php if ($result): ?>
                <div class="result-card">
                    <div class="result-header">
                        <div>
                            <h3 style="font-size: 1.5rem; color:var(--text-main); font-family:'Outfit', sans-serif;"><?= htmlspecialchars($result['name']) ?></h3>
                            <p style="color: var(--primary-dark); font-weight: 600; font-size: 0.95rem; margin-top: 0.25rem;">
                                <i class="fa-solid fa-award" style="margin-right: 0.4rem;"></i><?= htmlspecialchars($result['type']) ?>
                            </p>
                        </div>
                        <div class="badge-verified">
                            <i class="fa-solid fa-circle-check"></i> Verified Authenticated
                        </div>
                    </div>
                    
                    <div class="result-grid">
                        <div>
                            <div class="result-label">Duration</div>
                            <div class="result-value"><?= htmlspecialchars($result['duration']) ?></div>
                        </div>
                        <div>
                            <div class="result-label">Issued At</div>
                            <div class="result-value"><?= htmlspecialchars($result['issued_at']) ?></div>
                        </div>
                        <div>
                            <div class="result-label">Evaluation Grade</div>
                            <div class="result-value"><?= htmlspecialchars($result['grade']) ?></div>
                        </div>
                        <div>
                            <div class="result-label">Registry Method</div>
                            <div class="result-value">
                                <?php if ($is_mocked): ?>
                                    <span style="color: var(--secondary)">Mock Sandbox System</span>
                                <?php else: ?>
                                    <span style="color: var(--primary-hover)">Primary Database SQL Registry</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-glow);">
                        <div class="result-label">Performance Details</div>
                        <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6;"><?= htmlspecialchars($result['description']) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="result-alert">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <div>
                        <h4 style="font-family:'Outfit', sans-serif; font-weight:600; font-size: 1.1rem; margin-bottom: 0.25rem;">Verification Failed</h4>
                        <p style="font-size: 0.9rem; opacity: 0.95;"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function fillCode(code) {
        document.getElementById('certificate_code').value = code;
    }
</script>
