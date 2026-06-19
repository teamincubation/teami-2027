<style>
    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--bg-base);
        background-image: radial-gradient(at 0% 0%, rgba(38, 181, 209, 0.05) 0px, transparent 50%);
        padding: 2rem;
    }

    .auth-card {
        background: #ffffff;
        width: 100%;
        max-width: 440px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-glow);
        overflow: hidden;
    }

    .auth-header {
        padding: 2.5rem 2.5rem 1.5rem 2.5rem;
        text-align: center;
        border-bottom: 1px solid var(--border-glow);
    }

    .auth-header img {
        height: 48px;
        margin-bottom: 1.5rem;
    }

    .auth-header h2 {
        font-size: 1.5rem;
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .auth-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .auth-body {
        padding: 2.5rem;
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-success {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
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
        box-shadow: 0 0 0 3px var(--primary-glow);
    }

    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #ffffff;
        border: none;
        padding: 0.85rem;
        border-radius: 8px;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(38, 181, 209, 0.2);
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card" data-aos="zoom-in" data-aos-duration="400">
        <div class="auth-header">
            <img src="/images/logo.png" alt="Team Incubation Logo">
            <h2>Forgot Password</h2>
            <p>Enter your registered email address below to receive password reset instructions.</p>
        </div>
        
        <div class="auth-body">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['flash_errors']) && is_array($_SESSION['flash_errors'])): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <?php foreach ($_SESSION['flash_errors'] as $error): ?>
                            <?= htmlspecialchars($error) ?><br>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php unset($_SESSION['flash_errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <form action="/auth/forgot-password" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-icon-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Send Reset Link <i class="fa-solid fa-paper-plane" style="margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="/auth/admin-login" style="font-size: 0.9rem; color: var(--text-muted); text-decoration: none; transition: var(--transition);"><i class="fa-solid fa-arrow-left" style="margin-right: 0.25rem;"></i> Back to Login</a>
            </div>
        </div>
    </div>
</div>
