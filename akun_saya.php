<?php
// FILE: tokoku/akun_saya.php

include 'header.php';

// Cek jika pengguna belum login, arahkan ke login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$username = $_SESSION['username'];

// Ambil riwayat pesanan pengguna
$sql_riwayat = "SELECT id_pesanan, total_harga, tanggal_pesanan, status_pembayaran 
                FROM pesanan 
                WHERE id_pengguna = $id_pengguna 
                ORDER BY tanggal_pesanan DESC";
$result_riwayat = mysqli_query($conn, $sql_riwayat);
?>

    <h2>Selamat Datang, <?php echo htmlspecialchars($username); ?></h2>
    
    <h3>Riwayat Pesanan Anda</h3>
    
    <?php if (mysqli_num_rows($result_riwayat) == 0): ?>
        <p>Anda belum memiliki riwayat pesanan. <a href="index.php">Mulai belanja</a> sekarang!</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Produk Digital</th>
            </tr>
            <?php while($pesanan = mysqli_fetch_assoc($result_riwayat)): ?>
            <tr>
                <td>#<?php echo $pesanan['id_pesanan']; ?></td>
                <td><?php echo $pesanan['tanggal_pesanan']; ?></td>
                <td>Rp <?php echo number_format($pesanan['total_harga']); ?></td>
                <td>
                    <?php 
                        $status = $pesanan['status_pembayaran'];
                        $color = ($status == 'LUNAS') ? 'green' : 'red';
                        echo "<span style='color:$color; font-weight:bold;'>$status</span>";
                    ?>
                </td>
                <td>
                    <?php if ($status == 'LUNAS'): 
                        // Jika LUNAS, tampilkan kode digital
                        $id_pesanan = $pesanan['id_pesanan'];
                        $sql_kode = "SELECT d.konten_digital, p.nama_produk 
                                     FROM detail_pesanan d
                                     JOIN produk p ON d.id_produk = p.id_produk
                                     WHERE d.id_pesanan = $id_pesanan";
                        $result_kode = mysqli_query($conn, $sql_kode);
                        
                        echo "<ul>";
                        while($kode = mysqli_fetch_assoc($result_kode)) {
                            echo "<li>**" . htmlspecialchars($kode['nama_produk']) . "**: <code>" . htmlspecialchars($kode['konten_digital']) . "</code></li>";
                        }
                        echo "</ul>";
                    ?>
                    <?php else: ?>
                        Menunggu konfirmasi pembayaran (Lihat di admin).
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

<?php
include 'footer.php';
?>