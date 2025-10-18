<?php
include '../db.php';
if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM kelas WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: index.php");
    exit();
}
?>