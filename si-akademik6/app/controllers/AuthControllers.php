<?php

class AuthController
{
    public function loginForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require __DIR__ . '/../views/Auth/login.php';
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === '12345') {

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';

            // Flash message
            $_SESSION['flash'] = 'Selamat datang, Admin';

            header('Location: /si-akademik6/publik/dashboard');
            exit;
        }

        $_SESSION['error'] = 'Username atau password salah';

        header('Location: /si-akademik6/publik/login');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        $_SESSION['flash'] = 'Anda telah logout';

        header('Location: /si-akademik6/publik/login');
        exit;
    }
}