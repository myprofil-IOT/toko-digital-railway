<?php
// FILE: admin/produk.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include '../koneksi.php';

$sql_produk = "SELECT * FROM produk ORDER BY id_produk DESC";
$hasil_produk = mysqli_query($conn, $sql_produk);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk</title>
</head>
<body>
    <h2>Daftar Produk yang Dijual</h2>
    <a href="dashboard.php">← Dashboard</a> | <a href="tambah_produk.php">➕ Tambah Produk Baru</a>
    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php while($produk = mysqli_fetch_assoc($hasil_produk)): ?>
        <tr>
            <td><?php echo $produk['id_produk']; ?></td>
            <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
            <td>Rp <?php echo number_format($produk['harga']); ?></td>
            <td>
                <a href="stok.php?id=<?php echo $produk['id_produk']; ?>">Kelola Stok Digital</a> | 
                <a href="edit_produk.php?id=<?php echo $produk['id_produk']; ?>">Edit</a> |
                <a href="proses_produk.php?action=hapus&id=<?php echo $produk['id_produk']; ?>" 
                   onclick="return confirm('Yakin menghapus produk ini? Semua stok terkait akan hilang.');">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>