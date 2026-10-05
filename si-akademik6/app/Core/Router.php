<?php

namespace App\Core;

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $requestUri): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        // Hilangkan base path jika project di subfolder
        if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
            $uri = substr($uri, strlen(BASE_URL));
        }

        $uri = '/' . trim($uri, '/');

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 - Halaman tidak ditemukan";
            return;
        }

        $route = $this->routes[$method][$uri];

        // Middleware dijalankan sebelum Controller
        $middleware = $route['middleware'] ?? [];
        foreach ($middleware as $mw) {
            $mwClass = "App\\Core\\Middleware\\{$mw}";
            $mwInstance = new $mwClass();
            $mwInstance->handle();
        }

        // Baru panggil controller
        $controllerClass = "App\\Controllers\\{$route[0]}";
        $action = $route[1];

        $controller = new $controllerClass();
        $controller->$action();
    }
}
