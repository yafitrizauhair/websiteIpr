<?php 
require "Backend/functions.php";
session_start();

$error = false;

if (isset($_POST["login"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    // Cek ke database
    $result = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row["password"])) {
            // Simpan session
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"] ?? 'admin'; // Default 'admin' jika kolom role tidak ada

            // Log berhasil
            log_event("Login berhasil", $row["username"]);

            header("Location: dashboard.php");
            exit;
        }
    }

    // Log gagal
    log_event("Login gagal", $username);

    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <link rel="stylesheet" href="login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="assets/logoNavbar.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <h1>Login Dashboard Admin</h1>
      <p>Silahkan masuk untuk melanjutkan</p>
    </div>

    <?php if ($error) : ?>
      <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        ⚠️ Username atau password salah.
      </div>
    <?php endif; ?>

    <form action="" method="post">
      <div class="form-group">
        <label for="username">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          class="form-control"
          placeholder="Masukkan username"
          required
        />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Masukkan password"
          required
        />
        <div class="password-info">
          Password harus merupakan kombinasi dari minimal 8 huruf, angka, dan simbol.
        </div>
      </div>

      <p><a href="reset_password.php">🔑 Lupa Password?</a></p>

      <button type="submit" class="btn-login" name="login">Masuk</button>
    </form>
  </div>
</body>
</html>
