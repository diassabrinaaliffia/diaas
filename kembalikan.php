<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $mysqli->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) die('Barang tidak ditemukan.');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peminjam = trim($_POST['peminjam']);
    $jumlah = (int)$_POST['jumlah'];
    $catatan = trim($_POST['catatan']);

    if ($peminjam === '' || $jumlah <= 0) $error = 'Isi pengembali dan jumlah dengan benar.';
    else {
        $stmt = $mysqli->prepare("INSERT INTO transaksi (barang_id, peminjam, jenis, jumlah, catatan) VALUES (?, ?, 'kembali', ?, ?)");
        $stmt->bind_param('isis', $id, $peminjam, $jumlah, $catatan);
        if ($stmt->execute()) {
            $stmt2 = $mysqli->prepare("UPDATE barang SET tersedia = LEAST(jumlah, tersedia + ?) WHERE id = ?");
            $stmt2->bind_param('ii', $jumlah, $id);
            $stmt2->execute();
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menyimpan: " . $mysqli->error;
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kembalikan Barang</title>
    <style>
        /* Warna tema hijau sage */
        :root {
            --sage-light: #e6f0ea;
            --sage: #a8c0a0;
            --sage-dark: #6b8e66;
            --text-dark: #333;
            --error-red: #d9534f;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--sage-light);
            color: var(--text-dark);
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            max-width: 500px;
            margin: auto;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: var(--sage-dark);
        }

        .error {
            color: var(--error-red);
            margin-bottom: 10px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--sage);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 60px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: var(--sage);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--sage-dark);
        }

        p a {
            display: inline-block;
            margin-top: 15px;
            color: var(--sage-dark);
            text-decoration: none;
            font-weight: bold;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Kembalikan: <?= htmlspecialchars($data['nama']) ?></h2>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Pengembali (atau nama peminjam)</label>
            <input type="text" name="peminjam" required>
            <label>Jumlah yang Dikembalikan</label>
            <input type="number" name="jumlah" value="1" min="1" required>
            <label>Catatan</label>
            <textarea name="catatan"></textarea>
            <button type="submit">Kembalikan</button>
        </form>
        <p><a href="index.php">Kembali</a></p>
    </div>
</body>

</html>
