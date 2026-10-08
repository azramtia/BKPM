<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class MahasiswaController
{
    public function index()
    {
        $model = new MahasiswaModel();

        $mahasiswa = $model->all();

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function edit()
    {
        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }
}