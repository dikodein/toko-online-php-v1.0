<?php
require "../koneksi.php";
require "session.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        alert('ID kategori tidak valid!');
        window.location='index.php';
    </script>";
    exit();
}

$id = $_GET['id'];

// Ambil data kategori berdasarkan ID
$query = $koneksi->prepare("SELECT nama FROM kategori WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    echo "<script>
        alert('Kategori tidak ditemukan!');
        window.location='index.php';
    </script>";
    exit();
}

$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = trim($_POST['nama_kategori']);

    // Validasi input tidak boleh kosong
    if (empty($nama_kategori)) {
        echo "<script>alert('Nama kategori tidak boleh kosong!');</script>";
    } else {
        // Update kategori
        $update_query = $koneksi->prepare("UPDATE kategori SET nama = ? WHERE id = ?");
        $update_query->bind_param("si", $nama_kategori, $id);

        if ($update_query->execute()) {
            echo "<script>
                alert('Kategori berhasil diperbarui!');
                window.location='index.php';
            </script>";
        } else {
            echo "<script>alert('Gagal memperbarui kategori: " . $koneksi->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Kategori</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>
