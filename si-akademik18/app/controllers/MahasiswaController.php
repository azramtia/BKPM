<?php

class MahasiswaController extends BaseController
{
    private const NIM_LENGTH = 9;

    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa atau GET /mahasiswa?id=1
    public function index($id = null): void
    {
        if ($id !== null && $id !== '') {
            $id = $this->requireId($id);
            $data = $this->model->find($id);

            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(
            200,
            true,
            'Data berhasil diambil',
            $this->model->getAll()
        );
    }

    // POST /mahasiswa
    public function store(): void
    {
        $data = $this->clean($this->readJson());

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        }

        Response::json(
            201,
            true,
            'Data berhasil ditambahkan',
            ['id' => (int) $id]
        );
    }

    // PUT /mahasiswa?id=1
    public function update($id = null): void
    {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->clean($this->readJson());

        try {
            $this->model->update($id, $data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        }

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    // DELETE /mahasiswa?id=1
    public function destroy($id = null): void
    {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);

        Response::json(200, true, 'Data berhasil dihapus');
    }

    // Membersihkan dan memvalidasi data
    private function clean(array $input): array
    {
        $data = [
            'nim'   => $this->str($input, 'nim'),
            'nama'  => $this->str($input, 'nama'),
            'email' => $this->str($input, 'email'),
            'prodi' => $this->str($input, 'prodi'),
        ];

        // Tahap 1: field wajib kosong -> HTTP 400
        $missing = [];

        foreach (['nim', 'nama', 'prodi'] as $field) {
            if ($data[$field] === '') {
                $missing[$field] = "Field $field wajib diisi";
            }
        }

        if (!empty($missing)) {
            Response::json(
                400,
                false,
                'Field nim, nama, dan prodi wajib diisi',
                null,
                $missing
            );
        }

        // Tahap 2: validasi format data -> HTTP 422
        $errors = [];

        // NIM hanya boleh berisi angka dan harus tepat 9 digit
        if (!preg_match('/^[0-9]{9}$/D', $data['nim'])) {
            $errors['nim'] =
                'NIM hanya boleh mengandung angka dan harus tepat 9 digit, tidak boleh mengandung huruf';
        }

        // Nama maksimal 100 karakter
        if ($this->len($data['nama']) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter';
        }

        // Prodi maksimal 50 karakter
        if ($this->len($data['prodi']) > 50) {
            $errors['prodi'] = 'Prodi maksimal 50 karakter';
        }

        // Email opsional, tetapi harus valid jika diisi
        if ($data['email'] !== '') {
            if (
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                $this->len($data['email']) > 100
            ) {
                $errors['email'] =
                    'Format email tidak valid atau melebihi 100 karakter';
            }
        } else {
            $data['email'] = null;
        }

        // Kirim seluruh kesalahan validasi
        if (!empty($errors)) {
            Response::json(
                422,
                false,
                'Validasi data gagal',
                null,
                $errors
            );
        }

        return $data;
    }
}
