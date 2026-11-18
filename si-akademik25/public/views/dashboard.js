const BASE_URL = 'http://localhost/BKPM/si-akademik25/public';

const token = localStorage.getItem('token');
const role = localStorage.getItem('role');
const adalahAdmin = role === 'admin';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const status = document.querySelector('#status');
const form = document.querySelector('#form-mahasiswa');
const judulForm = document.querySelector('#judul-form');
const tombolSimpan = document.querySelector('#btn-simpan');
const tombolBatal = document.querySelector('#btn-batal');

// Langkah 4: lindungi halaman. Tanpa token, kembali ke login.
if (!token) {
    window.location.replace('login.html');
} else {
    mulai();
}

function mulai() {
    document.querySelector('#info-peran').textContent = adalahAdmin
        ? 'Masuk sebagai admin'
        : 'Masuk sebagai user. Akun ini hanya dapat melihat data.';

    // Tugas Mandiri 2: sembunyikan Tambah/Ubah/Hapus untuk role user.
    // Ini hanya pelengkap tampilan; yang melindungi data tetap Auth::requireAdmin().
    if (!adalahAdmin) {
        document.querySelectorAll('.khusus-admin').forEach((el) => { el.hidden = true; });
    }

    // Tugas Mandiri 1: Logout menghapus token lalu kembali ke login.
    document.querySelector('#btn-logout').addEventListener('click', () => keluar());
    form.addEventListener('submit', simpanMahasiswa);
    tombolBatal.addEventListener('click', resetForm);

    muatData();
}

function keluar(pesan) {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    if (pesan) {
        sessionStorage.setItem('pesan', pesan);
    }
    window.location.replace('login.html');
}

// Semua request membawa token. Token ditolak (401) berarti sesi berakhir.
async function panggilApi(path, opsi = {}) {
    const response = await fetch(`${BASE_URL}${path}`, {
        ...opsi,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
    });
    const hasil = await response.json();

    if (response.status === 401) {
        keluar('Sesi berakhir, silakan login lagi.');
    }
    return { response, hasil };
}

function tampilkanStatus(teks, jenis, daftarError) {
    status.textContent = teks;
    status.className = 'status ' + (jenis || '');
    if (daftarError) {
        const ul = document.createElement('ul');
        Object.values(daftarError).forEach((pesan) => {
            const li = document.createElement('li');
            li.textContent = pesan;
            ul.appendChild(li);
        });
        status.appendChild(ul);
    }
}

function tampilkanPesanTabel(teks, kelas) {
    tbody.innerHTML = '';
    const baris = document.createElement('tr');
    const sel = document.createElement('td');
    sel.colSpan = adalahAdmin ? 4 : 3;
    sel.className = 'pesan ' + (kelas || '');
    sel.textContent = teks;
    baris.appendChild(sel);
    tbody.appendChild(baris);
}

// Langkah 5: ringkasan memakai hasil GET /mahasiswa (MahasiswaController::index)
async function muatData() {
    tampilkanPesanTabel('Memuat data...', 'memuat');

    try {
        const { response, hasil } = await panggilApi('/mahasiswa');

        if (!response.ok || !hasil.success) {
            tampilkanPesanTabel(hasil.message || 'Gagal memuat data mahasiswa.', 'galat');
            return;
        }

        document.querySelector('#total-mahasiswa').textContent = hasil.data.length;

        if (hasil.data.length === 0) {
            tampilkanPesanTabel('Belum ada data', 'kosong');
            return;
        }

        tbody.innerHTML = '';
        hasil.data.forEach((mhs) => tbody.appendChild(buatBaris(mhs)));
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesanTabel(
            'Tidak dapat terhubung ke API. Pastikan Apache dan MySQL di Laragon sudah berjalan.',
            'galat'
        );
    }
}

function buatBaris(mhs) {
    const baris = document.createElement('tr');

    [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => {
        const sel = document.createElement('td');
        sel.textContent = nilai; // textContent: HTML dari data tidak ikut dieksekusi
        baris.appendChild(sel);
    });

    if (adalahAdmin) {
        const sel = document.createElement('td');
        sel.className = 'aksi';

        const ubah = document.createElement('button');
        ubah.type = 'button';
        ubah.className = 'sekunder kecil';
        ubah.textContent = 'Ubah';
        ubah.addEventListener('click', () => isiFormUbah(mhs));

        const hapus = document.createElement('button');
        hapus.type = 'button';
        hapus.className = 'bahaya kecil';
        hapus.textContent = 'Hapus';
        hapus.addEventListener('click', () => hapusMahasiswa(mhs));

        sel.append(ubah, hapus);
        baris.appendChild(sel);
    }
    return baris;
}

function isiFormUbah(mhs) {
    document.querySelector('#id-mahasiswa').value = mhs.id;
    document.querySelector('#nim').value = mhs.nim;
    document.querySelector('#nama').value = mhs.nama;
    document.querySelector('#prodi').value = mhs.prodi;
    document.querySelector('#email').value = mhs.email || '';
    judulForm.textContent = 'Ubah mahasiswa';
    tombolBatal.hidden = false;
    tampilkanStatus('');
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function resetForm() {
    form.reset();
    document.querySelector('#id-mahasiswa').value = '';
    judulForm.textContent = 'Tambah mahasiswa';
    tombolBatal.hidden = true;
}

async function simpanMahasiswa(e) {
    e.preventDefault();
    tampilkanStatus('');
    tombolSimpan.disabled = true;

    const id = document.querySelector('#id-mahasiswa').value;
    const data = {
        nim: document.querySelector('#nim').value,
        nama: document.querySelector('#nama').value,
        prodi: document.querySelector('#prodi').value,
        email: document.querySelector('#email').value,
    };

    try {
        // Ada id berarti ubah (PUT), tidak ada berarti tambah (POST)
        const { response, hasil } = await panggilApi(
            id ? `/mahasiswa?id=${encodeURIComponent(id)}` : '/mahasiswa',
            { method: id ? 'PUT' : 'POST', body: JSON.stringify(data) }
        );

        if (response.ok && hasil.success) {
            tampilkanStatus(hasil.message, 'ok');
            resetForm();
            muatData();
        } else {
            // 403 dari Auth::requireAdmin() juga ditampilkan di sini
            tampilkanStatus(hasil.message, 'galat', hasil.errors);
        }
    } catch (error) {
        console.error('Gagal menyimpan:', error);
        tampilkanStatus('Tidak dapat terhubung ke API.', 'galat');
    } finally {
        tombolSimpan.disabled = false;
    }
}

async function hapusMahasiswa(mhs) {
    if (!window.confirm(`Hapus data ${mhs.nama} (NIM ${mhs.nim})?`)) {
        return;
    }
    tampilkanStatus('');

    try {
        const { response, hasil } = await panggilApi(
            `/mahasiswa?id=${encodeURIComponent(mhs.id)}`,
            { method: 'DELETE' }
        );

        if (response.ok && hasil.success) {
            tampilkanStatus(hasil.message, 'ok');
            muatData();
        } else {
            tampilkanStatus(hasil.message, 'galat');
        }
    } catch (error) {
        console.error('Gagal menghapus:', error);
        tampilkanStatus('Tidak dapat terhubung ke API.', 'galat');
    }
}
