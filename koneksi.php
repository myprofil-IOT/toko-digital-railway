<?php
// FILE: tokoku/koneksi.php

// --- KEAMANAN DASAR (Tahap 20) ---
// Matikan laporan error agar tidak muncul di browser saat sudah live (Penting untuk keamanan!)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); 

// --- PENGATURAN KONEKSI DATABASE (Tahap 22) ---
// GANTI nilai di bawah ini dengan detail database hosting Anda!

$host = "shuttle.proxy.rlwy.net";        // GANTI DENGAN HOSTNAME DARI HOSTING (Seringnya tetap "localhost")
$user = "root";     
$pass = "MoRBgDrWZaCAyageYtziePNeJHidihKf";  
$db_name = "railway";      // GANTI DENGAN NAMA DATABASE ONLINE ANDA

$conn = mysqli_connect($host, $user, $pass, $db_name);

// Cek koneksi
if (!$conn) {
    // Di lingkungan live, tampilkan pesan generik saja, jangan detail error!
    die("Koneksi ke database gagal. Silakan hubungi administrator.");
    // Atau bisa menggunakan: die("Koneksi database gagal: " . mysqli_connect_error());
}
?>