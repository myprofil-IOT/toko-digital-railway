<?php
// FILE: admin/proses_produk.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include '../koneksi.php';

$action = $_GET['action'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($action == 'tambah_stok') {
    $id_produk = $id;
    $konten = mysqli_real_escape_string($conn, $_POST['konten_digital']);
    
    // Pastikan konten tidak kosong
    if (!empty($konten)) {
        $sql = "INSERT INTO data_digital (id_produk_asal, konten_digital, status) 
                VALUES ($id_produk, '$konten', 'TERSEDIA')";
        mysqli_query($conn, $sql);
    }
    header("Location: stok.php?id=$id_produk");
    exit();
} 
// ... (tambahkan logika untuk tambah_produk, edit_produk, hapus_produk, dan hapus_stok)
// ... (Untuk kesederhanaan, kita hanya fokus pada logika stok saat ini)

if ($action == 'hapus_stok') {
    $id_data = $id;
    
    // Ambil ID produk asal sebelum menghapus
    $res = mysqli_query($conn, "SELECT id_produk_asal FROM data_digital WHERE id_data = $id_data");
    $data = mysqli_fetch_assoc($res);
    $id_produk = $data['id_produk_asal'];

    // Hanya boleh menghapus yang statusnya TERSEDIA
    $sql = "DELETE FROM data_digital WHERE id_data = $id_data AND status = 'TERSEDIA'";
    mysqli_query($conn, $sql);
    
    header("Location: stok.php?id=$id_produk");
    exit();
}

header("Location: produk.php");
exit();
?>