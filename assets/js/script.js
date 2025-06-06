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

// Filter movies by category
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const category = this.getAttribute('data-filter');
        
        // Remove active class from all buttons
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        
        // Add active class to clicked button
        this.classList.add('active');
        
        // Filter movies
        const movies = document.querySelectorAll('#top-rated-movies li');
        movies.forEach(movie => {
            if (category === 'all' || movie.getAttribute('data-category') === category) {
                movie.style.display = 'block';
            } else {
                movie.style.display = 'none';
            }
        });
    });
});

document.querySelectorAll('[data-play-btn]').forEach(button => {
  button.addEventListener('click', function() {
    const trailerUrl = this.getAttribute('data-trailer-url');
    if (trailerUrl) {
      // Buka trailer di popup atau tab baru
      window.open(trailerUrl, '_blank');
      
      // Atau tampilkan dalam modal
      // showTrailerModal(trailerUrl);
    }
  });
});