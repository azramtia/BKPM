<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Tambah Mahasiswa';

require __DIR__ . '/../layouts/main.php';
?>

<h2 class="mb-3">Tambah Data Mahasiswa</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form
    method="POST"
    action="<?= $base . '/mahasiswa/store' ?>"
    class="card card-body"
>

    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input
            type="text"
            class="form-control"
            name="nim"
            value="<?= htmlspecialchars($data['nim']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input
            type="text"
            class="form-control"
            name="nama"
            value="<?= htmlspecialchars($data['nama']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input
            type="email"
            class="form-control"
            name="email"
            value="<?= htmlspecialchars($data['email']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Prodi</label>

        <select class="form-select" name="prodi_id">
            <option value="">-- Pilih Prodi --</option>

            <?php foreach ($prodi as $p): ?>
                <option
                    value="<?= $p['id'] ?>"
                    <?= (string) $data['prodi_id'] === (string) $p['id']
                        ? 'selected'
                        : '' ?>
                >
                    <?= htmlspecialchars($p['kode'] . ' - ' . $p['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Angkatan</label>

        <input
            type="number"
            class="form-control"
            name="angkatan"
            value="<?= htmlspecialchars($data['angkatan']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>

        <select class="form-select" name="status">
            <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>
                <option
                    value="<?= $s ?>"
                    <?= $data['status'] === $s ? 'selected' : '' ?>
                >
                    <?= ucfirst($s) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            Simpan
        </button>

        <a
            class="btn btn-secondary"
            href="<?= $base . '/mahasiswa' ?>"
        >
            Batal
        </a>
    </div>

</form>

</div>
</body>
</html>