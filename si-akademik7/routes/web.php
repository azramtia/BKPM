<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\MahasiswaController;

$routes = [

    // ==========================================
    // ROUTE GET
    // ==========================================

    'GET' => [

        // Halaman utama
        '/' => [
            HomeController::class,
            'index'
        ],

        // Login
        '/login' => [
            AuthController::class,
            'login'
        ],

        // Logout
        '/logout' => [
            AuthController::class,
            'logout'
        ],

        // Dashboard
        '/dashboard' => [
            HomeController::class,
            'index'
        ],

        // Data Mahasiswa
        '/mahasiswa' => [
            MahasiswaController::class,
            'index'
        ],

        // Tambah Mahasiswa
        '/mahasiswa/create' => [
            MahasiswaController::class,
            'create'
        ],

        // Edit Mahasiswa
        '/mahasiswa/edit' => [
            MahasiswaController::class,
            'edit'
        ],

    ],


    // ==========================================
    // ROUTE POST
    // ==========================================

    'POST' => [

        // Proses Login
        '/login/process' => [
            AuthController::class,
            'processLogin'
        ],

    ],

];


// ==========================================
// KEMBALIKAN SEMUA ROUTE
// ==========================================

return $routes;