<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Kalau belum login, arahkan ke halaman login
    header("Location: register.php");
    exit();
}

// Kalau kamu mau batasi hanya admin
if ($_SESSION['privilege'] != 'admin') {
    echo "<script>alert('Akses ditolak! Halaman ini hanya untuk admin.'); window.location='user_dashboard.php';</script>";
    exit();
}
?>
