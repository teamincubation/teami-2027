<?php

namespace App\Middleware;

class CSRFMiddleware {
    /**
     * Handle CSRF checks for POST requests.
     */
    public function handle(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sessionToken = $_SESSION['csrf_token'] ?? '';
            $postToken = $_POST['csrf_token'] ?? '';

            if (empty($sessionToken) || empty($postToken) || !hash_equals($sessionToken, $postToken)) {
                http_response_code(403);
                $errorFile = dirname(dirname(__DIR__)) . '/app/Views/errors/403.php';
                if (file_exists($errorFile)) {
                    require_once $errorFile;
                } else {
                    echo "<h1>403 Forbidden</h1>";
                    echo "<p>Security check failed. Cross-Site Request Forgery (CSRF) token validation failed.</p>";
                }
                exit;
            }
        }
        return true;
    }
}
