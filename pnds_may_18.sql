-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 25, 2018 at 09:57 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
(1, 'test', 'ad', 'ada', 'ad', 'ad', 'ad#ada#adaa#india@803118', 'ad@ad.ad', '89545', 'ad852', 'sa', 'da', '', '', ''),
(2, 'test', 'Volta Tech Talasery', 'Talasery', 'TLY01', 'Talasery', 'Pinaraymuttam\nKaivettu Kunnu\nTalesery P.O\nKannur, Kerala', 'sachin@salesboys.in', '080556478', 'CIN9968445217556', 'GST00987665T', 'Account Name: Voltatech Solutions Private Limited, Account Numbe:8896532548, YesBank, IFC: 009Y76', '1', '2', '3'),
(3, 'test', 'hello', 'hello', 'hello', 'world', 'hello#world', 'hello@gh.in', '89562', 'wds', 'das95', 'sax', 'wqewd', 'sads', 'ads'),
(4, 'test', 'Volta Tech Solutions', 'Test17', 'Test17', 'Bangalore', 'Tetimangala#\nKannuthumchalu#\nKalppakonakam#', 'test17@gmail.com', '6688548226', 'REG44566T67', 'GST55486212', 'Volta Tech Solutions', '66895542136', 'IDBIDI', 'ID6678'),
(5, 'test', 'Test18', 'Volta18', 'T18', 'Texasa', 'Thamarasery Churam#\nPappannakkodeu#\nMarappatti#', 'sa@mail.com', '88659987', 'RE5589741', 'GS889654', 'Volta Team 18', '002445897562', 'IDBIB', 'ID558954'),
(6, 'test', 'Volta Tech Solutions', 'Test19', 'T19', 'Bangalore', 'BTM 1st Stage#\nThavarakara#\nBangalore#', '', '995586632', 'REG558977562', 'GST7789542', 'Volta Tech', '66985547892', 'CIDIDI', 'DBI789521'),
(7, 'test', 'Volta Tech Solutions', 'Bangalore', 'BA1', 'Bangalore', 'BTM Stage 1 #\nThavarakara #\nBangalore #', 'Bangalore@volta.in', '0805598642', 'Reg88765543', 'Gst999999', 'Volta Tech Solutions', '9986532245', 'ICICI', 'IF998665');

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
(1, 'user', 'ad', 'Aditya', 'MNgo', 'adityasuman2025@gmail.com', '7424947945', 'B-619#Boys Hostel#IIT Patna#Bihta#Patna Bihar', '485', 'dasf', 'B-619#Boys Hostel#IIT Patna#Bihta#Patna Bihar 801103', '', '', '2018-06-05 08:26:37'),
(2, 'Sachin', 'HO', 'Santhosh', 'Santhoor and Santhosh Limited', 'sa@gmail.com', '88655420', 'Tekkottu Kara Plaza\nKozhikody mattam\nPuliyarakonum Po\nParalivattam\nPin: 647825', '89876TTYU', 'GST66578HY', 'Tekkottu Kara PlazaKozhikody mattamPuliyarakonum PoParalivattamPin: 647825', '', '', '2018-06-06 07:38:34'),
(3, 'Karuna', 'TLY01', 'Thankappan', 'Ponnappan and sons Limitted', 'Justin@oxyplant.in', '6658422035', 'Kudiyanmala#\nThankamma doodu#\nPonnappan Via#\nKilimangala#', 'PANNAHI', 'GST998776TY77', 'KudiyanmalaThankamma dooduPonnappan ViaKilimangala', '', '', '2018-06-09 20:05:15'),
(4, 'Test17', 'Test17', 'Test Customer', 'Test Company', 'testc@mail.com', '5568774521', 'Asianet Limited#\nPuliyanakollam#\nPanoor Pin:689561#', 'PAN8895648', 'GST558974', 'Asianet Limited#\nPuliyanakollam#\nPanoor Pin:689561#', '', '', '2018-06-17 18:22:39'),
(5, 'user18', 'T18', 'Gautam', 'Salesboys', 'Gautam@salesboys.in', '9665582652', '108, Vainavi Developers #\n4th Cross, Duo Heights Layout#\nBengaluru, Karnataka 560068#', 'PAN88765', 'GST5589762', '108, Vainavi Developers #\n4th Cross, Duo Heights Layout#\nBengaluru, Karnataka 560068#', '', '', '2018-06-21 16:16:42'),
(6, 'test19', 'T19', 'Test19customer', 'Test19', 'sachin@salesboys.in', '336658875', 'Vainavai Nest#\nDuohietgs Layout#\nBangalore, 67099#', 'PAN665788', 'GST556', 'Vainavai Nest#\nDuohietgs Layout#\nBangalore, 67099#', '', '', '2018-06-23 04:59:35'),
(7, 'Userb', 'BA1', 'Srini', 'Srini Limited', 'Srini@mail.com', '556998542', 'Calicut Thane #\nMangalasery Mana#\nTrivandrum#\nKerala, 986527#', 'PAN 0098776', 'GST889776', 'Calicut Thane #\nMangalasery Mana#\nTrivandrum#\nKerala, 986527#', '', '', '2018-06-25 06:23:12');

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
(3, 'Epson', 'Printer', 'LX-310', '84433220', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'product', '2018-06-06 07:36:13'),
(4, 'Dell', 'Service Charge', '', '', '', 'service', '2018-06-07 09:08:08'),
(5, 'Epson', 'Printer', 'PEd99874', 'GSN8879', 'Printer Header', 'part', '2018-06-09 19:41:10'),
(6, 'Hoenybee', 'Rum', 'XXX', 'HSNXXX777555', 'Honeybee Rum', 'product', '2018-06-09 20:00:33'),
(7, 'Dell', 'Product', '', '', '', 'service', '2018-06-17 18:16:55'),
(8, 'HP', 'Catridge', '668954', 'HSN778965', 'HP Printer Catridge', 'part', '2018-06-17 18:18:03'),
(9, 'Dell', 'Service charge', '', '', '', 'service', '2018-06-18 14:02:21'),
(10, 'MH', 'Brandy', 'Bra00587', 'Bra889865', 'Cheap Brandi', 'product', '2018-06-21 16:11:05'),
(11, '', 'Call Taxi', '', '', '', 'service', '2018-06-21 16:11:49'),
(12, 'HP', 'Service Charge', '', '', '', 'service', '2018-06-23 04:53:46'),
(13, 'HP', 'Laptop', 'H01', 'HSN01', 'New Laptop', 'product', '2018-06-25 06:18:56'),
(14, 'Dell', 'Service Charge', '', '', '', 'service', '2018-06-25 06:19:28');

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

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `creator_username`, `creator_branch_code`, `supplier`, `date`, `invoice_num`, `brand`, `model_name`, `model_num`, `hsn_code`, `description`, `type`, `quantity`, `rate`, `cgst`, `sgst`, `igst`, `total_amount`) VALUES
(4, 'user', 'ad', 'Bhemu', '2018-06-07', 'sadf', 'Apple', 'laptop', 'Macbook', '5s9d1', 'good laptop', 'product', '5000', '10', '0', '0', '0', '50000'),
(5, 'test19', 'T19', 'HP', '2018-06-23', 'Hp00987', 'Hoenybee', 'Rum', 'XXX', 'HSNXXX777555', 'Honeybee Rum', 'product', '10000', '4500', '5', '0', '0', '47250000'),
(6, 'Userb', 'BA1', 'HP', '2018-06-25', '559864', 'HP', 'Laptop', 'H01', 'HSN01', 'New Laptop', 'product', '10', '10000', '10', '0', '0', '110000'),
(7, 'Userb', 'BA1', 'HP', '2018-06-25', '669854', 'HP', 'Laptop', 'H01', 'HSN01', 'New Laptop', 'product', '10', '15000', '0', '0', '0', '150000');

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
  `description` varchar(100) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `serial_num` varchar(100) NOT NULL,
  `service_id` varchar(100) NOT NULL,
  `part_name` varchar(100) NOT NULL,
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
  `date_of_payment` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`id`, `creator_username`, `creator_branch_code`, `quotation_num`, `serial`, `description`, `customer`, `date`, `brand`, `model_name`, `model_number`, `serial_num`, `service_id`, `part_name`, `purchase_order`, `quantity`, `rate`, `discount`, `cgst`, `sgst`, `igst`, `hsn_code`, `total_price`, `type`, `payment_method`, `date_of_payment`) VALUES
(1, 'user', 'ad', 1, '1', 'good laptop', 'Aditya', '2018-06-05', 'Apple', 'laptop', 'Macbook', '', 'asd', '', '', '1', '100000', '', '1', '2', '3', '5s9d1', '106000', '', '', '0000-00-00 00:00:00'),
(2, 'user', 'ad', 2, '1', 'good laptop', 'Aditya', '2018-06-05', 'Apple', 'laptop', 'Macbook', 'sds', 'ad541', '', 'd41', '1', '90000', '', '1', '2', '3', '5s9d1', '95400', '', 'Credit', '2018-06-05 08:33:39'),
(3, 'Sachin', 'HO', 3, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Santhosh', '2018-06-06', 'Epson', 'Printer', 'LX-310', '', '', '', '', '10', '1000', '', '10', '5', '6', '84433220', '12100', '', '', '0000-00-00 00:00:00'),
(4, 'Sachin', 'HO', 4, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Santhosh', '2018-06-06', 'Epson', 'Printer', 'LX-310', '', '', '', '', '1', '1000', '', '5', '12', '0', '84433220', '1170', '', 'Cash', '2018-06-06 07:50:17'),
(5, 'Machu', 'ad', 5, '1', 'ttyy', 'Aditya', '2018-06-06', 'Apple', 'laptop', 'Macbook', '77', '', '', '77', '1', '100', '', '10', '0', '0', '', '110', 'sales', 'Cash', '2018-06-06 20:16:13'),
(6, 'user', 'ad', 6, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-07', 'Epson', 'Printer', 'LX-310', '', '', '', '', '1', '1000', '', '0', '0', '0', '84433220', '1000', 'product', 'Net', '2018-06-21 01:50:47'),
(7, 'user', 'ad', 6, '2', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-07', 'Epson', 'Printer', 'LX-310', '', '', '', '', '1', '1000', '', '0', '0', '0', '84433220', '1000', 'product', 'Net', '2018-06-21 01:50:47'),
(8, 'user', 'ad', 7, '1', 'good laptop', 'Aditya', '2018-06-07', 'Apple', 'laptop', 'Macbook', '', '', '', '', '10', '0', '', '0', '0', '0', '5s9d1', '0', 'product', 'Card', '2018-06-07 08:34:14'),
(9, 'user', 'ad', 7, '2', 'good laptop', 'Aditya', '2018-06-07', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '', '0', '0', '0', '5s9d1', '0', 'product', 'Card', '2018-06-07 08:34:14'),
(11, 'Machu', 'ad', 8, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-07', 'Epson', 'Printer', 'LX-310', '', '1', '', '', '1', '1000', '', '1', '2', '3', '84433220', '1060', 'product', 'Net', '2018-06-16 10:43:33'),
(12, 'user', 'ad', 9, '1', 'good laptop', 'Aditya', '2018-06-09', 'Apple', 'laptop', 'Macbook', '', '', '', '', '1', '1000', '', '1', '2', '3', '5s9d1', '1060', 'product', 'Net', '2018-06-19 09:31:42'),
(13, 'user', 'ad', 9, '2', '', 'Aditya', '2018-06-09', 'Dell', 'Service Charge', '', '', '', '', '', '0', '1000', '', '1', '2', '3', '', '0', 'service', 'Net', '2018-06-19 09:31:42'),
(14, 'user', 'ad', 10, '2', 'good laptop', 'Aditya', '2018-06-09', 'Apple', 'laptop', 'Macbook', 'sdf', 'd64512', '', '', '5', '100', '10', '1', '2', '3', '5s9d1', '477', 'product', 'Card', '2018-06-09 18:05:40'),
(15, 'user', 'ad', 10, '1', 'good laptop', 'Aditya', '2018-06-09', 'Apple', 'laptop', 'Macbook', '68645120', '5123', '', '', '5', '100', '10', '1', '2', '3', '5s9d1', '477', 'product', 'Card', '2018-06-09 18:05:40'),
(17, 'Karuna', 'TLY01', 12, '1', 'Honeybee Rum with picke', 'Thankappan', '2018-06-09', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '2', '50', '', '10', '20', '0', 'HSNXXX777555', '130', 'product', '', '0000-00-00 00:00:00'),
(18, 'Karuna', 'TLY01', 12, '2', 'Water Service dell workstation', 'Thankappan', '2018-06-09', 'Dell', 'Service Charge', '', '', '', '', '', '0', '0', '', '0', '0', '0', '', '0', 'service', '', '0000-00-00 00:00:00'),
(19, 'Karuna', 'TLY01', 13, '1', 'Honeybee Rum', 'Thankappan', '2018-06-09', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '0', '0', '', '0', '0', '0', 'HSNXXX777555', '0', 'product', '', '0000-00-00 00:00:00'),
(20, 'Karuna', 'TLY01', 14, '1', 'Honeybee Rum', 'Thankappan', '2018-06-09', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '4', '50', '5', '12', '0', '0', 'HSNXXX777555', '212.8', 'product', 'Cash', '2018-06-09 20:32:53'),
(21, 'Karuna', 'TLY01', 15, '1', 'Honeybee Rum', 'Thankappan', '2018-06-10', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '1', '100', '0', '10', '0', '0', 'HSNXXX777555', '110', 'product', 'Cash', '2018-06-10 18:34:25'),
(22, 'Testuser', 'TLY01', 16, '1', '', 'Thankappan', '2018-06-15', 'Dell', 'Service Charge', '', '', '', '', '', '0', '0', '', '0', '0', '0', '', '0', 'service', '', '0000-00-00 00:00:00'),
(23, 'Testuser', 'TLY01', 17, '1', 'good laptop', 'Thankappan', '2018-06-15', 'Apple', 'laptop', 'Macbook', '', '', '', '', '1', '200', '', '5', '0', '0', '5s9d1', '210', 'product', '', '0000-00-00 00:00:00'),
(25, 'user', 'ad', 8, '2', 'Honeybee Rum', 'Aditya', '2018-06-07', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '0', 'NaN', '', 'NaN', 'NaN', 'NaN', 'HSNXXX777555', 'NaN', 'product', 'Net', '2018-06-16 10:43:33'),
(27, 'Test17', 'Test17', 18, '1', 'HP Printer Catridge', 'Test Customer', '2018-06-17', 'HP', 'Catridge', '668954', '', '', '', '', '1', '1500', '', '10', '9', '0', 'HSN778965', '1785', 'part', 'Card', '2018-06-20 04:28:42'),
(28, 'Test17', 'Test17', 18, '2', '', 'Test Customer', '2018-06-17', 'Dell', 'Service Charge', '', '', '', '', '', '1', '1800', '', '20', '0', '0', '', '2160', 'service', 'Card', '2018-06-20 04:28:42'),
(29, 'Test17', 'Test17', 19, '2', 'HP Printer Catridge', 'Test Customer', '2018-06-17', 'HP', 'Catridge', '668954', '445678', '', '', '', '1', '1000', '0', '0', '10', '0', 'HSN778965', '1100', 'part', 'Cash', '2018-06-17 18:39:56'),
(30, 'Test17', 'Test17', 19, '1', 'Serviced the header', 'Test Customer', '2018-06-17', 'Dell', 'Service Charge', '', '445678', 'SR:77686', '', 'PO:334567', '1', '1800', '10', '10', '9', '0', '', '1927.8', 'service', 'Cash', '2018-06-17 18:39:56'),
(31, 'Test17', 'Test17', 20, '1', 'Honeybee Rum', 'Test Customer', '2018-06-17', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '1', '100', '', '0', '10', '0', 'HSNXXX777555', '110', 'product', '', '0000-00-00 00:00:00'),
(32, 'Test17', 'Test17', 21, '1', 'HP Printer Catridge', 'Test Customer', '2018-06-17', 'HP', 'Catridge', '668954', '', '', '', '', '1', '4589', '', '10', '0', '0', 'HSN778965', '5047.9', 'part', '', '0000-00-00 00:00:00'),
(33, 'user', 'ad', 22, '1', '', 'Aditya', '2018-06-21', 'Dell', 'Service Charge', '', '', '', '', '', '7', '0', '0', '0', '0', '0', '', '0', 'service', 'Card', '2018-06-21 01:21:55'),
(36, 'user', 'ad', 6, '3', '', 'Aditya', '2018-06-07', 'Dell', 'Product', '', '', '', '', '', '1', '2000', '', '1', '2', '3', '', '2120', 'service', 'Net', '2018-06-21 01:50:47'),
(37, 'user18', 'T18', 23, '1', 'Honeybee Rum', 'Gautam', '2018-06-21', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '5', '450', '', '10', '0', '0', 'HSNXXX777555', '2475', 'product', 'Card', '2018-06-22 07:55:48'),
(38, 'user18', 'T18', 23, '2', 'Picked customer from bar and dropped him at home', 'Gautam', '2018-06-21', '', '', '', '', '', '', '', '0', '1000', '', '0', '20', '0', '', '0', 'service', 'Card', '2018-06-22 07:55:48'),
(40, 'user18', 'T18', 23, '3', 'Cheap Brandi', 'Gautam', '2018-06-21', 'MH', 'Brandy', 'Bra00587', '', '', '', '', '2', '500', '', '10', '10', '10', 'Bra889865', '1300', 'product', 'Card', '2018-06-22 07:55:48'),
(41, 'user18', 'T18', 24, '1', 'hello taxi', 'Gautam', '2018-06-21', '', 'Call Taxi', '', '', '', '', '', '5', '1000', '', '1', '2', '3', '', '5300', 'service', 'Cash', '2018-06-21 17:17:20'),
(42, 'user18', 'T18', 25, '1', 'good laptop', 'Gautam', '2018-06-22', 'Apple', 'laptop', 'Macbook', '', '', '', '', '1', '1000', '', '12', '3', '3', '5s9d1', '1180', 'product', '', '0000-00-00 00:00:00'),
(43, 'user', 'ad', 26, '1', 'good laptop', 'Aditya', '2018-06-22', 'Apple', 'laptop', 'Macbook', '', '', '', '', '1', '1000', '', '1', '2', '3', '5s9d1', '1060', 'product', 'Card', '2018-06-22 08:03:57'),
(44, 'user', 'ad', 27, '1', 'good laptop', 'Aditya', '2018-06-22', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '', '0', '0', '0', '5s9d1', '0', 'product', '', '0000-00-00 00:00:00'),
(45, 'user', 'ad', 28, '1', 'Printer Header', 'Aditya', '2018-06-22', 'Epson', 'Printer', 'PEd99874', '', '', '', '', '0', '0', '', '0', '0', '0', 'GSN8879', '0', 'part', '', '0000-00-00 00:00:00'),
(46, 'user', 'ad', 29, '1', 'good laptop', 'Aditya', '2018-06-22', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '0', '0', '0', '0', '5s9d1', '0', 'product', 'Cash', '2018-06-22 08:10:59'),
(47, 'user', 'ad', 30, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-22', 'Epson', 'Printer', 'LX-310', '', '', '', '', '0', '0', '', '0', '0', '0', '84433220', '0', 'product', '', '0000-00-00 00:00:00'),
(48, 'user', 'ad', 31, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-22', 'Epson', 'Printer', 'LX-310', '', '', '', '', '0', '0', '', '0', '0', '0', '84433220', '0', 'product', '', '0000-00-00 00:00:00'),
(49, 'user', 'ad', 32, '1', 'Epson LX-310 Impact Dot Matrix Printer..........................................tttyu', 'Aditya', '2018-06-22', 'Epson', 'Printer', 'LX-310', '', '', '', '', '0', '0', '0', '0', '0', '0', '84433220', '0', 'product', 'Credit', '2018-06-22 08:18:37'),
(50, 'test19', 'T19', 33, '1', 'Service Charge for servicing dell laptop', 'Test19customer', '2018-06-23', '', '', '', '', '', '', '', '1', '3000', '', '0', '0', '0', '', '3000', 'service', 'Cash', '2018-06-23 05:05:50'),
(51, 'test19', 'T19', 33, '2', 'Honeybee Rum', 'Test19customer', '2018-06-23', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '12', '5000', '', '0', '4', '0', 'HSNXXX777555', '62400', 'product', 'Cash', '2018-06-23 05:05:50'),
(52, 'test19', 'T19', 33, '3', 'Honeybee Rum', 'Test19customer', '2018-06-23', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '2', '2000', '', '0', '5', '0', 'HSNXXX777555', '4200', 'product', 'Cash', '2018-06-23 05:05:50'),
(53, 'user', 'ad', 34, '1', 'good laptop', 'Aditya', '2018-06-23', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '', '0', '0', '0', '5s9d1', '0', 'product', '', '0000-00-00 00:00:00'),
(54, 'user', 'ad', 35, '1', 'good laptop', 'Aditya', '2018-06-23', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '0', '0', '0', '0', '5s9d1', '0', 'product', 'Card', '2018-06-23 09:59:33'),
(55, 'user', 'ad', 36, '1', 'HP Printer Catridge', 'Aditya', '2018-06-23', 'HP', 'Catridge', '668954', '', '', '', '', '0', '0', '', '0', '0', '0', 'HSN778965', '0', 'part', '', '0000-00-00 00:00:00'),
(56, 'user', 'ad', 37, '1', 'good laptop', 'Aditya', '2018-06-23', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '', '0', '0', '0', '5s9d1', '0', 'product', '', '0000-00-00 00:00:00'),
(57, 'user', 'ad', 38, '1', 'good laptop', 'Aditya', '2018-06-23', 'Apple', 'laptop', 'Macbook', '', '', '', '', '0', '0', '', '0', '0', '0', '5s9d1', '0', 'product', '', '0000-00-00 00:00:00'),
(58, 'user', 'ad', 39, '1', '', 'Aditya', '2018-06-23', '', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '', '0', 'product', 'Net', '2018-06-23 10:52:47'),
(59, 'user', 'ad', 40, '1', '', 'Aditya', '2018-06-23', '', '', '', '', '', '', '', '0', '0', '', '0', '0', '0', '', '0', '', '', '0000-00-00 00:00:00'),
(60, 'user', 'ad', 41, '1', '', 'Aditya', '2018-06-23', '', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '', '0', '', 'Card', '2018-06-23 10:58:12'),
(61, 'user18', 'T18', 42, '1', 'Honeybee Rum', 'Gautam', '2018-06-24', 'Hoenybee', 'Rum', 'XXX', '', '', '', '', '1', '100', '', '0', '0', '0', 'HSNXXX777555', '100', 'product', '', '0000-00-00 00:00:00'),
(62, 'user18', 'T18', 42, '2', 'Servicing a bad laptop to make it a good laptop. The laptops\'s head and abtery was replaced', 'Gautam', '2018-06-24', 'Dell', 'Product', '', '', '', '', '', '1', '1000', '', '10', '0', '0', '', '1100', 'service', '', '0000-00-00 00:00:00'),
(63, 'Userb', 'BA1', 43, '1', 'New Laptop', 'Srini', '2018-06-25', 'HP', 'Laptop', 'H01', 'SN8897765', '', '', '558964', '5', '15000', '', '18', '18', '0', 'HSN01', '102000', 'product', 'Cash', '2018-06-25 06:51:16'),
(64, 'Userb', 'BA1', 43, '2', 'HP Laptop was serviced', 'Srini', '2018-06-25', 'HP', 'Service Charge', '', '', 'S7767998', '', '', '1', '2000', '', '0', '0', '10', '', '2200', 'service', 'Cash', '2018-06-25 06:51:16'),
(65, 'Userb', 'BA1', 44, '1', 'New Laptop', 'Srini', '2018-06-25', 'HP', 'Laptop', 'H01', '', '', '', '', '1', '2000', '0', '0', '0', '0', 'HSN01', '2000', 'product', 'Credit', '2018-06-25 07:03:58'),
(66, 'Userb', 'BA1', 45, '1', 'New Laptop', 'Srini', '2018-06-25', 'HP', 'Laptop', 'H01', '', '', '', '', '1', '300', '0', '0', '0', '0', 'HSN01', '300', 'product', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(255) NOT NULL,
  `creator_username` varchar(100) NOT NULL,
  `creator_branch_code` varchar(100) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `invoice_num` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model_name` varchar(100) NOT NULL,
  `model_number` varchar(100) NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `part_number` varchar(100) NOT NULL,
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

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `creator_username`, `creator_branch_code`, `brand`, `model_name`, `model_number`, `part_name`, `part_number`, `type`, `quantity`, `sales_price`, `supplier_price`, `hsn_code`, `in_stock`, `sold`, `date`) VALUES
(5, 'user', 'ad', 'Apple', 'laptop', 'Macbook', '', '', 'product', '5000', '', '10', '5s9d1', '4978', '22', '2018-06-07 08:33:23'),
(6, 'Machu', 'ad', 'Epson', 'Printer', 'LX-310', '', '', 'product', '5', '', '', '84433220', '2', '3', '2018-06-09 19:41:51'),
(7, 'Karuna', 'TLY01', 'Hoenybee', 'Rum', 'XXX', '', '', 'product', '25', '120', '80', 'HSNXXX777555', '20', '5', '2018-06-09 20:02:17'),
(8, 'Testuser', 'TLY01', 'Dell', 'Service Charge', '', '', '', 'service', '1000', '', '', '', '1000', '0', '2018-06-15 17:38:14'),
(9, 'Test17', 'Test17', 'HP', 'Catridge', '668954', '', '', 'part', '100', '2500', '2000', 'HSN778965', '98', '2', '2018-06-17 18:19:30'),
(10, 'Test17', 'Test17', 'Dell', 'Service Charge', '', '', '', 'service', '100', '', '', '', '98', '2', '2018-06-17 18:20:07'),
(11, 'user18', 'T18', 'Hoenybee', 'Rum', 'XXX', '', '', 'product', '10', '', '', 'HSNXXX777555', '5', '5', '2018-06-21 16:13:00'),
(12, 'user18', 'T18', 'MH', 'Brandy', 'Bra00587', '', '', 'product', '55', '', '', 'Bra889865', '58', '7', '2018-06-21 16:13:39'),
(13, 'test19', 'T19', 'Hoenybee', 'Rum', 'XXX', '', '', 'product', '10000', '', '4500', 'HSNXXX777555', '9986', '14', '2018-06-23 04:57:19'),
(14, 'Userb', 'BA1', 'HP', 'Laptop', 'H01', '', '', 'product', '10', '', '10000', 'HSN01', '34', '6', '2018-06-25 06:27:06');

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

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `creator_username`, `creator_branch_code`, `name`, `email`, `mobile`, `address`, `details`, `name_of_product`) VALUES
(1, 'user', 'ad', 'Bhemu', 'bhemu@gmail.com', '9304902747', 'Bihar Sharif', 'kya pata', 'mango'),
(2, 'test19', 'T19', 'HP', 'hp@mail.com', '080556692', 'HP\nBangalore', 'HP\nBangalore', 'Printer, Laptop'),
(3, 'Userb', 'BA1', 'HP', 'hp@mail.com', '8865584', 'Hp\nBangalore', 'Hp\nBangalore', 'HP laptop');

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
(17, 'user', 'qw', 'ba5ef51294fea5cb4eadea5306f3ca3b', 'qw', 'qw', 'ad', '0'),
(18, 'user1', 'ad@ad.in', '24c9e15e52afc47c225b757e7bee1f9d', '', '', 'ad', '1'),
(19, 'Machu', 'tt@gmail.com', '6426e668ef2de95553d0760c620ee6de', '', '', 'ad', '0'),
(20, 'Karuna', 'gautam@salesboys.in', 'f4acdd0e41c6c487c34633c106f0385b', '', '', 'TLY01', '0'),
(21, 'Testuser', 'Sachin@salesboys.in', 'fa9cafb1322b5438a98f68ca936959b3', '', '', 'TLY01', '0'),
(22, 'Test17', 'test17@mail.com', '95174ccdc9cdad115f347a06e216684f', '', '', 'Test17', '0'),
(23, 'user18', 'sachin@salesboys.in', '7ac18a1893e1d2bd5b46958ce1d2a8d0', '', '', 'T18', '0'),
(24, 'Test19admin', 'test19admin@mail.com', '4cfc3a71f3ad315e7f5640192438d2e4', '', '', 'T18', '1'),
(25, 'test19', 'test19@mail.com', '45357a5c731751a44000d1ba2c0e25fb', '', '', 'T19', '0'),
(26, 'Userb', 'userb@mail.com', 'a501c9da548457b913a54d2d82d4799e', '', '', 'BA1', '0');

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
