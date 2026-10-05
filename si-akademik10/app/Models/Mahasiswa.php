<?php

require_once __DIR__ . '/Model.php';

class Mahasiswa extends Model
{
    public function all()
    {
        $query = "SELECT * FROM mahasiswa";

        $stmt = $this->db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}