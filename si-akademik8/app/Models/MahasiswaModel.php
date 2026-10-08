<?php

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    /**
     * Daftar mahasiswa + kode prodi (JOIN ke tabel prodi)
     * + nama dosen pembimbing (JOIN ke tabel dosen).
     * Kolom mahasiswa.prodi berisi nama prodi (teks).
     */
    public function all(string $search = ''): array
    {
        $sql = "SELECT m.id, m.nim, m.nama, m.prodi, m.status, m.dosen_id,
                       p.kode AS kode_prodi,
                       d.nama AS nama_dosen
                FROM mahasiswa m
                LEFT JOIN prodi p ON p.nama = m.prodi
                LEFT JOIN dosen d ON d.id = m.dosen_id";

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
            "INSERT INTO mahasiswa (nim, nama, prodi, status)
             VALUES (:nim, :nama, :prodi, :status)"
        );

        return $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'prodi' => $data['prodi'],
            'status' => $data['status'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        // dosen_id sengaja tidak diubah agar dosen pembimbing tetap aman
        $stmt = $this->db->prepare(
            "UPDATE mahasiswa SET nim = :nim, nama = :nama,
             prodi = :prodi, status = :status
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id,
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'prodi' => $data['prodi'],
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
