<?php
session_start();
include "../Conf/info.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Frame X</title>
  <link rel="shortcut icon" href="../Assets/images/icon.png" type="image/png">
  <link rel="stylesheet" href="../Assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="top">

<?php include_once __DIR__ . './header.php'; ?>

<main>
    <article>
        <section class="upcoming" style="padding-top: 100px; padding-bottom: 50px;">
            <div class="container">
                <div class="title-wrapper">
                    <p class="section-subtitle">Get to Know Us</p>
                    <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>About FrameX</strong></h2>
                </div>

                <div class="text-center text-white space-y-8">
                    <h3 class="text-3xl font-bold text-citrine">Your Ultimate Media Archive</h3>
                    <p class="text-lg leading-relaxed">
                        FrameX is designed to be your personal archive for all your movie and TV show watching habits.
                        In an era of proliferating streaming services, keeping track of what you've watched,
                        what's next, and where you left off can be a challenge. FrameX aims to solve this
                        by providing a centralized platform to manage your viewing journey.
                    </p>

                    <h3 class="text-2xl font-bold text-citrine pt-8">Our Team</h3>
                    <ul class="list-none p-0 flex flex-wrap justify-center gap-8">
                        <li class="w-full sm:w-1/2 md:w-1/4">
                            <img src="../Assets/images/team-member-1.jpg" alt="Ahmad Ramadhani R" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            <p class="text-lg font-semibold">Ahmad Ramadhani R</p>
                            <p class="text-gray-400">Project Lead & Backend Developer</p>
                        </li>
                        <li class="w-full sm:w-1/2 md:w-1/4">
                            <img src="../Assets/images/team-member-2.jpg" alt="Sucitasari Rahmadani" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            <p class="text-lg font-semibold">Sucitasari Rahmadani</p>
                            <p class="text-gray-400">Frontend Developer</p>
                        </li>
                        <li class="w-full sm:w-1/2 md:w-1/4">
                            <img src="../Assets/images/team-member-3.jpg" alt="Rengganis Cahya Andini" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            <p class="text-lg font-semibold">Rengganis Cahya Andini</p>
                            <p class="text-gray-400">UI/UX Designer</p>
                        </li>
                    </ul>

                    <h3 class="text-2xl font-bold text-citrine pt-8">Background & Purpose</h3>
                    <p class="text-lg leading-relaxed text-justify">
                        Dalam era digital yang semakin maju, konsumsi film dan serial televisi meningkat pesat seiring dengan berkembangnya layanan streaming seperti Netflix, Disney+, dan Amazon Prime.
                        Meskipun akses terhadap film menjadi lebih mudah, pengguna sering kali mengalami kesulitan dalam mengelola dan melacak rekam jejak tontonan mereka. Karena banyaknya platform streaming, banyak orang bingung sudah menonton sampai mana. FrameX hadir sebagai solusi untuk permasalahan ini, memberikan kemudahan bagi pengguna untuk mengarsip dan melacak semua tontonan mereka di satu tempat, tanpa peduli platform asalnya.
                    </p>

                    <h3 class="text-2xl font-bold text-citrine pt-8">Use Case Diagram</h3>
                    <div class="flex justify-center py-8">
                        <img src="../Assets/images/use_case_diagram.png" alt="Use Case Diagram" class="max-w-full h-auto">
                    </div>

                    <h3 class="text-2xl font-bold text-citrine pt-8">Database Specification</h3>
                    <div class="flex justify-center py-8">
                        <img src="../Assets/images/database_schema.png" alt="Database Schema" class="max-w-full h-auto">
                    </div>

                </div>
            </div>
        </section>
    </article>
</main>

<?php include_once __DIR__ . './footer.php'; ?>

<a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up"></ion-icon>
</a>

<script src="../Assets/js/script.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>