<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Tambah Mata Kuliah';

require __DIR__ . '/../layouts/main.php';
?>

<h2 class="mb-3">Tambah Mata Kuliah</h2>

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
    action="<?= $base . '/matakuliah/store' ?>"
    class="card card-body"
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
            value="