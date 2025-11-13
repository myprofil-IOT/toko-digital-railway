<?php
// FILE: tokoku/checkout.php

include 'header.php'; 

// Cek jika pengguna belum login atau keranjang kosong
if (!isset($_SESSION['id_pengguna']) || empty($_SESSION['keranjang'])) {
    header("Location: index.php");
    exit();
}

$id_pengguna = $_SESSION['id_pengguna'];
$total_harga = 0;

// --- LOGIKA UTAMA CHECKOUT/TRANSAKSI ---
if (isset($_POST['konfirmasi_pesanan'])) {
    
    // 1. Hitung total harga final
    $produk_count = array_count_values($_SESSION['keranjang']);
    $id_list = implode(",", array_unique($_SESSION['keranjang']));
    $sql_produk = "SELECT id_produk, harga FROM produk WHERE id_produk IN ($id_list)";
    $result_produk = mysqli_query($conn, $sql_produk);
    
    $harga_produk = [];
    while ($row = mysqli_fetch_assoc($result_produk)) {
        $harga_produk[$row['id_produk']] = $row['harga'];
    }
    
    $total_final = 0;
    foreach ($produk_count as $id_produk => $jumlah) {
        $total_final += $harga_produk[$id_produk] * $jumlah;
    }
    
    // 2. INSERT ke tabel pesanan
    $tanggal = date('Y-m-d H:i:s');
    $sql_insert_pesanan = "INSERT INTO pesanan (id_pengguna, total_harga, tanggal_pesanan, status_pembayaran) 
                           VALUES ($id_pengguna, $total_final, '$tanggal', 'PENDING')";
    
    if (mysqli_query($conn, $sql_insert_pesanan)) {
        $id_pesanan_baru = mysqli_insert_id($conn);
        $berhasil_alokasi = true;

        // 3. Looping untuk setiap item di keranjang dan alokasikan kode unik (data_digital)
        foreach ($produk_count as $id_produk => $jumlah) {
            // Looping sebanyak jumlah item yang dibeli
            for ($i = 0; $i < $jumlah; $i++) {
                
                // A. Ambil 1 kode unik yang statusnya 'TERSEDIA'
                $sql_ambil_kode = "SELECT id_data, konten_digital FROM data_digital 
                                   WHERE id_produk_asal = $id_produk AND status = 'TERSEDIA' LIMIT 1";
                $result_kode = mysqli_query($conn, $sql_ambil_kode);
                
                if ($row_kode = mysqli_fetch_assoc($result_kode)) {
                    $id_data = $row_kode['id_data'];
                    $konten_digital = mysqli_real_escape_string($conn, $row_kode['konten_digital']);
                    
                    // B. INSERT ke detail_pesanan
                    $sql_insert_detail = "INSERT INTO detail_pesanan (id_pesanan, id_produk, konten_digital) 
                                          VALUES ($id_pesanan_baru, $id_produk, '$konten_digital')";
                    mysqli_query($conn, $sql_insert_detail);
                    
                    // C. UPDATE status di data_digital menjadi 'TERJUAL'
                    $sql_update_stok = "UPDATE data_digital SET status = 'TERJUAL', id_pesanan_terkait = $id_pesanan_baru 
                                        WHERE id_data = $id_data";
                    mysqli_query($conn, $sql_update_stok);

                } else {
                    // Jika stok habis saat transaksi, ini adalah error fatal
                    $berhasil_alokasi = false;
                    // Log error atau batalkan transaksi
                    break 2; // Keluar dari kedua loop
                }
            }
        }

        // 4. Bersihkan Keranjang dan Arahkan ke halaman Terima Kasih
        if ($berhasil_alokasi) {
            unset($_SESSION['keranjang']);
            header("Location: terima_kasih.php?id=$id_pesanan_baru");
            exit();
        }
        
    } else {
        die("Gagal membuat pesanan: " . mysqli_error($conn));
    }
}
// --- AKHIR LOGIKA UTAMA ---

// Tampilan Ringkasan (jika belum konfirmasi)
$produk_count = array_count_values($_SESSION['keranjang']);
$id_list = implode(",", array_unique($_SESSION['keranjang']));
$sql_ringkasan = "SELECT id_produk, nama_produk, harga FROM produk WHERE id_produk IN ($id_list)";
$result_ringkasan = mysqli_query($conn, $sql_ringkasan);

$daftar_produk = [];
while ($row = mysqli_fetch_assoc($result_ringkasan)) {
    $daftar_produk[$row['id_produk']] = $row;
}
?>

    <h2>Checkout Pesanan</h2>
    
    <h3>Ringkasan Pesanan</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $total_keseluruhan = 0;
        foreach ($produk_count as $id_produk => $jumlah): 
            $produk = $daftar_produk[$id_produk];
            $subtotal = $produk['harga'] * $jumlah;
            $total_keseluruhan += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                <td><?php echo $jumlah; ?></td>
                <td>Rp <?php echo number_format($produk['harga']); ?></td>
                <td>Rp <?php echo number_format($subtotal); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" align="right"><strong>Total Pembayaran:</strong></td>
            <td><strong>Rp <?php echo number_format($total_keseluruhan); ?></strong></td>
        </tr>
    </table>
    
    <br>
    <h3>Instruksi Pembayaran (Simulasi)</h3>
    <p>Silakan lakukan transfer ke rekening Virtual Account (Contoh: **1234567890** a.n. Toko Digital) sebesar **Rp <?php echo number_format($total_keseluruhan); ?>**.</p>
    <p>Setelah transfer, klik tombol konfirmasi di bawah.</p>

    <form method="POST" action="checkout.php">
        <input type="hidden" name="konfirmasi_pesanan" value="1">
        <button type="submit" style="background-color: #28a745;">✅ Saya Sudah Bayar dan Konfirmasi Pesanan</button>
    </form>

<?php
include 'footer.php';
?>