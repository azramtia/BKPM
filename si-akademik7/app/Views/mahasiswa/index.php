<?php

// Pastikan variabel selalu berupa array
$mahasiswa = $mahasiswa ?? [];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mahasiswa</title>

    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 1100px;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 12px 10px;
            font-size: 16px;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        td:first-child {
            text-align: center;
        }

        td:nth-child(2) {
            width: 120px;
        }

        td:nth-child(3) {
            width: 200px;
        }

        td:nth-child(4) {
            width: 280px;
        }

        td:nth-child(5),
        td:nth-child(6) {
            text-align: center;
            width: 120px;
        }
    </style>
</head>

<body>

    <h1>Data Mahasiswa</h1>

    <table>

        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>Dosen</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <?php if (count($mahasiswa) > 0): ?>

                <?php $no = 1; ?>

                <?php foreach ($mahasiswa as $mhs): ?>

                    <tr>

                        <td>
                            <?= $no++ ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['nim'] ?? '-') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['nama'] ?? '-') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['prodi'] ?? '-') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['nama_dosen'] ?? '-') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['status'] ?? '-') ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align: center;">
                        Data mahasiswa tidak ditemukan.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</body>

</html>