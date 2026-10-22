<?php

ini_set('display_errors', '0'); // jangan bocorkan error PHP ke response JSON
header('Content-Type: application/json; charset=utf-8');

const JSON_FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

function respond(int $status, bool $success, string $message, $data = null, array $headers = []): void
{
    http_response_code($status);

    foreach ($headers as $header) {
        header($header);
    }

    $body = ['success' => $success, 'message' => $message];

    if ($data !== null) {
        $body['data'] = $data;
    }

    echo json_encode($body, JSON_FLAGS);
    exit;
}

function strLength(string $text): int
{
    return function_exists('mb_strlen')
        ? mb_strlen($text, 'UTF-8')
        : (int) preg_match_all('/./us', $text);
}

function parseId($raw): int
{
    if ($raw === null || $raw === '' || !ctype_digit((string) $raw) || (int) $raw < 1) {
        respond(400, false, 'ID mahasiswa tidak valid');
    }

    return (int) $raw;
}

function readJsonBody(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        respond(400, false, 'Body request kosong, kirim data dalam format JSON');
    }

    $input = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
        respond(400, false, 'Format JSON tidak valid');
    }

    return $input;
}

/** Ambil satu mahasiswa berdasarkan ID. */
function findMahasiswa(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare(
        'SELECT id, nim, nama, email, prodi, status, dosen_id
         FROM mahasiswa WHERE id = :id'
    );
    $stmt->execute([':id' => $id]);

    return $stmt->fetch();
}

/** Cek apakah NIM sudah dipakai (abaikan ID tertentu saat update). */
function nimExists(PDO $pdo, string $nim, ?int $excludeId = null): bool
{
    $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
    $params = [':nim' => $nim];

    if ($excludeId !== null) {
        $sql .= ' AND id <> :id';
        $params[':id'] = $excludeId;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn() > 0;
}

function validateMahasiswa(PDO $pdo, array $input, ?array $current = null): array
{
    $errors = [];

    // NIM (wajib)
    $nim = trim((string) ($input['nim'] ?? ''));
    if ($nim === '') {
        $errors['nim'] = 'NIM wajib diisi';
    } elseif (!preg_match('/^[A-Za-z0-9]+$/', $nim) || strlen($nim) > 20) {
        $errors['nim'] = 'NIM hanya huruf/angka dan maksimal 20 karakter';
    }

    // Nama (wajib)
    $nama = trim((string) ($input['nama'] ?? ''));
    if ($nama === '') {
        $errors['nama'] = 'Nama wajib diisi';
    } elseif (strLength($nama) > 100) {
        $errors['nama'] = 'Nama maksimal 100 karakter';
    }

    // Email (opsional, tetapi jika diisi harus valid)
    $email = array_key_exists('email', $input)
        ? trim((string) $input['email'])
        : ($current['email'] ?? '');

    if ($email !== '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        } elseif (strlen($email) > 150) {
            $errors['email'] = 'Email maksimal 150 karakter';
        }
    }
    $email = $email === '' ? null : $email;

    // Status (opsional, default aktif)
    $status = array_key_exists('status', $input)
        ? strtolower(trim((string) $input['status']))
        : ($current['status'] ?? 'aktif');

    if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
        $errors['status'] = 'Status harus aktif, cuti, atau lulus';
    }

    $prodi = $current['prodi'] ?? 'Teknik Informatika';

    if (isset($input['prodi_id']) && $input['prodi_id'] !== '') {
        if (!ctype_digit((string) $input['prodi_id']) || (int) $input['prodi_id'] < 1) {
            $errors['prodi_id'] = 'prodi_id tidak valid';
        } else {
            $stmt = $pdo->prepare('SELECT nama FROM prodi WHERE id = :id');
            $stmt->execute([':id' => (int) $input['prodi_id']]);
            $nama_prodi = $stmt->fetchColumn();

            if ($nama_prodi === false) {
                $errors['prodi_id'] = 'Program studi tidak ditemukan';
            } else {
                $prodi = $nama_prodi;
            }
        }
    } elseif (isset($input['prodi']) && trim((string) $input['prodi']) !== '') {
        $prodi = trim((string) $input['prodi']);
        if (strLength($prodi) > 100) {
            $errors['prodi'] = 'Prodi maksimal 100 karakter';
        }
    }

    // Dosen pembimbing (opsional, boleh null)
    $dosenId = $current['dosen_id'] ?? null;

    if (array_key_exists('dosen_id', $input)) {
        if ($input['dosen_id'] === null || $input['dosen_id'] === '') {
            $dosenId = null;
        } elseif (!ctype_digit((string) $input['dosen_id']) || (int) $input['dosen_id'] < 1) {
            $errors['dosen_id'] = 'dosen_id tidak valid';
        } else {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM dosen WHERE id = :id');
            $stmt->execute([':id' => (int) $input['dosen_id']]);

            if ((int) $stmt->fetchColumn() === 0) {
                $errors['dosen_id'] = 'Dosen tidak ditemukan';
            } else {
                $dosenId = (int) $input['dosen_id'];
            }
        }
    }

    if ($errors) {
        respond(400, false, 'Data tidak valid', ['errors' => $errors]);
    }

    return [
        'nim'      => $nim,
        'nama'     => $nama,
        'email'    => $email,
        'prodi'    => $prodi,
        'status'   => $status,
        'dosen_id' => $dosenId,
    ];
}

try {
    $config = require __DIR__ . '/../config/database.php';

    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

    $method = $_SERVER['REQUEST_METHOD'];

    // ID dari route (/api/mahasiswa/{id}) atau dari query (?id=1)
    $rawId = $apiRouteId ?? null;
    if ($rawId === null && isset($_GET['id'])) {
        $rawId = $_GET['id'];
    }
    $hasId = $rawId !== null && $rawId !== '';

    switch ($method) {

        // ---------------------------------------------------------- GET
        case 'GET':
            if ($hasId) {
                $id = parseId($rawId);
                $row = findMahasiswa($pdo, $id);

                if (!$row) {
                    respond(404, false, 'Mahasiswa dengan ID ' . $id . ' tidak ditemukan');
                }

                respond(200, true, 'Data mahasiswa berhasil diambil', $row);
            }

            $rows = $pdo->query(
                'SELECT id, nim, nama, email, prodi, status, dosen_id
                 FROM mahasiswa ORDER BY id ASC'
            )->fetchAll();

            respond(200, true, 'Data mahasiswa berhasil diambil', $rows);

        // --------------------------------------------------------- POST
        case 'POST':
            if ($hasId) {
                respond(405, false, 'POST tidak boleh memakai ID, gunakan PUT untuk mengubah data', null, ['Allow: GET, PUT, DELETE']);
            }

            $data = validateMahasiswa($pdo, readJsonBody());

            if (nimExists($pdo, $data['nim'])) {
                respond(409, false, 'NIM sudah terdaftar');
            }

            $stmt = $pdo->prepare(
                'INSERT INTO mahasiswa (nim, nama, email, prodi, status, dosen_id)
                 VALUES (:nim, :nama, :email, :prodi, :status, :dosen_id)'
            );
            $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama'],
                ':email'    => $data['email'],
                ':prodi'    => $data['prodi'],
                ':status'   => $data['status'],
                ':dosen_id' => $data['dosen_id'],
            ]);

            $baru = findMahasiswa($pdo, (int) $pdo->lastInsertId());

            respond(201, true, 'Data mahasiswa berhasil ditambahkan', $baru, [
                'Location: ' . rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/') . '/' . $baru['id'],
            ]);

        // ---------------------------------------------------------- PUT
        case 'PUT':
            if (!$hasId) {
                respond(400, false, 'ID mahasiswa wajib disertakan, contoh: /api/mahasiswa/1', null, ['Allow: GET, POST']);
            }

            $id = parseId($rawId);
            $lama = findMahasiswa($pdo, $id);

            if (!$lama) {
                respond(404, false, 'Mahasiswa dengan ID ' . $id . ' tidak ditemukan');
            }

            $data = validateMahasiswa($pdo, readJsonBody(), $lama);

            if (nimExists($pdo, $data['nim'], $id)) {
                respond(409, false, 'NIM sudah digunakan mahasiswa lain');
            }

            $stmt = $pdo->prepare(
                'UPDATE mahasiswa
                 SET nim = :nim, nama = :nama, email = :email,
                     prodi = :prodi, status = :status, dosen_id = :dosen_id
                 WHERE id = :id'
            );
            $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama'],
                ':email'    => $data['email'],
                ':prodi'    => $data['prodi'],
                ':status'   => $data['status'],
                ':dosen_id' => $data['dosen_id'],
                ':id'       => $id,
            ]);

            respond(200, true, 'Data mahasiswa berhasil diubah', findMahasiswa($pdo, $id));

        // ------------------------------------------------------- DELETE
        case 'DELETE':
            if (!$hasId) {
                respond(400, false, 'ID mahasiswa wajib disertakan, contoh: /api/mahasiswa/1', null, ['Allow: GET, POST']);
            }

            $id = parseId($rawId);

            if (!findMahasiswa($pdo, $id)) {
                respond(404, false, 'Mahasiswa dengan ID ' . $id . ' tidak ditemukan');
            }

            $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);

            respond(200, true, 'Data mahasiswa berhasil dihapus');

        default:
            respond(405, false, 'Method tidak diizinkan', null, ['Allow: GET, POST, PUT, DELETE']);
    }

} catch (Throwable $e) {
    require_once __DIR__ . '/../app/Core/Logger.php';
    Logger::error($e);

    respond(500, false, 'Terjadi kesalahan pada server');
}
