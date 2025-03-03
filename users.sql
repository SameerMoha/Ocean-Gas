-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 10:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myapp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','sales','procurement','support','user') NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `reset_token`) VALUES
(1, 'jamal', 'jamal@gmail.com', '$2y$10$h6ywkkybYEPLkaPsuRGlH.hFCQo8um/5Ksx1uSX3bVYa4vUVz82TS', '2025-02-25 11:55:16', 'sales', NULL),
(2, 'Sameer Munir', 'sameermunid606@gmail.com', '$2y$10$2dN1PP1ZwLZomj6OIYP8V..eKaxeRCg9nXjhovv1pfRUhVSC.XsQy', '2025-02-27 11:11:00', 'admin', NULL),
(3, 'John', 'j@none.com', '$2y$10$uFQHJGn/Rtg8.YWagwVo8umOIGjej9Pm4FloFfD1ZamD.8iSsOt2m', '2025-02-28 06:41:26', 'procurement', NULL),
(4, 'johnnie ngugi', 'johnwanjagi18@gmail.com', '$2y$10$4OY/erML8xYL0FM7diVNMOfL2E6lYPUl2m9Zkr6MANJj0BjuujxQC', '2025-02-28 08:11:58', 'support', '3d9f21531ff0dfce892e71e3390baef834507bff4b250c799d601b1e159456331b31fb7c4625eea5b6f92581790529583671'),
(5, 'mikaeel', 'mikaeelmoha123@gmail.com', '$2y$10$U/vK9W/emFbB4Zs0HmphU.EL7i9.GbGBzzyAjZY1rylwODZNFrHmC', '2025-02-28 08:14:14', 'user', '6d8495a5c024b0c122c63d8c1c3defb162157dbc9142f29d17d21fe8a46a584dba8d5088088f0d847082a192336a3a680737'),
(6, 'Athman Ali', '4athmanali@gmail.com', '$2y$10$ayT3AcnsXZ8aYA9g8I58J.oHSLvG3bhQ81SScGhxn3AmnkW0cWAd2', '2025-02-28 13:26:37', 'admin', 'b82f40aa7828367e8ef2be7774ab5c80bf2fead9d6f6187b22e8a018b65cd79fae02819b45b8a7a4bb4bfe9df1f51603726d'),
(8, 'Asman', 'asman@gmail.com', '$2y$10$pA93RzNztUfdH7F6wwXw7eTq.1m6Xgzh.6ISyQeodAzIsXBJwwUy6', '2025-03-03 09:37:31', 'sales', NULL),
(9, 'Elizabeth', 'Elizabeth@gmail.com', '$2y$10$M.dqquu0ggasqFt3iKXDRehYSVqscOdFlga49TI3SklTh9HUl0n/C', '2025-03-03 09:45:38', 'procurement', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
