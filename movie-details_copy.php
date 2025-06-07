<?php
  include "config/info.php";
  
  $id_movie = $_GET['id'];
    include_once "api/api_movie_id.php";
    include_once "api/api_movie_video_id.php";
    include_once "api/api_movie_similar.php";
    $title = "Detail Movie (".$movie_id->original_title.")";
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo htmlspecialchars($movie_id->title); ?>
    (<?php echo htmlspecialchars(substr($movie_id->release_date, 0, 4 )); ?>) | FrameX
</title>

  <link rel="shortcut icon" href="./icon.png" type="image/svg+xml">

  <link rel="stylesheet" href="./assets/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="#top">

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($movie['title']); ?></title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./icon.png" type="image/svg+xml">

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

<body id="#top">

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
            <option value="au">ID</option>
          </select>
        </div>

        <button class="btn btn-primary">Sign in</button>

      </div>

      <button class="menu-open-btn" data-menu-open-btn>
        <ion-icon name="reorder-two"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">

      <a href="./index.php" class="logo">
        <img src="./assets/images/iconname.png" alt="Frame X logo" style="width: 100px; height: 43.2px;">
      </a>

          <button class="menu-close-btn" data-menu-close-btn>
            <ion-icon name="close-outline"></ion-icon>
          </button>

        </div>

        <ul class="navbar-list">

          <li>
            <a href="./index_copy.php" class="navbar-link">Home</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Movie</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Tv Show</a>
          </li>

          <li>
            <a href="#" class="navbar-link">Web Series</a>
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

      <section class="movie-detail">
        <div class="container">

          <figure class="movie-detail-banner">
           <img src="https://image.tmdb.org/t/p/w500<?= $movie_id->poster_path?>" alt="<?= htmlspecialchars($movie_id->title) ?> movie poster">
          
            <button class="play-btn" onclick="window.open('<?php echo htmlspecialchars($movie['trailer_url']); ?>', '_blank')">
            <ion-icon name="play-circle-outline"></ion-icon>
          </button>

          </figure>

          <div class="movie-detail-content">    
            <h1 class="h1 detail-title">
              <a href="<?php echo htmlspecialchars($movie_id->homepage)?>" style="color: inherit;">
              <?php echo htmlspecialchars($movie_id->title); ?>
              </a>
            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-outline">
                  <a href="<?php echo htmlspecialchars($movie_id->homepage)?>" style="text-decoration: none; color: inherit;">
                      <?php echo htmlspecialchars(strtoupper($movie_id->original_language)); ?> 
                  </a>
                </div>
              </div>

              <div class="ganre-wrapper" >
                <?php foreach ($movie_id->genres as $g): ?>
                <a href="#"><?php echo htmlspecialchars($g->name) ?></a>
                <?php endforeach; ?>
              </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>
                  <time datetime="<?php echo htmlspecialchars(substr($movie_id->release_date, 0, 4 )); ?>"><?php echo htmlspecialchars(substr($movie_id->release_date, 0, 4 ));?></time>
                </div>


              </div>

            </div>

            <p class="storyline">
              <?php echo htmlspecialchars($movie_id->overview); ?>
            </p>

            <div class="details-actions">

              <button class="share">
                <ion-icon name="share-social"></ion-icon>
                <span>Share</span>
              </button>

              <div class="title-wrapper">
                <p class="title">IMDb Rating: <?php echo htmlspecialchars($movie_id->vote_average); ?></p>
                <p class="text">Rating</p>
              </div>

              <button class="btn btn-primary">
                <ion-icon name="play"></ion-icon>
                <span>Add To Your List</span>
              </button>

            </div>

            <a href="https://image.tmdb.org/t/p/w500 <?php echo htmlspecialchars($movie_id->poster_path); ?>" download class="download-btn">
              <span>Download Poster</span>
              <ion-icon name="download-outline"></ion-icon>
            </a>

          </div>

        </div>
      </section>


    </article>


<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex w-full mb-20 flex-wrap">
      <h1 class=" h1 section-subtitle">Watch the latest trailers for <strong> <?php echo htmlspecialchars($movie_id->title) ?> </strong>. Browse through our collection of official videos, teasers, and behind-the-scenes content.</h1>
    </div>
    
    <div class="flex flex-wrap md:-m-2 -m-1">
      <?php
      $counter = 2;
      $max_items_to_display = 9;
      $current_item_count = 0;
      
      // First row container
      echo '<div class="flex flex-wrap w-full">';
      
      foreach ($movie_video_id->results as $video) {
          if ($current_item_count >= $max_items_to_display) {
              break;
          }
          
          // Start new row after 3 items
          if ($current_item_count > 0 && $current_item_count % 3 == 0) {
              echo '</div><div class="flex flex-wrap w-full">';
          }
          
          echo '<div class="md:p-2 p-1 w-full md:w-1/3">';
          echo '<div class="div'. $counter .'">';
          echo '<iframe class="w-full h-64 object-cover object-center block" src="https://www.youtube.com/embed/' . htmlspecialchars($video->key) . '" frameborder="0" allowfullscreen></iframe>';
          echo '</div></div>';
          
          $current_item_count++;
          $counter++;
      }
      
      echo '</div>'; // Close last row
      ?>
    </div>
  </div>
</section>

 <section class="upcoming" style="background-image: url('./assets/images/tv-series-bg.jpg');">
   <div class="container">

        <div class="flex-wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Browse More Like <strong> <?php echo htmlspecialchars($movie_id->title) ?> </strong> </p>
                <h2 class="h2 section-title">Simmiliar Movie</h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($movie_similar_id->results as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="movie-details_copy.php?id=<?= $p->id ?>">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p->poster_path?>" alt="<?= htmlspecialchars($p->title) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="movie-details_copy.php?id=<?= $p->id?>">
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
<?php include __DIR__ . '/footer.php'; ?>


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