<?php
class Auth {
    const SECRET = 'kunci_rahasia_workshop_2026';
    // Tugas Mandiri 2: masa berlaku token 5 menit (300 detik)
    const TOKEN_TTL = 300;

    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64urlDecode(string $data) {
        $sisa = strlen($data) % 4;
        if ($sisa) {
            $data .= str_repeat('=', 4 - $sisa);
        }
        return base64_decode(strtr($data, '-_', '+/'), true);
    }

    public static function generateToken(array $payload): string {
        $header     = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + self::TOKEN_TTL;
        $payloadEnc = self::base64url(json_encode($payload));
        $signature  = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));
        return "$header.$payloadEnc.$signature";
    }

    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        [$header, $payload, $signature] = $parts;

        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) return false;

        $json = self::base64urlDecode($payload);
        $data = $json === false ? null : json_decode($json, true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) return false;

        return $data;
    }

    // Ambil header Authorization (tidak peka huruf besar/kecil)
    private static function getAuthorizationHeader(): string {
        if (function_exists('getallheaders')) {
            foreach (getallheaders() as $nama => $nilai) {
                if (strcasecmp($nama, 'Authorization') === 0) {
                    return trim($nilai);
                }
            }
        }
        return trim($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
    }

    // Middleware: hentikan request dengan 401 bila token tidak ada / tidak valid
    public static function requireAuth(): array {
        $authHeader = self::getAuthorizationHeader();

        if (!preg_match('/^Bearer\s+(\S+)$/i', $authHeader, $m)) {
            header('WWW-Authenticate: Bearer');
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);
        if (!$payload) {
            header('WWW-Authenticate: Bearer');
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }
        return $payload;
    }
}
