<?php

namespace App\Controllers;

class AuthController
{
    public function loginForm(): void
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Login disimulasikan dengan hardcode (nanti diganti database)
        if ($username === 'admin' && $password === '12345') {
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['logged_in'] = true;
            $_SESSION['flash'] = 'Selamat datang, Admin';

            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $_SESSION['error'] = 'Username atau password salah';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        $_SESSION['flash'] = 'Anda telah logout';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
