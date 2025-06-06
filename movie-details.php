<?php
require_once __DIR__ . '/config/database.php';

$movie_id = $_GET['id'] ?? null;

if (!$movie_id) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->execute([$movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
    
    if (!$movie) {
        header('Location: index.php'); // Redirect if movie not found
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Function to convert minutes to "X min" format
function formatDuration($minutes) {
    if ($minutes <= 0) return '0 min';
    return $minutes . ' min';
}

// Prepare dynamic title for the page
$pageTitle = $movie['title'] . ' ' . $movie['year'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>

  <link rel="shortcut icon" href="./icon.png" type="image/svg+xml">

  <link rel="stylesheet" href="./assets/css/style.css">

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
            <a href="./index.php" class="navbar-link">Home</a>
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
            <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> movie poster">
          
            <button class="play-btn" onclick="window.open('<?php echo htmlspecialchars($movie['trailer_url']); ?>', '_blank')">
            <ion-icon name="play-circle-outline"></ion-icon>
          </button>

          </figure>

          <div class="movie-detail-content">    
            <h1 class="h1 detail-title">
              <?php echo htmlspecialchars($movie['title']); ?>
            </h1>

            <div class="meta-wrapper">

              <div class="badge-wrapper">
                <div class="badge badge-outline"><?php echo htmlspecialchars(strtoupper($movie['quality'])); ?></div>
              </div>

              <div class="ganre-wrapper">
                <a href="#"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $movie['category']))); ?></a>
                <?php if ($movie['category'] === 'movie') { ?>
                    <?php } ?>
              </div>

              <div class="date-time">

                <div>
                  <ion-icon name="calendar-outline"></ion-icon>
                  <time datetime="<?php echo htmlspecialchars($movie['year']); ?>"><?php echo htmlspecialchars($movie['year']); ?></time>
                </div>

                <div>
                  <ion-icon name="time-outline"></ion-icon>
                  <time datetime="PT<?php echo htmlspecialchars($movie['duration']); ?>M"><?php echo htmlspecialchars(formatDuration($movie['duration'])); ?></time>
                </div>

              </div>

            </div>

            <p class="storyline">
              <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
            </p>

            <div class="details-actions">

              <button class="share">
                <ion-icon name="share-social"></ion-icon>
                <span>Share</span>
              </button>

              <div class="title-wrapper">
                <p class="title">IMDb Rating: <?php echo htmlspecialchars($movie['rating']); ?></p>
                <p class="text">Rating</p>
              </div>

              <button class="btn btn-primary">
                <ion-icon name="play"></ion-icon>
                <span>Add To Your List</span>
              </button>

            </div>

            <a href="<?php echo htmlspecialchars($movie['poster_url']); ?>" download class="download-btn">
              <span>Download Poster</span>
              <ion-icon name="download-outline"></ion-icon>
            </a>

          </div>

        </div>
      </section>


    </article>
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
              <a href="#" class="footer-link">Pricing</a>
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