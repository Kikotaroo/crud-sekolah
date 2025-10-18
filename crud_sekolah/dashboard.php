<?php
$page_title = "Dashboard";
include 'config.php';
include 'header.php';

// Query untuk menghitung data (tetap sama)
$siswa_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa"))['total'];
$kelas_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kelas"))['total'];
$nilai_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM nilai"))['total'];
$guru_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM guru"))['total'];
$mapel_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM mapel"))['total'];
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Selamat Datang di Sistem Informasi Sekolah</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people-fill" style="font-size: 3rem; color: #0d6efd;"></i>
                    <h5 class="card-title mt-3">Manajemen Siswa</h5>
                    <p class="card-text">Total Siswa Terdaftar: <strong><?php echo $siswa_count; ?></strong></p>
                    <a href="<?php echo BASE_URL; ?>siswa/index.php" class="btn btn-primary">Kelola Siswa</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                     <i class="bi bi-door-open-fill" style="font-size: 3rem; color: #198754;"></i>
                     <h5 class="card-title mt-3">Manajemen Kelas</h5>
                     <p class="card-text">Total Kelas Tersedia: <strong><?php echo $kelas_count; ?></strong></p>
                     <a href="<?php echo BASE_URL; ?>kelas/index.php" class="btn btn-success">Kelola Kelas</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-card-checklist" style="font-size: 3rem; color: #ffc107;"></i>
                    <h5 class="card-title mt-3">Manajemen Nilai</h5>
                    <p class="card-text">Total Nilai Tercatat: <strong><?php echo $nilai_count; ?></strong></p>
                    <a href="<?php echo BASE_URL; ?>nilai/index.php" class="btn btn-warning">Kelola Nilai</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-person-badge" style="font-size: 3rem; color:rgb(7, 176, 255);"></i>
                    <h5 class="card-title mt-3">Manajemen guru</h5>
                    <p class="card-text">Total Nilai Tercatat: <strong><?php echo $guru_count; ?></strong></p>
                    <a href="<?php echo BASE_URL; ?>guru/index.php" class="btn btn-info">Kelola Guru</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <i class="bi bi-journal-bookmark" style="font-size: 3rem; color:rgb(221, 50, 50);"></i>
                    <h5 class="card-title mt-3">Manajemen mapel</h5>
                    <p class="card-text">Total Nilai Tercatat: <strong><?php echo $guru_count; ?></strong></p>
                    <a href="<?php echo BASE_URL; ?>mapel/index.php" class="btn btn-danger">Kelola Mapel</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>