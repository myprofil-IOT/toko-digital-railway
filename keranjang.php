<?php
// FILE: tokoku/keranjang.php

include 'header.php'; 

// Cek jika pengguna belum login, arahkan ke login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$total_harga = 0;

// Logika penambahan produk ke keranjang (jika ada dari produk_detail.php)
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id_produk'])) {
    $id_produk = intval($_GET['id_produk']);
    
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }
    
    // Simpan hanya ID produk, kita asumsikan 1 kali klik = 1 item
    $_SESSION['keranjang'][] = $id_produk;
    
    // Arahkan kembali ke keranjang tanpa query string
    header("Location: keranjang.php");
    exit();
}

// Logika menghapus item dari keranjang (opsional, jika Anda membuat tombol hapus)
// Contoh: if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['index'])) { ... }


// Ambil detail produk dari database
if (!empty($_SESSION['keranjang'])) {
    // Ambil daftar ID produk unik untuk di-query
    $id_list = implode(",", array_unique($_SESSION['keranjang']));
    $sql_keranjang = "SELECT id_produk, nama_produk, harga FROM produk WHERE id_produk IN ($id_list)";
    $result_keranjang = mysqli_query($conn, $sql_keranjang);
    
    $daftar_produk = [];
    while ($row = mysqli_fetch_assoc($result_keranjang)) {
        $daftar_produk[$row['id_produk']] = $row;
    }
}
?>

    <h2>Keranjang Belanja Anda</h2>

    <?php if (empty($_SESSION['keranjang'])): ?>
        <p>Keranjang Anda kosong. Silakan <a href="index.php">mulai berbelanja</a>.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $no = 1;
            // Hitung frekuensi setiap produk di keranjang
            $produk_count = array_count_values($_SESSION['keranjang']);
            $total_keseluruhan = 0;

            foreach ($produk_count as $id_produk => $jumlah): 
                $produk = $daftar_produk[$id_produk];
                $subtotal = $produk['harga'] * $jumlah;
                $total_keseluruhan += $subtotal;
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                    <td><?php echo $jumlah; ?></td>
                    <td>Rp <?php echo number_format($produk['harga']); ?></td>
                    <td>Rp <?php echo number_format($subtotal); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" align="right"><strong>Total Keseluruhan:</strong></td>
                <td><strong>Rp <?php echo number_format($total_keseluruhan); ?></strong></td>
            </tr>
        </table>

        <br>
        <a href="checkout.php" class="button">Lanjutkan ke Checkout →</a>
    <?php endif; ?>

<?php
include 'footer.php';
?>