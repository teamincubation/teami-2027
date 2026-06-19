<?php
require_once dirname(__DIR__) . '/bootstrap/app.php';

header('Content-Type: text/plain; charset=utf-8');

echo "Google OAuth Configuration Test\n";
echo "===============================\n\n";

// Check if .env file exists
$envPath = dirname(__DIR__) . '/.env';
echo ".env file exists: " . (file_exists($envPath) ? "YES" : "NO") . "\n";
if (file_exists($envPath)) {
    echo ".env size: " . filesize($envPath) . " bytes\n";
}

echo "\nChecking loaded variables:\n";
echo "--------------------------\n";

$clientId = $_ENV['GOOGLE_CLIENT_ID'] ?? 'NOT SET IN $_ENV';
$clientIdServer = $_SERVER['GOOGLE_CLIENT_ID'] ?? 'NOT SET IN $_SERVER';
$configVal = config('google.client_id') ?? 'NOT SET IN config()';

echo "Client ID in \$_ENV: " . (empty($clientId) ? "EMPTY" : substr($clientId, 0, 10) . "..." . substr($clientId, -10)) . "\n";
echo "Client ID in \$_SERVER: " . (empty($clientIdServer) ? "EMPTY" : substr($clientIdServer, 0, 10) . "..." . substr($clientIdServer, -10)) . "\n";
echo "Client ID in config(): " . (empty($configVal) ? "EMPTY" : substr($configVal, 0, 10) . "..." . substr($configVal, -10)) . "\n";

echo "\nRedirect URI in config(): " . (config('google.redirect_uri') ?? 'NOT SET') . "\n";

// Print raw contents of .env (excluding the secret key value for security)
echo "\nRaw .env keys present:\n";
echo "----------------------\n";
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (empty(trim($line)) || strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        $key = trim($parts[0]);
        $val = isset($parts[1]) ? trim($parts[1]) : '';
        echo " - {$key}: " . (empty($val) ? "EMPTY" : "SET (length: " . strlen($val) . ")") . "\n";
    }
}
