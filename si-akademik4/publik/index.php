<?php

require_once __DIR__ . '/../app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = [
    new Mahasiswa('23001', 'Arshinta Puja Aulia'),
    new Mahasiswa('24002', 'Nindia Tri Anggraini'),
    new Mahasiswa('25003', 'Azira Mutia Gani'),
    new Mahasiswa('23004', 'Agustin Riski Rahmania'),
    new Mahasiswa('26005', 'Aulia Luh Bilqis')
];

$content = __DIR__ . '/../app/Views/mahasiswa/index.php';

require_once __DIR__ . '/../app/Views/layouts/main.php';