<?php

$routes = [
    '/login' => ['AuthController', 'loginForm'],

    '/mahasiswa' => ['MahasiswaController', 'index'],

    '/mahasiswa/create' => ['MahasiswaController', 'create'],

    '/logout' => ['AuthController', 'logout'],
];