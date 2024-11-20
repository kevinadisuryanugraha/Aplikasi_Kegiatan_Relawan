<?php
// 1. Koneksi ke database mysql
$hostname = "localhost";
$username = "root";
$password = "";

$db = new mysqli($hostname, $username, $password);

// cek kondisi
if ($db->connect_error) {
    die("Koneksi gagal" . $db->connect_error);
} else {
    echo "Koneksi berhasil" . "<br>";
}

// 2. buat database jika belum ada
$sql_buat_db = "CREATE DATABASE IF NOT EXISTS db_kegiatan_relawan";
$eksekusi_buat_db = $db->query($sql_buat_db);

if ($eksekusi_buat_db) {
    echo "database 'db_kegiatan_relawan' berhasil dibuat atau sudah ada" . "<br>";
} else {
    die("Gagal membuat database: " . $db->error);
}

// 3. pilih database
$sql_masuk_db = "USE db_kegiatan_relawan";
$eksekusi_masuk_db = $db->query($sql_masuk_db);

if ($eksekusi_masuk_db) {
    echo "Berhasil masuk ke database db_kegiatan_relawan";
} else {
    die("Gagal masuk database: " . $db->error);
}

// 4. buat tabel 'users' jika belum ada
$sql_buat_tabel_users = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nomor_telepon VARCHAR(50)
)";

$eksekusi_buat_tabel_users = $db->query($sql_buat_tabel_users);

if ($eksekusi_buat_tabel_users) {
    echo "Berhasi membuat tabel user";
} else {
    die("Gagal Membuat tabel users: " . $db->error);
}

// 5. Buat tabel 'kegiatan' 
$sql_buat_tabel_kegiatan = "CREATE TABLE IF NOT EXISTS kegiatan (
    kegiatan_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_kegiatan VARCHAR(255) NOT NULL,
    tanggal_kegiatan DATE NOT NULL,
    lokasi_kegiatan VARCHAR(255),
    deskripsi TEXT,
    durasi_kegiatan INT,
    jumlah_relawan INT,
    dokumentasi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";

$eksekusi_buat_tabel_kegiatan = $db->query($sql_buat_tabel_kegiatan);

if ($eksekusi_buat_tabel_kegiatan) {
    echo "Berhasi membuat tabel kegiatan";
} else {
    die("Gagal Membuat tabel kegiatan: " . $db->error);
}
