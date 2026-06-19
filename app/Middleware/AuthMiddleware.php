<?php

namespace App\Middleware;

class AuthMiddleware {
    /**
     * Ensure the user is authenticated.
     */
    public function handle(): bool {
        if (!is_auth()) {
            $_SESSION['flash_error'] = "Authentication required. Please sign in.";
            redirect('/auth/admin-login');
            return false;
        }
        return true;
    }
}
