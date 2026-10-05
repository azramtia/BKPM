<?php

namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    public function all(): array
    {
        return $this->db->query("SELECT * FROM mahasiswa")->fetchAll();
    }
}
