<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Mahasiswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

    <div class="container mt-4">

        <h1 class="mb-4">Daftar Mahasiswa</h1>

        <a href="create.php" class="btn btn-primary mb-3">
            Tambah Mahasiswa
        </a>

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>23001</td>
                    <td>Arshinta Puja Aulia</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23002</td>
                    <td>Nindia Tri Anggraini</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23003</td>
                    <td>Azira Mutia Gani</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23004</td>
                    <td>Agustin Riski Rahmania</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

                <tr>
                    <td>23005</td>
                    <td>Aulia Luh Bilqis</td>
                    <td>Teknik Informatika</td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>