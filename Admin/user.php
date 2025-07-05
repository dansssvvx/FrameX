<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ../Auth/login.php?error=access_denied");
    exit;
}
include "../Conf/info.php";
include "../Conf/database.php";

$stmt2 = $db->query("SELECT username FROM users WHERE id =" . $_SESSION['user_id']);
$username = $stmt2->fetchColumn();

$stmt3 = $db->query("SELECT * FROM users ORDER BY created_at ASC");
$users = $stmt3->fetchAll();

$nodata = "Belum ada data tersedia! Silahkan tambah terlebih dahulu.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Frame X</title>

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
                   <a href="./tv.php" class="flex items-center w-full p-2 text-app-white transition duration-75 rounded-lg pl-11 group hover:bg-gunmetal-1 hover:text-citrine">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                       </svg>
                       TV Shows
                   </a>
               </li>
               <li>
                   <a href="./user.php" class="flex items-center w-full p-2 text-app-white transition duration-75 rounded-lg pl-11 group hover:bg-gunmetal-1 hover:text-citrine bg-citrine bg-opacity-20 border border-citrine border-opacity-30">
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
                    <h1 class="text-3xl font-bold text-app-white mb-2">User Management</h1>
                    <p class="text-gray-400">Check the list of users registered in the system. You can manage their roles and remove them if necessary.</p>
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

        <!-- Users Table -->
        <div class="stat-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-app-white">Users List</h3>
                <button id="addUserBtn" onclick="toggleAddUserModal()" class="bg-citrine text-black font-semibold py-2 px-4 rounded hover:bg-citrine-hover transition">
                    + Add New User
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-300 uppercase bg-gunmetal-1">
                        <tr>
                            <th scope="col" class="px-6 py-3 rounded-l-lg">No</th>
                            <th scope="col" class="px-6 py-3">Username</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Role</th>
                            <th scope="col" class="px-6 py-3">Created At</th>
                            <th scope="col" class="px-6 py-3 rounded-r-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($users)): ?>
                            <tr class="border-b border-gray-700">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                        </svg>
                                        <p><?php echo $nodata ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach($users as $u): ?>
                                <tr class="border-b border-gray-700 hover:bg-gunmetal-1 transition-colors">
                                    <td class="px-6 py-4 text-app-white font-medium">
                                        <?php echo $no?>
                                    </td>
                                    <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($u['username']) ?></td>
                                    <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($u['email']) ?></td>
                                    <td class="px-6 py-4 text-app-white">
                                        <?php
                                            $role = $u['is_admin'] ? "Admin" : "User";
                                            echo $role;
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-app-white"><?php echo htmlspecialchars($u['created_at'])  ?></td>
                                    <td class="flex items-center px-6 py-4 space-x-3">
                                        <form method="POST" action="update_roleuser.php" onsubmit="return confirmRoleChange(<?php echo $u['id'] ?>, <?php echo $u['is_admin'] ?>);">
                                            <input type="hidden" name="user_id" value="<?php echo $u['id'] ?>">
                                            <button type="submit" class="font-medium text-citrine hover:underline">
                                                Make as <?php echo ($u['is_admin'] ? "User" : "Admin"); ?>
                                            </button>
                                        </form>

                                        <form method="GET" action="delete_user.php" onsubmit="return confirmDeleteUser();">
                                            <input type="hidden" name="id" value="<?php echo $u['id'] ?>">
                                            <button type="submit" class="font-medium text-red-600 hover:underline">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php $no++; endforeach; ?>
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

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Add a New User</h2>
            <form action="insert_user.php" method="POST">
                <div class="grid gap-6">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="addUsername" required placeholder="Enter username"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="addEmail" required placeholder="Enter email"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="addPassword" required placeholder="Enter password"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" name="confirm_password" id="addConfirmPassword" required placeholder="Confirm password"
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="is_admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select name="is_admin" id="addIsAdmin" required
                            class="w-full p-3 border border-gray-300 text-black rounded-lg dark:bg-app-white dark:border-gray-600">
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeAddUserModal()" class="px-6 py-3 text-sm font-medium text-black bg-gray-300 rounded-lg hover:bg-gray-400 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-600">
                            Cancel
                        </button>

                        <button type="submit" class="px-6 py-3 text-sm font-medium text-black bg-citrine rounded-lg hover:bg-citrine-hover focus:ring-4 focus:ring-citrine">
                            Add User
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

function toggleAddUserModal() {
    const modal = document.getElementById('addUserModal');
    modal.classList.toggle('hidden');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.add('hidden');
}

function confirmRoleChange(userId, currentRole) {
    const newRole = currentRole ? 'User' : 'Admin';
    return confirm(`Yakin ingin mengubah role user menjadi ${newRole}?`);
}

function confirmDeleteUser() {
    return confirm('Are you sure you want to delete this user? This action cannot be undone.');
}
</script>

</body>
</html>