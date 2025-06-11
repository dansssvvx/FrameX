-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Jun 2025 pada 09.54
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
(4, 'Semoga masih ada artinya', 'brief description the movie', 'Dari dapur kecil, menjadi besar', '2025-06-11', 'Planned', 0, 'https://facebook.com', 'https://picsum.photos/2000/3000?random=3', '2025-06-11 01:36:34');

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
(35, 'rama (admin) berhasil login', '2025-06-11 15:23:42', 2);

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
(3, 10463),
(3, 11565),
(4, 10766),
(4, 10767);

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
(7, 'user1', 'user1@gmail.com', '$2y$10$8Q4RfwmtBr2JztIpsnGW8eE2px.bLFtfK5XzcgDGM29rZcRQvd2wm', 0, '2025-06-08 23:04:29');

--
-- Indexes for dumped tables
--

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
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `custom_movie`
--
ALTER TABLE `custom_movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `custom_tv_show`
--
ALTER TABLE `custom_tv_show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
