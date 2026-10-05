<?php

class Model
{
    protected $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        $this->db = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['username'],
            $config['password']
        );

        $this->db->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }
}