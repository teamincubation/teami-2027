<?php
require_once dirname(__DIR__) . '/bootstrap/app.php';

header('Content-Type: text/plain; charset=utf-8');

echo "Detailed .env Content Analyzer\n";
echo "==============================\n\n";

$envPath = dirname(__DIR__) . '/.env';
if (!file_exists($envPath)) {
    echo "ERROR: .env file does not exist!\n";
    exit;
}

echo ".env File Size: " . filesize($envPath) . " bytes\n\n";

echo "File Lines:\n";
echo "-----------\n";
$lines = file($envPath, FILE_IGNORE_NEW_LINES);
foreach ($lines as $index => $line) {
    $trimmed = trim($line);
    $lineNum = $index + 1;
    
    // If it's a comment or empty line, print as-is
    if (empty($trimmed) || strpos($trimmed, '#') === 0) {
        echo sprintf("[%02d] %s\n", $lineNum, $line);
        continue;
    }
    
    // Parse key-value
    $parts = explode('=', $line, 2);
    $key = trim($parts[0]);
    $val = isset($parts[1]) ? trim($parts[1]) : '';
    
    // Mask value for security
    if (empty($val)) {
        $maskedVal = "EMPTY";
    } else {
        // Obfuscate but show structure
        $firstChar = substr($val, 0, 1);
        $lastChar = substr($val, -1);
        $len = strlen($val);
        $maskedVal = "SET (Length: {$len}, starts with '{$firstChar}', ends with '{$lastChar}')";
    }
    
    echo sprintf("[%02d] %s = %s\n", $lineNum, $key, $maskedVal);
}
