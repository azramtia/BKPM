<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Edit Mata Kuliah';

require __DIR__ . '/../layouts/main.php';
?>

<h2 class="mb-3">Edit Mata Kuliah</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form
    method="POST"
    action="<?= $base . '/matakuliah/update' ?>"
    class="card card-body"
>

    <input
        type="hidden"
        name="id"
        value="<?= $data['id'] ?>"
    >

    <div class="mb-3">
        <label class="form-label">Kode</label>
        <input
            type="text"
            class="form-control"
            name="kode"
            value="<?= htmlspecialchars($data['kode']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Mata Kuliah</label>
        <input
            type="text"
            class="form-control"
            name="nama"
            value="<?= htmlspecialchars($data['nama']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">SKS</label>
        <input
            type="number"
            class="form-control"
            name="sks"
            value="<?= htmlspecialchars($data['sks']) ?>"
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Prodi</label>

        <select class="form-select" name="prodi_id">
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

    <div>
        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a
            class="btn btn-secondary"
            href="<?= $base . '/matakuliah' ?>"
        >
            Batal
        </a>
    </div>

</form>

</div>
</body>
</html>