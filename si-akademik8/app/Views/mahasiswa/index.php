<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Data Mahasiswa';

require __DIR__ . '/../layouts/main.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Data Mahasiswa</h2>

    <a class="btn btn-primary" href="<?= $base . '/mahasiswa/create' ?>">
        + Tambah Mahasiswa
    </a>
</div>

<form method="GET"
      action="<?= $base . '/mahasiswa' ?>"
      class="row g-2 mb-3">

    <div class="col-md-10">
        <input
            type="text"
            class="form-control"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Cari berdasarkan NIM atau nama..."
        >
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-outline-primary">
            Cari
        </button>
    </div>
</form>

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped align-middle">

            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Dosen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!$mahasiswa): ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($mahasiswa as $i => $mhs): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>

                        <td>
                            <?= htmlspecialchars($mhs['nim']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                (($mhs['kode_prodi'] ?? '') !== ''
                                    ? $mhs['kode_prodi'] . ' - '
                                    : '') . $mhs['prodi']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($mhs['nama_dosen'] ?? '-') ?>
                        </td>

                        <td>
                            <span class="badge text-bg-secondary">
                                <?= htmlspecialchars($mhs['status']) ?>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <a
                                class="btn btn-sm btn-warning"
                                href="<?= $base . '/mahasiswa/edit?id=' . $mhs['id'] ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="btn btn-sm btn-danger"
                                href="<?= $base . '/mahasiswa/delete?id=' . $mhs['id'] ?>"
                                onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')"
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