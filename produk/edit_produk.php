<?php 
session_start();
// Naik satu folder ke atas (../) untuk menemukan koneksi.php
require '../koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// 1. Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    header("Location: index.php"); // Kembali ke daftar produk di folder yang sama
    exit;
}

// 2. Ambil data produk menggunakan Prepared Statement
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$d = $result->fetch_assoc();

if (!$d) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$error = "";

// 3. Proses Update
if(isset($_POST['update'])){
  $nama  = htmlspecialchars($_POST['nama']);
  $harga = intval($_POST['harga']);
  $stok  = intval($_POST['stok']);

  if($nama == "" || $harga <= 0 || $stok < 0){
    $error = "Semua field wajib diisi dengan benar!";
  } else {
    $update = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=? WHERE id=?");
    $update->bind_param("siii", $nama, $harga, $stok, $id);
    
    if($update->execute()){
        $_SESSION['success'] = "Produk berhasil diperbarui!";
        header("Location: index.php"); // Sesuaikan ke index.php di folder produk
        exit;
    } else {
        $error = "Gagal mengupdate data!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; border: none; }
    label { font-weight: 500; margin-bottom: 5px; color: #333; }
    .btn { border-radius: 8px; }
    .vh-100 { min-height: 100vh; }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 450px;">
    
    <h4 class="text-center mb-4">📦 Edit Produk</h4>

    <?php if($error): ?>
      <div class="alert alert-danger py-2 small"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">

      <div class="mb-3">
        <label>Nama Produk</label>
        <input type="text" name="nama" class="form-control" 
               value="<?= htmlspecialchars($d['nama_produk']); ?>" required>
      </div>

      <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" class="form-control" 
               value="<?= $d['harga']; ?>" required>
      </div>

      <div class="mb-3">
        <label>Stok Barang</label>
        <input type="number" name="stok" class="form-control" 
               value="<?= $d['stok']; ?>" required>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="index.php" class="btn btn-secondary text-white">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="update" class="btn btn-success px-4">
          <i class="bi bi-save"></i> Simpan
        </button>
      </div>

    </form>

    <div class="text-center mt-4 text-muted small">
        ID Produk: <span class="badge bg-light text-dark">#<?= $d['id']; ?></span>
    </div>

  </div>

</div>

</body>
</html>