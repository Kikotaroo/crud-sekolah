<?php
include '../db.php';
if ($_POST) {
    $mapel_id = mysqli_real_escape_string($conn, $_POST['id']);
    $mapel = mysqli_real_escape_string($conn, $_POST['mata_pelajaran']);
    $guru_id = mysqli_real_escape_string($conn, $_POST['guru_id']);
    
    $sql = "INSERT INTO mapel (id, mata_pelajaran, guru_id) VALUES ('$mapel_id', '$mapel', '$guru_id')";
    if(mysqli_query($conn, $sql)) {
        header("location: index.php?success=1");
    }else {
        header("location: index.php?error=1");
    }
    exit();
}
?>