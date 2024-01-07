-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2023 at 03:37 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodstrike_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_master`
--

CREATE TABLE `admin_master` (
  `admin_id` int(2) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_mobile` varchar(20) NOT NULL,
  `admin_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_master`
--

INSERT INTO `admin_master` (`admin_id`, `admin_name`, `admin_email`, `admin_mobile`, `admin_password`) VALUES
(1, 'Admin', 'admin@foodstrike.com', '+919292929292', '$2y$10$AIWAY58tbIxTKl.fzsO1nOHReSorkO4tq.zIGBKJaQlUq0eeL4yvi');

-- --------------------------------------------------------

--
-- Table structure for table `products_master`
--

CREATE TABLE `products_master` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text NOT NULL,
  `product_photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_master`
--

INSERT INTO `products_master` (`product_id`, `product_name`, `product_description`, `product_photo`) VALUES
(1, 'Veg Thali', '5 course meal', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNZhG-jWMBm0SMAg8tjKMKlJDisjmZAE18002n_6oGGgY9CD57zxDz6jzN&amp;s=10'),
(2, 'Non-Veg Thali', '5 course meal', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNZhG-jWMBm0SMAg8tjKMKlJDisjmZAE18002n_6oGGgY9CD57zxDz6jzN&amp;s=10'),
(3, 'Biryani Veg', 'Small biryani', 'https://myfoodstory.com/wp-content/uploads/2020/09/Veg-Biryani-4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_supplier_mapping`
--

CREATE TABLE `product_supplier_mapping` (
  `product_supplier_mapping_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_price` varchar(100) NOT NULL,
  `approx_delivery_duration` varchar(100) NOT NULL,
  `product_view_link` text NOT NULL,
  `last_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_supplier_mapping`
--

INSERT INTO `product_supplier_mapping` (`product_supplier_mapping_id`, `product_id`, `supplier_id`, `product_price`, `approx_delivery_duration`, `product_view_link`, `last_modified`) VALUES
(1, 1, 1, '1001', '121', 'https://www.zomato.com', '0000-00-00 00:00:00'),
(2, 1, 2, '105', '15', '', '0000-00-00 00:00:00'),
(3, 2, 1, '200', '12', '', '0000-00-00 00:00:00'),
(4, 2, 2, '210', '11', '', '0000-00-00 00:00:00'),
(5, 3, 1, '', '', '', '0000-00-00 00:00:00'),
(6, 3, 2, '', '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_master`
--

CREATE TABLE `suppliers_master` (
  `supplier_id` int(2) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_logo` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers_master`
--

INSERT INTO `suppliers_master` (`supplier_id`, `supplier_name`, `supplier_logo`) VALUES
(1, 'Zomato', 'https://i.ibb.co/tMv8C2L/zomato-logo.jpg'),
(2, 'Swiggy', 'https://i.ibb.co/q9m52vn/swiggy-logo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users_master`
--

CREATE TABLE `users_master` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(100) NOT NULL,
  `u_email` varchar(100) NOT NULL,
  `u_mobile` varchar(20) NOT NULL,
  `u_password` varchar(1000) NOT NULL,
  `u_registration_datetime` datetime NOT NULL,
  `u_account_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_master`
--

INSERT INTO `users_master` (`u_id`, `u_name`, `u_email`, `u_mobile`, `u_password`, `u_registration_datetime`, `u_account_status`) VALUES
(3, 'John Doe', 'john@xyz.com', '+919898989898', '$2y$10$fDh/0UXACXrfF1paVD4TgeHNAcbdtVuKxCo0tW9HF0zqiERD8s2.i', '2022-12-15 02:19:55', 1),
(4, 'Test User', 'test@gkgkg.com', '9099999999', '$2y$10$AIWAY58tbIxTKl.fzsO1nOHReSorkO4tq.zIGBKJaQlUq0eeL4yvi', '2022-12-26 21:30:20', 1),
(5, 'Test User', 'test1@gkgkg.com', '9099999888', '$2y$10$YDXgT2RhND7V18QHvt5mXObv9Jik9TZrfV9rSu9qrVHBKqRJ0tJiS', '2022-12-26 21:33:15', 1),
(6, 'Sachin', 'sachin@example.com', '9898659865', '$2y$10$PAVy6PGKUSF8KxNANHOReenuHmKx.dQljX2ibSokSwRKHnes6lum.', '2022-12-31 15:49:10', 1),
(7, 'Mitesh', 'mitesh@example.com', '8754875487', '$2y$10$NGk2Bp.C4874efjRBTUmBOKOCi.IfkaPqJScsL.l49/GThLqFepyW', '2022-12-31 18:56:20', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_master`
--
ALTER TABLE `admin_master`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `products_master`
--
ALTER TABLE `products_master`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_supplier_mapping`
--
ALTER TABLE `product_supplier_mapping`
  ADD PRIMARY KEY (`product_supplier_mapping_id`);

--
-- Indexes for table `suppliers_master`
--
ALTER TABLE `suppliers_master`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users_master`
--
ALTER TABLE `users_master`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_master`
--
ALTER TABLE `admin_master`
  MODIFY `admin_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products_master`
--
ALTER TABLE `products_master`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_supplier_mapping`
--
ALTER TABLE `product_supplier_mapping`
  MODIFY `product_supplier_mapping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `suppliers_master`
--
ALTER TABLE `suppliers_master`
  MODIFY `supplier_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_master`
--
ALTER TABLE `users_master`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
