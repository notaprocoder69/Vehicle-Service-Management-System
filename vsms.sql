-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 21, 2024 at 07:02 AM
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
-- Database: `vsms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `apt_id` varchar(20) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`apt_id`, `cust_id`, `vehicle_id`, `date`, `service_type`) VALUES
('202411200904146621', 4738, 5858, '2024-11-20', 'Regular Maintenance Checks'),
('202411200929122537', 3490, 232312, '2024-11-20', 'Engine Diagnostics'),
('202411201902469883', 7254, 2409, '2024-11-20', 'Engine Diagnostics');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `phone_number` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `name`, `phone_number`, `email`, `address`) VALUES
(987, 'hehe', 235674, 'haha@gmail.com', 'dfadfadfasdf'),
(1234, 'mayuresh', 1234567890, 'examplemail@gmail.com', 'banglore'),
(2354, 'mayuresh', 1234567890, 'examplemail@gmail.com', 'banglore'),
(3490, 'hjgfhf', 234, 'er@gmail.com', ''),
(4738, 'jaaza', 43349842, 'jaza@gmail.com', 'telugu'),
(6969, 'may', 334534, 'dis@gmail.com', 'adfadfadf'),
(7254, 'khushi', 88452892, 'khushi@gmail.com', 'nandangadda');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `cost` int(11) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `service_id`, `cost`, `cust_id`) VALUES
(1, 1, 300, 987),
(2, 2, 500, 987),
(3, 3, 700, 3490),
(4, 4, 644, 2354),
(5, 5, 600, 987),
(6, 6, 600, 987),
(7, 7, 500, 3490),
(8, 8, 300, 987),
(9, 9, 500, 4738),
(10, 10, 200, 1234),
(11, 11, 100, 6969);

-- --------------------------------------------------------

--
-- Table structure for table `rating_review`
--

CREATE TABLE `rating_review` (
  `customer_id` int(11) DEFAULT NULL,
  `review` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating_review`
--

INSERT INTO `rating_review` (`customer_id`, `review`, `date`) VALUES
(4738, 'very nice', '2024-11-20'),
(3490, 'nice', '2024-11-20'),
(7254, 'nice work', '2024-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `tech_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `vehicle_id`, `tech_id`, `description`, `cost`, `date`) VALUES
(1, 5858, 1, 'Regular Maintenance Checks', 300.00, '2024-11-20'),
(2, 1234134, 1, 'Regular Maintenance Checks', 500.00, '2024-11-20'),
(3, 5858, 6, 'Engine Diagnostics', 700.00, '2024-11-20'),
(4, 5858, 1, 'Regular Maintenance Checks', 644.00, '2024-11-20'),
(5, 232312, 1, 'Regular Maintenance Checks', 600.00, '2024-11-20'),
(6, 232312, 1, 'Regular Maintenance Checks', 600.00, '2024-11-20'),
(7, 6894, 8, 'Regular Maintenance Checks', 500.00, '2024-11-20'),
(8, 5858, 2, 'Engine Diagnostics', 300.00, '2024-11-20'),
(9, 1234134, 6, 'Regular Maintenance Checks', 500.00, '2024-11-20'),
(10, 232312, 5, 'Engine Diagnostics', 200.00, '2024-11-20'),
(11, 6894, 5, 'Regular Maintenance Checks', 100.00, '2024-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`id`, `username`, `password`, `dt`) VALUES
(1, 'admin123', '$2y$10$hc.rxnZf2P3IfbVd2Ho0Xe/1dzRlR5I6VtAOSRYT0A14gai114116', '2024-11-20 07:15:04'),
(2, 'admin69', '$2y$10$Y/bBCvzTpiaZ0Iwd52xgeu4wCa47m2f/PMec0ulz7uZ6xbRnJYHrC', '2024-11-20 08:30:07'),
(3, 'khushi123', '$2y$10$PPAYYvdpvnZzhwT4sTZppum9xh0m4nEVlVKIdeU3VHlph19uUCnYm', '2024-11-20 18:01:17');

-- --------------------------------------------------------

--
-- Table structure for table `technician`
--

CREATE TABLE `technician` (
  `tech_id` int(11) NOT NULL,
  `name` varchar(55) DEFAULT NULL,
  `phone_no` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technician`
--

INSERT INTO `technician` (`tech_id`, `name`, `phone_no`, `email`) VALUES
(1, 'John Doe', '9876543210', 'john.doe@example.com'),
(2, 'Jane Smith', '8765432109', 'jane.smith@example.com'),
(3, 'Robert Brown', '7654321098', 'robert.brown@example.com'),
(4, 'Emily Davis', '6543210987', 'emily.davis@example.com'),
(5, 'Michael Johnson', '5432109876', 'michael.johnson@example.com'),
(6, 'Sarah Wilson', '4321098765', 'sarah.wilson@example.com'),
(7, 'David Martinez', '3210987654', 'david.martinez@example.com'),
(8, 'Linda Garcia', '2109876543', 'linda.garcia@example.com'),
(9, 'Daniel Hernandez', '1098765432', 'daniel.hernandez@example.com'),
(10, 'Patricia Lopez', '9876101234', 'patricia.lopez@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `vehicle_id` int(11) NOT NULL,
  `model` varchar(10) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`vehicle_id`, `model`, `cust_id`) VALUES
(1234, 'car', 1234),
(2409, 'auto', 7254),
(5858, 'truck', 4738),
(6894, 'bike', 6969),
(232312, 'truck', 3490),
(1234134, 'car', 987);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`apt_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `rating_review`
--
ALTER TABLE `rating_review`
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `tech_id` (`tech_id`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `technician`
--
ALTER TABLE `technician`
  ADD PRIMARY KEY (`tech_id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`),
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`);

--
-- Constraints for table `rating_review`
--
ALTER TABLE `rating_review`
  ADD CONSTRAINT `rating_review_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`cust_id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`vehicle_id`),
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`tech_id`) REFERENCES `technician` (`tech_id`);

--
-- Constraints for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD CONSTRAINT `vehicle_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
