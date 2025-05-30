<?php
require_once '../includes/koneksi_database.php'; // Koneksi ke database
session_start(); // Pastikan sesi dimulai

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah input ada
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Buat koneksi database
        $db = new Database();
        $conn = $db->getConnection();

        try {
            // Query untuk cek username
            $query = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $query->execute(['username' => $username]);
            $user = $query->fetch();

            // Verifikasi password
            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['staff_id'] = $user['staffS_id'] ?? null;

                // Update last_login dan total_logins
                $updateLoginTime = $conn->prepare("UPDATE users SET last_login = NOW(), total_logins = total_logins + 1 WHERE id = :user_id");
                $updateLoginTime->execute(['user_id' => $user['id']]);

                // Redirect ke dashboard
                header("Location: ../pages/dashboard.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Username atau password salah!";
                header("Location: ../index.php");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Terjadi kesalahan pada sistem!";
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Form tidak lengkap!";
        header("Location: ../index.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Akses tidak sah!";
    header("Location: ../index.php");
    exit();
}
?>
