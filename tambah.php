<?php 
session_start();
include 'koneksi.php';

// Proteksi halaman
if(!isset($_SESSION['login'])){
  header("Location: login.php");
  exit;
}

$error = "";

if(isset($_POST['simpan'])){
  $nama = $_POST['nama'];
  $produk = $_POST['produk'];
  $jumlah = $_POST['jumlah'];

  if($nama == "" || $produk == "" || $jumlah == ""){
    $error = "Semua field wajib diisi!";
  } else {
    // Prepared statement (aman)
    $stmt = $conn->prepare("INSERT INTO pesanan (nama, produk, jumlah) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nama, $produk, $jumlah);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Pesanan</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 12px;
    }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 400px;">
    
    <h4 class="text-center mb-3">➕ Tambah Pesanan</h4>

    <?php if($error): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">

      <div class="mb-3">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama" required>
      </div>

      <div class="mb-3">
        <label>Produk</label>
        <input type="text" name="produk" class="form-control" placeholder="Masukkan produk" required>
      </div>

      <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah" required min="1">
      </div>

      <div class="d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
        <button name="simpan" class="btn btn-success">💾 Simpan</button>
      </div>

    </form>

  </div>

</div>

</body>
</html>