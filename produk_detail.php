<?php
// FILE: produk_detail.php

include 'koneksi.php';

// 1. Ambil ID dari URL (menggunakan $_GET)
// Jika ID tidak ada, kembalikan ke index.php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_produk = $_GET['id'];

// 2. Query untuk mengambil data produk spesifik
$sql = "SELECT * FROM produk WHERE id_produk = $id_produk";
$hasil = mysqli_query($conn, $sql);
$produk = mysqli_fetch_assoc($hasil);

// Cek jika produk tidak ditemukan
if (!$produk) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($produk['nama_produk']); ?> - Detail</title>
    </head>
<body>
    <div class="container">
        <a href="index.php">← Kembali ke Toko</a>
        
        <h2>Detail Produk: <?php echo htmlspecialchars($produk['nama_produk']); ?></h2>
        
        <div style="width:300px; height:200px; background:#ddd; line-height:200px; text-align:center;">
            (Gambar: <?php echo htmlspecialchars($produk['gambar']); ?>)
        </div>
        
        <p><strong>Harga:</strong> Rp <?php echo number_format($produk['harga']); ?></p>
        
        <h3>Deskripsi:</h3>
        <p><?php echo htmlspecialchars($produk['deskripsi']); ?></p>
        
        <form action="keranjang.php" method="POST">
            <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
            <button type="submit">🛒 Tambah ke Keranjang</button>
        </form>
        
    </div>
</body>
</html>