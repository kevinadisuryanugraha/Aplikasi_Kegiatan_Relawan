<?php
require_once 'config.php';
require_once 'functions.php';

// Cek apakah pengguna sudah login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada kegiatan yang ingin diedit
if (isset($_GET['kegiatan_id'])) {
    $kegiatan_id = $_GET['kegiatan_id'];
    // Ambil data kegiatan berdasarkan ID
    $kegiatan = ambil_kegiatan_by_id($kegiatan_id);

    // Cek apakah kegiatan ada dan user memiliki akses
    if (!$kegiatan || $kegiatan['user_id'] != $user_id) {
        header('Location: home.php');
        exit();
    }
} else {
    header('Location: home.php');
    exit();
}

// Proses update kegiatan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
    $lokasi_kegiatan = $_POST['lokasi_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $durasi_kegiatan = $_POST['durasi_kegiatan'];
    $jumlah_relawan = $_POST['jumlah_relawan'];
    $dokumentasi = $_FILES['dokumentasi'];

    // Update kegiatan
    if (update_kegiatan($kegiatan_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi)) {
        header('Location: home.php');
        exit();
    } else {
        echo "<p style='color:red;'>Terjadi kesalahan saat mengupdate kegiatan.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan</title>
    <link rel="stylesheet" href="css/edit.css">
</head>

<body>
    <div class="container">
        <h2>Edit Kegiatan</h2>
        <form action="edit_kegiatan.php?kegiatan_id=<?php echo $kegiatan_id; ?>" method="POST" enctype="multipart/form-data">
            <label for="nama_kegiatan">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?php echo htmlspecialchars($kegiatan['nama_kegiatan']); ?>" required autofocus>

            <label for="tanggal_kegiatan">Tanggal Kegiatan</label>
            <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?php echo htmlspecialchars($kegiatan['tanggal_kegiatan']); ?>" required>

            <label for="lokasi_kegiatan">Lokasi Kegiatan</label>
            <input type="text" id="lokasi_kegiatan" name="lokasi_kegiatan" value="<?php echo htmlspecialchars($kegiatan['lokasi_kegiatan']); ?>">

            <label for="deskripsi">Deskripsi Kegiatan</label>
            <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($kegiatan['deskripsi']); ?></textarea>

            <label for="durasi_kegiatan">Durasi Kegiatan (jam)</label>
            <input type="number" id="durasi_kegiatan" name="durasi_kegiatan" min="1" value="<?php echo htmlspecialchars($kegiatan['durasi_kegiatan']); ?>" required>

            <label for="jumlah_relawan">Jumlah Relawan</label>
            <input type="number" id="jumlah_relawan" name="jumlah_relawan" min="1" value="<?php echo htmlspecialchars($kegiatan['jumlah_relawan']); ?>" required>

            <label for="dokumentasi">Dokumentasi (Opsional)</label>
            <input type="file" id="dokumentasi" name="dokumentasi">

            <button type="submit">Update Kegiatan</button>

            <a href="home.php" class="back_button">Kembali</a>
        </form>
    </div>
</body>

</html>