<?php
require "../koneksi.php";
require "session.php";

// Ambil data dari database
$kategori_query = $koneksi->query("SELECT * FROM kategori");
$produk_query = $koneksi->query("SELECT produk.*, kategori.nama as kategori_nama FROM produk JOIN kategori ON produk.kategori_id = kategori.id");
$users_query = $koneksi->query("SELECT id, username, email, nomor_telepon FROM users");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #000428, #004e92);
            color: #fff;
            min-height: 100vh;
            padding-top: 50px;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-logout {
            background: #dc3545;
            border: none;
        }
        .btn-logout:hover {
            background: #b02a37;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🔹 Halo, <?= htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
    <p>Selamat datang di panel admin.</p>
    <a href="logout.php" class="btn btn-danger btn-logout">Logout</a>
    <hr>

    <h3>📂 Data Users <a href="tambah_user.php" class="btn btn-primary">Tambah</a></h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Nomor Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users_query->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id']; ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['nomor_telepon']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus_user.php?id=<?= $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus user ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>📂 Data Kategori <a href="tambah_kategori.php" class="btn btn-primary">Tambah</a></h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($kategori = $kategori_query->fetch_assoc()): ?>
                <tr>
                    <td><?= $kategori['id']; ?></td>
                    <td><?= htmlspecialchars($kategori['nama']); ?></td>
                    <td>
                        <a href="edit_kategori.php?id=<?= $kategori['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus_kategori.php?id=<?= $kategori['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus kategori ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>📂 Data Produk <a href="tambah_produk.php" class="btn btn-primary">Tambah</a></h3>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Foto</th>
                <th>Detail</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($produk = $produk_query->fetch_assoc()): ?>
                <tr>
                    <td><?= $produk['id']; ?></td>
                    <td><?= htmlspecialchars($produk['kategori_nama']); ?></td>
                    <td><?= htmlspecialchars($produk['nama']); ?></td>
                    <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                    <td><img src="../<?= htmlspecialchars($produk['foto']); ?>" width="50" alt="Foto Produk"></td>
                    <td><?= htmlspecialchars($produk['detail']); ?></td>
                    <td><?= $produk['stok']; ?></td>
                    <td>
                        <a href="edit_produk.php?id=<?= $produk['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus_produk.php?id=<?= $produk['id']; ?>" class="btn btn-danger" onclick="return confirm('Hapus produk ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>