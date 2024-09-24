-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 24, 2024 at 04:52 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quan_ly_diem_danh`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `session_id` int NOT NULL,
  `check_in_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('present','late','absent') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `session_id`, `check_in_time`, `status`) VALUES
(1, 1, 1, '2024-09-01 02:00:00', 'present'),
(2, 2, 1, '2024-09-01 02:15:00', 'late'),
(3, 3, 1, '2024-09-01 02:05:00', 'present'),
(4, 4, 1, '2024-09-01 02:20:00', 'late'),
(5, 5, 1, '2024-09-01 02:00:00', 'present');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_name` varchar(100) NOT NULL,
  `teacher_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `teacher_id`, `created_at`) VALUES
(1, 'Lập trình Web', 1, '2024-09-24 04:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `course_id` int NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enrolled_at`) VALUES
(1, 1, 1, '2024-09-24 04:17:41'),
(2, 2, 1, '2024-09-24 04:17:41'),
(3, 3, 1, '2024-09-24 04:17:41'),
(4, 4, 1, '2024-09-24 04:17:41'),
(5, 5, 1, '2024-09-24 04:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `session_date` date NOT NULL,
  `session_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `course_id`, `session_date`, `session_time`, `created_at`) VALUES
(1, 1, '2024-09-01', '09:00:00', '2024-09-24 04:17:41'),
(2, 1, '2024-09-08', '09:00:00', '2024-09-24 04:17:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('teacher','student') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`, `class`, `gender`, `plain_password`) VALUES
(70, '12345', '$2y$10$WIV46OM6o533GW./ikmfXeQenwyrrfqYwKx/6XoqNOMDLI4LObgem', 'nguyendinhv2n2004@hotmail.com', '', 'student', '2024-09-24 03:47:46', '45C', 'Nam', '1'),
(63, 'admin', '$2y$10$5lKqr6Im1sisufpsBlP.hOUKb.iqVBxGi89gY1Qcu2fUOG9k1zMIi', 'dvan.ceo@hotmail.com', '', 'teacher', '2024-09-24 03:13:36', NULL, NULL, '1'),
(64, 'hiepsi395', '$2y$10$yxp/8FGIKWD7fAygfoaSluSmIHnJrWUUlLw9HM7WlSYnvPyIAdxie', 'tnxk.test@hotmail.com', '', 'student', '2024-09-24 03:15:30', '47B', 'Nam', '1'),
(68, '1234', '$2y$10$tAj/.vXadmJK75xnXEAAYup.BPeuSTcUam1AKcmFIHT3sF70Wa7ri', 'nguyendinhvan2004@hotmail.com', '', 'student', '2024-09-24 03:26:54', '45C', 'Nam', NULL),
(69, '455111211', '$2y$10$sbXPhzvMBEzJWvKUF.TFc.qllHw/K8xEOwUGgF9a/77PGfRjhUTHW', 'dvan3@gmail.com', '', 'student', '2024-09-24 03:46:39', '45B', 'Nam', 'default_password'),
(71, '111', '$2y$10$aRezE1dRk6Mz/sQoMmzvEuEvX2RLh48rRkfzXz0VGGh7kIm281yF.', 'dvan.@hotmail.com', '', 'student', '2024-09-24 03:54:59', '45C', 'Nam', '1'),
(1, '45510502222', '$2y$10$kR8Y0xjX8zzpIv/fqeIw5OlACDBloIB0T/NseHG3ssuAzMf8Y.BBq', 'sv001@example.com', 'Nguyen Van A', 'student', '2024-09-24 04:17:41', '45B', 'Nam', ''),
(2, 'sv002', '$2y$10$kR8Y0xjX8zzpIv/fqeIw5OlACDBloIB0T/NseHG3ssuAzMf8Y.BBq', 'sv002@example.com', 'Le Thi B', 'student', '2024-09-24 04:17:41', 'IT02', 'Nữ', '1'),
(3, 'sv003', '$2y$10$kR8Y0xjX8zzpIv/fqeIw5OlACDBloIB0T/NseHG3ssuAzMf8Y.BBq', 'sv003@example.com', 'Tran Van C', 'student', '2024-09-24 04:17:41', 'IT03', 'Nam', '1'),
(4, 'sv004', '$2y$10$kR8Y0xjX8zzpIv/fqeIw5OlACDBloIB0T/NseHG3ssuAzMf8Y.BBq', 'sv004@example.com', 'Hoang Thi D', 'student', '2024-09-24 04:17:41', 'IT01', 'Nữ', '1'),
(5, 'sv005', '$2y$10$kR8Y0xjX8zzpIv/fqeIw5OlACDBloIB0T/NseHG3ssuAzMf8Y.BBq', 'sv005@example.com', 'Pham Van E', 'student', '2024-09-24 04:17:41', 'IT02', 'Nam', '1'),
(73, 'huan', '$2y$10$HS9/K7k3LMAEc.IL1HrZ9uMSH/ZRYdXmtr6k6l2Le4o9r1qu9R25m', 'tnxk.tdsdest@hotmail.com', '', 'student', '2024-09-24 04:41:03', '45C', 'Nam', '1'),
(75, 'dat', '$2y$10$4GvVUQL2fz67R6k2TDKxZetruDQsKzV2Yw40S5n5se7e7IvERah9a', 'ndv05092004@gmail.com', '', 'student', '2024-09-24 04:50:17', '47B', 'Nam', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
