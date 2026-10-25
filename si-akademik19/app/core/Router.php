<?php
class Router {

    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
    ];

    public function dispatch(): void {
        try {
            $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
            $segments = $uri === '' ? [] : explode('/', $uri);

            // Cari nama resource pada URL; segmen sesudahnya (jika ada) dianggap ID
            $resource = null;
            $pathId = null;
            foreach ($segments as $i => $segment) {
                if (isset($this->map[$segment])) {
                    $resource = $segment;
                    $pathId = $segments[$i + 1] ?? null;
                    break;
                }
            }

            if ($resource === null) {
                Response::json(404, false, 'Endpoint tidak ditemukan');
            }

            $id = $pathId ?? ($_GET['id'] ?? null);
            $controllerName = $this->map[$resource];
            $controller = new $controllerName();

            // register bukan resource CRUD: hanya menerima POST
            if ($resource === 'register') {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    header('Allow: POST');
                    Response::json(405, false, 'Method tidak diizinkan, endpoint register hanya menerima POST');
                }
                $controller->register();
                return;
            }

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':    $controller->index($id); break;
                case 'POST':   $controller->store(); break;
                case 'PUT':
                case 'PATCH':  $controller->update($id); break;
                case 'DELETE': $controller->destroy($id); break;
                default:
                    header('Allow: GET, POST, PUT, PATCH, DELETE');
                    Response::json(405, false, 'Method tidak diizinkan');
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
    }
}