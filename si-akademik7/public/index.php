<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Ambil URL yang diminta
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Tentukan lokasi folder public secara otomatis
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

// Hilangkan base path dari URL
if ($basePath !== '' && $basePath !== '/') {
    if (str_starts_with($requestUri, $basePath)) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
}

// Pastikan format route diawali /
$requestUri = '/' . ltrim($requestUri, '/');

if ($requestUri === '//') {
    $requestUri = '/';
}

// Routing
if (isset($routes[$requestUri])) {

    $controllerName = $routes[$requestUri][0];
    $methodName = $routes[$requestUri][1];

    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    } else {
        http_response_code(404);
        echo 'Method tidak ditemukan.';
    }

} else {

    http_response_code(404);

    echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
    echo '<p>Route <b>' . htmlspecialchars($requestUri) . '</b> tidak tersedia.</p>';
}
