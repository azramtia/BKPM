<?php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Mahasiswa</div>
    <div class="card-body">

        <form method="POST" action="<?= BASE_URL ?>/mahasiswa/store">

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" value="<?= htmlspecialchars($old['nim'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($old['nama'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Prodi</label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">-- Pilih Prodi --</option>
                    <?php foreach ($prodi as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ((int) ($old['prodi_id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Angkatan</label>
                <input type="number" name="angkatan" class="form-control" value="<?= htmlspecialchars($old['angkatan'] ?? date('Y')) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['aktif', 'cuti', 'lulus'] as $s): ?>
                        <option value="<?= $s ?>" <?= (($old['status'] ?? 'aktif') === $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-secondary">Batal</a>

        </form>

    </div>
</div>
