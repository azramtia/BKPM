# Dokumentasi API Sistem Informasi Akademik

REST API berbasis PHP Native (MVC) untuk pengelolaan data mahasiswa, buku, dan autentikasi pengguna.

- **Base URL:** `http://localhost/BKPM/si-akademik21/public`
- **Format data:** JSON (`Content-Type: application/json`)
- **Autentikasi:** JWT (HS256), berlaku 300 detik (5 menit)
- **Database:** `workshop` (tabel `mahasiswa`, `buku`, `users`)

---

## 1. Ringkasan Endpoint dan Peta Router

| No | Method | URL | Controller::Method | Keterangan | Autentikasi |
|----|--------|-----|--------------------|------------|-------------|
| 1 | POST | `/register` | `AuthController::register()` | Pendaftaran pengguna baru | Publik |
| 2 | POST | `/login` | `AuthController::login()` | Login, menghasilkan token JWT | Publik |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Ambil semua data mahasiswa | Publik |
| 4 | GET | `/mahasiswa?id={id}` | `MahasiswaController::index($id)` | Ambil satu mahasiswa | Publik |
| 5 | POST | `/mahasiswa` | `MahasiswaController::store()` | Tambah mahasiswa | **Bearer Token** |
| 6 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update($id)` | Ubah mahasiswa | **Bearer Token** |
| 7 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy($id)` | Hapus mahasiswa | **Bearer Token** |
| 8 | GET | `/buku`, `/buku?id={id}` | `BukuController::index()` | Ambil data buku | Publik |
| 9 | POST | `/buku` | `BukuController::store()` | Tambah buku | Publik |
| 10 | PUT | `/buku?id={id}` | `BukuController::update($id)` | Ubah buku | Publik |
| 11 | DELETE | `/buku?id={id}` | `BukuController::destroy($id)` | Hapus buku | Publik |

**Pemetaan resource pada `Router`:**

| Resource (URL) | Controller | Keterangan |
|----------------|------------|------------|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/buku` | `BukuController` | CRUD data buku |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |

**Alur permintaan:**
`Client -> public/index.php (Front Controller) -> Router -> Controller -> Model -> MySQL -> Response::json() -> JSON`

> ID dapat dikirim lewat query (`/mahasiswa?id=1`) atau path (`/mahasiswa/1`).

---

## 2. Format Response

**Sukses**
```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {},
  "timestamp": "2026-10-30T10:00:00+00:00"
}
```

**Gagal**
```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-30T10:00:00+00:00"
}
```

**Gagal validasi (422)** memiliki tambahan field `errors`:
```json
{
  "success": false,
  "message": "Validasi data gagal",
  "data": null,
  "errors": { "nim": "NIM hanya boleh mengandung angka dan harus tepat 9 digit, tidak boleh mengandung huruf" },
  "timestamp": "2026-10-30T10:00:00+00:00"
}
```

### Kode Status HTTP

| Kode | Arti | Dipakai untuk |
|------|------|---------------|
| 200 | OK | GET / PUT / DELETE / login berhasil |
| 201 | Created | POST berhasil membuat data |
| 400 | Bad Request | Body kosong, JSON rusak, ID tidak valid, field wajib kosong (auth, buku) |
| 401 | Unauthorized | Token tidak ada / tidak valid / kedaluwarsa, login gagal |
| 404 | Not Found | Data atau endpoint tidak ditemukan |
| 405 | Method Not Allowed | Method tidak diizinkan (mis. GET ke `/login`) |
| 409 | Conflict | Data duplikat (NIM, username, email) |
| 422 | Unprocessable Entity | Validasi gagal |
| 500 | Internal Server Error | Kegagalan server / koneksi database |

---

## 3. Autentikasi

### POST /register
**Controller:** `AuthController::register()` | **Autentikasi:** Publik

**Headers:** `Content-Type: application/json`

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| username | string | Ya | Maks. 50 karakter, unik |
| email | string | Ya | Format email valid, maks. 100 karakter, unik |
| password | string | Ya | Disimpan dengan `password_hash()` |

**Request Body:**
```json
{ "username": "andi", "email": "andi@mail.com", "password": "rahasia123" }
```

**Response 201 Created:**
```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": 1 }, "timestamp": "..." }
```

| Kode | Penyebab | message |
|------|----------|---------|
| 400 | Body kosong / JSON salah | `Body request kosong, ...` / `Format JSON tidak valid` |
| 400 | Field kosong | `Username, email, dan password wajib diisi` |
| 422 | Email tidak valid / melebihi panjang | `Validasi data gagal` |
| 409 | Username atau email sudah dipakai | `Username sudah digunakan` / `Email sudah terdaftar` |
| 405 | Method selain POST | `Method tidak diizinkan, endpoint register hanya menerima POST` |

---

### POST /login
**Controller:** `AuthController::login()` | **Autentikasi:** Publik

**Headers:** `Content-Type: application/json`

| Field | Tipe | Wajib |
|-------|------|-------|
| username | string | Ya |
| password | string | Ya |

**Request Body:**
```json
{ "username": "andi", "password": "rahasia123" }
```

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9....",
    "role": "user",
    "expires_in": 300
  },
  "timestamp": "..."
}
```

| Kode | Penyebab | message |
|------|----------|---------|
| 400 | Field kosong / JSON salah | `Username dan password wajib diisi` |
| 401 | Username atau password salah | `Username atau password salah` |
| 405 | Method selain POST | `Method tidak diizinkan, endpoint login hanya menerima POST` |

Gunakan token pada request terproteksi dengan header: `Authorization: Bearer <token>`

---

## 4. Endpoint Mahasiswa

**Controller:** `MahasiswaController`

| Field | Tipe | Wajib | Aturan |
|-------|------|-------|--------|
| nim | string | Ya | Tepat 9 digit angka, unik |
| nama | string | Ya | Maks. 100 karakter |
| prodi | string | Ya | Maks. 50 karakter |
| email | string | Tidak | Format email valid, maks. 100 karakter |

### GET /mahasiswa
**Controller:** `MahasiswaController::index()` | **Autentikasi:** Publik

**Response 200 OK:**
```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 7,
      "nim": "123456789",
      "nama": "Azira Mutia Gani",
      "email": "azira@gmail.com",
      "prodi": "Teknik Informatika",
      "created_at": "2026-10-25 12:23:12"
    }
  ],
  "timestamp": "..."
}
```

### GET /mahasiswa?id={id}
**Controller:** `MahasiswaController::index($id)` | **Autentikasi:** Publik

**Response 200 OK:** `data` berisi satu objek mahasiswa.

| Kode | Penyebab | message |
|------|----------|---------|
| 400 | ID bukan bilangan bulat positif | `ID tidak valid atau tidak disertakan, contoh: ?id=1` |
| 404 | ID tidak ada | `Data tidak ditemukan` |

### POST /mahasiswa
**Controller:** `MahasiswaController::store()` | **Autentikasi:** Bearer Token (`Auth::requireAuth()`)

**Headers:**
```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**
```json
{ "nim": "220100123", "nama": "Andi Saputra", "prodi": "Teknik Informatika", "email": "andi@mail.com" }
```

**Response 201 Created:**
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": 8 }, "timestamp": "..." }
```

| Kode | Penyebab | message |
|------|----------|---------|
| 400 | Body kosong / JSON salah | `Format JSON tidak valid` |
| 401 | Token tidak dikirim | `Token tidak ditemukan` |
| 401 | Token salah / kedaluwarsa | `Token tidak valid atau kedaluwarsa` |
| 422 | Field wajib kosong atau format salah (NIM, panjang, email) | `Validasi data gagal` |
| 409 | NIM sudah terdaftar | `NIM sudah terdaftar` |

### PUT /mahasiswa?id={id}
**Controller:** `MahasiswaController::update($id)` | **Autentikasi:** Bearer Token

**Headers dan Body:** sama seperti POST.

**Response 200 OK:**
```json
{ "success": true, "message": "Data berhasil diperbarui", "data": null, "timestamp": "..." }
```

| Kode | Penyebab |
|------|----------|
| 400 | ID tidak valid / JSON salah |
| 401 | Token tidak ada / tidak valid / kedaluwarsa |
| 404 | `Data tidak ditemukan` |
| 422 | Validasi gagal |
| 409 | `NIM sudah digunakan mahasiswa lain` |

### DELETE /mahasiswa?id={id}
**Controller:** `MahasiswaController::destroy($id)` | **Autentikasi:** Bearer Token

**Headers:** `Authorization: Bearer <token>`

**Response 200 OK:**
```json
{ "success": true, "message": "Data berhasil dihapus", "data": null, "timestamp": "..." }
```

| Kode | Penyebab |
|------|----------|
| 400 | ID tidak valid |
| 401 | Token tidak ada / tidak valid / kedaluwarsa |
| 404 | `Data tidak ditemukan` |

---

## 5. Endpoint Buku

**Controller:** `BukuController` | **Autentikasi:** Publik (tanpa token)

| Method | URL | Body | Response sukses | Response gagal |
|--------|-----|------|-----------------|----------------|
| GET | `/buku`, `/buku?id=1` | - | 200 | 400 ID tidak valid, 404 tidak ditemukan |
| POST | `/buku` | `{"judul":"Pemrograman Web","penulis":"Ahmad","tahun_terbit":2024}` | 201, `data.id` | 400 field kosong, 422 validasi gagal |
| PUT | `/buku?id=1` | sama seperti POST | 200 | 400, 404, 422 |
| DELETE | `/buku?id=1` | - | 200 | 400, 404 |

Aturan field: `judul` wajib (maks. 150), `penulis` wajib (maks. 100), `tahun_terbit` opsional (1000 sampai tahun depan).

---

## 6. Dokumentasi Postman

Collection Postman dari Acara 21 dilengkapi Description dan Examples pada tiap request.

Tautan View Documentation: `<tempel tautan di sini>`
