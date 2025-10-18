<?php
include '../db.php';
if ($_POST) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);
    
    $sql = "INSERT INTO siswa (nama, alamat, kelas_id) VALUES ('$nama', '$alamat', '$kelas_id')";
    if(mysqli_query($conn, $sql)) {
        header("location: index.php?success=1");
    }else {
        header("location: index.php?error=1");
    }
    exit();
}
?>