<?php 
// FILE: tokoku/index.php (SETELAH DIEDIT)

// 1. Panggil header.php di Awal (Ini akan menjalankan session_start, koneksi, dan mencetak Menu)
include 'header.php'; 

// 2. Kode PHP inti halaman untuk mengambil produk
$sql = "SELECT * FROM produk";
$hasil = mysqli_query($conn, $sql);
?>

    <h1>Selamat Datang di Toko Digital Saya</h1>
    <p>Silakan pilih produk yang Anda inginkan.</p>
    
    <div class="product-grid"> 
    
    <?php 
    // Looping untuk menampilkan setiap produk
    while ($produk = mysqli_fetch_assoc($hasil)): 
    ?>
        
        <div class="product-card"> 
            <p>(Gambar: <?php echo $produk['gambar']; ?>)</p> 
            
            <h3><?php echo htmlspecialchars($produk['nama_produk']); ?></h3>
            
            <div class="price">Rp <?php echo number_format($produk['harga']); ?></div>
            
            <p><?php echo nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
            
            <a href="produk_detail.php?id=<?php echo $produk['id_produk']; ?>">Lihat Detail</a>
        </div>
        
    <?php endwhile; ?>
    
    </div>

<?php 
// 3. Panggil footer.php di Akhir
include 'footer.php'; 
?>