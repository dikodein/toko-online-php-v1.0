<?php
require "../koneksi.php";
require "session.php";

// Ambil ID produk dari URL
$id = $_GET['id'] ?? '';
if (!$id) {
    echo "<script>alert('ID produk tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Ambil data produk dari database
$query_produk = $koneksi->prepare("SELECT * FROM produk WHERE id = ?");
$query_produk->bind_param("i", $id);
$query_produk->execute();
$result_produk = $query_produk->get_result();
$produk = $result_produk->fetch_assoc();

if (!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Ambil daftar kategori dari database
$query_kategori = $koneksi->query("SELECT id, nama FROM kategori");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $harga = trim($_POST['harga']);
    $stok = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    $detail = trim($_POST['detail']);

    if (empty($nama_produk) || empty($harga) || empty($stok) || empty($kategori_id) || empty($detail)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } else {
        $harga = str_replace('.', '', $harga);
        $foto_path = $produk['foto'];

        if (!empty($_FILES['foto']['name'])) {
            $foto_nama = $_FILES['foto']['name'];
            $foto_tmp = $_FILES['foto']['tmp_name'];
            $foto_size = $_FILES['foto']['size'];
            $foto_ext = strtolower(pathinfo($foto_nama, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($foto_ext, $allowed_ext) && $foto_size <= 2097152) {
                $foto_path = "uploads/" . time() . "_" . $foto_nama;
                move_uploaded_file($foto_tmp, "../" . $foto_path);
            } else {
                echo "<script>alert('Format gambar harus JPG, JPEG, PNG, atau GIF dan max 2MB!');</script>";
                exit;
            }
        }

        $query = $koneksi->prepare("UPDATE produk SET nama=?, harga=?, stok=?, kategori_id=?, foto=?, detail=? WHERE id=?");
        $query->bind_param("sisissi", $nama_produk, $harga, $stok, $kategori_id, $foto_path, $detail, $id);

        if ($query->execute()) {
            echo "<script>alert('Produk berhasil diperbarui!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui produk: " . $koneksi->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/\D/g, "");
            value = new Intl.NumberFormat("id-ID").format(value);
            input.value = value;
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Produk</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($produk['nama']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Harga (IDR)</label>
            <input type="text" name="harga" class="form-control" value="<?= number_format($produk['harga'], 0, ',', '.') ?>" oninput="formatRupiah(this)" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stok</label>
            <select name="stok" class="form-control" required>
                <option value="Tersedia" <?= $produk['stok'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="Habis" <?= $produk['stok'] == 'Habis' ? 'selected' : '' ?>>Habis</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <?php while ($kategori = $query_kategori->fetch_assoc()): ?>
                    <option value="<?= $kategori['id'] ?>" <?= $kategori['id'] == $produk['kategori_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kategori['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Detail Produk</label>
            <textarea name="detail" class="form-control" rows="3" required><?= htmlspecialchars($produk['detail']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Produk</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Max 2MB</small>
            <br>
            <?php if ($produk['foto']): ?>
                <img src="../<?= $produk['foto'] ?>" alt="Foto Produk" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>