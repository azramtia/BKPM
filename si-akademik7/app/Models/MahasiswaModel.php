<?php

namespace App\Models;

use PDO;

class MahasiswaModel extends Model
{
    /**
     * Ambil semua data mahasiswa beserta nama dosen pembimbing.
     * Kolom prodi pada tabel mahasiswa berupa teks (varchar).
     */
    public function all(): array
    {
        $sql = "
            SELECT
                m.id,
                m.nim,
                m.nama,
                m.prodi,
                m.status,
                m.dosen_id,
                d.nama AS nama_dosen
            FROM mahasiswa AS m
            LEFT JOIN dosen AS d
                ON m.dosen_id = d.id
            ORDER BY m.id ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
