<?php
// koneksi.php

$host     = "localhost";
$user     = "root";
$password = "";
$database = "planmate";

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    // Untuk pengembangan, bisa tampilkan error. Untuk produksi, sebaiknya log saja.
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
