
<?php

require_once __DIR__ . '/../Models/MahasiswaRepository.php';
require_once __DIR__ . '/../Core/Logger.php';

class MahasiswaService
{
    private $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    private function validate($data, $excludeId = null)
    {
        $errors = [];

        $nim = trim((string) ($data['nim'] ?? ''));
        $nama = trim((string) ($data['nama'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $status = strtolower(trim((string) ($data['status'] ?? 'aktif'))) ?: 'aktif';

        // Validasi NIM
        if ($nim === '') {
            $errors[] = 'NIM wajib diisi.';
        } elseif (!ctype_digit($nim)) {
            $errors[] = 'NIM harus berupa angka.';
        } elseif ($this->repository->nimExists($nim, $excludeId)) {
            $errors[] = 'NIM sudah digunakan mahasiswa lain.';
        }

        // Validasi nama
        if ($nama === '') {
            $errors[] = 'Nama mahasiswa wajib diisi.';
        } elseif (mb_strlen($nama) > 100) {
            $errors[] = 'Nama mahasiswa maksimal 100 karakter.';
        }

        // Validasi email (opsional)
        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            } elseif (strlen($email) > 150) {
                $errors[] = 'Email maksimal 150 karakter.';
            }
        }

        // Validasi status
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            $errors[] = 'Status mahasiswa tidak valid.';
        }

        // Validasi program studi
        $prodiId = $data['prodi_id'] ?? null;

        if ($prodiId !== null && $prodiId !== '') {
            if (
                !ctype_digit((string) $prodiId) ||
                (int) $prodiId <= 0 ||
                !$this->repository->prodiExists((int) $prodiId)
            ) {
                $errors[] = 'Program studi tidak valid.';
            }
        } else {
            $namaProdi = trim((string) ($data['prodi'] ?? ''));
            $prodiValid = false;

            foreach ($this->repository->prodiList() as $prodi) {
                if (
                    ($prodi['nama'] ?? '') === $namaProdi &&
                    $namaProdi !== ''
                ) {
                    $prodiValid = true;
                    break;
                }
            }

            if (!$prodiValid) {
                $errors[] = 'Program studi wajib dipilih.';
            }
        }

        // Validasi angkatan
        $angkatan = trim((string) ($data['angkatan'] ?? ''));

        if (
            $angkatan === '' ||
            !ctype_digit($angkatan) ||
            (int) $angkatan < 1901 ||
            (int) $angkatan > 2155
        ) {
            $errors[] = 'Angkatan wajib diisi dengan tahun yang valid.';
        }

        // Validasi dosen pembimbing
        $dosenId = trim((string) ($data['dosen_id'] ?? ''));

        // Dosen pembimbing bersifat opsional sesuai struktur database.
        if ($dosenId !== '') {
            if (!ctype_digit($dosenId) || (int) $dosenId <= 0) {
                $errors[] = 'Dosen ID tidak valid.';
            } elseif (!$this->repository->dosenExists((int) $dosenId)) {
                $errors[] = 'Dosen yang dipilih tidak terdaftar.';
            }
        }

        return $errors;
    }

    // Menambahkan mahasiswa
    public function create($data)
    {
        try {
            $errors = $this->validate($data);

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            $success = $this->repository->create($data);

            return [
                'success' => $success,
                'errors' => $success
                    ? []
                    : ['Data mahasiswa gagal disimpan.']
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            return [
                'success' => false,
                'errors' => [
                    'Data gagal disimpan. Periksa kembali data yang dimasukkan.'
                ]
            ];
        }
    }

    // Memperbarui mahasiswa
    public function update($id, $data)
    {
        try {
            $id = (int) $id;

            if ($id <= 0 || !$this->repository->find($id)) {
                return [
                    'success' => false,
                    'errors' => ['Data mahasiswa tidak ditemukan.']
                ];
            }

            $errors = $this->validate($data, $id);

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            $success = $this->repository->update($id, $data);

            return [
                'success' => $success,
                'errors' => $success
                    ? []
                    : ['Data mahasiswa gagal diperbarui.']
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            return [
                'success' => false,
                'errors' => ['Data mahasiswa gagal diperbarui.']
            ];
        }
    }

    // Menghapus mahasiswa
    public function delete($id)
    {
        try {
            $id = (int) $id;

            if ($id <= 0 || !$this->repository->find($id)) {
                return [
                    'success' => false,
                    'errors' => ['Data mahasiswa tidak ditemukan.']
                ];
            }

            $success = $this->repository->delete($id);

            return [
                'success' => $success,
                'errors' => $success
                    ? []
                    : ['Data mahasiswa gagal dihapus.']
            ];
        } catch (Throwable $e) {
            Logger::error($e);

            return [
                'success' => false,
                'errors' => ['Data mahasiswa gagal dihapus.']
            ];
        }
    }
}
