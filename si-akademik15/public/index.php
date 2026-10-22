
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Base URL otomatis, mengikuti lokasi folder public di server
define(
    'BASE_URL',
    rtrim(
        str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'])
        ),
        '/'
    )
);

// Memanggil file controller
require_once __DIR__ . '/../app/Controllers/BaseController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

// Memanggil file routes
require_once __DIR__ . '/../routes/web.php';

// Mengambil URL yang diminta
$requestUri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

// Mengambil base path dari lokasi public
$basePath = rtrim(
    str_replace(
        '\\',
        '/',
        dirname($_SERVER['SCRIPT_NAME'])
    ),
    '/'
);

// Menghilangkan base path dari URL
if (
    $basePath !== '' &&
    strpos($requestUri, $basePath) === 0
) {
    $requestUri = substr(
        $requestUri,
        strlen($basePath)
    );
}

// Memastikan URL tidak kosong
if ($requestUri === '' || $requestUri === false) {
    $requestUri = '/';
}

// Memastikan URL diawali tanda /
if (substr($requestUri, 0, 1) !== '/') {
    $requestUri = '/' . $requestUri;
}

// Menjalankan routing
if (isset($routes[$requestUri])) {

    $controllerName = $routes[$requestUri][0];
    $methodName = $routes[$requestUri][1];

    // Memeriksa apakah class controller tersedia
    if (!class_exists($controllerName)) {
        http_response_code(500);
        exit('Controller tidak ditemukan: ' .
            htmlspecialchars($controllerName));
    }

    // Membuat objek controller
    $controller = new $controllerName();

    // Memeriksa apakah method tersedia
    if (method_exists($controller, $methodName)) {

        $controller->$methodName();

    } else {

        http_response_code(404);

        echo '<h1>404 - Method Tidak Ditemukan</h1>';
        echo '<p>Method <b>' .
            htmlspecialchars($methodName) .
            '</b> tidak tersedia pada controller.</p>';
    }

} else {

    http_response_code(404);

    echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
    echo '<p>Route <b>' .
        htmlspecialchars($requestUri) .
        '</b> tidak tersedia.</p>';
}
