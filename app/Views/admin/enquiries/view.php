<div class="actions-header" style="margin-bottom: 2rem;">
    <a href="/admin/enquiries" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Enquiries</a>
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

    <!-- Message Details Card -->
    <div class="crud-card" style="padding: 2.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-glow); padding-bottom: 1.5rem; margin-bottom: 2rem;">
            <div>
                <h2 style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.5rem; color: var(--text-main);"><?= htmlspecialchars($enquiry['subject']) ?></h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">From: <strong><?= htmlspecialchars($enquiry['name']) ?></strong> (<?= htmlspecialchars($enquiry['email']) ?>)</p>
            </div>
            <span class="status-indicator-badge status-<?= htmlspecialchars($enquiry['status']) ?>">
                Status: <?= ucfirst(htmlspecialchars($enquiry['status'])) ?>
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div>
                <div class="detail-label">Sender Mobile</div>
                <div class="detail-value"><code><?= htmlspecialchars($enquiry['mobile'] ?: 'N/A') ?></code></div>
            </div>
            <div>
                <div class="detail-label">Received At</div>
                <div class="detail-value"><?= date('d M Y, h:i A', strtotime($enquiry['created_at'])) ?></div>
            </div>
        </div>

        <div style="border-top: 1px solid var(--border-glow); padding-top: 1.5rem; margin-bottom: 1.5rem;">
            <div class="detail-label" style="margin-bottom: 0.5rem;">Inquiry Message</div>
            <p style="font-size: 0.95rem; color: var(--text-main); line-height: 1.6; background-color: #fafbfc; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-glow);">
                <?= nl2br(htmlspecialchars($enquiry['message'])) ?>
            </p>
        </div>

        <?php if ($enquiry['status'] === 'replied'): ?>
            <div style="border-top: 1px dashed var(--border-active); padding-top: 1.5rem; margin-top: 2rem;">
                <div class="detail-label" style="margin-bottom: 0.5rem; color: var(--primary); font-weight:700;"><i class="fa-solid fa-reply"></i> Sent Reply (<?= date('d M Y, h:i A', strtotime($enquiry['replied_at'])) ?>)</div>
                <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; background-color: var(--pastel-teal); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(38, 181, 209, 0.15);">
                    <?= nl2br(htmlspecialchars($enquiry['reply_content'])) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Reply Input Card (Only show if not already replied) -->
    <?php if ($enquiry['status'] !== 'replied'): ?>
        <div class="crud-card" style="padding: 2.5rem; margin-top: 1rem;">
            <h3 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text-main); margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-glow); padding-bottom: 0.75rem;"><i class="fa-solid fa-paper-plane" style="color: var(--primary);"></i> Send Reply Email</h3>
            
            <form action="/admin/enquiries/reply/<?= $enquiry['id'] ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="reply_content">Message Response Body</label>
                    <textarea id="reply_content" name="reply_content" required rows="6" placeholder="Type your response here. An email will be queued and sent to <?= htmlspecialchars($enquiry['email']) ?>." class="form-control"></textarea>
                </div>

                <div style="display:flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn-submit">Send Response Email <i class="fa-regular fa-paper-plane" style="margin-left: 0.5rem;"></i></button>
                </div>
            </form>
        </div>
    <?php endif; ?>
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
    .status-unread { background-color: var(--pastel-pink); color: #991b1b; }
    .status-read { background-color: var(--pastel-blue); color: #0369a1; }
    .status-replied { background-color: var(--pastel-green); color: #166534; }
</style>
