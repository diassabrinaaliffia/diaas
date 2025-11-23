<?php
require_once 'koneksi.php';
require_once 'helpers.php';
require_login();

$sql = "SELECT t.*, b.nama AS nama_barang 
        FROM transaksi t 
        JOIN barang b ON t.barang_id = b.id 
        ORDER BY t.tanggal DESC";
$res = $mysqli->query($sql);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaksi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e8f0eb;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4b6354;
            margin-bottom: 24px;
        }

        /* Tombol kembali */
        a.kembali {
            display: inline-block;
            padding: 10px 16px;
            background: #a8c3b7;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.2s;
        }

        a.kembali:hover {
            background: #94b2a6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            overflow: hidden;
            border-radius: 10px;
        }

        thead {
            background: #7fa48d;
            color: white;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #dfe7e2;
        }

        tr:hover {
            background: #f3f8f5;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="kembali">⟵ Kembali</a>

        <h2>Daftar Transaksi</h2>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                    <td><?= htmlspecialchars($row['peminjam']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                    <td><?= htmlspecialchars($row['catatan']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
