<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Edit Prodi';

require __DIR__ . '/../layouts/main.php';
?>

<h2 class="mb-3">Edit Prodi</h2>

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
    action="<?= $base . '/prodi/update' ?>"
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
        <label class="form-label">Nama Prodi</label>

        <input
            type="text"
            class="form-control"
            name="nama"
            value="<?= htmlspecialchars($data['nama']) ?>"
        >
    </div>

    <div>
        <button type="submit" class="btn btn-primary">
            Update
        </button>

        <a
            class="btn btn-secondary"
            href="<?= $base . '/prodi' ?>"
        >
            Batal
        </a>
    </div>

</form>

</div>
</body>
</html>