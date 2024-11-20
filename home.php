<?php
require_once "functions.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$kegiatan_list = ambil_semua_kegiatan();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Kegiatan Relawan</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <div class="container">
        <div class="jumbotron">
            <h1>Daftar Kegiatan Relawan</h1>
            <p>Setiap langkah kecil yang Anda ambil sebagai sukarelawan adalah senyum baru bagi mereka yang membutuhkan. Ambil bagian dalam kegiatan sosial kami dan rasakan kebersamaan, kepedulian, dan kebahagiaan yang tak ternilai harganya.</p>
            <p>Mari Menjadi Bagian Dari Mereka</p>
        </div>
        <a href="tambah_kegiatan.php" class="btn">Tambah Kegiatan Baru</a>
        <a href="logout.php" class="btn btn-logout">Logout</a>

        <div class="card-container">
            <?php if (count($kegiatan_list) > 0): ?>
                <?php foreach ($kegiatan_list as $kegiatan): ?>
                    <div class="card">
                        <img src="uploads/kegiatan/<?php echo $kegiatan['dokumentasi']; ?>" alt="Gambar Kegiatan">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo $kegiatan['nama_kegiatan']; ?></h2>
                            <p class="card-text"><?php echo htmlspecialchars($kegiatan['deskripsi']); ?></p>
                            <p class="card-text">Tanggal: <?php echo date('d-m-Y', strtotime($kegiatan['tanggal_kegiatan'])); ?></p>
                            <p class="card-text">Lokasi: <?php echo htmlspecialchars($kegiatan['lokasi_kegiatan']); ?></p>
                        </div>
                        <div class="btn-container">
                            <a href="detail_kegiatan.php?kegiatan_id=<?php echo $kegiatan['kegiatan_id']; ?>" class="btn btn-detail">Detail</a>

                            <?php if ($kegiatan['user_id'] == $user_id): ?>
                                <a href="edit_kegiatan.php?kegiatan_id=<?php echo $kegiatan['kegiatan_id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="hapus_kegiatan.php?kegiatan_id=<?php echo $kegiatan['kegiatan_id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">Hapus</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Belum ada kegiatan yang terdaftar.</p>
            <?php endif; ?>
        </div>

        <footer style="background-color: #007bff; color: white; padding: 20px; text-align: center; margin-top: 50px;">
            <div class="footer-content">
                <p style="margin: 0;">© 2024 Kegiatan Relawan. All Rights Reserved.</p>
            </div>
        </footer>
    </div>

</body>

</html>