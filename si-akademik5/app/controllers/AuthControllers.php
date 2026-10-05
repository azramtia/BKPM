<?php

class AuthController
{
    public function loginForm()
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Login simulasi (hardcode), nanti diganti database
        if ($username === 'admin' && $password === '12345') {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';

            header('Location: ' . BASE_URL . '/mahasiswa');
            exit;
        }

        $_SESSION['error'] = 'Username atau password salah';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
