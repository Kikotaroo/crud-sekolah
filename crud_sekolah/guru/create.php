<?php
include '../db.php';
if ($_POST) {
    $nama_guru = mysqli_real_escape_string($conn, $_POST['nama_guru']);
    $sql = "INSERT INTO guru (nama_guru) VALUES ('$nama_guru')";
    if(mysqli_query($conn, $sql)) {
        header("location: index.php?success=1");
    }else {
        header("location: index.php?error=1");
    }
    exit();
}
?>