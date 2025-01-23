<?php
require_once 'config.php';
require_once 'functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
    $lokasi_kegiatan = $_POST['lokasi_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $durasi_kegiatan = $_POST['durasi_kegiatan'];
    $jumlah_relawan = $_POST['jumlah_relawan'];
    $dokumentasi = $_FILES['dokumentasi'];

    if (tambah_kegiatan($user_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi)) {
        header("Location: home.php?status=success&message=Kegiatan berhasil Ditambahkan");
        exit();
    } else {
        header("Location: tambah_kegiatan.php?status=error&message=Kegiatan gagal diperbarui");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kegiatan</title>
    <link rel="stylesheet" href="css/tambah.css">
</head>

<body>
    <div class="container">
        <h2>Tambah Kegiatan Relawan</h2>

        <?php
        if (isset($_GET['status']) && isset($_GET['message'])) {
            $status = $_GET['status'];
            $message = $_GET['message'];

            if ($status === 'success') {
                echo "<script>alert('Berhasil: $message');</script>";
            } else {
                echo "<script>alert('Gagal: $message');</script>";
            }
        }
        ?>

        <form action="tambah_kegiatan.php" method="POST" enctype="multipart/form-data">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" required autofocus>

            <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
            <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" required>

            <label for="lokasi_kegiatan">Lokasi Kegiatan</label>
            <input type="text" id="lokasi_kegiatan" name="lokasi_kegiatan">

            <label for="deskripsi">Deskripsi Kegiatan</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>

            <label for="durasi_kegiatan">Durasi Kegiatan (jam)</label>
            <input type="number" id="durasi_kegiatan" name="durasi_kegiatan" min="1" required>

            <label for="jumlah_relawan">Jumlah Relawan</label>
            <input type="number" id="jumlah_relawan" name="jumlah_relawan" min="1" required>

            <label for="dokumentasi">Dokumentasi</label>
            <input type="file" id="dokumentasi" name="dokumentasi">

            <button type="submit">Tambah Kegiatan</button>

            <a href="home.php" class="back_button">Kembali</a>
        </form>

    </div>
</body>

</html>