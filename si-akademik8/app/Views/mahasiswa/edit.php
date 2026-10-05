<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$title = 'Edit Mahasiswa';

require __DIR__ . '/../layouts/main.php';
?>

<h2 class="mb-3">Edit Data Mahasiswa</h2>

<form
    method="POST"
    action="<?= $base . '/mahasiswa/update' ?>"
    class="card card-body"
>

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars($data['id']) ?>"
    >

    <div class="mb-3">
        <label class="form-label">NIM</label>
        <input
            type="text"
            class="form-control"
            name="nim"
            value="<?= htmlspecialchars($data['nim']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input
            type="text"
            class="form-control"
            name="nama"
            value="<?= htmlspecialchars($data['nama']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input
            type="email"
            class="form-control"
            name="email"
            value="<?= htmlspecialchars($data['email']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Prodi</label>

        <select class="form-select" name="prodi_id" required>
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
            required
        >
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>

        <select class="form-select" name="status" required>

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
            Simpan Perubahan
        </button>

        <a
            class="btn btn-secondary"
            href="<?= $base . '/mahasiswa' ?>"
        >
            Batal
        </a>
    </div>

</form>