<?php
// FILE: admin/dashboard.php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
// ... di file admin/dashboard.php
<body>
    <h1>Selamat Datang, Admin!</h1>
    <a href="pesanan.php">Kelola Pesanan (Pending)</a> | 
    <a href="produk.php">Kelola Produk & Stok</a> | 
    <a href="logout.php">Logout</a>
    
    </body>
</html>