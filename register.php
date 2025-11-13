<?php
// FILE: tokoku/register.php

include 'header.php'; // Memanggil session_start(), koneksi, dan navigasi

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    // Cek apakah email sudah terdaftar
    $sql_check = "SELECT id_pengguna FROM pengguna WHERE email = '$email'";
    $result_check = mysqli_query($conn, $sql_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $pesan = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
    } else {
        // Hash password sebelum disimpan
        $password_hash = password_hash($password_input, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO pengguna (username, email, password) VALUES ('$username', '$email', '$password_hash')";
        
        if (mysqli_query($conn, $sql)) {
            $pesan = "Registrasi berhasil! Silakan <a href='login.php'>Login</a>.";
        } else {
            $pesan = "Registrasi gagal: " . mysqli_error($conn);
        }
    }
}
?>

    <h2>Registrasi Akun Baru</h2>

    <?php if ($pesan): ?>
        <p class="<?php echo (strpos($pesan, 'berhasil') !== false) ? 'success-msg' : 'error-msg'; ?>">
            <?php echo $pesan; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Daftar Sekarang</button>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </form>

<?php
include 'footer.php';
?>