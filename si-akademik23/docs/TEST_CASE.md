# Laporan Pengujian Manual (Blackbox Testing) API Sistem Informasi Akademik

## 1. Informasi Pengujian

| Item | Keterangan |
|------|------------|
| Metode | Blackbox testing, teknik Equivalence Partitioning |
| Jenis pengujian | Manual testing menggunakan Postman |
| Objek uji | `AuthController` dan `MahasiswaController` |
| Lingkungan | Laragon (Apache, PHP, MySQL), database `workshop` |
| Base URL | `http://localhost/BKPM/si-akademik23/public` (sesuaikan dengan alamat di Postman) |
| Tanggal pengujian | `<isi tanggal>` |
| Penguji | `<isi nama>` |

## 2. Ruang Lingkup

Berdasarkan peta Router, endpoint yang diuji:

| Endpoint | Method | Controller::Method |
|----------|--------|--------------------|
| `/register` | POST | `AuthController::register()` |
| `/login` | POST | `AuthController::login()` |
| `/mahasiswa` | GET, POST, PUT, DELETE | `MahasiswaController` |

## 3. Persiapan dan Urutan Eksekusi

1. Import `database/workshop.sql`, lalu jalankan Laragon (Apache dan MySQL).
2. Jalankan **TC-19** (register) lebih dulu agar ada akun uji `penguji`, lalu jalankan **TC-04** untuk mendapatkan token. Token berlaku 300 detik, jadi login ulang bila sudah kedaluwarsa.
3. Pada request terproteksi, tambahkan header `Authorization: Bearer <token>`.
4. Jalankan **TC-01** dan catat `data.id` dari response. Nilai tersebut dipakai sebagai `{id}` pada TC-07 sampai TC-16.
5. Jalankan test case berurutan sesuai nomor. TC-14 menghapus data hasil TC-01, sehingga TC-16 (hapus ulang) diharapkan 404.

## 4. Daftar Test Case

Kolom **Hasil Aktual** dan **Status** diisi setelah pengujian dijalankan di Postman.

### A. Test case dari modul (Auth dan Mahasiswa)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|----------|------------------|--------------|--------|
| TC-01 | NIM valid dan lengkap (`MahasiswaController::store`) | Token valid. Body: `nim=220100123`, `nama=Andi Saputra`, `prodi=Teknik Informatika`, `email=andi@mail.com` | 201 Created, `data.id` terisi | | |
| TC-02 | NIM kosong (partisi tidak valid) | Token valid. Body: `nim=""`, nama dan prodi terisi | 422 Unprocessable Entity (modul: 400/422), `errors.nim` terisi | | |
| TC-03 | NIM duplikat (partisi tidak valid) | Token valid. Body: `nim=123456789` (milik data lain) | 409 Conflict, pesan `NIM sudah terdaftar` (lihat catatan 1) | | |
| TC-04 | Login kredensial benar (`AuthController::login`) | `username=penguji`, `password=rahasia123` | 200 OK, `data.token` diterima, `expires_in=300` | | |
| TC-05 | Login kredensial salah | `username=penguji`, `password=salah123` | 401 Unauthorized, pesan `Username atau password salah` | | |
| TC-06 | Akses store tanpa token (`Auth::requireAuth`) | Tanpa header Authorization. Body sama seperti TC-01 | 401 Unauthorized, pesan `Token tidak ditemukan` | | |

### B. Test case tambahan: `MahasiswaController::update` (PUT)

| ID | Skenario | Partisi | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|---------|----------|------------------|--------------|--------|
| TC-07 | Update data valid | Valid | `PUT ?id={id}`, token valid. Body: `nim=220100123`, `nama=Andi Saputra Wijaya`, `prodi=Sistem Informasi`, `email=andi.wijaya@mail.com` | 200 OK, pesan `Data berhasil diperbarui` | | |
| TC-08 | Update tanpa token | Tidak valid (autentikasi) | `PUT ?id={id}`, tanpa header Authorization. Body sama seperti TC-07 | 401 Unauthorized | | |
| TC-09 | Update ID yang tidak ada | Tidak valid (ID) | `PUT ?id=99999`, token valid. Body sama seperti TC-07 | 404 Not Found, pesan `Data tidak ditemukan` | | |
| TC-10 | Update dengan NIM mengandung huruf | Tidak valid (format NIM) | `PUT ?id={id}`, token valid. Body: `nim=22010012A`, nama dan prodi terisi | 422 Unprocessable Entity, `errors.nim` terisi | | |
| TC-11 | Update dengan NIM milik mahasiswa lain | Tidak valid (duplikat) | `PUT ?id={id}`, token valid. Body: `nim=123456789`, nama dan prodi terisi | 409 Conflict, pesan `NIM sudah digunakan mahasiswa lain` | | |
| TC-12 | Update dengan ID bukan angka | Tidak valid (ID) | `PUT ?id=abc`, token valid. Body sama seperti TC-07 | 400 Bad Request | | |
| TC-13 | Update dengan nama kosong | Tidak valid (field wajib) | `PUT ?id={id}`, token valid. Body: `nama=""`, nim dan prodi valid | 422 Unprocessable Entity, `errors.nama` terisi | | |

### C. Test case tambahan: `MahasiswaController::destroy` (DELETE)

| ID | Skenario | Partisi | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|---------|----------|------------------|--------------|--------|
| TC-14 | Hapus data yang ada | Valid | `DELETE ?id={id}`, token valid | 200 OK, pesan `Data berhasil dihapus`. Verifikasi: `GET ?id={id}` menghasilkan 404 | | |
| TC-15 | Hapus tanpa token | Tidak valid (autentikasi) | `DELETE ?id={id}`, tanpa header Authorization | 401 Unauthorized | | |
| TC-16 | Hapus data yang sudah tidak ada | Tidak valid (ID) | `DELETE ?id={id}` (ID yang sama dengan TC-14), token valid | 404 Not Found, pesan `Data tidak ditemukan` | | |
| TC-17 | Hapus dengan ID tidak valid | Tidak valid (ID) | `DELETE ?id=0`, token valid | 400 Bad Request | | |
| TC-18 | Hapus dengan token salah | Tidak valid (token) | `DELETE ?id={id}`, header `Authorization: Bearer abc.def.ghi` | 401 Unauthorized, pesan `Token tidak valid atau kedaluwarsa` | | |

### D. Test case tambahan: `AuthController::register`

| ID | Skenario | Partisi | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|---------|----------|------------------|--------------|--------|
| TC-19 | Register data valid | Valid | `username=penguji`, `email=penguji@mail.com`, `password=rahasia123` | 201 Created, `data.id` terisi | | |
| TC-20 | Register dengan username sudah dipakai | Tidak valid (duplikat) | `username=penguji`, `email=lain@mail.com`, `password=rahasia123` | 409 Conflict | | |
| TC-21 | Register dengan field kosong | Tidak valid (field wajib) | `username=""`, email dan password terisi | 400 Bad Request, `errors.username` terisi | | |

## 5. Ringkasan Hasil

| Keterangan | Jumlah |
|------------|--------|
| Total test case | 21 |
| Pass | |
| Fail | |

## 6. Catatan

1. **TC-03 dan kode status duplikat.** Modul mencantumkan 422 untuk NIM duplikat, sedangkan API ini mengembalikan 409 Conflict. Kode 409 dipilih karena menggambarkan konflik data yang sudah ada, dan sudah tercantum pada dokumentasi API serta `openapi.yaml`. Jika yang dinilai adalah kesesuaian dengan modul, ubah `MahasiswaController` agar mengembalikan 422 pada `catch (DomainException)`, perbarui dokumentasi, lalu uji ulang.
2. **Test case berstatus Fail.** Catat perbaikan yang dilakukan pada bagian 7, lalu jalankan ulang test case terkait.

## 7. Perbaikan dan Retest

| ID | Penyebab Fail | Perbaikan (Model/Controller) | Hasil Retest |
|----|---------------|------------------------------|--------------|
| | | | |
