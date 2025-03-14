-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 06:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myapp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `approved_orders`
--

CREATE TABLE `approved_orders` (
  `approved_order_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `delivery_location` varchar(255) DEFAULT NULL,
  `apartment_number` varchar(100) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approved_orders`
--

INSERT INTO `approved_orders` (`approved_order_id`, `order_id`, `cust_id`, `first_name`, `last_name`, `phone_number`, `delivery_location`, `apartment_number`, `order_date`, `approved_at`) VALUES
(18, 22, 2, 'he', 'he', '078888888888', 'hhhhhhhhhhhhhh', 'hhh', '2025-03-13 12:31:06', '0000-00-00 00:00:00'),
(21, 25, 1, 'Sameer', 'Munir', '0727590770', 'Nairobi', 'Wambugu lane, Office Suites', '2025-03-14 08:59:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `cylinder_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cust_id` int(11) NOT NULL,
  `cust_name` varchar(100) NOT NULL,
  `cust_email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cust_id`, `cust_name`, `cust_email`, `password`, `created_at`, `reset_token`) VALUES
(1, 'Sam', 'sam1@gmail.com', '$2y$10$mzGKkEWRKK.Hu6.pc8nDMONT2ef.zrbuQ4tv9kljc6VL1zuXIJn4G', '2025-03-10 12:16:46', NULL),
(2, 'John', 'john12@gmail.com', '$2y$10$0vIfPhqUDqQ4V23J36.2gextC0H3fhx6nY9iwxO9cOFVPUXwVp.rG', '2025-03-11 06:11:20', NULL),
(3, 'me', 'me1@gmail.com', '$2y$10$zPCWHlImhXfUSdE38DdUk.7yS27G9aqR1QlywMd.te7r8NtvkShP2', '2025-03-12 11:40:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE `customer_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `delivery_location` varchar(255) NOT NULL,
  `apartment_number` varchar(100) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_hidden` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `funds_deductions`
--

CREATE TABLE `funds_deductions` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `deduction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `funds_deductions`
--

INSERT INTO `funds_deductions` (`id`, `purchase_id`, `amount`, `deduction_date`, `note`) VALUES
(1, 4, 0.00, '2025-03-14 05:31:41', 'Deduction for purchasing 1 units of 6kg at KES 0.00 each.'),
(2, 5, 1800.00, '2025-03-14 05:39:17', 'Deduction for purchasing 2 units of 6kg at KES 900.00 each.'),
(3, 6, 18000.00, '2025-03-14 05:59:04', 'Deduction for purchasing 20 units of 6kg at KES 900.00 each.'),
(4, 7, 1800.00, '2025-03-14 06:24:49', 'Deduction for purchasing 3 units of 6kg at KES 600.00 each.'),
(5, 8, 1400.00, '2025-03-14 06:25:33', 'Deduction for purchasing 2 units of 6kg at KES 700.00 each.'),
(6, 9, 12000.00, '2025-03-14 08:18:34', 'Deduction for purchasing 20 units of 6kg at KES 600.00 each.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `cust_name` varchar(255) NOT NULL,
  `cust_contact` varchar(100) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `cust_id`, `cust_name`, `cust_contact`, `order_date`) VALUES
(21, 3, '', '', '2025-03-13 06:48:49'),
(22, 2, '', '', '2025-03-13 09:31:06'),
(23, 1, '', '', '2025-03-14 04:14:54'),
(24, 2, '', '', '2025-03-14 05:43:38'),
(25, 1, '', '', '2025-03-14 05:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `cylinder_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `cylinder_id`, `quantity`, `price`) VALUES
(12, 21, 1, 1, '1200.00'),
(13, 21, 1, 1, '1200.00'),
(14, 21, 1, 1, '1200.00'),
(15, 22, 1, 1, '1200.00'),
(16, 22, 1, 1, '1200.00'),
(17, 22, 1, 1, '1200.00'),
(18, 23, 1, 1, '1200.00'),
(19, 23, 2, 1, '2300.00'),
(20, 24, 2, 1, '2300.00'),
(21, 24, 2, 1, '2300.00'),
(22, 24, 2, 1, '2300.00'),
(23, 24, 1, 1, '1200.00'),
(24, 24, 1, 1, '1200.00'),
(25, 24, 1, 1, '1200.00'),
(26, 25, 1, 1, '1200.00'),
(27, 25, 2, 1, '2300.00');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_funds`
--

CREATE TABLE `procurement_funds` (
  `id` int(11) NOT NULL,
  `allocated_amount` decimal(10,2) NOT NULL,
  `used_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allocated_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `procurement_funds`
--

INSERT INTO `procurement_funds` (`id`, `allocated_amount`, `used_amount`, `allocated_date`, `note`) VALUES
(1, 1000000.00, 0.00, '2025-03-14 05:15:16', 'Tight budget'),
(2, 1000.00, 0.00, '2025-03-14 05:43:46', 'Tight budget'),
(3, 200.00, 0.00, '2025-03-14 08:16:24', '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` int(11) NOT NULL,
  `cylinder_id` int(11) DEFAULT NULL,
  `purchase_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_history`
--

CREATE TABLE `purchase_history` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchased_by` int(11) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_history`
--

INSERT INTO `purchase_history` (`id`, `supplier_id`, `product`, `quantity`, `purchased_by`, `purchase_date`, `status`) VALUES
(1, 2, '6kg', 10, 22, '2025-03-14 01:42:24', 'completed'),
(2, 3, '12kg', 12, 24, '2025-03-14 01:52:05', 'completed'),
(3, 2, '6kg', 23, 9, '2025-03-14 02:15:46', 'completed'),
(4, 1, '6kg', 1, 22, '2025-03-14 02:31:41', 'completed'),
(5, 2, '6kg', 2, 22, '2025-03-14 02:39:16', 'completed'),
(6, 2, '6kg', 20, 9, '2025-03-14 02:59:03', 'completed'),
(7, 3, '6kg', 3, 24, '2025-03-14 03:24:49', 'completed'),
(8, 1, '6kg', 2, 24, '2025-03-14 03:25:32', 'completed'),
(9, 3, '6kg', 20, 24, '2025-03-14 05:18:34', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `quantity_6kg` int(11) NOT NULL DEFAULT 0,
  `quantity_12kg` int(11) NOT NULL DEFAULT 0,
  `price_6kg` decimal(10,2) NOT NULL DEFAULT 16.00,
  `price_12kg` decimal(10,2) NOT NULL DEFAULT 30.00,
  `total_sales` decimal(10,2) GENERATED ALWAYS AS (`quantity_6kg` * `price_6kg` + `quantity_12kg` * `price_12kg`) STORED,
  `status` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `product` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_id`, `sale_date`, `quantity_6kg`, `quantity_12kg`, `price_6kg`, `price_12kg`, `status`, `total`, `product`, `quantity`, `assigned_to`) VALUES
(1, 1, '2025-01-15', 5, 3, 16.00, 30.00, 'Pending', 0.00, '', NULL, NULL),
(2, 2, '2025-02-12', 7, 4, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(3, 3, '2025-03-20', 10, 6, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(4, 4, '2025-04-05', 12, 8, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(5, 5, '2025-05-14', 9, 5, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(6, 6, '2025-06-22', 15, 10, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(7, 7, '2025-07-30', 14, 12, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(8, 8, '2025-08-08', 18, 15, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(9, 9, '2025-09-18', 22, 19, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(10, 10, '2025-10-25', 25, 21, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(11, 11, '2025-11-10', 30, 24, 16.00, 30.00, '', 0.00, '', NULL, NULL),
(12, 12, '2025-12-15', 28, 26, 16.00, 30.00, '', 0.00, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `size` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `quantity`, `price`, `size`, `image_url`) VALUES
(1, '89', 1200.00, '6kg', 'Images/6kg.jpg'),
(2, '45', 2300.00, '12kg', 'Images/12kg.avif');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cost_6kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cost_12kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(20) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `id`, `name`, `contact`, `address`, `email`, `created_at`, `cost_6kg`, `cost_12kg`, `phone`, `details`) VALUES
(4, 1, 'GasPro Solutions', '', 'Industrial -area, Isiolo, DRC', 'info@gaspro.com', '2025-03-14 01:32:23', 700.00, 1000.00, '123-456-7890', 'Reliable supplier of high-quality gas cylinders.'),
(5, 2, 'BlueFlame Distributors', '', '456 Wambugu Rd, Parklands, Kenya', 'contact@blueflame.com', '2025-03-14 01:32:23', 900.00, 1100.00, '234-567-8901', 'Leading distributor with competitive pricing.'),
(6, 3, 'EcoFuel Suppliers', '', '789 Moi-Avenue Ave, Kilifi, Kenya', 'sales@ecofuel.com', '2025-03-14 01:32:23', 600.00, 1900.00, '345-678-9012', 'Eco-friendly and sustainable gas solutions.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','sales','procurement','support','user') NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `reset_token`) VALUES
(1, 'jamal', 'jamal@gmail.com', '$2y$10$h6ywkkybYEPLkaPsuRGlH.hFCQo8um/5Ksx1uSX3bVYa4vUVz82TS', '2025-02-25 11:55:16', 'sales', NULL),
(2, 'Sameer Munir', 'sameermunid606@gmail.com', '$2y$10$2dN1PP1ZwLZomj6OIYP8V..eKaxeRCg9nXjhovv1pfRUhVSC.XsQy', '2025-02-27 11:11:00', 'admin', NULL),
(3, 'John', 'j@none.com', '$2y$10$uFQHJGn/Rtg8.YWagwVo8umOIGjej9Pm4FloFfD1ZamD.8iSsOt2m', '2025-02-28 06:41:26', 'sales', NULL),
(4, 'johnnie ngugi', 'johnwanjagi18@gmail.com', '$2y$10$4OY/erML8xYL0FM7diVNMOfL2E6lYPUl2m9Zkr6MANJj0BjuujxQC', '2025-02-28 08:11:58', 'procurement', '3d9f21531ff0dfce892e71e3390baef834507bff4b250c799d601b1e159456331b31fb7c4625eea5b6f92581790529583671'),
(5, 'mikaeel', 'mikaeelmoha123@gmail.com', '$2y$10$U/vK9W/emFbB4Zs0HmphU.EL7i9.GbGBzzyAjZY1rylwODZNFrHmC', '2025-02-28 08:14:14', 'user', '6d8495a5c024b0c122c63d8c1c3defb162157dbc9142f29d17d21fe8a46a584dba8d5088088f0d847082a192336a3a680737'),
(6, 'Athman Ali', '4athmanali@gmail.com', '$2y$10$ayT3AcnsXZ8aYA9g8I58J.oHSLvG3bhQ81SScGhxn3AmnkW0cWAd2', '2025-02-28 13:26:37', 'admin', 'b82f40aa7828367e8ef2be7774ab5c80bf2fead9d6f6187b22e8a018b65cd79fae02819b45b8a7a4bb4bfe9df1f51603726d'),
(8, 'Asman', 'asman@gmail.com', '$2y$10$pA93RzNztUfdH7F6wwXw7eTq.1m6Xgzh.6ISyQeodAzIsXBJwwUy6', '2025-03-03 09:37:31', 'sales', NULL),
(9, 'Elizabeth', 'Elizabeth@gmail.com', '$2y$10$M.dqquu0ggasqFt3iKXDRehYSVqscOdFlga49TI3SklTh9HUl0n/C', '2025-03-03 09:45:38', 'procurement', NULL),
(10, 'Anonymous', 'a@m.com', '$2y$10$hrrnphFcCtyIsCwkUl22o.3e9EGp1mkqxYtaGecU5mdwggChhuLTi', '2025-03-04 10:07:35', 'user', NULL),
(11, 'Moha', 'm@1.com', '$2y$10$TJ0h3uzrcROmP9xlOQ12pe4YGITtuM6/w8TRH6Tj.SHdci2zhYGeu', '2025-03-05 07:44:08', 'user', NULL),
(12, 'john', 'john@gmail.com', '$2y$10$YP0VJWT333JLafj6c8qYzeK07A6K9sp90lC.ZGfkF3BJUWup0V7Ly', '2025-03-05 10:42:58', 'sales', NULL),
(15, 'abdi', 'abdi@gmail.com', '$2y$10$DzKfe7MLG4Z5ZhG1WMCJWOyphvRwQYCcOEl/d5XFGX2GJij5dIi32', '2025-03-06 10:46:30', 'user', NULL),
(16, 'jamal', 'jamal@gmail.com', '$2y$10$SExGHfv0aYapGq4Y2SolAu/WN67zkoxGL.Sg7FsFCkSTA9U6n2Lvq', '2025-03-06 11:43:13', 'user', NULL),
(17, 'omar', 'omar@gmail.com', '$2y$10$s/YghSlrl7/c0.yKItPmnuzn2Hcg0or9gD.LXJv4B6nRCwOHCA5tO', '2025-03-06 11:44:40', 'user', NULL),
(18, 'JC', 'JC1@gmail.com', '$2y$10$d9.yuwzhRI.E5vxCA6RDbeUBlhcwy.wediXJJbXUSOlVB9yaD7OHm', '2025-03-10 06:59:25', 'procurement', NULL),
(19, 'jeff', 'mark1@gmail.com', '$2y$10$IVV5bdf2mens3JBD6Io4AebOi17asYYuD9BVP0KIgkv2/6B70EpXa', '2025-03-14 06:20:06', 'sales', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approved_orders`
--
ALTER TABLE `approved_orders`
  ADD PRIMARY KEY (`approved_order_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `cylinder_id` (`cylinder_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cust_id`),
  ADD UNIQUE KEY `cust_email` (`cust_email`);

--
-- Indexes for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `funds_deductions`
--
ALTER TABLE `funds_deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cust_id` (`cust_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `cylinder_id` (`cylinder_id`);

--
-- Indexes for table `procurement_funds`
--
ALTER TABLE `procurement_funds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `cylinder_id` (`cylinder_id`);

--
-- Indexes for table `purchase_history`
--
ALTER TABLE `purchase_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `purchased_by` (`purchased_by`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `cylinder_id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approved_orders`
--
ALTER TABLE `approved_orders`
  MODIFY `approved_order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_details`
--
ALTER TABLE `customer_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `funds_deductions`
--
ALTER TABLE `funds_deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `procurement_funds`
--
ALTER TABLE `procurement_funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_history`
--
ALTER TABLE `purchase_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`cylinder_id`) REFERENCES `stock` (`id`);

--
-- Constraints for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD CONSTRAINT `customer_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_details_ibfk_2` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customers` (`cust_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`cylinder_id`) REFERENCES `stock` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`cylinder_id`) REFERENCES `stock` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
