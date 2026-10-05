<?php

session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

use App\Controllers\MahasiswaController;
use App\Core\Database;
use App\Core\Router;
use App\Repositories\MahasiswaRepository;

$routes = require __DIR__ . '/../routes/web.php';

// Dependency Injection: Database -> Repository -> Controller
$factories = [
    'MahasiswaController' => function () {
        $repo = new MahasiswaRepository(Database::getInstance());
        return new MahasiswaController($repo);
    },
];

$router = new Router($routes, $factories);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
