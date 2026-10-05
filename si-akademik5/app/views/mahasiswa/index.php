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

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Angkatan</th>
                </tr>
            </thead>

            <tbody>

                <?php $no = 1; ?>

                <?php foreach ($mahasiswa as $mhs): ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $mhs->getNim(); ?></td>
                        <td><?= $mhs->getNama(); ?></td>
                        <td><?= $mhs->getAngkatan(); ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</body>

</html>