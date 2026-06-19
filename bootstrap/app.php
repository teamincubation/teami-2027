<?php

// Prevent direct access to bootstrap file
if (!defined('BOOTSTRAPPED')) {
    define('BOOTSTRAPPED', true);
}

// 1. Setup Composer Autoloader
$autoloader = dirname(__DIR__) . '/vendor/autoload.php';
if (!file_exists($autoloader)) {
    die("Composer autoloader not found. Please run 'composer install' in the project directory.");
}
require_once $autoloader;

// 2. Load Environment Variables from .env
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

// 3. Initialize Configuration Container
class AppConfig {
    private static array $configs = [];

    public static function load(): void {
        $configPath = dirname(__DIR__) . '/config/';
        foreach (glob($configPath . '*.php') as $file) {
            $key = basename($file, '.php');
            self::$configs[$key] = require $file;
        }
    }

    public static function get(string $key, $default = null) {
        $parts = explode('.', $key);
        $group = array_shift($parts);
        
        if (!isset(self::$configs[$group])) {
            return $default;
        }
        
        $current = self::$configs[$group];
        foreach ($parts as $part) {
            if (!is_array($current) || !isset($current[$part])) {
                return $default;
            }
            $current = $current[$part];
        }
        
        return $current;
    }
}
AppConfig::load();

// 4. Setup Custom Logging & Error Handling
$logDir = AppConfig::get('app.paths.logs');
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

ini_set('log_errors', 1);
ini_set('error_log', $logDir . '/php_error.log');

if (AppConfig::get('app.debug')) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Custom Exception Handler
set_exception_handler(function (Throwable $exception) use ($logDir) {
    $message = "[" . date('Y-m-d H:i:s') . "] " . get_class($exception) . ": " . $exception->getMessage() . 
               " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n" . 
               $exception->getTraceAsString() . "\n\n";
    @file_put_contents($logDir . '/exceptions.log', $message, FILE_APPEND);

    // Clean any open output buffers
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code(500);
    
    if (AppConfig::get('app.debug')) {
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p><strong>Exception:</strong> " . htmlspecialchars($exception->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($exception->getFile()) . " on line " . $exception->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    } else {
        // Show user-friendly error page
        $errorFile = dirname(__DIR__) . '/app/Views/errors/500.php';
        if (file_exists($errorFile)) {
            require $errorFile;
        } else {
            echo "<h1>500 Internal Server Error</h1>";
            echo "<p>An unexpected error occurred. Please try again later.</p>";
        }
    }
    exit;
});

// Custom Error Handler to convert PHP errors to Exceptions
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return; // This error code is not included in error_reporting
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// 5. Initialize Secure PHP Sessions
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    
    // Cookie lifetime (default 30 days if remember me is set, otherwise until closed)
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
}

// Global Exception/Error Logger Helper
function logMessage(string $level, string $message): void {
    $logDir = AppConfig::get('app.paths.logs');
    $logFile = $logDir . '/app_' . date('Y-m-d') . '.log';
    $timestamp = date('Y-m-d H:i:s');
    $formatted = "[{$timestamp}] [{$level}] {$message}\n";
    @file_put_contents($logFile, $formatted, FILE_APPEND);
}
