<?php

class HomeController
{
    public function index()
    {
        echo "Halaman Home<br>";
        echo '<a href="' . BASE_URL . '/login">Login</a> | ';
        echo '<a href="' . BASE_URL . '/mahasiswa">Data Mahasiswa</a>';
    }
}
