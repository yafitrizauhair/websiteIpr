<?php
  require "Backend/functions.php";
  session_start();

  if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
  }

  if( isset($_POST['tambah_admin'])){
    if(tambahAdmin($_POST) > 0){
      echo "
        <script>
          alert('Admin baru telah ditambahkan');
          document.location.href = 'dashboard.php';
        </script>
      ";
    } else {
      mysqli_error($conn);
    }
  }

?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
      <link rel="icon" type="image/x-icon" href="assets/logoNavbar.png">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 via-white to-purple-100 min-h-screen flex items-center justify-center font-sans">
  <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">🧑‍💻 Tambah Admin Baru</h2>

    <?php if (!empty($message)): ?>
      <p class="mb-4 text-sm text-center <?= str_contains($message, '✅') ? 'text-green-600' : 'text-red-600' ?>">
        <?= $message ?>
      </p>
    <?php endif; ?>

    <form method="post" action="" class="space-y-4">
      <div>
        <label class="block mb-1 text-gray-700" for="usernameadmin">Username</label>
        <input type="text" name="usernameadmin" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <div>
        <label class="block mb-1 text-gray-700">Password</label>
        <input type="password" name="passwordadmin" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <div>
        <label class="block mb-1 text-gray-700" for="question">Pertanyaan Keamanan</label>
        <select name="question" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400">
          <option value="">-- Pilih Pertanyaan --</option>
          <option value="Siapa nama ibu kandungmu?">Siapa nama ibu kandungmu?</option>
          <option value="Di kota mana kamu lahir?">Di kota mana kamu lahir?</option>
          <option value="Siapa nama hewan peliharaan pertamamu?">Siapa nama hewan peliharaan pertamamu?</option>
          <option value="Berapa umurmu saat membuat akun ini?">Berapa umurmu saat membuat akun ini?</option>
        </select>
      </div>

      <div>
        <label class="block mb-1 text-gray-700" for="answer">Jawaban Keamanan</label>
        <input type="text" name="answer" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit" name="tambah_admin" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
        Tambah Admin
      </button>
    </form>

    <div class="mt-6 text-center">
      <a href="dashboard.php" class="text-blue-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
