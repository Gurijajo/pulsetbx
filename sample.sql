-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 08, 2025 at 01:04 PM
-- Server version: 8.0.32
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `festival_tickets`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `bio` text,
  `image_url` varchar(255) DEFAULT NULL,
  `social_links` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `name`, `bio`, `image_url`, `social_links`, `created_at`) VALUES
(5, 'LEBLANC', 'Techno DJ from Berlin', '/images/test.jfif', NULL, '2025-06-24 07:58:58'),
(6, 'SEBASTIAN', 'House music legend', '/images/sebastian.jfif', NULL, '2025-06-24 07:58:58'),
(7, 'PEGGY GOU', 'Korean DJ and producer', '/images/peegy.jfif', NULL, '2025-06-24 07:58:58'),
(8, 'VINI VICI', 'Psytrance duo', '/images/vinivici.jfif', NULL, '2025-06-24 07:58:58'),
(9, 'KEINEMUSIK', 'German electronic collective', '/assets/images/artists/keine.jpg', NULL, '2025-06-24 07:58:58'),
(10, 'DEBORAH DE LUCA', 'Italian techno queen', '/images/deluca.jfif', NULL, '2025-06-24 07:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `artist_id` int DEFAULT NULL,
  `stage` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `start_time`, `end_time`, `artist_id`, `stage`, `created_at`) VALUES
(1, 'LEBLANC Live', NULL, '2024-07-28', '00:00:00', NULL, 5, 'Main Stage', '2025-06-24 07:58:58'),
(2, 'SEBASTIAN Set', NULL, '2024-07-28', '01:00:00', NULL, 6, 'Main Stage', '2025-06-24 07:58:58'),
(3, 'PEGGY GOU Performance', NULL, '2024-07-28', '03:00:00', NULL, 7, 'Main Stage', '2025-06-24 07:58:58'),
(4, 'VINI VICI Show', NULL, '2024-07-28', '04:00:00', NULL, 8, 'Main Stage', '2025-06-24 07:58:58'),
(5, 'KEINEMUSIK Collective', NULL, '2024-07-28', '05:00:00', NULL, 9, 'Main Stage', '2025-06-24 07:58:58'),
(6, 'DEBORAH DE LUCA Live', NULL, '2024-07-28', '06:00:00', NULL, 10, 'Main Stage', '2025-06-24 07:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `success` tinyint(1) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `success`, `ip_address`, `created_at`) VALUES
(1, 3, 0, '::1', '2025-06-26 08:55:47'),
(2, 3, 0, '::1', '2025-06-26 08:56:57'),
(3, 3, 0, '::1', '2025-06-26 08:58:26'),
(4, 3, 1, '::1', '2025-06-26 08:58:34'),
(5, 3, 0, '::1', '2025-06-26 08:59:09'),
(6, 3, 0, '::1', '2025-06-26 08:59:11'),
(7, 3, 0, '::1', '2025-06-26 08:59:12'),
(8, 3, 0, '::1', '2025-06-26 08:59:14'),
(9, 3, 0, '::1', '2025-06-26 08:59:16'),
(10, 1, 0, '::1', '2025-07-08 07:07:21'),
(11, 3, 0, '::1', '2025-07-08 07:09:04'),
(12, 1, 1, '::1', '2025-07-08 07:13:38');

-- --------------------------------------------------------

--
-- Table structure for table `merch_items`
--

CREATE TABLE `merch_items` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ka` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description_ka` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_quantity` int DEFAULT '0',
  `size_options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_options` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merch_items`
--

INSERT INTO `merch_items` (`id`, `name`, `name_ka`, `description`, `description_ka`, `price`, `image`, `category`, `available_quantity`, `size_options`, `color_options`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'gurijajo', 'გურიჯაჯო', 'test', 'ტესტი', 45.00, '../uploads/merch/1751965660_481177642_1146599013507357_23754659167179989_n.jpg', 'clothing', 27, '[\"L\"]', '[\"#3bff44\",\"#db2525\",\"#035bff\"]', 1, '2025-07-08 09:07:40', '2025-07-08 09:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `merch_orders`
--

CREATE TABLE `merch_orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'processing',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merch_orders`
--

INSERT INTO `merch_orders` (`id`, `user_id`, `total_amount`, `shipping_address`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 145.00, 'Nihil dignissimos de, Unde sed nulla ducim, 0167, Georgia', 'crypto', 'delivered', '2025-07-08 09:09:37', '2025-07-08 09:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `ticket_type_id` int NOT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `quantity` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `ticket_type_id`, `id_number`, `quantity`, `total_amount`, `status`, `payment_method`, `qr_code`, `created_at`) VALUES
(12, 3, 3, NULL, 3, 1497.00, 'approved', 'crypto', NULL, '2025-06-24 11:26:56'),
(13, 3, 2, NULL, 1, 299.00, 'approved', 'crypto', NULL, '2025-06-24 13:56:00'),
(14, 3, 3, NULL, 3, 1497.00, 'approved', 'paypal', NULL, '2025-06-25 07:59:06'),
(15, 3, 3, NULL, 2, 998.00, 'approved', 'paypal', NULL, '2025-06-25 07:59:53'),
(16, 4, 3, NULL, 1, 499.00, 'approved', 'paypal', NULL, '2025-06-25 08:10:26'),
(17, 4, 3, NULL, 1, 499.00, 'approved', 'paypal', NULL, '2025-06-25 08:10:36'),
(18, 4, 3, NULL, 1, 499.00, 'approved', 'credit_card', NULL, '2025-06-25 08:10:45'),
(19, 4, 3, NULL, 1, 499.00, 'approved', 'apple_pay', NULL, '2025-06-25 08:10:50'),
(20, 4, 3, NULL, 1, 499.00, 'approved', 'crypto', NULL, '2025-06-25 08:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `merch_id` int NOT NULL,
  `quantity` int NOT NULL,
  `size` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_per_unit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `merch_id`, `quantity`, `size`, `color`, `price_per_unit`) VALUES
(1, 1, 1, 3, 'L', '#db2525', 45.00);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_holders`
--

CREATE TABLE `ticket_holders` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `qr_code` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ticket_holders`
--

INSERT INTO `ticket_holders` (`id`, `order_id`, `first_name`, `last_name`, `id_number`, `is_main`, `qr_code`, `created_at`) VALUES
(3, 12, 'Guram', 'Jajanidze', '', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-12-3-1750764416', '2025-06-24 11:26:56'),
(4, 12, 'Theodore', 'Fitzpatrick', '251', 0, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-12-4-1750764416', '2025-06-24 11:26:56'),
(5, 12, 'Cyrus', 'Snow', '685', 0, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-12-5-1750764416', '2025-06-24 11:26:56'),
(6, 13, 'Guram', 'Jajanidze', '', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-13-6-1750773360', '2025-06-24 13:56:00'),
(7, 14, 'Guram', 'Jajanidze', '', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-14-7-1750838346', '2025-06-25 07:59:06'),
(8, 14, 'Xandra', 'Hernandez', '479', 0, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-14-8-1750838346', '2025-06-25 07:59:06'),
(9, 14, 'Dale', 'Ayers', '476', 0, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-14-9-1750838346', '2025-06-25 07:59:06'),
(10, 15, 'Guram', 'Jajanidze', '', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-15-10-1750838393', '2025-06-25 07:59:53'),
(11, 15, 'Gillian', 'Pitts', '252', 0, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-15-11-1750838393', '2025-06-25 07:59:53'),
(12, 16, 'Basil', 'Roach', '832', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-16-12-1750839026', '2025-06-25 08:10:26'),
(13, 17, 'Basil', 'Roach', '832', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-17-13-1750839036', '2025-06-25 08:10:36'),
(14, 18, 'Basil', 'Roach', '832', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-18-14-1750839045', '2025-06-25 08:10:45'),
(15, 19, 'Basil', 'Roach', '832', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-19-15-1750839050', '2025-06-25 08:10:50'),
(16, 20, 'Basil', 'Roach', '832', 1, 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-20-16-1750839055', '2025-06-25 08:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `total_quantity` int NOT NULL,
  `available_quantity` int NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `name`, `price`, `description`, `total_quantity`, `available_quantity`, `is_active`, `created_at`) VALUES
(1, 'General Admission', 99.00, 'Standard festival access', 1000, 1000, 1, '2025-06-24 07:58:58'),
(2, 'VIP Pass', 299.00, 'VIP area access + perks', 200, 177, 1, '2025-06-24 07:58:58'),
(3, 'Premium Experience', 499.00, 'All access + backstage', 50, 36, 1, '2025-06-24 07:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `id_number` varchar(50) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `failed_login_attempts` int DEFAULT '0',
  `last_failed_login` timestamp NULL DEFAULT NULL,
  `session_invalidated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `id_number`, `is_admin`, `created_at`, `is_active`, `failed_login_attempts`, `last_failed_login`, `session_invalidated_at`) VALUES
(1, 'admin', 'admin@festival.com', '$2a$12$zuYp2ySmhByCBOczISgjfOTlqATvH8oSJdApnU6hRfCisfi2tSC0q', 'Admin', 'User', NULL, '123123123', 1, '2025-06-24 07:58:58', 1, 0, NULL, NULL),
(2, 'rinytyrefa', 'gabuhula@mailinator.com', '$2y$10$LUcC56YJVuGekTIWqot57e6.5zp7SSc5Gfhv.DjacDQSdY8nF1aAG', 'admin', 'admin', '+1 (146) 329-7464', '12312312312', 0, '2025-06-24 08:02:39', 1, 0, NULL, NULL),
(3, 'Gurijajo', 'admin@gmail.com', '$2y$10$MKKGQ3fB3bixfwHESkOIOOKUYT0/yLrNHsK.AOBtyvCiln.VR2c6O', 'Guram', 'Jajanidze', '+1 (495) 386-7497', '12312312312', 1, '2025-06-24 08:03:32', 1, 6, '2025-07-08 07:09:04', NULL),
(4, 'gurama1', 'gurama1@gmail.com', '$2y$10$UYls4sp/Pnija8boJMGwNOctPJA9zDWOyk/Y3eH2Pv9PkKaJVneCW', 'Basil', 'Roach', '+1 (898) 202-3804', '832', 0, '2025-06-25 08:04:28', 1, 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `merch_items`
--
ALTER TABLE `merch_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merch_orders`
--
ALTER TABLE `merch_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_type_id` (`ticket_type_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `merch_id` (`merch_id`);

--
-- Indexes for table `ticket_holders`
--
ALTER TABLE `ticket_holders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `merch_items`
--
ALTER TABLE `merch_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `merch_orders`
--
ALTER TABLE `merch_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_holders`
--
ALTER TABLE `ticket_holders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`);

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_types` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `merch_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`merch_id`) REFERENCES `merch_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_holders`
--
ALTER TABLE `ticket_holders`
  ADD CONSTRAINT `ticket_holders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
