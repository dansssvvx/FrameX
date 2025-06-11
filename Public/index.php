<?php
session_start();

require_once '../Conf/database.php';

include_once '../Conf/info.php';
include_once '../API/api_toprated.php';
include_once '../API/api_upcoming.php';
include_once '../API/api_popular.php';
include_once '../API/api_now.php';
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
  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="../Assets/css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">

<?php include __DIR__ . './header.php'; ?>

  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero" style="background-image: url('../Assets/images/bghero.jpg');" data-hero>
        <div class="container">

          <div class="hero-content">

            <p class="hero-subtitle">A Minecraft Movie</p>

            <h1 class="h1 hero-title">
              Your <strong>Ultimate</strong>, Media Archive
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

<section class="upcoming" style="background-image: url('../Assets/images/top-rated-bg.jpg');">
    <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Check the News movie</p>
                <h2 class="h2 section-title">Upcoming Movies</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($upcoming->results as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p->id ?>&type=movie">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p->poster_path?>" alt="<?= htmlspecialchars($p->title) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p->id?>&type=movie">
                            <h3 class="card-title"><?= htmlspecialchars($p->title) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>">
                          <?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                       <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($p->original_language)) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($p->vote_average, 1) ?>/10</data>
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
<section class="upcoming">
     <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Explore the G.O.A.T Movie</p>
                <h2 class="h2 section-title">Top Rated movies</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($toprated->results as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p->id ?>&type=movie">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p->poster_path?>" alt="<?= htmlspecialchars($p->title) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p->id?>&type=movie">
                            <h3 class="card-title"><?= htmlspecialchars($p->title) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>">
                          <?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($p->original_language)) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($p->vote_average, 1) ?>/10</data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>

  <section class="upcoming" style="background-image: url('../Assets/images/top-rated-bg.jpg');">
   <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Check the Most Popular Movies</p>
                <h2 class="h2 section-title">Popular Movies</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($popular->results as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p->id ?>&type=movie">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p->poster_path?>" alt="<?= htmlspecialchars($p->title) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p->id?>&type=movie">
                            <h3 class="card-title"><?= htmlspecialchars($p->title) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>">
                          <?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($p->original_language)) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($p->vote_average, 1) ?>/10</data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>
  </section>

  <section class="upcoming" style="background-image: url('../Assets/images/tv-series-bg.jpg');">
   <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Catch the Hype: See What's Playing Now!</p>
                <h2 class="h2 section-title">Now Playing</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($nowplaying->results as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p->id ?>&type=movie">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p->poster_path?>" alt="<?= htmlspecialchars($p->title) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p->id?>&type=movie">
                            <h3 class="card-title"><?= htmlspecialchars($p->title) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>">
                          <?= htmlspecialchars(substr($p->release_date, 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                        <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($p->original_language)) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($p->vote_average, 1) ?>/10</data>
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

<?php include __DIR__ . './footer.php'; ?>

  <!-- 
    - #GO TO TOP
  -->

  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
  </a>





  <!-- 
    - custom js link
  -->
  <script src="../Assets/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html> 