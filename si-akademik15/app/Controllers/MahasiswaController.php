
<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Services/MahasiswaService.php';
require_once __DIR__ . '/../Core/Logger.php';

class MahasiswaController extends BaseController
{
    private const LIST_URL = BASE_URL . '/mahasiswa';

    private $repository;
    private $service;

    public function __construct()
    {
        $this->repository = new MahasiswaRepository(new Database());
        $this->service = new MahasiswaService($this->repository);
    }

    // Menampilkan daftar mahasiswa
    public function index()
    {
        AuthMiddleware::handle();

        try {
            $flash = $_SESSION['flash'] ?? null;
            unset($_SESSION['flash']);

            $this->view('mahasiswa/index', [
                'mahasiswa' => $this->repository->all(),
                'flash' => $flash
            ]);
        } catch (Throwable $e) {
            Logger::error($e);

            $this->view('mahasiswa/index', [
                'mahasiswa' => [],
                'flash' => [
                    'type' => 'error',
                    'message' => 'Data mahasiswa gagal dimuat.'
                ]
            ]);
        }
    }

    // Menampilkan detail mahasiswa
    public function detail()
    {
        AuthMiddleware::handle();

        try {
            $id = (int) ($_GET['id'] ?? 0);
            $mahasiswa = $this->repository->findDetail($id);

            if (!$mahasiswa) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ];

                $this->redirect(self::LIST_URL);
                return;
            }

            $this->view('mahasiswa/detail', [
                'mahasiswa' => $mahasiswa
            ]);
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Detail mahasiswa gagal dimuat.'
            ];

            $this->redirect(self::LIST_URL);
        }
    }

    // Menampilkan form tambah mahasiswa
    public function create()
    {
        AuthMiddleware::handle();

        try {
            $this->view('mahasiswa/create', [
                'prodiList' => $this->repository->prodiList(),
                'dosenList' => $this->repository->dosenList()
            ]);
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Form tambah mahasiswa gagal dibuka.'
            ];

            $this->redirect(self::LIST_URL);
        }
    }

    // Menyimpan mahasiswa baru
    public function store()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        try {
            $result = $this->service->create($_POST);

            $_SESSION['flash'] = [
                'type' => $result['success'] ? 'success' : 'error',
                'message' => $result['success']
                    ? 'Data mahasiswa berhasil ditambahkan.'
                    : implode(' ', $result['errors'])
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data mahasiswa gagal disimpan.'
            ];
        }

        $this->redirect(self::LIST_URL);
    }

    // Menampilkan form edit mahasiswa
    public function edit()
    {
        AuthMiddleware::handle();

        try {
            $id = (int) ($_GET['id'] ?? 0);
            $mahasiswa = $this->repository->find($id);

            if (!$mahasiswa) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Data mahasiswa tidak ditemukan.'
                ];

                $this->redirect(self::LIST_URL);
                return;
            }

            $this->view('mahasiswa/edit', [
                'mahasiswa' => $mahasiswa,
                'prodiList' => $this->repository->prodiList(),
                'dosenList' => $this->repository->dosenList()
            ]);
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Form edit mahasiswa gagal dibuka.'
            ];

            $this->redirect(self::LIST_URL);
        }
    }

    // Memperbarui data mahasiswa
    public function update()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        try {
            $id = (int) ($_POST['id'] ?? 0);
            $result = $this->service->update($id, $_POST);

            $_SESSION['flash'] = [
                'type' => $result['success'] ? 'success' : 'error',
                'message' => $result['success']
                    ? 'Data mahasiswa berhasil diperbarui.'
                    : implode(' ', $result['errors'])
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data mahasiswa gagal diperbarui.'
            ];
        }

        $this->redirect(self::LIST_URL);
    }

    // Menghapus mahasiswa melalui service
    public function delete()
    {
        AuthMiddleware::handle();
        $this->mustBePost();

        try {
            $id = (int) ($_POST['id'] ?? 0);
            $result = $this->service->delete($id);

            $_SESSION['flash'] = [
                'type' => $result['success'] ? 'success' : 'error',
                'message' => $result['success']
                    ? 'Data mahasiswa berhasil dihapus.'
                    : implode(' ', $result['errors'])
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Data mahasiswa gagal dihapus.'
            ];
        }

        $this->redirect(self::LIST_URL);
    }

    // Memastikan proses perubahan data menggunakan POST
    private function mustBePost()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Allow: POST');
            http_response_code(405);
            exit('405 - Metode HTTP tidak diizinkan.');
        }
    }
}
