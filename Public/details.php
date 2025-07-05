<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../Auth/login.php');
    exit;
}

require_once "../Conf/database.php";
include_once "../Conf/info.php";
$id_tv = $_GET['id'];
$id_movie = $_GET['id'];
$id_custom_movie = $_GET['id'];
$type = $_GET['type'];

$is_tv_show_in_list = false;
$tv_show_id_for_list = null;
$saved_tv_show_data = []; // To store saved status, episode, season from DB

$is_movie_in_list = false;
$movie_id_for_list = null;
$saved_movie_data = [];

if ($type === 'tv') {
    include_once "../API/api_tv_id.php";
    include_once "../API/api_tv_video_id.php";
    include_once "../API/api_tv.php";
    include_once "../API/api_tv_similar.php";

    // Assuming tv_id->id is the unique identifier for TMDB TV shows
    $tv_show_id_for_list = $tv_id->id ?? null;

    if ($tv_show_id_for_list && isset($_SESSION['user_id'])) {
        $stmt_check_list = $db->prepare("SELECT status, current_episode, current_season FROM user_tv_list WHERE user_id = ? AND tv_show_id = ?");
        $stmt_check_list->execute([$_SESSION['user_id'], $tv_show_id_for_list]);
        $saved_tv_show_data = $stmt_check_list->fetch(PDO::FETCH_ASSOC);
        $is_tv_show_in_list = ($saved_tv_show_data !== false);
    }

} elseif ($type === 'movie') {
    include_once "../API/api_movie_id.php";
    include_once "../API/api_movie_video_id.php";
    include_once "../API/api_movie_similar.php";
    // include_once "../API/api_tv_id.php"; // This might not be needed for movies

    // Handle API Movies
    $movie_id_for_list = $movie_id->id ?? null;
    $movie_type = 'api_movie';

    if ($movie_id_for_list && isset($_SESSION['user_id'])) {
         $stmt_check_movie_list = $db->prepare("SELECT status FROM user_movies WHERE user_id = ? AND movie_id = ? AND movie_type = ?");
         $stmt_check_movie_list->execute([$_SESSION['user_id'], $movie_id_for_list, $movie_type]);
         $saved_movie_data = $stmt_check_movie_list->fetch(PDO::FETCH_ASSOC);
         $is_movie_in_list = ($saved_movie_data !== false);
    }
}else {
    $stmt = $db->query("SELECT * FROM custom_movie WHERE id =". $id_custom_movie);
    $custom_movie = $stmt->fetch();

    // Handle Custom Movies
    $movie_id_for_list = $custom_movie['id'] ?? null;
    $movie_type = 'custom_movie';

     if ($movie_id_for_list && isset($_SESSION['user_id'])) {
         $stmt_check_movie_list = $db->prepare("SELECT status FROM user_movies WHERE user_id = ? AND movie_id = ? AND movie_type = ?");
         $stmt_check_movie_list->execute([$_SESSION['user_id'], $movie_id_for_list, $movie_type]);
         $saved_movie_data = $stmt_check_movie_list->fetch(PDO::FETCH_ASSOC);
         $is_movie_in_list = ($saved_movie_data !== false);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name ?? $custom_movie['title'] )?> (<?php echo htmlspecialchars(substr($movie_id->release_date ?? $tv_id->first_air_date ?? $custom_movie['release_date'], 0, 4)); ?>)
</title>

  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
  <link rel="stylesheet" href="../Assets/css/style.css">
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
          <img src="<?php
          if($type === 'tv') {
              echo 'https://image.tmdb.org/t/p/w500' . htmlspecialchars($tv_id->poster_path ?? $custom_movie['poster_path']);
          } elseif ($type === 'movie') {
              echo 'https://image.tmdb.org/t/p/w500' . htmlspecialchars($movie_id->poster_path ?? $custom_movie['poster_path']);
          } else {
              echo $custom_movie['poster_path'];
          }
          ?>"
              alt="<?php echo $movie_id->title ?? $tv_id->name ?? $custom_movie['title'] ?? 'Movie Poster' ?>">
        </figure>

          <div class="movie-detail-content">
            <h1 class="h1 detail-title">
              <strong>
                <a href="<?php echo htmlspecialchars($movie_id->homepage ?? $tv_id->homepage ?? $custom_movie['homepage'])?>" style="color: white;">
                <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name ?? $custom_movie['title'])?> </strong>
                </a>

            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-outline">
                  <a style="text-decoration: none; color: inherit;">
                      <?php echo $movie_id->status ?? $tv_id->status ?? $custom_movie['status']?>
                  </a>
                </div>
              </div>

            <div class="ganre-wrapper">
                <?php
                $genres_to_display = [];

                // Check for movie genres
                if (isset($movie_id) && is_object($movie_id) && isset($movie_id->genres) && is_array($movie_id->genres)) {
                    $genres_to_display = $movie_id->genres;
                }
                // Check for TV show genres
                elseif (isset($tv_id) && is_object($tv_id) && isset($tv_id->genres) && is_array($tv_id->genres)) {
                    $genres_to_display = $tv_id->genres;
                }
                // Fallback to database query for custom movies
                elseif (isset($custom_movie) && is_array($custom_movie)) {
                    // Get genres for custom movie
                    $stmt5 = $db->prepare("SELECT genre_id FROM movie_genre WHERE movie_id = :movie_id");
                    $stmt5->execute([':movie_id' => $custom_movie['id']]);
                    $genres = $stmt5->fetchAll();

                    if (!empty($genres)) {
                        $gcount = count($genres);
                        $genre_counter = 0;

                        foreach($genres as $g) {
                            $stmt6 = $db->prepare("SELECT name FROM genre WHERE id = :genre_id");
                            $stmt6->execute([':genre_id' => $g['genre_id']]);
                            $p = $stmt6->fetch();

                            if ($p) {
                                ?>
                                <a><?php echo htmlspecialchars($p['name']); ?></a>
                                <?php
                                $genre_counter++;

                                // Add separator if not last item
                                if ($genre_counter < $gcount) {
                                    echo ", ";
                                }
                            }
                        }
                    } else {
                        echo "No genres found";
                    }
                }

                if (!empty($genres_to_display)) {
                    foreach ($genres_to_display as $genre_item): ?>
                        <a><?php echo htmlspecialchars($genre_item->name); ?></a>
                    <?php endforeach;
                }
                ?>
            </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>
                    <time datetime="<?php
                        $date = $movie_id->release_date ?? $tv_id->first_air_date ?? $custom_movie['release_date'] ?? '';
                        echo htmlspecialchars(substr($date, 0, 4));
                    ?>">
                        <?php
                        echo htmlspecialchars(substr($date, 0, 4));
                        ?>
                    </time>
                </div>


              </div>

            </div>

            <p class="storyline"><strong>Overview</strong><br>
              <?php echo htmlspecialchars($movie_id->overview ?? $tv_id->overview ?? $custom_movie['overview']); ?>
              <br><br>
              <em><?php echo htmlspecialchars($movie_id->tagline ?? $tv_id->tagline ?? $custom_movie['tagline'] ?? "")?></em>

            <?php if ($type === 'tv') { ?>
              <br><br>
              Total Episodes: <?php echo $tv_id->number_of_episodes ?? 'N/A'; ?><br>
              Total Seasons/Arcs : <?php echo $tv_id->number_of_seasons ?? 'N/A'; ?>
            <?php } ?>

            </p>

            <div class="details-actions">

              <div class="title-wrapper">
                <?php
                $rating = $movie_id->vote_average ?? $tv_id->vote_average ?? $custom_movie['vote_average'] ?? 0;

                ?>
                <p class="title">IMDb Rating: <?php echo number_format($rating, 1); ?>/10</p>
                <p class="text">
                Revenue :
                <?php
                  $revenue = $movie_id->revenue ?? $custom_movie['revenue'] ?? null;
                  if ($revenue && $revenue > 0) {
                    echo '$' . number_format($revenue, 0, '.', ',');
                  } else {
                    echo 'N/A';
                  }
                ?>
              </p>

              </div>

          <?php if ($type === 'tv' && $tv_show_id_for_list): ?>
                  <button class="btn btn-primary" id="saveToListBtn"
                          data-tv-show-id="<?= htmlspecialchars($tv_show_id_for_list) ?>"
                          data-action="<?= $is_tv_show_in_list ? 'edit' : 'add' ?>"
                          data-current-status="<?= htmlspecialchars($saved_tv_show_data['status'] ?? 'plan to watch') ?>"
                          data-current-episode="<?= htmlspecialchars($saved_tv_show_data['current_episode'] ?? 0) ?>"
                          data-current-season="<?= htmlspecialchars($saved_tv_show_data['current_season'] ?? 0) ?>"
                          data-total-episodes="<?= htmlspecialchars($tv_id->number_of_episodes ?? 0) ?>"
                          data-total-seasons="<?= htmlspecialchars($tv_id->number_of_seasons ?? 0) ?>"
                          >
                      <ion-icon name="play"></ion-icon>
                      <span id="buttonText"><?= $is_tv_show_in_list ? 'Edit My List Entry' : 'Add To Your List' ?></span>
                  </button>
                  <?php if($is_tv_show_in_list): ?>
                    <button class="btn btn-primary" id="removeFromListBtn" style="background: var(--raisin-black-hover); border-color: var(--raisin-black-hover);"
                            data-tv-show-id="<?= htmlspecialchars($tv_show_id_for_list) ?>" data-action="remove">
                        <ion-icon name="trash"></ion-icon>
                        <span>Remove From List</span>
                    </button>
                  <?php endif; ?>
              <?php elseif ($type === 'movie' && $movie_id_for_list): ?>
                <button class="btn btn-primary" id="saveMovieToListBtn"
                        data-movie-id="<?= htmlspecialchars($movie_id_for_list) ?>"
                        data-movie-type="<?= htmlspecialchars($movie_type) ?>"
                        data-action="<?= $is_movie_in_list ? 'edit' : 'add' ?>"
                        data-current-status="<?= htmlspecialchars($saved_movie_data['status'] ?? 'plan to watch') ?>">
                    <ion-icon name="play"></ion-icon>
                    <span id="movieButtonText"><?= $is_movie_in_list ? 'Edit My List Entry' : 'Add To Your List' ?></span>
                </button>
                <?php if($is_movie_in_list): ?>
                    <button class="btn btn-primary" id="removeMovieFromListBtn" style="background: var(--raisin-black-hover); border-color: var(--raisin-black-hover);"
                            data-movie-id="<?= htmlspecialchars($movie_id_for_list) ?>"
                            data-movie-type="<?= htmlspecialchars($movie_type) ?>"
                            data-action="remove">
                        <ion-icon name="trash"></ion-icon>
                        <span>Remove From List</span>
                    </button>
                  <?php endif; ?>
              <?php else: ?>
                  <button class="btn btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                      <ion-icon name="play"></ion-icon>
                      <span>This feature is unavailabale for custom movie</span>
                  </button>
              <?php endif; ?>

            </div>

            <a href="<?php echo htmlspecialchars($movie_id->homepage ?? $tv_id->homepage ?? $custom_movie['homepage']); ?>" download class="download-btn">
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
      <h1 class=" h1 section-subtitle">Watch the latest trailers for <strong> <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name ?? $custom_movie['title'])?></strong>. Browse through our collection of official videos, teasers, and behind-the-scenes content.</h1>
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
          // return; // Don't return here, continue to similar section
      }
      echo '</div>'; // Close last row
      ?>
    </div>
  </div>
</section>

 <section class="upcoming" style="background-image: url('./Assets/images/tv-series-bg.jpg'); padding-top:20px ;">
   <div class="container">

        <div class="flex-wrapper" style="margin-bottom: 25px;">

            <div class="title-wrapper">
                <p class="section-subtitle">Browse More Like <strong> <?php echo htmlspecialchars($movie_id->title ?? $tv_id->name ?? $custom_movie['title']) ?> </strong> </p>
                <?php if (!empty($movie_id->title)): ?>
                  <h2 class="h2 section-title">Similar Movie</h2>
                <?php elseif(!empty($tv_id->title)): ?>
                  <h2 class="h2 section-title">Similar TV Show</h2>
                <?php else: ?>
                  <h2 class="h2 section-title">Similar Custom Movie</h2>
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


<div id="tvShowListModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-700 transform transition-all duration-300 scale-95 opacity-0" id="tvModalContent">
        <div class="p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white" id="modalTitle">
                    <ion-icon name="tv" class="inline-block mr-2 text-citrine"></ion-icon>
                    Add TV Show to My List
                </h2>
                <button onclick="closeTvShowListModal()" class="text-gray-400 hover:text-white transition-colors duration-200">
                    <ion-icon name="close" class="text-2xl"></ion-icon>
                </button>
            </div>

            <!-- Form -->
            <form id="tvShowListForm" action="save_tv_to_list.php" method="POST" class="space-y-6">
                <input type="hidden" name="tv_show_id" id="modalTvShowId">
                <input type="hidden" name="action" id="modalAction">

                <!-- Status Selection -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-300 mb-3">
                        <ion-icon name="flag" class="inline-block mr-2 text-citrine"></ion-icon>
                        Watch Status
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="status-option">
                            <input type="radio" name="status" value="plan to watch" class="hidden">
                            <div class="status-card">
                                <ion-icon name="time" class="text-orange-400"></ion-icon>
                                <span>Plan to Watch</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="watching" class="hidden">
                            <div class="status-card">
                                <ion-icon name="play-circle" class="text-green-400"></ion-icon>
                                <span>Watching</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="completed" class="hidden">
                            <div class="status-card">
                                <ion-icon name="checkmark-circle" class="text-blue-400"></ion-icon>
                                <span>Completed</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="on-hold" class="hidden">
                            <div class="status-card">
                                <ion-icon name="pause-circle" class="text-purple-400"></ion-icon>
                                <span>On-Hold</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="dropped" class="hidden">
                            <div class="status-card">
                                <ion-icon name="close-circle" class="text-red-400"></ion-icon>
                                <span>Dropped</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Progress Tracking -->
                <div class="space-y-4">
                    <div>
                        <label for="current_episode" class="block text-sm font-semibold text-gray-300 mb-2">
                            <ion-icon name="albums" class="inline-block mr-2 text-citrine"></ion-icon>
                            Current Episode <span id="totalEpisodesInfo" class="text-gray-400 text-xs"></span>
                        </label>
                        <div class="relative">
                            <input type="number" name="current_episode" id="current_episode" min="0" value="0"
                                class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-citrine focus:ring-2 focus:ring-citrine/20 transition-all duration-200">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <ion-icon name="chevron-up-down"></ion-icon>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="current_season" class="block text-sm font-semibold text-gray-300 mb-2">
                            <ion-icon name="layers" class="inline-block mr-2 text-citrine"></ion-icon>
                            Current Season <span id="totalSeasonsInfo" class="text-gray-400 text-xs"></span>
                        </label>
                        <div class="relative">
                            <input type="number" name="current_season" id="current_season" min="0" value="0"
                                class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:border-citrine focus:ring-2 focus:ring-citrine/20 transition-all duration-200">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <ion-icon name="chevron-up-down"></ion-icon>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeTvShowListModal()" 
                        class="flex-1 px-6 py-3 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 transition-all duration-200">     
                        Cancel
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 text-sm font-medium text-black bg-white rounded-lg hover:bg-yellow-400 focus:ring-2 focus:ring-citrine transition-all duration-200 transform hover:scale-105">
                        Save to List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="movieListModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-700 transform transition-all duration-300 scale-95 opacity-0" id="movieModalContent">
        <div class="p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white" id="movieModalTitle">
                    <ion-icon name="film" class="inline-block mr-2 text-citrine"></ion-icon>
                    Add Movie to My List
                </h2>
                <button onclick="closeMovieListModal()" class="text-gray-400 hover:text-white transition-colors duration-200">
                    <ion-icon name="close" class="text-2xl"></ion-icon>
                </button>
            </div>

            <!-- Form -->
            <form id="movieListForm" action="save_movie_to_list.php" method="POST" class="space-y-6">
                <input type="hidden" name="movie_id" id="modalMovieId">
                <input type="hidden" name="movie_type" id="modalMovieType">
                <input type="hidden" name="action" id="modalMovieAction">

                <!-- Status Selection -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-300 mb-3">
                        <ion-icon name="flag" class="inline-block mr-2 text-citrine"></ion-icon>
                        Watch Status
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="status-option">
                            <input type="radio" name="status" value="plan to watch" class="hidden">
                            <div class="status-card">
                                <ion-icon name="time" class="text-orange-400"></ion-icon>
                                <span>Plan to Watch</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="watching" class="hidden">
                            <div class="status-card">
                                <ion-icon name="play-circle" class="text-green-400"></ion-icon>
                                <span>Watching</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="ended" class="hidden">
                            <div class="status-card">
                                <ion-icon name="checkmark-circle" class="text-blue-400"></ion-icon>
                                <span>Completed</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="on-hold" class="hidden">
                            <div class="status-card">
                                <ion-icon name="pause-circle" class="text-purple-400"></ion-icon>
                                <span>On-Hold</span>
                            </div>
                        </label>
                        <label class="status-option">
                            <input type="radio" name="status" value="dropped" class="hidden">
                            <div class="status-card">
                                <ion-icon name="close-circle" class="text-red-400"></ion-icon>
                                <span>Dropped</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeMovieListModal()" 
                        class="flex-1 px-6 py-3 text-sm font-medium text-gray-300 bg-gray-700 rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 text-sm font-medium text-black bg-white rounded-lg hover:bg-yellow-400 focus:ring-2 focus:ring-citrine transition-all duration-200 transform hover:scale-105">
                        Save to List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.status-option {
    cursor: pointer;
    transition: all 0.2s ease;
}

.status-option:hover .status-card {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.status-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(255,255,255,0.05);
    border: 2px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    transition: all 0.2s ease;
    text-align: center;
    min-height: 80px;
}

.status-card ion-icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.status-card span {
    font-size: 0.8rem;
    font-weight: 600;
    color: #e5e7eb;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Radio button styling */
.status-option input[type="radio"]:checked + .status-card {
    border-color: var(--citrine);
    background: rgba(255, 215, 0, 0.1);
    box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
}

.status-option input[type="radio"]:checked + .status-card span {
    color: var(--citrine);
}

/* Modal animations */
.modal-open {
    animation: modalOpen 0.3s ease-out forwards;
}

.modal-close {
    animation: modalClose 0.3s ease-in forwards;
}

@keyframes modalOpen {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes modalClose {
    from {
        transform: scale(1);
        opacity: 1;
    }
    to {
        transform: scale(0.95);
        opacity: 0;
    }
}

/* Input focus effects */
input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1);
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
}

button:active {
    transform: translateY(0);
}
</style>

<?php include __DIR__ . './footer.php'; ?>


  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
  </a>


<script>
    // Make functions globally accessible
    let closeMovieListModal, openMovieListModal, closeTvShowListModal, openTvShowListModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        const saveToListBtn = document.getElementById('saveToListBtn');
        const removeFromListBtn = document.getElementById('removeFromListBtn');
        const tvShowListModal = document.getElementById('tvShowListModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalTvShowId = document.getElementById('modalTvShowId');
        const modalAction = document.getElementById('modalAction');
        const listStatus = document.getElementById('list_status');
        const currentEpisode = document.getElementById('current_episode');
        const currentSeason = document.getElementById('current_season');
        const totalEpisodesInfo = document.getElementById('totalEpisodesInfo');
        const totalSeasonsInfo = document.getElementById('totalSeasonsInfo');
        const tvShowListForm = document.getElementById('tvShowListForm');

        const saveMovieToListBtn = document.getElementById('saveMovieToListBtn');
        const removeMovieFromListBtn = document.getElementById('removeMovieFromListBtn');
        const movieListModal = document.getElementById('movieListModal');
        const movieModalTitle = document.getElementById('movieModalTitle');
        const modalMovieId = document.getElementById('modalMovieId');
        const modalMovieType = document.getElementById('modalMovieType');
        const modalMovieAction = document.getElementById('modalMovieAction');
        const movieListStatus = document.getElementById('movie_list_status');
        const movieListForm = document.getElementById('movieListForm');

        openTvShowListModal = function(tvId, actionType, currentStatus, currentEp, currentSe, totalEp, totalSe) {
            modalTvShowId.value = tvId;
            modalAction.value = actionType;

            if (actionType === 'add') {
                modalTitle.innerHTML = '<ion-icon name="tv" class="inline-block mr-2 text-citrine"></ion-icon>Add TV Show to My List';
                // Set default status
                document.querySelector('input[value="plan to watch"]').checked = true;
                currentEpisode.value = 0;
                currentSeason.value = 0;
            } else if (actionType === 'edit') {
                modalTitle.innerHTML = '<ion-icon name="tv" class="inline-block mr-2 text-citrine"></ion-icon>Edit My List Entry';
                // Set current status
                document.querySelector(`input[value="${currentStatus}"]`).checked = true;
                currentEpisode.value = currentEp;
                currentSeason.value = currentSe;
            }

            totalEpisodesInfo.textContent = totalEp > 0 ? `(out of ${totalEp})` : '';
            totalSeasonsInfo.textContent = totalSe > 0 ? `(out of ${totalSe})` : '';

            currentEpisode.max = totalEp;
            currentSeason.max = totalSe;

            // Show modal with animation
            tvShowListModal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('tvModalContent').classList.add('modal-open');
            }, 10);
        }

        closeTvShowListModal = function() {
            const modalContent = document.getElementById('tvModalContent');
            modalContent.classList.remove('modal-open');
            modalContent.classList.add('modal-close');
            
            setTimeout(() => {
                tvShowListModal.classList.add('hidden');
                modalContent.classList.remove('modal-close');
            }, 300);
        }

        openMovieListModal = function(movieId, movieType, actionType, currentStatus) {
            modalMovieId.value = movieId;
            modalMovieType.value = movieType;
            modalMovieAction.value = actionType;

            if (actionType === 'add') {
                movieModalTitle.innerHTML = '<ion-icon name="film" class="inline-block mr-2 text-citrine"></ion-icon>Add Movie to My List';
                // Set default status
                document.querySelector('#movieListForm input[value="plan to watch"]').checked = true;
            } else if (actionType === 'edit') {
                movieModalTitle.innerHTML = '<ion-icon name="film" class="inline-block mr-2 text-citrine"></ion-icon>Edit My List Entry';
                // Set current status
                document.querySelector(`#movieListForm input[value="${currentStatus}"]`).checked = true;
            }
            
            // Show modal with animation
            movieListModal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('movieModalContent').classList.add('modal-open');
            }, 10);
        }

        closeMovieListModal = function() {
            const modalContent = document.getElementById('movieModalContent');
            if (!modalContent) {
                console.error('movieModalContent not found');
                return;
            }
            
            modalContent.classList.remove('modal-open');
            modalContent.classList.add('modal-close');
            
            setTimeout(() => {
                movieListModal.classList.add('hidden');
                modalContent.classList.remove('modal-close');
            }, 300);
        }

        if (saveToListBtn) {
            saveToListBtn.addEventListener('click', function() {
                const tvId = this.dataset.tvShowId;
                const actionType = this.dataset.action;
                const currentStatus = this.dataset.currentStatus;
                const currentEp = this.dataset.currentEpisode;
                const currentSe = this.dataset.currentSeason;
                const totalEp = this.dataset.totalEpisodes;
                const totalSe = this.dataset.totalSeasons;
                openTvShowListModal(tvId, actionType, currentStatus, currentEp, currentSe, totalEp, totalSe);
            });
        }

        if (removeFromListBtn) {
            removeFromListBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this TV Show from your list?')) {
                    const tvId = this.dataset.tvShowId;
                    fetch('save_tv_to_list.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `tv_show_id=${tvId}&action=remove`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
                }
            });
        }

        if (saveMovieToListBtn) {
            saveMovieToListBtn.addEventListener('click', function() {
                const movieId = this.dataset.movieId;
                const movieType = this.dataset.movieType;
                const actionType = this.dataset.action;
                const currentStatus = this.dataset.currentStatus;
                openMovieListModal(movieId, movieType, actionType, currentStatus);
            });
        }

        if (removeMovieFromListBtn) {
            removeMovieFromListBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this Movie from your list?')) {
                    const movieId = this.dataset.movieId;
                    const movieType = this.dataset.movieType;
                    fetch('save_movie_to_list.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `movie_id=${movieId}&movie_type=${movieType}&action=remove`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An unexpected error occurred.');
                    });
                }
            });
        }



        // Close modals when clicking outside
        tvShowListModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeTvShowListModal();
            }
        });

        movieListModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeMovieListModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!tvShowListModal.classList.contains('hidden')) {
                    closeTvShowListModal();
                }
                if (!movieListModal.classList.contains('hidden')) {
                    closeMovieListModal();
                }
            }
        });

        // Add visual feedback for status selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options in the same form
                const form = this.closest('form');
                form.querySelectorAll('.status-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                // Add active class to clicked option
                this.classList.add('active');
            });
        });

        // Add loading states to submit buttons
        function setLoadingState(form, isLoading) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            if (isLoading) {
                submitBtn.innerHTML = '<ion-icon name="hourglass" class="animate-spin mr-2"></ion-icon>Saving...';
                submitBtn.disabled = true;
            } else {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Enhanced form submission with loading states
        tvShowListForm.addEventListener('submit', function(event) {
            event.preventDefault();
            setLoadingState(this, true);

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('save_tv_to_list.php', {
                method: 'POST',
                body: params
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(this, false);
                if (data.success) {
                    // Show success message with better styling
                    showNotification(data.message, 'success');
                    closeTvShowListModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                setLoadingState(this, false);
                console.error('Error:', error);
                showNotification('An unexpected error occurred.', 'error');
            });
        });

        movieListForm.addEventListener('submit', function(event) {
            event.preventDefault();
            setLoadingState(this, true);

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('save_movie_to_list.php', {
                method: 'POST',
                body: params
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(this, false);
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeMovieListModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                setLoadingState(this, false);
                console.error('Error:', error);
                showNotification('An unexpected error occurred.', 'error');
            });
        });

        // Custom notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const icon = type === 'success' ? 'checkmark-circle' : type === 'error' ? 'close-circle' : 'information-circle';
            
            notification.innerHTML = `
                <div class="flex items-center ${bgColor} text-white p-3 rounded-lg">
                    <ion-icon name="${icon}" class="mr-2 text-xl"></ion-icon>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    });
</script>


  <script src="../Assets/js/script.js"></script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>