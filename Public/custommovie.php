<?php

session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ../Auth/login.php");
    exit;
}

include "../Conf/info.php";
include "../Conf/database.php";

$stmt = $db->query("SELECT * FROM custom_movie ORDER BY created_at DESC LIMIT 10");
$custom_movie = $stmt->fetchAll();

$stmt1 = $db->query("SELECT * FROM custom_tv_show ORDER BY created_at DESC LIMIT 10");
$custom_tvshow = $stmt1->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Custom | Frame X</title>
    <script src="https://cdn.tailwindcss.com"></script>

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
<body class="top">
<?php include_once __DIR__ . './header.php';?>

<section class="upcoming">
    <div class="container">

        <div class="flex- wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Check our -</p>
                <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>Custom Movie Premieres</strong></h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($custom_movie as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p['id'] ?>&type=custommovie">
                        <figure class="card-banner">
                            <img src="<?php echo $p['poster_path']?>" alt="<?php echo htmlspecialchars($p['title']) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p['id']?>&type=custommovie">
                            <h3 class="card-title"><?php echo htmlspecialchars($p['title']) ?></h3>
                        </a>
                      <time datetime="<?php echo htmlspecialchars(substr($p['release_date'], 0, 4)) ?>">
                          <?php echo htmlspecialchars(substr($p['release_date'], 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                       <div class="badge badge-outline"><?php echo htmlspecialchars(strtoupper($p['status'])) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?php echo number_format($p['vote_average'] ?? 0, 1) ?>/10</data>
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

        <div class="flex- wrapper">

            <div class="title-wrapper">
                <p class="section-subtitle">Check our -</p>
                <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>Custom TV Show Exclusives</strong></h2>
            </div>
            </div>

        <ul class="movies-list has-scrollbar" id="anime_collection">
           <?php foreach ($custom_tvshow as $p): ?>
            <li data-category="<?= htmlspecialchars($p-> genre_ids[0]) ?>">
                <div class="movie-card">
                    <a href="./details.php?id=<?= $p['id'] ?>&type=customtvshow">
                        <figure class="card-banner">
                            <img src="<?php echo $p['poster_path']?>" alt="<?php echo htmlspecialchars($p['title']) ?> movie poster">
                        </figure>
                    </a>

                    <div class="title-wrapper">
                        <a href="./details.php?id=<?= $p['id']?>&type=customtvshow">
                            <h3 class="card-title"><?php echo htmlspecialchars($p['title']) ?></h3>
                        </a>
                      <time datetime="<?php echo htmlspecialchars(substr($p['first_air_date'], 0, 4)) ?>">
                          <?php echo htmlspecialchars(substr($p['first_air_date'], 0, 4)) ?>
                      </time>
                    </div>

                    <div class="card-meta">
                       <div class="badge badge-outline"><?php echo htmlspecialchars(strtoupper($p['status'])) ?></div>
                        <div class="duration">
                        </div>
                        <div class="rating">
                            <ion-icon name="star"></ion-icon>
                            <data><?php echo number_format($p['vote_average'] ?? 0, 1) ?>/10</data>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>

    <?php include_once __DIR__ . './footer.php';?>

</body>

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

</html>