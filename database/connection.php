<?php

function getDBConnection() {
    $dsn = 'mysql:host=localhost;dbname=rawphp;charset=utf8mb4';
    $user = 'root';
    $pass = '';

    try {
        return new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
        die(json_encode(['error' => 'DB connection failed: ' . $e->getMessage()]));
    }
}
