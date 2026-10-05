<?php

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    public function all(string $search = ''): array
    {
        $sql = "SELECT m.id, m.nim, m.nama, m.email, m.prodi_id,
                       p.kode AS kode_prodi, p.nama AS nama_prodi,
                       m.angkatan, m.status
                FROM mahasiswa m
                LEFT JOIN prodi p ON p.id = m.prodi_id";

        if ($search !== '') {
            $sql .= " WHERE m.nim LIKE :nim_search OR m.nama LIKE :nama_search";
        }

        $sql .= " ORDER BY m.id DESC";

        $stmt = $this->db->prepare($sql);

        if ($search !== '') {
            $keyword = '%' . $search . '%';
            $stmt->execute([
                'nim_search' => $keyword,
                'nama_search' => $keyword,
            ]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );

        return $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa SET nim = :nim, nama = :nama, email = :email,
             prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status' => $data['status'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        if ($exceptId !== null) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim AND id != :id"
            );
            $stmt->execute(['nim' => $nim, 'id' => $exceptId]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim"
            );
            $stmt->execute(['nim' => $nim]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }
}
