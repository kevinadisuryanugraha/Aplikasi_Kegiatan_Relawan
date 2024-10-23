<?php
session_start();

session_destroy();

//Alihkan Pengguna kembali ke halaman login (index.php)
header("Location: index.php");
exit();
