<?php
session_start();
include 'config.php';
// Cek apakah user sudah login dan privilege admin
if (!isset($_SESSION['username']) || $_SESSION['privilege'] != 'admin') {
    header("Location: login.php");
    exit();
}
//Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .dashboard {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .welcome {
            font-size: 24px;
            color: #333;
        }

        .content {
            margin-top: 20px;
        }

        .admin-content {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
    </style>
</head>

<body>
    <div class="dashboard">
        <div class="header">
            <div class="welcome">Admin Dashboard</div>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>
        <div class="content">
            <h3>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            <p>Anda login sebagai <strong>Administrator</strong></p>
            <div class="admin-content">
                <h4>Fitur Admin:</h4>
                <ul>
                    <li>Manajemen User</li>
                    <li>Manajemen Data</li>
                    <li>Setting Sistem</li>
                    <li>Laporan</li>
                </ul>
            </div>
            <p>Ini adalah halaman khusus untuk administrator.</p>
        </div>
    </div>
</body>

</html>