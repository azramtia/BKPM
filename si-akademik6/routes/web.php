<?php

return [

    'GET' => [

        '/' => [
            'controller' => 'HomeController',
            'method' => 'index',
            'middleware' => []
        ],

        '/login' => [
            'controller' => 'AuthController',
            'method' => 'loginForm',
            'middleware' => []
        ],

        '/dashboard' => [
            'controller' => 'DashboardController',
            'method' => 'index',
            'middleware' => ['AuthMiddleware']
        ],

        '/mahasiswa' => [
            'controller' => 'MahasiswaController',
            'method' => 'index',
            'middleware' => ['AuthMiddleware']
        ],

        '/mahasiswa/create' => [
            'controller' => 'MahasiswaController',
            'method' => 'create',
            'middleware' => ['AuthMiddleware']
        ],

        '/mahasiswa/edit' => [
            'controller' => 'MahasiswaController',
            'method' => 'edit',
            'middleware' => ['AuthMiddleware']
        ],

        '/logout' => [
            'controller' => 'AuthController',
            'method' => 'logout',
            'middleware' => []
        ]

    ],

    'POST' => [

        '/login' => [
            'controller' => 'AuthController',
            'method' => 'login',
            'middleware' => []
        ]

    ]

];