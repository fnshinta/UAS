<?php
require_once '../../includes/koneksi_database.php';

$db = new Database();
$pdo = $db->getConnection();

for ($i = 1; $i <= 8; $i++) {
    $username = "subadmin" . $i;
    $password = password_hash("password" . $i, PASSWORD_DEFAULT);
    $role = 'sub-admin';

    $query = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $query->execute([
        'username' => $username,
        'password' => $password,
        'role' => $role
    ]);
}

echo "5 Sub-Admin berhasil ditambahkan!";
