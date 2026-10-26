<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(array $data): string {
        // Tugas Mandiri 1: email harus berformat valid sebelum disimpan
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid');
        }
        if ($this->findByUsername($data['username'])) {
            throw new DomainException('Username sudah digunakan');
        }
        if ($this->findByEmail($data['email'])) {
            throw new DomainException('Email sudah terdaftar');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([
            ':u' => $data['username'],
            ':e' => $data['email'],
            ':p' => $data['password'],
        ]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return $stmt->fetch();
    }
}