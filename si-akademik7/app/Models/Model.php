<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Model dasar: semua model turunan otomatis
 * mendapat koneksi PDO lewat properti $db.
 */
class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}