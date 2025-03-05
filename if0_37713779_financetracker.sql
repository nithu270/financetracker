-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql101.infinityfree.com
-- Generation Time: Nov 15, 2024 at 10:00 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37713779_financetracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `sav_name` varchar(255) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `saved_amount` int(255) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `userid`, `sav_name`, `total_amount`, `saved_amount`) VALUES
(5, 2, 'car', 100000, 60000),
(6, 3, 'Car', 800000, 2500);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `cashflow` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `userid`, `cashflow`, `type`, `description`, `amount`, `datetime`) VALUES
(1, 2, 'income', 'salary', 'tcs salary', 50000, '2024-09-03 00:00:00'),
(2, 2, 'expense', 'food', 'party', 1000, '2024-09-04 00:00:00'),
(3, 2, 'expense', 'rent', 'house rent', 3000, '2024-09-03 00:00:00'),
(4, 2, 'expense', 'vehicle', 'repair', 1000, '2024-09-05 00:00:00'),
(5, 2, 'income', 'salary', 'tcs salary', 10000, '2024-10-10 00:00:00'),
(6, 2, 'income', 'salary', 'tcs salary', 10000, '2024-01-16 00:00:00'),
(7, 2, 'expense', 'salary', 'tcs salary', 10000, '2024-02-16 00:00:00'),
(8, 2, 'income', 'salary', 'tcs salary', 10000, '2024-03-16 00:00:00'),
(9, 2, 'income', 'education', 'B.TECH', 15000, '2024-04-16 00:00:00'),
(10, 2, 'expense', 'food', 'party', 1000, '2024-10-15 00:00:00'),
(11, 2, 'expense', 'movie', 'jailer', 1500, '2024-10-14 00:00:00'),
(12, 2, 'expense', 'rent', 'house rent', 5000, '2024-10-01 00:00:00'),
(13, 2, 'expense', 'food', 'party', 1000, '2024-10-17 00:00:00'),
(14, 3, 'income', 'FOOD', 'AS Cafe', 550, '2024-11-16 00:00:00'),
(15, 3, 'income', 'FOOD', 'AS Cafe', 550, '2024-11-16 00:00:00'),
(16, 3, 'income', 'FOOD', 'AS Cafe', 550, '2024-11-16 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `name`, `mobile_number`, `password`, `city`, `state`, `username`) VALUES
(2, 'hari@gmail.com', 'harith', '9876543210', '$2y$10$kW89AMNOwt5SUNMnRArVwelL6l1z60EGr3DR4zGvMC4yQw8lOmUAC', 'coimbatore', 'Tamil Nadu', 'hari'),
(3, 'deepankumar.ec22@bitsathy.ac.in', 'DEEPAN KUMAR M ', '7418090382', '$2y$10$s7F9jwouehxX/kSgZ/iXmejdLPMXz6nkcDX90ZSwOi04pnbx/QIIK', 'Sathyamangalam, Erode.', 'Tamil Nadu', 'DEEPANKUMAR@M');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `savings`
--
ALTER TABLE `savings`
  ADD CONSTRAINT `savings_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
