<?php
// Koneksi database
require_once __DIR__ . '/config/database.php';

// Ambil data film untuk section "Top Rated"
$top_rated_query = "SELECT * FROM movies WHERE category = 'movie' ORDER BY rating DESC LIMIT 4";
$top_rated_movies = $db->query($top_rated_query)->fetchAll(PDO::FETCH_ASSOC);

// Ambil data film untuk section "Upcoming"
$anime_query = "SELECT * FROM movies WHERE category = 'anime' ORDER BY rating DESC LIMIT 4";
$anime_collection = $db->query($anime_query)->fetchAll(PDO::FETCH_ASSOC);

// Ambil data film untuk section "TV Series"
$tv_series_query = "SELECT * FROM movies WHERE category = 'tv_show' LIMIT 4";
$tv_series = $db->query($tv_series_query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Frame X</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="icon.png" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">

  <!-- 
    - #HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <div class="overlay" data-overlay></div>

      <a href="./index.php" class="logo">
        <img src="./assets/images/iconname.png" alt="Frame X logo" style="width: 100px; height: 43.2px;">
      </a>

      <div class="header-actions">

        <button class="search-btn">
          <ion-icon name="search-outline"></ion-icon>
        </button>

        <div class="lang-wrapper">
          <label for="language">
            <ion-icon name="globe-outline"></ion-icon>
          </label>

          <select name="language" id="language">
            <option value="en">EN</option>
            <option value="id">ID</option>
          </select>
        </div>

        <a href="login.html" class="btn btn-primary">Sign in</a>

      </div>

      <button class="menu-open-btn" data-menu-open-btn>
        <ion-icon name="reorder-two"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">

          <a href="./index.html" class="logo">
            <img src="./assets/images/logo.svg" alt="Filmlane logo">
          </a>

          <button class="menu-close-btn" data-menu-close-btn>
            <ion-icon name="close-outline"></ion-icon>
          </button>

        </div>

        <ul class="navbar-list">

          <li>
            <a href="./index.html" class="navbar-link">Home</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Movie</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Tv Show</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Anime</a>
          </li>

          <li>
            <a href="#" class="navbar-link">About</a>
          </li>

        </ul>

        <ul class="navbar-social-list">

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-pinterest"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="navbar-social-link">
              <ion-icon name="logo-youtube"></ion-icon>
            </a>
          </li>

        </ul>

      </nav>

    </div>
  </header>





  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero" style="background-image: url('./assets/images/bghero.jpg');" data-hero>
        <div class="container">

          <div class="hero-content">

            <p class="hero-subtitle">Frame X</p>

            <h1 class="h1 hero-title">
              Unlimited <strong>Movie</strong>, TVs Shows, & More.
            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-fill">PG 13</div>

                <div class="badge badge-outline">Blu-ray</div>
              </div>

              <div class="ganre-wrapper">
                <a href="#">Adventure, Fantasy, Comedy, Family,</a>
  
              </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>

                  <time datetime="2025">2025</time>
                </div>

                <div>
                  <ion-icon name="time-outline"></ion-icon>

                  <time datetime="PT101M">101 min</time>
                </div>

              </div>

            </div>

            <button class="btn btn-primary">
              <ion-icon name="play"></ion-icon>

              <span>Add to Your list NOW</span>
            </button>

          </div>

        </div>
      </section>





      <!-- 
        - #UPCOMING
      -->

<section class="upcoming">
    <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Discover the Latest Anime</p>
                <h2 class="h2 section-title">Top Rated Anime</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($anime_collection as $movie): ?>
            <li data-category="<?= htmlspecialchars($movie['category']) ?>">
                <div class="movie-card">
                    <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                        <figure class="card-banner">
                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                            <h3 class="card-title"><?= htmlspecialchars($movie['title']) ?></h3>
                        </a>
                        <time datetime="<?= $movie['year'] ?>"><?= $movie['year'] ?></time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars($movie['quality']) ?></div>
                        <div class="duration">
                            <ion-icon name="time-outline"></ion-icon>
                            <time datetime="PT<?= $movie['duration'] ?>M"><?= $movie['duration'] ?> min</time>
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($movie['rating'], 1) ?></data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>

      <!-- 
        - #TOP RATED MOVIES
        -->


      <section class="top-rated">
    <div class="container">
      <p class="section-subtitle">Fan Favorite Movie</p>
        <h2 class="h2 section-title">Top Rated Movies</h2>

        <ul class="movies-list has-scrollbar" id="top-rated-movies">
            <?php foreach ($top_rated_movies as $movie): ?>
            <li data-category="<?= htmlspecialchars($movie['category']) ?>">
                <div class="movie-card">
                    <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                        <figure class="card-banner">
                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                            <h3 class="card-title"><?= htmlspecialchars($movie['title']) ?></h3>
                        </a>
                        <time datetime="<?= $movie['year'] ?>"><?= $movie['year'] ?></time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars($movie['quality']) ?></div>
                        <div class="duration">
                            <ion-icon name="time-outline"></ion-icon>
                            <time datetime="PT<?= $movie['duration'] ?>M"><?= $movie['duration'] ?> min</time>
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($movie['rating'], 1) ?></data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>




      <!-- 
        - #TV SERIES
      -->

      <section class="tv-series">
        <div class="container">

          <p class="section-subtitle">Best TV Series</p>

          <h2 class="h2 section-title">World Best TV Series</h2>

          <ul class="movies-list has-scrollbar" id="tv-series">
            <?php foreach ($tv_series as $movie): ?>
            <li data-category="<?= htmlspecialchars($movie['category']) ?>">
                <div class="movie-card">
                    <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                        <figure class="card-banner">
                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./movie-details.php?id=<?= $movie['id'] ?>">
                            <h3 class="card-title"><?= htmlspecialchars($movie['title']) ?></h3>
                        </a>
                        <time datetime="<?= $movie['year'] ?>"><?= $movie['year'] ?></time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars($movie['quality']) ?></div>
                        <div class="duration">
                            <ion-icon name="time-outline"></ion-icon>
                            <time datetime="PT<?= $movie['duration'] ?>M"><?= $movie['season'] ?> Season <?= $movie['duration'] ?> eps</time>
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($movie['rating'], 1) ?></data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
          </ul>

        </div>
      </section>
  </main>





  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top">
      <div class="container">

        <div class="footer-brand-wrapper">

          <a href="./index.php" class="logo">
            <img src="./assets/images/iconname.png" alt="Frame X logo" style="width: 150px; height: 64.8px;">
          </a>

          <ul class="footer-list">

            <li>
              <a href="./index.html" class="footer-link">Home</a>
            </li>

            <li>
              <a href="#" class="footer-link">Movie</a>
            </li>

            <li>
              <a href="#" class="footer-link">TV Show</a>
            </li>

            <li>
              <a href="#" class="footer-link">Web Series</a>
            </li>

            <li>
              <a href="#" class="footer-link">About</a>
            </li>

          </ul>

        </div>

        <div class="divider"></div>

        <div class="quicklink-wrapper">

          <ul class="quicklink-list">

            <li>
              <a href="#" class="quicklink-link">Faq</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Help center</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Terms of use</a>
            </li>

            <li>
              <a href="#" class="quicklink-link">Privacy</a>
            </li>

          </ul>

          <ul class="social-list">

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-facebook"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-twitter"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-pinterest"></ion-icon>
              </a>
            </li>

            <li>
              <a href="#" class="social-link">
                <ion-icon name="logo-linkedin"></ion-icon>
              </a>
            </li>

          </ul>

        </div>

      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">
          &copy; 2025 <a href="#">Kelompok 2 Project PemWeb</a>. All Rights Reserved
        </p>

        <img src="./assets/images/footerimg.png" style="height: 3252,1px; width: 300px;" alt="Platform Movie companies logo" class="footer-bottom-img">

      </div>
    </div>

  </footer>





  <!-- 
    - #GO TO TOP
  -->

  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>