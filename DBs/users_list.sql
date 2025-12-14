-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2025 at 03:25 PM
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
(9, 'Innovate Forward 2025: The Future of Digital Transformation', '2025-12-18', '14:00:00', 'Gamphaha', 'conference', 'Join industry leaders, entrepreneurs, and technology professionals for a full-day conference exploring the next wave of digital innovation. From AI advancements and automation to cybersecurity strategies and data-driven decision-making, this event provides in-depth sessions, panel discussions, and hands-on workshops. Attendees will gain practical insights, network with experts, and discover emerging technologies reshaping the global landscape.', 'A tech-focused conference covering AI, automation, and digital transformation with workshops, expert speakers, and networking opportunities.', 'Eventimages/1765085834_2025-11-12 21 11 13.png', '2025-12-07 05:37:14'),
(10, 'Tests', '2025-12-18', '11:13:00', 'colombo', 'sports', 'Tests TestsTestsTestsTestsTests', '', 'Eventimages/1765086207_2025-11-12 21 11 13.png', '2025-12-07 05:43:27'),
(15, 't', '2025-12-04', '06:13:00', 'colombo', 'concert', 't', 'ttttttttttttttttttttttrtgrtgrgrgrtg', '', '2025-12-07 08:40:19'),
(16, 't', '2025-12-04', '06:13:00', 'colombo', 'concert', 't', 'ttttttttttttttttttttttrtgrtgrgrgrtg', '', '2025-12-07 08:42:40'),
(18, 't', '2025-12-04', '06:13:00', 'colombo', 'concert', 't', 'ttttttttttttttttttttttrtgrtgrgrgrtg', '', '2025-12-07 08:45:19'),
(19, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 08:47:05'),
(20, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 08:48:21'),
(21, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 08:52:27'),
(22, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 08:52:29'),
(24, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:05:07'),
(28, '', '0000-00-00', '00:00:00', '', '', '', 'ghjghjghjgj', '', '2025-12-07 09:56:56'),
(29, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:00'),
(30, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:09'),
(31, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:11'),
(32, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:25'),
(33, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:32'),
(34, '', '0000-00-00', '00:00:00', '', '', '', '', '', '2025-12-07 09:57:54'),
(37, 't', '2025-12-14', '19:29:00', 'colombo', 'sports', 'Join industry leaders, entrepreneurs, and technology professionals for a full-day conference exploring the next wave of digital innovation. From AI advancements and automation to cybersecurity strategies and data-driven decision-making, this event provides in-depth sessions, panel discussions, and hands-on workshops. Attendees will gain practical insights, network with experts, and discover emerging technologies reshaping the global landscape.', '', 'Eventimages/1765108694_2025-11-12 21 11 13.png', '2025-12-07 11:58:14'),
(38, 'sdsdfsf12', '2025-12-13', '19:32:00', 'Gamphaha', 'concert', 'sssssssss Shows current count\r\n✔ Updates as user types\r\n✔ Turns red when close to limit\r\n✔ Prevents typing beyond limit due to maxlength=\"\"sdfsfsfsdfsdfsfdsf sfdsf sdfsf dfsdfsfs fddsffs sdfsdfs sdfsdfsfsf', 'Shows current count\r\n✔ Updates as user types\r\n', 'uploads/1765122778_thumbnail_12_4a5036c97330b0f5.png', '2025-12-07 12:00:03'),
(39, 't', '2025-12-07', '12:40:00', 'colombo', 'concert', ' testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', '', 'uploads/1765121959_thumbnail_12_4a5036c97330b0f5.png', '2025-12-07 15:06:14'),
(40, 'test', '2025-12-12', '12:30:00', 'Gamphaha', 'concert', 'testtesttesttesttest testtesttesttesttest testtesttesttesttest testtesttesttesttest testtesttesttesttest', 'testtesttesttesttest', '', '2025-12-07 15:58:13'),
(41, '1', '2025-12-12', '22:12:00', 'colombo', 'concert', 'test test test test test test test testtest test test testtest test test testtest test test testtest test test testtest test test testtest test test test', 'test', '', '2025-12-12 16:42:05');

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
(10, 39, 'standard', 200.00, 5, 'available'),
(11, 39, 'standard', 200.00, 5, 'available'),
(12, 38, 't', 1000.00, 2, 'available'),
(13, 38, 't', 1000.00, 2, 'available'),
(18, 40, 'standard', 1000.00, 2, 'available'),
(19, 40, 'test', 500.00, 4, 'available'),
(20, 40, 'test2', 100.00, 2, 'sold-out'),
(21, 40, 'test12', 50.00, 1, 'available'),
(22, 40, 'test12', 50.00, 1, 'available');

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
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(2, '123', 'abc@g.com', '$2y$10$kIw6wzxM5gsZXE1zvBgIQeSagWtei3IDCo1h/TB4RxFALeNNi6S86', '2025-12-06 11:10:33', 'customer'),
(3, 'test', 'abc@gmail.com', '$2y$10$ObJE7E8VRpE0tz1TZo/3YeGYTd.3oPIEQ58TuDq2FBKiNONviTq6y', '2025-12-12 08:15:09', 'customer'),
(4, 'test1', 'abc1@gmail.com', '$2y$10$9q8Ph6qvC7WYpMcvGK9TjexMAJOP3CYtjIVZQ8f2VwcdaGy2q6qya', '2025-12-12 08:18:40', 'customer'),
(8, '123123', 'aabc11@gmail.com', '$2y$10$v3PluxMvtcIaVI0G9jwnq.goEQTPxUj4ROVCDg6vltf/PRKnpM0Fa', '2025-12-12 09:01:07', 'customer'),
(17, 'admin', 'admin@user.com', '$2y$10$F7SkH8LVB12OI3tzyZ6ewutfxOYf2nNLAje.Lam6EXVsEa2k02B9K', '2025-12-12 16:40:23', 'admin');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
