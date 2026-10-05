<?php

session_start();

require_once __DIR__ . '/../app/controllers/HomeControllers.php';
require_once __DIR__ . '/../app/controllers/AuthControllers.php';
require_once __DIR__ . '/../app/controllers/MahasiswaControllers.php';

$routes = require __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Base path otomatis (tidak perlu diubah walau folder dipindah/diganti nama)
// contoh hasil: /BKPM/si-akademik5/publik
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $basePath);

if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Rapikan: "" atau "/login/" => "/" atau "/login"
$uri = '/' . trim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];

// Route dengan parameter: GET /mahasiswa/{id}
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

    [$controllerName, $methodName] = explode('@', $routes[$method][$uri]);

    $controller = new $controllerName();
    $controller->$methodName();

} else {

    http_response_code(404);
    echo "404 - Halaman Tidak Ditemukan";
}
