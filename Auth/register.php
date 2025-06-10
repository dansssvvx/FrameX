<?php
session_start();
require_once '../Conf/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } else {

        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $is_admin = 0; // Default non-admin user
            
            // Insert user baru ke database
            $stmt = $db->prepare("INSERT INTO users (username, email, password, is_admin, created_at) VALUES (?, ?, ?, ?, NOW())");
            $result = $stmt->execute([$username, $email, $hashed_password, $is_admin]);
            
            if ($result) {
                $success = "Registration successful! You can login now.";
                // Redirect ke halaman login setelah 2 detik
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed. Please try again.";
            }
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
    <title>Register | Frame X</title>
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link rel="icon" href="../Assets/images/icon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<section class="upcoming">
    <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
        <form class="w-full max-w-md" method="POST" action="register.php">
            <div class="flex justify-center mx-auto">
                <img class="w-auto h-14 sm:h-15" src="../Assets/images/iconname.png" alt="">
            </div>
            
            <!-- Menampilkan pesan error/success -->
            <?php if (!empty($error)): ?>
                <div class="p-3 mb-4 text-red-700 bg-red-100 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
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

                <input type="text" name="username"
                       class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Username" required>
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

                <input type="email" name="email"
                       class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Email address" required>
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

                <input type="password" name="password"
                       class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Password" required minlength="8">
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

                <input type="password" name="confirm_password"
                       class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600  focus:outline-none focus:ring focus:ring-opacity-40"
                       style="--tw-ring-color: var(--citrine); border-color: var(--citrine);"
                       placeholder="Confirm Password" required minlength="6">
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full px-6 py-3 text-sm font-medium tracking-wide text-gray-900 capitalize transition-colors duration-300 transform rounded-lg focus:outline-none focus:ring focus:ring-opacity-50"
                    style="background-color: var(--citrine); --tw-ring-color: var(--citrine);">
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

<!-- Footer dan script lainnya tetap sama -->
<?php include_once '../Public/footer.php'; ?>