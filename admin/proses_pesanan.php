<?php
// FILE: admin/proses_pesanan.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include '../koneksi.php';

if (isset($_GET['action']) && $_GET['action'] == 'konfirmasi' && isset($_GET['id'])) {
    $id_pesanan = intval($_GET['id']);
    
    // Query untuk mengubah status pembayaran dari PENDING menjadi LUNAS
    $sql_update = "UPDATE pesanan SET status_pembayaran = 'LUNAS' WHERE id_pesanan = $id_pesanan";
    
    if (mysqli_query($conn, $sql_update)) {
        header("Location: pesanan.php?status=sukses_konfirmasi");
        exit();
    } else {
        die("Gagal mengkonfirmasi pesanan: " . mysqli_error($conn));
    }
} else {
    header("Location: pesanan.php");
    exit();
}
?>