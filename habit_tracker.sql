-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 02:48 AM
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
-- Database: `habit_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `habits`
--

CREATE TABLE `habits` (
  `habit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `habit` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habits`
--

INSERT INTO `habits` (`habit_id`, `user_id`, `habit`, `start_date`, `end_date`, `category`, `created_at`, `updated_at`) VALUES
(1, 5, 'Yoga exercise with ma Homies', '2025-04-07', '2025-04-09', 'yoga', '2025-04-06 00:47:48', '2025-04-06 00:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `progress` int(11) NOT NULL DEFAULT 0,
  `status` enum('incomplete','complete') DEFAULT 'incomplete',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `user_id`, `description`, `progress`, `status`, `created_at`, `updated_at`) VALUES
(6, 5, 'Workout for 20 Min', 12, 'incomplete', '2025-04-05 23:53:56', '2025-04-05 23:54:18'),
(7, 5, 'Study for 10 Min', 19, 'incomplete', '2025-04-05 23:53:56', '2025-04-05 23:57:41'),
(8, 5, 'Read for 10 Min', 11, 'incomplete', '2025-04-05 23:53:56', '2025-04-05 23:57:41'),
(9, 5, 'Do Plank 2 Min', 15, 'incomplete', '2025-04-05 23:53:56', '2025-04-05 23:57:40'),
(10, 5, 'Meditate for 10 minutes', 26, 'incomplete', '2025-04-05 23:53:56', '2025-04-05 23:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `dob`, `password`, `created_at`) VALUES
(1, 'Gabby', 'gaspar11@gmail.com', '2001-04-24', 'Gaspar11', '2025-04-04 03:50:48'),
(2, 'Gasssy', 'gatsby11@gmail.com', '2001-04-24', '$2y$10$Ip3unEEK20C5lbKztAhx/OXZfBN6Xjgf1iB.Ta/N7Yq1xsXf2qnX6', '2025-04-05 04:05:31'),
(3, 'Eddie', 'Eddie@gmail.com', '2001-04-24', '$2y$10$k1O2B.ZIRNfJ4eiUgmRaXO/wecstd6xmMNhBCo70PXaHZIdSSqnUu', '2025-04-05 20:10:39'),
(5, 'Gatsby', 'Gatsby@gmail.com', '2000-04-24', '$2y$10$a3TvNMhIrJlHRYJXPXRvJek4RJNzyH.cdLAuaUyvHbwfwgovNbMSy', '2025-04-05 23:53:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `habits`
--
ALTER TABLE `habits`
  ADD PRIMARY KEY (`habit_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habits`
--
ALTER TABLE `habits`
  MODIFY `habit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `habits`
--
ALTER TABLE `habits`
  ADD CONSTRAINT `habits_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
