<?php
include '../db.php';
if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama_kelas = mysqli_real_escape_string($conn, $_POST['nama_guru']);
    $sql = "UPDATE guru SET nama_guru='$nama_kelas' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>