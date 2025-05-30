<?php
include '../../includes/koneksi_database.php'; // Sesuaikan path jika perlu

// Inisialisasi koneksi ke database
$db = new Database();
$pdo = $db->getConnection();

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT); // Password aman
$role = "admin";

// Eksekusi query tambah user admin
$query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
$query->execute([
    'username' => $username,
    'password' => $password,
    'role' => $role
]);

echo "User admin berhasil ditambahkan!";
?>
