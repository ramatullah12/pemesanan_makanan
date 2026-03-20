<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: login.php");
  exit;
}

// Ambil data
$query = "SELECT * FROM pesanan";
$result = mysqli_query($conn, $query);

if(!$result){
  die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Pemesanan</title>

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
    <a class="navbar-brand">Aplikasi Pemesanan</a>
    <div>
      <span class="text-white me-3">
        👤 <?php echo $_SESSION['username']; ?>
      </span>
      <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-5">
  <div class="card shadow p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-3">
      <h4>📦 Data Pesanan</h4>
      <a href="tambah.php" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Tambah
      </a>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="table table-hover text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
<?php
$no = 1;

if(mysqli_num_rows($result) > 0){
  while($d = mysqli_fetch_assoc($result)){

    // Aman jika id belum ada
    $id = isset($d['id']) ? $d['id'] : 0;
?>
<tr>
  <td><?php echo $no++; ?></td>
  <td><?php echo htmlspecialchars($d['nama']); ?></td>
  <td><?php echo htmlspecialchars($d['produk']); ?></td>
  <td>
    <span class="badge bg-primary">
      <?php echo htmlspecialchars($d['jumlah']); ?>
    </span>
  </td>

  <td>
    <?php if($id != 0){ ?>

      <!-- EDIT -->
      <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil"></i>
      </a>

      <!-- HAPUS -->
      <a href="hapus.php?id=<?php echo $id; ?>" 
         class="btn btn-danger btn-sm btn-hapus">
        <i class="bi bi-trash"></i>
      </a>

    <?php } else { ?>
      <span class="text-danger">ID tidak ada</span>
    <?php } ?>
  </td>
</tr>
<?php 
  }
} else {
?>
<tr>
  <td colspan="5">Data masih kosong</td>
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
      title: 'Yakin hapus data?',
      text: "Data tidak bisa dikembalikan!",
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