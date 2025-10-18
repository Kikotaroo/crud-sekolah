<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../db.php";

if ($_POST) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM mapel WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
    exit();
}
?>