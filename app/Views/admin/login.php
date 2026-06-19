<div class="login-wrapper" style="width: 100%; max-width: 440px; padding: 2rem;">
    <!-- Brand Logo -->
    <div style="text-align: center; margin-bottom: 2rem;">
        <img src="/images/logo.png" alt="Team Incubation Logo" style="height: 60px; width: auto; display: inline-block;">
    </div>

    <!-- Login Card -->
    <div style="background: var(--bg-card); padding: 2.5rem; border-radius: 16px; border: 1px solid var(--border-glow); box-shadow: 0 10px 30px rgba(38, 181, 209, 0.08); position: relative; overflow: hidden;">
        <!-- Banner/Accents -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--primary), var(--secondary));"></div>
        
        <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.75rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; text-align: center;">Welcome Back</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem; text-align: center; margin-bottom: 2rem;">Log in to manage the Team Incubation platform</p>

        <!-- Flash Message Alerts -->
        <?php if (!empty($_SESSION['flash_errors'])): ?>
            <div style="background: var(--pastel-pink); border: 1px solid #fecdd3; color: #e11d48; padding: 1rem; border-radius: 8px; font-size: 0.9rem; margin-bottom: 1.5rem;">
                <ul style="list-style: none; padding-left: 0; margin: 0;">
                    <?php foreach ($_SESSION['flash_errors'] as $err): ?>
                        <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 0.85rem;"></i>
                            <?= htmlspecialchars($err) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['flash_errors']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div style="background: var(--pastel-green); border: 1px solid #a7f3d0; color: #059669; padding: 1rem; border-radius: 8px; font-size: 0.9rem; margin-bottom: 1.5rem;">
                <p style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                    <i class="fa-solid fa-circle-check" style="font-size: 0.85rem;"></i>
                    <?= $_SESSION['flash_success'] ?>
                </p>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <!-- Google Login Button -->
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <a href="/auth/google" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; width: 100%; padding: 0.75rem; border: 1px solid var(--border-glow); border-radius: 8px; font-family: 'Outfit', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--text-main); background: #ffffff; text-decoration: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); transition: var(--transition); box-sizing: border-box;" onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.borderColor='var(--primary)';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='var(--border-glow)';">
                <svg width="18" height="18" viewBox="0 0 18 18" style="display: block;">
                    <path fill="#4285F4" d="M17.64 9.2c0-.63-.06-1.25-.16-1.84H9v3.47h4.84c-.21 1.12-.84 2.07-1.79 2.7v2.24h2.91c1.7-1.56 2.68-3.86 2.68-6.57z"/>
                    <path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.23l-2.91-2.24c-.8.54-1.84.87-3.05.87-2.35 0-4.33-1.59-5.04-3.73H.95v2.3A9 9 0 0 0 9 18z"/>
                    <path fill="#FBBC05" d="M3.96 10.67A5.4 5.4 0 0 1 3.6 9c0-.58.1-1.15.28-1.67V5.03H.95A9 9 0 0 0 0 9c0 1.45.35 2.82.95 4.03l3.01-2.36z"/>
                    <path fill="#EA4335" d="M9 3.58c1.32 0 2.5.45 3.44 1.35l2.58-2.58C13.47.8 11.43 0 9 0 5.48 0 2.44 2.03.95 5.03l3.01 2.36c.71-2.14 2.69-3.73 5.04-3.73z"/>
                </svg>
                Sign in with Google
            </a>
        </div>

        <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1.5rem; color: var(--text-muted); font-size: 0.85rem;">
            <div style="flex: 1; height: 1px; background: var(--border-glow);"></div>
            <span>or login with credentials</span>
            <div style="flex: 1; height: 1px; background: var(--border-glow);"></div>
        </div>

        <!-- Form -->
        <form action="/auth/admin-login" method="POST" autocomplete="off">
            <?= csrf_field() ?>

            <!-- Email -->
            <div style="margin-bottom: 1.25rem;">
                <label for="email" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">Email Address</label>
                <div style="position: relative;">
                    <i class="fa-regular fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.95rem;"></i>
                    <input type="email" id="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--border-glow); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-main); outline: none; background-color: #fafbfc; transition: var(--transition);">
                </div>
            </div>

            <!-- Password -->
            <div style="margin-bottom: 1.25rem;">
                <label for="password" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">Password</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.95rem;"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem; border: 1px solid var(--border-glow); border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 0.95rem; color: var(--text-main); outline: none; background-color: #fafbfc; transition: var(--transition);">
                </div>
            </div>
            
            <div style="text-align: right; margin-bottom: 1.5rem;">
                <a href="/auth/forgot-password" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 500;">Forgot Password?</a>
            </div>

            <!-- Math CAPTCHA Security Check -->
            <div style="margin-bottom: 1.5rem; background: var(--pastel-teal); padding: 1rem; border-radius: 8px; border: 1px solid rgba(38, 181, 209, 0.15);">
                <label for="captcha" style="display: block; font-size: 0.9rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">Security Check</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--primary); background: #ffffff; padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid var(--border-glow); white-space: nowrap; user-select: none;">
                        <?= intval($num1) ?> + <?= intval($num2) ?> = ?
                    </div>
                    <input type="text" id="captcha" name="captcha" required placeholder="Answer" style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid var(--border-glow); border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.95rem; outline: none; text-align: center; font-weight: 600; color: var(--text-main); transition: var(--transition);">
                </div>
            </div>

            <!-- Remember & Forgot -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; font-size: 0.875rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); cursor: pointer; user-select: none;">
                    <input type="checkbox" name="remember" style="accent-color: var(--primary); width: 16px; height: 16px; border-radius: 4px;">
                    Keep me signed in
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" style="width: 100%; padding: 0.75rem; border: none; border-radius: 8px; font-family: 'Outfit', sans-serif; font-size: 1rem; font-weight: 700; color: #ffffff; background: linear-gradient(135deg, var(--primary), var(--secondary)); box-shadow: 0 4px 15px rgba(38, 181, 209, 0.25); cursor: pointer; transition: var(--transition);">
                Log In <i class="fa-solid fa-arrow-right-to-bracket" style="margin-left: 0.5rem;"></i>
            </button>
        </form>
    </div>
</div>

<script>
    // Simple focus style behavior
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"], input[type="text"]');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.style.borderColor = 'var(--primary)';
            input.style.backgroundColor = '#ffffff';
            input.style.boxShadow = '0 0 0 3px rgba(38, 181, 209, 0.15)';
        });
        input.addEventListener('blur', () => {
            input.style.borderColor = 'var(--border-glow)';
            input.style.backgroundColor = '#fafbfc';
            input.style.boxShadow = 'none';
        });
    });
</script>
