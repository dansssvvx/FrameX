<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Validate credentials
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | FrameX</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="icon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<section class="upcoming">
    <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
        <form class="w-full max-w-md">
            <div class="flex justify-center mx-auto">
                <img class="w-auto h-14 sm:h-15" src="assets/images/iconname.png" alt="">
            </div>
            
            <div class="flex items-center justify-center mt-6">
                <a href="login.php"
                   class="w-1/3 pb-4 font-medium text-center text-gray-500 capitalize border-b dark:border-gray-400 dark:text-gray-300">
                    sign in
                </a>

                <a href="register.php"
                   class="w-1/3 pb-4 font-medium text-center text-gray-800 capitalize border-b-2 dark:text-white"
                   style="border-color: var(--citrine);">
                    sign up
                </a>
            </div>

            <div class="relative flex items-center mt-8">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </span>

                <input type="text"
                       class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Username">
            </div>

            <div class="relative flex items-center mt-6">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>

                <input type="email"
                       class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Email address">
            </div>

            <div class="relative flex items-center mt-4">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>

                <input type="password"
                       class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Password">
            </div>

            <div class="relative flex items-center mt-4">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>

                <input type="password"
                       class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Confirm Password">
            </div>

            <div class="mt-6">
                <button
                    class="w-full px-6 py-3 text-sm font-medium tracking-wide text-gray-900 capitalize transition-colors duration-300 transform rounded-lg  focus:outline-none focus:ring focus:ring-opacity-40"
                    style="background-color: var(--citrine); box-shadow: 0 0 0 3px var(--citrine-ring);">
                    Sign Up
                </button>

                <div class="mt-6 text-center">
                    <a href="login.php" class="text-sm hover:underline" style="color: var(--citrine);">
                        Already have an account?
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>


  <!-- 
    - #FOOTER
  -->

  <?php include __DIR__ . '/footer.php'; ?>

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