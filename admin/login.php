<?php
// FILE: admin/login.php
session_start();
include '../koneksi.php'; // Koneksi keluar satu folder

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = 'admin'; // Username admin statis
    $password_input = $_POST['password'];

    // PENTING: Untuk contoh sederhana, kita gunakan password statis '12345'
    // DI PROYEK NYATA: Anda harus menyimpan hash password admin di database juga.
    if ($username == 'admin' && $password_input == '12345') { 
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $pesan = "Username atau Password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <p style="color:red;"><?php echo $pesan; ?></p>
    
    <form method="POST" action="login.php">
        Username: <br>
        <input type="text" name="username" value="admin" readonly><br><br>
        
        Password: <br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login Admin</button>
    </form>
</body>
</html>