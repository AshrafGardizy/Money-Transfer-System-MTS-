-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17,  at 02:26 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money_exchange`
--
CREATE DATABASE IF NOT EXISTS `money_exchange` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `money_exchange`;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `b_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`b_id`, `name`, `location`, `manager_id`, `created_at`) VALUES
(6, 'Mazar Branch', 'Balkh, Mazar Sharif', 12, '-12-13 03:59:29'),
(7, 'Kabul Branch', 'Barchi', 21, '-12-13 04:01:10');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `b_id` int(11) DEFAULT NULL,
  `position` enum('exchanger','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `firstname`, `lastname`, `phone`, `email`, `password`, `b_id`, `position`) VALUES
(10, 'Latif', 'Hakimi', '078987898', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin'),
(12, 'hamid', 'hamidi', '098789', 'hamid@gmail.com', 'dfb8e2bec9362a4e99e0cc79af77f123', 6, 'exchanger'),
(21, 'Nadir', 'Nadiri', '8778987789', 'nadir@gmail.com', '202cb962ac59075b964b07152d234b70', 7, 'exchanger');

-- --------------------------------------------------------

--
-- Table structure for table `exchange`
--

CREATE TABLE `exchange` (
  `ex_id` int(11) NOT NULL,
  `ex_code` varchar(100) DEFAULT NULL,
  `s_full_name` varchar(100) DEFAULT NULL,
  `s_b_id` int(11) DEFAULT NULL,
  `r_full_name` varchar(100) DEFAULT NULL,
  `r_b_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `r_amount` int(11) DEFAULT NULL,
  `profit` int(11) NOT NULL,
  `achived` enum('yes','no') DEFAULT NULL,
  `s_date` timestamp NULL DEFAULT current_timestamp(),
  `r_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `branch_id_fk` (`manager_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`),
  ADD KEY `cons_name` (`b_id`);

--
-- Indexes for table `exchange`
--
ALTER TABLE `exchange`
  ADD PRIMARY KEY (`ex_id`),
  ADD UNIQUE KEY `ex_code_unique` (`ex_code`),
  ADD KEY `exchange_b_id_fk_1` (`s_b_id`),
  ADD KEY `exchange_b_id_fk_2` (`r_b_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `exchange`
--
ALTER TABLE `exchange`
  MODIFY `ex_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `branch_id_fk` FOREIGN KEY (`manager_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `cons_name` FOREIGN KEY (`b_id`) REFERENCES `branch` (`b_id`);

--
-- Constraints for table `exchange`
--
ALTER TABLE `exchange`
  ADD CONSTRAINT `exchange_b_id_fk_1` FOREIGN KEY (`s_b_id`) REFERENCES `branch` (`b_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exchange_b_id_fk_2` FOREIGN KEY (`r_b_id`) REFERENCES `branch` (`b_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
