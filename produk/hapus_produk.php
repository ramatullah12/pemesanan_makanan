<?php
session_start();
include '../koneksi.php';

// Proteksi login
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// Validasi ID
if(isset($_GET['id'])){
  $id = intval($_GET['id']);

  // Hapus data menggunakan Prepared Statement (Sesuai Referensi)
  $stmt = $conn->prepare("DELETE FROM produk WHERE id=?");
  $stmt->bind_param("i", $id);
  
  if($stmt->execute()){
    $_SESSION['success'] = "Produk berhasil dihapus!";
  } else {
    $_SESSION['error'] = "Gagal menghapus produk!";
  }
}

header("Location: index.php");
exit;
?>