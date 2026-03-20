<?php
session_start();
include 'koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: login.php");
  exit;
}

// Ambil ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if(!$data){
  die("Data tidak ditemukan!");
}

// Update data
if(isset($_POST['update'])){
  $nama = $_POST['nama'];
  $produk = $_POST['produk'];
  $jumlah = $_POST['jumlah'];

  $stmt = $conn->prepare("UPDATE pesanan SET nama=?, produk=?, jumlah=? WHERE id=?");
  $stmt->bind_param("ssii", $nama, $produk, $jumlah, $id);
  $stmt->execute();

  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Pesanan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 400px;">
    
    <h4 class="text-center mb-3">✏️ Edit Pesanan</h4>

    <form method="POST">

      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control"
               value="<?php echo htmlspecialchars($data['nama']); ?>" required>
      </div>

      <div class="mb-3">
        <label>Produk</label>
        <input type="text" name="produk" class="form-control"
               value="<?php echo htmlspecialchars($data['produk']); ?>" required>
      </div>

      <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" class="form-control"
               value="<?php echo htmlspecialchars($data['jumlah']); ?>" required>
      </div>

      <div class="d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
        <button name="update" class="btn btn-primary">💾 Update</button>
      </div>

    </form>

  </div>

</div>

</body>
</html>