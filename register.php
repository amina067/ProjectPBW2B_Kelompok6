<?php
session_start();
include 'koneksi.php';

$error = "";
$sukses = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email    = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($username) || empty($password)) {
        $error = "Username dan Password tidak boleh kosong.";
    } elseif (strlen($username) < 4) {
        $error = "Username minimal 4 karakter.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username atau email sudah digunakan.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO user (username, password, email) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $hashed, $email);

            if ($insert->execute()) {
                $sukses = "Registrasi berhasil. Mengarahkan ke halaman login...";
                echo '<meta http-equiv="refresh" content="2;url=index.php">';
                $_POST = [];
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar - PlanMate</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white rounded-2xl shadow-lg w-full max-w-sm">
    <div class="bg-blue-600 text-white text-center py-6 rounded-t-2xl">
      <h2 class="text-xl font-bold">PlanMate</h2>
      <p class="text-sm mt-1">Silakan daftar untuk melanjutkan</p>
    </div>

    <form method="POST" class="p-6 space-y-5">
      <h3 class="text-center text-lg font-semibold text-gray-700">Registrasi Akun Baru</h3>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-2 text-sm rounded"><?= $error ?></div>
      <?php elseif ($sukses): ?>
        <div class="bg-green-100 text-green-700 p-2 text-sm rounded"><?= $sukses ?></div>
      <?php endif; ?>

      <div>
        <label class="block mb-1 text-sm font-medium">Username</label>
        <div class="flex items-center border rounded-md px-3 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 007 21h10a4 4 0 001.879-3.196M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required class="w-full outline-none" />
        </div>
      </div>

      <div>
        <label class="block mb-1 text-sm font-medium">Email</label>
        <div class="flex items-center border rounded-md px-3 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0l-4-4m4 4l-4 4" />
          </svg>
          <input type="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required class="w-full outline-none" />
        </div>
      </div>


      <div>
        <label class="block mb-1 text-sm font-medium">Password</label>
        <div class="flex items-center border rounded-md px-3 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104.896-2 2-2s2 .896 2 2m0 0c0 1.104-.896 2-2 2s-2-.896-2-2zm4 4v2a2 2 0 01-2 2H10a2 2 0 01-2-2v-2a4 4 0 118 0z" />
          </svg>
          <input type="password" name="password" required class="w-full outline-none" />
        </div>
      </div>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
        </svg>
        Daftar
      </button>

      <p class="text-center text-sm text-gray-600">
        <a href="index.php" class="text-blue-600 font-medium hover:underline">&larr; Kembali ke Login</a>
      </p>
    </form>
  </div>

</body>
</html>
