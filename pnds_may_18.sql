-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2018 at 07:58 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pnds_may_18`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_code` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `gst_number` varchar(100) NOT NULL,
  `bank_accnt_name` varchar(100) NOT NULL,
  `bank_accnt_no` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_ifsc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `creator_username`, `company_name`, `branch_name`, `branch_code`, `city`, `address`, `email`, `phone_number`, `registration_number`, `gst_number`, `bank_accnt_name`, `bank_accnt_no`, `bank_name`, `bank_ifsc`) VALUES
(1, 'test', 'ad', 'ada', 'ad', 'ad', 'Ashanagar\nBihar Sharif Sohsarai\nNalanda@803118', 'ad@ad.ad', '89545', 'ad852', 'sa', 'Aditya Suman', '541df65sd', 'SBI', 'SBIN0017164');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `pan` varchar(100) NOT NULL,
  `gst` varchar(100) NOT NULL,
  `shipping_address` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `balance` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `creator_username`, `creator_branch_code`, `name`, `company_name`, `email`, `mobile`, `address`, `pan`, `gst`, `shipping_address`, `type`, `balance`, `date`) VALUES
(1, 'user', 'ad', 'Aditya', 'MNgo', 'adityasuman2025@gmail.com', '7424947945', 'B-619 Boys Hostel\nIIT Patna\nBihta\nPatna Bihar', '485', 'dasf', 'B-619 Boys Hostel\nIIT Patna\nBihta\nPatna Bihar', '', '', '2018-06-05 08:26:37'),
(8, 'user', 'ad', 'Aman', 'Bhemu', 'adarsh3699@gmail.com', '7424947945', 'Ashanagar\nBihar Sharif\nSohsarai\nNalanda - 803118', '746543', '645', 'Ashanagar\nBihar Sharif\nSohsarai\nNalanda - 803118', '', '', '2018-07-06 08:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `brand`, `model_name`, `model_number`, `hsn_code`, `description`, `type`, `date`) VALUES
(1, 'Apple', 'laptop', 'Macbook', '5s9d1', 'good laptop', 'product', '2018-06-05 08:29:49'),
(5, 'Epson', 'Printer', 'PEd99874', 'GSN8879', 'Printer Header', 'part', '2018-06-09 19:41:10'),
(9, 'Dell', 'Service charge', '', '', '', 'service', '2018-06-18 14:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `quotation_num` int(255) NOT NULL,
  `note` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `invoice_num` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_num` varchar(100) NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `rate` varchar(100) NOT NULL,
  `cgst` varchar(100) NOT NULL,
  `sgst` varchar(100) NOT NULL,
  `igst` varchar(100) NOT NULL,
  `total_amount` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `quotation_num` int(255) NOT NULL,
  `serial` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `customer_company` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `serial_num` varchar(100) NOT NULL,
  `service_id` varchar(100) NOT NULL,
  `advance` varchar(100) NOT NULL,
  `purchase_order` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `rate` varchar(100) NOT NULL,
  `discount` varchar(100) NOT NULL,
  `cgst` varchar(100) NOT NULL,
  `sgst` varchar(100) NOT NULL,
  `igst` varchar(100) NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `date_of_payment` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `customer_company` varchar(100) NOT NULL,
  `invoice_num` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `return_note` varchar(1000) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `part_number` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `quantity` varchar(100) NOT NULL,
  `sales_price` varchar(100) NOT NULL,
  `supplier_price` varchar(100) NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `in_stock` varchar(100) NOT NULL,
  `sold` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `details` varchar(1000) NOT NULL,
  `name_of_product` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `serv_tax_no` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `branch_code` varchar(100) NOT NULL,
  `isadmin` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `serv_tax_no`, `address`, `branch_code`, `isadmin`) VALUES
(1, 'test', 'adityasuman2025@gmail.com', 'f925916e2754e5e03f75dd58a5733251', 'ad', 'IIT Patna,  bihta', 'ad', '1'),
(17, 'user', 'qw', 'ba5ef51294fea5cb4eadea5306f3ca3b', 'qw', 'qw', 'ad', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
