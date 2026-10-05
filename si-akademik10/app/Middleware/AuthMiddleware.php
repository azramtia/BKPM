<?php

class AuthMiddleware
{
    public static function handle()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /si-akademik10/public/login');
            exit;
        }
    }
}