-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 11:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_man`
--

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('IN','OUT') NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `recent_quantity` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `user_id`, `type`, `stock_quantity`, `recent_quantity`, `remarks`, `created_at`, `updated_at`) VALUES
(7, 15, 1, 'OUT', 11, 10, NULL, '2025-02-13 14:31:15', '2025-02-13 14:31:15'),
(8, 15, 1, 'OUT', 10, 9, NULL, '2025-02-13 14:32:12', '2025-02-13 14:32:12'),
(9, 15, 1, 'OUT', 9, 7, NULL, '2025-02-13 14:32:50', '2025-02-13 14:32:50'),
(10, 15, 1, 'OUT', 7, 6, NULL, '2025-02-13 14:34:04', '2025-02-13 14:34:04'),
(11, 23, 1, 'IN', 2, NULL, '', '2025-02-13 14:35:01', '2025-02-13 14:35:01'),
(12, 23, 1, 'IN', 2, 3, NULL, '2025-02-13 15:00:56', '2025-02-13 15:00:56'),
(13, 14, 1, 'OUT', 3, 2, NULL, '2025-02-11 15:31:16', '2025-02-13 15:31:16'),
(14, 24, 1, 'IN', 3, NULL, '', '2025-02-13 22:25:09', '2025-02-13 22:25:09'),
(15, 25, 1, 'IN', 1, NULL, '', '2025-02-14 15:13:00', '2025-02-14 15:13:00'),
(16, 25, 1, 'IN', 1, 2, NULL, '2025-02-14 15:20:22', '2025-02-14 15:20:22'),
(17, 25, 2, 'OUT', 2, 1, NULL, '2025-02-14 18:07:00', '2025-02-14 18:07:00'),
(18, 15, 2, 'OUT', 6, 5, NULL, '2025-02-14 18:08:34', '2025-02-14 18:08:34'),
(19, 15, 2, 'OUT', 5, 4, NULL, '2025-02-14 18:08:52', '2025-02-14 18:08:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
