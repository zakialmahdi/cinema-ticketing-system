-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2026 at 03:43 AM
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
-- Database: `ticketing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `fnb`
--

CREATE TABLE `fnb` (
  `FnbID` int(11) NOT NULL,
  `FnbName` varchar(100) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fnb`
--

INSERT INTO `fnb` (`FnbID`, `FnbName`, `Price`) VALUES
(4, 'Popcorn', 20.00),
(5, 'Ice Lemon Tea', 17.00),
(6, 'Nugget Set', 15.00),
(7, 'Nasi lemak', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `MovieID` int(11) NOT NULL,
  `MovieName` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`MovieID`, `MovieName`) VALUES
(5, 'Shrek: Love Never Ends'),
(6, 'Missing Piece The Movie'),
(7, 'Lego Batman');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SaleID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `MovieID` int(11) DEFAULT NULL,
  `TicketType` varchar(50) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Showtime` varchar(50) DEFAULT NULL,
  `FnbID` int(11) DEFAULT NULL,
  `FnbQuantity` int(11) DEFAULT NULL,
  `TotalPrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(30) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `Password` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`UserID`, `Username`, `Email`, `Password`) VALUES
(131, 'nsilemark', 'zakialmahdi@gmail.com', '12345678'),
(132, 'zaki', 'zaki@gmail.com', '12345678'),
(133, 'zakialmahdi', 'zakialmahdi@gmail.com', '12345678');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fnb`
--
ALTER TABLE `fnb`
  ADD PRIMARY KEY (`FnbID`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`MovieID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SaleID`),
  ADD KEY `fk_sales_user` (`UserID`),
  ADD KEY `fk_sales_movie` (`MovieID`),
  ADD KEY `fk_sales_fnb` (`FnbID`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fnb`
--
ALTER TABLE `fnb`
  MODIFY `FnbID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `MovieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_fnb` FOREIGN KEY (`FnbID`) REFERENCES `fnb` (`FnbID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_movie` FOREIGN KEY (`MovieID`) REFERENCES `movies` (`MovieID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
