<?php 
require "Backend/functions.php";
session_start();

// Cek login
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

// Inisialisasi variabel
$search = '';
$registrations = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    if (!empty($search)) {
        $registrations = searchRegistrations($search);
    }
}

// Jika belum dilakukan pencarian atau pencarian kosong, ambil semua data
if (empty($registrations)) {
    $registrations = query("SELECT * FROM registrations");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/x-icon" href="assets/logoNavbar.png">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        .navbar {
            background: linear-gradient(to right, #007bff, #0056b3);
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-logo {
            font-size: 20px;
            font-weight: bold;
        }

        .logout-btn {
            background: #ff4d4f;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #e03131;
        }

        .container {
            padding: 30px;
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h1::before {
            content: "👥";
            font-size: 28px;
        }

        .btn-add-admin {
            background: linear-gradient(to right, #28a745, #218838);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s ease;
        }

        .btn-add-admin:hover {
            background: linear-gradient(to right, #218838, #1e7e34);
            transform: scale(1.05);
        }

        .search-container {
            margin: 25px 0;
        }

        .search-container input {
            padding: 10px;
            width: 300px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-btn, .reset-btn {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            margin-left: 5px;
            cursor: pointer;
        }

        .search-btn {
            background: #007bff;
            color: white;
        }

        .reset-btn {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }

        .search-results {
            margin-bottom: 10px;
            font-style: italic;
            color: #555;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 13px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .btn-primary {
            background: #17a2b8;
            color: white;
        }

        .btn-primary:hover {
            background: #138496;
        }

        .btn-edit {
            background: #ffc107;
            color: black;
        }

        .btn-edit:hover {
            background: #e0a800;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #bd2130;
        }

        .no-results {
            padding: 20px;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h2 class="navbar-logo">📊 IPR Dashboard Admin</h2>
        <?php if (isset($_SESSION["id"])) : ?>
            <a href="logout.php" class="logout-btn">🔓 Logout</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <div class="header">
            <h1>Manajemen Pengguna</h1>
            <a href="tambah_admin.php" class="btn-add-admin">➕ Tambah Admin</a>
        </div>

        <!-- Search Bar -->
        <div class="search-container">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari nama, organisasi, atau email..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="search-btn">🔍 Cari</button>
                <?php if (!empty($search)): ?>
                    <a href="dashboard.php" class="reset-btn">🔄 Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!empty($search)): ?>
            <div class="search-results">
                Menampilkan hasil untuk: "<strong><?= htmlspecialchars($search) ?></strong>" (<?= count($registrations) ?> data ditemukan)
            </div>
        <?php endif; ?>

        <!-- Table Registrations -->
        <div class="table-container">
            <?php if (count($registrations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Organisasi/Perusahaan</th>
                            <th>Nama Lengkap</th>
                            <th>Penjelasan Badan Usaha</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                        <tr>
                            <td><?= htmlspecialchars($registration["nama_organisasi"]) ?></td>
                            <td><?= htmlspecialchars($registration["nama_lengkap"]) ?></td>
                            <td><?= htmlspecialchars($registration["penjelasan_bahan_limbah"]) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-primary" onclick="openModal(<?= $registration['id'] ?>)">👁 Lihat</button>
                                    <a href="edit.php?id=<?= $registration['id'] ?>" class="btn btn-edit">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $registration['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑 Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-results">
                    Tidak ada data ditemukan sesuai pencarian Anda.
                </div>
            <?php endif; ?>
        </div>
    </div>

     
</body>
</html>
