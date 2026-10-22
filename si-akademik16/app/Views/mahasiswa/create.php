<?php
$prodiList = $prodiList ?? [];
$dosenList = $dosenList ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            display: block;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
            color: #222;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
        }

        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 20px;
                margin: 20px auto;
            }
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Tambah Mahasiswa</h1>

    <form
        method="POST"
        action="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa/store"
    >
        <div class="form-group">
            <label for="nim">NIM</label>
            <input
                type="text"
                id="nim"
                name="nim"
                placeholder="NIM harus angka"
                pattern="[0-9]+"
                title="NIM harus berupa angka"
                value="<?= htmlspecialchars((string) ($_SESSION['old']['nim'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="nama">Nama Mahasiswa</label>
            <input
                type="text"
                id="nama"
                name="nama"
                maxlength="100"
                value="<?= htmlspecialchars((string) ($_SESSION['old']['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
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
                value="<?= htmlspecialchars((string) ($_SESSION['old']['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="prodi">Prodi</label>
            <select name="prodi" id="prodi" required>
                <option value="">-- Pilih Prodi --</option>

                <?php foreach ($prodiList as $prodi): ?>
                    <?php
                    $namaProdi = is_array($prodi)
                        ? ($prodi['nama'] ?? $prodi['nama_prodi'] ?? '')
                        : $prodi;
                    ?>

                    <?php if (is_string($namaProdi) && trim($namaProdi) !== ''): ?>
                        <option
                            value="<?= htmlspecialchars($namaProdi, ENT_QUOTES, 'UTF-8') ?>"
                            <?= (($_SESSION['old']['prodi'] ?? '') === $namaProdi) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($namaProdi, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="dosen_id">Dosen Pembimbing</label>
            <select name="dosen_id" id="dosen_id" required>
                <option value="">-- Pilih Dosen --</option>

                <?php foreach ($dosenList as $dosen): ?>
                    <option
                        value="<?= htmlspecialchars((string) $dosen['id'], ENT_QUOTES, 'UTF-8') ?>"
                        <?= ((string) ($_SESSION['old']['dosen_id'] ?? '') === (string) $dosen['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($dosen['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        (<?= htmlspecialchars($dosen['nidn'] ?? '', ENT_QUOTES, 'UTF-8') ?>)
                        - <?= htmlspecialchars($dosen['bidang_keahlian'] ?? '', ENT_QUOTES, 'UTF-8') ?>
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
                placeholder="Contoh: 2026"
                min="1901"
                max="2155"
                value="<?= htmlspecialchars((string) ($_SESSION['old']['angkatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="Aktif" <?= (($_SESSION['old']['status'] ?? '') === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                <option value="Lulus" <?= (($_SESSION['old']['status'] ?? '') === 'Lulus') ? 'selected' : '' ?>>Lulus</option>
                <option value="Cuti" <?= (($_SESSION['old']['status'] ?? '') === 'Cuti') ? 'selected' : '' ?>>Cuti</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit">Tambah Mahasiswa</button>

            <a
                href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8') ?>/mahasiswa"
                class="back"
            >Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
