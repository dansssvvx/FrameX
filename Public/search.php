<?php
session_start();

require_once '../Conf/database.php';
include_once '../Conf/info.php';

// Get search parameters
$query = $_GET['q'] ?? '';
$type = $_GET['type'] ?? 'multi';
$page = $_GET['page'] ?? 1;

// If no query, redirect to home
if (empty(trim($query))) {
    header('Location: ./index.php');
    exit;
}

$search_results = null;
$error = null;

if (!empty($query)) {
    // Direct API call to TMDB instead of going through our API file
    $api_url = "https://api.themoviedb.org/3/search/{$type}?api_key={$apikey}&query=" . urlencode($query) . "&page={$page}&include_adult=false";
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if (!$err && $http_code === 200) {
        $search_results = json_decode($response);
        if (!$search_results) {
            $error = 'Failed to parse search results';
        } elseif (isset($search_results->error)) {
            $error = 'API Error: ' . $search_results->error;
        }
    } else {
        if ($err) {
            $error = 'Connection Error: ' . $err;
        } else {
            switch ($http_code) {
                case 401:
                    $error = 'API Key Error: Invalid or missing API key';
                    break;
                case 403:
                    $error = 'Access Denied: API key does not have permission';
                    break;
                case 404:
                    $error = 'Search service not found';
                    break;
                case 429:
                    $error = 'Rate Limit Exceeded: Too many requests';
                    break;
                case 500:
                    $error = 'Server Error: TMDB service is temporarily unavailable';
                    break;
                default:
                    $error = 'Search service returned error code: ' . $http_code;
            }
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
  <title>Search Results - Frame X</title>

  <!-- favicon -->
  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">

  <!-- custom css link -->
  <link rel="stylesheet" href="../Assets/css/style.css">

  <!-- google font link -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">

<?php include __DIR__ . './header.php'; ?>

<main>
  <article>

    <!-- Search Results Section -->
    <section class="upcoming">
      <div class="container">

        <div class="flex-wrapper">
          <div class="title-wrapper">
            <p class="section-subtitle">Search Results</p>
            <h2 class="h2 section-title">
              <?php if (!empty($query)): ?>
                Results for "<?= htmlspecialchars($query) ?>"
              <?php else: ?>
                Search Results
              <?php endif; ?>
            </h2>
          </div>

          <!-- Search Type Filter -->
          <div class="filter-list">
            <button class="filter-btn <?= $type === 'multi' ? 'active' : '' ?>" onclick="changeSearchType('multi')">
              All
            </button>
            <button class="filter-btn <?= $type === 'movie' ? 'active' : '' ?>" onclick="changeSearchType('movie')">
              Movies
            </button>
            <button class="filter-btn <?= $type === 'tv' ? 'active' : '' ?>" onclick="changeSearchType('tv')">
              TV Shows
            </button>
          </div>
        </div>

        <?php if ($error): ?>
          <div class="error-message" style="text-align: center; color: #ff6b6b; margin: 50px 0;">
            <h3>Error</h3>
            <p><?= htmlspecialchars($error) ?></p>
            <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
              <div style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.3); border-radius: 5px; text-align: left; font-family: monospace; font-size: 12px;">
                <strong>Debug Info:</strong><br>
                Query: <?= htmlspecialchars($query) ?><br>
                Type: <?= htmlspecialchars($type) ?><br>
                Page: <?= htmlspecialchars($page) ?><br>
                API Key: <?= htmlspecialchars(substr($apikey, 0, 10)) ?>...<br>
                cURL Available: <?= function_exists('curl_init') ? 'Yes' : 'No' ?><br>
                PHP Version: <?= phpversion() ?>
              </div>
            <?php endif; ?>
            <div style="margin-top: 20px;">
              <a href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&page=<?= urlencode($page) ?>" class="btn btn-primary" style="margin-right: 10px;">
                Try Again
              </a>
              <a href="./index.php" class="btn btn-primary">
                Go to Home
              </a>
            </div>
          </div>
        <?php elseif ($search_results && isset($search_results->results) && count($search_results->results) > 0): ?>
          
          <ul class="movies-list has-scrollbar">
            <?php foreach ($search_results->results as $item): ?>
              <li>
                <div class="movie-card">
                  <a href="./details.php?id=<?= $item->id ?>&type=<?= $item->media_type ?? ($type === 'tv' ? 'tv' : 'movie') ?>">
                    <figure class="card-banner">
                      <?php if ($item->poster_path): ?>
                        <img src="https://image.tmdb.org/t/p/w500<?= $item->poster_path ?>" 
                             alt="<?= htmlspecialchars($item->title ?? $item->name) ?> poster">
                      <?php else: ?>
                        <img src="../Assets/images/icon.png" 
                             alt="No poster available" 
                             style="background: #333; padding: 20px;">
                      <?php endif; ?>
                    </figure>
                  </a>

                  <div class="title-wrapper">
                    <a href="./details.php?id=<?= $item->id ?>&type=<?= $item->media_type ?? ($type === 'tv' ? 'tv' : 'movie') ?>">
                      <h3 class="card-title"><?= htmlspecialchars($item->title ?? $item->name) ?></h3>
                    </a>
                    <time datetime="<?= htmlspecialchars(substr($item->release_date ?? $item->first_air_date ?? '', 0, 4)) ?>">
                      <?= htmlspecialchars(substr($item->release_date ?? $item->first_air_date ?? '', 0, 4)) ?>
                    </time>
                  </div>

                  <div class="card-meta">
                    <div class="badge badge-outline">
                      <?= htmlspecialchars(strtoupper($item->original_language ?? 'N/A')) ?>
                    </div>
                    <div class="duration">
                      <?php if (isset($item->media_type) && $item->media_type === 'tv'): ?>
                        TV Series
                      <?php else: ?>
                        Movie
                      <?php endif; ?>
                    </div>
                    <div class="rating">
                      <ion-icon name="star"></ion-icon>
                      <data><?= number_format($item->vote_average ?? 0, 1) ?>/10</data>
                    </div>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>

          <!-- Pagination -->
          <?php if ($search_results->total_pages > 1): ?>
            <div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 50px;">
              <?php if ($page > 1): ?>
                <a href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&page=<?= $page - 1 ?>" 
                   class="btn btn-primary">Previous</a>
              <?php endif; ?>
              
              <span style="color: var(--white); display: flex; align-items: center;">
                Page <?= $page ?> of <?= $search_results->total_pages ?>
              </span>
              
              <?php if ($page < $search_results->total_pages): ?>
                <a href="?q=<?= urlencode($query) ?>&type=<?= urlencode($type) ?>&page=<?= $page + 1 ?>" 
                   class="btn btn-primary">Next</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div class="no-results" style="text-align: center; margin: 50px 0;">
            <h3 style="color: var(--white); margin-bottom: 20px;">No results found</h3>
            <p style="color: var(--light-gray);">
              Try searching with different keywords or browse our collections.
            </p>
            <a href="./index.php" class="btn btn-primary" style="margin-top: 20px; display: inline-block;">
              Go to Home
            </a>
          </div>
        <?php endif; ?>

      </div>
    </section>

  </article>
</main>

<?php include __DIR__ . './footer.php'; ?>

<script>
function changeSearchType(newType) {
  const currentUrl = new URL(window.location);
  currentUrl.searchParams.set('type', newType);
  currentUrl.searchParams.set('page', '1'); // Reset to first page
  window.location.href = currentUrl.toString();
}
</script>

<!-- ionicon link -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html> 