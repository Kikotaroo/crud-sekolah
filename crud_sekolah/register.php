<?php
session_start();
include 'config.php';
// Jika sudah login, redirect
if (isset($_SESSION['username'])) {
    if ($_SESSION['privilege'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
}
$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password != $confirm_password) {
        $error = "Password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek apakah username sudah ada
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert user baru dengan privilege 'user'
            $stmt = $conn->prepare("INSERT INTO users (username, password, privilege) VALUES (?,
?, 'user')");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat registrasi!";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo isset($_POST['username']) ?
                    htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <div class="login-link">
            <a href="login.php">Sudah punya akun? Login disini</a>
        </div>
    </div>
</body>

</html>