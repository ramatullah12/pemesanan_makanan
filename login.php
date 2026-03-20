<?php 
session_start();
include 'koneksi.php';

// Jika sudah login, langsung ke dashboard
if(isset($_SESSION['login'])){
  header("Location: dashboard.php");
  exit;
}

$error = "";

if(isset($_POST['login'])){
  $user = mysqli_real_escape_string($conn, $_POST['username']);
  $pass = $_POST['password'];

  // Prepared statement (lebih aman)
  $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows > 0){
    $data = $result->fetch_assoc();

    // PERBAIKAN: Menggunakan password_verify untuk mengecek password yang di-hash
    if(password_verify($pass, $data['password'])){
      $_SESSION['login'] = true;
      $_SESSION['username'] = $data['username'];

      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Password salah!";
    }
  } else {
    $error = "Username tidak ditemukan!";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pemesanan Makanan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 15px; border: none; }
    .vh-100 { min-height: 100vh; }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 380px;">
    
    <div class="text-center mb-4">
        <i class="bi bi-person-circle text-primary" style="font-size: 3rem;"></i>
        <h4 class="fw-bold mt-2">Selamat Datang</h4>
        <p class="text-muted small">Silakan login untuk mengelola pesanan</p>
    </div>

    <?php if($error): ?>
      <div class="alert alert-danger py-2 small"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label small fw-bold">Username</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label small fw-bold">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
      </div>

      <button name="login" class="btn btn-primary w-100 shadow-sm">Masuk</button>
    </form>

    <div class="text-center mt-4">
        <p class="small text-muted">Belum memiliki akun? <br> 
            <a href="register.php" class="text-decoration-none fw-bold">Buat Akun Baru</a>
        </p>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>