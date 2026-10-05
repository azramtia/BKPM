<?php

require_once __DIR__ . '/../app/Core/Model.php';
require_once __DIR__ . '/../app/Models/MahasiswaModel.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

$routes = require __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan base path jika project di subfolder (otomatis, contoh: /BKPM/si-akademik7/public)
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($base !== '' && strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . trim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    $controllerClass = "App\\Controllers\\{$controllerName}";
    $controller = new $controllerClass();
    $controller->$action();
} else {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}
