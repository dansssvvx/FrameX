<?php

session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../Auth/login.php');
    exit;
}

require_once __DIR__ .  "../Conf/database.php";
include_once __DIR__ . "../Conf/info.php";

$id_movie = $_GET['id'];
$id_tv = $_GET['id'];
$type = $_GET['type'];

if ($type === 'tv') {
    include_once "../API/api_tv_id.php";
    include_once "..API/api_tv_video_id.php";
    include_once "../API/api_tv.php";
    include_once "../API/api_tv_similar.php";
} else {
    include_once "../API/api_movie_id.php";
    include_once "../API/api_movie_video_id.php";
    include_once "../API/api_movie_similar.php";
    include_once "../API/api_tv_id.php";
}



  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name); ?> (<?php echo htmlspecialchars(substr($movie_id->release_date ?? $tv_id->first_air_date, 0, 4)); ?>)
</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="../Asset/images/icon.png" type="image/png">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="../Asset/css/style.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body id="#top">

<?php include_once __DIR__ . './header.php'; ?>

   <main>
    <article>

      <section class="movie-detail">

        <div class="container">

          <figure class="movie-detail-banner">
           <img src="https://image.tmdb.org/t/p/w500<?= $movie_id->poster_path ?? $tv_id->poster_path?>" alt="<?= htmlspecialchars($movie_id->title ?? $tv_id->name) ?> poster">

          </figure>

          <div class="movie-detail-content">    
            <h1 class="h1 detail-title">
              <a href="<?php echo htmlspecialchars($movie_id->homepage ?? $tv_id->homepage)?>" style="color: inherit;">
              <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name); ?>
              </a>
            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-outline">
                  <a style="text-decoration: none; color: inherit;">
                      <?php echo htmlspecialchars($movie_id->status ?? $tv_id->status)?> 
                  </a>
                </div>
              </div>

          <div class="ganre-wrapper" >
              <?php
              $genres_to_display = [];

              if (isset($movie_id) && is_object($movie_id) && isset($movie_id->genres) && is_array($movie_id->genres)) {
                  $genres_to_display = $movie_id->genres;
              } 
              elseif (isset($tv_id) && is_object($tv_id) && isset($tv_id->genres) && is_array($tv_id->genres)) {
                  $genres_to_display = $tv_id->genres;
              }

              foreach ($genres_to_display as $genre_item): 
              ?>
              <a><?php echo htmlspecialchars($genre_item->name) ?></a>
              <?php endforeach; ?>
          </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>
                  <time datetime="<?php echo htmlspecialchars(substr($movie_id->release_date ?? $tv_id->first_air_date, 0, 4 )); ?>"><?php echo htmlspecialchars(substr($movie_id->release_date ?? $tv_id->first_air_date, 0, 4 ));?></time>
                </div>


              </div>

            </div>

            <p class="storyline">
              <?php echo htmlspecialchars($movie_id->overview ?? $tv_id->overview); ?>
            </p>

            <div class="details-actions">

              <button class="share">
                <ion-icon name="share-social"></ion-icon>
                <span>Share</span>
              </button>

              <div class="title-wrapper">
                <p class="title">IMDb Rating: <?php echo htmlspecialchars($movie_id->vote_average ?? $tv_id->vote_average); ?></p>
                <p class="text">Popularity: <?php echo htmlspecialchars($movie_id->popularity ?? $tv_id->popularity)?></p>
              </div>

              <button class="btn btn-primary">
                <ion-icon name="play"></ion-icon>
                <span>Add To Your List</span>
              </button>

            </div>

            <a href="<?php echo htmlspecialchars($movie_id->homepage ?? $tv_id->homepage); ?>" download class="download-btn">
              <span>Official Website</span>
              <ion-icon name="download-outline"></ion-icon>
            </a>

          </div>

        </div>
      </section>


    </article>


<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex w-full mb-20 flex-wrap" style="margin-bottom: 20px;">
      <h1 class=" h1 section-subtitle">Watch the latest trailers for <strong> <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name)?></strong>. Browse through our collection of official videos, teasers, and behind-the-scenes content.</h1>
    </div>
    
    <div class="flex flex-wrap md:-m-2 -m-1">
      <?php
      $counter = 2;
      $max_items_to_display = 9;
      $current_item_count = 0;
      
      // First row container
      echo '<div class="flex flex-wrap w-full">';
    
      if (isset($movie_video_id->results) && is_array($movie_video_id->results) && !empty($movie_video_id->results)) {
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
      } else if (isset($tv_video_id->results) && is_array($tv_video_id->results) && !empty($tv_video_id->results)) {
        foreach ($tv_video_id->results as $video) {
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
      } else {
          echo '<p class="w-full text-center">No videos available for this movie.</p>';
          return; // Exit if no videos are available
      }
      echo '</div>'; // Close last row
      ?>
    </div>
  </div>
</section>

 <section class="upcoming" style="background-image: url('./Asset/images/tv-series-bg.jpg'); padding-top:20px ;">
   <div class="container">

        <div class="flex-wrapper" style="margin-bottom: 25px;">

            <div class="title-wrapper">
                <p class="section-subtitle">Browse More Like <strong> <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name) ?> </strong> </p>
                <?php if (empty($movie_id->title)): ?>
                  <h2 class="h2 section-title">Similar TV Show</h2>
                <?php else: ?>
                  <h2 class="h2 section-title">Similar Movie</h2>
                <?php endif; ?>
            </div>
            </div>

     <ul class="movies-list has-scrollbar" id="anime_collection">
  <?php

if (isset($movie_similar_id->results) && is_array($movie_similar_id->results) && !empty($movie_similar_id->results)) {
    foreach ($movie_similar_id->results as $similar): ?>
        <li data-category="<?= htmlspecialchars($similar->genre_ids[0] ?? '') ?>">
            <div class="movie-card">
                <a href="./details.php?id=<?= htmlspecialchars($similar->id) ?>&type=movie">
                    <figure class="card-banner">
                        <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($similar->poster_path) ?>" 
                             alt="<?= htmlspecialchars($similar->title ?? $similar->name) ?> poster" 
                             loading="lazy">
                    </figure>
                </a>

                <div class="title-wrapper">
                    <a href="./details.php?id=<?= htmlspecialchars($similar->id) ?>&type=movie">
                        <h3 class="card-title"><?= htmlspecialchars($similar->title ?? $similar->name ?? 'N/A') ?></h3>
                    </a>
                    <time datetime="<?= htmlspecialchars(substr($similar->release_date ?? $similar->first_air_date ?? '', 0, 4)) ?>">
                        <?= htmlspecialchars(substr($similar->release_date ?? $similar->first_air_date ?? '', 0, 4)) ?>
                    </time>
                </div>

                <div class="card-meta">
                    <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($similar->original_language ?? '')) ?></div>
                    <div class="duration"></div>
                    <div class="rating">
                        <ion-icon name="star"></ion-icon>
                        <data><?= number_format($similar->vote_average ?? 0, 1) ?>/10</data>
                    </div>
                    </div>
                    </div>
            
        </li>
    <?php endforeach;
} elseif (isset($tv_similar_id->results) && is_array($tv_similar_id->results) && !empty($tv_similar_id->results)) {
    foreach ($tv_similar_id->results as $similar): ?>
        <li data-category="<?= htmlspecialchars($similar->genre_ids[0] ?? '') ?>">
            <div class="movie-card">
                <a href="./details.php?id=<?= htmlspecialchars($similar->id) ?>&type=tv">
                    <figure class="card-banner">
                        <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($similar->poster_path) ?>" 
                             alt="<?= htmlspecialchars($similar->name ?? $similar->title) ?> poster" 
                             loading="lazy">
                    </figure>
                </a>

                <div class="title-wrapper">
                    <a href="./details.php?id=<?= htmlspecialchars($similar->id) ?>&type=tv">
                        <h3 class="card-title"><?= htmlspecialchars($similar->name ?? $similar->title ?? 'N/A') ?></h3>
                    </a>
                    <time datetime="<?= htmlspecialchars(substr($similar->first_air_date ?? $similar->release_date ?? '', 0, 4)) ?>">
                        <?= htmlspecialchars(substr($similar->first_air_date ?? $similar->release_date ?? '', 0, 4)) ?>
                    </time>
                </div>

                <div class="card-meta">
                    <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($similar->original_language ?? '')) ?></div>
                    <div class="duration"></div>
                    <div class="rating">
                        <ion-icon name="star"></ion-icon>
                        <data><?= number_format($similar->vote_average ?? 0, 1) ?>/10</data>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach;
} else {
    echo '<li class="no-results">No similar content found</li>';
}
?>
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
  <script src="../Asset/js/script.js"></scrip>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>