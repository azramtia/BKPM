<?php

class AuthController extends BaseController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    // POST /register
    public function register(): void {
        $input = $this->readJson();

        $data = [
            'username' => $this->str($input, 'username'),
            'email'    => $this->str($input, 'email'),
            // password tidak di-trim: spasi di awal/akhir bisa jadi bagian dari kata sandi
            'password' => isset($input['password']) && is_scalar($input['password']) ? (string) $input['password'] : '',
        ];

        // Tahap 1: field wajib kosong -> HTTP 400
        $missing = [];
        foreach (['username', 'email', 'password'] as $field) {
            if ($data[$field] === '') {
                $missing[$field] = "Field $field wajib diisi";
            }
        }
        if ($missing) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $missing);
        }

        // Tahap 2: batas panjang sesuai kolom tabel -> HTTP 422
        $errors = [];
        if ($this->len($data['username']) > 50) {
            $errors['username'] = 'Username maksimal 50 karakter';
        }
        if ($this->len($data['email']) > 100) {
            $errors['email'] = 'Email maksimal 100 karakter';
        }
        if ($errors) {
            Response::json(422, false, 'Validasi data gagal', null, $errors);
        }

        // Kata sandi disimpan sebagai hash, tidak pernah plain text
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, 'Validasi data gagal', null, ['email' => $e->getMessage()]);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        }

        // Hash tidak ikut dikirim ke client
        Response::json(201, true, 'Registrasi berhasil', ['id' => (int) $id]);
    }
}