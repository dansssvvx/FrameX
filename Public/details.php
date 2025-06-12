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
                      <span>Add To Your List (TV Show Only)</span>
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


<div id="tvShowListModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white" id="modalTitle">Add TV Show to My List</h2>
            <form id="tvShowListForm" action="save_tv_to_list.php" method="POST">
                <input type="hidden" name="tv_show_id" id="modalTvShowId">
                <input type="hidden" name="action" id="modalAction">

                <div class="grid gap-4">
                    <div>
                        <label for="list_status" class="block mb-2 text-sm font-medium text-white">Status</label>
                        <select name="status" id="list_status" required
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white  dark:border-gray-600">
                            <option value="plan to watch">Plan to Watch</option>
                            <option value="watching">Watching</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On-Hold</option>
                            <option value="dropped">Dropped</option>
                        </select>
                    </div>

                    <div>
                        <label for="current_episode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Episode <span id="totalEpisodesInfo"></span></label>
                        <input type="number" name="current_episode" id="current_episode" min="0" value="0"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="current_season" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Current Season <span id="totalSeasonsInfo"></span></label>
                        <input type="number" name="current_season" id="current_season" min="0" value="0"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeTvShowListModal()" class="px-6 py-3 text-sm font-medium text-black bg-gray-300 rounded-lg hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-yellow rounded-lg hover:bg-citrine-hover focus:ring-4 focus:ring-citrine">
                            Save to List
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<div id="movieListModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white" id="movieModalTitle">Add Movie to My List</h2>
            <form id="movieListForm" action="save_movie_to_list.php" method="POST">
                <input type="hidden" name="movie_id" id="modalMovieId">
                <input type="hidden" name="movie_type" id="modalMovieType">
                <input type="hidden" name="action" id="modalMovieAction">

                <div class="grid gap-4">
                    <div>
                        <label for="movie_list_status" class="block mb-2 text-sm font-medium text-white">Status</label>
                        <select name="status" id="movie_list_status" required
                            class="w-full p-3 border border-gray-300 rounded-lg bg-white border-gray-600">
                            <option value="plan to watch">Plan to Watch</option>
                            <option value="watching">Watching</option>
                            <option value="ended">Ended</option>
                            <option value="dropped">Dropped</option>
                            <option value="on-hold">On-Hold</option>
                        </select>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeMovieListModal()" class="px-6 py-3 text-sm font-medium text-black bg-gray-300 rounded-lg hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-citrine dark:bg-citrine rounded-lg hover:bg-citrine-hover focus:ring-4 focus:ring-citrine">
                            Save to List
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include __DIR__ . './footer.php'; ?>


  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
  </a>


<script>
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

        function openTvShowListModal(tvId, actionType, currentStatus, currentEp, currentSe, totalEp, totalSe) {
            modalTvShowId.value = tvId;
            modalAction.value = actionType;

            if (actionType === 'add') {
                modalTitle.textContent = 'Add TV Show to My List';
                listStatus.value = currentStatus;
                currentEpisode.value = 0;
                currentSeason.value = 0;
            } else if (actionType === 'edit') {
                modalTitle.textContent = 'Edit My List Entry';
                listStatus.value = currentStatus;
                currentEpisode.value = currentEp;
                currentSeason.value = currentSe;
            }

            totalEpisodesInfo.textContent = totalEp > 0 ? `(out of ${totalEp})` : '';
            totalSeasonsInfo.textContent = totalSe > 0 ? `(out of ${totalSe})` : '';

            currentEpisode.max = totalEp;
            currentSeason.max = totalSe;

            tvShowListModal.classList.remove('hidden');
        }

        function closeTvShowListModal() {
            tvShowListModal.classList.add('hidden');
        }

  function openMovieListModal(movieId, movieType, actionType, currentStatus) {
            modalMovieId.value = movieId;
            modalMovieType.value = movieType;
            modalMovieAction.value = actionType;

            if (actionType === 'add') {
                movieModalTitle.textContent = 'Add Movie to My List';
                movieListStatus.value = currentStatus;
            } else if (actionType === 'edit') {
                movieModalTitle.textContent = 'Edit My List Entry';
                movieListStatus.value = currentStatus;
            }
            movieListModal.classList.remove('hidden');
        }

        function closeMovieListModal() {
            movieListModal.classList.add('hidden');
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

        tvShowListForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('save_tv_to_list.php', {
                method: 'POST',
                body: params
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeTvShowListModal();
                    location.reload(); // Reload page to update button state and profile list
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
        });

        // Handle Movie modal form submission
        movieListForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            const params = new URLSearchParams(formData);

            fetch('save_movie_to_list.php', {
                method: 'POST',
                body: params
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeMovieListModal();
                    location.reload(); // Reload page to update button state and profile list
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
        });
    });
</script>


  <script src="../Assets/js/script.js"></script>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>