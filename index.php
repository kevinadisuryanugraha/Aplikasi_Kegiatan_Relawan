<?php
require_once 'functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $check_user = ambil_user_by_email($email);

    if ($check_user) {
        $_SESSION['user_id'] = $check_user['user_id'];
        header("Location: home.php");
        exit();
    } else {
        $user_id = tambah_user($username, $email, $nomor_telepon);

        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            header("Location: home.php");
            exit();
        } else {
            echo "gagal menambahkan pengguna: " . $db->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan Relawan</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="form-section">
            <h2>Form Daftar Masuk</h2>
            <form action="" method="POST">
                <input type="text" name="username" placeholder="Nama Lengkap" required autofocus>
                <input type="email" name="email" placeholder="Email" required>
                <input type="number" name="nomor_telepon" placeholder="Nomor Telepon" required>
                <input type="submit" value="Submit">
            </form>
        </div>

        <div class="welcome-section">
            <h1>Selamat datang di Aplikasi Kegiatan Relawan</h1>
        </div>
    </div>
</body>

</html>