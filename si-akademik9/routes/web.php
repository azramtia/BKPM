<?php

return [
    'GET' => [
        '/mahasiswa'        => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
        '/mahasiswa/edit'   => ['MahasiswaController', 'edit'],
    ],
    'POST' => [
        '/mahasiswa/store'   => ['MahasiswaController', 'store'],
        '/mahasiswa/update'  => ['MahasiswaController', 'update'],
        '/mahasiswa/destroy' => ['MahasiswaController', 'destroy'],
    ],
];
