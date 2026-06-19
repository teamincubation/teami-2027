<?php

namespace App\Middleware;

class SecurityHeadersMiddleware {
    /**
     * Apply secure response headers.
     */
    public function handle(): bool {
        if (headers_sent()) {
            return true;
        }

        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Strict Content Security Policy allowing necessary Bootstrap, Google Fonts, and Google OAuth CDNs
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://accounts.google.com https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https://*.googleusercontent.com https://accounts.google.com; " .
               "frame-src 'self' https://accounts.google.com; " .
               "connect-src 'self' https://accounts.google.com;";
               
        header("Content-Security-Policy: " . $csp);
        
        return true;
    }
}
