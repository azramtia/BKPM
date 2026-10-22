<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Base URL project, tanpa /public
$projectBasePath = rtrim(
    str_replace(
        '\\',
        '/',
        dirname(dirname($_SERVER['SCRIPT_NAME']))
    ),
    '/'
);

define('BASE_URL', $projectBasePath);

// Memanggil file controller
require_once __DIR__ . '/../app/Controllers/BaseController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

// Memanggil file routes
require_once __DIR__ . '/../routes/web.php';

// Mengambil path URL yang diminta
$requestUri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

if ($requestUri === false || $requestUri === null) {
    $requestUri = '/';
}

// Menghilangkan base path project dari URL
if (
    $projectBasePath !== '' &&
    strpos($requestUri, $projectBasePath) === 0
) {
    $requestUri = substr(
        $requestUri,
        strlen($projectBasePath)
    );
}

// Menghilangkan /public jika masih ada
if (strpos($requestUri, '/public/') === 0) {
    $requestUri = substr(
        $requestUri,
        strlen('/public')
    );
} elseif ($requestUri === '/public') {
    $requestUri = '/';
}

// Memastikan URL tidak kosong
if ($requestUri === '') {
    $requestUri = '/';
}

// Memastikan URL diawali tanda /
if (substr($requestUri, 0, 1) !== '/') {
    $requestUri = '/' . $requestUri;
}

// ==========================================
// ROUTING REST API MAHASISWA
// Endpoint:
// GET    /api/mahasiswa
// GET    /api/mahasiswa/{id}
// POST   /api/mahasiswa
// PUT    /api/mahasiswa/{id}
// DELETE /api/mahasiswa/{id}
// ==========================================

if (
    strpos($requestUri, '/api/') === 0 ||
    $requestUri === '/api'
) {
    if (
        preg_match(
            '#^/api/mahasiswa(?:/([^/]+))?/?$#',
            $requestUri,
            $matches
        )
    ) {
        $apiRouteId = $matches[1] ?? null;

        require __DIR__ . '/../api/mahasiswa.php';
        exit;
    }

    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(
        [
            'success' => false,
            'message' => 'Endpoint tidak ditemukan'
        ],
        JSON_PRETTY_PRINT
    );

    exit;
}

// ==========================================
// ROUTING HALAMAN WEB
// ==========================================

if (isset($routes[$requestUri])) {

    $controllerName = $routes[$requestUri][0];
    $methodName = $routes[$requestUri][1];

    // Memeriksa ketersediaan controller
    if (!class_exists($controllerName)) {
        http_response_code(500);

        exit(
            'Controller tidak ditemukan: ' .
            htmlspecialchars($controllerName)
        );
    }

    // Membuat objek controller
    $controller = new $controllerName();

    // Memeriksa ketersediaan method
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