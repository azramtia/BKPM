<?php
class BukuController extends BaseController {
    private Buku $model;

    public function __construct() {
        $this->model = new Buku();
    }

    public function index($id = null): void {
        if ($id !== null && $id !== '') {
            $data = $this->model->find($this->requireId($id));
            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }
            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void {
        $data = $this->clean($this->readJson());
        $id = $this->model->create($data);

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => (int) $id]);
    }

    public function update($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->update($id, $this->clean($this->readJson()));
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete($id);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    /** 400 jika field wajib kosong; 422 jika gagal validasi. */
    private function clean(array $input): array {
        $judul   = $this->str($input, 'judul');
        $penulis = $this->str($input, 'penulis');
        $tahun   = $this->str($input, 'tahun_terbit');

        $missing = [];
        if ($judul === '')   $missing['judul']   = 'Field judul wajib diisi';
        if ($penulis === '') $missing['penulis'] = 'Field penulis wajib diisi';
        if ($missing) {
            Response::json(400, false, 'Field judul dan penulis wajib diisi', null, $missing);
        }

        $errors = [];
        if ($this->len($judul) > 150)   $errors['judul']   = 'Judul maksimal 150 karakter';
        if ($this->len($penulis) > 100) $errors['penulis'] = 'Penulis maksimal 100 karakter';
        if ($tahun !== '' && (!ctype_digit($tahun) || (int) $tahun < 1000 || (int) $tahun > (int) date('Y') + 1)) {
            $errors['tahun_terbit'] = 'tahun_terbit tidak valid';
        }
        if ($errors) {
            Response::json(422, false, 'Validasi data gagal', null, $errors);
        }

        return ['judul' => $judul, 'penulis' => $penulis, 'tahun_terbit' => $tahun === '' ? null : (int) $tahun];
    }
}
