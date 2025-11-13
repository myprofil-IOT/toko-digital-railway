<?php
// FILE: admin/stok.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include '../koneksi.php';

if (!isset($_GET['id'])) { header("Location: produk.php"); exit(); }
$id_produk = intval($_GET['id']);

$sql_produk = "SELECT nama_produk FROM produk WHERE id_produk = $id_produk";
$produk = mysqli_fetch_assoc(mysqli_query($conn, $sql_produk));

$sql_stok = "SELECT * FROM data_digital WHERE id_produk_asal = $id_produk ORDER BY status DESC";
$hasil_stok = mysqli_query($conn, $sql_stok);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Digital: <?php echo $produk['nama_produk']; ?></title>
</head>
<body>
    <h2>Kelola Stok Kode untuk <?php echo htmlspecialchars($produk['nama_produk']); ?></h2>
    <a href="produk.php">← Kembali ke Daftar Produk</a>
    <br><br>

    <form method="POST" action="proses_produk.php?action=tambah_stok&id=<?php echo $id_produk; ?>">
        <label>Kode Baru (Lisensi/Akun):</label>
        <input type="text" name="konten_digital" required size="50">
        <button type="submit">➕ Tambah Kode</button>
    </form>
    
    <hr>
    <h3>Stok yang Tersedia (Total: <?php echo mysqli_num_rows($hasil_stok); ?>)</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID Data</th>
            <th>Konten Digital</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while($data = mysqli_fetch_assoc($hasil_stok)): ?>
        <tr>
            <td><?php echo $data['id_data']; ?></td>
            <td><?php echo htmlspecialchars($data['konten_digital']); ?></td>
            <td>
                <?php 
                    $status_warna = ($data['status'] == 'TERSEDIA') ? 'green' : 'red';
                    echo "<span style='color: $status_warna; font-weight: bold;'>{$data['status']}</span>"; 
                ?>
            </td>
            <td>
                <?php if ($data['status'] == 'TERSEDIA'): ?>
                    <a href="proses_produk.php?action=hapus_stok&id=<?php echo $data['id_data']; ?>" 
                       onclick="return confirm('Hapus kode ini dari stok?');">Hapus</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>