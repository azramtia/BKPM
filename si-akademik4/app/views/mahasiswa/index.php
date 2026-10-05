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