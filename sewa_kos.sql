-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 05, 2026 at 05:20 PM
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
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `foto_bukti` varchar(255) NOT NULL,
  `status` enum('hadir','izin','alpha') DEFAULT 'hadir',
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `tanggal`, `jam_masuk`, `foto_bukti`, `status`, `keterangan`, `created_at`) VALUES
(1, 2, '2026-04-05', '12:39:54', 'ABSEN_1775385594_2.jpg', 'hadir', '', '2026-04-05 10:39:54'),
(2, 12, '2026-04-05', '15:59:10', 'ABSEN_1775397550_12.jpg', 'hadir', '', '2026-04-05 13:59:10');

-- --------------------------------------------------------

--
-- Table structure for table `additional_services`
--

CREATE TABLE `additional_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `duration_type` enum('Harian','Mingguan','Bulanan') NOT NULL DEFAULT 'Mingguan',
  `service_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `additional_services`
--

INSERT INTO `additional_services` (`id`, `service_name`, `duration_type`, `service_price`, `created_at`, `updated_at`) VALUES
(1, 'Catering Makanan 2x Sehari', 'Harian', 25000, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(2, 'Laundry Express', 'Mingguan', 40000, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(3, 'Cleaning Service', 'Mingguan', 40000, '2026-03-08 00:49:15', '2026-03-08 00:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` varchar(50) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` enum('pending','paid','expired','canceled') NOT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `check_in`, `check_out`, `total_price`, `status`, `payment_token`, `created_at`, `updated_at`) VALUES
('KOS-1775394265', 8, 1, '2026-04-05', '2026-04-30', 1965000, 'paid', 'ad45b3c6-e3a5-4170-8a74-6e39495c6bc9', '2026-01-15 03:00:00', '2026-04-05 13:04:25'),
('KOS-1775394352', 8, 6, '2026-04-05', '2026-04-30', 2515000, 'paid', 'ded571d3-81be-4e3d-8648-8dd168d75281', '2026-02-10 07:00:00', '2026-04-05 13:05:52'),
('KOS-1775394409', 8, 11, '2026-04-05', '2026-05-31', 6140000, 'paid', '983ea730-972f-41f6-ac96-0da97a361189', '2026-03-20 02:00:00', '2026-04-05 13:06:49'),
('KOS-1775394448', 8, 16, '2026-04-05', '2026-05-31', 7940000, 'paid', '1f8f1deb-9d34-44e0-a908-6184a8033471', '2026-04-01 04:00:00', '2026-04-05 13:07:28'),
('KOS-1775395272', 8, 4, '2026-04-06', '2026-04-30', 1170000, 'paid', '356f2fa0-7152-4d7e-80a2-4c0eae3e7444', '2026-04-08 08:30:00', '2026-04-05 13:21:12'),
('KOS-1775395314', 8, 9, '2026-04-07', '2026-04-30', 2195000, 'paid', 'c7d62ad0-8c3c-44c0-842c-5d63220d6ad9', '2026-04-15 03:45:00', '2026-04-05 13:21:54'),
('KOS-1775396707', 8, 8, '2026-04-05', '2026-04-08', 300000, 'paid', '04d0561f-135c-444e-a142-cbd65eff9488', '2026-04-22 06:20:00', '2026-04-05 13:45:07'),
('KOS-1775396749', 8, 14, '2026-04-05', '2026-04-06', 125000, 'paid', '03ed74ee-f686-4d4b-a545-ebd1b9971170', '2026-04-02 01:00:00', '2026-04-05 13:45:49'),
('KOS-1775396785', 8, 14, '2026-04-05', '2026-04-06', 100000, 'paid', '2858f42b-9f02-4d09-b3ec-eb09e304806c', '2026-04-03 09:00:00', '2026-04-05 13:46:25'),
('KOS-1775396824', 8, 17, '2026-04-05', '2026-04-12', 1155000, 'paid', 'c12e4f5b-f0c4-4543-8355-f8470852b281', '2026-04-04 03:00:00', '2026-04-05 13:47:04'),
('KOS-1775396874', 8, 19, '2026-04-05', '2026-04-06', 150000, 'paid', '6ae07637-c20d-4159-9864-732c74253e44', '2026-04-05 06:00:00', '2026-04-05 13:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `booking_service`
--

CREATE TABLE `booking_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `additional_service_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` int(11) NOT NULL,
  `service_status` enum('pending','on_progress','done') DEFAULT 'pending',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_service`
--

INSERT INTO `booking_service` (`id`, `booking_id`, `additional_service_id`, `quantity`, `price_at_purchase`, `service_status`, `employee_id`, `created_at`, `updated_at`) VALUES
(4, 'KOS-1775394265', 1, 25, 25000, 'on_progress', 12, '2026-04-05 13:04:25', '2026-04-05 13:04:25'),
(5, 'KOS-1775394265', 2, 3, 40000, 'on_progress', 12, '2026-04-05 13:04:25', '2026-04-05 13:04:25'),
(6, 'KOS-1775394265', 3, 3, 40000, 'pending', NULL, '2026-04-05 13:04:25', '2026-04-05 13:04:25'),
(7, 'KOS-1775394352', 1, 25, 25000, 'on_progress', 12, '2026-04-05 13:05:52', '2026-04-05 13:05:52'),
(8, 'KOS-1775394352', 2, 3, 40000, 'on_progress', 12, '2026-04-05 13:05:52', '2026-04-05 13:05:52'),
(9, 'KOS-1775394352', 3, 3, 40000, 'pending', NULL, '2026-04-05 13:05:52', '2026-04-05 13:05:52'),
(10, 'KOS-1775394409', 1, 56, 25000, 'on_progress', 12, '2026-04-05 13:06:49', '2026-04-05 13:06:49'),
(11, 'KOS-1775394409', 2, 8, 40000, 'on_progress', 12, '2026-04-05 13:06:49', '2026-04-05 13:06:49'),
(12, 'KOS-1775394409', 3, 8, 40000, 'pending', NULL, '2026-04-05 13:06:49', '2026-04-05 13:06:49'),
(13, 'KOS-1775394448', 1, 56, 25000, 'on_progress', 12, '2026-04-05 13:07:28', '2026-04-05 13:07:28'),
(14, 'KOS-1775394448', 2, 8, 40000, 'on_progress', 12, '2026-04-05 13:07:28', '2026-04-05 13:07:28'),
(15, 'KOS-1775394448', 3, 8, 40000, 'pending', NULL, '2026-04-05 13:07:28', '2026-04-05 13:07:28'),
(16, 'KOS-1775395272', 3, 3, 40000, 'pending', NULL, '2026-04-05 13:21:12', '2026-04-05 13:21:12'),
(17, 'KOS-1775395314', 1, 23, 25000, 'on_progress', 12, '2026-04-05 13:21:54', '2026-04-05 13:21:54'),
(18, 'KOS-1775395314', 3, 3, 40000, 'pending', NULL, '2026-04-05 13:21:54', '2026-04-05 13:21:54'),
(19, 'KOS-1775396707', 1, 3, 25000, 'on_progress', 12, '2026-04-05 13:45:07', '2026-04-05 13:45:07'),
(20, 'KOS-1775396749', 1, 1, 25000, 'on_progress', 12, '2026-04-05 13:45:49', '2026-04-05 13:45:49'),
(21, 'KOS-1775396824', 1, 7, 25000, 'on_progress', 12, '2026-04-05 13:47:04', '2026-04-05 13:47:04'),
(22, 'KOS-1775396824', 2, 1, 40000, 'on_progress', 12, '2026-04-05 13:47:04', '2026-04-05 13:47:04'),
(23, 'KOS-1775396824', 3, 1, 40000, 'pending', NULL, '2026-04-05 13:47:04', '2026-04-05 13:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `SD` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `issue_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `status` enum('pending','on_progress','done') DEFAULT 'pending',
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `user_id`, `booking_id`, `issue_name`, `description`, `photo`, `location`, `status`, `employee_id`, `created_at`) VALUES
(4, 8, 'KOS-1775394352', 'Shower tidak menyala', 'Air di shower tidak keluar', 'MAINT_1775401488_4.jpg', 'Kamar S01', 'done', 12, '2026-04-05 14:25:27'),
(5, 8, 'KOS-1775394448', 'AC Tidak Dingin', 'AC mengalami kebocoran', 'ISSUE_1775401547_8.jpg', 'Kamar L01', 'on_progress', 12, '2026-04-05 15:05:47'),
(6, 8, 'KOS-1775396824', 'TV tidak bisa menyala', 'TV Rusak tidak dapat terhubung dengan internet', 'MAINT_1775402006_6.jpg', 'Kamar L02', 'done', 12, '2026-04-05 15:06:20');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_24_134318_create_room_types_table', 1),
(5, '2026_02_24_134324_create_rooms_table', 1),
(6, '2026_02_24_134331_create_additional_services_table', 1),
(7, '2026_02_24_134338_create_bookings_table', 1),
(8, '2026_02_24_135812_create_booking_service_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_type_id` bigint(20) UNSIGNED NOT NULL,
  `room_number` varchar(255) NOT NULL,
  `gender_type` enum('Putra','Putri') NOT NULL,
  `price` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `facilities` text NOT NULL,
  `area_size` varchar(255) NOT NULL,
  `is_electric_included` tinyint(1) NOT NULL DEFAULT 0,
  `is_water_included` tinyint(1) NOT NULL DEFAULT 1,
  `room_rules` text NOT NULL,
  `status` enum('available','occupied','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `gender_type`, `price`, `rating`, `facilities`, `area_size`, `is_electric_included`, `is_water_included`, `room_rules`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'H01', 'Putra', 800000, 4.7, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(2, 1, 'H02', 'Putri', 800000, 4.0, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(3, 1, 'H03', 'Putra', 800000, 4.4, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(4, 1, 'H04', 'Putri', 800000, 4.9, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(5, 1, 'H05', 'Putra', 800000, 4.3, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(6, 2, 'S01', 'Putra', 1200000, 5.0, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(7, 2, 'S02', 'Putri', 1200000, 4.5, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(8, 2, 'S03', 'Putra', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(9, 2, 'S04', 'Putri', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(10, 2, 'S05', 'Putra', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(11, 3, 'N01', 'Putra', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(12, 3, 'N02', 'Putri', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(13, 3, 'N03', 'Putra', 1500000, 4.4, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(14, 3, 'N04', 'Putri', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(15, 3, 'N05', 'Putra', 1500000, 4.0, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(16, 4, 'L01', 'Putra', 2000000, 4.4, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(17, 4, 'L02', 'Putri', 2000000, 4.3, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(18, 4, 'L03', 'Putra', 2000000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(19, 4, 'L04', 'Putri', 2000000, 4.1, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(20, 4, 'L05', 'Putra', 2000000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `facilities` text NOT NULL,
  `base_price_daily` int(11) NOT NULL,
  `base_price_weekly` int(11) NOT NULL,
  `base_price_monthly` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `image`, `description`, `facilities`, `base_price_daily`, `base_price_weekly`, `base_price_monthly`, `created_at`, `updated_at`) VALUES
(1, 'Hemat', 'kamar-hemat.jpg', 'Kamar sederhana dan nyaman untuk mahasiswa dengan harga terjangkau.', '\"Bed, Lemari, Meja Belajar, Kipas Angin, WiFi\"', 50000, 300000, 800000, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(2, 'Santai', 'kamar-santai.jpg', 'Kamar dengan fasilitas lebih lengkap dan kamar mandi dalam.', '\"Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi\"', 75000, 450000, 1200000, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(3, 'Nyaman', 'kamar-nyaman.jpg', 'Kamar luas cocok untuk mahasiswa tingkat akhir atau pekerja remote.', '\"Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi\"', 100000, 600000, 1500000, '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(4, 'Luas', 'kamar-luas.jpg', 'Kamar paling lega dengan fasilitas lengkap dan nyaman untuk jangka panjang.', '\"Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi\"', 150000, 900000, 2000000, '2026-03-08 00:49:15', '2026-03-08 00:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `service_reports`
--

CREATE TABLE `service_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_service_id` bigint(20) UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `proof_photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_reports`
--

INSERT INTO `service_reports` (`id`, `booking_service_id`, `report_date`, `proof_photo`, `created_at`) VALUES
(3, 4, '2026-04-05', 'LAPOR_4_1775401095.jpg', '2026-04-05 14:58:15'),
(4, 7, '2026-04-05', 'LAPOR_7_1775401112.jpg', '2026-04-05 14:58:32'),
(5, 10, '2026-04-05', 'LAPOR_10_1775402050.jpg', '2026-04-05 15:14:10'),
(6, 22, '2026-04-05', 'LAPOR_22_1775402241.jpg', '2026-04-05 15:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Ca4JoYaKgeGVH1oW7E428ccic8VL7no3bJw56xuX', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekZlNnVDQ1EyZWJXRGlRMkpleVg5RHFWMVViY001OW04M1N2c1NSTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9zZXdhLWtvcy50ZXN0L3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774169151),
('RDN6zqrbDqsbJ75VgPv1paWa9nbkD3q2g6BiJ8bS', 5, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN0MxVXRDcnRPTGY5d2FDVmlWb3RUZmNXMGQydUtxNzFVeW9LVTU2ciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9zZXdhLWtvcy50ZXN0L2thbWFyLzEiO3M6NToicm91dGUiO3M6MTA6ImthbWFyLnNob3ciO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1772985881),
('sArZQ1gy9rpj6YkhNXHnbxB74dS3VpqCuUkumUs6', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXJxQ2FNNlJsMkQ5MzNDYXNscVJoRVEwN3Y5ZnBsZ0xWNExKd0pueCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly9zZXdhLWtvcy50ZXN0L2thbWFyL2Rhc2hib2FyZC5waHAiO3M6NToicm91dGUiO3M6MTA6ImthbWFyLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774196427),
('YJabUPWGFlfSvXt6TgiTvqYryY0faZUxDvjepjt7', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY05JMlpodUlRMWk4YU9EMEhhSkdjRzVSejJhbFI5SEZLWVJqT0NPMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjA6Imh0dHA6Ly9zZXdhLWtvcy50ZXN0IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO319', 1773127856);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('site_title', 'Griya Asri Kos');

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
(12, 'fahis', 'fahis', NULL, 'fahis@gmail.com', NULL, '039239293', NULL, '$2y$10$YD1kQ1ahFFMUH2Ev7GBzReVRjt0mmzXAeALjiA2ITiWEI7meS9XIu', NULL, NULL, NULL, 'pegawai', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `additional_services`
--
ALTER TABLE `additional_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_room_id_foreign` (`room_id`);

--
-- Indexes for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_service_booking_id_index` (`booking_id`),
  ADD KEY `booking_service_additional_service_id_index` (`additional_service_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `fk_maintenance_employee` (`employee_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_room_type_id_foreign` (`room_type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_reports`
--
ALTER TABLE `service_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_service_id` (`booking_service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

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
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `additional_services`
--
ALTER TABLE `additional_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_service`
--
ALTER TABLE `booking_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_reports`
--
ALTER TABLE `service_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_service`
--
ALTER TABLE `booking_service`
  ADD CONSTRAINT `booking_service_additional_service_id_foreign` FOREIGN KEY (`additional_service_id`) REFERENCES `additional_services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_service_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `fk_maintenance_employee` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`);

--
-- Constraints for table `service_reports`
--
ALTER TABLE `service_reports`
  ADD CONSTRAINT `service_reports_ibfk_1` FOREIGN KEY (`booking_service_id`) REFERENCES `booking_service` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
