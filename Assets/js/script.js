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
    const modal = document.getElementById('EditTVShowModal'); // Corrected: Use the new ID for the modal
    modal.classList.toggle('hidden');

    try {
        // Fetch data TV show dari server
        const response = await fetch(`get_tvshow.php?id=${id}`); // Fetch from get_tvshow.php
        const tvshow = await response.json();

        // Isi form dengan data tv show
        document.getElementById('editTvShowId').value = tvshow.id; // Set the hidden ID field
        document.getElementById('editTvShowTitle').value = tvshow.title; // Corrected ID
        document.getElementById('editTvShowTagline').value = tvshow.tagline; // Corrected ID
        document.getElementById('editTvShowFirstAirDate').value = tvshow.first_air_date; // Corrected ID
        document.getElementById('editTvShowStatus').value = tvshow.status; // Corrected ID
        document.getElementById('editTvShowTotalEpisodes').value = tvshow.total_episodes; // Corrected ID
        document.getElementById('editTvShowTotalSeasons').value = tvshow.total_seasons; // Corrected ID
        document.getElementById('editTvShowHomepage').value = tvshow.homepage; // Corrected ID
        document.getElementById('editTvShowPoster').value = tvshow.poster_path; // Corrected ID
        document.getElementById('editTvShowOverview').value = tvshow.overview; // Corrected ID

        // Centang genre yang sesuai
        const genreCheckboxes = document.querySelectorAll('#EditTVShowModal input[name="genres[]"]'); // Select within the correct modal
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

function closeEditModal() { // This function is for movie edit modal, keep it as is
    document.getElementById('editModal').classList.add('hidden');
}

function toggleModal() { // This function seems to be for adding new TV show modal
    const modal = document.getElementById('tvshowmodal');
    modal.classList.toggle('hidden');
}

function closeModal() { // This function seems to be for closing new TV show modal
    document.getElementById('tvshowmodal').classList.add('hidden');
}

