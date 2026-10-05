<?php
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Si-Akademik') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>/mahasiswa">SI-AKADEMIK</a>
        <div>
            <a class="btn btn-sm btn-outline-light me-1" href="<?= $base ?>/mahasiswa">Mahasiswa</a>
            <a class="btn btn-sm btn-outline-light me-1" href="<?= $base ?>/prodi">Prodi</a>
            <a class="btn btn-sm btn-outline-light" href="<?= $base ?>/matakuliah">Mata Kuliah</a>
        </div>
    </div>
</nav>
<div class="container pb-5">
