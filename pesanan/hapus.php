<?php
session_start();

// PERBAIKAN: Tambahkan ../ untuk naik satu folder ke direktori utama
include '../koneksi.php';

// Proteksi login - Gunakan ../ agar kembali ke halaman login di folder utama
if(!isset($_SESSION['login'])){
  header("Location: ../login.php");
  exit;
}

// Validasi ID
if(isset($_GET['id'])){
  $id = intval($_GET['id']);

  // Pastikan variabel $conn tersedia dari file koneksi.php
  if (isset($conn)) {
      $stmt = $conn->prepare("DELETE FROM pesanan WHERE id=?");
      $stmt->bind_param("i", $id);
      
      if($stmt->execute()){
          $_SESSION['success'] = "Data pesanan berhasil dihapus!";
      } else {
          $_SESSION['error'] = "Gagal menghapus data!";
      }
  }
}

// Redirect kembali ke dashboard yang ada di folder utama
header("Location: ../dashboard.php");
exit;
?>