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
                $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
            }
            $this->send(200, ['success' => true, 'data' => $data]);
        }

        $this->send(200, ['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void {
        $data = $this->clean($this->readJson());
        $id = $this->model->create($data);

        $this->send(201, [
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => ['id' => (int) $id],
        ]);
    }

    public function update($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->model->update($id, $this->clean($this->readJson()));
        $this->send(200, ['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id = null): void {
        $id = $this->requireId($id);

        if (!$this->model->find($id)) {
            $this->send(404, ['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $this->model->delete($id);
        $this->send(200, ['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    private function clean(array $input): array {
        $judul   = $this->str($input, 'judul');
        $penulis = $this->str($input, 'penulis');
        $tahun   = $this->str($input, 'tahun_terbit');

        if ($judul === '' || $penulis === '') {
            $this->send(400, ['success' => false, 'message' => 'Field judul dan penulis wajib diisi']);
        }
        if ($this->len($judul) > 150 || $this->len($penulis) > 100) {
            $this->send(400, ['success' => false, 'message' => 'Panjang maksimal: judul 150, penulis 100 karakter']);
        }
        if ($tahun !== '' && (!ctype_digit($tahun) || (int) $tahun < 1000 || (int) $tahun > (int) date('Y') + 1)) {
            $this->send(400, ['success' => false, 'message' => 'tahun_terbit tidak valid']);
        }

        return ['judul' => $judul, 'penulis' => $penulis, 'tahun_terbit' => $tahun === '' ? null : (int) $tahun];
    }
}
