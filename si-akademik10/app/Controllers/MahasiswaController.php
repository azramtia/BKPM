<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';

/**
 * MahasiswaController mewarisi BaseController (view & redirect)
 * dan mengakses data lewat MahasiswaRepository, tanpa query SQL.
 */
class MahasiswaController extends BaseController
{
    private const LIST_URL = BASE_URL . '/mahasiswa';

    private $repository;

    public function __construct()
    {
        $this->repository = new MahasiswaRepository(new Database());
    }

    public function index()
    {
        AuthMiddleware::handle();

        $this->view('mahasiswa/index', [
            'mahasiswa' => $this->repository->all()
        ]);
    }

    public function detail()
    {
        AuthMiddleware::handle();

        $mahasiswa = $this->repository->findDetail((int) ($_GET['id'] ?? 0));

        if (!$mahasiswa) {
            $this->redirect(self::LIST_URL);
        }

        $this->view('mahasiswa/detail', ['mahasiswa' => $mahasiswa]);
    }

    public function create()
    {
        AuthMiddleware::handle();

        $this->view('mahasiswa/create', [
            'prodiList' => $this->repository->prodiList()
        ]);
    }

    public function store()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        $this->repository->create($_POST);

        $this->redirect(self::LIST_URL);
    }

    public function edit()
    {
        AuthMiddleware::handle();

        $mahasiswa = $this->repository->find((int) ($_GET['id'] ?? 0));

        if (!$mahasiswa) {
            $this->redirect(self::LIST_URL);
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodiList' => $this->repository->prodiList()
        ]);
    }

    public function update()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        $this->repository->update((int) ($_POST['id'] ?? 0), $_POST);

        $this->redirect(self::LIST_URL);
    }

    public function delete()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        $this->repository->delete((int) ($_POST['id'] ?? 0));

        $this->redirect(self::LIST_URL);
    }

    // Aksi yang mengubah data hanya boleh lewat form (POST)
    private function mustBePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(self::LIST_URL);
        }
    }
}
