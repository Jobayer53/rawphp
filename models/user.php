<?php

class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($name, $email, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $password, $role]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
