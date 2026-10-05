<?php

session_start();


require_once __DIR__ . '/../app/controllers/HomeControllers.php';
require_once __DIR__ . '/../app/controllers/AuthControllers.php';
require_once __DIR__ . '/../app/controllers/DashboardControllers.php';
require_once __DIR__ . '/../app/controllers/MahasiswaControllers.php';
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';


$routes = require __DIR__ . '/../routes/web.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/si-akademik6/publik';


// Menghilangkan base path
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}


// Jika URL kosong
if ($uri === '') {
    $uri = '/';
}


// HTTP Method
$method = $_SERVER['REQUEST_METHOD'];


// Cek Route
if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];


    // Middleware
    if (!empty($route['middleware'])) {

        foreach ($route['middleware'] as $middlewareName) {

            $middleware = new $middlewareName();

            $middleware->handle();
        }
    }


    // Controller
    $controllerName = $route['controller'];
    $methodName = $route['method'];

    $controller = new $controllerName();

    $controller->$methodName();

} else {

    http_response_code(404);

    echo "404 - Halaman Tidak Ditemukan";
}