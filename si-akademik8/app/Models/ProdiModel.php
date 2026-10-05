<?php

require_once __DIR__ . '/Model.php';

class ProdiModel extends Model
{
    public function all(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM prodi ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)"
        );
        return $stmt->execute(['kode' => $data['kode'], 'nama' => $data['nama']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM prodi WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
