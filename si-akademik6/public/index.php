<?php

session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

$routes = require __DIR__ . '/../routes/web.php';

$router = new App\Core\Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
