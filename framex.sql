-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2025 pada 18.12
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `framex`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text,
  `user_id` int(11) DEFAULT NULL,
  `user_type` enum('admin','user') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `action`, `description`, `user_id`, `user_type`, `created_at`) VALUES
(1, 'User Login', 'Admin logged in successfully', 2, 'admin', '2025-07-05 16:50:27'),
(2, 'Movie Added', 'New custom movie \"Dit, geprek satu\" was added', 2, 'admin', '2025-07-05 15:50:27'),
(3, 'TV Show Updated', 'TV show \"Kesultanan Bogor B -Series\" was updated', 2, 'admin', '2025-07-05 14:50:27'),
(4, 'User Management', 'User permissions were updated', 2, 'admin', '2025-07-05 13:50:27'),
(5, 'System Maintenance', 'Database backup completed', 2, 'admin', '2025-07-05 12:50:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `custom_movie`
--

CREATE TABLE `custom_movie` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `overview` text,
  `tagline` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `status` enum('Rumored','Planned','In Production','Post Production','Released','Canceled') DEFAULT NULL,
  `revenue` bigint(20) DEFAULT '0',
  `homepage` varchar(255) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `custom_movie`
--

INSERT INTO `custom_movie` (`id`, `title`, `overview`, `tagline`, `release_date`, `status`, `revenue`, `homepage`, `poster_path`, `created_at`) VALUES
(2, 'Dit, geprek satu', 'Kisah ini berfokus pada seorang pria bernama Yoga, yang sering makan ayam geprek di warung milik Pak Adit.', 'Pak Adit, Gepreknya satu!', '2025-06-11', 'Released', 1234, 'https://instagram.com', 'https://picsum.photos/2000/3000?random=1', '2025-06-10 20:47:43'),
(3, 'Kesultanan Bogor B', 'Tragedi dimulai saat isi WAG (WhatsApp Group) yang tidak sengaja kesebar.......', 'mok, mok, mok, moooook', '2025-06-11', 'Rumored', 0, 'https://whatsapp.com', 'https://picsum.photos/2000/3000?random=2', '2025-06-11 01:29:16'),
(4, 'Semoga masih ada artinya', 'brief description the movie', 'Dari dapur kecil, menjadi besar', '2025-06-11', 'Planned', 0, 'https://facebook.com', 'https://picsum.photos/2000/3000?random=3', '2025-06-11 01:36:34'),
(5, 'Absolute Cinema', 'ini deskripsi', 'popmie', '2025-06-12', 'Rumored', 0, 'https://ikn.go.id/', 'https://picsum.photos/2000/3000?random=98', '2025-06-12 05:25:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `custom_tv_show`
--

CREATE TABLE `custom_tv_show` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `overview` text,
  `tagline` varchar(255) DEFAULT NULL,
  `first_air_date` date DEFAULT NULL,
  `total_episodes` int(11) DEFAULT '0',
  `total_seasons` int(11) DEFAULT '0',
  `status` enum('Returning Series','Planned','In Production','Ended','Canceled','Pilot') NOT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `custom_tv_show`
--

INSERT INTO `custom_tv_show` (`id`, `title`, `overview`, `tagline`, `first_air_date`, `total_episodes`, `total_seasons`, `status`, `homepage`, `poster_path`, `created_at`) VALUES
(1, 'Kesultanan Bogor B -Series', 'Sekuel dari FILM Kesultanan Bogor B', 'mok, mok, mok, moooook.  Seperti biasa', '2025-06-11', 9, 1, 'Returning Series', 'https://whatsapp.com', 'https://picsum.photos/2000/3000?random=192', '2025-06-11 08:48:27'),
(2, 'tes tv show', 'tes ubah tes update tes update tes update', 'movie tagline', '2025-06-11', 15, 2, 'Ended', 'https://instagram.com', 'https://picsum.photos/2000/3000?random=387', '2025-06-11 15:22:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `genre`
--

INSERT INTO `genre` (`id`, `name`, `created_at`) VALUES
(12, 'Adventure', '2025-06-10 16:23:32'),
(14, 'Fantasy', '2025-06-10 16:23:32'),
(16, 'Animation', '2025-06-10 16:23:32'),
(18, 'Drama', '2025-06-10 16:23:32'),
(27, 'Horror', '2025-06-10 16:23:32'),
(28, 'Action', '2025-06-10 16:23:32'),
(35, 'Comedy', '2025-06-10 16:23:32'),
(36, 'History', '2025-06-10 16:23:32'),
(37, 'Western', '2025-06-10 16:23:32'),
(53, 'Thriller', '2025-06-10 16:23:32'),
(80, 'Crime', '2025-06-10 16:23:32'),
(99, 'Documentary', '2025-06-10 16:23:32'),
(878, 'Science Fiction', '2025-06-10 16:23:32'),
(9648, 'Mystery', '2025-06-10 16:23:32'),
(10402, 'Music', '2025-06-10 16:23:32'),
(10463, 'Adult', '2025-06-10 16:23:32'),
(10749, 'Romance', '2025-06-10 16:23:32'),
(10751, 'Family', '2025-06-10 16:23:32'),
(10752, 'War', '2025-06-10 16:23:32'),
(10759, 'Action & Adventure', '2025-06-10 16:23:32'),
(10762, 'Kids', '2025-06-10 16:23:32'),
(10763, 'News', '2025-06-10 16:23:32'),
(10764, 'Reality', '2025-06-10 16:23:32'),
(10765, 'Sci-Fi & Fantasy', '2025-06-10 16:23:32'),
(10766, 'Soap', '2025-06-10 16:23:32'),
(10767, 'Talk', '2025-06-10 16:23:32'),
(10768, 'War & Politics', '2025-06-10 16:23:32'),
(10770, 'TV Movie', '2025-06-10 16:23:32'),
(11079, 'Anime', '2025-06-10 16:23:32'),
(11132, 'Biography', '2025-06-10 16:23:32'),
(11450, 'Disaster', '2025-06-10 16:23:32'),
(11557, 'Espionage', '2025-06-10 16:23:32'),
(11559, 'Experimental', '2025-06-10 16:23:32'),
(11560, 'Film Noir', '2025-06-10 16:23:32'),
(11561, 'Game Show', '2025-06-10 16:23:32'),
(11562, 'Health & Fitness', '2025-06-10 16:23:32'),
(11563, 'Holiday', '2025-06-10 16:23:32'),
(11564, 'Indie', '2025-06-10 16:23:32'),
(11565, 'LGBTQ', '2025-06-10 16:23:32'),
(11566, 'Martial Arts', '2025-06-10 16:23:32'),
(11567, 'Military', '2025-06-10 16:23:32'),
(11568, 'Musical', '2025-06-10 16:23:32'),
(11569, 'Nature', '2025-06-10 16:23:32'),
(11570, 'Paranormal', '2025-06-10 16:23:32'),
(11571, 'Political', '2025-06-10 16:23:32'),
(11572, 'Psychological', '2025-06-10 16:23:32'),
(11573, 'Religious', '2025-06-10 16:23:32'),
(11574, 'Satire', '2025-06-10 16:23:32'),
(11575, 'Sports', '2025-06-10 16:23:32'),
(11576, 'Superhero', '2025-06-10 16:23:32'),
(11577, 'Supernatural', '2025-06-10 16:23:32'),
(11578, 'Teen', '2025-06-10 16:23:32'),
(11579, 'Travel', '2025-06-10 16:23:32'),
(11580, 'True Crime', '2025-06-10 16:23:32'),
(11581, 'Variety', '2025-06-10 16:23:32'),
(11582, 'Zombie', '2025-06-10 16:23:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `isi_log` varchar(256) NOT NULL,
  `tanggal_log` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `log`
--

INSERT INTO `log` (`id_log`, `isi_log`, `tanggal_log`, `id_user`) VALUES
(10, 'user (user) berhasil login', '2025-06-10 18:15:42', 1),
(11, 'user (user) berhasil logout', '2025-06-10 18:15:50', 1),
(12, 'rama (admin) berhasil login', '2025-06-10 18:16:04', 2),
(13, 'rama (admin) berhasil logout', '2025-06-10 18:16:12', 2),
(14, 'rama (admin) berhasil login', '2025-06-10 18:22:36', 2),
(15, 'rama (admin) berhasil logout', '2025-06-10 22:29:24', 2),
(16, 'rama (admin) berhasil login', '2025-06-10 23:49:02', 2),
(17, 'rama (admin) berhasil logout', '2025-06-11 00:24:45', 2),
(18, 'user (user) berhasil login', '2025-06-11 00:24:53', 1),
(19, 'user (user) berhasil logout', '2025-06-11 00:50:40', 1),
(20, 'rama (admin) berhasil login', '2025-06-11 00:50:48', 2),
(21, 'rama (admin) berhasil logout', '2025-06-11 06:05:20', 2),
(22, 'user (user) berhasil login', '2025-06-11 06:05:26', 1),
(23, 'ucii (admin) berhasil login', '2025-06-11 09:21:22', 3),
(24, 'ucii (admin) berhasil logout', '2025-06-11 09:37:14', 3),
(25, 'user (user) berhasil login', '2025-06-11 09:37:22', 1),
(26, 'user (user) berhasil logout', '2025-06-11 11:58:53', 1),
(27, 'rama (admin) berhasil login', '2025-06-11 11:59:09', 2),
(28, 'rama (admin) berhasil logout', '2025-06-11 13:08:55', 2),
(29, 'user (user) berhasil login', '2025-06-11 13:09:04', 1),
(30, 'user (user) berhasil logout', '2025-06-11 13:56:45', 1),
(31, 'rama (admin) berhasil login', '2025-06-11 13:56:53', 2),
(32, 'rama (admin) berhasil logout', '2025-06-11 14:15:19', 2),
(33, 'user (user) berhasil login', '2025-06-11 14:15:29', 1),
(34, 'user (user) berhasil logout', '2025-06-11 15:23:32', 1),
(35, 'rama (admin) berhasil login', '2025-06-11 15:23:42', 2),
(36, 'rama (admin) berhasil logout', '2025-06-11 17:09:55', 2),
(37, 'user (user) berhasil login', '2025-06-11 17:10:02', 1),
(38, 'user (user) berhasil logout', '2025-06-11 17:32:46', 1),
(39, 'rama (admin) berhasil login', '2025-06-11 17:32:56', 2),
(40, 'rama (admin) menambahkan user baru: atmin dengan role: Admin', '2025-06-11 19:46:57', 2),
(41, 'rama (admin) Mengganti role atmin menjadi User', '2025-06-11 19:47:11', 2),
(42, 'rama (admin) Menghapus user:  (ID: 9)', '2025-06-11 19:47:15', 2),
(43, 'rama (admin) berhasil login', '2025-06-11 21:12:30', 2),
(44, 'rama (admin) menambahkan TV Show baru: tes tv show', '2025-06-11 23:22:20', 2),
(45, 'rama (admin) Mengupdate TV Show dengan ID 2: tes tv show', '2025-06-11 23:23:01', 2),
(46, 'rama (admin) Mengupdate TV Show dengan ID 2: tes tv show', '2025-06-11 23:23:35', 2),
(47, 'rama (admin) berhasil logout', '2025-06-12 00:03:02', 2),
(48, 'user (user) berhasil login', '2025-06-12 00:03:07', 1),
(49, 'user (user) berhasil logout', '2025-06-12 00:33:00', 1),
(50, 'user (user) berhasil login', '2025-06-12 08:03:04', 1),
(51, 'user (user) berhasil logout', '2025-06-12 11:52:48', 1),
(52, 'rama (admin) berhasil login', '2025-06-12 11:53:05', 2),
(53, 'rama (admin) Mengupdate TV Show dengan ID 1: Kesultanan Bogor B -Series', '2025-06-12 12:06:05', 2),
(54, 'rama (admin) Mengupdate Movie dengan ID 3: Kesultanan Bogor B', '2025-06-12 12:13:36', 2),
(55, 'rama (admin) berhasil login', '2025-06-12 13:22:54', 2),
(56, 'rama (admin) menambahkan Movie baru: Absolute Cinema', '2025-06-12 13:25:31', 2),
(57, 'rama (admin) berhasil logout', '2025-06-12 13:27:10', 2),
(58, 'user (user) berhasil login', '2025-06-12 13:27:17', 1),
(59, 'user (user) berhasil logout', '2025-06-12 13:39:57', 1),
(60, 'rama (admin) berhasil login', '2025-06-12 13:40:10', 2),
(61, 'ucii (admin) berhasil login', '2025-06-12 13:55:00', 3),
(62, 'ucii (admin) menambahkan Movie baru: webb', '2025-06-12 13:57:29', 3),
(63, 'ucii (admin) Mengupdate Movie dengan ID 6: webb', '2025-06-12 13:58:03', 3),
(64, 'ucii (admin) Menghapus movie: webb (ID: 6)', '2025-06-12 13:58:12', 3),
(65, 'ucii (admin) berhasil logout', '2025-06-12 13:59:35', 3),
(66, 'ucii (admin) berhasil login', '2025-06-12 13:59:51', 3),
(67, 'ucii (admin) berhasil login', '2025-06-12 13:59:57', 3),
(68, 'ucii (admin) berhasil login', '2025-06-12 14:00:04', 3),
(69, 'ucii (admin) berhasil login', '2025-06-12 14:00:10', 3),
(70, 'ucii (admin) berhasil login', '2025-06-12 14:00:17', 3),
(71, 'ucii (admin) berhasil login', '2025-06-12 14:00:17', 3),
(72, 'ucii (admin) berhasil login', '2025-06-12 14:00:17', 3),
(73, 'ucii (admin) berhasil login', '2025-06-12 14:00:17', 3),
(74, 'ucii (admin) berhasil login', '2025-06-12 14:00:19', 3),
(75, 'ucii (admin) berhasil login', '2025-06-12 14:00:19', 3),
(76, 'ucii (admin) berhasil login', '2025-06-12 14:00:25', 3),
(77, 'ucii (admin) berhasil logout', '2025-06-12 14:01:23', 3),
(78, 'user (user) berhasil login', '2025-06-12 14:01:32', 1),
(79, 'user (user) berhasil logout', '2025-06-12 14:10:48', 1),
(80, 'user (user) berhasil login', '2025-06-12 14:21:36', 1),
(81, 'rama (admin) berhasil login', '2025-06-12 16:20:59', 2),
(82, 'rama (admin) berhasil login', '2025-06-12 16:21:16', 2),
(83, 'rama (admin) berhasil login', '2025-06-12 16:21:16', 2),
(84, 'rama (admin) berhasil login', '2025-06-12 16:21:16', 2),
(85, 'rama (admin) berhasil login', '2025-06-12 16:21:31', 2),
(86, 'rama (admin) berhasil login', '2025-06-12 16:21:31', 2),
(87, 'rama (admin) berhasil login', '2025-06-12 16:21:31', 2),
(88, 'rama (admin) berhasil login', '2025-06-12 16:21:32', 2),
(89, 'rama (admin) berhasil login', '2025-06-12 16:21:36', 2),
(90, 'rama (admin) berhasil logout', '2025-06-12 16:22:47', 2),
(91, 'user (user) berhasil login', '2025-06-12 16:22:58', 1),
(92, 'user (user) berhasil login', '2025-06-15 22:03:28', 1),
(93, 'user (user) berhasil login', '2025-06-21 18:11:31', 1),
(94, 'user (user) berhasil login', '2025-06-26 13:53:28', 1),
(95, 'user (user) berhasil login', '2025-06-29 15:28:17', 1),
(96, 'user (user) berhasil login', '2025-07-01 11:16:23', 1),
(97, 'user (user) berhasil logout', '2025-07-01 11:40:24', 1),
(98, 'rama (admin) berhasil login', '2025-07-01 11:40:36', 2),
(99, 'user (user) berhasil login', '2025-07-06 00:58:09', 1),
(100, 'user (user) berhasil logout', '2025-07-06 01:38:26', 1),
(101, 'rama (admin) berhasil login', '2025-07-06 01:38:45', 2),
(102, 'rama (admin) Mengupdate Movie dengan ID 5: Absolute Cinema', '2025-07-06 02:04:29', 2),
(103, 'rama (admin) berhasil logout', '2025-07-06 02:18:46', 2),
(104, 'user (user) berhasil login', '2025-07-06 02:18:57', 1),
(105, 'user (user) berhasil login', '2025-07-06 11:39:08', 1),
(106, 'user (user) berhasil login', '2025-07-26 00:21:58', 1),
(107, 'rama (admin) berhasil login', '2025-07-27 15:51:20', 2),
(108, 'rama (admin) berhasil login', '2025-09-15 23:34:56', 2),
(109, 'user (user) berhasil login', '2025-09-15 23:35:22', 1),
(110, 'user (user) berhasil login', '2025-09-16 01:14:20', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `movie_genre`
--

CREATE TABLE `movie_genre` (
  `movie_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `movie_genre`
--

INSERT INTO `movie_genre` (`movie_id`, `genre_id`) VALUES
(2, 10759),
(3, 12),
(3, 10759),
(4, 10766),
(4, 10767),
(5, 10463),
(5, 10759),
(5, 11559);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tvshow_genre`
--

CREATE TABLE `tvshow_genre` (
  `tvshow_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tvshow_genre`
--

INSERT INTO `tvshow_genre` (`tvshow_id`, `genre_id`) VALUES
(1, 11132),
(2, 12),
(2, 18),
(2, 35);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `is_admin`, `created_at`) VALUES
(1, 'user', 'user@gmail.com', '$2y$10$8Q4RfwmtBr2JztIpsnGW8eE2px.bLFtfK5XzcgDGM29rZcRQvd2wm', 0, '2025-06-08 23:04:29'),
(2, 'rama', 'f1d02310102@gmail.com', '$2y$10$NdwmFrcNgt75MRnFrw.xz.1t73inJfrGKJZxTM8W44HnU4T2MEkSi', 1, '2025-06-09 02:03:39'),
(3, 'ucii', 'sucitsrirhmdni.10@gmail.com', '$2y$10$HYGmi.kDSBw9IJMHZ5opNOCtYgc9ostO23180LPA8OU44M6VloC8S', 1, '2025-06-10 14:02:14'),
(4, 'rama', 'daniramadany2005@gmail.com', '$2y$10$AXVu5evrHHO.Q6EgJxyEUesEX9NRAZM/Ixji7yEVOF.dO5KaJKKtS', 0, '2025-09-16 01:13:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_movies`
--

CREATE TABLE `user_movies` (
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `status` enum('Watching','Completed','On-hold','Dropped','Plan to Watch') NOT NULL,
  `movie_type` enum('api_movie','custom_movie') NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_movies`
--

INSERT INTO `user_movies` (`user_id`, `movie_id`, `status`, `movie_type`, `added_at`) VALUES
(1, 552524, 'Plan to Watch', 'api_movie', '2025-06-12 06:24:50'),
(1, 986056, 'Watching', 'api_movie', '2025-07-05 17:12:42'),
(1, 1087192, 'Plan to Watch', 'api_movie', '2025-06-12 01:08:30'),
(1, 1233413, 'Watching', 'api_movie', '2025-06-12 06:03:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_tv_list`
--

CREATE TABLE `user_tv_list` (
  `user_id` int(11) NOT NULL,
  `tv_show_id` int(11) NOT NULL,
  `total_episodes` int(11) NOT NULL,
  `total_seasons` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('watching','completed','on-hold','dropped','plan to watch') DEFAULT 'plan to watch',
  `current_episode` int(11) DEFAULT '0',
  `current_season` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_tv_list`
--

INSERT INTO `user_tv_list` (`user_id`, `tv_show_id`, `total_episodes`, `total_seasons`, `added_at`, `status`, `current_episode`, `current_season`) VALUES
(1, 37854, 1135, 22, '2025-06-12 00:33:19', 'plan to watch', 110, 2),
(1, 100088, 0, 0, '2025-06-12 00:12:36', 'plan to watch', 12, 2),
(1, 245703, 9, 1, '2025-06-12 01:09:46', 'watching', 7, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indeks untuk tabel `custom_movie`
--
ALTER TABLE `custom_movie`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `custom_tv_show`
--
ALTER TABLE `custom_tv_show`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indeks untuk tabel `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD PRIMARY KEY (`movie_id`,`genre_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indeks untuk tabel `tvshow_genre`
--
ALTER TABLE `tvshow_genre`
  ADD PRIMARY KEY (`tvshow_id`,`genre_id`),
  ADD KEY `tvshow_genre_ibfk_2` (`genre_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_movies`
--
ALTER TABLE `user_movies`
  ADD PRIMARY KEY (`user_id`,`movie_id`,`movie_type`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indeks untuk tabel `user_tv_list`
--
ALTER TABLE `user_tv_list`
  ADD PRIMARY KEY (`user_id`,`tv_show_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `custom_movie`
--
ALTER TABLE `custom_movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `custom_tv_show`
--
ALTER TABLE `custom_tv_show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD CONSTRAINT `movie_genre_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `custom_movie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `movie_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tvshow_genre`
--
ALTER TABLE `tvshow_genre`
  ADD CONSTRAINT `tvshow_genre_ibfk_1` FOREIGN KEY (`tvshow_id`) REFERENCES `custom_tv_show` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tvshow_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_movies`
--
ALTER TABLE `user_movies`
  ADD CONSTRAINT `user_movies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_tv_list`
--
ALTER TABLE `user_tv_list`
  ADD CONSTRAINT `user_tv_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
