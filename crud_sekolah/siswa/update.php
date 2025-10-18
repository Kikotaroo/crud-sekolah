<?php
include '../db.php';
if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kelas_id = mysqli_real_escape_string($conn, $_POST['kelas_id']);

    $sql = "UPDATE siswa SET nama='$nama', alamat='$alamat', kelas_id='$kelas_id' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>