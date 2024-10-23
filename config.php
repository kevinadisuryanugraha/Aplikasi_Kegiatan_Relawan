<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_kegiatan_relawan";

$db = new mysqli($hostname, $username, $password, $database);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
} else {
    // echo "koneksi berhasil . "<br>";
}
