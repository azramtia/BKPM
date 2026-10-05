<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';

class MahasiswaController
{
    public function index()
    {
        // Cek apakah user sudah login
        AuthMiddleware::handle();

        $model = new Mahasiswa();

        $mahasiswa = $model->all();

        echo "<h1>Data Mahasiswa</h1>";

        echo "<table border='1' cellpadding='8' cellspacing='0'>";

        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>NIM</th>";
        echo "<th>Nama</th>";
        echo "<th>Email</th>";
        echo "<th>Prodi ID</th>";
        echo "<th>Angkatan</th>";
        echo "<th>Status</th>";
        echo "</tr>";

        foreach ($mahasiswa as $mhs) {
            echo "<tr>";
            echo "<td>" . $mhs['id'] . "</td>";
            echo "<td>" . $mhs['nim'] . "</td>";
            echo "<td>" . $mhs['nama'] . "</td>";
            echo "<td>" . $mhs['email'] . "</td>";
            echo "<td>" . $mhs['prodi_id'] . "</td>";
            echo "<td>" . $mhs['angkatan'] . "</td>";
            echo "<td>" . $mhs['status'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

   
}