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
  $user = $_POST['username'];
  $pass = $_POST['password'];

  // Prepared statement (lebih aman)
  $stmt = $conn->prepare("SELECT * FROM user WHERE username=?");
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows > 0){
    $data = $result->fetch_assoc();

    // Untuk sekarang masih plain password
    if($pass == $data['password']){
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
<html>
<head>
  <title>Login</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="width: 350px;">
    
    <h4 class="text-center mb-3">Login</h4>

    <?php if($error): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <button name="login" class="btn btn-primary w-100">Login</button>
    </form>

  </div>
</div>

</body>
</html>