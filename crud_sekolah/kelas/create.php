<?php
include '../db.php';
if ($_POST) {
    $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_kelas']);
    $sql = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";
    if(mysqli_query($conn, $sql)) {
        header("location: index.php?success=1");
    }else {
        header("location: index.php?error=1");
    }
    exit();
}
?>