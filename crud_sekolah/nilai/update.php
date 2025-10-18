<?php
include '../db.php';
if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $siswa_id = mysqli_real_escape_string($conn, $_POST['siswa_id']);
    $mapel = mysqli_real_escape_string($conn, $_POST['mapel_id']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);

    $sql = "UPDATE nilai SET siswa_id='$siswa_id', mapel_id='$mapel', nilai='$nilai' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>