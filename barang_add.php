<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $jumlah = (int)$_POST['jumlah'];
    $lokasi = trim($_POST['lokasi']);
    $kode = trim($_POST['kode']);

    if ($nama === '' || $jumlah < 0) $error = 'Nama dan jumlah harus diisi dengan benar.';
    else {
        $tersedia = $jumlah;
        $stmt = $mysqli->prepare("INSERT INTO barang (nama, deskripsi, jumlah, tersedia, lokasi, kode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiss', $nama, $deskripsi, $jumlah, $tersedia, $lokasi, $kode);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Barang - Inventaris</title>
<style>
body { font-family: Arial, sans-serif; background: #e6f0ea; padding: 20px; }
.container { max-width: 500px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #2f4f4f; margin-bottom: 25px; font-size: 24px; }
label { display:block; margin-top:15px; font-weight:600; color:#3b5240; }
input, textarea { width:100%; padding:12px; margin-top:6px; border:1px solid #b2d8c1; border-radius:8px; font-size:14px; transition:0.3s; }
input:focus, textarea:focus { border-color:#16a34a; outline:none; box-shadow:0 0 5px rgba(22,163,74,0.3); }
textarea { min-height:80px; resize:vertical; }
.button-group { display:flex; gap:15px; margin-top:25px; }
button { flex:1; padding:12px 0; font-size:16px; font-weight:bold; border:none; border-radius:8px; cursor:pointer; transition:0.3s; }
button.submit-btn { background:#16a34a; color:#fff; }
button.submit-btn:hover { background:#13833b; }
button.cancel-btn { background:#dc2626; color:#fff; }
button.cancel-btn:hover { background:#b91c1c; }
.error { color:#b91c1c; background:#fde2e2; padding:10px 15px; margin-bottom:15px; border-radius:6px; text-align:center; font-weight:600; }
</style>
</head>
<body>
<div class="container">
<h2>Tambah Barang</h2>
<?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post">
<label>Nama Barang</label>
<input type="text" name="nama" required>

<label>Kode (unik)</label>
<input type="text" name="kode">

<label>Deskripsi</label>
<textarea name="deskripsi"></textarea>

<label>Jumlah</label>
<input type="number" name="jumlah" value="1" min="0" required>

<label>Lokasi</label>
<input type="text" name="lokasi">

<div class="button-group">
<button type="submit" class="submit-btn">Simpan</button>
<button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Kembali</button>
</div>
</form>
</div>
</body>
</html>
