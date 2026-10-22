<?php
class MahasiswaController extends BaseController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // GET /mahasiswa            -> semua data
    // GET /mahasiswa?id=1       -> satu data (juga bisa /mahasiswa/1)
    public function index($id = null): void {
        if ($id !== null && $id !== '') {
            $data = $this->model->find($this->requireId($id));
            if (!$data) {
                $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
            }
            $this->send(200, ['success' => true, 'data' => $data]);
        }

        $this->send(200, ['success' => true, 'data' => $this->model->getAll()]);
    }

    // POST /mahasiswa
    public function store(): void {
        $data = $this->clean($this->readJson());

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            $this->send(409, ['success' => false, 'message' => $e->getMessage()]);
        }

        $this->send(201, [
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => ['id' => (int) $id],
        ]);
    }

    // PUT /mahasiswa?id=1
    public function update($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $data = $this->clean($this->readJson());

        try {
            $this->model->update($id, $data);
        } catch (DomainException $e) {
            $this->send(409, ['success' => false, 'message' => $e->getMessage()]);
        }

        $this->send(200, ['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    // DELETE /mahasiswa?id=1
    public function destroy($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->model->delete($id);
        $this->send(200, ['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    /** Validasi field wajib dan panjang maksimal sesuai kolom tabel. */
    private function clean(array $input): array {
        $data = [
            'nim'   => $this->str($input, 'nim'),
            'nama'  => $this->str($input, 'nama'),
            'prodi' => $this->str($input, 'prodi'),
        ];

        if ($data['nim'] === '' || $data['nama'] === '' || $data['prodi'] === '') {
            $this->send(400, ['success' => false, 'message' => 'Field nim, nama, dan prodi wajib diisi']);
        }
        if ($this->len($data['nim']) > 15 || $this->len($data['nama']) > 100 || $this->len($data['prodi']) > 50) {
            $this->send(400, ['success' => false, 'message' => 'Panjang maksimal: nim 15, nama 100, prodi 50 karakter']);
        }

        return $data;
    }
}
