-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Apr 14, 2025 at 12:34 PM
-- Server version: 11.6.2-MariaDB-ubu2404
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment1`
--

-- --------------------------------------------------------

--
-- Table structure for table `auction`
--

CREATE TABLE `auction` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categoryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `endDate` datetime NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `auction`
--

INSERT INTO `auction` (`id`, `title`, `description`, `categoryId`, `userId`, `endDate`, `image`, `price`) VALUES
(12, 'Land Rover Range Rover', 'A luxury 4x4 with great off-road capability and a premium interior', 15, 2, '2025-05-31 00:00:00', NULL, 150000.00),
(13, 'Toyota Land Cruiser', 'The ultimate 4x4 with rugged durability', 15, 2, '2025-06-01 00:00:00', NULL, NULL),
(14, 'Porsche 718 Cayman', 'a two seater car with mid engine layout', 20, 2, '2025-04-21 00:00:00', NULL, NULL),
(15, '2021 Chevrolet Corvette', 'An American icon, the Corvette offers blistering performance and stunning design', 20, 2, '2025-04-29 00:00:00', NULL, NULL),
(16, 'Ferrari 488 GTB', 'A high-performance sports car with a turbocharged V8 engine and sharp handling.', 16, 2, '2025-05-08 00:00:00', NULL, NULL),
(17, 'McLaren 570S', 'A supercar with impressive performance, sleek design, and high-tech features.', 16, 2, '2025-04-26 00:00:00', NULL, NULL),
(18, 'Jeep Wrangler', 'Iconic and tough, the Jeep Wrangler is the perfect off-road vehicle for adventurous spirits', 19, 1, '2025-07-01 00:00:00', NULL, NULL),
(19, '2020 Mercedes-Benz G-Class', '2020 Mercedes-Benz G-Class', 19, 1, '2025-06-01 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `auctionId` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `bidAmount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`id`, `userId`, `auctionId`, `date`, `bidAmount`) VALUES
(1, 7, 19, '2025-04-13 17:23:41', 800000.00),
(2, 2, 19, '2025-04-13 17:47:14', NULL),
(3, 2, 18, '2025-04-13 17:47:44', NULL),
(4, 5, 17, '2025-04-13 17:48:43', NULL),
(5, 2, 16, '2025-04-13 18:37:39', 900000.00),
(6, 2, 19, '2025-04-14 08:55:12', 90000.00),
(7, 2, 19, '2025-04-14 08:55:27', 700000.00),
(8, 5, 15, '2025-04-14 09:31:07', 8709000.00),
(9, 5, 14, '2025-04-14 09:31:55', 4567000.00),
(10, 7, 12, '2025-04-14 09:34:36', 4500000.00),
(11, 7, 13, '2025-04-14 09:39:39', 335500.00),
(12, 7, 17, '2025-04-14 09:39:53', 129000.00),
(13, 7, 18, '2025-04-14 09:40:20', 129000.00);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(15, '4x4'),
(16, 'Sports'),
(18, 'All'),
(19, 'Tesla'),
(20, 'Porsche');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `reviewText` text NOT NULL,
  `reviewerName` varchar(255) NOT NULL,
  `reviewerEmail` varchar(255) NOT NULL,
  `auctionId` int(11) DEFAULT NULL,
  `reviewerId` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT current_timestamp(),
  `userId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `reviewText`, `reviewerName`, `reviewerEmail`, `auctionId`, `reviewerId`, `date`, `userId`) VALUES
(1, 'very nice car', 'Guest', 'guest@carbuy.com', 19, 2, '2025-04-13 17:18:46', NULL),
(2, 'niceee', 'Guest', 'guest@carbuy.com', 19, 2, '2025-04-13 18:27:37', NULL),
(3, 'wow!', 'Guest', 'guest@carbuy.com', 15, 2, '2025-04-13 18:37:21', NULL),
(4, 'i like this car', 'Guest', 'guest@carbuy.com', 19, 5, '2025-04-14 08:30:59', NULL),
(5, 'wowww', 'Guest', 'guest@carbuy.com', 19, 2, '2025-04-14 08:50:18', NULL),
(6, 'very relaible!!', 'Guest', 'guest@carbuy.com', 14, 5, '2025-04-14 09:32:25', NULL),
(7, 'good car!!', 'Guest', 'guest@carbuy.com', 12, 7, '2025-04-14 09:34:23', NULL),
(8, 'i will buy this!', 'Guest', 'guest@carbuy.com', 13, 7, '2025-04-14 09:39:22', NULL),
(9, 'this is nice', 'Guest', 'guest@carbuy.com', 18, 7, '2025-04-14 09:40:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `role`) VALUES
(1, 'taragrg78@gmail.com', '$2y$12$.N8Z9LzIfYjRWu2tgMU9GeSuNNpr.9RMtlSxv52Ks6.GUD1EnME/a', 'Tara', 'admin'),
(2, 'liza.gurung18@gmail.com', '$2y$12$gPr7AYw0xfX8C5VO.45hJO6z8wl11pLhGtk7Rigfm9Z6RXFU8kERq', 'liza', 'user'),
(3, 'pras@gmail.com', '$2y$12$W2Fy6G6bATacmbX6R8eJH.eN3B86WuNtik11DW6KSq37tI9Elnoji', 'prashna', 'user'),
(5, 'meg90@gmail.com', '$2y$12$lUbHOLIVUAZw/OewlQ7Gi.1vZIGZUyEJMaJyhQGXE7HpsJJOK4wP2', 'megha', 'user'),
(6, 'mrinalgrg11@gmail.com', '$2y$12$xmiEUog0c6x0HF6goU28JO6FEZw.tYxgyIaIiSYiy9tytOgWOHYAi', 'mrinal', 'user'),
(7, 'hom78@gmail.com', '$2y$12$QjdWVz5IRqCypuKb4b9I.OdKc/b/jwhiZhg2XXW7eDrJ4U36ruiae', 'hom', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auction`
--
ALTER TABLE `auction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `auctionId` (`auctionId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auctionId` (`auctionId`),
  ADD KEY `reviewerId` (`reviewerId`),
  ADD KEY `fk_user_review` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auction`
--
ALTER TABLE `auction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `bid`
--
ALTER TABLE `bid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auction`
--
ALTER TABLE `auction`
  ADD CONSTRAINT `auction_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auction_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`auctionId`) REFERENCES `auction` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_user_review` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`auctionId`) REFERENCES `auction` (`id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`reviewerId`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
