<?php 

require "Backend/functions.php";
if( isset($_POST["register"]) ){
    if(register($_POST) > 0){
        echo "
            <script>
                alert('Akun telah dibuat');
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Page</title>
    <link rel="stylesheet" href="login.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      rel="stylesheet"
    />
  </head>
  <body>
    <div class="login-container">
      <div class="login-header">
        <h1>Selamat Datang Kembali</h1>
        <p>Silahkan masuk untuk melanjutkan</p>
      </div>

      <form action="" method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            placeholder="Placeholder"
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            placeholder="Placeholder"
          />
          <div class="password-info">
            Password harus merupakan kombinasi dari minimal 8 huruf, angka, dan
            simbol.
          </div>
        </div>

        <div class="form-group">
          <label for="confirm-password">Konfirmasi Password</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            class="form-control"
            placeholder="Placeholder"
          />
          <div class="password-info">
            Password harus merupakan kombinasi dari minimal 8 huruf, angka, dan
            simbol.
          </div>
        </div>

        <button type="submit" name="register" class="btn-login">Masuk</button>
      </form>
    </div>
  </body>
</html>