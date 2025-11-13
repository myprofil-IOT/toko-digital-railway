<?php
// FILE: tokoku/login.php

include 'header.php'; // Memanggil session_start(), koneksi, dan navigasi

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    $sql = "SELECT id_pengguna, username, password FROM pengguna WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password_input, $row['password'])) {
            $_SESSION['id_pengguna'] = $row['id_pengguna'];
            $_SESSION['username'] = $row['username'];
            header("Location: akun_saya.php");
            exit();
        } else {
            $pesan = "Email atau Password salah.";
        }
    } else {
        $pesan = "Email atau Password salah.";
    }
}
?>

    <h2>Login Akun Pelanggan</h2>
    
    <?php if ($pesan): ?>
        <p class="error-msg"><?php echo $pesan; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </form>

<?php
include 'footer.php';
?>