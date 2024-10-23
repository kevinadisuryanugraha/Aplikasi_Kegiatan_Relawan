<?php
require_once "config.php";

// Fungsi untuk menambahkan pengguna baru

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

// Fungsi untuk mendapatkan pengguna berdasarkan email

function ambil_user_by_email($email)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}



// Fungsi untuk menambahkan kegiatan baru

function tambah_kegiatan($user_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi)
{
    global $db;

    if (!isset($dokumentasi) || $dokumentasi['error'] != UPLOAD_ERR_OK) {
        echo "Tidak ada file yang di upload atau terjadi kesalahan saat upload.";
        return false;
    }

    // penanganan upload gambar
    $target_dir = "uploads/kegiatan/";
    $nama_file = basename($dokumentasi["name"]);
    $target_file = $target_dir . $nama_file;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // cek apakah file adalah gambar
    $check = getimagesize($dokumentasi["tmp_name"]);
    if ($check === false) {
        echo "File yang diupload bukan gambar";
        return false;
    }

    // validasi format file
    if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
        echo "Hanya diperbolehkan file jpg, jpeg, dan png.";
        return false;
    }

    // validasi ukuran file
    if ($dokumentasi["size"] > 2000000) {
        echo "Ukuran file terlalu besar. Maksimum 2MB";
        return false;
    }

    // upload file gambar
    if (move_uploaded_file($dokumentasi["tmp_name"], $target_file)) {
        // query untuk menambah file baru
        $stmt = $db->prepare("INSERT INTO kegiatan (user_id, nama_kegiatan, tanggal_kegiatan, lokasi_kegiatan, deskripsi, durasi_kegiatan, jumlah_relawan, dokumentasi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssis", $user_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $nama_file);
        return $stmt->execute();
    } else {
        echo "Terjadi Kesalahan saat mengupload gambar. ";
        return false;
    }
}



// Fungsi untuk mengupdate agenda

function update_kegiatan($kegiatan_id, $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi = null)
{
    global $db;

    // jika dokumentasi adalah array (file baru diupload), lakukan upload
    if (is_array($dokumentasi)) {
        $target_dir = "uploads/kegiatan/";
        $nama_file = basename($dokumentasi["name"]);
        $target_file = $target_dir . $nama_file;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // cek apakah file adalah gambar
        $check = getimagesize($dokumentasi["tmp_name"]);
        if ($check === false) {
            echo "File yang diupload bukan gambar";
            return false;
        }

        // validasi format file
        if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png") {
            echo "hanya file jpg, jpeg, dan png yang diperbolehkan";
            return false;
        }

        // Validasi ukuran file
        if ($dokumentasi["size"] > 2000000) {
            echo "Ukuran file terlalu besar. Maksimal 2MB.";
            return false;
        }

        // Upload file gambar
        if (!move_uploaded_file($dokumentasi["tmp_name"], $target_file)) {
            echo "Terjadi kesalahan saat mengupload gambar.";
            return false;
        }

        // Gunakan file_name untuk update gambar baru
        $dokumentasi = $nama_file;
    }


    // Query dasar untuk update
    $query = "UPDATE kegiatan SET nama_kegiatan = ?, tanggal_kegiatan = ?, lokasi_kegiatan = ?, deskripsi = ?, durasi_kegiatan = ?, jumlah_relawan = ?";

    // Jika ada dokumentasi, tambahkan query
    if ($dokumentasi) {
        $query .= ", dokumentasi = ?";
    }

    $query .= " WHERE kegiatan_id = ?";

    $stmt = $db->prepare($query);

    // Bind parameter dengan benar
    if ($dokumentasi) {
        $stmt->bind_param("sssssssi", $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $dokumentasi, $kegiatan_id);
    } else {
        $stmt->bind_param("ssssssi", $nama_kegiatan, $tanggal_kegiatan, $lokasi_kegiatan, $deskripsi, $durasi_kegiatan, $jumlah_relawan, $kegiatan_id);
    }

    return $stmt->execute();
}



// Fungsi untuk mendapatkan semua kegiatan
function ambil_semua_kegiatan()
{
    global $db;
    $result = $db->query("SELECT * FROM kegiatan");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk mendapatkan kegiatan berdasarkan ID
function ambil_kegiatan_by_id($kegiatan_id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM kegiatan WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $kegiatan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : false;
}


// Fungsi untuk menghapus kegiatan
function hapus_kegiatan($kegiatan_id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM kegiatan WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $kegiatan_id);
    return $stmt->execute();
}
