<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Data Prodi';

require __DIR__ . '/../layouts/main.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Program Studi</h2>

    <a
        class="btn btn-primary"
        href="<?= $base . '/prodi/create' ?>"
    >
        + Tambah Prodi
    </a>
</div>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($prodi as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>

                        <td>
                            <?= htmlspecialchars($p['kode']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($p['nama']) ?>
                        </td>

                        <td class="text-nowrap">
                            <a
                                class="btn btn-sm btn-warning"
                                href="<?= $base . '/prodi/edit?id=' . $p['id'] ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="btn btn-sm btn-danger"
                                href="<?= $base . '/prodi/delete?id=' . $p['id'] ?>"
                                onclick="return confirm('Yakin ingin menghapus prodi ini?')"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>

        </table>

    </div>
</div>

</div>
</body>
</html>