<?php

namespace App\Middleware;

class RoleMiddleware {
    /**
     * Handle permissions check for the matching route.
     * 
     * @param string ...$permissions Required permissions. Super Admin bypasses all checks.
     */
    public function handle(...$permissions): bool {
        if (!is_auth()) {
            $_SESSION['flash_error'] = "Authentication required.";
            redirect('/login');
            return false;
        }

        $user = auth_user();
        
        // Super Admin bypasses all authorization checks
        if ($user['role_name'] === 'Super Admin') {
            return true;
        }

        // Retrieve user permissions stored in session (populated on login)
        $userPermissions = session('permissions') ?? [];

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true; // Authorized
            }
        }

        // Access Denied
        http_response_code(403);
        $errorFile = dirname(dirname(__DIR__)) . '/app/Views/errors/403.php';
        if (file_exists($errorFile)) {
            require_once $errorFile;
        } else {
            echo "<h1>403 Forbidden</h1>";
            echo "<p>Access Denied. You do not have the permissions required to perform this action.</p>";
        }
        exit;
    }
}
