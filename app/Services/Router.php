<?php

namespace App\Services;

use Exception;

class Router {
    private array $routes = [];
    private array $globalMiddleware = [];

    /**
     * Register a GET route.
     */
    public function get(string $path, string $handler, array $middleware = []): void {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Register a POST route.
     */
    public function post(string $path, string $handler, array $middleware = []): void {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Register global middleware that runs on all routes.
     */
    public function use(string $middleware): void {
        $this->globalMiddleware[] = $middleware;
    }

    /**
     * Add route to collection.
     */
    private function addRoute(string $method, string $path, string $handler, array $middleware): void {
        // Convert route variables like {slug} to regex capturing groups
        // e.g. /projects/{slug} -> ^/projects/(?P<slug>[^/]+)$
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[$method][] = [
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch the current request.
     */
    public function dispatch(): void {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Strip query string from URI
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Decode URL components
        $requestUri = rawurldecode($requestUri);

        // Standardize ending slash (except for home route)
        if ($requestUri !== '/' && str_ends_with($requestUri, '/')) {
            $requestUri = rtrim($requestUri, '/');
        }

        $routesForMethod = $this->routes[$requestMethod] ?? [];
        $matchedRoute = null;
        $matches = [];

        foreach ($routesForMethod as $route) {
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                $matchedRoute = $route;
                break;
            }
        }

        if (!$matchedRoute) {
            $this->abort404();
            return;
        }

        // Filter out numeric array indices from preg_match results
        $routeParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // Run middleware chain: Global Middleware first, then Route Middleware
        $middlewareChain = array_merge($this->globalMiddleware, $matchedRoute['middleware']);
        
        foreach ($middlewareChain as $middleware) {
            $args = [];
            // Parse arguments in middleware e.g., "RoleMiddleware:manage_users"
            if (str_contains($middleware, ':')) {
                [$middlewareClass, $paramString] = explode(':', $middleware, 2);
                $args = explode(',', $paramString);
            } else {
                $middlewareClass = $middleware;
            }

            if (!class_exists($middlewareClass)) {
                throw new Exception("Middleware class '{$middlewareClass}' not found.");
            }

            $middlewareInstance = new $middlewareClass();
            
            // Middlewares must return true to proceed, otherwise they block and/or redirect
            if (!$middlewareInstance->handle(...$args)) {
                return; // Execution blocked by middleware
            }
        }

        // Execute Controller and Action
        $handlerParts = explode('@', $matchedRoute['handler']);
        $controllerName = 'App\\Controllers\\' . $handlerParts[0];
        $actionName = $handlerParts[1] ?? 'index';

        if (!class_exists($controllerName)) {
            throw new Exception("Controller '{$controllerName}' not found.");
        }

        $controllerInstance = new $controllerName();
        if (!method_exists($controllerInstance, $actionName)) {
            throw new Exception("Method '{$actionName}' not found in controller '{$controllerName}'.");
        }

        // Call the action passing URL parameters as method arguments
        call_user_func_array([$controllerInstance, $actionName], $routeParams);
    }

    /**
     * Abort with a 404 page.
     */
    private function abort404(): void {
        http_response_code(404);
        $errorFile = dirname(dirname(__DIR__)) . '/app/Views/errors/404.php';
        if (file_exists($errorFile)) {
            require_once $errorFile;
        } else {
            echo "<h1>404 Not Found</h1>";
            echo "<p>The requested page was not found on this server.</p>";
        }
        exit;
    }
}
