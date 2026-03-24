-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2026 at 09:23 AM
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
('KOS-1774337153', 8, 20, '2026-03-24', '2026-05-31', 7620000, 'paid', '85645f70-0020-4722-8d52-b77d9158ad1e', '2026-03-24 07:25:53', '2026-03-24 07:25:53'),
('KOS-1774337387', 10, 11, '2026-03-24', '2026-06-30', 8870000, 'paid', '505dc994-e993-47f8-bb1b-d2681c9ad477', '2026-03-24 07:29:47', '2026-03-24 07:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `booking_service`
--

CREATE TABLE `booking_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `additional_service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 1, 'H01', 'Putra', 800000, 4.7, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4 m', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(2, 1, 'H02', 'Putri', 800000, 4.0, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4 m', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(3, 1, 'H03', 'Putra', 800000, 4.4, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4 m', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(4, 1, 'H04', 'Putri', 800000, 4.9, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4 m', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(5, 1, 'H05', 'Putra', 800000, 4.3, 'Bed, Lemari, Meja Belajar, Kipas Angin, WiFi', '3x4 m', 0, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(6, 2, 'S01', 'Putra', 1200000, 5.0, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(7, 2, 'S02', 'Putri', 1200000, 4.5, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(8, 2, 'S03', 'Putra', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(9, 2, 'S04', 'Putri', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(10, 2, 'S05', 'Putra', 1200000, 4.7, 'Bed, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(11, 3, 'N01', 'Putra', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(12, 3, 'N02', 'Putri', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(13, 3, 'N03', 'Putra', 1500000, 4.4, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(14, 3, 'N04', 'Putri', 1500000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(15, 3, 'N05', 'Putra', 1500000, 4.0, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, WiFi', '3x4 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(16, 4, 'L01', 'Putra', 2000000, 4.4, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(17, 4, 'L02', 'Putri', 2000000, 4.3, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(18, 4, 'L03', 'Putra', 2000000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(19, 4, 'L04', 'Putri', 2000000, 4.1, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'available', '2026-03-08 00:49:15', '2026-03-08 00:49:15'),
(20, 4, 'L05', 'Putra', 2000000, 4.6, 'Bed Queen, Lemari Besar, Meja Kerja, AC, Kamar Mandi Dalam, TV, WiFi', '4x5 m', 1, 1, '1. Dilarang membawa lawan jenis ke dalam kamar.\n                    \n2. Maksimal bertamu jam 22.00 WIB.\n                    \n3. Menjaga kebersihan dan ketenangan.', 'occupied', '2026-03-08 00:49:15', '2026-03-08 00:49:15');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `additional_services`
--
ALTER TABLE `additional_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_service`
--
ALTER TABLE `booking_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_room_type_id_foreign` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
