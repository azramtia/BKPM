<?php

/**
 * Mahasiswa (entity)
 * Menampung data satu mahasiswa lewat getter & setter.
 * Query ke database ada di MahasiswaRepository.
 */
class Mahasiswa
{
    private $id;
    private $nim;
    private $nama;
    private $email;
    private $prodiId;
    private $angkatan;
    private $status;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = (int) $id; }

    public function getNim() { return $this->nim; }
    public function setNim($nim) { $this->nim = trim($nim); }

    public function getNama() { return $this->nama; }
    public function setNama($nama) { $this->nama = trim($nama); }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = trim($email); }

    public function getProdiId() { return $this->prodiId; }
    public function setProdiId($prodiId) { $this->prodiId = (int) $prodiId; }

    public function getAngkatan() { return $this->angkatan; }
    public function setAngkatan($angkatan) { $this->angkatan = (int) $angkatan; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = strtolower(trim($status)); }
}
