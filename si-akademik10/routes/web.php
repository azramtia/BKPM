<?php

$routes = [
    '/' => ['DashboardController', 'index'],
    '/dashboard' => ['DashboardController', 'index'],
    '/login' => ['AuthController', 'loginForm'],

    '/mahasiswa' => ['MahasiswaController', 'index'],
    '/mahasiswa/create' => ['MahasiswaController', 'create'],

    '/dosen' => ['DosenController', 'index'],
    '/dosen/create' => ['DosenController', 'create'],

    '/logout' => ['AuthController', 'logout'],
];
