<?php
// FILE: tokoku/terima_kasih.php

include 'header.php';

$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

    <h2>🎉 Transaksi Berhasil!</h2>
    <p class="success-msg">Pesanan Anda telah berhasil dicatat dengan ID Pesanan: <strong>#<?php echo $id_pesanan; ?></strong>.</p>
    
    <h3>Apa langkah selanjutnya?</h3>
    <ol>
        <li>Pesanan Anda saat ini berstatus **PENDING**.</li>
        <li>Admin (Anda) harus memproses pembayaran ini di halaman Admin.</li>
        <li>Setelah status diubah menjadi **LUNAS**, kode digital Anda akan muncul di halaman **Akun Saya**.</li>
    </ol>
    
    <p>Lihat status pesanan Anda: <a href="akun_saya.php">Kembali ke Akun Saya</a></p>

<?php
include 'footer.php';
?>