<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaRepository extends Model
{
    public function all()
    {
        $sql = "SELECT
                    m.id, m.nim, m.nama, m.email,
                    p.nama AS prodi, m.prodi_id,
                    m.angkatan, m.status, m.dosen_id
                FROM mahasiswa m
                LEFT JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prodiList()
    {
        $stmt = $this->db->prepare(
            "SELECT id, nama FROM prodi ORDER BY nama"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function prodiExists($prodiId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM prodi WHERE id = :id"
        );
        $stmt->execute([':id' => (int) $prodiId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function dosenList()
    {
        $stmt = $this->db->prepare(
            "SELECT id, nidn, nama, bidang_keahlian
             FROM dosen ORDER BY nama"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dosenExists($dosenId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM dosen WHERE id = :id"
        );
        $stmt->execute([':id' => (int) $dosenId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mahasiswa WHERE id = :id"
        );
        $stmt->execute([':id' => (int) $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findDetail($id)
    {
        $stmt = $this->db->prepare(
            "SELECT m.id, m.nim, m.nama, m.email,
                    m.prodi_id, p.nama AS prodi,
                    m.angkatan, m.status, m.dosen_id
             FROM mahasiswa m
             LEFT JOIN prodi p ON m.prodi_id = p.id
             WHERE m.id = :id"
        );
        $stmt->execute([':id' => (int) $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function nimExists($nim, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim";
        $params = [':nim' => trim((string) $nim)];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = (int) $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function resolveProdiId($data)
    {
        if (
            isset($data['prodi_id']) &&
            ctype_digit((string) $data['prodi_id']) &&
            (int) $data['prodi_id'] > 0
        ) {
            $id = (int) $data['prodi_id'];

            return $this->prodiExists($id) ? $id : null;
        }

        $nama = trim((string) ($data['prodi'] ?? ''));

        if ($nama === '') {
            return null;
        }

        $stmt = $this->db->prepare(
            "SELECT id FROM prodi WHERE nama = :nama"
        );
        $stmt->execute([':nama' => $nama]);

        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    private function resolveDosenId($data)
    {
        $dosenId = trim((string) ($data['dosen_id'] ?? ''));

        if (
            $dosenId === '' ||
            !ctype_digit($dosenId) ||
            (int) $dosenId <= 0
        ) {
            return null;
        }

        $id = (int) $dosenId;

        return $this->dosenExists($id) ? $id : null;
    }

    public function create($data)
    {
        $prodiId = $this->resolveProdiId($data);
        $dosenId = $this->resolveDosenId($data);

        if ($prodiId === null) {
            return false;
        }

        $angkatan = trim((string) ($data['angkatan'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        $prodiStmt = $this->db->prepare("SELECT nama FROM prodi WHERE id = :id");
        $prodiStmt->execute([':id' => $prodiId]);
        $namaProdi = $prodiStmt->fetchColumn();

        $sql = "INSERT INTO mahasiswa
                    (nim, nama, email, prodi, prodi_id, angkatan, status, dosen_id)
                VALUES
                    (:nim, :nama, :email, :prodi, :prodi_id, :angkatan, :status, :dosen_id)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nim' => trim((string) ($data['nim'] ?? '')),
            ':nama' => trim((string) ($data['nama'] ?? '')),
            ':email' => $email === '' ? null : $email,
            ':prodi' => $namaProdi,
            ':prodi_id' => $prodiId,
            ':angkatan' => $angkatan === '' ? null : (int) $angkatan,
            ':status' => strtolower(trim((string) ($data['status'] ?? 'aktif'))) ?: 'aktif',
            ':dosen_id' => $dosenId
        ]);
    }

    public function update($id, $data)
    {
        $id = (int) $id;
        $prodiId = $this->resolveProdiId($data);
        $dosenId = $this->resolveDosenId($data);

        if ($id <= 0 || $prodiId === null) {
            return false;
        }

        $angkatan = trim((string) ($data['angkatan'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        $prodiStmt = $this->db->prepare("SELECT nama FROM prodi WHERE id = :id");
        $prodiStmt->execute([':id' => $prodiId]);
        $namaProdi = $prodiStmt->fetchColumn();

        $sql = "UPDATE mahasiswa SET
                    nim = :nim,
                    nama = :nama,
                    email = :email,
                    prodi = :prodi,
                    prodi_id = :prodi_id,
                    angkatan = :angkatan,
                    status = :status,
                    dosen_id = :dosen_id
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nim' => trim((string) ($data['nim'] ?? '')),
            ':nama' => trim((string) ($data['nama'] ?? '')),
            ':email' => $email === '' ? null : $email,
            ':prodi' => $namaProdi,
            ':prodi_id' => $prodiId,
            ':angkatan' => $angkatan === '' ? null : (int) $angkatan,
            ':status' => strtolower(trim((string) ($data['status'] ?? 'aktif'))) ?: 'aktif',
            ':dosen_id' => $dosenId
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM mahasiswa WHERE id = :id"
        );

        return $stmt->execute([':id' => (int) $id]);
    }
}
