<?php

session_start();


// =====================================================
// AUTOLOAD (namespace App\ => folder app/)
// =====================================================

spl_autoload_register(function (string $class): void {

    $prefix = 'App\\';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));

    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});


// =====================================================
// LOAD ROUTES
// =====================================================

$routes = require __DIR__ . '/../routes/web.php';


// =====================================================
// AMBIL & BERSIHKAN URI
// =====================================================

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

$uri = parse_url($requestUri, PHP_URL_PATH);

$uri = rtrim($uri, '/');


// =====================================================
// TENTUKAN URI ROUTE (buang base path)
// =====================================================

// Base path dihitung otomatis, jadi /BKPM dan /bkpm sama-sama bisa.
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

if ($basePath !== '' && stripos($uri, $basePath) === 0) {
    $routeUri = substr($uri, strlen($basePath));
} else {
    $routeUri = $uri;
}

if ($routeUri === '') {
    $routeUri = '/';
}


// =====================================================
// JALANKAN ROUTE
// =====================================================

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (isset($routes[$method][$routeUri])) {

    [$controllerClass, $action] = $routes[$method][$routeUri];

    $controller = new $controllerClass();

    $controller->$action();

    exit;
}


// =====================================================
// 404
// =====================================================

http_response_code(404);

echo '<!DOCTYPE html>';
echo '<html lang="id">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>404 - Halaman Tidak Ditemukan</title>';
echo '</head>';
echo '<body>';
echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
echo '</body>';
echo '</html>';