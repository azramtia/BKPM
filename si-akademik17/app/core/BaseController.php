<?php
/**
 * Controller dasar: berisi helper untuk membaca body JSON dan mengirim response.
 * (Pada acara 18 format response akan dirapikan menjadi class Response tersendiri.)
 */
abstract class BaseController {
    protected function send(int $status, array $body): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /** Baca body JSON; kirim 400 jika kosong atau formatnya salah. */
    protected function readJson(): array {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            $this->send(400, ['success' => false, 'message' => 'Body request kosong, kirim data dalam format JSON']);
        }
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $this->send(400, ['success' => false, 'message' => 'Format JSON tidak valid']);
        }
        return $data;
    }

    /** Pastikan ID berupa bilangan bulat positif. */
    protected function requireId($id): int {
        if ($id === null || $id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
            $this->send(400, ['success' => false, 'message' => 'ID tidak valid atau tidak disertakan, contoh: ?id=1']);
        }
        return (int) $id;
    }

    /** Ambil nilai string dari input (di-trim). */
    protected function str(array $data, string $key): string {
        return isset($data[$key]) && is_scalar($data[$key]) ? trim((string) $data[$key]) : '';
    }

    /** Panjang string (karakter UTF-8); tetap jalan walau ekstensi mbstring tidak aktif. */
    protected function len(string $text): int {
        return function_exists('mb_strlen')
            ? mb_strlen($text, 'UTF-8')
            : (int) preg_match_all('/./us', $text);
    }
}
