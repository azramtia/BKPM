
<?php
$mahasiswa = $mahasiswa ?? [];
$prodiList = $prodiList ?? [];
$dosenList = $dosenList ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }

        .container {
            width: 80%;
            max-width: 700px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 { margin-bottom: 25px; }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover { background-color: #0056b3; }

        .back {
            display: inline-block;
            margin-left: 10px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Mahasiswa</h1>

    <form method="POST"
          action="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa/update">

        <input
            type="hidden"
            name="id"
            value="<?= htmlspecialchars((string) ($mahasiswa['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="form-group">
            <label for="nim">NIM</label>
            <input
                type="text"
                id="nim"
                name="nim"
                value="<?= htmlspecialchars((string) ($mahasiswa['nim'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                pattern="[0-9]+"
                title="NIM harus berupa angka"
                required
            >
        </div>

        <div class="form-group">
            <label for="nama">Nama</label>
            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                value="<?= htmlspecialchars((string) ($mahasiswa['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email Mahasiswa</label>
            <input
                type="email"
                id="email"
                name="email"
                maxlength="150"
                placeholder="contoh@email.com"
                value="<?= htmlspecialchars((string) ($mahasiswa['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="prodi_id">Prodi</label>
            <select id="prodi_id" name="prodi_id" required>
                <option value="">-- Pilih Prodi --</option>

                <?php foreach ($prodiList as $prodi): ?>
                    <option
                        value="<?= htmlspecialchars((string) ($prodi['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        <?= (
                            isset($mahasiswa['prodi_id'], $prodi['id']) &&
                            (int) $mahasiswa['prodi_id'] === (int) $prodi['id']
                        ) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars((string) ($prodi['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="dosen_id">Dosen Pembimbing</label>
            <select id="dosen_id" name="dosen_id" required>
                <option value="">-- Pilih Dosen --</option>

                <?php foreach ($dosenList as $dosen): ?>
                    <option
                        value="<?= htmlspecialchars((string) $dosen['id'], ENT_QUOTES, 'UTF-8') ?>"
                        <?= (
                            isset($mahasiswa['dosen_id'], $dosen['id']) &&
                            (int) $mahasiswa['dosen_id'] === (int) $dosen['id']
                        ) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars((string) ($dosen['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        (<?= htmlspecialchars((string) ($dosen['nidn'] ?? ''), ENT_QUOTES, 'UTF-8') ?>)
                        - <?= htmlspecialchars((string) ($dosen['bidang_keahlian'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="angkatan">Angkatan</label>
            <input
                type="number"
                id="angkatan"
                name="angkatan"
                min="1901"
                max="2155"
                value="<?= htmlspecialchars((string) ($mahasiswa['angkatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <?php $statusSekarang = strtolower((string) ($mahasiswa['status'] ?? '')); ?>

            <select id="status" name="status" required>
                <option value="aktif" <?= $statusSekarang === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="cuti" <?= $statusSekarang === 'cuti' ? 'selected' : '' ?>>Cuti</option>
                <option value="lulus" <?= $statusSekarang === 'lulus' ? 'selected' : '' ?>>Lulus</option>
            </select>
        </div>

        <button type="submit">Simpan Perubahan</button>

        <a
            href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa"
            class="back"
        >Kembali</a>
    </form>
</div>

</body>
</html>
