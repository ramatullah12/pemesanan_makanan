<?php 
session_start();
require '../koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

$error = "";

// Proses simpan
if(isset($_POST['simpan'])){
  $nama  = htmlspecialchars($_POST['nama']);
  $harga = intval($_POST['harga']);
  $stok  = intval($_POST['stok']);

  if($nama == "" || $harga <= 0 || $stok < 0){
    $error = "Semua data harus diisi dengan benar!";
  } else {
    // Gunakan Prepared Statement agar lebih aman & profesional
    $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, stok) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $nama, $harga, $stok);
    
    if($stmt->execute()){
      $_SESSION['success'] = "Produk berhasil ditambahkan!";
      header("Location: index.php");
      exit;
    } else {
      $error = "Gagal menyimpan data ke database!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; border: none; }
    .vh-100 { min-height: 100vh; }
    label { font-weight: 500; margin-bottom: 5px; color: #444; }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 450px;">

    <h4 class="text-center mb-4">➕ Tambah Produk</h4>

    <?php if($error): ?>
      <div class="alert alert-danger py-2 small"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">

      <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="nama" class="form-control" placeholder="Contoh: Nasi Goreng" required>
      </div>

      <div class="mb-3">
        <label>Harga (Rp)</label>
        <div class="input-group">
          <span class="input-group-text bg-light text-muted">Rp</span>
          <input type="number" name="harga" class="form-control" placeholder="0" required>
        </div>
      </div>

      <div class="mb-3">
        <label>Stok Awal</label>
        <input type="number" name="stok" class="form-control" placeholder="0" required>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="index.php" class="btn btn-secondary px-3">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="simpan" class="btn btn-success px-4">
          <i class="bi bi-save"></i> Simpan
        </button>
      </div>

    </form>

  </div>
</div>

</body>
</html>