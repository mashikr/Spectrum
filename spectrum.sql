-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 14, 2020 at 08:04 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spectrum`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentnotify`
--

CREATE TABLE `commentnotify` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `date-time` datetime NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commentnotify`
--

INSERT INTO `commentnotify` (`id`, `sender_id`, `post_id`, `comment_id`, `date-time`, `seen`) VALUES
(6, 43, 62, 81, '2020-10-05 17:45:05', 1),
(7, 43, 61, 82, '2020-10-05 17:45:14', 1),
(10, 39, 34, 88, '2020-10-06 18:37:25', 1),
(11, 39, 27, 89, '2020-10-09 15:44:33', 1),
(14, 40, 78, 95, '2020-10-12 13:05:07', 1),
(19, 40, 79, 102, '2020-10-14 05:43:59', 1),
(20, 40, 79, 103, '2020-10-14 05:44:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `source` varchar(255) NOT NULL,
  `date-time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `sender_id`, `content`, `type`, `source`, `date-time`) VALUES
(4, 37, 39, 'Aishee nice pic', 'text', '', '2020-10-04 15:53:56'),
(7, 62, 39, '', 'emoji', '/spectrum/public/emoji/emoji-13.png', '2020-10-04 16:05:37'),
(8, 37, 39, '', 'emoji', '/spectrum/public/emoji/emoji-8.png', '2020-10-04 16:05:53'),
(12, 62, 39, '', 'emoji', '/spectrum/public/emoji/emoji-8.png', '2020-10-04 17:24:53'),
(46, 35, 43, 'nice bro', 'text', '', '2020-10-04 22:33:04'),
(81, 62, 43, 'nice pic', 'text', '', '2020-10-05 17:45:05'),
(82, 61, 43, '', 'emoji', '/spectrum/public/emoji/emoji-15.png', '2020-10-05 17:45:14'),
(88, 34, 39, 'nice pic', 'text', '', '2020-10-06 18:37:25'),
(89, 27, 39, '', 'photo', 'Fatafati.png', '2020-10-09 15:44:33'),
(90, 61, 39, 'nice', 'text', '', '2020-10-11 18:25:05'),
(91, 61, 39, '', 'emoji', '/spectrum/public/emoji/emoji-6.png', '2020-10-11 18:25:11'),
(92, 61, 39, '', 'photo', 'Atto Shoja.png', '2020-10-11 18:25:29'),
(95, 78, 40, 'wow', 'text', '', '2020-10-12 13:05:07'),
(102, 79, 40, 'nice pic', 'text', '', '2020-10-14 05:43:59'),
(103, 79, 40, '', 'emoji', '/spectrum/public/emoji/emoji-6.png', '2020-10-14 05:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `friendreqnotify`
--

CREATE TABLE `friendreqnotify` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `date-time` datetime NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `friendreqnotify`
--

INSERT INTO `friendreqnotify` (`id`, `sender`, `receiver`, `date-time`, `seen`) VALUES
(7, 43, 39, '2020-10-06 07:21:27', 1),
(14, 40, 39, '2020-10-14 05:57:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `friend_request`
--

CREATE TABLE `friend_request` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `date-time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `sender_id`) VALUES
(8, 37, 43),
(9, 36, 43),
(10, 35, 43),
(12, 32, 43),
(13, 15, 43),
(60, 62, 43),
(66, 62, 44),
(67, 17, 39),
(69, 62, 39),
(84, 37, 39),
(85, 61, 39),
(104, 36, 39),
(125, 79, 43);

-- --------------------------------------------------------

--
-- Table structure for table `likesnotify`
--

CREATE TABLE `likesnotify` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `like_id` int(11) NOT NULL,
  `date-time` datetime NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `likesnotify`
--

INSERT INTO `likesnotify` (`id`, `sender_id`, `post_id`, `like_id`, `date-time`, `seen`) VALUES
(14, 43, 62, 60, '2020-10-06 12:33:28', 1),
(18, 44, 62, 66, '2020-10-08 20:10:05', 1),
(19, 39, 17, 67, '2020-10-09 18:44:56', 0),
(25, 39, 37, 84, '2020-10-09 20:33:20', 0),
(42, 39, 36, 104, '2020-10-12 11:44:29', 1),
(63, 43, 79, 125, '2020-10-14 20:37:19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(25) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `type`, `seen`, `time`) VALUES
(68, 39, 40, 'hlw bro', 'text', 1, '2020-10-09 15:44:55'),
(69, 39, 40, 'how are you?', 'text', 1, '2020-10-09 15:45:04'),
(70, 40, 39, 'hey bro', 'text', 1, '2020-10-09 15:45:40'),
(71, 40, 39, 'Alhamdulillah valo ', 'text', 1, '2020-10-09 15:45:47'),
(72, 40, 39, 'tui?', 'text', 1, '2020-10-09 15:45:49'),
(73, 40, 39, '/spectrum/public/emoji/emoji-6.png', 'emoji', 1, '2020-10-09 15:46:03'),
(74, 39, 40, 'amiO valo', 'text', 1, '2020-10-09 15:46:23'),
(75, 39, 40, '/spectrum/public/emoji/emoji-3.png', 'emoji', 1, '2020-10-09 15:46:26'),
(76, 43, 40, 'Hlw bro..', 'text', 0, '2020-10-09 15:50:22'),
(77, 39, 43, 'hlw bro...', 'text', 1, '2020-10-11 18:26:41'),
(78, 40, 39, 'hlw ', 'text', 1, '2020-10-12 12:15:07'),
(80, 39, 40, 'hlw', 'text', 1, '2020-10-12 12:17:59'),
(85, 40, 39, 'hlw', 'text', 1, '2020-10-14 06:08:23'),
(92, 40, 39, 'asif', 'text', 1, '2020-10-14 06:17:36'),
(93, 40, 39, 'asif', 'text', 1, '2020-10-14 06:17:48'),
(94, 40, 39, 'hoo', 'text', 1, '2020-10-14 06:18:13'),
(97, 40, 39, 'hlw', 'text', 1, '2020-10-14 06:20:52'),
(99, 40, 39, 'asif', 'text', 1, '2020-10-14 06:21:04'),
(107, 40, 39, 'hlw', 'text', 1, '2020-10-14 06:50:52'),
(108, 40, 39, 'aisf', 'text', 1, '2020-10-14 07:03:47'),
(120, 40, 39, 'hlw', 'text', 1, '2020-10-14 18:12:58'),
(122, 40, 39, 'ow', 'text', 1, '2020-10-14 18:16:41'),
(123, 40, 39, 'test', 'text', 1, '2020-10-14 18:18:57'),
(137, 39, 40, 'kiya', 'text', 1, '2020-10-14 19:03:06'),
(154, 40, 39, 'dfhab', 'text', 1, '2020-10-14 19:23:10'),
(165, 40, 39, '/spectrum/public/emoji/emoji-6.png', 'emoji', 1, '2020-10-14 20:04:23'),
(166, 39, 40, '/spectrum/public/emoji/emoji-7.png', 'emoji', 1, '2020-10-14 20:04:41'),
(167, 40, 39, '420 tk.jpg', 'image', 1, '2020-10-14 20:04:57'),
(168, 39, 40, 'Atto Shoja.png', 'image', 0, '2020-10-14 20:06:43'),
(169, 40, 39, 'cmd command.txt', 'txt', 1, '2020-10-14 20:07:01'),
(171, 43, 39, 'bor', 'text', 1, '2020-10-14 20:08:04'),
(179, 43, 39, 'bol', 'text', 1, '2020-10-14 20:26:39'),
(183, 43, 39, 'koire', 'text', 1, '2020-10-14 20:34:49'),
(184, 39, 43, 'ghore', 'text', 1, '2020-10-14 20:34:58'),
(186, 39, 43, '/spectrum/public/emoji/emoji-6.png', 'emoji', 1, '2020-10-14 20:35:27'),
(187, 43, 39, '/spectrum/public/emoji/emoji-6.png', 'emoji', 1, '2020-10-14 20:35:32'),
(189, 39, 43, 'haaa', 'text', 1, '2020-10-14 20:35:51'),
(190, 43, 39, 'link.txt', 'txt', 1, '2020-10-14 20:36:28'),
(193, 39, 43, 'kiya', 'text', 1, '2020-10-14 20:41:15'),
(194, 43, 39, 'ki koros', 'text', 1, '2020-10-14 20:41:21'),
(195, 39, 43, 'ei ito', 'text', 1, '2020-10-14 20:41:26'),
(197, 39, 43, 'huuu', 'text', 1, '2020-10-14 20:42:50'),
(198, 43, 39, 'ki', 'text', 1, '2020-10-14 20:42:53'),
(199, 39, 43, 'ho', 'text', 1, '2020-10-14 20:42:57'),
(200, 43, 39, 'brro', 'text', 1, '2020-10-14 20:43:37'),
(201, 39, 43, 'ki', 'text', 1, '2020-10-14 20:44:01'),
(202, 43, 39, 'ki', 'text', 1, '2020-10-14 20:44:13'),
(203, 39, 43, 'hu', 'text', 1, '2020-10-14 20:44:20'),
(204, 43, 39, 'ki', 'text', 1, '2020-10-14 20:44:47'),
(208, 43, 39, 'ashik', 'text', 1, '2020-10-14 20:51:38'),
(209, 43, 39, 'bro', 'text', 1, '2020-10-14 20:51:56'),
(210, 43, 39, 'hlw', 'text', 1, '2020-10-14 23:18:20'),
(211, 39, 43, 'kiare', 'text', 1, '2020-10-14 23:18:34'),
(212, 43, 39, 'koi tui', 'text', 1, '2020-10-14 23:18:41'),
(213, 39, 43, 'achi to', 'text', 1, '2020-10-14 23:18:48'),
(214, 39, 43, 'basay', 'text', 1, '2020-10-14 23:19:05'),
(215, 43, 39, 'oww', 'text', 1, '2020-10-14 23:19:15'),
(219, 43, 39, 'oww', 'text', 1, '2020-10-14 23:20:54'),
(221, 43, 39, 'oww', 'text', 1, '2020-10-14 23:24:40'),
(222, 43, 39, 'hlw', 'text', 1, '2020-10-14 23:24:54'),
(223, 39, 43, 'kiya', 'text', 1, '2020-10-14 23:25:02'),
(224, 43, 39, 'kireeeh', 'text', 1, '2020-10-14 23:26:19'),
(225, 39, 43, 'kiya', 'text', 1, '2020-10-14 23:26:27'),
(226, 43, 39, 'kiyaros', 'text', 1, '2020-10-14 23:26:35'),
(227, 39, 43, 'nachi', 'text', 1, '2020-10-14 23:26:40'),
(228, 43, 39, 'fbdfzb', 'text', 1, '2020-10-14 23:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `privacy` varchar(10) NOT NULL,
  `date-time` datetime NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) NOT NULL DEFAULT 0,
  `comments` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `author_id`, `content`, `type`, `file_name`, `privacy`, `date-time`, `likes`, `comments`) VALUES
(2, 39, 'Hi im new at spectrum', 'post', '', 'Public', '2020-09-27 22:25:48', 0, 0),
(8, 39, 'hlw friends', 'post', '', 'Public', '2020-09-28 08:01:06', 0, 0),
(9, 40, 'Hello guys......', 'post', '', 'Friends', '2020-09-28 11:13:24', 0, 0),
(10, 40, 'Hey people, learn CMD command', 'txt', 'cmd command.txt', 'Public', '2020-09-28 11:15:03', 0, 0),
(11, 39, 'Sei tmi keno eto ocena hole..', 'video', '_Sei_Tumi_Keno_Eto_Ochena_Hole__subtitled_by_Arindam_Dutta.mp4', 'Public', '2020-09-28 15:46:46', 0, 0),
(12, 42, 'Hi i\'m aishee. i\'m new at spectrum', 'post', '', 'Friends', '2020-09-28 20:28:26', 0, 0),
(13, 43, 'Hi i\'m kmrunzaman', 'post', '', 'Public', '2020-09-28 20:29:08', 0, 0),
(14, 43, 'Hi im kamruzzaman, I\'m a student', 'post', '', 'Friends', '2020-09-28 20:29:27', 0, 0),
(15, 43, 'ami forhad', 'post', '', 'Private', '2020-09-28 20:30:02', 1, 0),
(16, 42, 'ami aishee', 'post', '', 'Private', '2020-09-28 20:30:35', 0, 0),
(17, 42, 'i\'m aishee', 'post', '', 'Public', '2020-09-28 20:30:45', 1, 0),
(27, 43, '', 'cover_pic', 'IMG_3833.JPG', 'Public', '2020-09-30 11:52:50', 0, 1),
(32, 39, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque excepturi asperiores ipsam provident. Maiores, qui quos! Maiores sapiente ex quisquam nulla natus explicabo, ipsum hic voluptates deserunt cumque nostrum corrupti.', 'post', '', 'Public', '2020-09-30 22:08:32', 1, 0),
(34, 40, '', 'profile_pic', 'asif.PNG', 'Public', '2020-10-01 15:46:05', 1, 1),
(35, 40, '', 'cover_pic', 'IMG_0668.JPG', 'Public', '2020-10-01 15:47:02', 1, 1),
(36, 43, '', 'profile_pic', 'forhad.PNG', 'Public', '2020-10-01 15:47:47', 2, 0),
(37, 42, '', 'profile_pic', 'aishee.PNG', 'Public', '2020-10-01 15:50:04', 7, 2),
(61, 39, '', 'photo', 'IMG_6736.JPG', 'Public', '2020-10-01 22:23:41', 1, 4),
(62, 39, 'Hello CSTE ....', 'cover_pic', 'IMG_1583.JPG', 'Public', '2020-10-01 22:24:06', 3, 3),
(78, 39, '', 'photo', 'mypic2.PNG', 'Public', '2020-10-11 18:28:30', 0, 1),
(79, 39, '', 'profile_pic', 'IMG_511345.JPG', 'Public', '2020-10-11 18:28:42', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `remember_logins`
--

CREATE TABLE `remember_logins` (
  `token` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(55) NOT NULL,
  `expired_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `remember_logins`
--

INSERT INTO `remember_logins` (`token`, `user_id`, `email`, `expired_at`) VALUES
('8aac9f4733c09387d2ecec4dcb76d682ccb15fa70937f42a5de9669d7f6b7ac1', 43, 'forhad@gmail.com', '2020-11-13 15:07:27'),
('9b92db3d087a1697d444c5ec0f713c19c9e2dbd1ac27c2a9c617669265a081a5', 39, 'ashik@gmail.com', '2020-11-11 03:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` varchar(15) NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `password_reset_hash` varchar(255) NOT NULL,
  `password_reset_expire` timestamp NULL DEFAULT NULL,
  `account_active_hash` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `friends` text DEFAULT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `cover_pic` varchar(255) NOT NULL,
  `works_at` varchar(255) NOT NULL,
  `studied` varchar(255) NOT NULL,
  `live_in` varchar(255) NOT NULL,
  `home_town` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `dob`, `gender`, `password`, `create_at`, `password_reset_hash`, `password_reset_expire`, `account_active_hash`, `active`, `friends`, `profile_pic`, `cover_pic`, `works_at`, `studied`, `live_in`, `home_town`, `phone`) VALUES
(39, 'Ashikur', 'Rahman', 'ashik@gmail.com', '08 Aug, 1998', 'Male', '$2y$10$h80XPLlT7MLqvMKb2aBce.M9a7j/Gly7rSlXbmycR8iEnAlti7HOu', '2020-09-27 10:21:25', '', NULL, '', 1, '43,40', 'IMG_511345.JPG', 'IMG_1583.JPG', 'Class Representative of 12th Batch at Department of Computer Science and Telecommunication Engineering, NSTU', 'Computer Science & Telecommunication Engineering at Noakhali Science and Technology University', 'Noakhali, Chittagong, Bangladesh', 'Laksam, Comilla', '01712327242'),
(40, 'Asif', 'Mahmud', 'asif@gmail.com', '09 Feb, 2000', 'Male', '$2y$10$uufTJSYBSpKto1WgEo82Pe7rwXlOoB5sLWr9aSOpwV9er5CxJc5a2', '2020-09-27 10:22:33', '', NULL, '', 1, '39', 'asif.PNG', 'IMG_0668.JPG', '', '', '', '', ''),
(42, 'Aishee', 'Progga', 'aishee@yahoo.com', '12 Jul, 2000', 'Female', '$2y$10$khkgNfjawc8dPky.O1l6o.x6eBpAcBFrduuAPo0aStUDAeDcpdNdW', '2020-09-27 10:36:35', '', NULL, '', 1, '', 'aishee.PNG', 'dark-bg.jpg', '', '', '', '', ''),
(43, 'Kamruzzaman', 'Forhad', 'forhad@gmail.com', '06 Feb, 2002', 'Male', '$2y$10$N4Uy8UcIkJWcPCWGvzOMwO8i4lB.XzTb/TSKqgtfeE8tOiu8CFNHC', '2020-09-28 08:51:44', '', NULL, '', 1, '39', 'forhad.PNG', 'IMG_3833.JPG', 'Student', '', '', '', ''),
(44, 'Nahid', 'Aslam', 'nahid@yahoo.com', '15 Feb, 2000', 'Male', '$2y$10$K29IMEnsTqVLhsfc3ihH/.ICH8Y.RDEkQx.CablWnOUBILAGSjeBu', '2020-10-08 20:09:06', '', NULL, '', 1, NULL, 'male-user.png', 'dark-bg.jpg', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentnotify`
--
ALTER TABLE `commentnotify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `friendreqnotify`
--
ALTER TABLE `friendreqnotify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_request`
--
ALTER TABLE `friend_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `likesnotify`
--
ALTER TABLE `likesnotify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `like_id` (`like_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remember_logins`
--
ALTER TABLE `remember_logins`
  ADD PRIMARY KEY (`token`);

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
-- AUTO_INCREMENT for table `commentnotify`
--
ALTER TABLE `commentnotify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `friendreqnotify`
--
ALTER TABLE `friendreqnotify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `friend_request`
--
ALTER TABLE `friend_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `likesnotify`
--
ALTER TABLE `likesnotify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentnotify`
--
ALTER TABLE `commentnotify`
  ADD CONSTRAINT `commentnotify_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likesnotify`
--
ALTER TABLE `likesnotify`
  ADD CONSTRAINT `likesnotify_ibfk_1` FOREIGN KEY (`like_id`) REFERENCES `likes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
