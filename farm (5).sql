-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2025 at 10:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farm`
--

-- --------------------------------------------------------

--
-- Table structure for table `cooperative`
--

CREATE TABLE `cooperative` (
  `cooperativeid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contactinfo` varchar(255) DEFAULT NULL,
  `accreditationstatus` varchar(255) DEFAULT NULL,
  `dateestablished` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerid`, `userid`) VALUES
(1, 3),
(2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

CREATE TABLE `farmer` (
  `farmerid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `cooperativeid` int(11) DEFAULT NULL,
  `farmname` varchar(255) DEFAULT NULL,
  `certification` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `businesspermit` varchar(100) DEFAULT NULL,
  `tin` varchar(32) DEFAULT NULL,
  `pickup_address` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `registered_address` varchar(255) DEFAULT NULL,
  `taxpayer_id` varchar(32) DEFAULT NULL,
  `business_registration_certificate` varchar(255) DEFAULT NULL,
  `proof_of_identity` varchar(255) DEFAULT NULL,
  `seller_type` enum('individual','partnership','corporation') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`farmerid`, `userid`, `cooperativeid`, `farmname`, `certification`, `description`, `status`, `businesspermit`, `tin`, `pickup_address`, `business_name`, `registered_address`, `taxpayer_id`, `business_registration_certificate`, `proof_of_identity`, `seller_type`) VALUES
(1, 2, NULL, 'Happy Farm', 'Organic Certified', 'We grow fresh veggies!', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 7, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 8, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `type` enum('order','message','system') DEFAULT 'order',
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderid` int(11) NOT NULL,
  `customerid` int(11) DEFAULT NULL,
  `orderdate` datetime DEFAULT NULL,
  `status` enum('pending','shipped','delivered','cancelled') NOT NULL,
  `totalamount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderid`, `customerid`, `orderdate`, `status`, `totalamount`) VALUES
(1, 1, '2025-08-18 00:38:44', 'pending', 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `orderitem`
--

CREATE TABLE `orderitem` (
  `orderitemid` int(11) NOT NULL,
  `orderid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitem`
--

INSERT INTO `orderitem` (`orderitemid`, `orderid`, `productid`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 23.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentid` int(11) NOT NULL,
  `orderid` int(11) DEFAULT NULL,
  `paymentmethod` varchar(255) DEFAULT NULL,
  `paymentdate` datetime DEFAULT NULL,
  `paymentstatus` enum('paid','pending','failed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productid` int(11) NOT NULL,
  `farmerid` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stockquantity` int(11) DEFAULT NULL,
  `imageurl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productid`, `farmerid`, `name`, `description`, `category`, `price`, `stockquantity`, `imageurl`) VALUES
(1, 1, 'qwewqe', 'asdsa', 'eqwe', 23.00, 1, 'storage/products/QqbqI9aVoLT3wy2uzv3mWeiXZ4J4HTZEYIayuazz.png'),
(2, 1, 'eqwe', 'dasd', 'eqwe', 23.00, 2323, 'storage/products/V0YWVHnhCksBjpD1TIuvyZNcqaHd2qgt54XqLYRc.png');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `reviewid` int(11) NOT NULL,
  `customerid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `reviewdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LpKAU2y9m0aayDzM2g3QtWm40Xz20ZftZW3uDIe9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicExlaEVTN1FlMTVSNVNNT3ZPQUtsNEE4bEVOdEN6Mjc5eld3M3ZFZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1755784730);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('farmer','customer','cooperativeadmin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `name`, `email`, `password`, `phone`, `address`, `role`) VALUES
(1, 'Admin One', 'admin@example.com', '$2y$12$OVIoqnXJRxiUB0VZxuVTrOmw5czuNcHyD3tQLFKoLQDd35OT9efAq', '09123456789', 'Tarlac City', 'cooperativeadmin'),
(2, 'Farmer One', 'farmer@example.com', '$2y$12$Uaok9gPTqYmzTo15dXZMsuIGwNx8Xf/MZMPjXxt1eQ2P3D0OSyxjW', '09123456780', 'Barangay A, Tarlac City', 'farmer'),
(3, 'Customer One', 'customer1@example.com', '$2y$12$QFHeJSIxvx9wtR9nwlxgQev04PJNcFzh/IcPERD8niWEwoDpg6Xaa', '09123456781', 'Barangay B, Tarlac City', 'customer'),
(4, 'Customer Two', 'customer2@example.com', '$2y$12$.b/ZBdrKrQElrgW4i0qPceB96hsPAgPaV6qpYs8TgZJW5Iuo1MVAu', '09123456782', 'Barangay C, Tarlac City', 'customer'),
(5, 'Van Rodolf Mauricio Suliva', 'vthres@gmail.com', '$2y$12$81Ga9WjIia7bLsAxnyo6JOhzFxzsVMcExNHcVAVSk4WAmBLvcCBQC', NULL, NULL, 'farmer'),
(6, 'Van Rodolf Mauricio Suliva', 'vr.suliva00072@student.tsu.edu.ph', '$2y$12$ZjHK8mzMp4EErdBdYB5qN.4dQWOEy7iWogpRSghWXLpUtD5XI9F46', NULL, NULL, 'farmer'),
(7, 'Van Rodolf Mauricio Suliva', 'v@gmail.com', '$2y$12$0L5xBJkT7fLkQFmrmwZ8ZuXUohWmIUG3F2dDdG6kg3uffTV.NkaTS', NULL, NULL, 'farmer'),
(8, 'Van Rodolf Mauricio Suliva', 'va@gmail.com', '$2y$12$44aDUHW0do0IfvzFDMO3ROCHfcS6KXFI3lX0BWC8OFU643WenlN16', NULL, NULL, 'farmer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cooperative`
--
ALTER TABLE `cooperative`
  ADD PRIMARY KEY (`cooperativeid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `farmer`
--
ALTER TABLE `farmer`
  ADD PRIMARY KEY (`farmerid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `cooperativeid` (`cooperativeid`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD PRIMARY KEY (`orderitemid`),
  ADD KEY `orderid` (`orderid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `orderid` (`orderid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productid`),
  ADD KEY `farmerid` (`farmerid`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`reviewid`),
  ADD KEY `customerid` (`customerid`),
  ADD KEY `productid` (`productid`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cooperative`
--
ALTER TABLE `cooperative`
  MODIFY `cooperativeid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `farmer`
--
ALTER TABLE `farmer`
  MODIFY `farmerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orderitem`
--
ALTER TABLE `orderitem`
  MODIFY `orderitemid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `reviewid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`);

--
-- Constraints for table `farmer`
--
ALTER TABLE `farmer`
  ADD CONSTRAINT `farmer_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`),
  ADD CONSTRAINT `farmer_ibfk_2` FOREIGN KEY (`cooperativeid`) REFERENCES `cooperative` (`cooperativeid`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_customer_fk` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`);

--
-- Constraints for table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `order` (`orderid`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`orderid`) REFERENCES `order` (`orderid`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`farmerid`) REFERENCES `farmer` (`farmerid`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customer` (`customerid`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`productid`) REFERENCES `product` (`productid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
