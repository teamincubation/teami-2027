<?php

namespace App\Controllers;

use Exception;

abstract class BaseController {

    /**
     * Render a view file within a layout.
     * 
     * @param string $view Path to view inside app/Views/ (e.g. 'home' or 'admin/users')
     * @param array $data Variables to extract into the view
     * @param string $layout Path to layout inside app/Views/layouts/ (e.g. 'public', 'admin', 'dashboard')
     */
    protected function render(string $view, array $data = [], string $layout = 'public'): void {
        $viewFile = dirname(dirname(__DIR__)) . '/app/Views/' . ltrim($view, '/') . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("View file not found at: {$viewFile}");
        }

        // Scope data to view
        extract($data);

        // Capture inner view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render layout
        $layoutFile = dirname(dirname(__DIR__)) . '/app/Views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new Exception("Layout file not found at: {$layoutFile}");
        }

        require $layoutFile;
    }

    /**
     * Shortcut to send JSON response.
     */
    protected function json(array $data, int $status = 200): void {
        json($data, $status);
    }

    /**
     * Shortcut to redirect.
     */
    protected function redirect(string $url, int $statusCode = 302): void {
        redirect($url, $statusCode);
    }

    /**
     * Shortcut to validate form inputs.
     * 
     * @param array $data Form data array
     * @param array $rules Rules array e.g. ['email' => 'required|email']
     * @return array Array of error messages, empty if validation passes
     */
    protected function validate(array $data, array $rules): array {
        $validator = new \App\Validators\FormValidator($data);
        return $validator->validate($rules);
    }
}
