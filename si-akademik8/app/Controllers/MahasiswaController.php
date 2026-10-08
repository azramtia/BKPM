<?php

require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    private MahasiswaModel $model;
    private ProdiModel $prodiModel;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
        $this->prodiModel = new ProdiModel();
    }

    // =========================
    // MENAMPILKAN DATA MAHASISWA
    // =========================
    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');

        $mahasiswa = $this->model->all($search);

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    // =========================
    // FORM TAMBAH MAHASISWA
    // =========================
    public function create(): void
    {
        $prodi = $this->prodiModel->all();

        $data = [
            'nim' => '',
            'nama' => '',
            'prodi' => '',
            'status' => 'aktif'
        ];

        $errors = [];

        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    // =========================
    // PROSES TAMBAH MAHASISWA
    // =========================
    public function store(): void
    {
        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'prodi' => trim($_POST['prodi'] ?? ''),
            'status' => $_POST['status'] ?? 'aktif',
        ];

        $this->model->create($data);

        $this->redirect('/mahasiswa');
    }

    // =========================
    // FORM EDIT MAHASISWA
    // =========================
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $data = $this->model->find($id);

        if (!$data) {
            $this->redirect('/mahasiswa');
        }

        $prodi = $this->prodiModel->all();

        // Agar edit.php tidak mengalami
        // Undefined variable $errors
        $errors = [];

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    // =========================
    // PROSES UPDATE MAHASISWA
    // =========================
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $data = [
            'nim' => trim($_POST['nim'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'prodi' => trim($_POST['prodi'] ?? ''),
            'status' => $_POST['status'] ?? 'aktif',
        ];

        $this->model->update($id, $data);

        $this->redirect('/mahasiswa');
    }

    // =========================
    // HAPUS MAHASISWA
    // =========================
    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $this->model->delete($id);

        $this->redirect('/mahasiswa');
    }

    // =========================
    // REDIRECT
    // =========================
    private function redirect(string $path): void
    {
        header(
            'Location: ' . dirname($_SERVER['SCRIPT_NAME']) . $path
        );

        exit;
    }
}