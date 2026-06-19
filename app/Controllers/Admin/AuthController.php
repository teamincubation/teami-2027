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
}
