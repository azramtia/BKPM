<?php

abstract class BaseController {
    /** Baca body JSON; kirim 400 jika kosong atau formatnya salah. */
    protected function readJson(): array {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            Response::json(400, false, 'Body request kosong, kirim data dalam format JSON');
        }
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            Response::json(400, false, 'Format JSON tidak valid');
        }
        return $data;
    }

    /** Pastikan ID berupa bilangan bulat positif. */
    protected function requireId($id): int {
        if ($id === null || $id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
            Response::json(400, false, 'ID tidak valid atau tidak disertakan, contoh: ?id=1');
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
