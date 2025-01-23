<?php
require_once 'config.php';
require_once 'functions.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['kegiatan_id'])) {
    $kegiatan_id = $_GET['kegiatan_id'];
    $kegiatan = ambil_kegiatan_by_id($kegiatan_id);

    if ($kegiatan === false) {
        echo "kegiatan tidak ditemukan";
    }
} else {
    header('Location: home.php');
    exit();
}

if (!$kegiatan_id) {
    echo "<p style='color:red;'>Kegiatan tidak ditemukan.</p>";
    exit();
}

if (!$kegiatan) {
    echo "<p style='color:red;'>Kegiatan tidak ditemukan.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/detail.css">
</head>

<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($kegiatan['nama_kegiatan']); ?></h2>

        <div class="kegiatan-info">
            <div>
                <label>Tanggal Kegiatan:</label>
                <p><?php echo date("d M Y", strtotime($kegiatan['tanggal_kegiatan'])); ?></p>

                <label>Lokasi Kegiatan:</label>
                <p><?php echo htmlspecialchars($kegiatan['lokasi_kegiatan']); ?></p>

                <label>Deskripsi Kegiatan:</label>
                <p><?php echo nl2br(htmlspecialchars($kegiatan['deskripsi'])); ?></p>

                <label>Durasi Kegiatan:</label>
                <p><?php echo htmlspecialchars($kegiatan['durasi_kegiatan']); ?> jam</p>

                <label>Jumlah Relawan:</label>
                <p><?php echo htmlspecialchars($kegiatan['jumlah_relawan']); ?> </p>
            </div>

            <div>
                <?php if ($kegiatan['dokumentasi']): ?>
                    <label>Dokumentasi:</label>
                    <img src="uploads/kegiatan/<?php echo htmlspecialchars($kegiatan['dokumentasi']); ?>" alt="Dokumentasi Kegiatan">
                <?php endif; ?>
            </div>
        </div>

        <a href="home.php" class="back_button">Kembali</a>
    </div>
</body>

</html>