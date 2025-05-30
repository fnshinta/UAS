<?php
session_start();
require_once '../../includes/koneksi_database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak! Anda bukan admin.");
}

$db = new Database();
$pdo = $db->getConnection();

try {
    for ($i = 1; $i <= 8; $i++) {
        $username = "user_subadmin" . $i;
        $password = password_hash("password_sub" . $i, PASSWORD_DEFAULT);
        $role = "user";

        $query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $query->execute([
            'username' => $username,
            'password' => $password,
            'role' => $role
        ]);
    }

    echo "5 User berhasil ditambahkan!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
