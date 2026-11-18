<?php
// Header CORS: dipasang sekali di Front Controller, sebelum Router berjalan,
// sehingga berlaku untuk semua resource (mahasiswa, login, register, buku).
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight: browser mengirim OPTIONS lebih dulu untuk request yang membawa
// header Authorization atau body JSON. Cukup dijawab 204 tanpa isi.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/core/autoload.php';

(new Router())->dispatch();
