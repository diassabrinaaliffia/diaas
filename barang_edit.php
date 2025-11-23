<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$data = $res->fetch_assoc()) {
    die("Barang tidak ditemukan.");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    // adjust 'tersedia' bila total jumlah berubah
    $selisih = $jumlah - $data['jumlah'];
    $tersedia = $data['tersedia'] + $selisih;
    if ($tersedia < 0) $tersedia = 0;

    $stmt = $mysqli->prepare("UPDATE barang SET nama=?, deskripsi=?, jumlah=?, tersedia=?, lokasi=?, kode=? WHERE id=?");
    $stmt->bind_param('ssiissi', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode, $id);
    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Gagal update: " . $mysqli->error;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Barang</title>
<style>
body { font-family: Arial, sans-serif; background: #e6f0ea; padding: 20px; }
.container { max-width: 500px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2f4f4f; margin-bottom: 25px; font-size: 24px; }
label { display:block; margin-top:15px; font-weight:600; color:#3b5240; }
input, textarea { width:100%; padding:12px; margin-top:6px; border:1px solid #b2d8c1; border-radius:8px; font-size:14px; transition:0.3s; }
input:focus, textarea:focus { border-color:#16a34a; outline:none; box-shadow:0 0 5px rgba(22,163,74,0.3); }
textarea { min-height:80px; resize:vertical; }
button { width:100%; padding:12px 0; font-size:16px; font-weight:bold; border:none; border-radius:8px; cursor:pointer; background:#16a34a; color:#fff; transition:0.3s; margin-top:25px; }
button:hover { background:#13833b; }
.error { color:#b91c1c; background:#fde2e2; padding:10px 15px; margin-bottom:15px; border-radius:6px; text-align:center; font-weight:600; }
a { display:block; margin-top:15px; text-align:center; color:#16a34a; text-decoration:none; font-weight:bold; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>
<div class="container">
<h2>Edit Barang</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post">
<label>Nama Barang</label>
<input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

<label>Kode</label>
<input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>">

<label>Deskripsi</label>
<textarea name="deskripsi"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

<label>Jumlah (total)</label>
<input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" min="0" required>

<label>Lokasi</label>
<input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>">

<button type="submit">Update</button>
</form>
<a href="index.php">Kembali</a>
</div>
</body>
</html>
