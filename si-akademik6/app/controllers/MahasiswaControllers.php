<?php

class MahasiswaController
{
    private $mahasiswa = [
        [
            'nim' => 'E41250290',
            'nama' => 'Azira Mutia Gani',
            'prodi' => 'Teknik Informatika'
        ],
        [
            'nim' => 'E41250656',
            'nama' => 'Arshinta Puja Aulia',
            'prodi' => 'Teknik Informatika'
        ],
        [
            'nim' => 'E41251544',
            'nama' => 'Aulia Luh Bilqis',
            'prodi' => 'Teknik Informatika'
        ],
        [
            'nim' => 'E41251311',
            'nama' => 'Agustin Riski Rahmania',
            'prodi' => 'Teknik Informatika'
        ]
    ];

    public function index()
    {
        $mahasiswa = $this->mahasiswa;

        require __DIR__ . '/../views/mahasiswa/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../views/mahasiswa/create.php';
    }

    public function edit()
    {
        require __DIR__ . '/../views/mahasiswa/edit.php';
    }
}