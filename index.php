<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

include 'koneksi.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 3 || strlen($password) < 3) {
        $error = "Username dan password minimal 3 karakter!";
    } else {
        // Gunakan prepared statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
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
  <title>Login - PlanMate</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="bg-white rounded-2xl shadow-lg w-full max-w-sm">
    <div class="bg-blue-600 text-white text-center py-6 rounded-t-2xl">
      <h2 class="text-xl font-bold">PLANMATE</h2>
      <p class="text-sm mt-1">Silakan login untuk melanjutkan</p>
    </div>
  <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'logout'): ?>
    <div class="bg-green-100 text-green-700 p-2 text-sm rounded">
      Kamu telah berhasil logout.
    </div>
  <?php endif; ?>

    <form action="" method="POST" class="p-6 space-y-5">
      <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <div>
        <label class="block mb-1 text-sm font-medium">Username</label>
        <div class="flex items-center border rounded-md px-3 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 007 21h10a4 4 0 001.879-3.196M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <input type="text" name="username" required class="w-full outline-none" />
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
        Login
      </button>

      <p class="text-center text-sm text-gray-600">
        Belum punya akun? <a href="register.php" class="text-blue-600 font-medium hover:underline">Daftar disini</a>
      </p>
    </form>
  </div>

</body>
</html>
