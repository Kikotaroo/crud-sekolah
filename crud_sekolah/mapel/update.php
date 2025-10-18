<?php
include '../db.php';
if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $mapel = mysqli_real_escape_string($conn, $_POST['mata_pelajaran']);
    $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);

    $sql = "UPDATE mapel SET mata_pelajaran='$mapel', guru_id='$guru_id' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>