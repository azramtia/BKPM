<?php
class Router {
  
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
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
                $this->json(404, ['success' => false, 'message' => 'Endpoint tidak ditemukan']);
            }

            $id = $pathId ?? ($_GET['id'] ?? null);
            $controllerName = $this->map[$resource];
            $controller = new $controllerName();

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':    $controller->index($id); break;
                case 'POST':   $controller->store(); break;
                case 'PUT':
                case 'PATCH':  $controller->update($id); break;
                case 'DELETE': $controller->destroy($id); break;
                default:
                    header('Allow: GET, POST, PUT, PATCH, DELETE');
                    $this->json(405, ['success' => false, 'message' => 'Method tidak diizinkan']);
            }
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->json(500, ['success' => false, 'message' => 'Terjadi kesalahan pada server']);
        }
    }

    private function json(int $status, array $body): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
