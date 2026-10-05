<!DOCTYPE html>
<html>
<head>
    <title>Tambah Mahasiswa</title>
</head>
<body>

    <h1>Tambah Mahasiswa</h1>

    <form method="POST" action="/si-akademik10/public/mahasiswa/store">

        <label>NIM</label><br>
        <input type="text" name="nim" required>

        <br><br>

        <label>Nama Mahasiswa</label><br>
        <input type="text" name="nama" required>

        <br><br>

        <label>Email</label><br>
        <input type="email" name="email" required>

        <br><br>

        <label>Prodi ID</label><br>
        <input type="number" name="prodi_id" required>

        <br><br>

        <label>Angkatan</label><br>
        <input type="number" name="angkatan" required>

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

        <a href="/si-akademik10/public/mahasiswa">
            Batal
        </a>

    </form>

</body>
</html>