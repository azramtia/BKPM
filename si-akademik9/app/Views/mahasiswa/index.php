<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Daftar Mahasiswa</h2>
    <a href="<?= BASE_URL ?>/mahasiswa/create" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
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
            <?php if (empty($mahasiswa)): ?>
                <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
            <?php endif; ?>

            <?php $no = 1; ?>
            <?php foreach ($mahasiswa as $m): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($m->getNim()) ?></td>
                    <td><?= htmlspecialchars($m->getNama()) ?></td>
                    <td><?= htmlspecialchars($m->getEmail()) ?></td>
                    <td><?= htmlspecialchars($m->getProdiNama()) ?></td>
                    <td><?= $m->getAngkatan() ?></td>
                    <td><?= htmlspecialchars($m->getStatus()) ?></td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_URL ?>/mahasiswa/edit?id=<?= $m->getId() ?>" class="btn btn-warning btn-sm">Edit</a>

                        <form method="POST" action="<?= BASE_URL ?>/mahasiswa/destroy" class="d-inline"
                            onsubmit="return confirm('Yakin hapus data <?= htmlspecialchars($m->getNama(), ENT_QUOTES) ?>?')">
                            <input type="hidden" name="id" value="<?= $m->getId() ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
