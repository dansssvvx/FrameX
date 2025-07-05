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
                    <p class="section-subtitle">Student Project</p>
                    <h2 class="h2 section-title" style="margin-bottom:15px;"><strong>About FrameX</strong></h2>
                </div>

                <div class="text-center text-white space-y-8">
                    <!-- Project Overview -->
                    <div class="bg-gray-800/50 rounded-lg p-6 mb-8">
                        <h3 class="text-3xl font-bold text-citrine mb-4">Academic Project Overview</h3>
                        <p class="text-lg leading-relaxed">
                            FrameX is a comprehensive web application developed as part of the <strong>Web Programming</strong> course 
                            at <strong>Mataram University</strong>. This project demonstrates proficiency in modern web development 
                            technologies and best practices, showcasing the skills acquired during Semester 4 of the 
                            Informatics Engineering program.
                        </p>
                    </div>

                    <!-- Course Information -->
                    <div class="bg-gray-800/50 rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-bold text-citrine mb-4">Course Information</h3>
                        <div class="grid md:grid-cols-2 gap-6 text-left">
                            <div>
                                <h4 class="text-xl font-semibold text-citrine mb-2">Academic Details</h4>
                                <ul class="space-y-2 text-gray-300">
                                    <li><strong>University:</strong> Mataram University</li>
                                    <li><strong>Program:</strong> Informatics Engineering (S1)</li>
                                    <li><strong>Semester:</strong> 4</li>
                                    <li><strong>Course:</strong> Web Programming</li>
                                    <li><strong>Project Type:</strong> Full-Stack Web Application</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold text-citrine mb-2">Technologies Used</h4>
                                <ul class="space-y-2 text-gray-300">
                                    <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Tailwind CSS</li>
                                    <li><strong>Backend:</strong> PHP</li>
                                    <li><strong>Database:</strong> MySQL</li>
                                    <li><strong>APIs:</strong> TMDB (The Movie Database)</li>
                                    <li><strong>Tools:</strong> XAMPP, Git</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Project Description -->
                    <div class="bg-gray-800/50 rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-bold text-citrine mb-4">Project Description</h3>
                        <p class="text-lg leading-relaxed text-justify">
                            FrameX is designed to be a comprehensive media management platform that addresses the modern challenge 
                            of tracking movies and TV shows across multiple streaming services. In today's digital era, with the 
                            proliferation of streaming platforms like Netflix, Disney+, and Amazon Prime, users often struggle to 
                            manage their viewing history and preferences. This project demonstrates the application of web development 
                            principles to create a practical solution for real-world problems.
                        </p>
                        <div class="mt-6 grid md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                                <h4 class="text-lg font-semibold text-citrine mb-2">Movie Management</h4>
                                <p class="text-sm text-gray-300">Browse, search, and save movies with detailed information</p>
                            </div>
                            <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                                <h4 class="text-lg font-semibold text-citrine mb-2">TV Show Tracking</h4>
                                <p class="text-sm text-gray-300">Manage TV series and track viewing progress</p>
                            </div>
                            <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                                <h4 class="text-lg font-semibold text-citrine mb-2">User Profiles</h4>
                                <p class="text-sm text-gray-300">Personalized experience with user accounts</p>
                            </div>
                        </div>
                    </div>

                    <!-- Development Team -->
                    <div class="bg-gray-800/50 rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-bold text-citrine mb-6">Development Team</h3>
                        <div class="grid md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <img src="../Assets/images/team-member-1.jpg" alt="Ahmad Ramadhani R" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-citrine">
                                <h4 class="text-lg font-semibold mb-1">Ahmad Ramadhani R</h4>
                                <p class="text-gray-400 text-sm mb-2">Project Lead & Backend Developer</p>
                                <p class="text-xs text-gray-500">Informatics Engineering Student</p>
                            </div>
                            <div class="text-center">
                                <img src="../Assets/images/team-member-2.jpg" alt="Sucitasari Rahmadani" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-citrine">
                                <h4 class="text-lg font-semibold mb-1">Sucitasari Rahmadani</h4>
                                <p class="text-gray-400 text-sm mb-2">Frontend Developer</p>
                                <p class="text-xs text-gray-500">Informatics Engineering Student</p>
                            </div>
                            <div class="text-center">
                                <img src="../Assets/images/team-member-3.jpg" alt="Rengganis Cahya Andini" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-citrine">
                                <h4 class="text-lg font-semibold mb-1">Rengganis Cahya Andini</h4>
                                <p class="text-gray-400 text-sm mb-2">UI/UX Designer</p>
                                <p class="text-xs text-gray-500">Informatics Engineering Student</p>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Objectives -->
                    <div class="bg-gray-800/50 rounded-lg p-6">
                        <h3 class="text-2xl font-bold text-citrine mb-4">Learning Objectives Achieved</h3>
                        <div class="grid md:grid-cols-2 gap-6 text-left">
                            <div>
                                <h4 class="text-lg font-semibold text-citrine mb-3">Technical Skills</h4>
                                <ul class="space-y-2 text-gray-300">
                                    <li>• Full-stack web development with PHP and MySQL</li>
                                    <li>• Responsive design implementation</li>
                                    <li>• API integration and data management</li>
                                    <li>• User authentication and session management</li>
                                    <li>• Database design and optimization</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-citrine mb-3">Soft Skills</h4>
                                <ul class="space-y-2 text-gray-300">
                                    <li>• Team collaboration and project management</li>
                                    <li>• Problem-solving and critical thinking</li>
                                    <li>• User experience design principles</li>
                                    <li>• Documentation and code organization</li>
                                    <li>• Version control with Git</li>
                                </ul>
                            </div>
                        </div>
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