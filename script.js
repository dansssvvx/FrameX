// script.js
document.addEventListener('DOMContentLoaded', () => {
    const addFilmBtn = document.getElementById('addFilmBtn');
    const filmTitleInput = document.getElementById('filmTitle');
    const releaseYearInput = document.getElementById('releaseYear');
    const directorInput = document.getElementById('director');
    const genreInput = document.getElementById('genre');
    const filmItemsList = document.getElementById('filmItems');
    const getStartedBtn = document.getElementById('getStartedBtn');

    // URL API PHP Anda
    const API_URL = 'http://localhost/frame_x/api.php';

    // Fungsi untuk memuat film dari database
    async function loadFilms() {
        try {
            const response = await fetch(API_URL);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const films = await response.json();
            filmItemsList.innerHTML = ''; // Kosongkan daftar sebelum menambahkan yang baru
            films.forEach(film => addFilmToDOM(film));
        } catch (error) {
            console.error('Gagal memuat film:', error);
            alert('Gagal memuat daftar film. Silakan coba lagi.');
        }
    }

    // Fungsi untuk menambahkan film ke DOM
    function addFilmToDOM(film) {
        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <span><strong>${film.title}</strong> (${film.release_year}) - Sutradara: ${film.director}, Genre: ${film.genre}</span>
            <button data-id="${film.id}">Hapus</button>
        `;
        filmItemsList.appendChild(listItem);
    }

    // Event listener untuk tombol 'Tambahkan Film'
    addFilmBtn.addEventListener('click', async () => {
        const title = filmTitleInput.value.trim();
        const year = releaseYearInput.value.trim();
        const director = directorInput.value.trim();
        const genre = genreInput.value.trim();

        if (title && year && director && genre) {
            const newFilm = { title: title, release_year: parseInt(year), director: director, genre: genre };

            try {
                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(newFilm)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.message === "Film berhasil ditambahkan") {
                    // Muat ulang daftar film untuk menampilkan film baru dengan ID dari database
                    loadFilms();
                    // Kosongkan input
                    filmTitleInput.value = '';
                    releaseYearInput.value = '';
                    directorInput.value = '';
                    genreInput.value = '';
                } else {
                    alert('Gagal menambahkan film: ' + result.message);
                }
            } catch (error) {
                console.error('Error saat menambahkan film:', error);
                alert('Terjadi kesalahan saat menambahkan film. Silakan coba lagi.');
            }
        } else {
            alert('Harap isi semua kolom!');
        }
    });

    // Event listener untuk tombol 'Hapus' pada daftar film
    filmItemsList.addEventListener('click', async (event) => {
        if (event.target.tagName === 'BUTTON' && event.target.textContent === 'Hapus') {
            const filmIdToRemove = event.target.dataset.id;
            if (!confirm('Apakah Anda yakin ingin menghapus film ini?')) {
                return; // Batalkan jika pengguna tidak yakin
            }

            try {
                const response = await fetch(API_URL, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: filmIdToRemove })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                if (result.message === "Film berhasil dihapus") {
                    // Hapus dari DOM
                    event.target.closest('li').remove();
                } else {
                    alert('Gagal menghapus film: ' + result.message);
                }
            } catch (error) {
                console.error('Error saat menghapus film:', error);
                alert('Terjadi kesalahan saat menghapus film. Silakan coba lagi.');
            }
        }
    });

    // Event listener untuk tombol "Mulai Analisis" di bagian hero
    getStartedBtn.addEventListener('click', () => {
        document.getElementById('analisis').scrollIntoView({ behavior: 'smooth' });
    });

    // Muat film saat halaman dimuat
    loadFilms();
});