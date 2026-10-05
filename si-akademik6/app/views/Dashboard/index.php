<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

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

    <div class="card shadow-sm">

        <div class="card-body">

            <h3>
                Dashboard
            </h3>

            <p class="text-muted">
                Selamat datang,
                <strong>
                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                </strong>
            </p>


            <?php if (isset($_SESSION['flash'])): ?>

                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['flash']) ?>
                </div>

                <?php unset($_SESSION['flash']); ?>

            <?php endif; ?>


            <hr>


            <div class="d-flex gap-2">

                <a
                    href="/si-akademik6/publik/mahasiswa"
                    class="btn btn-primary"
                >
                    Data Mahasiswa
                </a>

                <a
                    href="/si-akademik6/publik/mahasiswa/create"
                    class="btn btn-success"
                >
                    Tambah Mahasiswa
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>