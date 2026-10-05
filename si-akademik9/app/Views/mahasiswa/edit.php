<div class="card shadow-sm">
    <div class="card-header bg-warning">Edit Mahasiswa</div>
    <div class="card-body">

        <form method="POST" action="<?= BASE_URL ?>/mahasiswa/update">

            <input type="hidden" name="id" value="<?= $m->getId() ?>">

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($m->getNim()) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($m->getNama()) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($m->getEmail()) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prodi</label>
                <select name="prodi_id" class="form-select" required>
                    <?php foreach ($prodi as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($m->getProdiId() === (int) $p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Angkatan</label>
                <input type="number" name="angkatan" class="form-control" value="<?= $m->getAngkatan() ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>
                        <option value="<?= $s ?>" <?= ($m->getStatus() === $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
            <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-secondary">Batal</a>

        </form>

    </div>
</div>
