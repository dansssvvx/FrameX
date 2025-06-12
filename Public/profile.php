<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/info.php"; // Contains $apikey
include "../Conf/database.php";

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT username, email, is_admin, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ../Auth/logout.php");
    exit;
}

// --- Fetch user's saved TV shows ---
$saved_tv_shows = [];
$stmt_saved_tv = $db->prepare("SELECT tv_show_id, status, current_episode, current_season, total_episodes, total_seasons FROM user_tv_list WHERE user_id = ? ORDER BY added_at DESC");
$stmt_saved_tv->execute([$user_id]);
$tv_data_from_list = $stmt_saved_tv->fetchAll(PDO::FETCH_ASSOC);

foreach ($tv_data_from_list as $saved_entry) {
    $id_tv = $saved_entry['tv_show_id'];
    // Fetch TV show details from TMDB API for each ID
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://api.themoviedb.org/3/tv/{$id_tv}?api_key=" . $apikey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
    ]);
    $response_tv_id = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if (!$err) {
        $tv_show_details = json_decode($response_tv_id);
        if ($tv_show_details && property_exists($tv_show_details, 'name')) {
            // Merge saved list data with API details
            $tv_show_details->user_status = $saved_entry['status'];
            $tv_show_details->user_current_episode = $saved_entry['current_episode'];
            $tv_show_details->user_current_season = $saved_entry['current_season'];
            // We use saved_entry for total_episodes/seasons if stored in DB, otherwise fall back to API (if needed)
            $tv_show_details->number_of_episodes = $saved_entry['total_episodes'];
            $tv_show_details->number_of_seasons = $saved_entry['total_seasons'];
            $saved_tv_shows[] = $tv_show_details;
        }
    }
}


// --- Fetch user's saved Movies ---
$saved_movies = [];
$stmt_saved_movies = $db->prepare("SELECT movie_id, status, movie_type FROM user_movies WHERE user_id = ? ORDER BY added_at DESC");
$stmt_saved_movies->execute([$user_id]);
$movie_data_from_list = $stmt_saved_movies->fetchAll(PDO::FETCH_ASSOC);

foreach ($movie_data_from_list as $saved_movie_entry) {
    $movie_id_from_list = $saved_movie_entry['movie_id'];
    $movie_type_from_list = $saved_movie_entry['movie_type'];

    if ($movie_type_from_list === 'api_movie') {
        // Fetch movie details from TMDB API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://api.themoviedb.org/3/movie/{$movie_id_from_list}?api_key=" . $apikey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ["Accept: application/json"],
        ]);
        $response_movie_id = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!$err) {
            $movie_details = json_decode($response_movie_id);
            if ($movie_details && property_exists($movie_details, 'title')) {
                $movie_details->user_status = $saved_movie_entry['status'];
                $movie_details->saved_movie_type = $movie_type_from_list; // Add type for distinction
                $saved_movies[] = $movie_details;
            }
        }
    } elseif ($movie_type_from_list === 'custom_movie') {
        // Fetch movie details from custom_movie table
        $stmt_custom_movie = $db->prepare("SELECT * FROM custom_movie WHERE id = ?");
        $stmt_custom_movie->execute([$movie_id_from_list]);
        $custom_movie_details = $stmt_custom_movie->fetch(PDO::FETCH_ASSOC);

        if ($custom_movie_details) {
            // Convert array to object for consistent property access in display
            $movie_obj = (object)$custom_movie_details;
            $movie_obj->user_status = $saved_movie_entry['status'];
            $movie_obj->saved_movie_type = $movie_type_from_list; // Add type for distinction
            $saved_movies[] = $movie_obj;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile | Frame X</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">

  <link rel="stylesheet" href="../Assets/css/style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="top">
<?php include_once __DIR__ . './header.php';?>

<section class="upcoming" style="padding-top: 100px; padding-bottom: 50px;">
    <div class="container">
        <div class="title-wrapper">
            <p class="section-subtitle">Your Personal Hub</p>
            <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>User Profile</strong></h2>
        </div>

        <div class="flex flex-col items-center justify-center bg-raisin-black p-8 rounded-lg shadow-lg max-w-md mx-auto">
            <div class="text-center">
                <ion-icon name="person-circle-outline" style="font-size: 80px; color: var(--citrine);"></ion-icon>
                <h3 class="text-white text-2xl font-bold mt-4"><?php echo htmlspecialchars($user['username']); ?></h3>
                <p class="text-gray-400 text-md"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <div class="mt-8 w-full">
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-300">Account Type:</span>
                    <span class="text-white"><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-700">
                    <span class="text-gray-300">Member Since:</span>
                    <span class="text-white"><?php echo htmlspecialchars(date('M d, Y', strtotime($user['created_at']))); ?></span>
                </div>
            </div>

            <div class="mt-8">
                <a href="../Auth/logout.php" class="btn btn-primary">Log Out</a>
            </div>
        </div>

        <div class="title-wrapper" style="margin-top: 50px;">
            <p class="section-subtitle">Your TV Show Collection</p>
            <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>My Saved TV Shows</strong></h2>
        </div>

        <?php if (!empty($saved_tv_shows)): ?>
        <ul class="movies-list has-scrollbar">
           <?php foreach ($saved_tv_shows as $p): ?>
            <li>
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p->id ?>&type=tv">
                        <figure class="card-banner">
                            <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($p->poster_path)?>" alt="<?= htmlspecialchars($p->name) ?> TV show poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p->id?>&type=tv">
                            <h3 class="card-title"><?= htmlspecialchars($p->name) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($p->first_air_date, 0, 4)) ?>">
                          <?= htmlspecialchars(substr($p->first_air_date, 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                       <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($p->original_language)) ?></div>
                        <div class="duration">
                          <ion-icon name="albums-outline"></ion-icon>
                          <data><?= htmlspecialchars($p->user_current_episode) ?> / <?= htmlspecialchars($p->number_of_episodes) ?></data>
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($p->vote_average, 1) ?>/10</data>
                        </div>
                    </div>
                     <p class="text-gray-400" style="margin-top: 10px;">Status: <span style="color: var(--citrine);"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $p->user_status))) ?></span></p>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <p class="text-center text-gray-400 mt-10">You haven't saved any TV shows yet. Start adding from the TV Show details page!</p>
        <?php endif; ?>

        <div class="title-wrapper" style="margin-top: 50px;">
            <p class="section-subtitle">Your Movie Collection</p>
            <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>My Saved Movies</strong></h2>
        </div>

        <?php if (!empty($saved_movies)): ?>
        <ul class="movies-list has-scrollbar">
           <?php foreach ($saved_movies as $m): ?>
            <li>
                <div class="movie-card">
                    <a href="./details.php?id=<?= $m->id ?>&type=<?= $m->saved_movie_type == 'api_movie' ? 'movie' : 'custom_movie' ?>">
                        <figure class="card-banner">
                            <img src="<?php
                                if ($m->saved_movie_type == 'api_movie') {
                                    echo 'https://image.tmdb.org/t/p/w500' . htmlspecialchars($m->poster_path);
                                } else { // custom_movie
                                    echo htmlspecialchars($m->poster_path); // Assuming custom movies have full paths
                                }
                            ?>" alt="<?= htmlspecialchars($m->title ?? $m->name) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $m->id?>&type=<?= $m->saved_movie_type == 'api_movie' ? 'movie' : 'custom_movie' ?>">
                            <h3 class="card-title"><?= htmlspecialchars($m->title ?? $m->name) ?></h3>
                        </a>
                      <time datetime="<?= htmlspecialchars(substr($m->release_date ?? '', 0, 4)) ?>">
                          <?= htmlspecialchars(substr($m->release_date ?? '', 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                       <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($m->original_language ?? '')) ?></div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?= number_format($m->vote_average ?? 0, 1) ?>/10</data>
                        </div>
                    </div>
                     <p class="text-gray-400" style="margin-top: 10px;">Status: <span style="color: var(--citrine);"><?= htmlspecialchars(ucwords(str_replace('-', ' ', $m->user_status))) ?></span></p>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <p class="text-center text-gray-400 mt-10">You haven't saved any movies yet. Start adding from the Movie details page!</p>
        <?php endif; ?>

    </div>
</section>

<?php include_once __DIR__ . './footer.php';?>

</body>
<a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
</a>
<script src="../Assets/js/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</html>