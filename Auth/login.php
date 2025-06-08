<?php
session_start();
require_once '../Conf/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "username and password are required!";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user_logged_in'] = true;
            // Jika user adalah admin, redirect ke halaman admin
            if ($user['is_admin']) {
                header('Location: ../Admin/index.php');
                exit;
            }
            
            // Redirect ke halaman yang sesuai
            header('Location: ../Public/index.php');
            exit;
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Frame X</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link rel="icon" href="../Assets/images/icon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<section class="upcoming">
  <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
    <form class="w-full max-w-md" method="POST" action="login.php">
      <div class="flex justify-center mx-auto">
        <img class="w-auto h-14 sm:h-15" src="Assets/images/iconname.png" alt="">
      </div>

      <!-- Tampilkan pesan error -->
      <?php if (!empty($error)): ?>
        <div class="p-3 mb-4 text-red-700 bg-red-100 rounded-lg">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <div class="flex items-center justify-center mt-6">
        <a href="login.php"
          class="w-1/3 pb-4 font-medium text-center text-gray-800 capitalize border-b-2 dark:text-white"
          style="border-color: var(--citrine);">
          sign in
        </a>

        <a href="register.php"
          class="w-1/3 pb-4 font-medium text-center text-gray-500 capitalize border-b dark:border-gray-400 dark:text-gray-300">
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

            <input type="text" name="username"
                  class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                  style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                  placeholder="Username" required>
        </div>

      <div class="relative flex items-center mt-4">
        <span class="absolute">
          <svg xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </span>

        <input type="password" name="password" required minlength="8"
          class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring focus:ring-opacity-40"
          placeholder="Password"
          style="--tw-ring-color: var(--citrine); border-color: var(--citrine);">
      </div>

      <div class="mt-6">
        <button type="submit"
          class="w-full px-6 py-3 text-sm font-medium tracking-wide text-gray-900 capitalize transition-colors duration-300 transform rounded-lg focus:outline-none focus:ring focus:ring-opacity-50"
          style="background-color: var(--citrine); --tw-ring-color: var(--citrine);">
          Sign in
        </button>

        <div class="mt-6 text-center">
          <a href="register.php" class="text-sm hover:underline" style="color: var(--citrine);">
            Don't have an account?
          </a>
        </div>
      </div>
    </form>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>

<!-- Script lainnya tetap sama -->
</body>
</html>