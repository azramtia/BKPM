<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa</title>
</head>
<body>

    <h1>Tambah Mahasiswa</h1>

    <form method="POST" action="<?= BASE_URL ?>/mahasiswa/store">

        <label>NIM</label><br>
        <input type="text" name="nim" required>

        <br><br>

        <label>Nama Mahasiswa</label><br>
        <input type="text" name="nama" required>

        <br><br>

        <label>Prodi</label><br>
        <select name="prodi" required>
            <option value="">-- Pilih Prodi --</option>
            <?php foreach ($prodiList as $namaProdi): ?>
                <option value="<?= htmlspecialchars($namaProdi) ?>">
                    <?= htmlspecialchars($namaProdi) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Status</label><br>
        <select name="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="Aktif">Aktif</option>
            <option value="Lulus">Lulus</option>
            <option value="Cuti">Cuti</option>
        </select>

        <br><br>

        <button type="submit">Simpan</button>

        <a href="<?= BASE_URL ?>/mahasiswa">
            Batal
        </a>

    </form>

</body>
</html>