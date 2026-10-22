
<?php

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=si_akademik7;charset=utf8mb4',
        'root',
        ''
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $method = $_SERVER['REQUEST_METHOD'];

    // GET: mengambil data mahasiswa
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

            if ($id === false || $id === null || $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID mahasiswa tidak valid'
                ]);
                exit;
            }

            $stmt = $pdo->prepare(
                'SELECT * FROM mahasiswa WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch();

            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Mahasiswa tidak ditemukan'
                ]);
                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } else {
            $stmt = $pdo->query(
                'SELECT * FROM mahasiswa ORDER BY id ASC'
            );

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data' => $stmt->fetchAll()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

    // POST: menambahkan data mahasiswa
    } elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $input = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Format JSON tidak valid'
            ]);
            exit;
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $prodiId = filter_var(
            $input['prodi_id'] ?? null,
            FILTER_VALIDATE_INT
        );
        $angkatan = filter_var(
            $input['angkatan'] ?? null,
            FILTER_VALIDATE_INT
        );
        $status = trim($input['status'] ?? 'aktif');
        $dosenId = $input['dosen_id'] ?? null;

        // Validasi data wajib
        if (
            $nim === '' ||
            $nama === '' ||
            $prodiId === false || $prodiId === null || $prodiId < 1 ||
            $angkatan === false || $angkatan === null ||
            $angkatan < 1901 || $angkatan > 2155
        ) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'NIM, nama, prodi_id, dan angkatan wajib diisi dengan benar'
            ]);
            exit;
        }

        if (strlen($nim) > 20 || strlen($nama) > 100) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'NIM maksimal 20 karakter dan nama maksimal 100 karakter'
            ]);
            exit;
        }

        // Email boleh kosong atau null
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Format email tidak valid'
            ]);
            exit;
        }

        if ($email === '') {
            $email = null;
        }

        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Status harus aktif, cuti, atau lulus'
            ]);
            exit;
        }

        // Validasi dosen_id jika diisi
        if ($dosenId === '' || $dosenId === null) {
            $dosenId = null;
        } else {
            $dosenId = filter_var($dosenId, FILTER_VALIDATE_INT);

            if ($dosenId === false || $dosenId < 1) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'dosen_id tidak valid'
                ]);
                exit;
            }
        }

        // Periksa apakah NIM sudah digunakan
        $check = $pdo->prepare(
            'SELECT id FROM mahasiswa WHERE nim = :nim'
        );
        $check->execute(['nim' => $nim]);

        if ($check->fetch()) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'NIM sudah terdaftar'
            ]);
            exit;
        }

        // Pastikan program studi tersedia
        $checkProdi = $pdo->prepare(
            'SELECT id FROM prodi WHERE id = :id'
        );
        $checkProdi->execute(['id' => $prodiId]);

        $prodiData = $checkProdi->fetch();

        if (!$prodiData) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Program studi tidak ditemukan'
            ]);
            exit;
        }

        // Ambil nama program studi karena kolom mahasiswa.prodi masih wajib diisi.
        $stmtNamaProdi = $pdo->prepare(
            'SELECT nama FROM prodi WHERE id = :id'
        );
        $stmtNamaProdi->execute(['id' => $prodiId]);
        $namaProdi = $stmtNamaProdi->fetchColumn();

        if ($namaProdi === false) {
            http_response_code(422);
            echo json_encode([
                'success' => false,
                'message' => 'Nama program studi tidak ditemukan'
            ]);
            exit;
        }

        // Pastikan dosen tersedia jika dosen_id diisi
        if ($dosenId !== null) {
            $checkDosen = $pdo->prepare(
                'SELECT id FROM dosen WHERE id = :id'
            );
            $checkDosen->execute(['id' => $dosenId]);

            if (!$checkDosen->fetch()) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Dosen tidak ditemukan'
                ]);
                exit;
            }
        }

        // Simpan data dengan prepared statement
        $stmt = $pdo->prepare(
            'INSERT INTO mahasiswa
                (nim, nama, email, prodi, prodi_id, angkatan, status, dosen_id)
             VALUES
                (:nim, :nama, :email, :prodi, :prodi_id, :angkatan, :status, :dosen_id)'
        );

        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi' => $namaProdi,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status' => $status,
            'dosen_id' => $dosenId
        ]);

        $idBaru = (int) $pdo->lastInsertId();

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan',
            'data' => [
                'id' => $idBaru,
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'prodi' => $namaProdi,
                'prodi_id' => $prodiId,
                'angkatan' => $angkatan,
                'status' => $status,
                'dosen_id' => $dosenId
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } else {
        header('Allow: GET, POST');
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan'
        ]);
    }

} catch (Throwable $e) {
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    error_log(
        '[' . date('Y-m-d H:i:s') . '] API error: '
        . $e->getMessage() . PHP_EOL,
        3,
        $logDir . '/app.log'
    );

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server'
    ]);
}
