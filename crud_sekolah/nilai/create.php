<?php
include '../db.php';
if ($_POST) {
    $siswa_id = mysqli_real_escape_string($conn, $_POST['siswa_id']);
    $mapel = mysqli_real_escape_string($conn, $_POST['mapel_id']);
    $nilai = mysqli_real_escape_string($conn, $_POST['nilai']);
    
    $sql = "INSERT INTO nilai (siswa_id, mapel_id, nilai) VALUES ('$siswa_id', '$mapel', '$nilai')";
    if(mysqli_query($conn, $sql)) {
        header("location: index.php?success=1");
    }else {
        header("location: index.php?error=1");
    }
    exit();
}
?>