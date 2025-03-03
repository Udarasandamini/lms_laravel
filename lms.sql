-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2024 at 10:30 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `author_name`, `status`, `created_at`) VALUES
(1, 'Cheryl jack MD', 'Active', '2024-10-08 06:12:15'),
(2, 'H.D.Gordon', 'Active', '2024-10-08 06:18:16'),
(3, 'Kamal Gawrawa', 'Active', '2024-10-08 06:19:44'),
(4, 'Robert Kin', 'Active', '2024-10-08 06:20:03'),
(5, 'Jhon Doe', 'Active', '2024-10-08 06:20:24'),
(6, 'Montgomery Toylar', 'Active', '2024-10-08 06:20:56'),
(7, 'Kumarathunga Munidhasa', 'Active', '2024-10-08 06:21:17'),
(8, 'Anisur Rahman Shahin', 'Active', '2024-10-08 06:21:35'),
(9, 'Arun Gupta', 'Active', '2024-10-08 06:21:58'),
(10, 'Hatim Tai', 'Active', '2024-10-08 06:23:54'),
(11, 'Ishrat shila', 'Active', '2024-10-08 06:24:17'),
(12, 'Nandana Weerarathna', 'Active', '2024-10-08 08:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `book_image` varchar(200) NOT NULL,
  `book_author` varchar(100) NOT NULL,
  `book_quantity` int(10) NOT NULL,
  `book_avilable` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `librian_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_name`, `book_image`, `book_author`, `book_quantity`, `book_avilable`, `date`, `librian_name`) VALUES
(29, 'The King of Drugs', '4017book1.jpg', 'Jhon', 10, 10, '2024-10-11 11:09:09', 'sada'),
(30, 'The Design Hustile', '5615book3.jpg', 'Jhon Doe', 10, 10, '2024-09-18 07:20:01', 'sada'),
(31, 'Red Planet', '980book5.jpg', 'Jhon Doe', 10, 10, '2024-10-14 06:26:52', 'sada'),
(32, 'English Grammar', '6654book6.jpg', 'Jhon Doe', 10, 10, '2024-09-18 07:18:51', 'sada'),
(34, 'Mental English', '563book7.jpg', 'Jhon Doe', 10, 10, '2024-09-18 06:28:01', 'sada'),
(35, 'Coding Kids', '4788book14.jpg', 'Jhon Doe', 10, 11, '2024-10-14 07:55:53', 'sada'),
(37, 'Html and CSS', '3923book12.jpg', 'Anisur Rahman Shahin', 10, 10, '2024-10-14 06:26:10', 'sada'),
(38, 'Codding and Questions', '7415book10.jpg', 'Robert Kin', 10, 10, '2024-09-18 07:15:15', 'sada'),
(39, 'Happy Secure', '7010book11.jpg', 'Montgomery Toylar', 10, 11, '2024-10-14 07:51:16', 'naveen'),
(40, 'Java Developers', '8713book13.gif', 'Arun Gupta', 10, 11, '2024-10-14 07:53:14', 'naveen'),
(41, 'Magic Knowledge', '158photo-book-1318702__340.webp', 'Hatim Tai', 20, 20, '2024-10-14 07:52:01', 'naveen'),
(43, '7 Secret of Health ', '6191book18.jpg', 'Cheryl jack MD', 10, 10, '2024-10-14 07:40:15', 'sada'),
(44, 'Physics', '6755book19.jpg', 'Ishrat shila', 10, 10, '2024-10-14 07:58:50', 'naveen'),
(45, 'Blood Warriorm', '6657book20.jpg', 'H.D.Gordon', 10, 10, '2024-10-14 06:25:25', 'sada'),
(47, 'Hathpana', '153hathpana.jpg', 'Kumarathunga Munidhasa', 10, 10, '2024-10-14 06:25:59', 'naveen'),
(48, 'Book Title Here', '8154910book4.jpg', 'Kamal Gawrawa', 10, 13, '2024-10-14 07:38:00', 'sada'),
(49, 'Batalandata Gini Thebuwemu', '2305bta.jpg', 'Nandana Weerarathna', 10, 11, '2024-10-14 07:39:01', 'naveen');

-- --------------------------------------------------------

--
-- Table structure for table `issue_book`
--

CREATE TABLE `issue_book` (
  `id` int(5) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `book_id` int(5) NOT NULL,
  `lib_name` varchar(50) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_book`
--

INSERT INTO `issue_book` (`id`, `student_id`, `book_id`, `lib_name`, `issue_date`, `due_date`, `return_date`, `fine`, `created_at`) VALUES
(46, '2', 35, 'naveen', '2024-10-11', '2024-10-25', '2024-10-26', 1.00, '2024-10-11 10:49:54'),
(47, '1', 29, 'naveen', '2024-10-11', '2024-10-25', '2024-10-28', 3.04, '2024-10-11 10:50:37'),
(48, '2', 47, 'naveen', '2024-10-11', '2024-10-25', '2024-10-30', 5.04, '2024-10-11 10:51:46'),
(49, '1', 49, 'sada', '2024-10-14', '2024-10-28', '2024-10-30', 2.00, '2024-10-14 06:09:15'),
(50, '1', 48, 'sada', '2024-10-14', '2024-10-28', '2024-10-31', 3.00, '2024-10-14 06:48:36'),
(51, '1', 43, 'sada', '2024-10-14', '2024-10-28', '2024-11-01', 4.00, '2024-10-14 07:39:53'),
(52, '1', 39, 'sada', '2024-10-14', '2024-10-28', '2024-10-15', 0.00, '2024-10-14 07:41:15'),
(53, '1', 41, 'sada', '2024-10-14', '2024-10-28', '2024-10-31', 3.00, '2024-10-14 07:51:41'),
(54, '1', 40, 'sada', '2024-10-14', '2024-10-28', '2024-10-15', 0.00, '2024-10-14 07:52:28'),
(55, '1', 44, 'sada', '2024-10-14', '2024-10-28', '2024-10-31', 3.00, '2024-10-14 07:58:31');

-- --------------------------------------------------------

--
-- Table structure for table `libraian`
--

CREATE TABLE `libraian` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(150) DEFAULT NULL,
  `city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `libraian`
--

INSERT INTO `libraian` (`id`, `name`, `email`, `username`, `tag`, `password`, `phone`, `date`, `image`, `city`) VALUES
(1, 'Udara Sandamini', 'sandaminiudara@gmail.com', 'sada', 'Librarian', '111111', '0766702590', '2024-08-27 05:58:41', '66d00db475c2b.jpg', 'Puttalam.'),
(2, 'Naveen Wijayabandara', 'naveen@gmail.com', 'naveen', 'Librarian', '111111', '0756952923', '2024-08-29 10:53:00', '66d0534a83764.jpg', 'kandy');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `librarian_name` varchar(100) NOT NULL,
  `librarian_message` text NOT NULL,
  `student_reply` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `student_id`, `student_name`, `librarian_name`, `librarian_message`, `student_reply`, `timestamp`) VALUES
(143, '200169201128', 'Udara Sandamini', 'Naveen Wijayabandara', 'rf', 'uu', '2024-10-09 06:46:47'),
(159, '200169201128', 'Udara Sandamini', 'Naveen Wijayabandara', ' b g', 'bhbh', '2024-10-09 08:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `reply_message` text DEFAULT NULL,
  `student_reply` text DEFAULT NULL,
  `librarian_followup_reply` text DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `librarian_message` text DEFAULT NULL,
  `student_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `uid`, `message`, `reply_message`, `student_reply`, `librarian_followup_reply`, `name`, `librarian_message`, `student_message`) VALUES
(1, '1', 'hh', 'hh', ' kk k', 'njjx', NULL, NULL, NULL),
(2, '1', ' mm ', 'mknjnj', '   jnjnjnj', 'kkk', NULL, NULL, NULL),
(3, '1', 'hi', 'njfnf', 'bhf', 'nfv', NULL, NULL, NULL),
(4, '1', 'testingg', 'test', 'gvg', 'bhh', NULL, NULL, NULL),
(5, '1', 'hh', 'bh', 'hv', 'iiiii', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_book`
--

CREATE TABLE `request_book` (
  `id` int(5) NOT NULL,
  `student_name` varchar(50) NOT NULL,
  `student_id` varchar(25) NOT NULL,
  `book_id` int(5) NOT NULL,
  `book_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `batch` tinyint(5) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `blood_grp` varchar(5) NOT NULL,
  `gpa` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `username`, `email`, `uid`, `batch`, `pass`, `phone`, `address`, `image`, `status`, `date`, `blood_grp`, `gpa`) VALUES
(1, 'Udara Sandamini', 'udara', 'sandaminiudara@gmail.com', '200169201128', 127, '111111', '0766702590', 'no.169,kurunegala road, puttalam.', '4050udara.jpg', 1, '2024-09-27 11:57:50', '', ''),
(2, 'Pushpa Kumari', 'pushpa', 'pushpa@gmail.com', '196767502212', 127, '111111', '0718009085', 'no.169,kurunegala road, puttalam.', '9115pushpa.JPG', 1, '2024-09-27 11:59:30', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_book`
--
ALTER TABLE `issue_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `libraian`
--
ALTER TABLE `libraian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_book`
--
ALTER TABLE `request_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `issue_book`
--
ALTER TABLE `issue_book`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `libraian`
--
ALTER TABLE `libraian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `request_book`
--
ALTER TABLE `request_book`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
