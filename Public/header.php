  <header class="header" data-header>
    <div class="container">

      <div class="overlay" data-overlay></div>

      <a href="./index.php" class="logo">
        <img src="../Assets/images/iconname.png" alt="Frame X logo" style="width: 100px; height: 43.2px;">
      </a>

      <div class="header-actions">

        <!-- Search Form -->
        <form class="search-form" action="./search.php" method="GET" style="display: flex; align-items: center; gap: 10px;">
          <input type="text" name="q" placeholder="Search movies & TV shows..." 
                 class="search-input" 
                 style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); 
                        color: white; padding: 8px 12px; border-radius: 5px; min-width: 200px;
                        font-size: 14px;">
          <button type="submit" class="search-btn" style="background: none; border: none; cursor: pointer;">
            <ion-icon name="search-outline"></ion-icon>
          </button>
        </form>
        
        <?php if (isset($_SESSION['user_logged_in'])): ?>
        <a href="./profile.php?id=<?php echo $_SESSION['user_id']?>" class="btn btn-primary"><?php echo $_SESSION['username'] ?></a>
        <a href="../Auth/logout.php" class="btn btn-primary">Log out</a>
       <?php else: ?>
        <a href="../Auth/login.php" class="btn btn-primary">Login</a>
       <?php endif; ?>

      </div>

      <button class="menu-open-btn" data-menu-open-btn>
        <ion-icon name="reorder-two"></ion-icon>
      </button>

      <nav class="navbar" data-navbar>

        <div class="navbar-top">

          <a href="./index.html" class="logo">
            <img src="./Assets/images/iconanme.png" alt="FrameX logo">
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
            <a href="./trending.php" class="navbar-link">Trending</a>
          </li>

          <li>
            <a href="./tvshow.php" class="navbar-link">Tv</a>
          </li>

          <li>
            <a href="./custommovie.php" class="navbar-link">Custom</a>
          </li>

          <li>
            <a href="./about.php" class="navbar-link">About</a>
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