<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$judul      = $_POST['judul'] ?? '';
$tanggal    = $_POST['tanggal'] ?? '';
$waktu      = $_POST['waktu'] ?? '';
$lokasi     = $_POST['lokasi'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$status     = "belum"; // sesuaikan dengan enum di database

if (empty($judul) || empty($tanggal) || empty($waktu) || empty($lokasi)) {
    echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
    exit();
}

if (!is_numeric($user_id) || $user_id <= 0) {
    echo "<script>alert('User tidak valid!'); window.location.href='index.php';</script>";
    exit();
}

$stmt = $conn->prepare("INSERT INTO agenda (user_id, judul, tanggal, waktu, lokasi, keterangan, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("issssss", $user_id, $judul, $tanggal, $waktu, $lokasi, $keterangan, $status);

if ($stmt->execute()) {
    header("Location: agenda.php?pesan=sukses_tambah");
    exit();
} else {
    echo "<script>alert('Gagal menambahkan agenda: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

