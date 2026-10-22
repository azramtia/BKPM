
<?php
$mahasiswa = $mahasiswa ?? [];
$flash = $flash ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #222;
        }

        h1 { margin-bottom: 20px; }

        .flash {
            margin: 15px 0;
            padding: 12px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .add-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .add-link:hover { background-color: #0056b3; }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th { background-color: #f2f2f2; }

        tr:nth-child(even) { background-color: #fafafa; }

        .edit-link {
            color: #007bff;
            text-decoration: none;
            margin-right: 8px;
        }

        .delete-button {
            padding: 6px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover { background-color: #b02a37; }
    </style>
</head>
<body>

<h1>Data Mahasiswa</h1>

<?php if (!empty($flash)): ?>
    <div class="flash <?= ($flash['type'] ?? '') === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars((string) ($flash['message'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<a class="add-link"
   href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa/create">
    + Tambah Mahasiswa
</a>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Prodi</th>
                <th>Angkatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($mahasiswa)): ?>
                <?php foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($mhs['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($mhs['nim'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($mhs['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?= htmlspecialchars((string) (($mhs['email'] ?? '') ?: '-'), ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td><?= htmlspecialchars((string) ($mhs['prodi'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) (($mhs['angkatan'] ?? '') ?: '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($mhs['status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a class="edit-link"
                               href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa/edit?id=<?= (int) ($mhs['id'] ?? 0) ?>">
                                Edit
                            </a>

                            <form method="POST"
                                  action="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa/delete"
                                  style="display:inline;"
                                  onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) ($mhs['id'] ?? 0) ?>"
                                >

                                <button class="delete-button" type="submit">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Belum ada data mahasiswa.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
