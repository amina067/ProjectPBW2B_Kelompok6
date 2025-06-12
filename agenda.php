<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include 'koneksi.php';
$user_id = $_SESSION['user_id'];

$hasil = $conn->query("SELECT * FROM agenda WHERE user_id = $user_id ORDER BY tanggal DESC");

if (!$hasil || !$hasil instanceof mysqli_result) {
    echo "<div style='color:red;'>Query error: " . $conn->error . "</div>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Agenda</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6">
      <h2 class="text-blue-600 font-bold text-xl mb-10"><i class="fa fa-calendar-check"></i> PlanMate</h2>
      <nav class="space-y-4">
        <a href="dashboard.php" class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
          <i class="fa fa-home"></i> <span>Tampilan</span>
        </a>
        <a href="agenda.php" class="flex items-center gap-2 text-blue-600 font-semibold">
          <i class="fa fa-calendar"></i> <span>Agenda</span>
        </a>
        <a href="logout.php" class="flex items-center gap-2 text-red-600 hover:underline">
          <i class="fa fa-sign-out-alt"></i> <span>Keluar</span>
        </a>
      </nav>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-8">
      <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-800"><i class="fa fa-folder-open"></i> Data Agenda</h3>
          <a href="tambah_agenda.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-1">
            <i class="fa fa-plus"></i> Tambah Agenda
          </a>
        </div>

        <div class="overflow-x-auto">
        <?php
          $pesan = $_GET['pesan'] ?? '';
          if ($pesan == 'sukses_tambah') {
              echo '<div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded"><p class="font-semibold"><i class="fa fa-check-circle"></i> Agenda berhasil ditambahkan.</p></div>';
          } elseif ($pesan == 'sukses_edit') {
              echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded"><p class="font-semibold"><i class="fa fa-edit"></i> Agenda berhasil diperbarui.</p></div>';
          } elseif ($pesan == 'sukses_hapus') {
              echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded"><p class="font-semibold"><i class="fa fa-trash-alt"></i> Agenda berhasil dihapus.</p></div>';
          } elseif ($pesan == 'gagal_tambah' || $pesan == 'gagal_edit' || $pesan == 'gagal_hapus') {
              echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded"><p class="font-semibold"><i class="fa fa-times-circle"></i> Terjadi kesalahan. Silakan coba lagi.</p></div>';
          }
        ?>
          <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-200 text-xs uppercase">
              <tr>
                <th class="px-4 py-2">Agenda</th>
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Waktu</th>
                <th class="px-4 py-2">Lokasi</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Keterangan</th>
                <th class="px-4 py-2">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($hasil->num_rows === 0): ?>
                <tr><td colspan="7" class="text-center py-4 text-gray-500">Belum ada agenda.</td></tr>
              <?php endif; ?>
             <?php while ($row = $hasil->fetch_assoc()) : ?>
              <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-2"><?= htmlspecialchars($row['judul']) ?></td>
                <td class="px-4 py-2"><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['waktu']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['lokasi']) ?></td>
                <td class="px-4 py-2">
                  <?php
                    $status = strtolower(trim($row['status']));
                    if ($status == 'selesai') {
                      echo '<span class="bg-green-200 text-green-700 px-2 py-1 rounded-full text-xs">Selesai</span>';
                    } elseif ($status == 'belum selesai' || $status == 'belum') {
                      echo '<span class="bg-red-200 text-red-700 px-2 py-1 rounded-full text-xs">Belum</span>';
                    } else {
                      echo '<span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs">-</span>';
                    }
                  ?>
                </td>
                <td class="px-4 py-2"><?= htmlspecialchars($row['keterangan']) ?></td>
                <td class="px-4 py-2">
                  <a href="edit.php?id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline mr-2"><i class="fa fa-edit"></i> Edit</a>
                  <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus agenda ini?');" class="text-red-600 hover:underline"><i class="fa fa-trash"></i> Hapus</a>
                </td>
              </tr>
             <?php endwhile; ?>

            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
