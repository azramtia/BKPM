<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class MahasiswaController
{
    public function index(): void
    {
        $model = new MahasiswaModel();
        $mahasiswa = $model->all();

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }
}
