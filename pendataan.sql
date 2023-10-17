-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 17, 2023 at 06:19 AM
-- Server version: 8.0.33
-- PHP Version: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pendataan`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `code`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'JAKSEL', 'Jakarta Selatan', '2023-10-13 21:40:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_collection`
--

CREATE TABLE `data_collection` (
  `id` int NOT NULL,
  `coordinator_id` int DEFAULT NULL,
  `city_id` int DEFAULT NULL,
  `disctrict_id` int DEFAULT NULL,
  `subdisctrict_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `no_tps` int DEFAULT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `whatsapp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_reference`
--

CREATE TABLE `data_reference` (
  `id` int NOT NULL,
  `city_id` int DEFAULT NULL,
  `disctrict_id` int DEFAULT NULL,
  `subdisctrict_id` int DEFAULT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `id` int NOT NULL,
  `city_id` int DEFAULT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`id`, `city_id`, `code`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'TBT', 'Tebet', '2023-10-13 21:42:32', NULL, NULL),
(2, 1, 'MPNG', 'Mampang', '2023-10-13 21:42:32', NULL, NULL),
(3, 1, 'PCRN', 'Pancoran', '2023-10-13 21:42:32', NULL, NULL),
(4, 1, 'PSRMG', 'Pasar Minggu', '2023-10-13 21:42:32', NULL, NULL),
(5, 1, 'JGKRS', 'Jagakarsa', '2023-10-13 21:42:32', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `election_results`
--

CREATE TABLE `election_results` (
  `id` int NOT NULL,
  `city_id` int DEFAULT NULL,
  `disctrict_id` int DEFAULT NULL,
  `subdisctrict_id` int DEFAULT NULL,
  `no_tps` int DEFAULT NULL,
  `total_result` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `code`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ADM', 'Administrator', '2023-10-13 21:38:07', NULL, NULL),
(2, 'COR', 'Coordinator', '2023-10-13 21:38:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subdistrict`
--

CREATE TABLE `subdistrict` (
  `id` int NOT NULL,
  `district_id` int DEFAULT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subdistrict`
--

INSERT INTO `subdistrict` (`id`, `district_id`, `code`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'TBT_MD', 'Menteng Dalam', '2023-10-13 21:45:44', NULL, NULL),
(2, 1, 'TBT_TB', 'Tebet Barat', '2023-10-13 21:45:44', NULL, NULL),
(3, 1, 'TBT_TT', 'Tebet Timur', '2023-10-13 21:45:44', NULL, NULL),
(4, 1, 'TBT_KB', 'Kebon Baru', '2023-10-13 21:45:44', NULL, NULL),
(5, 1, 'TBT_BD', 'Bukit Duri', '2023-10-13 21:45:44', NULL, NULL),
(6, 1, 'TBT_MG', 'Manggarai', '2023-10-13 21:45:44', NULL, NULL),
(7, 1, 'TBT_MS', 'Manggarai Selatan', '2023-10-13 21:45:44', NULL, NULL),
(8, 2, 'MPNG_KB', 'Kuningan Barat', '2023-10-13 21:47:37', NULL, NULL),
(9, 2, 'MPNG_PM', 'Pela Mampang', '2023-10-13 21:47:37', NULL, NULL),
(10, 2, 'MPNG_BG', 'Bangka', '2023-10-13 21:47:37', NULL, NULL),
(11, 2, 'MPNG_TP', 'Tegal Parang', '2023-10-13 21:47:37', NULL, NULL),
(12, 2, 'MPNG_MP', 'Mampang Prapatan', '2023-10-13 21:47:37', NULL, NULL),
(13, 3, 'PCRN_PC', 'Pancoran', '2023-10-13 21:50:52', NULL, NULL),
(14, 3, 'PCRN_CK', 'Cikoko', '2023-10-13 21:50:52', NULL, NULL),
(15, 3, 'PCRN_PD', 'Pangadegan', '2023-10-13 21:50:52', NULL, NULL),
(16, 3, 'PCRN_RJ', 'Rawajati', '2023-10-13 21:50:52', NULL, NULL),
(17, 3, 'PCRN_KL', 'Kalibata', '2023-10-13 21:50:52', NULL, NULL),
(18, 3, 'PCRN_DT', 'Duren Tiga', '2023-10-13 21:50:52', NULL, NULL),
(19, 4, 'PSRMG_PB', 'Pejaten Barat', '2023-10-13 21:54:04', NULL, NULL),
(20, 4, 'PSRMG_PM', 'Pasar Minggu', '2023-10-13 21:54:04', NULL, NULL),
(21, 4, 'PSRMG_JP', 'Jati Padang', '2023-10-13 21:54:04', NULL, NULL),
(22, 4, 'PSRMG_RG', 'Ragunan', '2023-10-13 21:54:04', NULL, NULL),
(23, 4, 'PSRMG_CT', 'Cilandak Timur', '2023-10-13 21:54:04', NULL, NULL),
(24, 4, 'PSRMG_PT', 'Pejaten TImur', '2023-10-13 21:54:04', NULL, NULL),
(25, 4, 'PSRMG_KB', 'Kebagusan', '2023-10-13 21:54:04', NULL, NULL),
(26, 5, 'JGKRS_TB', 'Tanjung Barat', '2023-10-13 21:56:24', NULL, NULL),
(27, 5, 'JGKRS_LA', 'Lenteng Agung', '2023-10-13 21:56:24', NULL, NULL),
(28, 5, 'JGKRS_JG', 'Jagakarsa', '2023-10-13 21:56:24', NULL, NULL),
(29, 5, 'JGKRS_CG', 'Ciganjur', '2023-10-13 21:56:24', NULL, NULL),
(30, 5, 'JGKRS_SS', 'Srengseng Sawah', '2023-10-13 21:56:24', NULL, NULL),
(31, 5, 'JGKRS_CP', 'Cipedak', '2023-10-13 21:56:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `groups_id` int DEFAULT NULL,
  `subdisctrict_id` int DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` int DEFAULT '1' COMMENT '1=>Aktif, 0=>Tidak Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `groups_id`, `subdisctrict_id`, `username`, `name`, `email`, `phone_no`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 1, NULL, 'admin', 'Admin Smartrio', 'smartrio@gmail.com', '12345678910', '$2y$10$f4vlF/mosXKsxhmX.BzpjeSoxJ1WjCcwcm02I3iu.rASt0T2cq3PO', NULL, '2023-10-15 21:59:51', '2023-10-16 08:59:39', NULL, 1),
(2, 2, NULL, 'coordinator', 'Coordinator', NULL, NULL, '$2y$10$vm1CueaQyjJi1cCIgX1/6eK5KN3YKFajJbRlnx2rO.V9JRis7n0Vu', NULL, '2023-10-15 21:59:51', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_collection`
--
ALTER TABLE `data_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_reference`
--
ALTER TABLE `data_reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `election_results`
--
ALTER TABLE `election_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subdistrict`
--
ALTER TABLE `subdistrict`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `data_collection`
--
ALTER TABLE `data_collection`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_reference`
--
ALTER TABLE `data_reference`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `election_results`
--
ALTER TABLE `election_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subdistrict`
--
ALTER TABLE `subdistrict`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
