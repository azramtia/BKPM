
<?php

class MahasiswaController
{
    public function index()
    {
        echo "Halaman Data Mahasiswa";
    }

    public function create()
    {
        echo "Halaman Tambah Mahasiswa";
    }

    public function show($id)
    {
        echo "Detail Mahasiswa dengan ID: " . htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8');
    }
}
