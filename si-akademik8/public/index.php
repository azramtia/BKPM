<?php

require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MatakuliahController.php';

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = '/' . ltrim(substr($path, strlen($base)), '/');
$path = rtrim($path, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

switch (true) {
    case $path === '/' || $path === '/mahasiswa':
        (new MahasiswaController())->index();
        break;

    case $path === '/mahasiswa/create' && $method === 'GET':
        (new MahasiswaController())->create();
        break;
    case $path === '/mahasiswa/store' && $method === 'POST':
        (new MahasiswaController())->store();
        break;
    case $path === '/mahasiswa/edit' && $method === 'GET':
        (new MahasiswaController())->edit();
        break;
    case $path === '/mahasiswa/update' && $method === 'POST':
        (new MahasiswaController())->update();
        break;
    case $path === '/mahasiswa/delete' && $method === 'GET':
        (new MahasiswaController())->delete();
        break;

    case $path === '/prodi' && $method === 'GET':
        (new ProdiController())->index();
        break;
    case $path === '/prodi/create' && $method === 'GET':
        (new ProdiController())->create();
        break;
    case $path === '/prodi/store' && $method === 'POST':
        (new ProdiController())->store();
        break;
    case $path === '/prodi/edit' && $method === 'GET':
        (new ProdiController())->edit();
        break;
    case $path === '/prodi/update' && $method === 'POST':
        (new ProdiController())->update();
        break;
    case $path === '/prodi/delete' && $method === 'GET':
        (new ProdiController())->delete();
        break;

    case $path === '/matakuliah' && $method === 'GET':
        (new MatakuliahController())->index();
        break;
    case $path === '/matakuliah/create' && $method === 'GET':
        (new MatakuliahController())->create();
        break;
    case $path === '/matakuliah/store' && $method === 'POST':
        (new MatakuliahController())->store();
        break;
    case $path === '/matakuliah/edit' && $method === 'GET':
        (new MatakuliahController())->edit();
        break;
    case $path === '/matakuliah/update' && $method === 'POST':
        (new MatakuliahController())->update();
        break;
    case $path === '/matakuliah/delete' && $method === 'GET':
        (new MatakuliahController())->delete();
        break;

    default:
        http_response_code(404);
        echo '<h1>404</h1><p>Halaman tidak ditemukan.</p>';
}
