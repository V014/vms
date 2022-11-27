-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2022 at 04:55 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vms`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `established` int(11) DEFAULT NULL,
  `location` point DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `name`, `established`, `location`) VALUES
(1, 2, 'Neon Fuel', 1958, 0x000000000101000000237cb0eac1ae2fc0d22361fafe7a4140),
(2, 3, 'FuturEnergy', 1999, 0x0000000001010000006950916b84d32fc0245db2fc9b864140),
(3, 4, 'Work Fuel', 2000, 0x000000000101000000154555c37f572fc0622959da11764140),
(4, 5, 'EnergyPlus', 1978, 0x000000000101000000369270c2bbff2bc01d90e534a0ea4040),
(5, 6, 'EneGrade', 1990, 0x000000000101000000d9f0b0ae90dd2bc0400f0cb4b6e64040);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `national_id` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `user_id`, `national_id`, `dob`, `first_name`, `last_name`) VALUES
(1, 7, 'JK4893UI', '1998-05-12', 'Leon', 'Tsetsa'),
(2, 8, 'IP0392NG', '1978-01-01', 'Bright', 'Magoba'),
(3, 9, '10MDJ302', '1989-09-12', 'Masala', 'Dausi'),
(4, 10, 'AS9032PO', '1990-12-12', 'John', 'Limpopo'),
(5, 11, 'FG3849EM', '1970-11-20', 'Henry', 'Saladi'),
(6, 12, 'LM2010WO', '1988-07-29', 'Benjamin', 'Mavuto'),
(7, 13, 'HJG9430', '2000-02-01', 'Edward', 'Ulendo'),
(8, 14, 'VG3928AS', '1969-05-04', 'Daniel', 'Gonjani'),
(9, 15, 'RP2312QW', '1993-03-16', 'Mike', 'Pafupi'),
(10, 16, 'MK9839ZZ', '1997-02-15', 'Stewart', 'Mwomba');

-- --------------------------------------------------------

--
-- Table structure for table `fuel_types`
--

CREATE TABLE `fuel_types` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `cost_per_litre` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fuel_types`
--

INSERT INTO `fuel_types` (`id`, `name`, `cost_per_litre`) VALUES
(1, 'parafin', '1200.00'),
(2, 'diesel', '1850.00'),
(3, 'petrol', '2000.00');

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE `help` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `question` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `cost` decimal(15,2) NOT NULL,
  `status` enum('pending','delivered') NOT NULL,
  `order_date` date NOT NULL,
  `date_delivered` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `company_id`, `type_id`, `quantity`, `cost`, `status`, `order_date`, `date_delivered`) VALUES
(1, 2, 2, 3500, '6475000.00', 'delivered', '2022-01-22', NULL),
(2, 2, 2, 5000, '9250000.00', 'delivered', '2022-01-01', NULL),
(3, 2, 2, 7600, '12950000.00', 'delivered', '2022-03-10', NULL),
(4, 2, 3, 10000, '20000000.00', 'delivered', '2022-04-01', NULL),
(5, 2, 3, 7600, '15200000.00', 'delivered', '2022-07-05', NULL),
(6, 2, 3, 12000, '24000000.00', 'delivered', '2022-02-10', NULL),
(7, 2, 3, 20000, '40000000.00', 'delivered', '2021-12-22', NULL),
(8, 2, 2, 13000, '24050000.00', 'delivered', '2021-09-22', NULL),
(9, 2, 3, 8500, '17000000.00', 'pending', '2022-11-16', NULL),
(10, 2, 2, 7900, '14615000.00', 'pending', '2022-11-16', NULL),
(11, 3, 3, 10000, '20000000.00', 'delivered', '2022-03-22', NULL),
(12, 3, 2, 12000, '22200000.00', 'delivered', '2022-02-02', NULL),
(13, 3, 3, 5000, '10000000.00', 'delivered', '2022-06-09', NULL),
(14, 3, 3, 7000, '14000000.00', 'delivered', '2022-03-01', NULL),
(15, 3, 2, 8400, '15540000.00', 'delivered', '2022-05-01', NULL),
(16, 3, 2, 5000, '9250000.00', 'delivered', '2022-08-22', NULL),
(17, 3, 2, 7900, '14615000.00', 'delivered', '2022-07-01', NULL),
(18, 3, 3, 10000, '20000000.00', 'delivered', '2022-04-05', NULL),
(19, 3, 3, 8000, '16000000.00', 'delivered', '2021-10-20', NULL),
(20, 3, 2, 6000, '11100000.00', 'pending', '2022-11-17', NULL),
(21, 4, 2, 7000, '12950000.00', 'delivered', '2022-01-01', NULL),
(22, 4, 3, 9000, '18000000.00', 'delivered', '2022-01-31', NULL),
(23, 4, 2, 6900, '12765000.00', 'delivered', '2022-02-01', NULL),
(24, 4, 3, 8000, '16000000.00', 'delivered', '2022-02-27', NULL),
(25, 4, 3, 4000, '8000000.00', 'delivered', '2022-03-01', NULL),
(26, 4, 2, 7000, '12950000.00', 'delivered', '2022-03-31', NULL),
(27, 4, 2, 6900, '12765000.00', 'delivered', '2022-04-01', NULL),
(28, 4, 3, 15000, '30000000.00', 'pending', '2022-11-17', NULL),
(29, 4, 3, 27000, '54000000.00', 'pending', '2022-11-16', NULL),
(30, 4, 2, 14000, '25900000.00', 'pending', '2022-11-17', NULL),
(31, 5, 3, 7000, '14000000.00', 'delivered', '2022-01-01', NULL),
(32, 5, 2, 8000, '14800000.00', 'delivered', '2022-02-01', NULL),
(33, 5, 3, 20000, '40000000.00', 'delivered', '2022-03-01', NULL),
(34, 5, 2, 10000, '18500000.00', 'delivered', '2022-04-01', NULL),
(35, 5, 3, 8000, '16000000.00', 'delivered', '2022-05-01', NULL),
(36, 5, 2, 5000, '9250000.00', 'delivered', '2022-06-01', NULL),
(37, 5, 2, 9000, '16650000.00', 'delivered', '2022-07-01', NULL),
(38, 5, 2, 10000, '18500000.00', 'pending', '2022-11-17', NULL),
(39, 5, 3, 6700, '13400000.00', 'pending', '2022-11-16', NULL),
(40, 5, 3, 8000, '16000000.00', 'pending', '2022-11-17', NULL),
(41, 6, 2, 10000, '18500000.00', 'delivered', '2022-01-01', NULL),
(42, 6, 3, 7500, '15000000.00', 'delivered', '2022-02-01', NULL),
(43, 6, 2, 8000, '8000.00', 'delivered', '2022-03-01', NULL),
(44, 6, 2, 10000, '18500000.00', 'delivered', '2022-04-01', NULL),
(45, 6, 3, 5000, '10000000.00', 'delivered', '2022-05-01', NULL),
(46, 6, 2, 6700, '12395000.00', 'delivered', '2022-06-01', NULL),
(47, 6, 3, 7900, '15800000.00', 'delivered', '2022-07-01', NULL),
(48, 6, 3, 5000, '10000000.00', 'delivered', '2022-08-01', NULL),
(49, 6, 2, 6000, '11100000.00', 'delivered', '2021-12-01', NULL),
(50, 6, 2, 8000, '14800000.00', 'pending', '2022-11-17', NULL),
(51, 6, 2, 2000, '3700000.00', 'delivered', '2021-12-01', NULL),
(52, 6, 2, 20000, '37000000.00', 'delivered', '2021-11-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_driver`
--

CREATE TABLE `order_driver` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_driver`
--

INSERT INTO `order_driver` (`id`, `order_id`, `driver_id`, `vehicle_id`) VALUES
(1, 1, 7, 1),
(2, 2, 8, 4),
(3, 3, 9, 2),
(4, 4, 10, 5),
(5, 5, 11, 6),
(6, 6, 12, 7),
(7, 7, 13, 8),
(8, 8, 14, 10),
(9, 9, 15, 9),
(10, 10, 16, 3),
(11, 11, 7, 4),
(12, 12, 8, 6),
(13, 13, 9, 7),
(14, 14, 10, 9),
(15, 15, 11, 2),
(16, 16, 12, 2),
(17, 17, 13, 10),
(18, 18, 14, 5),
(19, 19, 15, 4),
(20, 20, 7, 6),
(21, 21, 7, 5),
(22, 22, 8, 7),
(23, 23, 9, 5),
(24, 24, 10, 3),
(25, 25, 11, 2),
(26, 26, 12, 5),
(27, 27, 13, 6),
(28, 28, 10, 7),
(29, 29, 12, 10),
(30, 30, 13, 5),
(31, 31, 7, 9),
(32, 32, 8, 8),
(33, 33, 9, 7),
(34, 34, 10, 6),
(35, 35, 11, 5),
(36, 36, 12, 4),
(37, 37, 13, 3),
(38, 38, 9, 2),
(39, 39, 11, 6),
(40, 40, 15, 7),
(41, 41, 7, 8),
(42, 42, 8, 10),
(43, 43, 9, 1),
(44, 44, 10, 2),
(45, 45, 11, 3),
(46, 46, 12, 5),
(47, 47, 13, 6),
(48, 48, 14, 7),
(49, 49, 15, 8),
(50, 50, 12, 2),
(51, 51, 7, 3),
(52, 52, 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `current_location` point NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `order_id`, `current_location`) VALUES
(1, 9, 0x0000000001010000000ee88694b5ce2fc0777aac91f87a4140),
(2, 10, 0x000000000101000000cad9e4dea7d42fc0601591a5cc794140),
(3, 20, 0x000000000101000000396ef5c0dd4f2fc08e19c9d664924140),
(4, 28, 0x000000000101000000b181732886d12fc0283016ba897a4140),
(5, 29, 0x000000000101000000b13b9de626da2fc0c8a9059819784140),
(6, 30, 0x000000000101000000291bc4fd27f22fc0ba9c379adb734140),
(7, 38, 0x0000000001010000004e26f3b038952cc05bcca78475144140),
(8, 39, 0x000000000101000000a1081111eba82cc068a81212db184140),
(9, 40, 0x0000000001010000004bdc4bba53b12cc0d1e4d04cda1c4140),
(10, 50, 0x0000000001010000009fbc0c96b00d2cc08952c51998c14040);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` enum('company','driver','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `profile_picture`, `created_at`, `updated_at`, `phone_number`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-14', '2022-10-14', '+265887013077', 'admin@gmail.com', 'admin'),
(2, 'neonfuel', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-04', '2022-10-04', '+265887031610', 'neonfuel@gmail.com', 'company'),
(3, 'futurenergy', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-06', '2022-10-06', '+265886268585', 'futurenergy@gmail.com', 'company'),
(4, 'workfuel', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-08', '2022-10-08', '+265995133886', 'workfuel@gmail.com', 'company'),
(5, 'energyplus', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-09', '2022-10-09', '+265997652456', 'energyplus@gmail.com', 'company'),
(6, 'enegrade', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-09', '2022-10-09', '+265887065997', 'enegrade@gmail.com', 'company'),
(7, 'leon', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-05', '2022-10-05', '+265886707196', 'leon@gmail.com', 'driver'),
(8, 'bright', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-07', '2022-10-07', '+265886096405', 'bright@gmail.com', 'driver'),
(9, 'masala', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-14', '2022-10-14', '+265994943434', 'masala@gmail.com', 'driver'),
(10, 'john', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-04', '2022-10-04', '+265884344235', 'john@gmail.com', 'driver'),
(11, 'henry', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-06', '2022-10-06', '+265993196992', 'henry@gmail.com', 'driver'),
(12, 'benjamin', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-05', '2022-10-05', '+265992372013', 'benjamin@gmail.com', 'driver'),
(13, 'edward', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-07', '2022-10-07', '+265881433552', 'edward@gmail.com', 'driver'),
(14, 'daniel', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-09', '2022-10-09', '+265887993307', 'daniel@gmail.com', 'driver'),
(15, 'mike', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-08', '2022-10-08', '+265997362621', 'mike@gmail.com', 'driver'),
(16, 'stewart', '$2y$10$2s9C16/qsAKbsuw1xHja9u25yhOgqzKLP/HUN7XgyQFZpr8SrLyra', 'uploads/profiles/user-profile.png', '2022-10-14', '2022-10-14', '+265889576450', 'stewart@gmail.com', 'driver');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `registration_no` varchar(50) NOT NULL,
  `make` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `registration_no`, `make`, `capacity`, `year`) VALUES
(1, 'QW9032', 'VolksWagen', 30000, 2005),
(2, 'HT3432', 'Toyota', 30000, 2007),
(3, 'LF3451', 'VolksWagen', 30000, 2008),
(4, 'PR9T34', 'Toyota', 30000, 2005),
(5, 'SDF3E', 'VolksWagen', 30000, 2005),
(6, 'DF2030', 'Toyota', 30000, 2006),
(7, 'KS1039', 'VolksWagen', 30000, 2007),
(8, 'SJ9433', 'Toyota', 30000, 2009),
(9, 'EQVD34', 'VolksWagen', 30000, 2005),
(10, 'LK0493', 'Toyota', 30000, 2008);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuel_types`
--
ALTER TABLE `fuel_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `help`
--
ALTER TABLE `help`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_driver`
--
ALTER TABLE `order_driver`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fuel_types`
--
ALTER TABLE `fuel_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `help`
--
ALTER TABLE `help`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `order_driver`
--
ALTER TABLE `order_driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
