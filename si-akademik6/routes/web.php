<?php

return [
    'GET' => [
        '/login'     => ['AuthController', 'loginForm'],
        '/logout'    => ['AuthController', 'logout'],
        '/dashboard' => ['DashboardController', 'index', 'middleware' => ['AuthMiddleware']],
        '/mahasiswa' => ['MahasiswaController', 'index', 'middleware' => ['AuthMiddleware']],
    ],
    'POST' => [
        '/login'     => ['AuthController', 'login'],
    ],
];
