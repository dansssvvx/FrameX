'use strict';

/**
 * navbar variables
 */

const navOpenBtn = document.querySelector("[data-menu-open-btn]");
const navCloseBtn = document.querySelector("[data-menu-close-btn]");
const navbar = document.querySelector("[data-navbar]");
const overlay = document.querySelector("[data-overlay]");

const navElemArr = [navOpenBtn, navCloseBtn, overlay];

for (let i = 0; i < navElemArr.length; i++) {

  navElemArr[i].addEventListener("click", function () {

    navbar.classList.toggle("active");
    overlay.classList.toggle("active");
    document.body.classList.toggle("active");

  });

}



/**
 * header sticky
 */

const header = document.querySelector("[data-header]");

window.addEventListener("scroll", function () {

  window.scrollY >= 10 ? header.classList.add("active") : header.classList.remove("active");

});



/**
 * go top
 */

const goTopBtn = document.querySelector("[data-go-top]");

window.addEventListener("scroll", function () {

  window.scrollY >= 500 ? goTopBtn.classList.add("active") : goTopBtn.classList.remove("active");

});


document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("addMovieBtn").addEventListener("click", toggleModal);
});

async function editModal(id) {
    const modal = document.getElementById('editModal');
    modal.classList.toggle('hidden');

    try {
        // Fetch data movie dari server
        const response = await fetch(`get_movie.php?id=${id}`);
        const movie = await response.json();

        // Isi form dengan data movie
        document.getElementById('editMovieId').value = movie.id;
        document.querySelector('#editModal input[name="title"]').value = movie.title;
        document.querySelector('#editModal input[name="tagline"]').value = movie.tagline;
        document.querySelector('#editModal input[name="release_date"]').value = movie.release_date;
        document.querySelector('#editModal select[name="status"]').value = movie.status;
        document.querySelector('#editModal input[name="revenue"]').value = movie.revenue;
        document.querySelector('#editModal input[name="homepage"]').value = movie.homepage;
        document.querySelector('#editModal input[name="poster"]').value = movie.poster_path;
        document.querySelector('#editModal textarea[name="overview"]').value = movie.overview;

        // Centang genre yang sesuai
        const genreCheckboxes = document.querySelectorAll('#editModal input[name="genres[]"]');
        genreCheckboxes.forEach(checkbox => {
            checkbox.checked = movie.genres.includes(parseInt(checkbox.value));
        });
    } catch (error) {
        console.error("Error fetching movie data:", error);
        alert("Gagal memuat data movie.");
    }
}

async function editTvShowModal(id) {
    const modal = document.getElementById('editTvShowModal'); // Use the new ID
    modal.classList.toggle('hidden');

    try {
        // Fetch data TV show dari server
        const response = await fetch(`get_tvshow.php?id=${id}`); // Fetch from get_tvshow.php
        const tvshow = await response.json();

        // Isi form dengan data tv show
        document.getElementById('editTvShowId').value = tvshow.id; // New ID for hidden input
        document.querySelector('#editTvShowModal input[name="title"]').value = tvshow.title;
        document.querySelector('#editTvShowModal input[name="tagline"]').value = tvshow.tagline;
        document.querySelector('#editTvShowModal input[name="first_air_date"]').value = tvshow.first_air_date; // Use first_air_date
        document.querySelector('#editTvShowModal select[name="status"]').value = tvshow.status;
        document.querySelector('#editTvShowModal input[name="total_episodes"]').value = tvshow.total_episodes; // Use total_episodes
        document.querySelector('#editTvShowModal input[name="total_seasons"]').value = tvshow.total_seasons; // Use total_seasons
        document.querySelector('#editTvShowModal input[name="homepage"]').value = tvshow.homepage;
        document.querySelector('#editTvShowModal input[name="poster"]').value = tvshow.poster_path;
        document.querySelector('#editTvShowModal textarea[name="overview"]').value = tvshow.overview;

        // Centang genre yang sesuai
        const genreCheckboxes = document.querySelectorAll('#editTvShowModal input[name="genres[]"]');
        genreCheckboxes.forEach(checkbox => {
            checkbox.checked = tvshow.genres.includes(parseInt(checkbox.value));
        });
    } catch (error) {
        console.error("Error fetching TV show data:", error);
        alert("Gagal memuat data TV Show.");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const addUserBtn = document.getElementById("addUserBtn");
    if (addUserBtn) {
        addUserBtn.addEventListener("click", toggleAddUserModal);
    }
});

function toggleAddUserModal() {
    const modal = document.getElementById('addUserModal');
    modal.classList.toggle('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
}

function closeEditModal() {
    document.getElementById('editTvShowModal').classList.add('hidden'); // Change ID here
}
