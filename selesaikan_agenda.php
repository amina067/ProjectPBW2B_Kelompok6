<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$judul = $_POST['judul'] ?? '';

if (empty($judul)) {
    http_response_code(400); // Bad Request
    exit('Judul agenda tidak boleh kosong');
}

$stmt = $conn->prepare("UPDATE agenda SET status = 'selesai' WHERE user_id = ? AND judul = ?");
$stmt->bind_param("is", $user_id, $judul);

if ($stmt->execute()) {
    http_response_code(200);
    echo 'Agenda berhasil diselesaikan';
} else {
    http_response_code(500);
    echo 'Gagal memperbarui status agenda';
}

$stmt->close();
$conn->close();
?>
