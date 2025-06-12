<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil ID agenda dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cek apakah agenda milik user yang sedang login
$cek = $conn->prepare("SELECT * FROM agenda WHERE id = ? AND user_id = ?");
$cek->bind_param("ii", $id, $user_id);
$cek->execute();
$hasil = $cek->get_result();

if ($hasil->num_rows === 0) {
    // Data tidak ditemukan atau bukan milik user
    header("Location: agenda.php?pesan=gagal_hapus");
    exit;
}

// Lanjut hapus data
$hapus = $conn->prepare("DELETE FROM agenda WHERE id = ? AND user_id = ?");
$hapus->bind_param("ii", $id, $user_id);

if ($hapus->execute()) {
    header("Location: agenda.php?pesan=sukses_hapus");
} else {
    header("Location: agenda.php?pesan=gagal_hapus");
}
?>
