<?php

require_once __DIR__ . '/../Core/Database.php';

class Model
{
    protected $db;

    public function __construct(?Database $database = null)
    {
        $database = $database ?? new Database();

        $this->db = $database->getConnection();
    }
}
