<?php
session_start();
require 'koneksi.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi sederhana
    if (empty($username) || empty($password)) {
        $error = "Username dan Password tidak boleh kosong!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $cek_user = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username sudah terdaftar, silakan pilih nama lain.";
        } else {
            // Hash password untuk keamanan
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Simpan ke database
            $query = "INSERT INTO user (username, password) VALUES ('$username', '$password_hash')";
            if (mysqli_query($conn, $query)) {
                $success = "Akun berhasil dibuat! Silakan <a href='login.php'>Login</a>";
            } else {
                $error = "Gagal mendaftarkan akun.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pemesanan Makanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .vh-100 { min-height: 100vh; }
        label { font-weight: 500; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
        
        <div class="text-center mb-4">
            <i class="bi bi-person-plus text-primary" style="font-size: 3rem;"></i>
            <h4 class="fw-bold mt-2">Daftar Akun</h4>
            <p class="text-muted small">Buat akun admin baru</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger py-2 small"><?= $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success py-2 small"><?= $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>
            </div>

            <button type="submit" name="register" class="btn btn-primary w-100 mt-3 shadow-sm">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>