<?php
session_start();
include 'koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: login.php");
  exit;
}

// Validasi ID
if(isset($_GET['id'])){
  $id = intval($_GET['id']);

  // Hapus data
  $stmt = $conn->prepare("DELETE FROM pesanan WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

// Redirect kembali
header("Location: dashboard.php");
exit;
?><?php if(isset($_SESSION['success'])){ ?>
  <div class="alert alert-success">
    <?php 
      echo $_SESSION['success']; 
      unset($_SESSION['success']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['error'])){ ?>
  <div class="alert alert-danger">
    <?php 
      echo $_SESSION['error']; 
      unset($_SESSION['error']);
    ?>
  </div>
<?php } ?>