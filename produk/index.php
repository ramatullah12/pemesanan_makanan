<?php
session_start();
include '../koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// Ambil data produk
$data = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");

if(!$data){
  die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Data Produk</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icon -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    .table th { background-color: #0d6efd; color: white; }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary shadow">
  <div class="container">
    <a class="navbar-brand">🍔 Menu Produk</a>

    <div>
      <a href="../dashboard.php" class="btn btn-light btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-5">
  <div class="card shadow p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">📋 Data Produk</h4>

      <a href="tambah_produk.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Tambah Produk
      </a>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="table table-hover text-center align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th width="150">Aksi</th>
          </tr>
        </thead>

        <tbody>
<?php
$no = 1;

if(mysqli_num_rows($data) > 0){
  while($d = mysqli_fetch_assoc($data)){
?>
<tr>
  <td><?= $no++; ?></td>

  <td><?= htmlspecialchars($d['nama_produk']); ?></td>

  <td>
    <span class="text-success fw-bold">
      Rp <?= number_format($d['harga'], 0, ',', '.'); ?>
    </span>
  </td>

  <td>
    <span class="badge bg-secondary">
      <?= htmlspecialchars($d['stok']); ?>
    </span>
  </td>

  <td>
    <!-- EDIT -->
    <a href="edit_produk.php?id=<?= $d['id']; ?>" class="btn btn-warning btn-sm">
      <i class="bi bi-pencil"></i>
    </a>

    <!-- HAPUS -->
    <a href="hapus_produk.php?id=<?= $d['id']; ?>" 
       class="btn btn-danger btn-sm btn-hapus">
      <i class="bi bi-trash"></i>
    </a>
  </td>
</tr>
<?php 
  }
} else {
?>
<tr>
  <td colspan="5">Data produk masih kosong</td>
</tr>
<?php } ?>
        </tbody>

      </table>
    </div>

  </div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// KONFIRMASI HAPUS
document.querySelectorAll('.btn-hapus').forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault();
    const url = this.getAttribute('href');

    Swal.fire({
      title: 'Hapus produk?',
      text: "Data akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
  });
});
</script>

<!-- NOTIFIKASI -->
<?php if(isset($_SESSION['success'])){ ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?php echo $_SESSION['success']; ?>'
});
</script>
<?php unset($_SESSION['success']); } ?>

<?php if(isset($_SESSION['error'])){ ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '<?php echo $_SESSION['error']; ?>'
});
</script>
<?php unset($_SESSION['error']); } ?>

</body>
</html>