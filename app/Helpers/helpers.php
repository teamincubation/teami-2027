<?php

use AppConfig as Config;

/**
 * Get configuration value.
 */
if (!function_exists('config')) {
    function config(string $key, $default = null) {
        return Config::get($key, $default);
    }
}

/**
 * Escape text for HTML output (XSS protection).
 */
if (!function_exists('esc')) {
    function esc(?string $text): string {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Generate or retrieve CSRF token.
 */
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

/**
 * Generate CSRF hidden input field.
 */
if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

/**
 * Redirect HTTP response.
 */
if (!function_exists('redirect')) {
    function redirect(string $url, int $statusCode = 302): void {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}

/**
 * Get or set session key values.
 */
if (!function_exists('session')) {
    function session(string $key = null, $value = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (is_null($key)) {
            return $_SESSION;
        }
        
        if (is_null($value)) {
            return $_SESSION[$key] ?? null;
        }
        
        $_SESSION[$key] = $value;
        return $value;
    }
}

/**
 * Get currently authenticated user.
 */
if (!function_exists('auth_user')) {
    function auth_user() {
        return session('user');
    }
}

/**
 * Check if user is authenticated.
 */
if (!function_exists('is_auth')) {
    function is_auth(): bool {
        return session('user') !== null;
    }
}

/**
 * Check if the user has a specific role name.
 */
if (!function_exists('has_role')) {
    function has_role($roles): bool {
        $user = auth_user();
        if (!$user) return false;
        
        if (is_array($roles)) {
            return in_array($user['role_name'], $roles);
        }
        return $user['role_name'] === $roles;
    }
}

/**
 * Check if the user has a specific permission.
 */
if (!function_exists('has_permission')) {
    function has_permission(string $permission): bool {
        $user = auth_user();
        if (!$user) return false;
        
        // Super Admin has all permissions bypass
        if ($user['role_name'] === 'Super Admin') {
            return true;
        }
        
        $permissions = session('permissions') ?? [];
        return in_array($permission, $permissions);
    }
}

/**
 * Helper to generate asset paths.
 */
if (!function_exists('asset')) {
    function asset(string $path): string {
        return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
    }
}

/**
 * Helper to generate public media path URLs (loads via public/media.php).
 */
if (!function_exists('media_url')) {
    function media_url(?string $relativeKey): string {
        if (empty($relativeKey)) {
            return asset('assets/images/placeholder.jpg'); // Fallback placeholder asset
        }
        
        // If it starts with http, it is external
        if (str_starts_with($relativeKey, 'http://') || str_starts_with($relativeKey, 'https://')) {
            return $relativeKey;
        }
        
        return rtrim(config('app.url'), '/') . '/media.php?file=' . urlencode($relativeKey);
    }
}

/**
 * Helper to render JSON response.
 */
if (!function_exists('json')) {
    function json(array $data, int $status = 200): void {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}

/**
 * Debug helper (Dump and Die).
 */
if (!function_exists('dd')) {
    function dd(...$vars): void {
        header('Content-Type: text/html; charset=utf-8');
        echo '<div style="background-color:#1e1e1e; color:#d4d4d4; padding:20px; font-family:monospace; border-radius:8px; margin:20px;">';
        foreach ($vars as $var) {
            echo '<pre style="background-color:#2d2d2d; padding:15px; border-radius:4px; overflow-x:auto;">';
            var_dump($var);
            echo '</pre>';
        }
        echo '</div>';
        exit;
    }
}
