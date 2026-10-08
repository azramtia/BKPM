<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa</title>
</head>
<body>

    <h1>Edit Mahasiswa</h1>

    <form method="POST" action="<?= BASE_URL ?>/mahasiswa/update">

        <input type="hidden"
               name="id"
               value="<?= htmlspecialchars($mahasiswa['id']) ?>">

        <label>NIM</label><br>
        <input type="text"
               name="nim"
               value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
               required>

        <br><br>

        <label>Nama Mahasiswa</label><br>
        <input type="text"
               name="nama"
               value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
               required>

        <br><br>

        <label>Prodi</label><br>
        <select name="prodi" required>
            <?php foreach ($prodiList as $namaProdi): ?>
                <option value="<?= htmlspecialchars($namaProdi) ?>"
                    <?= $mahasiswa['prodi'] === $namaProdi ? 'selected' : '' ?>>
                    <?= htmlspecialchars($namaProdi) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Status</label><br>
        <select name="status" required>

            <option value="Aktif"
                <?= strcasecmp($mahasiswa['status'], 'Aktif') === 0 ? 'selected' : '' ?>>
                Aktif
            </option>

            <option value="Lulus"
                <?= strcasecmp($mahasiswa['status'], 'Lulus') === 0 ? 'selected' : '' ?>>
                Lulus
            </option>

            <option value="Cuti"
                <?= strcasecmp($mahasiswa['status'], 'Cuti') === 0 ? 'selected' : '' ?>>
                Cuti
            </option>

        </select>

        <br><br>

        <button type="submit">Simpan Perubahan</button>

        <a href="<?= BASE_URL ?>/mahasiswa">
            Batal
        </a>

    </form>

</body>
</html> 