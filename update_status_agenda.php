<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['judul']) || !isset($input['waktu'])) {
    echo json_encode(['status' => 'invalid']);
    exit();
}

$user_id = $_SESSION['user_id'];
$judul = $input['judul'];
$waktu = $input['waktu'];

$koneksi = new mysqli("localhost", "root", "", "planmate");
if ($koneksi->connect_error) {
    echo json_encode(['status' => 'error']);
    exit();
}

// Update agenda yang sesuai dengan judul, waktu, dan user_id
$stmt = $koneksi->prepare("UPDATE agenda SET status = 'selesai' WHERE user_id = ? AND judul = ? AND waktu = ?");
$stmt->bind_param("iss", $user_id, $judul, $waktu);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'not_found']);
}

$stmt->close();
$koneksi->close();
