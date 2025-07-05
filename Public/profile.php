<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/info.php";
include "../Conf/database.php";

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT username, email, is_admin, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ../Auth/logout.php");
    exit;
}

// Get statistics
$stmt_tv_count = $db->prepare("SELECT COUNT(*) FROM user_tv_list WHERE user_id = ?");
$stmt_tv_count->execute([$user_id]);
$tv_count = $stmt_tv_count->fetchColumn();

$stmt_movie_count = $db->prepare("SELECT COUNT(*) FROM user_movies WHERE user_id = ?");
$stmt_movie_count->execute([$user_id]);
$movie_count = $stmt_movie_count->fetchColumn();

$stmt_watching = $db->prepare("SELECT COUNT(*) FROM user_tv_list WHERE user_id = ? AND status = 'watching'");
$stmt_watching->execute([$user_id]);
$watching_count = $stmt_watching->fetchColumn();

$stmt_completed = $db->prepare("SELECT COUNT(*) FROM user_tv_list WHERE user_id = ? AND status = 'ended'");
$stmt_completed->execute([$user_id]);
$completed_count = $stmt_completed->fetchColumn();

// --- Fetch user's saved TV shows with improved error handling ---
$saved_tv_shows = [];
$stmt_saved_tv = $db->prepare("SELECT tv_show_id, status, current_episode, current_season, total_episodes, total_seasons, added_at FROM user_tv_list WHERE user_id = ? ORDER BY added_at DESC LIMIT 20");
$stmt_saved_tv->execute([$user_id]);
$tv_data_from_list = $stmt_saved_tv->fetchAll(PDO::FETCH_ASSOC);

foreach ($tv_data_from_list as $saved_entry) {
    $id_tv = $saved_entry['tv_show_id'];
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.themoviedb.org/3/tv/{$id_tv}?api_key=" . $apikey,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
        CURLOPT_TIMEOUT => 10,
    ]);
    
    $response_tv_id = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if (!$err) {
        $tv_show_details = json_decode($response_tv_id);
        if ($tv_show_details && property_exists($tv_show_details, 'name')) {
            $tv_show_details->user_status = $saved_entry['status'];
            $tv_show_details->user_current_episode = $saved_entry['current_episode'];
            $tv_show_details->user_current_season = $saved_entry['current_season'];
            $tv_show_details->number_of_episodes = $saved_entry['total_episodes'];
            $tv_show_details->number_of_seasons = $saved_entry['total_seasons'];
            $tv_show_details->added_at = $saved_entry['added_at'];
            $saved_tv_shows[] = $tv_show_details;
        }
    }
}

// --- Fetch user's saved Movies with improved error handling ---
$saved_movies = [];
$stmt_saved_movies = $db->prepare("SELECT movie_id, status, movie_type, added_at FROM user_movies WHERE user_id = ? ORDER BY added_at DESC LIMIT 20");
$stmt_saved_movies->execute([$user_id]);
$movie_data_from_list = $stmt_saved_movies->fetchAll(PDO::FETCH_ASSOC);

foreach ($movie_data_from_list as $saved_movie_entry) {
    $movie_id_from_list = $saved_movie_entry['movie_id'];
    $movie_type_from_list = $saved_movie_entry['movie_type'];

    if ($movie_type_from_list === 'api_movie') {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3/movie/{$movie_id_from_list}?api_key=" . $apikey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ["Accept: application/json"],
            CURLOPT_TIMEOUT => 10,
        ]);
        
        $response_movie_id = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!$err) {
            $movie_details = json_decode($response_movie_id);
            if ($movie_details && property_exists($movie_details, 'title')) {
                $movie_details->user_status = $saved_movie_entry['status'];
                $movie_details->saved_movie_type = $movie_type_from_list;
                $movie_details->added_at = $saved_movie_entry['added_at'];
                $saved_movies[] = $movie_details;
            }
        }
    } elseif ($movie_type_from_list === 'custom_movie') {
        $stmt_custom_movie = $db->prepare("SELECT * FROM custom_movie WHERE id = ?");
        $stmt_custom_movie->execute([$movie_id_from_list]);
        $custom_movie_details = $stmt_custom_movie->fetch(PDO::FETCH_ASSOC);

        if ($custom_movie_details) {
            $movie_obj = (object)$custom_movie_details;
            $movie_obj->user_status = $saved_movie_entry['status'];
            $movie_obj->saved_movie_type = $movie_type_from_list;
            $movie_obj->added_at = $saved_movie_entry['added_at'];
            $saved_movies[] = $movie_obj;
        }
    }
}

// Calculate profile completion percentage
$total_items = $tv_count + $movie_count;
$profile_completion = $total_items > 0 ? min(100, ($total_items / 50) * 100) : 0; // 50 items = 100%
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile | Frame X</title>

  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
  <link rel="stylesheet" href="../Assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    .profile-header {
      background: linear-gradient(135deg, var(--eerie-black) 0%, var(--rich-black-fogra-29) 100%);
      border-radius: 20px;
      padding: 40px;
      margin-bottom: 40px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .profile-avatar {
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, var(--citrine) 0%, #ffd700 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
      margin: 30px 0;
    }
    
    .stat-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 15px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      border-color: var(--citrine);
    }
    
    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--citrine);
      margin-bottom: 5px;
    }
    
    .stat-label {
      color: var(--light-gray);
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .progress-bar {
      width: 100%;
      height: 8px;
      background: rgba(255,255,255,0.1);
      border-radius: 4px;
      overflow: hidden;
      margin: 10px 0;
    }
    
    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--citrine) 0%, #ffd700 100%);
      border-radius: 4px;
      transition: width 0.3s ease;
    }
    
    .collection-tabs {
      display: flex;
      justify-content: center;
      margin: 40px 0 30px;
      gap: 10px;
    }
    
    .tab-btn {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      color: var(--light-gray);
      padding: 12px 24px;
      border-radius: 25px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    .tab-btn.active {
      background: var(--citrine);
      color: var(--black);
      border-color: var(--citrine);
    }
    
    .tab-btn:hover {
      background: rgba(255,255,255,0.1);
      border-color: var(--citrine);
    }
    
    .collection-section {
      display: none;
    }
    
    .collection-section.active {
      display: block;
    }
    
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: rgba(255,255,255,0.02);
      border-radius: 15px;
      border: 2px dashed rgba(255,255,255,0.1);
    }
    
    .empty-state ion-icon {
      font-size: 4rem;
      color: var(--citrine);
      margin-bottom: 20px;
    }
    
    .quick-actions {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .action-btn {
      background: var(--citrine);
      color: var(--black);
      padding: 12px 20px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .action-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }
    
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .status-watching { background: #4CAF50; color: white; }
    .status-completed { background: #2196F3; color: white; }
    .status-plan { background: #FF9800; color: white; }
    .status-dropped { background: #F44336; color: white; }
    .status-hold { background: #9C27B0; color: white; }
    
    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .collection-tabs {
        flex-direction: column;
        align-items: center;
      }
      
      .quick-actions {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>

<body id="top">
<?php include_once __DIR__ . './header.php';?>

<main>
  <article>
    <section class="upcoming" style="padding-top: 100px; padding-bottom: 50px;">
      <div class="container">
        
        <!-- Profile Header -->
        <div class="profile-header">
          <div class="profile-avatar">
            <ion-icon name="person" style="font-size: 60px; color: var(--black);"></ion-icon>
          </div>
          
          <div class="text-center">
            <h1 class="h1" style="color: var(--white); margin-bottom: 10px;"><?php echo htmlspecialchars($user['username']); ?></h1>
            <p style="color: var(--light-gray); margin-bottom: 20px;"><?php echo htmlspecialchars($user['email']); ?></p>
            
            <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 30px;">
              <span class="badge " style="color: var(--citrine);">
                <ion-icon name="shield-checkmark" style="margin-right: 5px;"></ion-icon>
                <?php echo $user['is_admin'] ? 'Administrator' : 'Member'; ?>
              </span>
              <span class="badge " style="color: var(--citrine);">
                <ion-icon name="calendar" style="margin-right: 5px;"></ion-icon>
                Member since <?php echo date('M Y', strtotime($user['created_at'])); ?>
              </span>
            </div>
            
            <!-- Profile Completion -->
            <div style="margin-bottom: 20px;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: var(--light-gray);">Profile Completion</span>
                <span style="color: var(--citrine); font-weight: 600;"><?php echo round($profile_completion); ?>%</span>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width: <?php echo $profile_completion; ?>%"></div>
              </div>
            </div>
          </div>
          
          <!-- Statistics Grid -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-number"><?php echo $tv_count; ?></div>
              <div class="stat-label">TV Shows</div>
            </div>
            <div class="stat-card">
              <div class="stat-number"><?php echo $movie_count; ?></div>
              <div class="stat-label">Movies</div>
            </div>
            <div class="stat-card">
              <div class="stat-number"><?php echo $watching_count; ?></div>
              <div class="stat-label">Watching</div>
            </div>
            <div class="stat-card">
              <div class="stat-number"><?php echo $completed_count; ?></div>
              <div class="stat-label">Completed</div>
            </div>
          </div>
          
          <!-- Quick Actions -->
          <div class="quick-actions">
            <a href="./index.php" class="action-btn">
              <ion-icon name="home"></ion-icon>
              Browse Content
            </a>
            <a href="./search.php?q=&type=multi" class="action-btn">
              <ion-icon name="search"></ion-icon>
              Search
            </a>
            <a href="../Auth/logout.php" class="action-btn" style="background: rgba(244, 67, 54, 0.8);">
              <ion-icon name="log-out"></ion-icon>
              Log Out
            </a>
          </div>
        </div>

        <!-- Collection Tabs -->
        <div class="collection-tabs">
          <button class="tab-btn active" onclick="showTab('tv')">
            <ion-icon name="tv" style="margin-right: 8px;"></ion-icon>
            TV Shows (<?php echo $tv_count; ?>)
          </button>
          <button class="tab-btn" onclick="showTab('movies')">
            <ion-icon name="film" style="margin-right: 8px;"></ion-icon>
            Movies (<?php echo $movie_count; ?>)
          </button>
        </div>

        <!-- TV Shows Collection -->
        <div id="tv-collection" class="collection-section active">
          <?php if (!empty($saved_tv_shows)): ?>
            <ul class="movies-list has-scrollbar">
              <?php foreach ($saved_tv_shows as $show): ?>
                <?php
                switch($show->user_status) {
                    case 'watching': $icon = 'play-circle'; break;
                    case 'completed':
                    case 'ended': $icon = 'checkmark-circle'; break;
                    case 'plan to watch':
                    case 'plan': $icon = 'time'; break;
                    case 'dropped': $icon = 'close-circle'; break;
                    case 'on-hold':
                    case 'hold': $icon = 'pause-circle'; break;
                    default: $icon = 'ellipse';
                }
                ?>
                <li>
                  <div class="movie-card">
                    <a href="./details.php?id=<?= $show->id ?>&type=tv">
                      <figure class="card-banner">
                        <?php if ($show->poster_path): ?>
                          <img src="https://image.tmdb.org/t/p/w500<?= htmlspecialchars($show->poster_path) ?>" 
                               alt="<?= htmlspecialchars($show->name) ?> poster">
                        <?php else: ?>
                          <img src="../Assets/images/icon.png" 
                               alt="No poster available" 
                               style="background: #333; padding: 20px;">
                        <?php endif; ?>
                      </figure>
                    </a>

                    <div class="title-wrapper">
                      <a href="./details.php?id=<?= $show->id ?>&type=tv">
                        <h3 class="card-title"><?= htmlspecialchars($show->name) ?></h3>
                      </a>
                      <time datetime="<?= htmlspecialchars(substr($show->first_air_date ?? '', 0, 4)) ?>">
                        <?= htmlspecialchars(substr($show->first_air_date ?? '', 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                      <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($show->original_language ?? 'N/A')) ?></div>
                      <div class="duration">
                        <ion-icon name="albums-outline"></ion-icon>
                        <data><?= htmlspecialchars($show->user_current_episode) ?> / <?= htmlspecialchars($show->number_of_episodes) ?></data>
                      </div>
                      <div class="rating">
                        <ion-icon name="star"></ion-icon>
                        <data><?= number_format($show->vote_average ?? 0, 1) ?>/10</data>
                      </div>
                    </div>
                    
                    <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                      <span class="status-badge status-<?= str_replace('-', '', $show->user_status) ?>">
                        <ion-icon name="<?= $icon ?>" style="margin-right: 4px; font-size: 14px;"></ion-icon>
                        <?= htmlspecialchars(ucwords(str_replace('-', ' ', $show->user_status))) ?>
                      </span>
                      <small style="color: var(--light-gray);">
                        <?= date('M d', strtotime($show->added_at)) ?>
                      </small>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <div class="empty-state">
              <ion-icon name="tv-outline"></ion-icon>
              <h3 style="color: var(--white); margin-bottom: 10px;">No TV Shows Yet</h3>
              <p style="color: var(--light-gray); margin-bottom: 20px;">
                Start building your TV show collection by adding shows from the details page!
              </p>
              <a href="./tvshow.php" class="action-btn">
                <ion-icon name="add"></ion-icon>
                Browse TV Shows
              </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Movies Collection -->
        <div id="movies-collection" class="collection-section">
          <?php if (!empty($saved_movies)): ?>
            <ul class="movies-list has-scrollbar">
              <?php foreach ($saved_movies as $movie): ?>
                <?php
                switch($movie->user_status) {
                    case 'watching': $icon = 'play-circle'; break;
                    case 'completed':
                    case 'ended': $icon = 'checkmark-circle'; break;
                    case 'plan to watch':
                    case 'plan': $icon = 'time'; break;
                    case 'dropped': $icon = 'close-circle'; break;
                    case 'on-hold':
                    case 'hold': $icon = 'pause-circle'; break;
                    default: $icon = 'ellipse';
                }
                ?>
                <li>
                  <div class="movie-card">
                    <a href="./details.php?id=<?= $movie->id ?>&type=<?= $movie->saved_movie_type == 'api_movie' ? 'movie' : 'custom_movie' ?>">
                      <figure class="card-banner">
                        <img src="<?php
                          if ($movie->saved_movie_type == 'api_movie') {
                            echo 'https://image.tmdb.org/t/p/w500' . htmlspecialchars($movie->poster_path);
                          } else {
                            echo htmlspecialchars($movie->poster_path);
                          }
                        ?>" alt="<?= htmlspecialchars($movie->title ?? $movie->name) ?> poster">
                      </figure>
                    </a>

                    <div class="title-wrapper">
                      <a href="./details.php?id=<?= $movie->id ?>&type=<?= $movie->saved_movie_type == 'api_movie' ? 'movie' : 'custom_movie' ?>">
                        <h3 class="card-title"><?= htmlspecialchars($movie->title ?? $movie->name) ?></h3>
                      </a>
                      <time datetime="<?= htmlspecialchars(substr($movie->release_date ?? '', 0, 4)) ?>">
                        <?= htmlspecialchars(substr($movie->release_date ?? '', 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                      <div class="badge badge-outline"><?= htmlspecialchars(strtoupper($movie->original_language ?? 'N/A')) ?></div>
                      <div class="rating">
                        <ion-icon name="star"></ion-icon>
                        <data><?= number_format($movie->vote_average ?? 0, 1) ?>/10</data>
                      </div>
                    </div>
                    
                    <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                      <span class="status-badge status-<?= str_replace('-', '', $movie->user_status) ?>">
                        <ion-icon name="<?= $icon ?>" style="margin-right: 4px; font-size: 14px;"></ion-icon>
                        <?= htmlspecialchars(ucwords(str_replace('-', ' ', $movie->user_status))) ?>
                      </span>
                      <small style="color: var(--light-gray);">
                        <?= date('M d', strtotime($movie->added_at)) ?>
                      </small>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <div class="empty-state">
              <ion-icon name="film-outline"></ion-icon>
              <h3 style="color: var(--white); margin-bottom: 10px;">No Movies Yet</h3>
              <p style="color: var(--light-gray); margin-bottom: 20px;">
                Start building your movie collection by adding movies from the details page!
              </p>
              <a href="./index.php" class="action-btn">
                <ion-icon name="add"></ion-icon>
                Browse Movies
              </a>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </section>
  </article>
</main>

<?php include_once __DIR__ . './footer.php';?>

<script>
function showTab(tabName) {
  // Hide all collection sections
  document.querySelectorAll('.collection-section').forEach(section => {
    section.classList.remove('active');
  });
  
  // Remove active class from all tabs
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  
  // Show selected section
  document.getElementById(tabName + '-collection').classList.add('active');
  
  // Add active class to clicked tab
  event.target.classList.add('active');
}

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
  // Animate stats on load
  const statNumbers = document.querySelectorAll('.stat-number');
  statNumbers.forEach(stat => {
    const finalValue = parseInt(stat.textContent);
    let currentValue = 0;
    const increment = finalValue / 20;
    
    const timer = setInterval(() => {
      currentValue += increment;
      if (currentValue >= finalValue) {
        stat.textContent = finalValue;
        clearInterval(timer);
      } else {
        stat.textContent = Math.floor(currentValue);
      }
    }, 50);
  });
  
  // Animate progress bar
  const progressFill = document.querySelector('.progress-fill');
  const targetWidth = progressFill.style.width;
  progressFill.style.width = '0%';
  
  setTimeout(() => {
    progressFill.style.width = targetWidth;
  }, 500);
});
</script>

<!-- ionicon link -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>