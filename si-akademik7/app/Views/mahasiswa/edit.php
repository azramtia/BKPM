<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Mahasiswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <h2>Edit Mahasiswa</h2>

    <hr>

    <form>

        <div class="mb-3">

            <label class="form-label">
                NIM
            </label>

            <input
                type="text"
                class="form-control"
                value="23001">

        </div>


        <div class="mb-3">

            <label class="form-label">
                Nama
            </label>

            <input
                type="text"
                class="form-control"
                value="Mahasiswa Contoh">

        </div>


        <div class="mb-3">

            <label class="form-label">
                Program Studi
            </label>

            <input
                type="text"
                class="form-control"
                value="Teknik Informatika">

        </div>


        <button
            type="submit"
            class="btn btn-warning">

            Update

        </button>


        <a
            href="/bkpm/si-akademik7/public/mahasiswa"
            class="btn btn-secondary">

            Kembali

        </a>

    </form>

</div>

</body>

</html>