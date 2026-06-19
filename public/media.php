<?php

// Front-facing media reader for Team Incubation
// Serves images/banners from persistent storage safely

$file = $_GET['file'] ?? '';

if (empty($file)) {
    header("HTTP/1.0 400 Bad Request");
    echo "File parameter is missing.";
    exit;
}

$storagePath = dirname(__DIR__) . '/storage/app';

// Resolve full path
$fullPath = realpath($storagePath . '/' . $file);
$basePath = realpath($storagePath);

// Security Check: Block directory traversal
if (!$fullPath || !$basePath || !str_starts_with($fullPath, $basePath)) {
    header("HTTP/1.0 403 Forbidden");
    echo "Access denied.";
    exit;
}

// Security Check: Verify file exists and is a file
if (!file_exists($fullPath) || !is_file($fullPath)) {
    header("HTTP/1.0 404 Not Found");
    echo "File not found.";
    exit;
}

// Get file mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $fullPath);
finfo_close($finfo);

// Security Check: Only serve standard web assets publicly (images, pdfs)
$allowedMimes = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'image/svg+xml',
    'application/pdf'
];

if (!in_array($mimeType, $allowedMimes)) {
    header("HTTP/1.0 403 Forbidden");
    echo "FileType not allowed for public view.";
    exit;
}

// Disable caching for dev, enable standard cache headers for production
header("Content-Type: " . $mimeType);
header("Content-Length: " . filesize($fullPath));
header("Cache-Control: public, max-age=86400"); // Cache for 1 day

// Stream file
readfile($fullPath);
exit;
