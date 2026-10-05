<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Data Mata Kuliah';

require __DIR__ . '/../layouts/main.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Mata Kuliah</h2>

    <a
        class="btn btn-primary"
        href="<?= $base . '/matakuliah/create' ?>"
    >
        + Tambah Mata Kuliah
    </a>
</div>

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>SKS</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($matakuliah as $i => $mk): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>

                        <td>
                            <?= htmlspecialchars($mk['kode']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mk['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mk['sks']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $mk['kode_prodi'] . ' - ' . $mk['nama_prodi']
                            ) ?>
                        </td>

                        <td class="text-nowrap">
                            <a
                                class="btn btn-sm btn-warning"
                                href="<?= $base . '/matakuliah/edit?id=' . $mk['id'] ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="btn btn-sm btn-danger"
                                href="<?= $base . '/matakuliah/delete?id=' . $mk['id'] ?>"
                                onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')"
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