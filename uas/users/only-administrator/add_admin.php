<?php
include '../../includes/db.php';

$db = new Database();         // Buat objek dari class Database
$pdo = $db->getConnection(); 

$username = "admin";
$password = password_hash("admin", PASSWORD_DEFAULT); // Ganti dengan password Anda
$role = "admin";

$query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
$query->execute(['username' => $username, 'password' => $password, 'role' => $role]);

echo "User admin berhasil ditambahkan!";
?>
