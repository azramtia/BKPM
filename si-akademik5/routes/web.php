<?php

return [
    'GET' => [
        '/'                 => 'HomeController@index',
        '/login'            => 'AuthController@loginForm',
        '/logout'           => 'AuthController@logout',
        '/mahasiswa'        => 'MahasiswaController@index',
        '/mahasiswa/create' => 'MahasiswaController@create',
    ],
    'POST' => [
        '/login'            => 'AuthController@login',
    ],
];
