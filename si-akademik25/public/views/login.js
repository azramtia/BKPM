const BASE_URL = 'http://localhost/BKPM/si-akademik25/public';

const form = document.querySelector('#form-login');
const status = document.querySelector('#status');
const tombol = document.querySelector('#btn-login');

function tampilkanStatus(teks) {
    status.textContent = teks;
    status.className = 'status galat';
}

// Pesan dari dashboard (misalnya sesi berakhir) ditampilkan sekali.
const pesanSesi = sessionStorage.getItem('pesan');
if (pesanSesi) {
    tampilkanStatus(pesanSesi);
    sessionStorage.removeItem('pesan');
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    status.textContent = '';
    tombol.disabled = true;

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                username: form.username.value,
                password: form.password.value,
            }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            tampilkanStatus(hasil.message);
        }
    } catch (error) {
        console.error('Gagal login:', error);
        tampilkanStatus('Tidak dapat terhubung ke API. Pastikan Apache dan MySQL di Laragon sudah berjalan.');
    } finally {
        tombol.disabled = false;
    }
});
