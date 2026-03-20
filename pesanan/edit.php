<?php
session_start();
include '../koneksi.php'; // ✅ FIX PATH

// Cek koneksi
if(!$conn){
  die("Koneksi database gagal!");
}

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// Validasi ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0){
  die("ID tidak valid!");
}

// Ambil data pesanan
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if(!$data){
  die("Data tidak ditemukan!");
}

// Ambil produk
$produk = mysqli_query($conn, "SELECT * FROM produk");

// Update data
if(isset($_POST['update'])){
  $nama   = htmlspecialchars($_POST['nama']);
  $produk_nama = $_POST['produk'];
  $harga  = intval($_POST['harga']);
  $jumlah = intval($_POST['jumlah']);
  $total  = $harga * $jumlah;

  if($nama == "" || $produk_nama == "" || $jumlah <= 0){
    $error = "Semua data wajib diisi!";
  } else {
    $stmt = $conn->prepare("UPDATE pesanan 
      SET nama_pelanggan=?, produk=?, harga=?, jumlah=?, total=? 
      WHERE id=?");

    if($stmt){
      $stmt->bind_param("ssiiii", $nama, $produk_nama, $harga, $jumlah, $total, $id);
      $stmt->execute();

      $_SESSION['success'] = "Pesanan berhasil diupdate!";
      header("Location: ../dashboard.php"); // ✅ FIX PATH
      exit;
    } else {
      $error = "Gagal update data!";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Pesanan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
  </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 450px;">
    
    <h4 class="text-center mb-3">✏️ Edit Pesanan</h4>

    <?php if(isset($error)){ ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php } ?>

    <form method="POST">

      <!-- Nama -->
      <div class="mb-3">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" class="form-control"
               value="<?= htmlspecialchars($data['nama_pelanggan']); ?>" required>
      </div>

      <!-- Produk -->
      <div class="mb-3">
        <label>Produk</label>
        <select name="produk" id="produk" class="form-control" onchange="setHarga()" required>
          <option value="">-- Pilih Produk --</option>
          <?php while($p = mysqli_fetch_assoc($produk)){ ?>
            <option 
              value="<?= $p['nama_produk']; ?>"
              data-harga="<?= $p['harga']; ?>"
              <?= ($p['nama_produk'] == $data['produk']) ? 'selected' : ''; ?>>
              <?= $p['nama_produk']; ?> (Rp <?= number_format($p['harga'],0,',','.'); ?>)
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Harga -->
      <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" id="harga" class="form-control"
               value="<?= $data['harga']; ?>" readonly>
      </div>

      <!-- Jumlah -->
      <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" class="form-control"
               value="<?= $data['jumlah']; ?>" min="1" required onkeyup="hitungTotal()">
      </div>

      <!-- Total -->
      <div class="mb-3">
        <label>Total</label>
        <input type="number" id="total" class="form-control"
               value="<?= $data['total']; ?>" readonly>
      </div>

      <div class="d-flex justify-content-between">
        <a href="../dashboard.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button name="update" class="btn btn-primary">
          <i class="bi bi-save"></i> Update
        </button>
      </div>

    </form>

  </div>

</div>

<script>
// Set harga otomatis
function setHarga(){
  let select = document.getElementById("produk");
  let harga = select.options[select.selectedIndex].getAttribute("data-harga");
  document.getElementById("harga").value = harga;
  hitungTotal();
}

// Hitung total
function hitungTotal(){
  let harga = document.getElementById("harga").value;
  let jumlah = document.getElementById("jumlah").value;

  let total = harga * jumlah;
  document.getElementById("total").value = total;
}
</script>

</body>
</html>