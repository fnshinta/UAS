<?php
session_start();
include '../../includes/db.php';

$db = new Database();         // Buat objek dari class Database
$pdo = $db->getConnection();  // Ambil koneksi PDO dan simpan ke $pdo


for ($i = 1; $i <= 5; $i++) {
    $username = "subadmin" . $i;
    $password = password_hash("password" . $i, PASSWORD_DEFAULT);
    $role = 'user';

    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'username' => $username,
        'password' => $password,
        'role' => $role
    ]);
}

echo "5 Sub-Admin berhasil ditambahkan!";
?>
