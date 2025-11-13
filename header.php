<?php 
// FILE: tokoku/header.php

// PENTING: Panggil session_start() di sini agar semua halaman memiliki sesi
session_start();
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Digital</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="index.php">🏠 Beranda</a>
                <?php if (isset($_SESSION['id_pengguna'])): ?>
                    <a href="keranjang.php">🛒 Keranjang</a>
                    <a href="akun_saya.php">👤 Akun Saya</a>
                    <a href="logout.php">Keluar</a>
                <?php else: ?>
                    <a href="login.php">🔑 Login</a>
                    <a href="register.php">📝 Daftar</a>
                <?php endif; ?>
                <a href="admin/login.php" style="margin-left: auto; color: yellow;">⚙️ Admin</a>
            </nav>
        </div>
    </header>
    
    <div class="container main-content">