<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (isset($_GET['kegiatan_id'])) {
    $kegiatan = ambil_kegiatan_by_id($_GET['kegiatan_id']);
    hapus_kegiatan($_GET['kegiatan_id']);
}

header("Location: home.php");
exit();
