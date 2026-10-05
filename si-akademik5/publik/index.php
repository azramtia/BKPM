<?php

require_once __DIR__ . '/../app/Controllers/HomeControllers.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaControllers.php';

$routes = require __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/si-akademik5/publik';

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

if ($uri === '') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

$parts = explode('/', trim($uri, '/'));

if (
    $method === 'GET' &&
    count($parts) === 2 &&
    $parts[0] === 'mahasiswa' &&
    is_numeric($parts[1])
) {

    $controller = new MahasiswaController();

    $controller->show($parts[1]);

    exit;
}

if (isset($routes[$method][$uri])) {

    $route = $routes[$method][$uri];

    [$controllerName, $methodName] = explode('@', $route);

    $controller = new $controllerName();

    $controller->$methodName();

} else {

    http_response_code(404);

    echo "404 - Halaman Tidak Ditemukan";
}