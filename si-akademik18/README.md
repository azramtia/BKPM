# Acara 18 — JSON Encoding dan HTTP Status Code (PHP Native, MVC)

## Cara menjalankan
1. Letakkan folder `sistem-informasi-akademik` di `htdocs` (XAMPP) atau `www` (Laragon). Aktifkan Apache + MySQL, dan pastikan `mod_rewrite` aktif.
2. Buka phpMyAdmin, lalu import `database/db_workshop.sql`.
3. Uji di Postman dengan alamat dasar:
   `http://localhost/sistem-informasi-akademik/public`
   (sesuaikan jika folder berada di subfolder lain, misalnya `/BKPM/sistem-informasi-akademik/public`)

## Alur
Client → `public/index.php` (Front Controller) → `Router` → `Controller` → `Model` → MySQL → `Response::json()` → JSON

## Dokumentasi endpoint

### Mahasiswa — `/mahasiswa`
| Method | Alamat | Body (JSON) | Response sukses | Response gagal |
|---|---|---|---|---|
| GET | `/mahasiswa` | - | 200, `data` berisi semua mahasiswa | - |
| GET | `/mahasiswa?id=1` | - | 200, `data` satu mahasiswa | 404 tidak ditemukan, 400 ID tidak valid |
| POST | `/mahasiswa` | `{"nim":"220100123","nama":"Andi Saputra","prodi":"Teknik Informatika"}` | 201, `data.id` | 400 field kosong / JSON salah, 422 format NIM (harus 9 digit) / panjang / email, 409 NIM duplikat |
| PUT | `/mahasiswa?id=1` | sama seperti POST | 200 berhasil diperbarui | 400, 404, 422, 409 NIM dipakai mahasiswa lain |
| DELETE | `/mahasiswa?id=1` | - | 200 berhasil dihapus | 400, 404 |

### Buku — `/buku`
| Method | Alamat | Body (JSON) | Response sukses |
|---|---|---|---|
| GET | `/buku`, `/buku?id=1` | - | 200 |
| POST | `/buku` | `{"judul":"Pemrograman Web","penulis":"Ahmad","tahun_terbit":2024}` | 201 (400 field kosong, 422 validasi gagal) |
| PUT | `/buku?id=1` | sama seperti POST | 200 |
| DELETE | `/buku?id=1` | - | 200 |

`tahun_terbit` boleh dikosongkan. ID juga bisa dikirim lewat path, misalnya `/mahasiswa/1`.

### Format response (envelope seragam)
Semua response — dari Router, Controller, maupun Database — dikirim lewat `Response::json()`.

```json
{ "success": true,  "message": "Data berhasil ditambahkan", "data": { "id": 1 }, "timestamp": "2026-10-24T16:00:37+00:00" }
{ "success": false, "message": "NIM sudah terdaftar", "data": null, "timestamp": "2026-10-24T16:00:37+00:00" }
{ "success": false, "message": "Validasi data gagal", "data": null,
  "errors": { "nim": "Format NIM tidak valid, harus 9 digit angka" }, "timestamp": "2026-10-24T16:00:37+00:00" }
```
Field `errors` hanya muncul pada kegagalan validasi (400 field kosong dan 422).

### Kode status yang dipakai
| Kode | Dipakai untuk |
|---|---|
| 200 | GET / PUT / DELETE berhasil |
| 201 | POST berhasil membuat data |
| 400 | Body kosong, JSON rusak, field wajib kosong, ID tidak valid |
| 404 | Data atau endpoint tidak ditemukan |
| 405 | Method HTTP tidak diizinkan |
| 409 | Konflik data (NIM duplikat) |
| 422 | Data lengkap tetapi gagal validasi (format NIM, panjang, email, tahun_terbit) |
| 500 | Kegagalan server / koneksi database |

### Mode pengembangan
`app/config/app.php` berisi konstanta `APP_ENV`. Nilai `development` membuat JSON dicetak rapi (`JSON_PRETTY_PRINT`), nilai `production` membuat JSON ringkas.

## Struktur
```
app/config/Database.php        Koneksi PDO (Singleton)
app/core/autoload.php          Autoloader (spl_autoload_register)
app/core/Router.php            Memetakan URL + method HTTP ke Controller
app/core/Response.php          Response::json() — envelope JSON + kode status HTTP
app/core/BaseController.php    Helper baca body JSON, validasi ID, utilitas string
app/config/app.php             Konstanta APP_ENV (development / production)
app/models/Mahasiswa.php       Query tabel mahasiswa + validasi NIM duplikat
app/models/Buku.php            Query tabel buku (Tugas Mandiri 1)
app/controllers/...            Logika endpoint
public/index.php, .htaccess    Front Controller
database/db_workshop.sql       Skrip database
```
