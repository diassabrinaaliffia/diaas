<?php
session_start();
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

// ambil list barang
$result = $mysqli->query("SELECT * FROM barang ORDER BY created_at DESC");
if (!$result) {
    die("Query gagal: " . $mysqli->error);
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Inventaris Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6faf6;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            color: #375a3b;
        }

        .user {
            margin-bottom: 12px;
            color: #415847;
        }

        .btn {
            background: #7da27d;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 6px;
            font-size: 14px;
        }

        .btn:hover {
            background: #658b65;
        }

        .btn.logout {
            background: #d16060;
        }

        .btn.logout:hover {
            background: #b91c1c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9efe9;
            text-align: left;
        }

        thead {
            background: #7da27d;
            color: white;
        }

        tr:hover {
            background: #f3f8f6;
        }

        a.action-btn {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            margin-right: 4px;
        }

        a.edit { background: #0284c7; }
        a.edit:hover { background: #0369a1; }
        a.delete { background: #dc2626; }
        a.delete:hover { background: #b91c1c; }
        a.pinjam { background: #16a34a; }
        a.pinjam:hover { background: #15803d; }
        a.kembalikan { background: #f59e0b; }
        a.kembalikan:hover { background: #d97706; }
        span.kosong { color: gray; }
    </style>
</head>

<body>
    <div class="container">
        <h1>Inventaris Barang</h1>

        <p class="user">
            Selamat datang, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
        </p>

        <p>
            <a href="barang_add.php" class="btn">+ Tambah Barang</a>
            <a href="transaksi.php" class="btn">Lihat Transaksi</a>
            <a href="logout.php" class="btn logout">Logout</a>
        </p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Tersedia</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()):
                    $tersedia = $row['tersedia'] ?? ($row['jumlah_tersedia'] ?? 0);
                    $jumlah = $row['jumlah'] ?? 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                    <td><?= nl2br(htmlspecialchars($row['deskripsi'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($jumlah) ?></td>
                    <td><?= htmlspecialchars($tersedia) ?></td>
                    <td><?= htmlspecialchars($row['lokasi'] ?? '') ?></td>
                    <td>
                        <a href="barang_edit.php?id=<?= urlencode($row['id']) ?>" class="action-btn edit">Edit</a>
                        <a href="barang_delete.php?id=<?= urlencode($row['id']) ?>" onclick="return confirm('Hapus barang?')" class="action-btn delete">Hapus</a>
                        <?php if ($tersedia > 0): ?>
                            <a href="pinjam.php?id=<?= urlencode($row['id']) ?>" class="action-btn pinjam">Pinjam</a>
                        <?php else: ?>
                            <span class="kosong">Kosong</span>
                        <?php endif; ?>

                        <?php if ($jumlah > $tersedia): ?>
                            <a href="kembalikan.php?id=<?= urlencode($row['id']) ?>" class="action-btn kembalikan">Kembalikan</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
