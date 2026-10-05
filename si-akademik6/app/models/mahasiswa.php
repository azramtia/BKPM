<?php

namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;

    public function __construct(string $nim, string $nama)
    {
        $this->nim = $nim;
        $this->nama = $nama;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        if (strlen($nama) < 3) {
            throw new \InvalidArgumentException("Nama terlalu pendek");
        }

        $this->nama = $nama;
    }

    public function getAngkatan(): string
    {
        return substr($this->nim, 0, 2);
    }
}