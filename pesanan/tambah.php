<?php 
session_start();
include '../koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// Ambil data produk
$produk = mysqli_query($conn, "SELECT * FROM produk");

$error = "";

if(isset($_POST['simpan'])){
  $nama   = htmlspecialchars($_POST['nama']);
  $produk_nama = $_POST['produk'];
  $harga  = intval($_POST['harga']);
  $jumlah = intval($_POST['jumlah']);
  $total  = $harga * $jumlah;

  if($nama == "" || $produk_nama == "" || $jumlah <= 0){
    $error = "Semua field wajib diisi!";
  } else {
    $stmt = $conn->prepare("INSERT INTO pesanan (nama_pelanggan, produk, harga, jumlah, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $nama, $produk_nama, $harga, $jumlah, $total);
    $stmt->execute();

    $_SESSION['success'] = "Pesanan berhasil ditambahkan!";
    header("Location: ../dashboard.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tambah Pesanan</title>

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
    
    <h4 class="text-center mb-3">🛒 Tambah Pesanan</h4>

    <?php if($error): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">

      <!-- Nama -->
      <div class="mb-3">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama" class="form-control" required>
      </div>

      <!-- Produk -->
      <div class="mb-3">
        <label>Pilih Produk</label>
        <select name="produk" id="produk" class="form-control" required onchange="setHarga()">
          <option value="">-- Pilih Produk --</option>
          <?php while($p = mysqli_fetch_assoc($produk)){ ?>
            <option 
              value="<?= $p['nama_produk']; ?>" 
              data-harga="<?= $p['harga']; ?>">
              <?= $p['nama_produk']; ?> (Rp <?= number_format($p['harga']); ?>)
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Harga -->
      <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="harga" id="harga" class="form-control" readonly>
      </div>

      <!-- Jumlah -->
      <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required onkeyup="hitungTotal()">
      </div>

      <!-- Total -->
      <div class="mb-3">
        <label>Total</label>
        <input type="number" id="total" class="form-control" readonly>
      </div>

      <!-- Button -->
      <div class="d-flex justify-content-between">
        <a href="../dashboard.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button name="simpan" class="btn btn-success">
          <i class="bi bi-save"></i> Simpan
        </button>
      </div>

    </form>

  </div>

</div>

<script>
// Ambil harga dari produk
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