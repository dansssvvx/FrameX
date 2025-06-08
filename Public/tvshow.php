<?php

session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: ./login.php");
    exit;
}
include "../Conf/info.php";
include "../API/api_tv.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trending | Frame X</title>
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
     
     <style>
      :root {
        --lerp-0: 1;
        --lerp-1: 0.5787037;
        --lerp-2: 0.2962963;
        --lerp-3: 0.125;
        --lerp-4: 0.037037;
        --lerp-5: 0.0046296;
        --lerp-6: 0;
      }

      .interactive-gallery ul:is(:hover, :focus-within) img {
        opacity: calc(0.1 + (var(--active-lerp, 0) * 0.9));
        filter: grayscale(calc(1 - var(--active-lerp, 0)));
      }

      .interactive-gallery li {
        flex: calc(0.1 + (var(--active-lerp, 0) * 1));
        transition: flex 0.2s ease;
      }

      .interactive-gallery li:is(:hover, :focus-within) {
        --active-lerp: var(--lerp-0);
        z-index: 7;
      }
      .interactive-gallery li:has(+ li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li {
        --active-lerp: var(--lerp-1);
        z-index: 6;
      }
      .interactive-gallery li:has(+ li + li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li + li {
        --active-lerp: var(--lerp-2);
        z-index: 5;
      }
      .interactive-gallery li:has(+ li + li + li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li + li + li {
        --active-lerp: var(--lerp-3);
        z-index: 4;
      }
      .interactive-gallery li:has(+ li + li + li + li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li + li + li + li {
        --active-lerp: var(--lerp-4);
        z-index: 3;
      }
      .interactive-gallery li:has(+ li + li + li + li + li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li + li + li + li + li {
        --active-lerp: var(--lerp-5);
        z-index: 2;
      }
      .interactive-gallery li:has(+ li + li + li + li + li + li:is(:hover, :focus-within)),
      .interactive-gallery li:is(:hover, :focus-within) + li + li + li + li + li + li {
        --active-lerp: var(--lerp-6);
        z-index: 1;
      }
    </style>
</head>
<body class="top">
<?php include_once __DIR__ . '/header.php';?>
<body class="min-h-screen grid place-items-center" style="
    margin-bottom: 30px;
    padding-bottom: 96px;
    padding-top: 96px;
">
    <div class="container py-16">
        <div class="interactive-gallery">
            <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 p-0 m-0 list-none">
                <?php

                if (isset($tv_trending) && is_object($tv_trending) && isset($tv_trending->results) && is_array($tv_trending->results) && !empty($tv_trending->results)) {
                    foreach ($tv_trending->results as $t) :

                        $image_path = $t->poster_path;
                        $image_url = $image_path ? "https://image.tmdb.org/t/p/w500" . htmlspecialchars($image_path) : 'https://dummyimage.com/500x750/cccccc/000000.png&text=No+Image';
                        
                ?>
                        <li class="transition-all">
                            <a href="./details.php?id=<?= htmlspecialchars($t->id) ?>&type=<?= htmlspecialchars($t->media_type) ?>" 
                               class="outline-offset-4 block">
                                <img
                                    src="<?= $image_url ?>"
                                    alt="<?= htmlspecialchars($t->name) ?>"
                                    class="w-full max-w-full object-cover transition-all duration-200 ease-in-out"
                                />
                            </a>
                        </li>
                <?php
                    endforeach;
                } else {
                    echo '<li class="w-full p-4 text-center text-gray-500">Tidak ada item trending yang tersedia untuk galeri ini.</li>';
                }
                ?>
            </ul>
        </div>
    </div>


</body>
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