<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Models\BaseModel;

class AuthController extends BaseController {
    
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    /**
     * Show login form.
     */
    public function loginForm(): void {
        if (is_auth()) {
            $this->redirect('/admin/dashboard');
        }

        // Generate custom CAPTCHA numbers
        if (empty($_SESSION['login_captcha_num1']) || empty($_SESSION['login_captcha_num2'])) {
            $_SESSION['login_captcha_num1'] = rand(1, 9);
            $_SESSION['login_captcha_num2'] = rand(1, 9);
        }

        $this->render('admin/login', [
            'title' => 'Admin Portal Login | Team Incubation',
            'num1' => $_SESSION['login_captcha_num1'],
            'num2' => $_SESSION['login_captcha_num2']
        ], 'blank'); // Using blank layout
    }

    /**
     * Handle login post request.
     */
    public function login(): void {
        if (is_auth()) {
            $this->redirect('/admin/dashboard');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $captcha = trim($_POST['captcha'] ?? '');
        $remember = isset($_POST['remember']);

        $errors = [];

        // 1. Validation
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        if (empty($password)) {
            $errors[] = "Please enter your password.";
        }

        // 2. CAPTCHA verification
        $num1 = $_SESSION['login_captcha_num1'] ?? 0;
        $num2 = $_SESSION['login_captcha_num2'] ?? 0;
        if (empty($captcha) || intval($captcha) !== ($num1 + $num2)) {
            $errors[] = "Security check failed. Please solve the math sum correctly.";
        }

        // Reset CAPTCHA for next attempts
        $_SESSION['login_captcha_num1'] = rand(1, 9);
        $_SESSION['login_captcha_num2'] = rand(1, 9);

        if (!empty($errors)) {
            $_SESSION['flash_errors'] = $errors;
            $this->redirect('/auth/admin-login');
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $cutoffTime = date('Y-m-d H:i:s', strtotime('-15 minutes'));

        // 3. Check login rate limiting
        // We run queries through a temporary BaseModel wrapper to count failures
        $db = BaseModel::getConnection();
        
        $stmtCount = $db->prepare("
            SELECT COUNT(*) 
            FROM login_attempts 
            WHERE ip_address = ? AND email = ? AND attempted_at > ?
        ");
        $stmtCount->execute([$ip, $email, $cutoffTime]);
        $failedAttempts = (int) $stmtCount->fetchColumn();

        if ($failedAttempts >= 5) {
            $_SESSION['flash_errors'] = ["Too many failed login attempts. You are locked out for 15 minutes."];
            $this->redirect('/auth/admin-login');
        }

        // 4. Authenticate
        if ($this->authService->login($email, $password, $remember)) {
            // Success: clear failed attempts
            $stmtDel = $db->prepare("DELETE FROM login_attempts WHERE ip_address = ? AND email = ?");
            $stmtDel->execute([$ip, $email]);

            $this->redirect('/admin/dashboard');
        } else {
            // Fail: record login attempt
            $stmtIns = $db->prepare("INSERT INTO login_attempts (ip_address, email) VALUES (?, ?)");
            $stmtIns->execute([$ip, $email]);

            $_SESSION['flash_errors'] = ["Invalid email or password."];
            $this->redirect('/auth/admin-login');
        }
    }

    /**
     * Handle logout.
     */
    public function logout(): void {
        $this->authService->logout();
        $_SESSION['flash_success'] = "You have been logged out successfully.";
        $this->redirect('/auth/admin-login');
    }

    public function forgotPasswordForm(): void {
        $this->render('admin/forgot-password', [
            'title' => 'Forgot Password | Team Incubation'
        ], 'blank');
    }

    public function sendResetLink(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $_SESSION['flash_errors'] = ["Email is required."];
            $this->redirect('/auth/forgot-password');
        }

        $token = $this->authService->forgotPassword($email);
        
        if ($token) {
            $resetLink = rtrim(config('app.url'), '/') . '/reset-password?token=' . $token;
            $_SESSION['flash_success'] = "Password reset link generated! Since email delivery is currently disabled/failing, you can use the link below to reset your password immediately:<br><br><a href='{$resetLink}' class='btn btn-sm btn-primary' style='display:inline-block; padding: 8px 16px; background:#0d6efd; color:#fff; text-decoration:none; border-radius:4px; margin-top:8px;'>Reset Password Now</a>";
        } else {
            $_SESSION['flash_success'] = "If that email is in our database, we have sent a password reset link to it.";
        }
        $this->redirect('/auth/admin-login');
    }

    public function resetPasswordForm(): void {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $_SESSION['flash_errors'] = ["Invalid or missing reset token."];
            $this->redirect('/auth/admin-login');
        }

        $this->render('admin/reset-password', [
            'title' => 'Reset Password | Team Incubation',
            'token' => $token
        ], 'blank');
    }

    public function updatePassword(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password) || empty($confirm)) {
            $_SESSION['flash_errors'] = ["All fields are required."];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if ($password !== $confirm) {
            $_SESSION['flash_errors'] = ["Passwords do not match."];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if (strlen($password) < 8) {
            $_SESSION['flash_errors'] = ["Password must be at least 8 characters long."];
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        if ($this->authService->resetPassword($token, $password)) {
            $_SESSION['flash_success'] = "Password has been reset successfully. You can now log in.";
            $this->redirect('/auth/admin-login');
        } else {
            $_SESSION['flash_errors'] = ["The reset link is invalid or has expired."];
            $this->redirect('/auth/forgot-password');
        }
    }

    public function googleRedirect(): void {
        $googleAuth = new \App\Services\GoogleAuth();
        $authUrl = $googleAuth->getAuthUrl();
        $this->redirect($authUrl);
    }

    public function googleCallback(): void {
        $code = $_GET['code'] ?? '';
        if (empty($code)) {
            $_SESSION['flash_errors'] = ["Google authentication failed: Authorization code missing."];
            $this->redirect('/auth/admin-login');
        }

        $googleAuth = new \App\Services\GoogleAuth();
        $userInfo = $googleAuth->getUserInfo($code);

        if (!$userInfo || empty($userInfo['email'])) {
            $_SESSION['flash_errors'] = ["Google authentication failed: Could not retrieve user profile."];
            $this->redirect('/auth/admin-login');
        }

        $email = $userInfo['email'];

        // Retrieve user and role name from users database
        $db = BaseModel::getConnection();
        $stmt = $db->prepare("
            SELECT u.*, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.email = ? AND u.deleted_at IS NULL
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['flash_errors'] = ["Access denied. The Google account '{$email}' is not registered as an administrator."];
            $this->redirect('/auth/admin-login');
        }

        if ($user['status'] === 'suspended') {
            $_SESSION['flash_errors'] = ["Account suspended."];
            $this->redirect('/auth/admin-login');
        }

        // If the user does not have a google_id stored yet, save it!
        if (empty($user['google_id'])) {
            $stmtUpdate = $db->prepare("UPDATE users SET google_id = ? WHERE id = ?");
            $stmtUpdate->execute([$userInfo['google_id'], $user['id']]);
        }

        // Start authenticated session
        $this->authService->startUserSession($user);

        // Record audit trail log
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $stmtLog = $db->prepare("
            INSERT INTO audit_logs (user_id, action, details, ip_address, user_agent) 
            VALUES (?, 'GOOGLE_LOGIN', 'User logged in successfully via Google OAuth.', ?, ?)
        ");
        $stmtLog->execute([$user['id'], $ip, substr($agent, 0, 255)]);

        // Redirect to dashboard
        $this->redirect('/admin/dashboard');
    }
}
