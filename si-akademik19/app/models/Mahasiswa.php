<?php
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function nimExists(string $nim, ?int $exceptId = null): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string {
        if ($this->nimExists($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar');
        }
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi, email) VALUES (:nim, :nama, :prodi, :email)'
        );
        $stmt->execute([
            ':nim'   => $data['nim'],
            ':nama'  => $data['nama'],
            ':prodi' => $data['prodi'],
            ':email' => $data['email'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        if ($this->nimExists($data['nim'], $id)) {
            throw new DomainException('NIM sudah digunakan mahasiswa lain');
        }
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi, email = :email WHERE id = :id'
        );
        $stmt->execute([
            ':nim'   => $data['nim'],
            ':nama'  => $data['nama'],
            ':prodi' => $data['prodi'],
            ':email' => $data['email'] ?? null,
            ':id'    => $id,
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}