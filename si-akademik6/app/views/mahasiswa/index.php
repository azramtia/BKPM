<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">


<nav class="navbar navbar-dark bg-primary">

    <div class="container">

        <span class="navbar-brand">
            Si-Akademik
        </span>

        <a
            href="/si-akademik6/publik/logout"
            class="btn btn-light btn-sm"
        >
            Logout
        </a>

    </div>

</nav>


<div class="container mt-4">


    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>
            Data Mahasiswa
        </h3>

        <div>

            <a
                href="/si-akademik6/publik/dashboard"
                class="btn btn-secondary"
            >
                Dashboard
            </a>

            <a
                href="/si-akademik6/publik/mahasiswa/create"
                class="btn btn-success"
            >
                + Tambah Mahasiswa
            </a>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">


            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover">

                    <thead class="table-primary">

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                NIM
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Program Studi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($mahasiswa as $index => $mhs): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs['nim']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs['nama']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($mhs['prodi']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        </div>

    </div>


</div>

</body>

</html>