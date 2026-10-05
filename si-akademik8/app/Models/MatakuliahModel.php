<?php

require_once __DIR__ . '/Model.php';

class MatakuliahModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare(
            "SELECT mk.id, mk.kode, mk.nama, mk.sks, mk.prodi_id,
                    p.kode AS kode_prodi, p.nama AS nama_prodi
             FROM matakuliah mk
             LEFT JOIN prodi p ON p.id = mk.prodi_id
             ORDER BY mk.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO matakuliah (kode, nama, sks, prodi_id)
             VALUES (:kode, :nama, :sks, :prodi_id)"
        );
        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE matakuliah SET kode = :kode, nama = :nama,
             sks = :sks, prodi_id = :prodi_id WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks' => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM matakuliah WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
