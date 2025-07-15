<?php
require "Backend/functions.php";
session_start();

$message = "";
$step = 1;

// STEP 1: Cek Username
if (isset($_POST['check_user'])) {
    $username = trim($_POST['username']);

    $stmt = $conn->prepare("SELECT security_question FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
        $_SESSION['reset_username'] = $username;
        $_SESSION['security_question'] = $data['security_question'];
        $step = 2;
    } else {
        $message = "❌ Username tidak ditemukan.";
    }
    $stmt->close();
}

// STEP 2: Verifikasi Jawaban
if (isset($_POST['verify_answer'])) {
    $answer = trim($_POST['answer']);
    $username = $_SESSION['reset_username'] ?? '';

    $stmt = $conn->prepare("SELECT security_answer FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        if (password_verify($answer, $data['security_answer'])) {
            $_SESSION['verified'] = true;
            $step = 3;
        } else {
            $message = "❌ Jawaban keamanan salah.";
            session_unset();
            $step = 1;
        }
    } else {
        $message = "❌ Gagal memverifikasi.";
        session_unset();
        $step = 1;
    }
    $stmt->close();
}

// STEP 3: Reset Password
if (isset($_POST['reset_password'])) {
    if (!isset($_SESSION['verified']) || !isset($_SESSION['reset_username'])) {
        $message = "⛔ Akses tidak sah. Ulangi proses.";
        $step = 1;
    } else {
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (
            strlen($new_password) < 8 ||
            !preg_match('/[A-Z]/', $new_password) ||
            !preg_match('/[\W]/', $new_password)
        ) {
            $message = "❌ Password minimal 8 karakter, ada huruf besar dan simbol.";
            $step = 3;
        } elseif ($new_password !== $confirm_password) {
            $message = "❌ Konfirmasi password tidak cocok.";
            $step = 3;
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $username = $_SESSION['reset_username'];

            $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed, $username);

            if ($stmt->execute()) {
                $message = "✅ Password berhasil di-reset. Silakan login kembali.";
                if (function_exists('log_event')) {
                    log_event("Reset password berhasil", $username);
                }
            } else {
                $message = "❌ Gagal reset password.";
            }

            $stmt->close();
            session_destroy();
            $step = 4;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Admin</title>
    <link rel="icon" href="assets/logoNavbar.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
        }

        small {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 10px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🔐 Reset Password Admin</h2>

    <?php if ($message): ?>
        <div class="message <?= str_starts_with($message, '✅') ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if ($step == 1): ?>
        <form method="POST">
            <label>Masukkan Username:</label>
            <input type="text" name="username" placeholder="Contoh: admin01" required>
            <button name="check_user">Lanjutkan</button>
        </form>

    <?php elseif ($step == 2): ?>
        <form method="POST">
            <label>Pertanyaan Keamanan:</label>
            <p><strong><?= htmlspecialchars($_SESSION['security_question']) ?></strong></p>
            <input type="text" name="answer" placeholder="Jawaban Anda" required>
            <button name="verify_answer">Verifikasi</button>
        </form>

    <?php elseif ($step == 3): ?>
        <form method="POST">
            <label>Password Baru:</label>
            <input type="password" name="new_password" placeholder="Password Baru" required>

            <label>Ulangi Password Baru:</label>
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>

            <small>💡 Password minimal 8 karakter, gunakan huruf besar dan simbol.</small>
            <button name="reset_password">Reset Password</button>
        </form>

    <?php elseif ($step == 4): ?>
        <p><a href="login.php">← Kembali ke Login</a></p>
    <?php endif; ?>
</div>
</body>
</html>
