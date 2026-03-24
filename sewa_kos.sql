-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2026 at 09:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sewa_kos`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `full_name_ktp` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `ktp_photo` varchar(255) DEFAULT NULL,
  `selfie_photo` varchar(255) DEFAULT NULL,
  `is_verified` enum('pending','verified','rejected') DEFAULT NULL,
  `role` enum('admin','pegawai','penyewa') NOT NULL DEFAULT 'penyewa',
  `gender` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nickname`, `full_name_ktp`, `email`, `address`, `phone`, `email_verified_at`, `password`, `ktp_photo`, `selfie_photo`, `is_verified`, `role`, `gender`, `birth_date`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Kos', 'admin', NULL, 'admin@gmail.com', NULL, '08123456781', NULL, '$2y$12$VT5aIm0b8gBGcH/n0BX2audNhfZ4Fx8XsRphem6BwZlw6UMTA4gZ6', NULL, NULL, NULL, 'admin', NULL, NULL, NULL, '2026-03-08 00:49:14', '2026-03-08 00:49:14'),
(2, 'Pegawai User', 'pegawai', NULL, 'pegawai@gmail.com', NULL, '08123456782', NULL, '$2y$12$cGctb3VdiPPrKNz9JgBl2.BJKuGBYKg9xdFJBpwNTHs2bxIcXgKSO', NULL, NULL, NULL, 'pegawai', NULL, NULL, NULL, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(3, 'Penyewa User', 'penyewa', NULL, 'penyewa@gmail.com', NULL, '08123456783', NULL, '$2y$12$zecB7rAOWvLRUX4AnPAHMexniy0trt54ncBXKZAysXt9uSuJtBIBa', NULL, NULL, NULL, 'penyewa', NULL, NULL, NULL, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(8, 'hammam', 'hammam', 'Muhammad Raynar Hammam', 'hammam@gmail.com', 'Manukan Luhur', '08953023232', NULL, '$2y$10$q6WGQriIEhQflX1TxMRRoOaevWXkItKrvK8w4n65BMD6WBZG1l5T2', 'ktp_8.jpg', 'selfie_8.jpg', 'verified', 'penyewa', 'Laki-laki', '2006-05-23', NULL, NULL, NULL),
(9, 'ronaldo', 'ronaldo', 'Cristiano Ronaldo', 'ronaldo@gmail.com', 'Portugal Gang 7', '08953302323', NULL, '$2y$10$pASa0DhTQHD3V1QBIugxq.rcsL4Cgf0e28hkrFlHX5uxa20C7/Jv.', 'ktp_9.jpg', 'selfie_9.jpg', 'verified', 'penyewa', 'Laki-laki', '1997-05-23', NULL, NULL, NULL),
(10, 'fahmi', 'fahmi', 'fahmi zaki ahmad', 'fahmi@gmail.com', 'Jalan Simo Gunung Gang 5', '0888819929', NULL, '$2y$10$S8kBpYVu7W.K/JssHlM4guSrdv4aNBIEMcZtohW3ag875XY7Tj71u', 'ktp_10.jpg', 'selfie_10.jpg', 'verified', 'penyewa', 'Laki-laki', '2020-03-23', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
