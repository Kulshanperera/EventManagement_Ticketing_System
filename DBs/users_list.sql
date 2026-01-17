-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 03:14 PM
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
-- Database: `users_list`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `booking_reference` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `booking_status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `cardholder_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `event_id`, `booking_reference`, `total_amount`, `payment_status`, `booking_status`, `cardholder_name`, `created_at`, `cancelled_at`) VALUES
(43, 32, 66, 'BK69692A5D40BCE', 1000.00, 'completed', 'cancelled', '1234', '2026-01-15 17:56:45', '2026-01-16 07:55:52'),
(44, 32, 66, 'BK6969E48468478', 1000.00, 'completed', 'cancelled', '1234', '2026-01-16 07:11:00', '2026-01-16 07:38:15'),
(45, 32, 66, 'BK6969E4EF32E81', 2000.00, 'completed', 'cancelled', '1234', '2026-01-16 07:12:47', '2026-01-16 07:32:50'),
(46, 32, 66, 'BK6969E59302AC5', 1000.00, 'completed', 'cancelled', '1234', '2026-01-16 07:15:31', '2026-01-16 07:31:47'),
(47, 32, 66, 'BK696A1D8C952F3', 1000.00, 'completed', 'confirmed', '1234', '2026-01-16 11:14:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_tickets`
--

CREATE TABLE `booking_tickets` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_tickets`
--

INSERT INTO `booking_tickets` (`id`, `booking_id`, `ticket_id`, `quantity`, `price`) VALUES
(33, 43, 66, 1, 1000.00),
(34, 44, 66, 1, 1000.00),
(35, 45, 66, 2, 1000.00),
(36, 46, 66, 1, 1000.00),
(37, 47, 66, 1, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `summary` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `event_time`, `location`, `category`, `description`, `summary`, `image`, `created_at`) VALUES
(66, 'Aluth Kalawak Festival 2026 Test', '2026-01-31', '20:30:00', 'PortCity', 'concert', 'What started as a nostalgic trip down memory What started as a nostalgic trip down memory What started as a nostalgic trip down memory What started as a nostalgic trip down memory', 'What started as a nostalgic trip down memory What started as a nostalgic trip down memory', '', '2026-01-15 11:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `event_id`, `ticket_name`, `price`, `quantity`, `status`) VALUES
(66, 66, 'Test', 1000.00, 9, 'available');

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
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `status` enum('active','deactivated') DEFAULT 'active',
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expiry` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `status`, `reset_token`, `reset_token_expiry`) VALUES
(32, '1234', '1234@gmail.com', '$2y$10$r0XolR1CEDEg9Y6Ehtj54O2W.qm4QYVui5/MYC8iA7P4h5D1jr.gC', '2026-01-15 12:49:37', 'customer', 'active', NULL, '2026-01-15 12:57:45'),
(39, 'admin', 'admin@user.com', '$2y$10$BU6qW0IVEDOsGflUoBGzBOsvuCMxGd1i7JbBH0BkX5pJXkUHIilGC', '2026-01-15 15:50:02', 'admin', 'active', NULL, '2026-01-15 15:50:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `booking_tickets`
--
ALTER TABLE `booking_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `booking_tickets`
--
ALTER TABLE `booking_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `booking_tickets`
--
ALTER TABLE `booking_tickets`
  ADD CONSTRAINT `booking_tickets_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_tickets_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
