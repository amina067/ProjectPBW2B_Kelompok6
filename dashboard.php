<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$koneksi = new mysqli("localhost", "root", "", "planmate");

$user_id = $_SESSION['user_id'];
$tanggal_hari_ini = date('Y-m-d');

// Ambil data user
$query_user = $koneksi->query("SELECT username FROM user WHERE id = $user_id");
$data_user = $query_user->fetch_assoc();
$username = $data_user['username'] ?? 'Pengguna';

// Ambil agenda HARI INI yang belum selesai untuk alarm
$agenda_hari_ini = $koneksi->query("SELECT * FROM agenda WHERE user_id = $user_id AND tanggal = '$tanggal_hari_ini' AND status = 'belum'");

// Ambil statistik agenda
$total = $koneksi->query("SELECT COUNT(*) as total FROM agenda WHERE user_id = $user_id")->fetch_assoc()['total'];
$selesai = $koneksi->query("SELECT COUNT(*) as selesai FROM agenda WHERE user_id = $user_id AND status = 'selesai'")->fetch_assoc()['selesai'];
$tertunda = $koneksi->query("SELECT COUNT(*) as tertunda FROM agenda WHERE user_id = $user_id AND status = 'belum'")->fetch_assoc()['tertunda'];

$agenda_mendatang = $koneksi->query("SELECT * FROM agenda WHERE user_id = $user_id AND tanggal > '$tanggal_hari_ini' ORDER BY tanggal ASC LIMIT 5");
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - PlanMate</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="flex">
    <aside class="w-64 bg-white shadow-md p-6 h-screen">
      <h2 class="text-blue-600 font-bold text-xl mb-10"><i class="fa fa-calendar-check"></i> PlanMate</h2>
      <nav class="space-y-4">
        <a href="dashboard.php" class="flex items-center gap-2 text-blue-600 font-semibold">
          <i class="fa fa-home"></i> <span>Tampilan</span>
        </a>
        <a href="agenda.php" class="flex items-center gap-2 text-gray-700 hover:text-blue-600">
          <i class="fa fa-calendar"></i> <span>Agenda</span>
        </a>
        <a href="logout.php" class="flex items-center gap-2 text-red-600 hover:underline">
          <i class="fa fa-sign-out-alt"></i> <span>Keluar</span>
        </a>
      </nav>
    </aside>

    <main class="flex-1 p-10">
      <h1 class="text-2xl font-bold mb-1">Selamat Datang, <?= htmlspecialchars($username) ?>!</h1>
      <p class="text-sm text-gray-500 mb-6"><?php echo date('d F Y'); ?></p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold text-gray-700 mb-2">Notifikasi Agenda Hari Ini</h3>
          <?php if ($agenda_hari_ini->num_rows > 0): ?>
            <ul class="list-disc pl-5 text-sm text-gray-800">
              <?php $agenda_hari_ini->data_seek(0); while ($a = $agenda_hari_ini->fetch_assoc()): ?>
                <li><?= htmlspecialchars($a['judul']) ?> - <?= htmlspecialchars($a['waktu']) ?></li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-blue-600 font-semibold">Tidak ada agenda hari ini</p>
          <?php endif; ?>
        </div>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold text-gray-700 mb-2">Ringkasan Agenda</h3>
          <div class="text-sm space-y-1">
            <p>Total Agenda: <strong><?= $total ?></strong></p>
            <p>Selesai: <strong><?= $selesai ?></strong></p>
            <p>Tertunda: <strong><?= $tertunda ?></strong></p>
          </div>
        </div>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold text-gray-700 mb-3">Agenda Mendatang</h3>
        <table class="w-full text-sm table-auto">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-3 py-2 text-left">AGENDA</th>
              <th class="px-3 py-2 text-left">TANGGAL</th>
              <th class="px-3 py-2 text-left">WAKTU</th>
              <th class="px-3 py-2 text-left">KETERANGAN</th>
            </tr>
          </thead>
          <tbody>
            <?php $agenda_mendatang->data_seek(0); while ($am = $agenda_mendatang->fetch_assoc()): ?>
              <tr class="border-t">
                <td class="px-3 py-2"><?= htmlspecialchars($am['judul']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($am['tanggal']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($am['waktu']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($am['keterangan']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modal Alarm -->
  <div id="alarmModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-md text-center">
      <h2 class="text-lg font-bold text-blue-700 mb-2">Pengingat Agenda!</h2>
      <p id="alarmText" class="text-gray-800 mb-4" data-judul="" data-waktu="">Agenda sedang berlangsung.</p>
      <button onclick="tutupModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tutup</button>
    </div>
  </div>
 
  <script>
    const agendaHariIni = <?php
      $agenda_array = [];
      $agenda_hari_ini->data_seek(0);
      while ($a = $agenda_hari_ini->fetch_assoc()) {
        $agenda_array[] = [
          'judul' => $a['judul'],
          'waktu' => $a['waktu'],
          'status' => $a['status']
        ];
      }
      echo json_encode($agenda_array);
    ?>;

    const now = new Date();
    const nowMinutes = now.getHours() * 60 + now.getMinutes();
    let alarmSudahMuncul = false;

    agendaHariIni.forEach(agenda => {
      const [jam, menit] = agenda.waktu.split(":").map(Number);
      const agendaMinutes = jam * 60 + menit;

      if (!alarmSudahMuncul && agenda.status.toLowerCase() === 'belum' && agendaMinutes >= nowMinutes && agendaMinutes <= nowMinutes + 5) {
        const alarmText = document.getElementById("alarmText");
        alarmText.innerText = `⏰ Agenda "${agenda.judul}" dimulai pada ${agenda.waktu}`;
        alarmText.setAttribute("data-judul", agenda.judul);
        alarmText.setAttribute("data-waktu", agenda.waktu);

        document.getElementById("alarmModal").classList.remove("hidden");
        document.getElementById("alarmModal").classList.add("flex");

        alarmSudahMuncul = true;
      }
    });

    function tutupModal() {
      document.getElementById("alarmModal").classList.add("hidden");

      // Kirim request ke PHP untuk ubah status jadi 'selesai'
      fetch("update_status_agenda.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          judul: document.getElementById("alarmText").dataset.judul,
          waktu: document.getElementById("alarmText").dataset.waktu
        })
      }).then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            location.reload();
          }
        });
    }
  </script>
</body>
</html>
