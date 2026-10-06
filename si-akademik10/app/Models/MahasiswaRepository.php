<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaRepository extends Model
{

    public function all()
    {
        $sql = "SELECT
                    m.id,
                    m.nim,
                    m.nama,
                    m.email,
                    m.prodi_id,
                    p.nama AS prodi_nama,
                    m.angkatan,
                    m.status
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                ORDER BY m.nim";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM mahasiswa WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findDetail($id)
    {
        $sql = "SELECT m.nim, m.nama, p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON m.prodi_id = p.id
                WHERE m.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $mahasiswa = new Mahasiswa();

        $mahasiswa->setNim($data['nim']);
        $mahasiswa->setNama($data['nama']);
        $mahasiswa->setEmail($data['email']);
        $mahasiswa->setProdiId($data['prodi_id']);
        $mahasiswa->setAngkatan($data['angkatan']);
        $mahasiswa->setStatus($data['status']);

        $sql = "INSERT INTO mahasiswa
                (nim, nama, email, prodi_id, angkatan, status)
                VALUES
                (:nim, :nama, :email, :prodi_id, :angkatan, :status)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nim' => $mahasiswa->getNim(),
            ':nama' => $mahasiswa->getNama(),
            ':email' => $mahasiswa->getEmail(),
            ':prodi_id' => $mahasiswa->getProdiId(),
            ':angkatan' => $mahasiswa->getAngkatan(),
            ':status' => $mahasiswa->getStatus()
        ]);
    }

    public function update($id, $data)
    {
        $mahasiswa = new Mahasiswa();

        $mahasiswa->setId($id);
        $mahasiswa->setNim($data['nim']);
        $mahasiswa->setNama($data['nama']);
        $mahasiswa->setEmail($data['email']);
        $mahasiswa->setProdiId($data['prodi_id']);
        $mahasiswa->setAngkatan($data['angkatan']);
        $mahasiswa->setStatus($data['status']);

        $sql = "UPDATE mahasiswa SET
                    nim = :nim,
                    nama = :nama,
                    email = :email,
                    prodi_id = :prodi_id,
                    angkatan = :angkatan,
                    status = :status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $mahasiswa->getId(),
            ':nim' => $mahasiswa->getNim(),
            ':nama' => $mahasiswa->getNama(),
            ':email' => $mahasiswa->getEmail(),
            ':prodi_id' => $mahasiswa->getProdiId(),
            ':angkatan' => $mahasiswa->getAngkatan(),
            ':status' => $mahasiswa->getStatus()
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM mahasiswa WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}