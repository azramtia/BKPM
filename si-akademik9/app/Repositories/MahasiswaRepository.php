<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa;
use PDO;

class MahasiswaRepository
{
    private PDO $pdo;

    // Constructor injection: Database diberikan dari luar
    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    // Semua mahasiswa + nama prodi (JOIN)
    public function all(): array
    {
        $stmt = $this->pdo->query(
            "SELECT m.*, p.nama AS prodi_nama
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             ORDER BY m.nim"
        );

        return array_map([Mahasiswa::class, 'fromRow'], $stmt->fetchAll());
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare(
            "SELECT m.*, p.nama AS prodi_nama
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             WHERE m.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Mahasiswa::fromRow($row) : null;
    }

    public function create(Mahasiswa $m): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute([
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi_id' => $m->getProdiId(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
        ]);
    }

    public function update(Mahasiswa $m): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute([
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi_id' => $m->getProdiId(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
            'id'       => $m->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // Daftar prodi untuk dropdown pada form
    public function allProdi(): array
    {
        return $this->pdo->query("SELECT id, nama FROM prodi ORDER BY nama")->fetchAll();
    }
}
