<?php

namespace App\Services;

use Exception;

class FileStorage {

    private string $storagePath;

    public function __construct() {
        // Load storage path from environment or default to local storage folder
        $this->storagePath = $_ENV['STORAGE_PATH'] ?? dirname(dirname(__DIR__)) . '/storage/app';
        if (!is_dir($this->storagePath)) {
            @mkdir($this->storagePath, 0755, true);
        }
    }

    /**
     * Get the base storage path.
     */
    public function getStoragePath(): string {
        return $this->storagePath;
    }

    /**
     * Upload a file securely.
     * 
     * @param array $fileElement PHP $_FILES structure element (e.g. $_FILES['resume'])
     * @param string $subFolder Target subfolder in storage (e.g. 'resumes', 'banners')
     * @param array $allowedMimes Array of allowed mime types
     * @return string Relative key path (e.g. 'banners/abcdef123.jpg')
     */
    public function store(array $fileElement, string $subFolder, array $allowedMimes = []): string {
        if (!isset($fileElement['error']) || is_array($fileElement['error'])) {
            throw new Exception("Invalid file upload parameters.");
        }

        switch ($fileElement['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception("No file sent.");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception("Exceeded file size limit.");
            default:
                throw new Exception("Unknown upload error.");
        }

        // Validate file size (e.g. max 10MB)
        if ($fileElement['size'] > 10 * 1024 * 1024) {
            throw new Exception("File size exceeds 10MB limit.");
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileElement['tmp_name']);
        finfo_close($finfo);

        if (!empty($allowedMimes) && !in_array($mimeType, $allowedMimes)) {
            throw new Exception("Invalid file type: {$mimeType}. Allowed: " . implode(', ', $allowedMimes));
        }

        // Sanitize subfolder and create full path
        $subFolder = trim(str_replace(['..', '\\', '/'], '', $subFolder));
        $targetDirectory = $this->storagePath . '/' . $subFolder;
        if (!is_dir($targetDirectory)) {
            @mkdir($targetDirectory, 0755, true);
        }

        // Generate a safe unique name
        $extension = pathinfo($fileElement['name'], PATHINFO_EXTENSION);
        $safeName = bin2hex(random_bytes(16)) . '.' . strtolower($extension);
        $targetFile = $targetDirectory . '/' . $safeName;

        // Verify path traversal check
        if (!str_starts_with(realpath($targetDirectory), realpath($this->storagePath))) {
            throw new Exception("Directory traversal attempt detected.");
        }

        if (!move_uploaded_file($fileElement['tmp_name'], $targetFile)) {
            throw new Exception("Failed to move uploaded file.");
        }

        return $subFolder . '/' . $safeName;
    }

    /**
     * Delete a file from storage.
     */
    public function delete(string $relativeKey): bool {
        $fullPath = $this->storagePath . '/' . ltrim($relativeKey, '/\\');
        
        // Prevent path traversal
        $realBase = realpath($this->storagePath);
        $realFile = realpath(dirname($fullPath));
        
        if ($realBase && $realFile && !str_starts_with($realFile, $realBase)) {
            return false;
        }

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
