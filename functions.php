<?php
require_once "config.php";

function tambah_user($username, $email, $nomor_telepon)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO users (username, email, nomor_telepon) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $email, $nomor_telepon);
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        return false;
    }
}

function ambil_user_by_email($email)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}

function tambah_kegiatan($user_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi)
{
    global $db;

    if (!isset($dokumentasi) || $dokumentasi['error'] != UPLOAD_ERR_OK) {
        echo "Tidak ada file yang di upload atau terjadi kesalahan saat upload.";
        return false;
    }

    $target_dir = "uploads/kegiatan/";
    $nama_file = basename($dokumentasi["name"]);
    $target_file = $target_dir . $nama_file;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($dokumentasi["tmp_name"]);
    if ($check === false) {
        echo "File yang diupload bukan gambar";
        return false;
    }

    if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
        echo "Hanya diperbolehkan file jpg, jpeg, dan png.";
        return false;
    }

    if ($dokumentasi["size"] > 2000000) {
        echo "Ukuran file terlalu besar. Maksimum 2MB";
        return false;
    }

    if (move_uploaded_file($dokumentasi["tmp_name"], $target_file)) {
        $stmt = $db->prepare("INSERT INTO kegiatan (user_id, nama_kegiatan, tanggal_kegiatan, lokasi_kegiatan, deskripsi, durasi_kegiatan, jumlah_relawan, dokumentasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssis", $user_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $nama_file);
        return $stmt->execute();
    } else {
        echo "Terjadi Kesalahan saat mengupload gambar. ";
        return false;
    }
}

function update_kegiatan($kegiatan_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi = null)
{
    global $db;

    if (is_array($dokumentasi) && isset($dokumentasi['tmp_name']) && !empty($dokumentasi['tmp_name'])) {
        $target_dir = "uploads/kegiatan/";
        $nama_file = basename($dokumentasi["name"]);
        $target_file = $target_dir . $nama_file;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($dokumentasi["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan gambar";
            return false;
        }

        if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
            echo "hanya file jpg, jpeg, dan png yang diperbolehkan";
            return false;
        }

        if ($dokumentasi["size"] > 2000000) {
            echo "Ukuran file terlalu besar. Maksimal 2MB.";
            return false;
        }

        if (!move_uploaded_file($dokumentasi["tmp_name"], $target_file)) {
            echo "Terjadi kesalahan saat mengupload gambar.";
            return false;
        }

        $dokumentasi = $nama_file;
    } else {
        // Tidak ada file yang diunggah, gunakan foto lama
        $query_foto = "SELECT dokumentasi FROM kegiatan WHERE kegiatan_id = ?";
        $stmt_foto = $db->prepare($query_foto);
        $stmt_foto->bind_param("i", $kegiatan_id);
        $stmt_foto->execute();
        $result_foto = $stmt_foto->get_result();
        if ($row = $result_foto->fetch_assoc()) {
            $dokumentasi = $row['dokumentasi'];
        } else {
            echo "Data kegiatan tidak ditemukan.";
            return false;
        }
    }

    $query = "UPDATE kegiatan SET nama_kegiatan = ?, tanggal_kegiatan = ?, lokasi_kegiatan = ?, deskripsi = ?, durasi_kegiatan = ?, jumlah_relawan = ?";

    if ($dokumentasi) {
        $query .= ", dokumentasi = ?";
    }

    $query .= " WHERE kegiatan_id = ?";

    $stmt = $db->prepare($query);

    if ($dokumentasi) {
        $stmt->bind_param("sssssssi", $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi, $kegiatan_id);
    } else {
        $stmt->bind_param("ssssssi", $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $kegiatan_id);
    }

    return $stmt->execute();
}

function ambil_semua_kegiatan()
{
    global $db;
    $result = $db->query("SELECT * FROM kegiatan");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function ambil_kegiatan_by_id($kegiatan_id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM kegiatan WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $kegiatan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}

function hapus_kegiatan($kegiatan_id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM kegiatan WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $kegiatan_id);
    return $stmt->execute();
}
