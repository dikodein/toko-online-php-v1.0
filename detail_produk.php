<?php
require 'koneksi.php'; // Pastikan ada koneksi ke database

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Produk tidak ditemukan.</div></div>");
}

$id = intval($_GET['id']);
$query = $koneksi->prepare("SELECT p.*, k.nama as kategori_nama FROM produk p INNER JOIN kategori k ON p.kategori_id = k.id WHERE p.id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    die("<div class='container mt-5'><div class='alert alert-danger'>Produk tidak ditemukan.</div></div>");
}

// Ambil data WhatsApp dari tabel produk
$wa_number = preg_replace('/[^0-9]/', '', $produk['whatsapp']);
$wa_message = urlencode("Halo, saya tertarik dengan produk \"{$produk['nama']}\" yang Anda jual. Bisa saya dapatkan informasi lebih lanjut?");
$wa_link = "https://wa.me/{$wa_number}?text={$wa_message}";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($produk['nama']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">Toko Online</a>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($produk['foto']); ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($produk['nama']); ?>">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <h3 class="card-title"> <?= htmlspecialchars($produk['nama']); ?> </h3>
                    <p class="text-muted"><span class="badge bg-primary"> <?= htmlspecialchars($produk['kategori_nama']); ?> </span></p>
                    <h4 class="text-primary">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></h4>
                    <p class="card-text mt-3"> <?= nl2br(htmlspecialchars($produk['detail'])); ?> </p>
                    <p class="card-text"><span class="badge bg-warning"><b style="color:gray;"><?= htmlspecialchars($produk['penjual_nama']); ?></b></span></p>
                    <a href="<?= $wa_link; ?>" class="btn btn-success" target="_blank">Hubungi Penjual via WhatsApp</a>
                    <a href="produk_search.php" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
