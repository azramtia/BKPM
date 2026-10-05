<?php

namespace App\Core;

class Router
{
    private array $routes;
    private array $factories;

    // $factories: cara membuat controller beserta dependency-nya (Dependency Injection)
    public function __construct(array $routes, array $factories = [])
    {
        $this->routes    = $routes;
        $this->factories = $factories;
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

        [$controllerName, $action] = $this->routes[$method][$uri];

        if (isset($this->factories[$controllerName])) {
            $controller = $this->factories[$controllerName]();
        } else {
            $controllerClass = "App\\Controllers\\{$controllerName}";
            $controller = new $controllerClass();
        }

        $controller->$action();
    }
}
