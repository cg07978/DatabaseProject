-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2020 at 05:11 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `instrument_rentals`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`password`) VALUES
('clause490');

-- --------------------------------------------------------

--
-- Table structure for table `a_i_monitors`
--

CREATE TABLE `a_i_monitors` (
  `admin_password` varchar(128) NOT NULL,
  `instrument_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `a_i_monitors`
--

INSERT INTO `a_i_monitors` (`admin_password`, `instrument_id`) VALUES
('clause490', 8),
('clause490', 9);

-- --------------------------------------------------------

--
-- Table structure for table `a_p_monitors`
--

CREATE TABLE `a_p_monitors` (
  `admin_password` varchar(128) NOT NULL,
  `payment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `a_p_monitors`
--

INSERT INTO `a_p_monitors` (`admin_password`, `payment_id`) VALUES
('clause490', 11),
('clause490', 12);

-- --------------------------------------------------------

--
-- Table structure for table `a_u_monitors`
--

CREATE TABLE `a_u_monitors` (
  `admin_password` varchar(128) NOT NULL,
  `user_username` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `a_u_monitors`
--

INSERT INTO `a_u_monitors` (`admin_password`, `user_username`) VALUES
('clause490', 'firstone1'),
('clause490', 'musicstore'),
('clause490', 'orange');

-- --------------------------------------------------------

--
-- Table structure for table `instrument`
--

CREATE TABLE `instrument` (
  `inst_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_posted` datetime DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','hidden','rented') NOT NULL DEFAULT 'hidden',
  `rent_time` datetime DEFAULT NULL,
  `owner_username` varchar(32) NOT NULL,
  `renter_username` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `instrument`
--

INSERT INTO `instrument` (`inst_id`, `name`, `date_posted`, `price`, `status`, `rent_time`, `owner_username`, `renter_username`) VALUES
(8, 'drum', '2020-11-08 08:58:20', '5.65', 'available', NULL, 'orange', NULL),
(9, 'harp', '2020-11-08 08:58:34', '8.95', 'rented', '2020-11-09 08:59:40', 'orange', 'firstone1'),
(11, 'A Very Valuable Piano', NULL, '11111111.00', 'hidden', NULL, 'orange', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payer_username` varchar(32) NOT NULL,
  `reciever_username` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `start_date`, `amount`, `payer_username`, `reciever_username`) VALUES
(11, '2020-10-25 18:57:55', '11.30', 'firstone1', 'orange'),
(12, '2020-10-25 19:08:58', '26.85', 'firstone1', 'orange');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `feedback_count` int(11) NOT NULL DEFAULT 0,
  `dig` varchar(16) NOT NULL,
  `security_code` int(11) NOT NULL,
  `expiration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `email`, `address`, `total_points`, `feedback_count`, `dig`, `security_code`, `expiration_date`) VALUES
('firstone1', 'secondone1', 'tropicana@gmail.com', '3454 Real Place', 0, 0, '9403293847583740', 434, '2020-12-24'),
('musicstore', 'creativepassword', 'musicstore@genericemail.com', '7865 Sound Road', 0, 0, '7382836473847364', 545, '2021-10-12'),
('orange', 'juice', 'tropicana@gmail.com', '3412 Florida Way', 44, 14, '4324868594839281', 345, '2021-04-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`password`);

--
-- Indexes for table `a_i_monitors`
--
ALTER TABLE `a_i_monitors`
  ADD PRIMARY KEY (`admin_password`,`instrument_id`),
  ADD KEY `instrumentid` (`instrument_id`);

--
-- Indexes for table `a_p_monitors`
--
ALTER TABLE `a_p_monitors`
  ADD PRIMARY KEY (`admin_password`,`payment_id`),
  ADD KEY `payid` (`payment_id`);

--
-- Indexes for table `a_u_monitors`
--
ALTER TABLE `a_u_monitors`
  ADD PRIMARY KEY (`admin_password`,`user_username`),
  ADD KEY `userusername` (`user_username`);

--
-- Indexes for table `instrument`
--
ALTER TABLE `instrument`
  ADD PRIMARY KEY (`inst_id`),
  ADD KEY `ownerusername` (`owner_username`),
  ADD KEY `renterusername` (`renter_username`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payerusername` (`payer_username`),
  ADD KEY `recieverusername` (`reciever_username`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instrument`
--
ALTER TABLE `instrument`
  MODIFY `inst_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `a_i_monitors`
--
ALTER TABLE `a_i_monitors`
  ADD CONSTRAINT `admininstpassword` FOREIGN KEY (`admin_password`) REFERENCES `administrator` (`password`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `instrumentid` FOREIGN KEY (`instrument_id`) REFERENCES `instrument` (`inst_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `a_p_monitors`
--
ALTER TABLE `a_p_monitors`
  ADD CONSTRAINT `adminpaypassword` FOREIGN KEY (`admin_password`) REFERENCES `administrator` (`password`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payid` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `a_u_monitors`
--
ALTER TABLE `a_u_monitors`
  ADD CONSTRAINT `adminpassword` FOREIGN KEY (`admin_password`) REFERENCES `administrator` (`password`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userusername` FOREIGN KEY (`user_username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instrument`
--
ALTER TABLE `instrument`
  ADD CONSTRAINT `ownerusername` FOREIGN KEY (`owner_username`) REFERENCES `user` (`username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `renterusername` FOREIGN KEY (`renter_username`) REFERENCES `user` (`username`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payerusername` FOREIGN KEY (`payer_username`) REFERENCES `user` (`username`) ON UPDATE CASCADE,
  ADD CONSTRAINT `receiverusername` FOREIGN KEY (`reciever_username`) REFERENCES `user` (`username`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
