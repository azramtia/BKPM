<?php

namespace App\Controllers;

class AuthController
{
    public function login()
    {
        require __DIR__ . '/../Views/Auth/login.php';
    }

    public function processLogin()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === 'admin' && $password === '12345') {

            $_SESSION['user'] = $username;
            $_SESSION['logged_in'] = true;
            $_SESSION['flash'] = 'Selamat datang, Admin';

            header('Location: /bkpm/si-akademik7/public/dashboard');
            exit;

        } else {

            $_SESSION['error'] = 'Username atau password salah';

            header('Location: /bkpm/si-akademik7/public/login');
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];

        session_destroy();

        session_start();

        $_SESSION['flash'] = 'Anda telah logout';

        header('Location: /bkpm/si-akademik7/public/login');
        exit;
    }
}