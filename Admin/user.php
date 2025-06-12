<?php
session_start();

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: ./login.php?error=access_denied");
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
    <title>Admin | Frame X</title>

    <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
    <link rel="stylesheet" href="../Assets/css/style.css">
    <script src="../Assets/js/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
           <a href="./index.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-app-white dark:bg-eerie-black dark:hover:bg-gunmetal-2 group"> <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-x group-hover:text-gray-900 dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21"> <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                   <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
           </a>
         </li>
         <li>
           <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example"> <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-x dark:group-hover:text-app-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M3 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.25Zm0 4.5A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 9.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
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
                       <a href="./user.php" class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group dark:bg-gunmetal-2 hover:bg-gray-100 dark:text-app-white dark:hover:bg-gunmetal-2">User</a> </li>
           </ul>
         </li>
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

               <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
           </a>
         </li>
     </ul>
   </div>
</aside>

<div class="p-4 sm:ml-64 bg-rich-black-fogra-39 px-20 py-10">

<div class="title-wrapper" style="padding-bottom:20px;">
    <p class="section-subtitle" style="margin-bottom:0px; text-align: left;">
    Check the list of users registered in the system. You can manage their roles and remove them if necessary.
    </p>
    <h2 class="h2 section-title" style="text-align:left;">
    <strong>Users List</strong>
    </h2>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
  <thead class="text-xs text-gray-700 uppercase dark:bg-light-gray dark:text-black">
    <tr>
        <th scope="col" class="px-6 py-3">No</th>
        <th scope="col" class="px-6 py-3">Username</th>
        <th scope="col" class="px-6 py-3">Email</th>
        <th scope="col" class="px-6 py-3">Role</th>
        <th scope="col" class="px-6 py-3">Created At</th>
        <th scope="col" class="px-6 py-3">Action</th>
    </tr>
  </thead>
  <tbody>

    <?php if(empty($users)):?>
        <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 font-poppins">
            <th colspan="12" class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white text-center">
                <?php echo $nodata ?>
            </th>
    <?php else: $no = 1?>

        <?php foreach($users as $u):?>
            <tr class="bg-white border-b bg-gray-50 dark:bg-eerie-black dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 font-poppins">
            <th scope="row" class="px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                <?php echo $no?>
            </th>
            <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($u['username']) ?></td>
            <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($u['email']) ?></td>
            <td class="px-6 py-4 text-white"> <?php
                $role = $u['is_admin'] ? "Admin" : "User";
                echo $role;?>
            </td>
            <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($u['created_at'])  ?></td>

            <td class="flex items-center px-6 py-4">
                <form method="POST" action="update_roleuser.php" onsubmit="return confirmRoleChange(<?php echo $u['id'] ?>, <?php echo $u['is_admin'] ?>);">
                    <input type="hidden" name="user_id" value="<?php echo $u['id'] ?>">
                    <button type="submit" class="font-medium text-citrine dark:text-citrine hover:underline">
                        Make as <?php echo ($u['is_admin'] ? "User" : "Admin"); ?>
                    </button>
                </form>

                <form method="GET" action="delete_user.php" onsubmit="return confirmDeleteUser();" style="margin-left: 10px;">
                    <input type="hidden" name="id" value="<?php echo $u['id'] ?>">
                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                </form>
            </td>

        </tr>
            <?php $no++; endforeach;?>
        <?php endif; ?>
  </tbody>
</table>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert" style="top: auto; margin-top: 10px;">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert" style="top: auto; margin-top: 10px;">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="flex justify-center mt-6">
  <button id="addUserBtn" onclick="toggleAddUserModal()" class="bg-citrine text-black font-semibold py-2 px-4 rounded hover:bg-citrine-hover transition">
    + Add New User
  </button>
</div>

<div id="addUserModal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
    <section class="bg-white dark:bg-gray-900 rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="py-8 px-8 mx-auto bg-eerie-black">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Add a New User</h2>
            <form action="insert_user.php" method="POST">
                <div class="grid gap-6">
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="addUsername" required placeholder="Enter username"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email" id="addEmail" required placeholder="Enter email"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="addPassword" required placeholder="Enter password"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm Password</label>
                        <input type="password" name="confirm_password" id="addConfirmPassword" required placeholder="Confirm password"
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
                    </div>

                    <div>
                        <label for="is_admin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                        <select name="is_admin" id="addIsAdmin" required
                            class="w-full p-3 border border-gray-300 rounded-lg dark:bg-app-white dark:border-gray-600">
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
    function toggleAddUserModal() {
        const modal = document.getElementById('addUserModal');
        modal.classList.toggle('hidden');
    }

    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
    }
</script>

<script>
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