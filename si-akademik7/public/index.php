<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();


require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Ambil URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan /si-akademik/public
$basePath = dirname($_SERVER['SCRIPT_NAME']);

if ($basePath !== '/') {
    $requestUri = str_replace($basePath, '', $requestUri);
}

// Pastikan diawali /
if ($requestUri === '') {
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
        echo "Method tidak ditemukan.";
    }

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
    echo "<p>Route <b>" . htmlspecialchars($requestUri) . "</b> tidak tersedia.</p>";
}