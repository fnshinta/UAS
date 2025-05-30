<?php
session_start();
session_destroy(); // Hapus semua session
header("Location: ..\login.php?info+Anda Sudah Logout.");
exit;
?>
