<?php

class MahasiswaController
{
    public function index()
    {
        echo "Halaman Mahasiswa";
    }

    public function create()
    {
        echo "Halaman Tambah Mahasiswa";
    }

    public function show($id)
    {
        echo "Detail Mahasiswa dengan ID: " . $id;
    }
}