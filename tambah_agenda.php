<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Agenda</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-lg">
    <h2 class="text-2xl font-bold text-blue-600 mb-2 text-center">Tambah Agenda</h2>
    <p class="text-gray-500 mb-6 text-center">Silakan isi data agenda Anda</p>
    <form action="proses_tambah_agenda.php" method="POST" class="space-y-4">
      
      <div>
        <label class="block font-medium mb-1 text-gray-700">Nama Agenda</label>
        <input type="text" name="judul" required class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
      </div>

      <div class="flex gap-4">
        <div class="w-1/2">
          <label class="block font-medium mb-1 text-gray-700">Tanggal</label>
          <input type="date" name="tanggal" required class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
        </div>
        <div class="w-1/2">
          <label class="block font-medium mb-1 text-gray-700">Waktu</label>
          <input type="time" name="waktu" required class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1 text-gray-700">Lokasi</label>
        <input type="text" name="lokasi" required class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500">
      </div>

      <!-- <div>
        <label class="block font-medium mb-1 text-gray-700">Prioritas</label>
        <select name="prioritas" required class="w-full border rounded-lg px-4 py-2 bg-white focus:outline-blue-500 text-gray-700">
          <option value="">-- Pilih Prioritas --</option>
          <option value="Tak Penting" class="text-gray-500">Tak Penting</option>
          <option value="Penting" class="text-yellow-500">Penting</option>
          <option value="Sangat Penting" class="text-red-500">Sangat Penting</option>
        </select>
      </div> -->

      <div>
        <label class="block font-medium mb-1 text-gray-700">Keterangan</label>
        <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-4 py-2 focus:outline-blue-500" placeholder="Tuliskan detail tambahan (opsional)"></textarea>
      </div>


      <!-- Status default tidak ditampilkan, langsung diset di backend sebagai 'Belum selesai' -->

      <div class="text-center">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow inline-flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Simpan Agenda
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <a href="dashboard.php" class="text-blue-500 hover:underline">&larr; Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
