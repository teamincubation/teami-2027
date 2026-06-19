<?php

// Define constant to prevent direct access to sub-files
define('ENTRY_POINT', true);

// 1. Require the Bootstrapper
require_once dirname(__DIR__) . '/bootstrap/app.php';

// 2. Load and Apply Global Security Headers Middleware
$securityHeaders = new App\Middleware\SecurityHeadersMiddleware();
$securityHeaders->handle();

// 3. Load Routing Engine and Dispatch
$router = require_once dirname(__DIR__) . '/routes/web.php';

// Catch errors and parse path
$router->dispatch();
