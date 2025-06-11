<?php
session_start();

// Cek apakah user sudah login DAN memiliki role admin
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ./login.php?error=access_denied");
    exit;
}
include "../Conf/info.php";
include "../Conf/database.php";
include "../API/api_discover_movie.php";
include "../API/api_discover_tv.php";
include "../API/api_trending.php";
include "../API/api_popular.php";
include "../API/api_toprated.php";
include "../API/api_upcoming.php";
include "../API/api_tv.php";

$stmt = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 0");
$stmt1 = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 1");
$countuser = $stmt->fetchColumn();
$countadmin = $stmt1->fetchColumn();

$stmt2 = $db->query("SELECT username FROM users WHERE id =".$_SESSION['user_id']);
$username = $stmt2->fetchColumn();

$stmt3 = $db->query("SELECT COUNT(*) FROM custom_movie");
$countcustom = $stmt3->fetchColumn();

$stmt4 = $db->query("SELECT COUNT(*) FROM custom_tv_show");
$countcustomtv = $stmt4->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Frame X</title>

    <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: { // Tambahkan konfigurasi warna kustom di sini
                        'rich-black-fogra-29': 'hsl(225, 25%, 9%)',
                        'rich-black-fogra-39': 'hsl(170, 21%, 5%)',
                        'raisin-black': 'hsl(228, 13%, 15%)',
                        'eerie-black': 'hsl(207, 19%, 11%)',
                        'light-gray': 'hsl(0, 3%, 80%)',
                        'gunmetal-1': 'hsl(229, 15%, 21%)',
                        'gunmetal-2': 'hsl(216, 22%, 18%)',
                        'gainsboro': 'hsl(0, 7%, 88%)',
                        'citrine': 'hsl(57, 97%, 45%)',
                        'citrine-hover': 'hsl(57, 97%, 55%)',
                        'citrine-ring': 'hsla(57, 97%, 45%, 0.4)',
                        'xiketic': 'hsl(253, 21%, 13%)',
                        'gray-x': 'hsl(0, 0%, 74%)',
                        'app-white': 'hsl(0, 100%, 100%)',
                        'app-black': 'hsl(0, 0%, 0%)', 
                        'app-jet': 'hsl(0, 0%, 20%)', 
                    }
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
           
        }
        :root {
            --rich-black-fogra-29: hsl(225, 25%, 9%);
            --rich-black-fogra-39: hsl(170, 21%, 5%);
            --raisin-black: hsl(228, 13%, 15%);
            --eerie-black: hsl(207, 19%, 11%);
            --light-gray: hsl(0, 3%, 80%);
            --gunmetal-1: hsl(229, 15%, 21%);
            --gunmetal-2: hsl(216, 22%, 18%);
            --gainsboro: hsl(0, 7%, 88%);
            --citrine: hsl(57, 97%, 45%);
            --citrine-hover: hsl(57, 97%, 55%);
            --citrine-ring: hsla(57, 97%, 45%, 0.4);
            --xiketic: hsl(253, 21%, 13%);
            --gray-x: hsl(0, 0%, 74%);
            --white: hsl(0, 100%, 100%);
            --black: hsl(0, 0%, 0%);
            --jet: hsl(0, 0%, 20%);
        }
    </style>
</head>
<body class="font-poppins">
    
<button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-eerie-black"> <ul class="space-y-2 font-medium">
        <li>
            <a class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white dark:bg-eerie-black group" 
                style="border-bottom-width: 1px; margin-bottom: 25px;"> 
               <span class="ms-3">Hello, <strong><u><?php echo ucfirst($username) ?></u>-!</strong></span>
           </a>

        </li> 
        <li>
           <a href="./index.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white dark:bg-gunmetal-2 group"> <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-x group-hover:text-gray-900 dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21"> <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                   <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
           </a>
         </li>
         <li>
           <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example"> <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-x dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M3 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.25Zm0 4.5A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
</svg>

                 </svg>

                 
                 <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Data Management</span>
                 <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                 </svg>
           </button>
           <ul id="dropdown-example" class="hidden py-2 space-y-2">
                     <li>
                       <a href="./movie.php" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2">Movie</a> </li>
                     <li>
                       <a href="./tv.php" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2">TV Show</a> </li>
                     <li>
                       <a href="./user.php" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2">User</a> </li>
           </ul>
         </li>
         <li>
           <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white hover:bg-gray-100 dark:hover:bg-gunmetal-2 group"> <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-x group-hover:text-gray-900 dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"> <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-gunmetal-2 dark:text-gray-x">3</span> </a>
         </li>
        <li>
           <a href="./log.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white hover:bg-gray-100 dark:hover:bg-gunmetal-2 group"> 
            
           <svg  class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-x group-hover:text-gray-900 dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd" d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM12 9.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5H12Zm-.75-2.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5H12a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5H6Zm-.75 3.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5H6a.75.75 0 0 1-.75-.75ZM6 6.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-3A.75.75 0 0 0 9 6.75H6Z" clip-rule="evenodd" />
            <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 0 1-3 0V6.75Z" />
            </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Log</span> </a>
         </li>
         <li>
           <a href="../Auth/logout.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white hover:bg-gray-100 dark:hover:bg-gunmetal-2 group"> <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-x group-hover:text-gray-900 dark:group-hover:text-app-white" aria-hidden="true"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
            </svg>

            </svg>

               <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
           </a>
         </li>
     </ul>
   </div>
</aside>

<div class="p-4 sm:ml-64 bg-rich-black-fogra-39 px-20 py-10">

<div class="title-wrapper" style="padding-bottom:20px;">
    <h2 class="h2 section-title" style="text-align:left;"><strong>Dashboard</strong></h2>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase dark:bg-light-gray dark:text-black">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Category
                </th>
                <th scope="col" class="px-6 py-4">
                    Total Items
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 font-poppins">
                <?php ?>
                <th scope="row" class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                    Movies
                </th>
                <td class="px-6 py-4 text-white">
                    <?php 
                    $total_movies = $discover_movie->total_results + $countcustom;
                    echo htmlspecialchars(number_format($total_movies,0,',','.')) ?>
                </td>
            </tr>
            <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    TV Show
                </th>
                <td class="px-6 py-4 text-white">
                    <?php  $total_tvshow = $discover_tv->total_results + $countcustomtv;
                    echo htmlspecialchars(number_format($total_tvshow,0,',','.')) ?>
                </td>
            </tr>
            <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    User
                </th>
                <td class="px-6 py-4 text-white">
                    <?php echo $countuser?>
                </td>
            </tr>
            <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Admin
                </th>
                <td class="px-6 py-4 text-white">
                    <?php echo $countadmin ?>
                </td>
             </tr>
        </tbody>
    </table>
</div>

</div>

</body>
</html>