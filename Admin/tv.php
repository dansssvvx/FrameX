<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php?error=access_denied");
    exit;
}
include "../Conf/info.php";
include "../Conf/database.php";

$stmt2 = $db->query("SELECT username FROM users WHERE id =".$_SESSION['user_id']);
$username = $stmt2->fetchColumn();

$stmt3 = $db->query("SELECT * FROM genre ORDER BY name ASC");
$genrename = $stmt3->fetchAll();

$stmt = $db->query("SELECT * FROM custom_tv_show ORDER BY created_at");
$custom_tv_show = $stmt->fetchAll();

$nodata = "Belum ada data tersedia! Silahkan tambah terlebih dahulu.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Show Management | Frame X</title>

    <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <script src="../Assets/js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
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
        .stat-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-poppins bg-rich-black-fogra-39 text-app-white">

<!-- Mobile menu button -->
<button data-drawer-target="sidebar-multi-level-sidebar" data-drawer-toggle="sidebar-multi-level-sidebar" aria-controls="sidebar-multi-level-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
   <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<!-- Sidebar -->
<aside id="sidebar-multi-level-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-eerie-black to-gunmetal-2 border-r border-gray-700">
     <div class="mb-6 p-4 glass-effect rounded-lg">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-citrine rounded-full flex items-center justify-center">
                <span class="text-black font-bold text-lg"><?php echo strtoupper(substr($username, 0, 1)); ?></span>
            </div>
            <div>
                <p class="text-sm text-gray-300">Welcome back,</p>
                <p class="font-semibold text-app-white"><?php echo ucfirst($username); ?></p>
            </div>
        </div>
     </div>
     
     <ul class="space-y-2 font-medium">
        <li>
           <a href="./index.php" class="flex items-center p-3 text-app-white rounded-lg hover:bg-gunmetal-1 group">
               <svg class="w-5 h-5 text-gray-x group-hover:text-citrine" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                   <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                   <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
           </a>
         </li>
         <li>
           <button type="button" class="flex items-center w-full p-3 text-base text-app-white transition duration-75 rounded-lg group hover:bg-gunmetal-1" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
               <svg class="w-5 h-5 text-gray-x group-hover:text-citrine" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                   <path fill-rule="evenodd" d="M3 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.25Zm0 4.5A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
               </svg>
               <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Data Management</span>
               <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                   <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
               </svg>
           </button>
           <ul id="dropdown-example" class="hidden py-2 space-y-2">
               <li>
                   <a href="./movie.php" class="flex items-center w-full p-2 text-app-white transition duration-75 rounded-lg pl-11 group hover:bg-gunmetal-1 hover:text-citrine">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M2 6a2 2 0 012-2h6l2 2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                       </svg>
                       Movies
                   </a>
               </li>
               <li>
                   <a href="./tv.php" class="flex items-center w-full p-2 text-app-white transition duration-75 rounded-lg pl-11 group hover:bg-gunmetal-1 hover:text-citrine bg-citrine bg-opacity-20 border border-citrine border-opacity-30">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                       </svg>
                       TV Shows
                   </a>
               </li>
               <li>
                   <a href="./user.php" class="flex items-center w-full p-2 text-app-white transition duration-75 rounded-lg pl-11 group hover:bg-gunmetal-1 hover:text-citrine">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                       </svg>
                       Users
                   </a>
               </li>
           </ul>
         </li>
         <li>
           <a href="./log.php" class="flex items-center p-3 text-app-white rounded-lg hover:bg-gunmetal-1 group">
               <svg class="w-5 h-5 text-gray-x group-hover:text-citrine" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                   <path fill-rule="evenodd" d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM12 9.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5H12Zm-.75-2.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5H12a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5H6Zm-.75 3.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5H6a.75.75 0 0 1-.75-.75ZM6 6.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-3A.75.75 0 0 0 9 6.75H6Z" clip-rule="evenodd" />
                   <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 0 1-3 0V6.75Z" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Activity Log</span>
           </a>
         </li>
         <li>
           <a href="../Auth/logout.php" class="flex items-center p-3 text-app-white rounded-lg hover:bg-red-600 group">
               <svg class="w-5 h-5 text-gray-x group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                   <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
           </a>
         </li>
     </ul>
   </div>
</aside>

<!-- Main content -->
<div class="p-4 sm:ml-64 bg-rich-black-fogra-39 min-h-screen">
    <div class="px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-app-white mb-2">TV Show Management</h1>
                    <p class="text-gray-400">You can manage TV Show here, including adding, editing, and deleting TV Shows.</p>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Current Time</p>
                        <p class="text-app-white font-semibold" id="current-time"></p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-citrine to-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-black font-bold text-lg"><?php echo strtoupper(substr($username, 0, 1)); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TV Shows Table -->
        <div class="stat-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-app-white">Custom TV Show Collection</h3>
                <button id="addMovieBtn" onclick="toggleModal()" class="bg-citrine text-black font-semibold py-2 px-4 rounded hover:bg-citrine-hover transition">
                    + Add Custom TV Show
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-300 uppercase bg-gunmetal-1">
                        <tr>
                            <th scope="col" class="px-6 py-3 rounded-l-lg">No</th>
                            <th scope="col" class="px-6 py-3">Title</th>
                            <th scope="col" class="px-6 py-3">Overview</th>
                            <th scope="col" class="px-6 py-3">Tagline</th>
                            <th scope="col" class="px-6 py-3">Genre</th>
                            <th scope="col" class="px-6 py-3">First Air Date</th>
                            <th scope="col" class="px-6 py-3">Total Episodes</th>
                            <th scope="col" class="px-6 py-3">Total Seasons</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Homepage</th>
                            <th scope="col" class="px-6 py-3">Poster</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3 rounded-r-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($custom_tv_show)): ?>
                            <tr class="border-b border-gray-700">
                                <td colspan="13" class="px-6 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                        <p><?php echo $nodata ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach($custom_tv_show as $m):
                            $stmt5 = $db->query("SELECT genre_id FROM tvshow_genre WHERE tvshow_id =".$m['id']);
                            $genres = $stmt5->fetchAll();
                            ?>
                            <tr class="border-b border-gray-700 hover:bg-gunmetal-1 transition-colors">
                                <td class="px-6 py-4 text-app-white font-medium">
                                    <?php echo $no?>
                                </td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['title']) ?></td>
                                <td class="px-6 py-4 text-app-white max-w-xs truncate"><?php echo htmlspecialchars($m['overview']) ?></td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['tagline'])  ?></td>
                                <td class="px-6 py-4 text-app-white">
                                    <?php $stmt7 = $db->query("SELECT COUNT(*) from tvshow_genre WHERE tvshow_id =".$m['id'] );
                                            $gcount = $stmt7->fetchcolumn();
                                            $nogenre = 0?>
                                    <?php foreach ($genres as $g):
                                        $stmt6 = $db->query("SELECT name FROM genre WHERE id =".$g['genre_id']);
                                        $p = $stmt6->fetch();
                                        ?>
                                        <?php echo htmlspecialchars($p['name']); $nogenre++;
                                            if($nogenre < $gcount){
                                                echo ", ";
                                            }else{
                                                echo ".";
                                            };
                                        ?>
                                    <?php endforeach;  ?>
                                </td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['first_air_date'])  ?></td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['total_episodes'])  ?></td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['total_seasons'])  ?></td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['status'])  ?></td>
                                <td class="px-6 py-4 text-app-white"><a href="<?php echo htmlspecialchars($m['homepage'])?>" class="text-citrine hover:underline"><?php echo htmlspecialchars($m['homepage'])  ?></a></td>
                                <td class="px-6 py-4 text-app-white"><a href="<?php echo htmlspecialchars($m['poster_path'])?>" class="text-citrine hover:underline"><?php echo htmlspecialchars($m['poster_path'])  ?></a></td>
                                <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($m['created_at'])  ?></td>
                                <td class="flex items-center px-6 py-4 space-x-3">
                                    <button onclick="editTvShowModal(<?php echo $m['id']?>)" class="font-medium text-citrine hover:underline">Edit</button>
                                    <a href="delete_tvshow.php?id=<?php echo $m['id'] ?>" class="font-medium text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this TV show?');">Remove</a>
                                </td>
                            </tr>
                            <?php $no++; endforeach;?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mt-4" role="alert">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 mt-4" role="alert">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add TV Show Modal -->
<div id="tvshowmodal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Add a New TV Show</h2>
            <form action="insert_tvshow.php" method="POST">
                <div class="grid gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TV Show Title</label>
                                <input type="text" name="title" id="title" required placeholder="Enter TV show title"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="tagline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tagline</label>
                                <input type="text" name="tagline" id="tagline" placeholder="TV show tagline"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="first_air_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Air Date</label>
                                <input type="date" name="first_air_date" id="first_air_date" required
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="total_episodes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Episodes</label>
                                    <input type="number" name="total_episodes" id="total_episodes" placeholder="e.g. 5"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="total_seasons" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Seasons</label>
                                    <input type="number" name="total_seasons" id="total_seasons" placeholder="e.g. 1"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                                </div>
                            </div>

                            <div>
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select name="status" id="status" required
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600 ">
                                    <option value="">Select status</option>
                                    <option value="Returning Series">Returning Series</option>
                                    <option value="Ended">Ended</option>
                                    <option value="Rumored">Rumored</option>
                                    <option value="In Production">In Production</option>
                                    <option value="Canceled">Canceled</option>
                                    <option value="Planned">Planned</option>
                                </select>
                            </div>

                            <div>
                                <label for="homepage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Homepage URL</label>
                                <input type="url" name="homepage" id="homepage" placeholder="https://example.com"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="poster" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Poster URL</label>
                                <input type="url" name="poster" id="poster" placeholder="https://image-link.com"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genres</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 border border-gray-300 rounded-lg dark:border-gray-600 max-h-[200px] overflow-y-auto">
                            <?php foreach ($genrename as $g): ?>
                                <div class="flex items-center">
                                    <input id="genre-<?php echo ($g['id'])?>" type="checkbox" name="genres[]" value="<?php echo($g['id'])?>" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="genre-<?php echo ($g['id'])?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?php echo htmlspecialchars($g['name'])?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label for="overview" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" style="margin-top:10px;">Overview</label>
                        <textarea name="overview" id="overview" rows="6" required placeholder="Brief description of the TV show"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600"></textarea>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 text-sm font-medium text-black bg-gray-300 rounded-lg hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                            Cancel
                        </button>

                        <button type="submit" class="px-6 py-3 text-sm font-medium text-black bg-citrine rounded-lg hover:bg-citrine-hover focus:ring-4 focus:ring-citrine">
                            + Add TV Show
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Edit TV Show Modal -->
<div id="EditTVShowModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Update TV Show</h2>
            <form action="update_tvshow.php" method="POST">
                <div class="grid gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <input type="hidden" name="id" id="editTvShowId" value="">
                                <label for="editTvShowTitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TV Show Title</label>
                                <input type="text" name="title" id="editTvShowTitle" required placeholder="Enter TV show title"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="editTvShowTagline" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tagline</label>
                                <input type="text" name="tagline" id="editTvShowTagline" placeholder="TV show tagline"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="editTvShowFirstAirDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Air Date</label>
                                <input type="date" name="first_air_date" id="editTvShowFirstAirDate" required
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="editTvShowTotalEpisodes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Episodes</label>
                                    <input type="number" name="total_episodes" id="editTvShowTotalEpisodes" placeholder="e.g. 5"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                                </div>

                                <div>
                                    <label for="editTvShowTotalSeasons" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Seasons</label>
                                    <input type="number" name="total_seasons" id="editTvShowTotalSeasons" placeholder="e.g. 1"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                                </div>
                            </div>

                            <div>
                                <label for="editTvShowStatus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select name="status" id="editTvShowStatus" required
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600 ">
                                    <option value="">Select status</option>
                                    <option value="Returning Series">Returning Series</option>
                                    <option value="Ended">Ended</option>
                                    <option value="Rumored">Rumored</option>
                                    <option value="In Production">In Production</option>
                                    <option value="Canceled">Canceled</option>
                                    <option value="Planned">Planned</option>
                                </select>
                            </div>

                            <div>
                                <label for="editTvShowHomepage" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Homepage URL</label>
                                <input type="url" name="homepage" id="editTvShowHomepage" placeholder="https://example.com"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>

                            <div>
                                <label for="editTvShowPoster" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Poster URL</label>
                                <input type="url" name="poster" id="editTvShowPoster" placeholder="https://image-link.com"
                                    class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genres</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 border border-gray-300 rounded-lg dark:border-gray-600 max-h-[200px] overflow-y-auto">
                            <?php foreach ($genrename as $g): ?>
                                <div class="flex items-center">
                                    <input id="editTvShowGenre-<?php echo ($g['id'])?>" type="checkbox" name="genres[]" value="<?php echo($g['id'])?>" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="editTvShowGenre-<?php echo ($g['id'])?>" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?php echo htmlspecialchars($g['name'])?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <label for="editTvShowOverview" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" style="margin-top:10px;">Overview</label>
                        <textarea name="overview" id="editTvShowOverview" rows="6" required placeholder="Brief description of the TV show"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600"></textarea>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeEditTvShowModal()" class="px-6 py-3 text-sm font-medium text-black bg-gray-300 rounded-lg hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                            Cancel
                        </button>

                        <button type="submit" class="px-6 py-3 text-sm font-medium text-black bg-citrine rounded-lg hover:bg-citrine-hover focus:ring-4 focus:ring-citrine">
                            Update TV Show
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('current-time').textContent = timeString;
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call

function toggleModal() {
    const modal = document.getElementById('tvshowmodal');
    modal.classList.toggle('hidden');
}

function closeTvShowModal() {
    document.getElementById('tvshowmodal').classList.add('hidden');
}

function closeEditTvShowModal() {
    document.getElementById('EditTVShowModal').classList.add('hidden');
}

async function editTvShowModal(id) {
    const modal = document.getElementById('EditTVShowModal');
    modal.classList.toggle('hidden');

    try {
        // Fetch data TV show dari server
        const response = await fetch(`get_tvshow.php?id=${id}`);
        const tvshow = await response.json();

        // Isi form dengan data TV show
        document.getElementById('editTvShowId').value = tvshow.id;
        document.getElementById('editTvShowTitle').value = tvshow.title;
        document.getElementById('editTvShowTagline').value = tvshow.tagline;
        document.getElementById('editTvShowFirstAirDate').value = tvshow.first_air_date;
        document.getElementById('editTvShowStatus').value = tvshow.status;
        document.getElementById('editTvShowTotalEpisodes').value = tvshow.total_episodes;
        document.getElementById('editTvShowTotalSeasons').value = tvshow.total_seasons;
        document.getElementById('editTvShowHomepage').value = tvshow.homepage;
        document.getElementById('editTvShowPoster').value = tvshow.poster_path;
        document.getElementById('editTvShowOverview').value = tvshow.overview;

        // Centang genre yang sesuai
        const genreCheckboxes = document.querySelectorAll('#EditTVShowModal input[name="genres[]"]');
        genreCheckboxes.forEach(checkbox => {
            checkbox.checked = tvshow.genres.includes(parseInt(checkbox.value));
        });
    } catch (error) {
        console.error("Error fetching TV show data:", error);
        alert("Gagal memuat data TV show.");
        closeEditTvShowModal(); // Close modal on error
    }
}
</script>

</body>
</html>