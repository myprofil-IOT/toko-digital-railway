<?php
// FILE: admin/pesanan.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include '../koneksi.php';

// Ambil semua pesanan yang statusnya PENDING
$sql_pesanan = "SELECT 
    p.id_pesanan, 
    p.total_harga, 
    p.tanggal_pesanan, 
    u.username
FROM pesanan p
JOIN pengguna u ON p.id_pengguna = u.id_pengguna
WHERE p.status_pembayaran = 'PENDING'
ORDER BY p.tanggal_pesanan ASC";

$hasil_pesanan = mysqli_query($conn, $sql_pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan Pending</title>
</head>
<body>
    <h2>Daftar Pesanan Pending (Menunggu Pembayaran)</h2>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
    <br><br>

    <?php if (mysqli_num_rows($hasil_pesanan) == 0): ?>
        <p>Tidak ada pesanan yang sedang menunggu konfirmasi pembayaran.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID Pesanan</th>
                <th>User</th>
                <th>Total Harga</th>
                <th>Waktu Pesan</th>
                <th>Aksi</th>
            </tr>
            <?php while($pesanan = mysqli_fetch_assoc($hasil_pesanan)): ?>
            <tr>
                <td><?php echo $pesanan['id_pesanan']; ?></td>
                <td><?php echo htmlspecialchars($pesanan['username']); ?></td>
                <td>Rp <?php echo number_format($pesanan['total_harga']); ?></td>
                <td><?php echo $pesanan['tanggal_pesanan']; ?></td>
                <td>
                    <a href="proses_pesanan.php?action=konfirmasi&id=<?php echo $pesanan['id_pesanan']; ?>" 
                       onclick="return confirm('Yakin ingin mengkonfirmasi pembayaran ini (UBAH STATUS KE LUNAS)?');">
                        Konfirmasi LUNAS
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>