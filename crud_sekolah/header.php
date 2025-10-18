<?php
session_start();
@include_once __DIR__ . 'config.php';

//Logout
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title><?php echo isset($page_title) ? $page_title : 'Sistem Sekolah'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<style>
  .logout-btn {
    background-color: #dc3545;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
  }
</style>

<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>dashboard.php">🏫 Dashboard Sekolah</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>siswa/index.php">Manajemen Siswa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>kelas/index.php">Manajemen Kelas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>nilai/index.php">Manajemen Nilai</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>guru/index.php">Manajemen guru</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>mapel/index.php">Manajemen mapel</a>
          </li>
        </ul>
        <a href="?logout=1" class="logout-btn">Logout</a>
      </div>
    </div>
  </nav>