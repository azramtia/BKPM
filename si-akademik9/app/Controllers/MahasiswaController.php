<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

class MahasiswaController extends Controller
{
    private MahasiswaRepository $repo;

    // Constructor injection: controller tidak membuat koneksi database sendiri
    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $this->view('mahasiswa/index', ['mahasiswa' => $this->repo->all()]);
    }

    public function create(): void
    {
        $this->view('mahasiswa/create', ['prodi' => $this->repo->allProdi()]);
    }

    public function store(): void
    {
        try {
            $m = $this->isiDariForm(new Mahasiswa());
            $this->repo->create($m);

            $_SESSION['flash'] = 'Data mahasiswa berhasil ditambahkan';
            $this->redirect('/mahasiswa');
        } catch (InvalidArgumentException $e) {
            $_SESSION['error'] = $e->getMessage();
        } catch (PDOException $e) {
            $_SESSION['error'] = $this->pesanDatabase($e);
        }

        $_SESSION['old'] = $_POST;
        $this->redirect('/mahasiswa/create');
    }

    public function edit(): void
    {
        $m = $this->repo->find((int) ($_GET['id'] ?? 0));

        if ($m === null) {
            $_SESSION['error'] = 'Data mahasiswa tidak ditemukan';
            $this->redirect('/mahasiswa');
        }

        $this->view('mahasiswa/edit', ['m' => $m, 'prodi' => $this->repo->allProdi()]);
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $m  = $this->repo->find($id);

        if ($m === null) {
            $_SESSION['error'] = 'Data mahasiswa tidak ditemukan';
            $this->redirect('/mahasiswa');
        }

        try {
            $this->isiDariForm($m);
            $this->repo->update($m);

            $_SESSION['flash'] = 'Data mahasiswa berhasil diubah';
            $this->redirect('/mahasiswa');
        } catch (InvalidArgumentException $e) {
            $_SESSION['error'] = $e->getMessage();
        } catch (PDOException $e) {
            $_SESSION['error'] = $this->pesanDatabase($e);
        }

        $this->redirect('/mahasiswa/edit?id=' . $id);
    }

    public function destroy(): void
    {
        $this->repo->delete((int) ($_POST['id'] ?? 0));

        $_SESSION['flash'] = 'Data mahasiswa berhasil dihapus';
        $this->redirect('/mahasiswa');
    }

    // Mengisi objek Mahasiswa lewat setter (validasi terjadi di setter)
    private function isiDariForm(Mahasiswa $m): Mahasiswa
    {
        $m->setNim($_POST['nim'] ?? '');
        $m->setNama($_POST['nama'] ?? '');
        $m->setEmail($_POST['email'] ?? '');
        $m->setProdiId((int) ($_POST['prodi_id'] ?? 0));
        $m->setAngkatan((int) ($_POST['angkatan'] ?? 0));
        $m->setStatus($_POST['status'] ?? 'aktif');

        return $m;
    }

    private function pesanDatabase(PDOException $e): string
    {
        // 23000 = pelanggaran constraint (mis. NIM duplikat)
        return $e->getCode() === '23000'
            ? 'NIM sudah terdaftar'
            : 'Terjadi kesalahan pada database';
    }
}
