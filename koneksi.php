<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inventaris"; // ganti sesuai nama database kamu

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
