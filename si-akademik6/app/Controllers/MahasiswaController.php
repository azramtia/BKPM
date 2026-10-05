<?php

namespace App\Controllers;

class MahasiswaController
{
    // Data sementara (belum pakai database)
    private array $mahasiswa = [
        ['nim' => 'E41250290', 'nama' => 'Azira Mutia Gani',        'prodi' => 'Teknik Informatika'],
        ['nim' => 'E41250656', 'nama' => 'Arshinta Puja Aulia',     'prodi' => 'Teknik Informatika'],
        ['nim' => 'E41251544', 'nama' => 'Aulia Luh Bilqis',        'prodi' => 'Teknik Informatika'],
        ['nim' => 'E41251311', 'nama' => 'Agustin Riski Rahmania',  'prodi' => 'Teknik Informatika'],
    ];

    public function index(): void
    {
        $mahasiswa = $this->mahasiswa;

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }
}
