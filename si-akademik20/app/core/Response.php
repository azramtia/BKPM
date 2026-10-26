<?php
class Response {
   
    public static function json(int $httpCode, bool $success, string $message, $data = null, array $errors = []): void {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        $body = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];

        // Field errors hanya muncul jika ada kesalahan validasi
        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        $body['timestamp'] = date('c');

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;

        // Tugas Mandiri 2: PRETTY_PRINT hanya pada mode pengembangan
        if (self::isDevelopment()) {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }

    private static function isDevelopment(): bool {
        return defined('APP_ENV') && APP_ENV === 'development';
    }
}
