const BASE_URL = 'http://localhost/BKPM/si-akademik25/public';

const tbody = document.querySelector('#tabel-mahasiswa tbody');

// Menampilkan satu baris pesan pada tabel (memuat, kosong, atau gagal).
function tampilkanPesan(teks, kelas) {
    tbody.innerHTML = '';
    const baris = document.createElement('tr');
    const sel = document.createElement('td');
    sel.colSpan = 3;
    sel.className = 'pesan ' + (kelas || '');
    sel.textContent = teks;
    baris.appendChild(sel);
    tbody.appendChild(baris);
}

async function muatDataMahasiswa() {
    // Tugas Mandiri 1: indikator sebelum data berhasil diambil
    tampilkanPesan('Memuat data...', 'memuat');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            tampilkanPesan(hasil.message || 'Gagal memuat data mahasiswa.', 'galat');
            return;
        }

        // Tugas Mandiri 2: kondisi data kosong
        if (hasil.data.length === 0) {
            tampilkanPesan('Belum ada data', 'kosong');
            return;
        }

        tbody.innerHTML = '';
        hasil.data.forEach((mhs) => {
            const baris = document.createElement('tr');
            [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => {
                const sel = document.createElement('td');
                sel.textContent = nilai; // textContent mencegah HTML dari data ikut dieksekusi
                baris.appendChild(sel);
            });
            tbody.appendChild(baris);
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan(
            'Tidak dapat terhubung ke API. Pastikan Apache dan MySQL di Laragon sudah berjalan.',
            'galat'
        );
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);