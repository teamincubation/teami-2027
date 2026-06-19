<?php

namespace App\Services;

use App\Models\BaseModel;
use PDO;

class AuthService extends BaseModel {

    /**
     * Authenticate user credentials.
     */
    public function login(string $email, string $password, bool $remember = false): bool {
        // Retrieve user and role name
        $user = $this->selectOne(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ? AND u.deleted_at IS NULL", 
            [$email]
        );

        if (!$user) {
            $this->trackSession(null, false, 'password', $email . " - User not found");
            return false;
        }

        // Check verification and suspended status
        if ($user['status'] === 'suspended') {
            $this->trackSession($user['id'], false, 'password', "Account suspended");
            return false;
        }

        // Verify password hash
        if (!password_verify($password, $user['password_hash'])) {
            $this->trackSession($user['id'], false, 'password', "Incorrect password");
            return false;
        }

        // Start authenticated session
        $this->startUserSession($user);

        // Handle remember cookie if checked
        if ($remember) {
            $this->createRememberToken($user['id']);
        }

        // Log audit trail
        $this->logActivity($user['id'], 'LOGIN', 'User logged in successfully via credentials.');
        $this->trackSession($user['id'], true, 'password');

        return true;
    }

    /**
     * Start authenticated session and register details.
     */
    public function startUserSession(array $user): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // 2. Fetch role permissions
        $permissions = $this->select(
            "SELECT p.name 
             FROM permissions p 
             JOIN role_permissions rp ON p.id = rp.permission_id 
             WHERE rp.role_id = ?", 
            [$user['role_id']]
        );
        $permissionsList = array_column($permissions, 'name');

        // 3. Register user variables in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'role_name' => $user['role_name'],
            'status' => $user['status']
        ];
        $_SESSION['permissions'] = $permissionsList;
    }

    /**
     * Register a new user and profile.
     */
    public function register(string $email, string $password, string $fullName, string $mobile): array {
        // Check if email or mobile exists
        $exists = $this->selectOne("SELECT id FROM users WHERE email = ?", [$email]);
        if ($exists) {
            return ['success' => false, 'message' => 'Email address is already registered.'];
        }

        $mobileExists = $this->selectOne("SELECT id FROM profiles WHERE mobile = ?", [$mobile]);
        if ($mobileExists) {
            return ['success' => false, 'message' => 'Mobile number is already registered.'];
        }

        self::beginTransaction();
        try {
            // Hash password
            $passHash = password_hash($password, PASSWORD_BCRYPT);
            
            // Generate verification token
            $rawToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);

            // 1. Insert user (role_id 12 represents 'Incubant')
            $userId = $this->insert('users', [
                'email' => $email,
                'password_hash' => $passHash,
                'role_id' => 12,
                'status' => 'pending_verification',
                'verification_token_hash' => $tokenHash
            ]);

            // 2. Insert Incubant profile
            $this->insert('profiles', [
                'user_id' => $userId,
                'full_name' => $fullName,
                'mobile' => $mobile
            ]);

            self::commit();

            // 3. Queue activation email
            $verifyLink = rtrim(config('app.url'), '/') . '/verify-email?token=' . $rawToken;
            $emailBody = "<h3>Welcome to Team Incubation!</h3>" .
                         "<p>Hello " . htmlspecialchars($fullName) . ",</p>" .
                         "<p>Please click the link below to verify your email address and activate your account:</p>" .
                         "<p><a href='{$verifyLink}'>{$verifyLink}</a></p>" .
                         "<p>Thank you,<br>Team Incubation</p>";
            
            $queue = new EmailQueue();
            $queue->enqueue($email, $fullName, "Verify Your Email - Team Incubation", $emailBody);

            return ['success' => true, 'message' => 'Registration successful! Verification email sent.'];
        } catch (\Exception $e) {
            self::rollBack();
            logMessage('ERROR', "User registration failed: " . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred during registration. Please try again.'];
        }
    }

    /**
     * Verify email address using the raw token.
     */
    public function verifyEmail(string $rawToken): bool {
        $tokenHash = hash('sha256', $rawToken);
        $user = $this->selectOne("SELECT id FROM users WHERE verification_token_hash = ?", [$tokenHash]);

        if (!$user) {
            return false;
        }

        $this->update(
            'users', 
            [
                'status' => 'active', 
                'email_verified_at' => date('Y-m-d H:i:s'), 
                'verification_token_hash' => null
            ], 
            'id = ?', 
            [$user['id']]
        );

        $this->logActivity($user['id'], 'VERIFY_EMAIL', 'Email verified and account activated.');
        return true;
    }

    /**
     * Trigger a password reset request.
     */
    public function forgotPassword(string $email): bool {
        $user = $this->selectOne("SELECT id, email FROM users WHERE email = ? AND deleted_at IS NULL", [$email]);
        if (!$user) {
            return false; // Silence checks to prevent account enumeration checks? 
            // In professional systems, we usually tell the user a link is sent if email exists.
        }

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->update(
            'users',
            [
                'reset_token_hash' => $tokenHash,
                'reset_token_expires_at' => $expires
            ],
            'id = ?',
            [$user['id']]
        );

        // Fetch name
        $profile = $this->selectOne("SELECT full_name FROM profiles WHERE user_id = ?", [$user['id']]);
        $name = $profile['full_name'] ?? 'Incubant';

        $resetLink = rtrim(config('app.url'), '/') . '/reset-password?token=' . $rawToken;
        $emailBody = "<h3>Password Reset Request</h3>" .
                     "<p>Hello " . htmlspecialchars($name) . ",</p>" .
                     "<p>We received a request to reset your account password. Click the link below to set a new password (expires in 1 hour):</p>" .
                     "<p><a href='{$resetLink}'>{$resetLink}</a></p>" .
                     "<p>If you did not request this, you can safely ignore this email.</p>";

        $queue = new EmailQueue();
        $queue->enqueue($email, $name, "Reset Your Password - Team Incubation", $emailBody);

        return true;
    }

    /**
     * Reset password using token.
     */
    public function resetPassword(string $rawToken, string $newPassword): bool {
        $tokenHash = hash('sha256', $rawToken);
        $user = $this->selectOne(
            "SELECT id FROM users 
             WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()", 
            [$tokenHash]
        );

        if (!$user) {
            return false;
        }

        $passHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->update(
            'users',
            [
                'password_hash' => $passHash,
                'reset_token_hash' => null,
                'reset_token_expires_at' => null
            ],
            'id = ?',
            [$user['id']]
        );

        $this->logActivity($user['id'], 'RESET_PASSWORD', 'Password reset successfully.');
        return true;
    }

    /**
     * Perform Logout.
     */
    public function logout(): void {
        $user = auth_user();
        if ($user) {
            // Delete active remember tokens
            $this->delete('remember_tokens', 'user_id = ?', [$user['id']]);
            $this->logActivity($user['id'], 'LOGOUT', 'User logged out.');
        }

        // Clear remember cookie
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', '', time() - 3600, '/');
        }

        // Clear session array and destroy
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        @session_destroy();
    }

    /**
     * Validate remember-me cookies on bootstrap if session is empty.
     */
    public function checkRememberMe(): void {
        if (is_auth() || !isset($_COOKIE['remember_me'])) {
            return;
        }

        $token = $_COOKIE['remember_me'];
        $tokenHash = hash('sha256', $token);

        $remember = $this->selectOne(
            "SELECT r.user_id, u.*, rl.name as role_name 
             FROM remember_tokens r 
             JOIN users u ON r.user_id = u.id 
             JOIN roles rl ON u.role_id = rl.id
             WHERE r.token_hash = ? AND r.expires_at > NOW() AND u.deleted_at IS NULL", 
            [$tokenHash]
        );

        if ($remember) {
            // Auto login
            $this->startUserSession($remember);
            $this->logActivity($remember['user_id'], 'REMEMBER_LOGIN', 'User auto-logged in via remember-me cookie.');
            $this->trackSession($remember['user_id'], true, 'remember_cookie');
        }
    }

    /**
     * Create remember me tokens.
     */
    private function createRememberToken(int $userId): void {
        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expires = time() + (86400 * 30); // 30 days
        $dbExpires = date('Y-m-d H:i:s', $expires);

        $this->insert('remember_tokens', [
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $dbExpires
        ]);

        // Secure remember cookie
        setcookie('remember_me', $rawToken, $expires, '/', '', 
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', 
            true
        );
    }

    /**
     * Log user activity trail in system database.
     */
    public function logActivity(?int $userId, string $action, string $details): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $this->insert('audit_logs', [
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => $ip,
            'user_agent' => substr($agent, 0, 255)
        ]);
    }

    /**
     * Logs user session metadata (device, browser, OS, approx location).
     */
    private function trackSession(?int $userId, bool $success, string $method, ?string $details = null): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        // Parse User Agent details
        $uaParser = $this->parseUserAgent($agent);

        $this->insert('user_sessions', [
            'user_id' => $userId,
            'session_id' => session_id() ?: 'none',
            'ip_address' => $ip,
            'user_agent' => substr($agent, 0, 500),
            'device_type' => $uaParser['device'],
            'operating_system' => $uaParser['os'],
            'browser' => $uaParser['browser'],
            'location_approx' => $this->getApproxLocation($ip),
            'auth_method' => $method,
            'login_status' => $success ? 'success' : 'failed'
        ]);

        if ($details && $userId) {
            $this->logActivity($userId, 'LOGIN_FAILED', $details);
        }
    }

    /**
     * Extract browser, OS, and device type from User-Agent headers.
     */
    private function parseUserAgent(string $userAgent): array {
        $browser = 'Unknown Browser';
        $os = 'Unknown OS';
        $device = 'Desktop';

        // 1. Parse Browser
        if (preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Netscape/i', $userAgent)) {
            $browser = 'Netscape';
        }

        // 2. Parse OS
        if (preg_match('/windows|win32/i', $userAgent)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $os = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $os = 'Linux';
        } elseif (preg_match('/iphone|ipad/i', $userAgent)) {
            $os = 'iOS';
            $device = preg_match('/ipad/i', $userAgent) ? 'Tablet' : 'Mobile';
        } elseif (preg_match('/android/i', $userAgent)) {
            $os = 'Android';
            $device = preg_match('/mobile/i', $userAgent) ? 'Mobile' : 'Tablet';
        }

        // 3. Simple mobile indicator check
        if ($device === 'Desktop' && preg_match('/mobi/i', $userAgent)) {
            $device = 'Mobile';
        }

        return ['browser' => $browser, 'os' => $os, 'device' => $device];
    }

    /**
     * Query API to fetch approximate location (Non-blocking fallback).
     */
    private function getApproxLocation(string $ip): string {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return 'Localhost';
        }

        // Simple placeholder geolocation, or call free ipinfo api with short timeout
        // Because of network timeouts we will use a quick curl with 1s limit.
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/{$ip}?fields=country,regionName,city");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1); // 1 second timeout limit
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['city'], $data['regionName'], $data['country'])) {
                    return "{$data['city']}, {$data['regionName']}, {$data['country']}";
                }
            }
        } catch (\Throwable $t) {
            // Fail silently, fallback
        }
        return 'Unknown Location';
    }
}
