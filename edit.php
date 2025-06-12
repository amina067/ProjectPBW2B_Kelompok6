<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil ID agenda dari parameter
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: agenda.php?pesan=gagal_edit");
    exit;
}

// Ambil data agenda yang akan diedit
$query = "SELECT * FROM agenda WHERE id = $id AND user_id = $user_id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: agenda.php?pesan=gagal_edit");
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul      = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $waktu      = mysqli_real_escape_string($conn, $_POST['waktu']);
    $lokasi     = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $update = "UPDATE agenda 
               SET judul='$judul', tanggal='$tanggal', waktu='$waktu', lokasi='$lokasi', status='$status', keterangan='$keterangan'
               WHERE id=$id AND user_id=$user_id";

    if (mysqli_query($conn, $update)) {
        header("Location: agenda.php?pesan=sukses_edit");
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui agenda');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Edit Agenda</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg">
    <h2 class="text-2xl font-bold text-blue-600 mb-2 text-center">Edit Agenda</h2>
    <p class="text-gray-500 mb-6 text-center">Perbarui data agenda Anda</p>

    <form method="POST" class="space-y-4">

      <div>
        <label class="block font-medium mb-1 text-gray-700">Nama Agenda</label>
        <input type="text" name="judul" required value="<?= htmlspecialchars($data['judul']) ?>" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
      </div>

      <div class="flex gap-4">
        <div class="w-1/2">
          <label class="block font-medium mb-1 text-gray-700">Tanggal</label>
          <input type="date" name="tanggal" required value="<?= $data['tanggal'] ?>" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
        </div>
        <div class="w-1/2">
          <label class="block font-medium mb-1 text-gray-700">Waktu</label>
          <input type="time" name="waktu" required value="<?= $data['waktu'] ?>" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1 text-gray-700">Lokasi</label>
        <input type="text" name="lokasi" required value="<?= htmlspecialchars($data['lokasi']) ?>" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1 text-gray-700">Status</label>
        <select name="status" required class="w-full border rounded-lg px-4 py-2 bg-white focus:outline-blue-500 text-gray-700">
          <option value="belum" <?= $data['status'] == 'belum' ? 'selected' : '' ?>>Belum Selesai</option>
          <option value="selesai" <?= $data['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
      </div>

      <div>
        <label class="block font-medium mb-1 text-gray-700">Keterangan</label>
        <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500"><?= htmlspecialchars($data['keterangan']) ?></textarea>
      </div>

      <div class="text-center">
        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg shadow inline-flex items-center gap-2">
          <i class="fa fa-save"></i> Simpan Perubahan
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <a href="agenda.php" class="text-blue-500 hover:underline">&larr; Kembali ke Agenda</a>
    </div>
  </div>
</body>
</html>
