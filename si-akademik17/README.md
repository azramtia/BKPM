# Acara 17 — API CRUD (PHP Native, MVC)

## Cara menjalankan
1. Letakkan folder `sistem-informasi-akademik` di `htdocs` (XAMPP) atau `www` (Laragon). Aktifkan Apache + MySQL, dan pastikan `mod_rewrite` aktif.
2. Buka phpMyAdmin, lalu import `database/db_workshop.sql`.
3. Uji di Postman dengan alamat dasar:
   `http://localhost/sistem-informasi-akademik/public`
   (sesuaikan jika folder berada di subfolder lain, misalnya `/BKPM/sistem-informasi-akademik/public`)

## Alur
Client → `public/index.php` (Front Controller) → `Router` → `Controller` → `Model` → MySQL → JSON Response

## Dokumentasi endpoint

### Mahasiswa — `/mahasiswa`
| Method | Alamat | Body (JSON) | Response sukses | Response gagal |
|---|---|---|---|---|
| GET | `/mahasiswa` | - | 200, `data` berisi semua mahasiswa | - |
| GET | `/mahasiswa?id=1` | - | 200, `data` satu mahasiswa | 404 tidak ditemukan, 400 ID tidak valid |
| POST | `/mahasiswa` | `{"nim":"2201001","nama":"Andi Saputra","prodi":"Teknik Informatika"}` | 201, `data.id` | 400 field kosong / JSON salah, 409 NIM duplikat |
| PUT | `/mahasiswa?id=1` | sama seperti POST | 200 berhasil diperbarui | 400, 404, 409 NIM dipakai mahasiswa lain |
| DELETE | `/mahasiswa?id=1` | - | 200 berhasil dihapus | 400, 404 |

### Buku — `/buku`
| Method | Alamat | Body (JSON) | Response sukses |
|---|---|---|---|
| GET | `/buku`, `/buku?id=1` | - | 200 |
| POST | `/buku` | `{"judul":"Pemrograman Web","penulis":"Ahmad","tahun_terbit":2024}` | 201 |
| PUT | `/buku?id=1` | sama seperti POST | 200 |
| DELETE | `/buku?id=1` | - | 200 |

`tahun_terbit` boleh dikosongkan. ID juga bisa dikirim lewat path, misalnya `/mahasiswa/1`.

### Contoh response
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": 1 } }
{ "success": false, "message": "NIM sudah terdaftar" }
```

## Struktur
```
app/config/Database.php        Koneksi PDO (Singleton)
app/core/autoload.php          Autoloader (spl_autoload_register)
app/core/Router.php            Memetakan URL + method HTTP ke Controller
app/core/BaseController.php    Helper kirim response JSON dan baca body
app/models/Mahasiswa.php       Query tabel mahasiswa + validasi NIM duplikat
app/models/Buku.php            Query tabel buku (Tugas Mandiri 1)
app/controllers/...            Logika endpoint
public/index.php, .htaccess    Front Controller
database/db_workshop.sql       Skrip database
```
