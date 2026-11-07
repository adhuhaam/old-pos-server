# Ithaa Corner POS


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2026 at 06:30 AM
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
-- Database: `cafe_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_categories`
--

CREATE TABLE `book_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_categories`
--

INSERT INTO `book_categories` (`id`, `name`, `sort_order`, `active`) VALUES
(1, 'Supply', 1, 1),
(2, 'Rent', 2, 1),
(3, 'Utility bills', 3, 1),
(4, 'Other', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_days`
--

CREATE TABLE `book_days` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_date` date NOT NULL,
  `transfer` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash` decimal(10,2) NOT NULL DEFAULT 0.00,
  `origin` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_days`
--

INSERT INTO `book_days` (`id`, `sale_date`, `transfer`, `cash`, `origin`) VALUES
(1, '2026-07-26', 34.00, 0.00, 'previous'),
(2, '2026-07-27', 194.00, 0.00, 'previous'),
(3, '2026-07-28', 377.00, 0.00, 'previous'),
(4, '2026-07-29', 659.00, 0.00, 'previous'),
(5, '2026-07-30', 605.00, 0.00, 'previous'),
(6, '2026-07-31', 696.00, 0.00, 'previous'),
(7, '2026-08-01', 840.00, 0.00, 'previous'),
(8, '2026-08-02', 678.00, 0.00, 'previous'),
(9, '2026-08-03', 832.00, 0.00, 'previous'),
(10, '2026-08-04', 786.00, 0.00, 'previous'),
(11, '2026-08-05', 968.00, 0.00, 'previous'),
(12, '2026-08-06', 674.00, 0.00, 'previous'),
(13, '2026-08-07', 547.00, 0.00, 'previous'),
(14, '2026-08-08', 780.00, 0.00, 'previous'),
(15, '2026-08-09', 780.00, 0.00, 'previous'),
(16, '2026-08-10', 736.00, 0.00, 'previous'),
(17, '2026-08-11', 625.00, 0.00, 'previous'),
(18, '2026-08-12', 998.00, 0.00, 'previous'),
(19, '2026-08-13', 895.00, 0.00, 'previous'),
(20, '2026-08-14', 647.00, 0.00, 'previous'),
(21, '2026-08-15', 946.00, 0.00, 'previous'),
(22, '2026-08-16', 1204.00, 0.00, 'previous'),
(23, '2026-08-17', 1166.00, 0.00, 'previous'),
(24, '2026-08-18', 1136.00, 0.00, 'previous'),
(25, '2026-08-19', 1266.00, 0.00, 'previous'),
(26, '2026-08-20', 1219.00, 0.00, 'previous'),
(27, '2026-08-21', 851.00, 0.00, 'previous'),
(28, '2026-08-22', 1234.00, 0.00, 'previous'),
(29, '2026-08-23', 1285.00, 0.00, 'previous'),
(30, '2026-08-24', 847.00, 0.00, 'previous'),
(31, '2026-08-25', 2163.00, 0.00, 'previous'),
(32, '2026-08-26', 2763.00, 0.00, 'previous'),
(33, '2026-08-27', 1959.00, 0.00, 'previous'),
(34, '2026-08-28', 1134.00, 0.00, 'previous'),
(35, '2026-08-29', 1643.00, 0.00, 'previous'),
(36, '2026-08-30', 1522.00, 0.00, 'previous'),
(37, '2026-08-31', 2003.00, 0.00, 'previous'),
(38, '2026-09-01', 1926.00, 0.00, 'previous'),
(39, '2026-09-02', 2029.00, 0.00, 'previous'),
(40, '2026-09-03', 1904.00, 0.00, 'previous'),
(41, '2026-09-04', 1525.00, 0.00, 'previous'),
(42, '2026-09-05', 2939.00, 0.00, 'previous'),
(43, '2026-09-06', 1438.00, 0.00, 'previous'),
(44, '2026-09-07', 1801.00, 0.00, 'previous'),
(45, '2026-09-08', 1991.00, 0.00, 'previous'),
(46, '2026-09-09', 1563.00, 0.00, 'previous'),
(47, '2026-09-10', 1812.00, 0.00, 'previous'),
(48, '2026-09-11', 855.00, 0.00, 'previous'),
(49, '2026-09-12', 1618.00, 0.00, 'previous'),
(50, '2026-09-13', 1736.00, 0.00, 'previous'),
(51, '2026-09-14', 1515.00, 0.00, 'previous'),
(52, '2026-09-15', 1632.00, 0.00, 'previous'),
(53, '2026-09-16', 1486.00, 0.00, 'previous'),
(54, '2026-09-17', 1208.00, 0.00, 'previous'),
(55, '2026-09-18', 589.00, 0.00, 'previous'),
(56, '2026-09-19', 1914.00, 0.00, 'previous'),
(57, '2026-09-20', 1346.00, 0.00, 'previous'),
(58, '2026-09-21', 1748.00, 0.00, 'previous'),
(59, '2026-09-22', 1057.00, 0.00, 'previous'),
(60, '2026-09-23', 1361.00, 0.00, 'previous'),
(61, '2026-09-24', 1335.00, 0.00, 'previous'),
(62, '2026-09-25', 603.00, 0.00, 'previous'),
(63, '2026-09-26', 1284.00, 0.00, 'previous'),
(64, '2026-09-27', 1448.00, 0.00, 'previous'),
(65, '2026-09-28', 1731.00, 0.00, 'previous'),
(66, '2026-09-29', 1729.00, 0.00, 'previous'),
(67, '2026-09-30', 2207.00, 0.00, 'previous'),
(68, '2026-10-01', 3216.00, 0.00, 'previous'),
(69, '2026-10-02', 940.00, 0.00, 'previous'),
(70, '2026-10-03', 1307.00, 0.00, 'previous'),
(71, '2026-10-04', 1691.00, 0.00, 'previous'),
(72, '2026-10-05', 1677.00, 0.00, 'previous'),
(73, '2026-10-06', 2162.00, 0.00, 'previous'),
(74, '2026-10-07', 1448.00, 714.00, 'previous'),
(75, '2026-10-08', 968.00, 467.00, 'previous'),
(76, '2026-10-09', 210.00, 365.00, 'previous'),
(77, '2026-10-10', 722.00, 624.00, 'previous'),
(78, '2026-10-11', 733.00, 500.00, 'previous');

-- --------------------------------------------------------

--
-- Table structure for table `book_expenses`
--

CREATE TABLE `book_expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `spent_on` date NOT NULL,
  `category` varchar(80) NOT NULL,
  `kind` varchar(40) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(160) NOT NULL DEFAULT '',
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `proof` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_expenses`
--

INSERT INTO `book_expenses` (`id`, `spent_on`, `category`, `kind`, `amount`, `note`, `category_id`, `proof`) VALUES
(1, '2026-08-01', 'rent', '', 3500.00, 'Previous book', 2, ''),
(2, '2026-03-01', 'utilities', 'Electricity', 42.97, 'Previous book', 3, ''),
(3, '2026-04-01', 'utilities', 'Electricity', 41.69, 'Previous book', 3, ''),
(4, '2026-05-01', 'utilities', 'Electricity', 42.97, 'Previous book', 3, ''),
(5, '2026-06-01', 'utilities', 'Electricity', 42.97, 'Previous book', 3, ''),
(6, '2026-07-01', 'utilities', 'Electricity', 42.97, 'Previous book', 3, ''),
(7, '2026-08-01', 'utilities', 'Electricity', 2254.84, 'Previous book', 3, ''),
(8, '2026-09-01', 'utilities', 'Electricity', 1046.33, 'Previous book', 3, ''),
(12, '2026-10-23', 'Rent', 'Paid rent', 3500.00, 'Paid for October', 2, '12.jpg'),
(13, '2026-10-28', 'Supply', 'nuts', 250.00, 'purchased nuts 1 kg', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `book_partners`
--

CREATE TABLE `book_partners` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `share_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_partners`
--

INSERT INTO `book_partners` (`id`, `name`, `share_percent`, `sort_order`) VALUES
(1, 'Adhuham', 40.00, 1),
(2, 'Nooradhen', 10.00, 2),
(3, 'Zainab', 10.00, 3),
(4, 'Naura', 10.00, 4),
(5, 'Mifzaal', 10.00, 5),
(6, 'Mamnoon', 10.00, 6),
(7, 'Maain', 10.00, 7);

-- --------------------------------------------------------

--
-- Table structure for table `book_people`
--

CREATE TABLE `book_people` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `designation` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_people`
--

INSERT INTO `book_people` (`id`, `name`, `designation`) VALUES
(1, 'Suman', 'Waiter'),
(2, 'Waiter 2', 'Waiter'),
(3, 'Cook', 'Cook'),
(4, 'Manager', 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `book_recurring`
--

CREATE TABLE `book_recurring` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `kind` varchar(40) NOT NULL DEFAULT '',
  `amount` decimal(10,2) NOT NULL,
  `note` varchar(160) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_recurring_months`
--

CREATE TABLE `book_recurring_months` (
  `id` int(10) UNSIGNED NOT NULL,
  `recurring_id` int(10) UNSIGNED NOT NULL,
  `year_num` smallint(6) NOT NULL,
  `month_num` tinyint(4) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_salaries`
--

CREATE TABLE `book_salaries` (
  `id` int(10) UNSIGNED NOT NULL,
  `person_id` int(10) UNSIGNED NOT NULL,
  `year_num` smallint(6) NOT NULL,
  `month_num` tinyint(4) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_salaries`
--

INSERT INTO `book_salaries` (`id`, `person_id`, `year_num`, `month_num`, `amount`) VALUES
(1, 1, 2026, 8, 6000.00),
(2, 1, 2026, 9, 8000.00);

-- --------------------------------------------------------

--
-- Table structure for table `cafe_tables`
--

CREATE TABLE `cafe_tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `area_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cafe_tables`
--

INSERT INTO `cafe_tables` (`id`, `name`, `sort_order`, `active`, `area_id`) VALUES
(1, 'Hut 1', 1, 1, 1),
(2, 'Hut 2', 2, 1, 1),
(3, 'Hut 3', 3, 1, 1),
(4, 'Hut 4', 4, 1, 1),
(5, 'Outdoor 1', 5, 1, 2),
(6, 'Outdoor 2', 6, 1, 2),
(7, 'Outdoor 3', 7, 1, 2),
(8, 'Outdoor 4', 8, 1, 2),
(9, 'Indoor 1', 9, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `sort_order`) VALUES
(1, 'Drinks', 1),
(2, 'Food', 2),
(3, 'Extra', 3);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `credit_limit` decimal(10,2) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `created_at`, `credit_limit`, `password_hash`) VALUES
(3, 'kaamil', '9925481', '2026-10-21 19:45:41', 500.00, NULL),
(5, 'Adhuham', '9969252', '2026-10-22 00:07:48', 500.00, NULL),
(6, 'Ali Nihaan', '9337082', '2026-10-22 00:08:28', 200.00, NULL),
(10, 'fazaal', '9780035', '2026-10-22 12:06:31', 500.00, NULL),
(11, 'boss berother', '1234567', '2026-10-22 12:22:20', 200.00, NULL),
(12, 'Hogu', '9405661', '2026-10-22 14:59:10', 500.00, NULL),
(13, 'Mohamed mansoor', '9633334', '2026-10-22 15:52:15', 500.00, NULL),
(14, 'Hassan Azeez', '9637687', '2026-10-22 16:51:19', 200.00, NULL),
(17, 'mayu', '99999', '2026-10-22 17:54:03', 100.00, NULL),
(18, 'Faaris', '9777764', '2026-10-22 18:02:55', 1000.00, NULL),
(20, 'Suman', '7679042', '2026-10-22 18:20:35', 500.00, NULL),
(21, 'Sakib', '8801772422411', '2026-10-22 18:24:11', 500.00, NULL),
(31, 'hassan zayaan', '7660627', '2026-10-22 22:31:06', 500.00, NULL),
(33, 'Sid', '9888839', '2026-10-22 23:13:29', 500.00, NULL),
(34, 'Sizaan', '9779917', '2026-10-22 23:16:20', 200.00, NULL),
(35, 'Gasim nooradheen', '9652266', '2026-10-22 23:47:10', 1000.00, NULL),
(36, 'anis', '5363464', '2026-10-23 17:47:06', 200.00, NULL),
(37, 'visham', '3514365869', '2026-10-23 17:48:09', 200.00, NULL),
(38, 'safraz', '569158694', '2026-10-23 17:50:51', 200.00, NULL),
(39, 'Rashid', '346246', '2026-10-23 17:51:31', 200.00, NULL),
(40, 'hassanbe', '168416148', '2026-10-23 17:52:47', 200.00, NULL),
(41, 'magistrate', '99966663', '2026-10-23 17:55:56', 500.00, NULL),
(42, 'SG', '4563453', '2026-10-23 18:08:23', 200.00, NULL),
(43, 'Fayaaz', '56415615', '2026-10-23 18:10:39', 200.00, NULL),
(44, 'luthufee', '9995440', '2026-10-24 11:08:36', 200.00, NULL),
(45, 'osim', '9787009', '2026-10-24 15:48:48', 200.00, NULL),
(46, 'Maajidh', '9458243', '2026-10-26 14:20:51', 500.00, NULL),
(47, 'sony usam', '23564824', '2026-10-27 12:41:42', 200.00, NULL),
(48, 'omor', '9836920', '2026-10-27 18:02:00', 200.00, NULL),
(49, 'afraah', '9654710', '2026-10-27 18:28:18', 200.00, NULL),
(50, 'nijar', '7633860', '2026-10-27 18:52:12', 200.00, NULL),
(51, 'hamedu', '128135569', '2026-11-01 13:53:41', 200.00, NULL),
(52, 'adum pollice', '6569814689', '2026-11-01 15:43:42', 200.00, NULL),
(53, 'Medubay', '9676853', '2026-11-02 15:56:15', 200.00, NULL),
(54, 'Afsadh', '9151404', '2026-11-02 17:01:15', 200.00, NULL),
(55, 'cuncil7', '7543467', '2026-11-03 15:28:18', 200.00, NULL),
(56, 'jafor', '9932876', '2026-11-03 17:59:51', 200.00, NULL),
(57, 'kappi dhoni', '123456', '2026-11-04 15:20:20', 200.00, NULL),
(58, 'Krishna phiyak', '7482943', '2026-11-05 23:21:16', 200.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `floor_areas`
--

CREATE TABLE `floor_areas` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `floor_areas`
--

INSERT INTO `floor_areas` (`id`, `name`, `sort_order`) VALUES
(1, 'Hut', 1),
(2, 'Outdoor', 2),
(3, 'Indoor', 3);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `photo` varchar(80) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `name`, `price`, `active`, `sort_order`, `photo`) VALUES
(1, 2, 'Breakfast', 10.00, 1, 0, '1.jpg'),
(2, 1, 'Milk Coffee', 10.00, 1, 3, '2.jpg'),
(3, 1, 'Cereal Coffee (hard)', 15.00, 1, 4, '3.jpg'),
(4, 2, 'Boiled egg', 5.00, 1, 0, '4.jpg'),
(5, 1, 'YeYe coffee', 5.00, 1, 0, '5.jpg'),
(6, 2, 'Hedhika', 5.00, 1, 3, '6.jpg'),
(7, 1, 'Black Coffee Hard', 10.00, 1, 1, '7.jpg'),
(8, 1, 'Black Coffee Normal', 7.00, 1, 2, '8.jpg'),
(9, 1, 'Lemon Tea', 10.00, 1, 5, '9.jpg'),
(10, 1, 'Black Tea Large', 5.00, 1, 6, '10.jpg'),
(11, 1, 'Black Tea Small', 3.00, 1, 7, '11.jpg'),
(12, 1, 'Water Bottle 500 ML', 5.00, 1, 8, '12.jpg'),
(13, 1, 'Water Bottle 1.5 ML', 10.00, 1, 9, '13.jpg'),
(14, 3, 'Nuts Half', 5.00, 1, 1, '14.jpg'),
(15, 3, 'Nuts Full', 10.00, 1, 2, '15.jpg'),
(16, 2, 'Bisket', 5.00, 1, 4, '16.jpg'),
(17, 1, 'Cereal Coffee (Light)', 10.00, 1, 10, '17.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `open_tables`
--

CREATE TABLE `open_tables` (
  `table_id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `open_tables`
--

INSERT INTO `open_tables` (`table_id`, `sale_id`) VALUES
(1, 902);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `built_in` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `built_in`, `sort_order`) VALUES
(1, 'superadmin', 'Superadmin', 1, 1),
(2, 'manager', 'Manager', 1, 2),
(3, 'waiter', 'Waiter', 1, 3),
(70670, 'supply', 'Supply', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `role_access`
--

CREATE TABLE `role_access` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `area` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_access`
--

INSERT INTO `role_access` (`role_id`, `area`) VALUES
(1, 'admin.books'),
(1, 'admin.customers'),
(1, 'admin.expenses'),
(1, 'admin.floor'),
(1, 'admin.hours'),
(1, 'admin.menu'),
(1, 'admin.reports'),
(1, 'admin.sales'),
(1, 'admin.settings'),
(1, 'admin.today'),
(1, 'admin.users'),
(1, 'expenses'),
(1, 'orders'),
(1, 'today'),
(2, 'admin.books'),
(2, 'credit.limit'),
(2, 'expenses'),
(2, 'orders'),
(2, 'today'),
(3, 'orders'),
(3, 'today'),
(70670, 'supply.quote');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_id` int(10) UNSIGNED DEFAULT NULL,
  `table_name` varchar(40) NOT NULL,
  `opened_at` datetime NOT NULL,
  `service_started_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `payment_method` enum('cash','online','credit') DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('open','credit','paid') NOT NULL DEFAULT 'open',
  `slip_image` varchar(80) NOT NULL DEFAULT '',
  `waiter_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `held` tinyint(1) NOT NULL DEFAULT 0,
  `pay_review` varchar(12) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `table_id`, `table_name`, `opened_at`, `service_started_at`, `closed_at`, `payment_method`, `total`, `status`, `slip_image`, `waiter_id`, `customer_id`, `held`, `pay_review`) VALUES
(2, 1, 'Hut 1', '2026-10-21 18:00:59', '2026-10-21 18:06:44', '2026-10-21 18:09:54', 'online', 5.00, 'paid', '', NULL, NULL, 0, ''),
(3, 9, 'Indoor 1', '2026-10-21 18:01:50', '2026-10-21 18:01:50', '2026-10-21 18:10:10', 'online', 10.00, 'paid', '', NULL, NULL, 0, ''),
(4, 2, 'Hut 2', '2026-10-21 18:08:13', '2026-10-21 18:08:13', '2026-10-21 18:09:42', 'online', 56.00, 'paid', '', NULL, NULL, 0, ''),
(9, 1, 'Hut 1', '2026-10-21 18:31:44', '2026-10-21 18:31:44', '2026-10-21 18:41:19', 'online', 65.00, 'paid', '9.jpg', NULL, NULL, 0, ''),
(11, 1, 'Hut 1', '2026-10-21 18:42:00', '2026-10-21 18:42:00', '2026-10-21 20:32:23', 'online', 82.00, 'paid', '11.jpg', 2, NULL, 0, ''),
(15, 2, 'Hut 2', '2026-10-21 19:02:41', '2026-10-21 19:02:42', '2026-10-21 19:12:41', 'cash', 35.00, 'paid', '', 2, NULL, 0, ''),
(16, 3, 'Hut 3', '2026-10-21 19:03:38', '2026-10-21 19:03:38', '2026-10-28 11:10:17', 'online', 59.00, 'paid', '', 2, 3, 1, ''),
(17, 4, 'Hut 4', '2026-10-21 19:04:43', '2026-10-21 19:04:43', '2026-10-21 19:43:23', 'online', 34.00, 'paid', '', 2, NULL, 0, ''),
(18, 5, 'Outdoor 1', '2026-10-21 19:13:22', '2026-10-21 19:13:22', '2026-10-21 19:53:10', 'cash', 44.00, 'paid', '', 2, NULL, 0, ''),
(21, 6, 'Outdoor 2', '2026-10-21 19:21:50', '2026-10-21 19:21:50', '2026-10-21 19:26:30', 'cash', 17.00, 'paid', '', 2, NULL, 0, ''),
(30, 5, 'Outdoor 1', '2026-10-21 20:44:40', '2026-10-21 20:44:40', '2026-10-21 22:21:24', 'online', 6.00, 'paid', '30.jpg', 2, NULL, 0, ''),
(31, 1, 'Hut 1', '2026-10-21 21:56:53', '2026-10-21 21:56:53', '2026-10-21 22:00:48', 'online', 94.00, 'paid', '31.jpg', 2, NULL, 0, ''),
(32, 3, 'Hut 3', '2026-10-21 22:16:05', '2026-10-21 22:16:05', '2026-10-21 22:17:00', 'online', 20.00, 'paid', '32.jpg', 2, NULL, 0, ''),
(33, 2, 'Hut 2', '2026-10-21 22:22:29', '2026-10-21 22:22:29', '2026-10-21 22:22:41', 'online', 5.00, 'paid', '33.jpg', 2, NULL, 0, ''),
(38, 6, 'Outdoor 2', '2026-10-21 22:51:51', '2026-10-21 22:51:51', '2026-10-21 22:51:59', 'cash', 5.00, 'paid', '', 2, NULL, 0, ''),
(39, 3, 'Hut 3', '2026-10-21 23:15:49', '2026-10-21 23:15:49', '2026-10-21 23:53:04', 'online', 34.00, 'paid', '39.jpg', 2, NULL, 0, ''),
(40, 1, 'Hut 1', '2026-10-21 23:36:31', '2026-10-21 23:36:31', '2026-10-22 00:02:07', 'cash', 19.00, 'paid', '', 2, NULL, 0, ''),
(42, 3, 'Hut 3', '2026-10-21 23:59:22', '2026-10-21 23:59:22', '2026-10-21 23:59:40', 'online', 7.00, 'paid', '42.jpg', 2, NULL, 0, ''),
(52, 2, 'Hut 2', '2026-10-22 11:36:35', '2026-10-22 11:36:35', '2026-10-22 11:43:58', 'online', 12.00, 'paid', '52.jpg', 3, NULL, 0, ''),
(54, 1, 'Hut 1', '2026-10-22 11:51:16', '2026-10-22 11:51:16', '2026-10-22 12:14:12', 'online', 20.00, 'paid', '54.jpg', 9, NULL, 0, ''),
(55, 4, 'Hut 4', '2026-10-22 11:59:16', '2026-10-22 11:59:16', '2026-10-22 12:26:51', 'cash', 31.00, 'paid', '', 3, NULL, 0, ''),
(56, 5, 'Outdoor 1', '2026-10-22 12:01:27', '2026-10-22 12:01:27', '2026-10-22 12:02:21', 'cash', 8.00, 'paid', '', 3, NULL, 0, ''),
(58, 2, 'Hut 2', '2026-10-22 12:07:31', '2026-10-22 12:07:32', '2026-10-22 12:54:21', 'cash', 22.00, 'paid', '', 3, NULL, 0, ''),
(59, 1, 'Hut 1', '2026-10-22 12:19:00', '2026-10-22 12:19:01', '2026-10-22 19:30:10', 'online', 94.00, 'paid', '59.jpg', 3, 11, 1, ''),
(60, 1, 'Hut 1', '2026-10-22 13:00:29', '2026-10-22 13:00:29', '2026-10-22 14:05:26', 'online', 17.00, 'paid', '60.jpg', 3, NULL, 0, ''),
(61, 2, 'Hut 2', '2026-10-22 13:03:50', '2026-10-22 13:03:50', '2026-10-22 13:04:49', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(62, 2, 'Hut 2', '2026-10-22 13:58:30', '2026-10-22 13:58:30', '2026-10-22 14:43:07', 'online', 7.00, 'paid', '62.jpg', 3, NULL, 0, ''),
(63, 4, 'Hut 4', '2026-10-22 14:12:32', '2026-10-22 14:12:32', '2026-10-22 14:21:53', 'online', 25.00, 'paid', '63.jpg', 3, NULL, 0, ''),
(65, 3, 'Hut 3', '2026-10-22 14:27:31', '2026-10-22 14:27:31', '2026-10-22 15:13:29', 'online', 15.00, 'paid', '65.jpg', 3, NULL, 0, ''),
(66, 9, 'Indoor 1', '2026-10-22 14:43:30', '2026-10-22 14:43:31', '2026-10-22 15:09:37', 'online', 15.00, 'paid', '66.jpg', 9, NULL, 0, ''),
(67, 5, 'Outdoor 1', '2026-10-22 14:45:15', '2026-10-22 14:45:15', '2026-10-22 15:14:26', 'cash', 24.00, 'paid', '', 2, NULL, 0, ''),
(68, 8, 'Outdoor 4', '2026-10-22 14:59:48', '2026-10-22 14:59:48', '2026-11-02 13:06:00', 'online', 101.00, 'paid', '', 9, 12, 1, ''),
(70, 2, 'Hut 2', '2026-10-22 15:13:32', '2026-10-22 15:13:32', '2026-10-22 15:27:06', 'cash', 29.00, 'paid', '', 3, NULL, 0, ''),
(71, 4, 'Hut 4', '2026-10-22 15:14:33', '2026-10-22 15:14:33', '2026-10-22 15:55:32', 'online', 50.00, 'paid', '71.jpg', 2, NULL, 0, ''),
(73, 1, 'Hut 1', '2026-10-22 15:34:12', '2026-10-22 15:34:13', '2026-10-28 11:10:17', 'online', 102.00, 'paid', '', 2, 3, 1, ''),
(74, 3, 'Hut 3', '2026-10-22 15:34:47', '2026-10-22 15:34:48', '2026-10-22 16:30:15', 'online', 25.00, 'paid', '74.jpg', 2, NULL, 0, ''),
(75, 2, 'Hut 2', '2026-10-22 15:50:30', '2026-10-22 15:50:30', '2026-10-22 17:24:29', 'cash', 69.00, 'paid', '', 2, NULL, 0, ''),
(76, 6, 'Outdoor 2', '2026-10-22 15:52:35', '2026-10-22 15:52:35', '2026-10-22 16:02:50', 'cash', 55.00, 'paid', '', 2, NULL, 0, ''),
(77, 4, 'Hut 4', '2026-10-22 16:24:09', '2026-10-22 16:24:09', '2026-10-22 16:53:34', 'online', 22.00, 'paid', '77.jpg', 2, NULL, 0, ''),
(78, 6, 'Outdoor 2', '2026-10-22 16:26:34', '2026-10-22 16:26:34', '2026-10-22 17:25:30', 'cash', 20.00, 'paid', '', 2, NULL, 0, ''),
(79, 9, 'Indoor 1', '2026-10-22 16:27:05', '2026-10-22 16:27:06', NULL, 'credit', 110.00, 'credit', '', 2, 5, 1, ''),
(80, 7, 'Outdoor 3', '2026-10-22 16:49:26', '2026-10-22 16:49:26', '2026-10-22 16:49:50', 'cash', 40.00, 'paid', '', 2, NULL, 0, ''),
(82, 1, 'Hut 1', '2026-10-22 17:07:28', '2026-10-22 17:07:29', '2026-10-22 17:27:38', 'online', 64.00, 'paid', '82.jpg', 2, NULL, 0, ''),
(83, 7, 'Outdoor 3', '2026-10-22 17:07:56', '2026-10-22 17:07:56', '2026-10-22 17:26:59', 'online', 65.00, 'paid', '83.jpg', 2, NULL, 0, ''),
(84, 8, 'Outdoor 4', '2026-10-22 17:09:17', '2026-10-22 17:09:17', '2026-10-22 17:09:36', 'cash', 30.00, 'paid', '', 2, NULL, 0, ''),
(85, 8, 'Outdoor 4', '2026-10-22 17:14:15', '2026-10-22 17:14:15', '2026-10-22 17:14:28', 'cash', 75.00, 'paid', '', 2, NULL, 0, ''),
(86, 5, 'Outdoor 1', '2026-10-22 17:39:45', '2026-10-22 17:39:45', '2026-10-22 17:39:55', 'cash', 15.00, 'paid', '', 2, NULL, 0, ''),
(87, 4, 'Hut 4', '2026-10-22 17:44:55', '2026-10-22 17:44:56', '2026-10-22 17:45:38', 'online', 44.00, 'paid', '87.jpg', 2, NULL, 0, ''),
(88, 1, 'Hut 1', '2026-10-22 17:48:20', '2026-10-25 19:19:44', '2026-10-25 19:21:10', 'online', 54.00, 'paid', '', 2, 11, 1, ''),
(89, 2, 'Hut 2', '2026-10-22 17:54:16', '2026-10-22 17:54:16', '2026-10-22 17:54:29', 'cash', 60.00, 'paid', '', 2, NULL, 0, ''),
(90, 2, 'Hut 2', '2026-10-22 17:58:58', '2026-10-22 17:58:59', '2026-10-22 18:07:11', 'online', 25.00, 'paid', '90.jpg', 2, NULL, 0, ''),
(91, 3, 'Hut 3', '2026-10-22 18:11:26', '2026-10-22 18:11:26', '2026-10-22 19:13:28', 'online', 29.00, 'paid', '91.jpg', 2, NULL, 0, ''),
(92, 6, 'Outdoor 2', '2026-10-22 18:22:12', '2026-10-22 18:22:12', '2026-10-22 22:10:55', 'online', 23.00, 'paid', '92.jpg', 3, NULL, 0, ''),
(93, 5, 'Outdoor 1', '2026-10-22 18:29:26', '2026-10-22 18:29:26', '2026-10-22 18:57:34', 'cash', 60.00, 'paid', '', 2, NULL, 0, ''),
(94, 4, 'Hut 4', '2026-10-22 18:40:23', '2026-10-22 18:40:23', '2026-10-22 18:59:37', 'online', 26.00, 'paid', '94.jpg', 2, NULL, 0, ''),
(95, 7, 'Outdoor 3', '2026-10-22 18:45:18', '2026-10-22 18:45:19', '2026-10-22 18:57:40', 'cash', 36.00, 'paid', '', 2, NULL, 0, ''),
(96, 1, 'Hut 1', '2026-10-22 18:59:52', '2026-10-22 18:59:52', '2026-10-22 20:49:21', 'online', 92.00, 'paid', '96.jpg', 2, NULL, 0, ''),
(97, 3, 'Hut 3', '2026-10-22 19:40:21', '2026-10-22 19:40:21', '2026-10-22 20:17:36', 'online', 12.00, 'paid', '97.jpg', 2, NULL, 0, ''),
(98, 4, 'Hut 4', '2026-10-22 19:40:29', '2026-10-22 19:40:29', '2026-10-22 19:46:28', 'cash', 27.00, 'paid', '', 2, NULL, 0, ''),
(99, 8, 'Outdoor 4', '2026-10-22 20:37:01', '2026-10-22 20:37:01', '2026-10-28 12:08:01', 'online', 415.00, 'paid', '', 2, 31, 1, ''),
(100, 7, 'Outdoor 3', '2026-10-22 20:50:50', '2026-10-22 20:50:50', NULL, 'credit', 262.00, 'credit', '', 2, 33, 1, ''),
(101, 3, 'Hut 3', '2026-10-22 20:58:44', NULL, NULL, 'credit', 0.00, 'credit', '', 2, 11, 1, ''),
(102, 1, 'Hut 1', '2026-10-22 21:20:22', '2026-10-22 21:20:22', '2026-10-22 21:21:05', 'online', 27.00, 'paid', '102.jpg', 2, NULL, 0, ''),
(103, 4, 'Hut 4', '2026-10-22 21:21:18', '2026-10-22 21:21:18', '2026-10-22 21:46:45', 'cash', 22.00, 'paid', '', 2, NULL, 0, ''),
(104, 4, 'Hut 4', '2026-10-22 21:47:07', '2026-10-22 21:47:08', '2026-10-22 22:01:32', 'online', 10.00, 'paid', '104.jpg', 2, NULL, 0, ''),
(105, 1, 'Hut 1', '2026-10-22 23:35:59', '2026-10-22 23:35:59', '2026-10-22 23:36:13', 'cash', 10.00, 'paid', '', 2, NULL, 0, ''),
(106, 1, 'Hut 1', '2026-10-23 00:38:13', '2026-10-23 00:38:13', '2026-10-23 00:38:46', 'online', 20.00, 'paid', '106.jpg', 9, NULL, 0, ''),
(107, 4, 'Hut 4', '2026-10-23 13:59:52', '2026-10-23 13:59:52', NULL, 'credit', 24.00, 'credit', '', 9, 5, 1, ''),
(108, 2, 'Hut 2', '2026-10-23 14:01:27', '2026-10-23 14:01:27', '2026-10-23 14:23:16', 'online', 19.00, 'paid', '108.jpg', 9, NULL, 0, ''),
(109, 1, 'Hut 1', '2026-10-23 14:44:07', '2026-10-23 14:44:07', '2026-10-23 15:00:01', 'online', 51.00, 'paid', '109.jpg', 9, NULL, 0, ''),
(110, 2, 'Hut 2', '2026-10-23 14:58:10', '2026-10-23 14:58:10', '2026-10-23 16:30:25', 'cash', 31.00, 'paid', '', 3, NULL, 0, ''),
(111, 6, 'Outdoor 2', '2026-10-23 15:27:49', '2026-10-23 15:27:49', '2026-10-23 15:36:49', 'cash', 8.00, 'paid', '', 3, NULL, 0, ''),
(112, 8, 'Outdoor 4', '2026-10-23 15:33:26', '2026-10-23 15:33:27', '2026-10-23 15:40:14', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(113, 5, 'Outdoor 1', '2026-10-23 15:50:26', '2026-10-23 15:50:26', '2026-10-23 15:51:28', 'online', 37.00, 'paid', '113.jpg', 3, NULL, 0, ''),
(114, 1, 'Hut 1', '2026-10-23 16:15:47', '2026-10-23 16:15:47', '2026-10-23 16:17:00', 'cash', 31.00, 'paid', '', 3, NULL, 0, ''),
(115, 9, 'Indoor 1', '2026-10-23 16:20:27', '2026-10-23 16:20:27', '2026-10-23 16:25:41', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(116, 1, 'Hut 1', '2026-10-23 17:22:18', '2026-10-23 17:22:18', NULL, 'credit', 15.00, 'credit', '', 9, 5, 1, ''),
(117, 9, 'Indoor 1', '2026-10-23 17:47:17', '2026-10-23 17:47:26', '2026-10-30 15:34:16', 'online', 31.00, 'paid', '', 9, 36, 1, ''),
(118, 9, 'Indoor 1', '2026-10-23 17:48:22', '2026-10-23 17:48:22', '2026-11-01 14:51:18', 'online', 47.00, 'paid', '', 9, 37, 1, ''),
(119, 9, 'Indoor 1', '2026-10-23 17:49:30', '2026-10-23 17:49:35', '2026-10-28 11:10:17', 'online', 163.00, 'paid', '', 9, 3, 1, ''),
(120, 9, 'Indoor 1', '2026-10-23 17:50:55', '2026-10-23 17:51:01', '2026-10-31 08:36:46', 'online', 95.00, 'paid', '', 9, 38, 1, ''),
(121, 9, 'Indoor 1', '2026-10-23 17:51:33', '2026-10-23 17:51:36', '2026-10-29 12:00:59', 'online', 141.00, 'paid', '', 9, 39, 1, ''),
(122, 9, 'Indoor 1', '2026-10-23 17:52:50', '2026-10-23 17:52:56', '2026-10-26 15:06:39', 'online', 24.00, 'paid', '', 9, 14, 1, ''),
(123, 4, 'Hut 4', '2026-10-23 17:53:59', '2026-10-23 17:53:59', '2026-10-23 17:54:07', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(124, 4, 'Hut 4', '2026-10-23 17:54:24', '2026-10-23 17:54:25', '2026-10-23 18:18:23', 'online', 22.00, 'paid', '124.jpg', 3, NULL, 0, ''),
(125, 9, 'Indoor 1', '2026-10-23 17:56:37', '2026-10-23 17:56:37', '2026-11-04 15:05:22', 'online', 27.00, 'paid', '', 9, 41, 1, ''),
(127, 9, 'Indoor 1', '2026-10-23 18:08:58', '2026-10-23 18:08:58', NULL, 'credit', 115.00, 'credit', '', 9, 42, 1, ''),
(128, 9, 'Indoor 1', '2026-10-23 18:10:44', '2026-10-23 18:10:55', '2026-10-28 09:03:46', 'online', 49.00, 'paid', '128.jpg', 9, 43, 1, ''),
(129, 3, 'Hut 3', '2026-10-23 18:28:26', '2026-10-23 18:28:26', '2026-10-23 18:28:30', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(130, 4, 'Hut 4', '2026-10-23 18:47:35', '2026-10-23 18:47:35', '2026-10-23 19:00:33', 'cash', 34.00, 'paid', '', 3, NULL, 0, ''),
(131, 5, 'Outdoor 1', '2026-10-23 18:49:22', '2026-10-23 18:49:22', '2026-10-23 18:51:44', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(132, 3, 'Hut 3', '2026-10-23 19:01:10', '2026-10-23 19:01:11', '2026-10-23 19:01:25', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(133, 4, 'Hut 4', '2026-10-23 19:07:54', '2026-10-23 19:07:54', '2026-10-23 19:21:34', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(134, 3, 'Hut 3', '2026-10-23 19:16:53', '2026-10-23 19:16:54', '2026-10-23 19:52:54', 'cash', 54.00, 'paid', '', 3, NULL, 0, ''),
(135, 5, 'Outdoor 1', '2026-10-23 19:31:07', '2026-10-23 19:31:07', '2026-10-23 20:49:18', 'online', 13.00, 'paid', '135.jpg', 3, NULL, 0, ''),
(136, 1, 'Hut 1', '2026-10-23 20:15:29', '2026-10-23 20:15:29', '2026-11-02 13:06:00', 'online', 5.00, 'paid', '', 9, 12, 1, ''),
(137, 1, 'Hut 1', '2026-10-23 21:26:57', '2026-10-23 21:26:57', '2026-10-23 21:27:20', 'online', 25.00, 'paid', '137.jpg', 9, NULL, 0, ''),
(138, 6, 'Outdoor 2', '2026-10-24 06:36:25', '2026-10-24 06:36:25', '2026-10-24 06:52:26', 'online', 29.00, 'paid', '', 3, NULL, 0, ''),
(139, 6, 'Outdoor 2', '2026-10-24 06:58:30', '2026-10-24 06:58:30', '2026-10-24 07:07:36', 'cash', 17.00, 'paid', '', 3, NULL, 0, ''),
(141, 6, 'Outdoor 2', '2026-10-24 07:08:13', '2026-10-24 07:08:13', '2026-10-24 07:34:05', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(142, 5, 'Outdoor 1', '2026-10-24 07:08:22', '2026-10-24 07:08:22', '2026-10-24 07:34:52', 'online', 7.00, 'paid', '142.jpg', 3, NULL, 0, ''),
(143, 9, 'Indoor 1', '2026-10-24 07:17:48', '2026-10-24 07:17:48', NULL, 'credit', 10.00, 'credit', '', 3, 21, 1, ''),
(144, 7, 'Outdoor 3', '2026-10-24 07:28:45', '2026-10-24 07:28:45', '2026-10-24 07:33:47', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(145, 5, 'Outdoor 1', '2026-10-24 07:38:49', '2026-10-24 07:38:50', '2026-10-24 07:39:48', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(146, 4, 'Hut 4', '2026-10-24 08:22:20', '2026-10-24 08:22:20', '2026-11-01 13:38:47', 'cash', 74.00, 'paid', '', 3, 44, 1, ''),
(147, 3, 'Hut 3', '2026-10-24 08:23:14', '2026-10-24 08:23:14', NULL, 'credit', 20.00, 'credit', '', 3, 18, 1, ''),
(148, 1, 'Hut 1', '2026-10-24 08:37:35', '2026-10-24 08:37:35', NULL, 'credit', 31.00, 'credit', '', 3, 18, 1, ''),
(149, 1, 'Hut 1', '2026-10-24 09:29:00', '2026-10-24 09:29:00', '2026-10-24 09:59:54', 'online', 19.00, 'paid', '149.jpg', 3, NULL, 0, ''),
(150, 2, 'Hut 2', '2026-10-24 09:29:25', '2026-10-24 09:29:25', '2026-10-24 10:20:11', 'online', 50.00, 'paid', '150.jpg', 3, NULL, 0, ''),
(151, 5, 'Outdoor 1', '2026-10-24 09:31:30', '2026-10-24 09:31:30', '2026-10-24 10:07:49', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(152, 5, 'Outdoor 1', '2026-10-24 10:13:55', '2026-10-24 10:13:55', '2026-10-24 10:16:16', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(153, 3, 'Hut 3', '2026-10-24 10:29:27', '2026-10-24 10:29:28', '2026-10-24 10:33:30', 'cash', 40.00, 'paid', '', 3, NULL, 0, ''),
(154, 2, 'Hut 2', '2026-10-24 10:53:35', '2026-10-24 10:53:36', '2026-10-24 11:02:50', 'online', 12.00, 'paid', '154.jpg', 3, NULL, 0, ''),
(155, 5, 'Outdoor 1', '2026-10-24 10:55:51', '2026-10-24 10:55:51', '2026-10-24 11:32:29', 'online', 76.00, 'paid', '155.jpg', 3, NULL, 0, ''),
(156, 1, 'Hut 1', '2026-10-24 11:19:49', '2026-10-24 11:19:49', '2026-10-24 11:52:16', 'cash', 51.00, 'paid', '', 3, NULL, 0, ''),
(157, 4, 'Hut 4', '2026-10-24 12:05:46', '2026-10-24 12:05:47', '2026-10-24 12:16:45', 'online', 5.00, 'paid', '157.jpg', 3, NULL, 0, ''),
(158, 4, 'Hut 4', '2026-10-24 12:16:55', '2026-10-24 12:16:55', '2026-10-24 12:17:01', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(159, 8, 'Outdoor 4', '2026-10-24 12:54:34', '2026-10-24 12:54:35', '2026-10-24 12:54:43', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(160, 2, 'Hut 2', '2026-10-24 12:54:58', '2026-10-24 12:54:58', '2026-10-24 13:30:27', 'online', 50.00, 'paid', '160.jpg', 3, NULL, 0, ''),
(161, 3, 'Hut 3', '2026-10-24 12:55:23', '2026-10-24 12:55:23', '2026-10-24 13:31:09', 'cash', 29.00, 'paid', '', 3, NULL, 0, ''),
(162, 4, 'Hut 4', '2026-10-24 12:55:40', '2026-10-24 12:55:40', '2026-10-24 14:04:54', 'online', 54.00, 'paid', '162.jpg', 3, NULL, 0, ''),
(163, 6, 'Outdoor 2', '2026-10-24 13:10:48', '2026-10-24 13:10:49', '2026-10-24 14:29:53', 'online', 12.00, 'paid', '163.jpg', 2, NULL, 0, ''),
(164, 7, 'Outdoor 3', '2026-10-24 13:10:59', '2026-10-24 13:11:00', '2026-10-24 13:12:32', 'cash', 5.00, 'paid', '', 2, NULL, 0, ''),
(165, 1, 'Hut 1', '2026-10-24 13:14:58', '2026-10-24 13:14:59', '2026-10-24 13:15:06', 'cash', 30.00, 'paid', '', 2, NULL, 0, ''),
(166, 3, 'Hut 3', '2026-10-24 13:57:14', '2026-10-24 13:57:14', '2026-10-27 10:51:31', 'cash', 31.00, 'paid', '', 2, 45, 1, ''),
(167, 2, 'Hut 2', '2026-10-24 14:23:54', '2026-10-24 14:23:55', '2026-10-24 14:24:07', 'cash', 10.00, 'paid', '', 9, NULL, 0, ''),
(168, 2, 'Hut 2', '2026-10-24 14:24:50', '2026-10-24 14:24:51', '2026-10-24 14:25:14', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(169, 1, 'Hut 1', '2026-10-24 14:26:12', '2026-10-24 14:26:13', '2026-10-24 14:31:37', 'online', 24.00, 'paid', '169.jpg', 9, NULL, 0, ''),
(170, 7, 'Outdoor 3', '2026-10-24 14:33:56', '2026-10-24 14:33:56', '2026-10-24 14:44:31', 'cash', 8.00, 'paid', '', 9, NULL, 0, ''),
(171, 5, 'Outdoor 1', '2026-10-24 14:58:54', '2026-10-24 14:58:54', '2026-10-24 15:16:02', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(172, 4, 'Hut 4', '2026-10-24 15:33:11', '2026-10-24 15:33:11', '2026-10-24 15:49:45', 'online', 52.00, 'paid', '172.jpg', 9, NULL, 0, ''),
(173, 3, 'Hut 3', '2026-10-24 15:50:16', '2026-10-24 15:50:17', '2026-10-24 15:57:28', 'online', 79.00, 'paid', '', 9, NULL, 0, ''),
(174, 1, 'Hut 1', '2026-10-24 15:51:03', '2026-10-24 15:51:04', '2026-10-24 16:06:56', 'online', 32.00, 'paid', '174.jpg', 9, NULL, 0, ''),
(175, 9, 'Indoor 1', '2026-10-24 16:02:34', '2026-10-24 16:02:34', NULL, 'credit', 3.00, 'credit', '', 9, 20, 1, ''),
(176, 5, 'Outdoor 1', '2026-10-24 16:02:45', '2026-10-24 16:02:45', '2026-10-24 16:22:55', 'cash', 13.00, 'paid', '', 9, NULL, 0, ''),
(177, 6, 'Outdoor 2', '2026-10-24 16:09:23', '2026-10-24 16:09:23', '2026-10-24 16:48:49', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(178, 1, 'Hut 1', '2026-10-24 16:11:35', '2026-10-24 16:11:36', '2026-10-24 16:11:41', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(179, 4, 'Hut 4', '2026-10-24 16:18:45', '2026-10-24 16:18:45', NULL, 'credit', 15.00, 'credit', '', 3, 3, 1, ''),
(180, 5, 'Outdoor 1', '2026-10-24 16:26:16', '2026-10-24 16:26:16', '2026-10-24 16:48:55', 'cash', 25.00, 'paid', '', 9, NULL, 0, ''),
(181, 7, 'Outdoor 3', '2026-10-24 16:27:16', '2026-10-24 16:27:16', '2026-10-24 16:49:01', 'cash', 8.00, 'paid', '', 9, NULL, 0, ''),
(182, 2, 'Hut 2', '2026-10-24 16:29:29', '2026-10-24 16:29:30', '2026-10-26 15:06:39', 'online', 23.00, 'paid', '', 9, 14, 1, ''),
(183, 4, 'Hut 4', '2026-10-24 16:46:47', '2026-10-24 16:46:47', '2026-10-24 17:49:43', 'online', 58.00, 'paid', '183.jpg', 9, NULL, 0, ''),
(185, 1, 'Hut 1', '2026-10-24 17:45:16', '2026-10-24 17:45:16', '2026-10-24 17:46:07', 'online', 37.00, 'paid', '185.jpg', 2, NULL, 0, ''),
(186, 5, 'Outdoor 1', '2026-10-24 17:46:47', '2026-10-24 17:46:47', '2026-10-24 17:55:40', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(187, 5, 'Outdoor 1', '2026-10-24 18:05:57', '2026-10-24 18:05:58', '2026-10-24 18:06:15', 'cash', 25.00, 'paid', '', 2, NULL, 0, ''),
(188, 3, 'Hut 3', '2026-10-24 18:18:59', '2026-10-24 18:18:59', '2026-10-24 18:59:55', 'online', 164.00, 'paid', '188.jpg', 2, NULL, 0, ''),
(189, 5, 'Outdoor 1', '2026-10-24 18:21:22', '2026-10-24 18:21:22', '2026-10-24 18:21:26', 'cash', 15.00, 'paid', '', 2, NULL, 0, ''),
(190, 4, 'Hut 4', '2026-10-24 18:34:30', '2026-10-24 18:34:30', '2026-10-24 18:53:27', 'cash', 60.00, 'paid', '', 2, NULL, 0, ''),
(191, 6, 'Outdoor 2', '2026-10-24 18:39:25', '2026-10-24 18:39:25', '2026-10-24 18:56:17', 'cash', 68.00, 'paid', '', 2, NULL, 0, ''),
(192, 7, 'Outdoor 3', '2026-10-24 18:43:45', '2026-10-24 18:43:45', '2026-10-24 19:18:12', 'online', 15.00, 'paid', '192.jpg', 2, NULL, 0, ''),
(193, 1, 'Hut 1', '2026-10-24 18:43:55', '2026-10-24 18:43:55', '2026-10-24 18:55:34', 'cash', 28.00, 'paid', '', 2, NULL, 0, ''),
(194, 2, 'Hut 2', '2026-10-24 18:44:13', '2026-10-24 18:44:14', '2026-10-24 20:01:29', 'online', 20.00, 'paid', '194.jpg', 2, NULL, 0, ''),
(195, 5, 'Outdoor 1', '2026-10-24 18:44:33', '2026-10-24 18:44:33', '2026-11-02 13:06:00', 'online', 19.00, 'paid', '', 2, 12, 1, ''),
(196, 4, 'Hut 4', '2026-10-24 18:59:05', '2026-10-24 18:59:05', '2026-10-24 19:08:18', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(197, 6, 'Outdoor 2', '2026-10-24 19:00:17', '2026-10-24 19:00:17', '2026-10-24 20:37:59', 'online', 12.00, 'paid', '', 3, NULL, 0, ''),
(198, 1, 'Hut 1', '2026-10-24 19:06:21', '2026-10-24 19:06:21', '2026-10-24 20:10:41', 'cash', 40.00, 'paid', '', 3, NULL, 0, ''),
(199, 4, 'Hut 4', '2026-10-24 19:12:58', '2026-10-24 19:12:58', '2026-10-24 20:37:49', 'online', 52.00, 'paid', '199.jpg', 2, NULL, 0, ''),
(200, 3, 'Hut 3', '2026-10-24 19:20:31', '2026-10-24 19:20:31', '2026-10-24 19:52:46', 'cash', 27.00, 'paid', '', 2, NULL, 0, ''),
(201, 9, 'Indoor 1', '2026-10-24 19:23:56', '2026-10-24 19:23:56', NULL, 'credit', 5.00, 'credit', '', 2, 20, 1, ''),
(202, 7, 'Outdoor 3', '2026-10-24 19:54:55', '2026-10-24 19:54:55', '2026-10-24 20:09:05', 'cash', 13.00, 'paid', '', 9, NULL, 0, ''),
(203, 3, 'Hut 3', '2026-10-24 20:52:33', '2026-10-24 20:52:33', '2026-10-24 21:16:41', 'cash', 10.00, 'paid', '', 9, NULL, 0, ''),
(204, 6, 'Outdoor 2', '2026-10-24 21:40:27', '2026-10-24 21:40:28', '2026-11-02 13:06:00', 'online', 29.00, 'paid', '', 9, 12, 1, ''),
(205, 1, 'Hut 1', '2026-10-25 00:29:23', '2026-10-25 00:29:23', '2026-10-25 00:29:34', 'cash', 30.00, 'paid', '', 9, NULL, 0, ''),
(206, 2, 'Hut 2', '2026-10-25 07:22:30', '2026-10-25 07:22:31', '2026-10-25 08:37:19', 'online', 48.00, 'paid', '206.jpg', 3, NULL, 0, ''),
(207, 9, 'Indoor 1', '2026-10-25 07:24:02', '2026-10-25 07:24:03', NULL, 'credit', 5.00, 'credit', '', 3, 21, 1, ''),
(208, 3, 'Hut 3', '2026-10-25 07:29:21', '2026-10-25 07:29:21', '2026-10-25 08:38:20', 'online', 43.00, 'paid', '208.jpg', 3, NULL, 0, ''),
(209, 5, 'Outdoor 1', '2026-10-25 07:39:17', '2026-10-25 07:39:17', '2026-10-25 07:39:23', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(210, 5, 'Outdoor 1', '2026-10-25 07:51:44', '2026-10-25 07:51:44', '2026-10-25 16:30:54', 'online', 10.00, 'paid', '210.jpg', 3, NULL, 0, ''),
(211, 3, 'Hut 3', '2026-10-25 08:52:51', '2026-10-25 08:52:51', '2026-10-25 08:58:54', 'online', 5.00, 'paid', '211.jpg', 3, NULL, 0, ''),
(212, 6, 'Outdoor 2', '2026-10-25 09:17:06', '2026-10-25 09:17:06', '2026-10-25 09:17:11', 'cash', 14.00, 'paid', '', 3, NULL, 0, ''),
(213, 9, 'Indoor 1', '2026-10-25 10:13:45', '2026-10-25 10:13:46', NULL, 'credit', 20.00, 'credit', '', 3, 21, 1, ''),
(214, 4, 'Hut 4', '2026-10-25 10:17:49', '2026-10-25 10:17:49', '2026-10-25 10:51:58', 'online', 31.00, 'paid', '214.jpg', 3, NULL, 0, ''),
(215, 4, 'Hut 4', '2026-10-25 11:24:55', '2026-10-25 11:24:55', '2026-10-25 21:19:28', 'online', 40.00, 'paid', '215.jpg', 3, 10, 1, ''),
(216, 1, 'Hut 1', '2026-10-25 11:27:56', '2026-10-25 11:27:56', NULL, 'credit', 24.00, 'credit', '', 3, 18, 1, ''),
(217, 4, 'Hut 4', '2026-10-25 12:07:36', '2026-10-25 12:07:36', '2026-10-25 12:31:00', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(218, 3, 'Hut 3', '2026-10-25 12:31:05', '2026-10-25 12:31:05', '2026-10-25 12:45:12', 'cash', 24.00, 'paid', '', 3, NULL, 0, ''),
(219, 2, 'Hut 2', '2026-10-25 12:33:51', '2026-10-25 12:33:51', '2026-10-25 12:55:36', 'online', 20.00, 'paid', '219.jpg', 3, NULL, 0, ''),
(220, 1, 'Hut 1', '2026-10-25 12:54:13', '2026-10-25 12:54:13', '2026-10-25 13:17:30', 'online', 32.00, 'paid', '220.jpg', 3, NULL, 0, ''),
(221, 8, 'Outdoor 4', '2026-10-25 13:02:20', '2026-10-25 13:02:21', '2026-10-25 13:09:43', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(222, 4, 'Hut 4', '2026-10-25 13:19:30', '2026-10-25 13:19:30', '2026-10-25 16:02:24', 'online', 104.00, 'paid', '222.jpg', 3, NULL, 0, ''),
(223, 1, 'Hut 1', '2026-10-25 14:32:23', '2026-10-25 14:32:23', '2026-10-25 15:48:26', 'online', 65.00, 'paid', '223.jpg', 9, NULL, 0, ''),
(224, 3, 'Hut 3', '2026-10-25 14:32:36', '2026-10-25 14:32:36', '2026-10-25 14:43:16', 'cash', 31.00, 'paid', '', 9, NULL, 0, ''),
(225, 2, 'Hut 2', '2026-10-25 14:35:00', '2026-10-25 14:35:00', '2026-11-02 13:06:00', 'online', 7.00, 'paid', '', 9, 12, 1, ''),
(226, 2, 'Hut 2', '2026-10-25 14:59:51', '2026-10-25 14:59:52', '2026-10-25 15:20:06', 'cash', 42.00, 'paid', '', 9, 3, 0, ''),
(227, 6, 'Outdoor 2', '2026-10-25 15:07:43', '2026-10-25 15:07:43', '2026-10-25 15:07:49', 'cash', 8.00, 'paid', '', 9, NULL, 0, ''),
(228, 6, 'Outdoor 2', '2026-10-25 15:08:24', '2026-10-25 15:08:25', '2026-10-26 15:06:39', 'online', 7.00, 'paid', '', 9, 14, 1, ''),
(229, 8, 'Outdoor 4', '2026-10-25 15:35:26', '2026-10-25 15:35:26', '2026-10-25 15:38:27', 'online', 25.00, 'paid', '229.jpg', 3, NULL, 0, ''),
(230, 6, 'Outdoor 2', '2026-10-25 15:51:22', '2026-10-25 15:51:23', '2026-10-25 15:52:13', 'cash', 17.00, 'paid', '', 9, NULL, 0, ''),
(231, 7, 'Outdoor 3', '2026-10-25 15:51:41', '2026-10-25 15:51:41', '2026-10-25 16:20:58', 'cash', 10.00, 'paid', '', 9, NULL, 0, ''),
(233, 3, 'Hut 3', '2026-10-25 16:17:19', '2026-10-25 16:17:20', '2026-10-25 16:31:23', 'online', 32.00, 'paid', '', 9, NULL, 0, ''),
(234, 4, 'Hut 4', '2026-10-25 16:19:58', '2026-10-25 16:19:59', '2026-10-25 16:28:07', 'online', 47.00, 'paid', '234.jpg', 9, NULL, 0, ''),
(235, 6, 'Outdoor 2', '2026-10-25 16:22:48', '2026-10-25 16:22:48', NULL, 'credit', 10.00, 'credit', '', 9, 13, 1, ''),
(236, 1, 'Hut 1', '2026-10-25 16:38:12', '2026-10-25 16:38:12', '2026-10-25 16:39:05', 'cash', 41.00, 'paid', '', 9, NULL, 0, ''),
(237, 4, 'Hut 4', '2026-10-25 16:39:22', '2026-10-25 16:39:22', '2026-10-25 17:12:19', 'cash', 99.00, 'paid', '', 9, NULL, 0, ''),
(238, 2, 'Hut 2', '2026-10-25 17:29:11', '2026-10-25 17:29:11', '2026-10-25 17:29:53', 'cash', 37.00, 'paid', '', 9, NULL, 0, ''),
(239, 3, 'Hut 3', '2026-10-25 17:36:09', '2026-10-25 17:36:09', '2026-10-25 17:59:57', 'online', 62.00, 'paid', '239.jpg', 9, NULL, 0, ''),
(240, 4, 'Hut 4', '2026-10-25 17:36:45', '2026-10-25 17:36:45', '2026-10-25 18:56:39', 'online', 75.00, 'paid', '', 9, NULL, 0, ''),
(241, 1, 'Hut 1', '2026-10-25 18:09:14', '2026-10-25 18:09:14', '2026-10-25 18:34:18', 'cash', 25.00, 'paid', '', 9, NULL, 0, ''),
(242, 2, 'Hut 2', '2026-10-25 18:21:28', '2026-10-25 18:21:28', '2026-10-25 18:23:21', 'cash', 27.00, 'paid', '', 9, NULL, 0, ''),
(243, 3, 'Hut 3', '2026-10-25 18:27:25', '2026-10-25 18:27:25', '2026-10-25 18:44:29', 'cash', 85.00, 'paid', '', 9, NULL, 0, ''),
(244, 6, 'Outdoor 2', '2026-10-25 18:28:28', '2026-10-25 18:28:28', '2026-10-25 18:44:53', 'cash', 63.00, 'paid', '', 9, NULL, 0, ''),
(245, 1, 'Hut 1', '2026-10-25 18:38:40', '2026-10-25 18:38:40', '2026-10-25 19:00:05', 'cash', 75.00, 'paid', '', 3, NULL, 0, ''),
(246, 5, 'Outdoor 1', '2026-10-25 18:43:23', '2026-10-25 18:43:23', '2026-10-25 18:56:30', 'online', 72.00, 'paid', '246.jpg', 9, NULL, 0, ''),
(247, 8, 'Outdoor 4', '2026-10-25 18:58:29', '2026-10-25 18:58:29', '2026-10-25 19:26:11', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(248, 7, 'Outdoor 3', '2026-10-25 19:02:53', '2026-10-25 19:02:53', '2026-10-25 19:26:04', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(249, 2, 'Hut 2', '2026-10-25 19:37:08', '2026-10-25 19:37:09', '2026-10-25 19:45:44', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(250, 6, 'Outdoor 2', '2026-10-25 19:39:50', '2026-10-25 19:39:51', '2026-10-25 19:43:36', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(251, 4, 'Hut 4', '2026-10-25 20:37:35', '2026-10-25 20:37:35', '2026-10-25 21:14:26', 'online', 44.00, 'paid', '251.jpg', 3, NULL, 0, ''),
(252, 1, 'Hut 1', '2026-10-25 20:45:20', '2026-10-25 20:45:20', NULL, 'credit', 12.00, 'credit', '', 3, 10, 1, ''),
(253, 2, 'Hut 2', '2026-10-25 20:51:35', '2026-10-25 20:51:35', '2026-11-02 13:06:00', 'online', 19.00, 'paid', '', 3, 12, 1, ''),
(254, 8, 'Outdoor 4', '2026-10-25 21:37:57', '2026-10-25 21:37:57', '2026-10-25 21:38:10', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(255, 9, 'Indoor 1', '2026-10-25 21:50:38', '2026-10-25 21:50:39', NULL, 'credit', 5.00, 'credit', '', 9, 20, 1, ''),
(256, 5, 'Outdoor 1', '2026-10-25 21:50:57', '2026-10-25 21:50:57', '2026-10-25 22:27:59', 'cash', 34.00, 'paid', '', 3, NULL, 0, ''),
(257, 4, 'Hut 4', '2026-10-25 22:02:52', '2026-10-25 22:02:52', '2026-10-25 22:28:41', 'online', 29.00, 'paid', '257.jpg', 3, NULL, 0, ''),
(258, 8, 'Outdoor 4', '2026-10-25 22:28:52', '2026-10-25 22:28:52', '2026-10-25 22:48:27', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(259, 3, 'Hut 3', '2026-10-25 22:39:02', '2026-10-25 22:39:02', '2026-10-25 22:48:09', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(260, 1, 'Hut 1', '2026-10-25 22:39:55', '2026-10-25 22:39:55', '2026-10-25 22:59:26', 'cash', 37.00, 'paid', '', 3, NULL, 0, ''),
(261, 8, 'Outdoor 4', '2026-10-25 22:48:35', '2026-10-25 22:48:35', '2026-10-25 23:14:15', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(262, 3, 'Hut 3', '2026-10-25 23:03:11', '2026-10-25 23:03:11', '2026-10-25 23:13:48', 'online', 19.00, 'paid', '262.jpg', 3, NULL, 0, ''),
(263, 1, 'Hut 1', '2026-10-25 23:52:01', '2026-10-25 23:52:01', '2026-10-25 23:52:56', 'online', 31.00, 'paid', '263.jpg', 3, NULL, 0, ''),
(264, 4, 'Hut 4', '2026-10-26 07:06:02', '2026-10-26 07:06:02', '2026-10-26 07:07:02', 'online', 37.00, 'paid', '264.jpg', 9, NULL, 0, ''),
(265, 9, 'Indoor 1', '2026-10-26 07:10:43', '2026-10-26 07:10:44', NULL, 'credit', 10.00, 'credit', '', 9, 20, 1, ''),
(266, 3, 'Hut 3', '2026-10-26 07:41:52', '2026-10-26 07:41:52', NULL, 'credit', 49.00, 'credit', '', 9, 18, 1, ''),
(267, 2, 'Hut 2', '2026-10-26 08:18:18', '2026-10-26 08:18:18', '2026-10-26 09:05:38', 'cash', 19.00, 'paid', '', 9, NULL, 0, ''),
(268, 5, 'Outdoor 1', '2026-10-26 08:18:34', '2026-10-26 08:18:34', '2026-10-26 08:34:41', 'cash', 22.00, 'paid', '', 9, NULL, 0, ''),
(269, 3, 'Hut 3', '2026-10-26 08:34:50', '2026-10-26 08:34:50', '2026-10-26 09:59:48', 'online', 24.00, 'paid', '269.jpg', 9, NULL, 0, ''),
(270, 1, 'Hut 1', '2026-10-26 09:01:31', '2026-10-26 09:01:31', '2026-10-26 09:20:25', 'online', 14.00, 'paid', '', 9, NULL, 0, ''),
(271, 4, 'Hut 4', '2026-10-26 09:01:43', '2026-10-26 09:01:43', NULL, 'credit', 7.00, 'credit', '', 9, 18, 1, ''),
(272, 5, 'Outdoor 1', '2026-10-26 09:48:27', '2026-10-26 09:48:28', '2026-10-26 10:04:56', 'cash', 27.00, 'paid', '', 9, NULL, 0, ''),
(273, 4, 'Hut 4', '2026-10-26 10:33:32', '2026-10-26 10:33:33', NULL, 'credit', 17.00, 'credit', '', 9, 33, 1, ''),
(274, 1, 'Hut 1', '2026-10-26 10:33:42', '2026-10-26 10:33:42', '2026-10-26 11:27:32', 'online', 20.00, 'paid', '274.jpg', 9, NULL, 0, ''),
(275, 4, 'Hut 4', '2026-10-26 10:52:34', '2026-10-26 10:52:34', '2026-10-26 11:03:41', 'cash', 7.00, 'paid', '', 9, NULL, 0, ''),
(276, 5, 'Outdoor 1', '2026-10-26 10:52:45', '2026-10-26 10:52:45', '2026-10-26 10:59:20', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(277, 4, 'Hut 4', '2026-10-26 11:27:45', '2026-10-26 11:27:45', NULL, 'credit', 19.00, 'credit', '', 9, 18, 1, ''),
(278, 1, 'Hut 1', '2026-10-26 11:46:21', '2026-10-26 11:46:21', '2026-10-26 12:16:20', 'online', 19.00, 'paid', '278.jpg', 9, NULL, 0, ''),
(279, 2, 'Hut 2', '2026-10-26 12:16:41', '2026-10-26 12:16:41', '2026-10-26 13:20:38', 'online', 27.00, 'paid', '279.jpg', 9, NULL, 0, ''),
(280, 4, 'Hut 4', '2026-10-26 12:42:00', '2026-10-26 12:42:01', '2026-10-26 12:43:47', 'cash', 22.00, 'paid', '', 9, NULL, 0, ''),
(281, 1, 'Hut 1', '2026-10-26 12:48:05', '2026-10-26 12:48:06', '2026-10-26 12:55:07', 'cash', 40.00, 'paid', '', 9, NULL, 0, ''),
(282, 1, 'Hut 1', '2026-10-26 13:13:49', '2026-10-26 13:13:49', '2026-10-26 13:59:27', 'online', 32.00, 'paid', '282.jpg', 9, NULL, 0, ''),
(283, 3, 'Hut 3', '2026-10-26 13:14:09', '2026-10-26 13:14:09', '2026-10-26 13:28:34', 'online', 24.00, 'paid', '283.jpg', 9, NULL, 0, ''),
(284, 2, 'Hut 2', '2026-10-26 13:29:25', '2026-10-26 13:29:25', '2026-10-26 13:45:02', 'online', 19.00, 'paid', '284.jpg', 9, NULL, 0, ''),
(285, 3, 'Hut 3', '2026-10-26 13:45:09', '2026-10-26 13:45:09', '2026-10-26 14:02:30', 'cash', 15.00, 'paid', '', 9, NULL, 0, ''),
(286, 4, 'Hut 4', '2026-10-26 13:45:23', '2026-10-26 13:45:23', '2026-10-31 11:13:57', 'online', 31.00, 'paid', '', 9, 46, 1, ''),
(287, 5, 'Outdoor 1', '2026-10-26 14:33:35', '2026-10-26 14:33:35', '2026-10-26 14:33:55', 'cash', 35.00, 'paid', '', 9, NULL, 0, ''),
(288, 1, 'Hut 1', '2026-10-26 14:36:32', '2026-10-26 14:36:32', '2026-10-26 14:49:53', 'online', 56.00, 'paid', '288.jpg', 9, NULL, 0, ''),
(289, 3, 'Hut 3', '2026-10-26 14:37:21', '2026-10-26 14:37:21', '2026-10-26 14:38:18', 'online', 26.00, 'paid', '289.jpg', 9, NULL, 0, ''),
(290, 5, 'Outdoor 1', '2026-10-26 14:56:10', '2026-10-26 14:56:10', '2026-10-26 15:06:39', 'online', 7.00, 'paid', '', 9, 14, 1, ''),
(291, 1, 'Hut 1', '2026-10-26 15:17:18', '2026-10-26 15:17:18', '2026-10-26 16:24:49', 'cash', 24.00, 'paid', '', 3, NULL, 0, ''),
(292, 8, 'Outdoor 4', '2026-10-26 15:19:31', '2026-10-26 15:19:31', '2026-10-26 15:31:42', 'cash', 8.00, 'paid', '', 3, NULL, 0, ''),
(293, 8, 'Outdoor 4', '2026-10-26 15:38:15', '2026-10-26 15:38:15', '2026-10-26 15:56:07', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(294, 3, 'Hut 3', '2026-10-26 15:41:20', '2026-10-26 15:41:20', '2026-10-26 16:17:31', 'cash', 68.00, 'paid', '', 9, NULL, 0, ''),
(295, 8, 'Outdoor 4', '2026-10-26 15:56:24', '2026-10-26 15:56:24', '2026-10-26 16:06:28', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(296, 6, 'Outdoor 2', '2026-10-26 15:59:35', '2026-10-26 15:59:35', '2026-10-26 16:19:19', 'online', 107.00, 'paid', '296.jpg', 3, NULL, 0, ''),
(297, 2, 'Hut 2', '2026-10-26 16:03:44', '2026-10-26 16:03:44', '2026-10-26 17:22:39', 'online', 74.00, 'paid', '297.jpg', 9, NULL, 0, ''),
(298, 5, 'Outdoor 1', '2026-10-26 16:09:52', '2026-10-26 16:09:52', '2026-10-26 16:10:35', 'online', 45.00, 'paid', '298.jpg', 9, NULL, 0, ''),
(299, 5, 'Outdoor 1', '2026-10-26 16:15:14', '2026-10-26 16:15:14', '2026-10-26 16:15:28', 'cash', 55.00, 'paid', '', 9, NULL, 0, ''),
(300, 6, 'Outdoor 2', '2026-10-26 16:27:17', '2026-10-26 16:27:17', '2026-10-26 16:33:16', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(301, 8, 'Outdoor 4', '2026-10-26 16:27:38', '2026-10-26 16:27:38', '2026-10-26 16:28:16', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(302, 4, 'Hut 4', '2026-10-26 16:55:02', '2026-10-26 16:55:02', '2026-10-26 17:10:44', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(303, 5, 'Outdoor 1', '2026-10-26 16:55:29', '2026-10-26 16:55:29', '2026-10-26 17:23:57', 'cash', 46.00, 'paid', '', 3, NULL, 0, ''),
(304, 3, 'Hut 3', '2026-10-26 17:03:15', '2026-10-26 17:03:15', '2026-10-26 17:09:09', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(305, 4, 'Hut 4', '2026-10-26 17:13:25', '2026-10-26 17:13:25', '2026-10-26 17:24:33', 'online', 19.00, 'paid', '305.jpg', 3, NULL, 0, ''),
(306, 8, 'Outdoor 4', '2026-10-26 17:18:02', '2026-10-26 17:18:02', '2026-10-26 17:37:07', 'cash', 53.00, 'paid', '', 3, NULL, 0, ''),
(307, 5, 'Outdoor 1', '2026-10-26 17:29:06', '2026-10-26 17:29:06', '2026-10-26 17:29:34', 'online', 25.00, 'paid', '307.jpg', 3, NULL, 0, ''),
(308, 5, 'Outdoor 1', '2026-10-26 17:32:38', '2026-10-26 17:32:38', '2026-10-26 17:34:34', 'cash', 70.00, 'paid', '', 3, NULL, 0, ''),
(309, 5, 'Outdoor 1', '2026-10-26 17:35:35', '2026-10-26 17:35:35', '2026-10-26 17:36:37', 'online', 20.00, 'paid', '309.jpg', 3, NULL, 0, ''),
(310, 4, 'Hut 4', '2026-10-26 17:50:59', '2026-10-26 17:50:59', NULL, 'credit', 48.00, 'credit', '', 3, 18, 1, ''),
(311, 2, 'Hut 2', '2026-10-26 18:01:59', '2026-10-26 18:01:59', '2026-10-26 18:50:22', 'cash', 45.00, 'paid', '', 3, NULL, 0, ''),
(312, 3, 'Hut 3', '2026-10-26 18:24:54', '2026-10-26 18:24:55', '2026-10-26 19:08:36', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(313, 5, 'Outdoor 1', '2026-10-26 18:26:42', '2026-10-26 18:26:42', '2026-10-26 18:26:45', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(314, 5, 'Outdoor 1', '2026-10-26 18:29:29', '2026-10-26 18:29:29', '2026-10-26 20:00:00', 'online', 56.00, 'paid', '314.jpg', 3, NULL, 0, ''),
(315, 1, 'Hut 1', '2026-10-26 18:36:31', '2026-10-26 18:36:31', '2026-10-26 19:07:54', 'online', 12.00, 'paid', '315.jpg', 3, NULL, 0, ''),
(316, 1, 'Hut 1', '2026-10-26 19:25:39', '2026-10-26 19:25:39', '2026-10-26 19:25:43', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(317, 3, 'Hut 3', '2026-10-26 19:55:18', '2026-10-26 19:55:18', '2026-10-26 20:18:12', 'cash', 24.00, 'paid', '', 3, NULL, 0, ''),
(318, 1, 'Hut 1', '2026-10-26 19:55:27', '2026-10-26 19:55:28', '2026-11-02 13:06:00', 'online', 74.00, 'paid', '', 3, 12, 1, ''),
(319, 2, 'Hut 2', '2026-10-26 19:59:02', '2026-10-26 19:59:02', '2026-10-26 21:30:46', 'online', 29.00, 'paid', '319.jpg', 3, NULL, 0, ''),
(320, 5, 'Outdoor 1', '2026-10-26 20:04:54', '2026-10-26 20:04:54', '2026-10-26 22:18:19', 'online', 16.00, 'paid', '320.jpg', 3, NULL, 0, ''),
(322, 6, 'Outdoor 2', '2026-10-26 20:12:04', '2026-10-26 20:12:04', '2026-10-26 20:49:00', 'online', 82.00, 'paid', '322.jpg', 3, NULL, 0, ''),
(323, 8, 'Outdoor 4', '2026-10-26 20:20:29', '2026-10-26 20:20:29', '2026-10-26 20:20:33', 'cash', 3.00, 'paid', '', 3, NULL, 0, ''),
(325, 6, 'Outdoor 2', '2026-10-26 21:14:04', '2026-10-26 21:14:04', '2026-10-26 21:15:15', 'online', 10.00, 'paid', '325.jpg', 3, NULL, 0, ''),
(326, 8, 'Outdoor 4', '2026-10-26 21:45:26', '2026-10-26 21:45:26', '2026-10-26 21:45:50', 'cash', 6.00, 'paid', '', 3, NULL, 0, ''),
(327, 6, 'Outdoor 2', '2026-10-26 21:46:02', '2026-10-26 21:46:02', '2026-10-31 11:13:57', 'online', 26.00, 'paid', '', 3, 46, 1, ''),
(328, 1, 'Hut 1', '2026-10-26 23:04:05', NULL, NULL, 'credit', 0.00, 'credit', '', 3, 11, 1, ''),
(329, 5, 'Outdoor 1', '2026-10-26 23:16:11', '2026-10-26 23:16:11', '2026-10-27 00:07:18', 'online', 52.00, 'paid', '329.jpg', 3, NULL, 0, ''),
(330, 5, 'Outdoor 1', '2026-10-27 00:08:55', '2026-10-27 00:08:55', '2026-10-27 00:09:58', 'online', 20.00, 'paid', '330.jpg', 3, NULL, 0, ''),
(331, 3, 'Hut 3', '2026-10-27 07:28:52', '2026-10-27 07:28:52', '2026-10-27 07:56:32', 'online', 35.00, 'paid', '331.jpg', 9, NULL, 0, ''),
(332, 2, 'Hut 2', '2026-10-27 08:12:31', '2026-10-27 08:12:32', '2026-10-27 08:12:40', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(333, 4, 'Hut 4', '2026-10-27 09:18:14', '2026-10-27 09:18:14', '2026-10-27 10:51:31', 'cash', 81.00, 'paid', '', 9, 45, 1, ''),
(334, 2, 'Hut 2', '2026-10-27 10:07:48', '2026-10-27 10:07:49', NULL, 'credit', 19.00, 'credit', '', 9, 10, 1, ''),
(335, 5, 'Outdoor 1', '2026-10-27 10:17:16', '2026-10-27 10:17:17', NULL, 'credit', 10.00, 'credit', '', 9, 13, 1, ''),
(336, 5, 'Outdoor 1', '2026-10-27 10:35:51', '2026-10-27 10:35:51', '2026-10-27 10:36:16', 'online', 5.00, 'paid', '336.jpg', 9, NULL, 0, ''),
(337, 3, 'Hut 3', '2026-10-27 11:25:48', '2026-10-27 11:25:49', '2026-10-27 11:59:57', 'online', 56.00, 'paid', '337.jpg', 9, NULL, 0, ''),
(338, 2, 'Hut 2', '2026-10-27 11:35:23', '2026-10-27 11:35:24', '2026-10-27 13:00:54', 'online', 92.00, 'paid', '338.jpg', 9, NULL, 0, ''),
(339, 4, 'Hut 4', '2026-10-27 11:53:17', '2026-10-27 11:53:17', '2026-10-28 09:21:06', 'online', 46.00, 'paid', '339.jpg', 9, 47, 1, ''),
(340, 6, 'Outdoor 2', '2026-10-27 12:23:25', '2026-10-27 12:23:25', '2026-10-27 12:31:47', 'cash', 19.00, 'paid', '', 9, NULL, 0, ''),
(342, 3, 'Hut 3', '2026-10-27 12:42:36', '2026-10-27 12:42:36', '2026-10-27 13:23:31', 'online', 31.00, 'paid', '342.jpg', 9, NULL, 0, ''),
(343, 2, 'Hut 2', '2026-10-27 13:06:53', '2026-10-27 13:06:53', '2026-10-27 13:07:26', 'online', 7.00, 'paid', '343.jpg', 9, NULL, 0, ''),
(344, 5, 'Outdoor 1', '2026-10-27 13:08:59', '2026-10-27 13:08:59', '2026-10-27 13:45:29', 'online', 45.00, 'paid', '344.jpg', 9, NULL, 0, ''),
(345, 9, 'Indoor 1', '2026-10-27 13:11:22', '2026-10-27 13:11:22', NULL, 'credit', 10.00, 'credit', '', 9, 20, 1, ''),
(346, 2, 'Hut 2', '2026-10-27 13:16:41', '2026-10-27 13:16:41', '2026-10-27 13:17:02', 'online', 7.00, 'paid', '346.jpg', 9, NULL, 0, ''),
(347, 4, 'Hut 4', '2026-10-27 14:04:15', '2026-10-27 14:04:15', '2026-10-27 14:05:59', 'online', 39.00, 'paid', '347.jpg', 9, NULL, 0, ''),
(348, 4, 'Hut 4', '2026-10-27 14:55:47', '2026-10-27 14:55:47', '2026-10-27 15:32:12', 'online', 86.00, 'paid', '348.jpg', 9, NULL, 0, ''),
(349, 2, 'Hut 2', '2026-10-27 14:56:09', '2026-10-27 14:56:09', '2026-10-27 15:09:25', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(350, 3, 'Hut 3', '2026-10-27 15:37:36', '2026-10-27 15:37:36', '2026-10-27 15:55:21', 'online', 19.00, 'paid', '350.jpg', 3, NULL, 0, ''),
(351, 2, 'Hut 2', '2026-10-27 15:38:43', '2026-10-27 15:38:43', '2026-10-27 15:39:22', 'online', 70.00, 'paid', '351.jpg', 9, NULL, 0, ''),
(352, 4, 'Hut 4', '2026-10-27 15:39:02', '2026-10-27 15:39:02', '2026-10-27 16:26:56', 'online', 10.00, 'paid', '352.jpg', 3, NULL, 0, ''),
(353, 5, 'Outdoor 1', '2026-10-27 15:47:57', '2026-10-27 15:47:57', '2026-10-27 16:07:41', 'online', 186.00, 'paid', '353.jpg', 9, NULL, 0, ''),
(354, 1, 'Hut 1', '2026-10-27 15:50:54', '2026-10-27 15:50:55', NULL, 'credit', 19.00, 'credit', '', 3, 18, 1, ''),
(355, 6, 'Outdoor 2', '2026-10-27 15:58:17', '2026-10-27 15:58:17', '2026-10-27 15:58:25', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(356, 2, 'Hut 2', '2026-10-27 15:58:19', '2026-10-27 15:58:19', '2026-11-01 15:52:17', 'online', 54.00, 'paid', '', 3, 14, 1, ''),
(357, 6, 'Outdoor 2', '2026-10-27 16:04:02', '2026-10-27 16:04:02', '2026-10-27 16:04:06', 'cash', 8.00, 'paid', '', 3, NULL, 0, ''),
(358, 6, 'Outdoor 2', '2026-10-27 16:07:21', '2026-10-27 16:07:22', '2026-10-27 16:15:26', 'cash', 36.00, 'paid', '', 3, NULL, 0, ''),
(359, 5, 'Outdoor 1', '2026-10-27 16:13:00', '2026-10-27 16:13:00', '2026-10-27 16:33:16', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(360, 3, 'Hut 3', '2026-10-27 16:27:15', '2026-10-27 16:27:15', '2026-10-27 16:47:10', 'cash', 33.00, 'paid', '', 3, NULL, 0, ''),
(361, 6, 'Outdoor 2', '2026-10-27 16:32:28', '2026-10-27 16:32:28', '2026-10-27 16:33:09', 'online', 20.00, 'paid', '361.jpg', 3, NULL, 0, ''),
(362, 4, 'Hut 4', '2026-10-27 16:47:31', '2026-10-27 16:47:32', '2026-10-27 16:47:55', 'online', 33.00, 'paid', '362.jpg', 3, NULL, 0, ''),
(364, 4, 'Hut 4', '2026-10-27 16:50:53', '2026-10-27 16:50:53', '2026-10-27 16:54:32', 'cash', 17.00, 'paid', '', 3, NULL, 0, ''),
(365, 5, 'Outdoor 1', '2026-10-27 17:06:50', '2026-10-27 17:06:50', '2026-10-27 17:09:14', 'cash', 50.00, 'paid', '', 3, NULL, 0, ''),
(366, 5, 'Outdoor 1', '2026-10-27 17:12:11', '2026-10-27 17:12:11', '2026-10-27 17:12:42', 'online', 60.00, 'paid', '366.jpg', 3, NULL, 0, ''),
(367, 4, 'Hut 4', '2026-10-27 17:24:25', '2026-10-27 17:24:25', '2026-10-27 18:50:21', 'online', 19.00, 'paid', '367.jpg', 3, NULL, 0, ''),
(368, 1, 'Hut 1', '2026-10-27 17:33:00', '2026-10-27 17:33:00', NULL, 'credit', 22.00, 'credit', '', 3, 10, 1, ''),
(369, 3, 'Hut 3', '2026-10-27 17:40:18', '2026-10-27 17:40:18', '2026-10-27 18:27:57', 'cash', 45.00, 'paid', '', 3, NULL, 0, ''),
(370, 5, 'Outdoor 1', '2026-10-27 17:48:31', '2026-10-27 17:48:32', '2026-10-27 17:50:49', 'cash', 23.00, 'paid', '', 3, NULL, 0, ''),
(371, 8, 'Outdoor 4', '2026-10-27 17:59:01', '2026-10-27 17:59:01', '2026-10-31 17:36:57', 'cash', 20.00, 'paid', '', 3, 48, 1, ''),
(372, 7, 'Outdoor 3', '2026-10-27 18:00:55', '2026-10-27 18:00:55', '2026-10-27 18:01:22', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(374, 3, 'Hut 3', '2026-10-27 18:28:02', '2026-10-27 18:28:02', NULL, 'credit', 20.00, 'credit', '', 3, 49, 1, ''),
(375, 5, 'Outdoor 1', '2026-10-27 18:30:58', '2026-10-27 18:30:59', '2026-10-27 18:49:04', 'cash', 45.00, 'paid', '', 3, NULL, 0, ''),
(376, 2, 'Hut 2', '2026-10-27 18:34:41', '2026-10-27 18:34:41', '2026-10-27 19:31:45', 'online', 17.00, 'paid', '376.jpg', 3, NULL, 0, ''),
(377, 3, 'Hut 3', '2026-10-27 18:37:46', '2026-10-27 18:37:46', '2026-10-27 18:59:48', 'online', 40.00, 'paid', '377.jpg', 3, NULL, 0, ''),
(378, 4, 'Hut 4', '2026-10-27 18:53:20', '2026-10-27 18:53:20', '2026-10-29 12:49:47', 'online', 9.00, 'paid', '', 3, 50, 1, ''),
(379, 4, 'Hut 4', '2026-10-27 19:02:44', '2026-10-27 19:02:45', '2026-10-27 19:37:22', 'online', 24.00, 'paid', '379.jpg', 3, NULL, 0, ''),
(380, 5, 'Outdoor 1', '2026-10-27 19:30:56', '2026-10-27 19:30:56', '2026-10-27 19:31:02', 'cash', 21.00, 'paid', '', 3, NULL, 0, ''),
(381, 2, 'Hut 2', '2026-10-27 19:35:42', '2026-10-27 19:35:42', '2026-10-27 20:08:28', 'online', 25.00, 'paid', '381.jpg', 3, NULL, 0, ''),
(382, 1, 'Hut 1', '2026-10-27 19:46:21', '2026-10-27 19:46:21', '2026-10-27 21:03:43', 'online', 56.00, 'paid', '382.jpg', 3, NULL, 0, ''),
(383, 3, 'Hut 3', '2026-10-27 19:47:54', '2026-10-27 19:47:54', '2026-10-27 20:11:57', 'online', 32.00, 'paid', '383.jpg', 3, NULL, 0, ''),
(384, 8, 'Outdoor 4', '2026-10-27 20:12:55', '2026-10-27 20:12:55', '2026-10-27 20:12:57', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(385, 5, 'Outdoor 1', '2026-10-27 20:26:07', '2026-10-27 20:26:08', '2026-10-27 22:09:01', 'online', 6.00, 'paid', '385.jpg', 3, NULL, 0, ''),
(386, 2, 'Hut 2', '2026-10-27 20:32:56', '2026-10-27 20:32:56', '2026-10-28 12:08:01', 'online', 36.00, 'paid', '', 9, 31, 1, ''),
(387, 4, 'Hut 4', '2026-10-27 20:34:12', '2026-10-27 20:34:13', '2026-10-27 22:21:24', 'online', 7.00, 'paid', '387.jpg', 3, NULL, 0, ''),
(388, 6, 'Outdoor 2', '2026-10-27 22:06:10', '2026-10-27 22:06:11', '2026-10-27 22:06:25', 'online', 10.00, 'paid', '388.jpg', 3, NULL, 0, ''),
(389, 3, 'Hut 3', '2026-10-27 22:09:07', '2026-10-27 22:09:07', '2026-10-27 22:09:12', 'cash', 14.00, 'paid', '', 3, NULL, 0, ''),
(390, 1, 'Hut 1', '2026-10-27 22:09:17', '2026-10-27 22:09:17', '2026-10-27 22:23:09', 'online', 20.00, 'paid', '390.jpg', 3, NULL, 0, ''),
(391, 6, 'Outdoor 2', '2026-10-27 22:23:32', '2026-10-27 22:23:32', '2026-10-27 22:46:31', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(392, 8, 'Outdoor 4', '2026-10-27 22:23:39', '2026-10-27 22:23:40', '2026-10-27 22:26:40', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(393, 3, 'Hut 3', '2026-10-27 22:49:01', '2026-10-27 22:49:01', '2026-10-27 23:16:28', 'cash', 19.00, 'paid', '', 3, NULL, 0, ''),
(394, 1, 'Hut 1', '2026-10-27 23:23:18', '2026-10-27 23:23:19', '2026-10-28 00:05:14', 'online', 59.00, 'paid', '394.jpg', 3, NULL, 0, ''),
(395, 9, 'Indoor 1', '2026-10-28 07:54:12', '2026-10-28 07:54:13', NULL, 'credit', 30.00, 'credit', '', 9, 13, 1, ''),
(396, 5, 'Outdoor 1', '2026-10-28 08:06:08', '2026-10-28 08:06:08', '2026-10-28 08:15:17', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(397, 1, 'Hut 1', '2026-10-28 08:35:22', '2026-10-28 08:35:22', '2026-10-28 08:55:17', 'online', 12.00, 'paid', '397.jpg', 9, NULL, 0, ''),
(398, 2, 'Hut 2', '2026-10-28 08:35:29', '2026-10-28 08:35:29', NULL, 'credit', 86.00, 'credit', '', 9, 18, 1, ''),
(399, 4, 'Hut 4', '2026-10-28 08:35:45', '2026-10-28 08:35:45', '2026-10-28 09:09:19', 'online', 32.00, 'paid', '399.jpg', 9, NULL, 0, ''),
(400, 3, 'Hut 3', '2026-10-28 08:42:12', '2026-10-28 08:42:12', '2026-10-28 09:02:00', 'online', 37.00, 'paid', '400.jpg', 9, NULL, 0, ''),
(401, 5, 'Outdoor 1', '2026-10-28 09:01:18', '2026-10-28 09:01:18', '2026-10-28 09:28:27', 'online', 22.00, 'paid', '401.jpg', 9, NULL, 0, ''),
(402, 1, 'Hut 1', '2026-10-28 09:01:31', '2026-10-28 09:01:32', '2026-10-28 09:16:34', 'online', 31.00, 'paid', '402.jpg', 9, NULL, 0, ''),
(403, 1, 'Hut 1', '2026-10-28 09:34:14', '2026-10-28 09:34:14', '2026-10-28 11:10:17', 'online', 61.00, 'paid', '', 9, 3, 1, ''),
(404, 3, 'Hut 3', '2026-10-28 09:34:25', '2026-10-28 09:34:25', '2026-10-28 09:59:05', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(405, 2, 'Hut 2', '2026-10-28 09:58:54', '2026-10-28 09:58:54', '2026-10-28 10:55:25', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(406, 4, 'Hut 4', '2026-10-28 09:59:10', '2026-10-28 09:59:11', '2026-10-28 10:43:04', 'online', 48.00, 'paid', '406.jpg', 9, NULL, 0, ''),
(407, 3, 'Hut 3', '2026-10-28 11:51:35', '2026-10-28 11:51:35', '2026-10-28 11:52:14', 'online', 24.00, 'paid', '407.jpg', 9, NULL, 0, ''),
(408, 2, 'Hut 2', '2026-10-28 11:52:22', '2026-10-28 11:52:22', '2026-10-28 12:08:01', 'online', 31.00, 'paid', '', 9, 31, 1, ''),
(409, 1, 'Hut 1', '2026-10-28 12:09:52', '2026-10-28 12:39:46', '2026-10-28 12:39:54', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(410, 2, 'Hut 2', '2026-10-28 12:40:09', '2026-10-28 12:40:09', '2026-10-28 13:19:55', 'online', 27.00, 'paid', '410.jpg', 9, NULL, 0, ''),
(411, 3, 'Hut 3', '2026-10-28 12:40:17', '2026-10-28 12:40:17', '2026-10-28 12:46:56', 'online', 22.00, 'paid', '411.jpg', 9, NULL, 0, ''),
(412, 4, 'Hut 4', '2026-10-28 12:40:41', '2026-10-28 12:40:41', '2026-10-28 13:17:17', 'online', 79.00, 'paid', '412.jpg', 9, NULL, 0, ''),
(413, 1, 'Hut 1', '2026-10-28 12:47:36', '2026-10-28 12:47:36', '2026-10-28 12:47:39', 'cash', 15.00, 'paid', '', 9, NULL, 0, ''),
(415, 4, 'Hut 4', '2026-10-28 14:36:49', '2026-10-28 14:36:49', '2026-10-28 14:44:09', 'cash', 41.00, 'paid', '', 9, NULL, 0, ''),
(416, 2, 'Hut 2', '2026-10-28 14:37:10', '2026-10-28 14:37:10', '2026-10-28 15:00:45', 'online', 17.00, 'paid', '416.jpg', 9, NULL, 0, ''),
(417, 1, 'Hut 1', '2026-10-28 14:37:26', '2026-10-28 14:37:26', '2026-10-28 14:59:08', 'online', 30.00, 'paid', '417.jpg', 9, NULL, 0, ''),
(418, 5, 'Outdoor 1', '2026-10-28 14:44:19', '2026-10-28 14:44:20', '2026-10-28 14:59:24', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(419, 5, 'Outdoor 1', '2026-10-28 15:07:19', '2026-10-28 15:07:19', '2026-10-28 15:07:51', 'online', 24.00, 'paid', '419.jpg', 9, NULL, 0, ''),
(420, 3, 'Hut 3', '2026-10-28 15:09:09', '2026-10-28 15:09:09', NULL, 'credit', 34.00, 'credit', '', 9, 18, 1, ''),
(421, 2, 'Hut 2', '2026-10-28 15:29:52', '2026-10-28 15:29:52', '2026-10-28 15:44:55', 'online', 56.00, 'paid', '421.jpg', 9, NULL, 0, ''),
(422, 5, 'Outdoor 1', '2026-10-28 15:33:12', '2026-10-28 15:33:12', '2026-10-28 15:33:36', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(423, 4, 'Hut 4', '2026-10-28 15:43:43', '2026-10-28 15:43:43', '2026-10-28 16:04:16', 'cash', 45.00, 'paid', '', 9, NULL, 0, ''),
(424, 5, 'Outdoor 1', '2026-10-28 15:45:11', '2026-10-28 15:45:11', '2026-10-28 15:45:15', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(425, 6, 'Outdoor 2', '2026-10-28 15:47:08', '2026-10-28 15:47:09', '2026-10-28 15:59:15', 'cash', 70.00, 'paid', '', 9, NULL, 0, '');
INSERT INTO `sales` (`id`, `table_id`, `table_name`, `opened_at`, `service_started_at`, `closed_at`, `payment_method`, `total`, `status`, `slip_image`, `waiter_id`, `customer_id`, `held`, `pay_review`) VALUES
(426, 6, 'Outdoor 2', '2026-10-28 16:09:10', '2026-10-28 16:09:10', '2026-10-28 16:12:53', 'cash', 23.00, 'paid', '', 3, NULL, 0, ''),
(427, 1, 'Hut 1', '2026-10-28 16:23:33', '2026-10-28 16:23:33', '2026-10-28 16:58:57', 'online', 99.00, 'paid', '427.jpg', 3, NULL, 0, ''),
(428, 9, 'Indoor 1', '2026-10-28 16:32:02', '2026-10-28 16:32:02', NULL, 'credit', 5.00, 'credit', '', 9, 21, 1, ''),
(429, 8, 'Outdoor 4', '2026-10-28 16:43:07', '2026-10-28 16:43:07', '2026-10-28 16:53:35', 'cash', 25.00, 'paid', '', 9, NULL, 0, ''),
(430, 6, 'Outdoor 2', '2026-10-28 16:55:59', '2026-10-28 16:55:59', '2026-10-28 17:40:53', 'cash', 33.00, 'paid', '', 9, NULL, 0, ''),
(431, 5, 'Outdoor 1', '2026-10-28 17:18:59', '2026-10-28 17:18:59', '2026-10-28 17:32:17', 'online', 69.00, 'paid', '431.jpg', 3, NULL, 0, ''),
(432, 8, 'Outdoor 4', '2026-10-28 17:22:34', '2026-10-28 17:22:34', '2026-10-28 17:33:46', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(433, 5, 'Outdoor 1', '2026-10-28 17:43:19', '2026-10-28 17:43:19', '2026-11-02 13:06:00', 'online', 19.00, 'paid', '', 3, 12, 1, ''),
(434, 5, 'Outdoor 1', '2026-10-28 18:00:24', '2026-10-28 18:00:24', '2026-11-02 13:06:00', 'online', 5.00, 'paid', '', 3, 12, 1, ''),
(435, 5, 'Outdoor 1', '2026-10-28 18:20:41', '2026-10-28 18:20:41', '2026-10-28 18:21:16', 'online', 100.00, 'paid', '435.jpg', 3, NULL, 0, ''),
(436, 5, 'Outdoor 1', '2026-10-28 18:31:15', '2026-10-28 18:31:15', '2026-10-28 19:00:38', 'cash', 55.00, 'paid', '', 3, NULL, 0, ''),
(437, 4, 'Hut 4', '2026-10-28 18:31:39', '2026-10-28 18:31:39', '2026-10-28 18:53:52', 'online', 73.00, 'paid', '437.jpg', 3, NULL, 0, ''),
(438, 1, 'Hut 1', '2026-10-28 18:36:55', '2026-10-28 18:36:56', '2026-10-28 19:32:42', 'online', 80.00, 'paid', '438.jpg', 3, NULL, 0, ''),
(439, 4, 'Hut 4', '2026-10-28 19:02:04', '2026-10-28 19:02:05', '2026-10-28 19:02:54', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(440, 8, 'Outdoor 4', '2026-10-28 19:13:16', '2026-10-28 19:13:16', '2026-10-28 19:13:19', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(441, 5, 'Outdoor 1', '2026-10-28 19:13:38', '2026-10-28 19:13:39', '2026-10-28 19:13:41', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(442, 8, 'Outdoor 4', '2026-10-28 19:15:12', '2026-10-28 19:15:13', '2026-10-28 19:27:00', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(443, 5, 'Outdoor 1', '2026-10-28 19:15:33', '2026-10-28 19:15:33', '2026-10-28 22:29:00', 'cash', 21.00, 'paid', '', 3, NULL, 0, ''),
(444, 8, 'Outdoor 4', '2026-10-28 20:02:16', '2026-10-28 20:02:16', '2026-10-28 20:02:52', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(445, 1, 'Hut 1', '2026-10-28 20:03:11', '2026-10-28 20:03:11', '2026-10-31 11:13:57', 'online', 29.00, 'paid', '', 3, 46, 1, ''),
(446, 2, 'Hut 2', '2026-10-28 20:06:19', '2026-10-28 20:06:20', '2026-10-28 20:16:01', 'online', 51.00, 'paid', '446.jpg', 3, NULL, 0, ''),
(447, 4, 'Hut 4', '2026-10-28 20:19:14', '2026-10-28 20:19:14', '2026-10-28 20:42:29', 'online', 30.00, 'paid', '447.jpg', 3, NULL, 0, ''),
(448, 9, 'Indoor 1', '2026-10-28 20:37:00', '2026-10-28 20:37:00', NULL, 'credit', 15.00, 'credit', '', 3, 21, 1, ''),
(449, 4, 'Hut 4', '2026-10-28 21:08:11', '2026-10-28 21:08:11', '2026-10-28 21:23:03', 'cash', 50.00, 'paid', '', 3, NULL, 0, ''),
(450, 2, 'Hut 2', '2026-10-28 21:08:40', '2026-10-28 21:08:40', '2026-10-28 21:31:58', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(451, 1, 'Hut 1', '2026-10-28 21:32:01', '2026-10-28 21:32:02', '2026-10-28 21:32:07', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(452, 6, 'Outdoor 2', '2026-10-28 21:50:47', '2026-10-28 21:50:47', '2026-10-28 21:51:42', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(453, 6, 'Outdoor 2', '2026-10-28 22:21:28', '2026-10-28 22:21:28', '2026-10-28 22:21:47', 'online', 5.00, 'paid', '453.jpg', 3, NULL, 0, ''),
(454, 4, 'Hut 4', '2026-10-28 23:02:59', '2026-10-28 23:02:59', '2026-10-28 23:37:36', 'cash', 22.00, 'paid', '', 3, NULL, 0, ''),
(455, 1, 'Hut 1', '2026-10-28 23:21:04', '2026-10-28 23:21:04', '2026-10-28 23:35:15', 'online', 38.00, 'paid', '455.jpg', 3, NULL, 0, ''),
(456, 4, 'Hut 4', '2026-10-29 06:55:52', '2026-10-29 06:55:52', '2026-10-29 06:56:32', 'online', 19.00, 'paid', '', 9, NULL, 0, ''),
(457, 4, 'Hut 4', '2026-10-29 08:56:17', '2026-10-29 08:56:17', '2026-10-29 12:00:59', 'online', 46.00, 'paid', '', 9, 39, 1, ''),
(458, 1, 'Hut 1', '2026-10-29 09:33:58', '2026-10-29 09:33:58', '2026-10-29 09:34:04', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(459, 2, 'Hut 2', '2026-10-29 09:35:40', '2026-10-29 09:35:41', '2026-10-29 09:54:12', 'online', 17.00, 'paid', '459.jpg', 9, NULL, 0, ''),
(460, 5, 'Outdoor 1', '2026-10-29 09:53:28', '2026-10-29 09:53:28', '2026-10-29 09:54:18', 'cash', 40.00, 'paid', '', 9, NULL, 0, ''),
(461, 1, 'Hut 1', '2026-10-29 10:42:41', '2026-10-29 10:42:41', '2026-10-29 11:27:31', 'online', 67.00, 'paid', '', 9, NULL, 0, ''),
(462, 3, 'Hut 3', '2026-10-29 10:42:58', '2026-10-29 10:42:58', '2026-10-29 10:48:43', 'cash', 7.00, 'paid', '', 9, NULL, 0, ''),
(463, 2, 'Hut 2', '2026-10-29 10:45:54', '2026-10-29 10:45:54', '2026-10-29 10:46:35', 'online', 10.00, 'paid', '463.jpg', 9, NULL, 0, ''),
(464, 2, 'Hut 2', '2026-10-29 11:29:30', '2026-10-29 11:29:30', '2026-10-29 13:20:32', 'online', 71.00, 'paid', '464.jpg', 9, NULL, 0, ''),
(465, 3, 'Hut 3', '2026-10-29 11:30:03', '2026-10-29 11:30:03', '2026-10-29 11:56:56', 'online', 31.00, 'paid', '465.jpg', 9, NULL, 0, ''),
(466, 4, 'Hut 4', '2026-10-29 11:30:20', '2026-10-29 11:30:20', '2026-10-29 11:50:37', 'cash', 46.00, 'paid', '', 9, NULL, 0, ''),
(467, 6, 'Outdoor 2', '2026-10-29 11:43:39', '2026-10-29 11:43:39', '2026-10-29 11:49:41', 'cash', 22.00, 'paid', '', 9, NULL, 0, ''),
(468, 1, 'Hut 1', '2026-10-29 11:44:19', '2026-10-29 11:44:19', '2026-10-29 12:02:39', 'online', 36.00, 'paid', '468.jpg', 9, NULL, 0, ''),
(469, 3, 'Hut 3', '2026-10-29 12:46:51', '2026-10-29 12:46:51', '2026-10-29 12:57:16', 'online', 68.00, 'paid', '469.jpg', 9, NULL, 0, ''),
(470, 4, 'Hut 4', '2026-10-29 12:49:13', '2026-10-29 12:49:13', '2026-10-29 12:49:47', 'online', 12.00, 'paid', '', 9, 50, 1, ''),
(471, 9, 'Indoor 1', '2026-10-29 12:56:23', '2026-10-29 12:56:23', NULL, 'credit', 10.00, 'credit', '', 9, 20, 1, ''),
(472, 1, 'Hut 1', '2026-10-29 13:21:24', '2026-10-29 13:21:24', '2026-10-29 13:21:26', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(473, 1, 'Hut 1', '2026-10-29 13:43:57', '2026-10-29 13:43:57', NULL, 'credit', 30.00, 'credit', '', 9, 3, 1, ''),
(474, 2, 'Hut 2', '2026-10-29 13:44:19', '2026-10-29 13:44:19', '2026-10-29 13:53:15', 'online', 38.00, 'paid', '474.jpg', 9, NULL, 0, ''),
(475, 3, 'Hut 3', '2026-10-29 13:44:44', '2026-10-29 13:44:44', '2026-11-02 13:06:00', 'online', 22.00, 'paid', '', 9, 12, 1, ''),
(476, 1, 'Hut 1', '2026-10-29 14:04:44', '2026-10-29 14:04:44', '2026-10-29 14:04:49', 'cash', 15.00, 'paid', '', 9, NULL, 0, ''),
(477, 2, 'Hut 2', '2026-10-29 14:11:13', '2026-10-29 14:11:13', '2026-10-29 14:27:01', 'online', 73.00, 'paid', '477.jpg', 9, NULL, 0, ''),
(478, 5, 'Outdoor 1', '2026-10-29 14:11:31', '2026-10-29 14:11:31', '2026-10-29 14:27:17', 'cash', 11.00, 'paid', '', 9, NULL, 0, ''),
(479, 4, 'Hut 4', '2026-10-29 14:35:39', '2026-10-29 14:35:39', '2026-10-29 15:45:22', 'cash', 38.00, 'paid', '', 9, NULL, 0, ''),
(480, 1, 'Hut 1', '2026-10-29 14:48:55', '2026-10-29 14:48:55', '2026-10-29 15:43:02', 'cash', 32.00, 'paid', '', 3, NULL, 0, ''),
(481, 2, 'Hut 2', '2026-10-29 14:54:07', '2026-10-29 14:54:07', '2026-10-29 15:08:06', 'cash', 39.00, 'paid', '', 3, NULL, 0, ''),
(482, 6, 'Outdoor 2', '2026-10-29 15:23:01', '2026-10-29 15:23:01', '2026-10-29 15:59:22', 'cash', 70.00, 'paid', '', 9, NULL, 0, ''),
(484, 3, 'Hut 3', '2026-10-29 15:24:08', '2026-10-29 15:24:09', '2026-10-29 15:54:08', 'online', 47.00, 'paid', '484.jpg', 9, NULL, 0, ''),
(486, 2, 'Hut 2', '2026-10-29 15:41:15', '2026-10-29 15:41:15', '2026-10-29 15:55:54', 'online', 96.00, 'paid', '486.jpg', 3, NULL, 0, ''),
(487, 1, 'Hut 1', '2026-10-29 16:01:07', '2026-10-29 16:01:07', '2026-10-29 16:44:23', 'online', 7.00, 'paid', '487.jpg', 9, NULL, 0, ''),
(488, 5, 'Outdoor 1', '2026-10-29 16:18:12', '2026-10-29 16:18:12', '2026-10-29 16:18:15', 'cash', 10.00, 'paid', '', 9, NULL, 0, ''),
(489, 3, 'Hut 3', '2026-10-29 16:20:01', '2026-10-29 16:20:01', '2026-10-29 16:33:26', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(490, 4, 'Hut 4', '2026-10-29 16:24:31', '2026-10-29 16:24:31', NULL, 'credit', 32.00, 'credit', '', 9, 18, 1, ''),
(491, 6, 'Outdoor 2', '2026-10-29 16:32:28', '2026-10-29 16:32:28', '2026-10-29 16:39:56', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(492, 1, 'Hut 1', '2026-10-29 16:44:32', '2026-10-29 16:44:32', '2026-10-29 16:45:54', 'cash', 21.00, 'paid', '', 3, NULL, 0, ''),
(493, 2, 'Hut 2', '2026-10-29 16:46:48', '2026-10-29 16:46:48', '2026-10-29 16:53:53', 'online', 68.00, 'paid', '493.jpg', 3, NULL, 0, ''),
(494, 5, 'Outdoor 1', '2026-10-29 16:55:56', '2026-10-29 16:55:56', '2026-10-29 16:56:45', 'online', 40.00, 'paid', '494.jpg', 3, NULL, 0, ''),
(495, 5, 'Outdoor 1', '2026-10-29 16:56:56', '2026-10-29 16:56:56', '2026-10-29 19:08:21', 'cash', 28.00, 'paid', '', 3, NULL, 0, ''),
(496, 6, 'Outdoor 2', '2026-10-29 17:02:13', '2026-10-29 17:02:13', '2026-10-29 17:08:59', 'online', 25.00, 'paid', '496.jpg', 3, NULL, 0, ''),
(497, 8, 'Outdoor 4', '2026-10-29 17:02:34', '2026-10-29 17:02:34', '2026-10-29 17:07:12', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(498, 4, 'Hut 4', '2026-10-29 17:10:15', '2026-10-29 17:10:16', '2026-10-29 17:56:44', 'online', 41.00, 'paid', '498.jpg', 3, NULL, 0, ''),
(499, 7, 'Outdoor 3', '2026-10-29 17:21:09', '2026-10-29 17:21:09', '2026-10-29 17:22:26', 'online', 70.00, 'paid', '499.jpg', 3, NULL, 0, ''),
(500, 6, 'Outdoor 2', '2026-10-29 17:27:01', '2026-10-29 17:27:01', '2026-10-29 17:29:03', 'online', 27.00, 'paid', '500.jpg', 3, NULL, 0, ''),
(501, 2, 'Hut 2', '2026-10-29 17:32:33', '2026-10-29 17:32:33', '2026-10-29 17:43:45', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(502, 3, 'Hut 3', '2026-10-29 17:38:15', '2026-10-29 17:38:15', '2026-10-29 17:45:49', 'online', 33.00, 'paid', '502.jpg', 3, NULL, 0, ''),
(503, 6, 'Outdoor 2', '2026-10-29 17:51:47', '2026-10-29 17:51:47', '2026-10-29 17:52:05', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(505, 3, 'Hut 3', '2026-10-29 18:15:26', '2026-10-29 18:15:27', '2026-10-29 18:28:12', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(506, 6, 'Outdoor 2', '2026-10-29 18:41:22', '2026-10-29 18:41:22', '2026-10-29 19:09:34', 'cash', 46.00, 'paid', '', 3, NULL, 0, ''),
(507, 8, 'Outdoor 4', '2026-10-29 18:48:23', '2026-10-29 18:48:23', '2026-10-29 20:18:58', 'cash', 35.00, 'paid', '', 9, NULL, 0, ''),
(508, 5, 'Outdoor 1', '2026-10-29 19:13:51', '2026-10-29 19:13:52', '2026-10-29 19:15:56', 'online', 23.00, 'paid', '508.jpg', 3, NULL, 0, ''),
(509, 3, 'Hut 3', '2026-10-29 20:17:12', '2026-10-29 20:17:12', '2026-10-29 20:46:14', 'online', 31.00, 'paid', '509.jpg', 3, NULL, 0, ''),
(510, 6, 'Outdoor 2', '2026-10-29 20:17:57', '2026-10-29 20:17:57', '2026-10-29 20:53:43', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(511, 9, 'Indoor 1', '2026-10-29 20:29:20', '2026-10-29 20:29:20', NULL, 'credit', 15.00, 'credit', '', 3, 21, 1, ''),
(512, 2, 'Hut 2', '2026-10-29 20:53:51', '2026-10-29 20:53:52', '2026-10-29 21:20:44', 'online', 81.00, 'paid', '512.jpg', 3, NULL, 0, ''),
(513, 6, 'Outdoor 2', '2026-10-29 21:06:07', '2026-10-29 21:06:07', '2026-10-29 21:18:42', 'cash', 19.00, 'paid', '', 3, NULL, 0, ''),
(514, 5, 'Outdoor 1', '2026-10-29 21:11:09', '2026-10-29 21:11:09', '2026-10-29 21:11:11', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(515, 3, 'Hut 3', '2026-10-29 23:06:52', '2026-10-29 23:06:52', '2026-10-29 23:27:03', 'online', 38.00, 'paid', '515.jpg', 2, NULL, 0, ''),
(516, 6, 'Outdoor 2', '2026-10-29 23:07:25', '2026-10-29 23:07:25', '2026-10-29 23:12:28', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(517, 3, 'Hut 3', '2026-10-29 23:54:29', '2026-10-29 23:54:29', '2026-10-30 00:22:10', 'online', 20.00, 'paid', '517.jpg', 3, NULL, 0, ''),
(518, 4, 'Hut 4', '2026-10-29 23:55:03', '2026-10-29 23:55:03', NULL, 'credit', 52.00, 'credit', '', 3, 3, 1, ''),
(519, 1, 'Hut 1', '2026-10-29 23:56:38', '2026-10-29 23:56:38', '2026-10-29 23:56:41', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(520, 5, 'Outdoor 1', '2026-10-30 00:26:07', '2026-10-30 00:32:34', '2026-10-30 00:32:54', 'online', 70.00, 'paid', '520.jpg', 3, NULL, 0, ''),
(521, 2, 'Hut 2', '2026-10-30 00:35:06', '2026-10-30 00:35:06', '2026-10-30 00:35:25', 'online', 10.00, 'paid', '521.jpg', 3, NULL, 0, ''),
(522, 2, 'Hut 2', '2026-10-30 14:33:03', '2026-10-30 14:33:03', '2026-10-30 14:39:41', 'online', 24.00, 'paid', '522.jpg', 3, NULL, 0, ''),
(523, 4, 'Hut 4', '2026-10-30 14:33:18', '2026-10-30 14:33:18', '2026-10-30 15:15:30', 'online', 102.00, 'paid', '523.jpg', 3, NULL, 0, ''),
(524, 3, 'Hut 3', '2026-10-30 14:35:46', '2026-10-30 14:35:46', '2026-10-30 14:55:17', 'cash', 24.00, 'paid', '', 3, NULL, 0, ''),
(525, 1, 'Hut 1', '2026-10-30 14:49:56', '2026-10-30 14:49:56', NULL, 'credit', 34.00, 'credit', '', 3, 18, 1, ''),
(526, 2, 'Hut 2', '2026-10-30 14:57:01', '2026-10-30 14:57:01', '2026-10-30 14:57:25', 'online', 24.00, 'paid', '526.jpg', 3, NULL, 0, ''),
(528, 3, 'Hut 3', '2026-10-30 15:15:25', '2026-10-30 15:15:25', '2026-10-30 15:16:37', 'online', 12.00, 'paid', '528.jpg', 3, NULL, 0, ''),
(529, 2, 'Hut 2', '2026-10-30 15:19:11', '2026-10-30 15:19:12', '2026-10-30 16:08:14', 'online', 46.00, 'paid', '529.jpg', 2, NULL, 0, ''),
(530, 5, 'Outdoor 1', '2026-10-30 15:48:53', '2026-10-30 15:48:53', '2026-10-30 16:27:47', 'cash', 30.00, 'paid', '', 2, NULL, 0, ''),
(531, 4, 'Hut 4', '2026-10-30 16:27:52', '2026-10-30 16:27:52', '2026-10-30 16:44:10', 'online', 22.00, 'paid', '531.jpg', 2, NULL, 0, ''),
(532, 3, 'Hut 3', '2026-10-30 16:28:06', '2026-10-30 16:28:06', '2026-10-30 16:51:51', 'online', 35.00, 'paid', '532.jpg', 2, NULL, 0, ''),
(533, 2, 'Hut 2', '2026-10-30 16:44:17', '2026-10-30 16:44:17', '2026-10-30 16:55:46', 'online', 46.00, 'paid', '533.jpg', 2, NULL, 0, ''),
(534, 6, 'Outdoor 2', '2026-10-30 17:09:36', '2026-10-30 17:09:36', '2026-11-01 19:28:29', 'cash', 15.00, 'paid', '', 2, 48, 1, ''),
(535, 5, 'Outdoor 1', '2026-10-30 17:58:42', '2026-10-30 17:58:42', '2026-11-02 13:06:00', 'online', 12.00, 'paid', '', 3, 12, 1, ''),
(536, 7, 'Outdoor 3', '2026-10-30 18:22:36', '2026-10-30 18:22:36', '2026-10-30 18:22:41', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(537, 4, 'Hut 4', '2026-10-30 18:25:43', '2026-10-30 18:25:43', NULL, 'credit', 24.00, 'credit', '', 3, 18, 1, ''),
(538, 1, 'Hut 1', '2026-10-30 18:51:19', '2026-10-30 18:51:20', '2026-10-30 18:58:38', 'online', 50.00, 'paid', '538.jpg', 3, NULL, 0, ''),
(539, 2, 'Hut 2', '2026-10-30 19:18:07', '2026-10-30 19:18:07', '2026-10-30 19:52:21', 'online', 25.00, 'paid', '539.jpg', 9, NULL, 0, ''),
(540, 4, 'Hut 4', '2026-10-30 19:31:50', '2026-10-30 19:31:50', '2026-10-30 19:42:00', 'online', 37.00, 'paid', '540.jpg', 9, NULL, 0, ''),
(541, 3, 'Hut 3', '2026-10-30 19:39:16', '2026-10-30 19:39:17', '2026-10-30 20:11:04', 'online', 60.00, 'paid', '541.jpg', 3, NULL, 0, ''),
(542, 5, 'Outdoor 1', '2026-10-30 20:04:47', '2026-10-30 20:04:47', '2026-10-30 20:38:06', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(543, 7, 'Outdoor 3', '2026-10-30 20:04:57', '2026-10-30 20:04:57', '2026-10-30 20:14:26', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(544, 3, 'Hut 3', '2026-10-30 21:34:51', '2026-10-30 21:34:51', '2026-10-30 22:06:54', 'online', 25.00, 'paid', '544.jpg', 3, NULL, 0, ''),
(545, 3, 'Hut 3', '2026-10-30 22:07:52', '2026-10-30 22:07:52', '2026-10-30 22:08:02', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(546, 2, 'Hut 2', '2026-10-30 22:08:15', '2026-10-30 22:08:15', '2026-10-30 22:24:31', 'online', 45.00, 'paid', '546.jpg', 9, 6, 1, ''),
(547, 6, 'Outdoor 2', '2026-10-30 22:31:45', '2026-10-30 22:31:45', '2026-11-01 19:28:23', 'cash', 5.00, 'paid', '', 3, 48, 1, ''),
(548, 6, 'Outdoor 2', '2026-10-30 23:06:39', '2026-10-30 23:06:39', '2026-10-30 23:06:54', 'cash', 6.00, 'paid', '', 3, NULL, 0, ''),
(549, 4, 'Hut 4', '2026-10-31 08:28:48', '2026-10-31 08:28:49', NULL, 'credit', 24.00, 'credit', '', 9, 18, 1, ''),
(550, 4, 'Hut 4', '2026-10-31 09:02:34', '2026-10-31 09:02:34', '2026-10-31 09:34:28', 'online', 32.00, 'paid', '550.jpg', 9, NULL, 0, ''),
(551, 5, 'Outdoor 1', '2026-10-31 10:42:10', '2026-10-31 10:42:11', '2026-10-31 10:43:37', 'online', 5.00, 'paid', '', 9, NULL, 0, ''),
(552, 1, 'Hut 1', '2026-10-31 10:55:31', '2026-10-31 10:55:31', '2026-10-31 11:32:05', 'online', 12.00, 'paid', '552.jpg', 9, NULL, 0, ''),
(553, 4, 'Hut 4', '2026-10-31 10:55:39', '2026-10-31 10:55:39', '2026-10-31 11:19:34', 'online', 45.00, 'paid', '', 9, NULL, 0, ''),
(554, 2, 'Hut 2', '2026-10-31 11:07:23', '2026-10-31 11:07:24', '2026-10-31 11:13:57', 'online', 36.00, 'paid', '', 9, 46, 1, ''),
(555, 5, 'Outdoor 1', '2026-10-31 11:23:06', '2026-10-31 11:23:06', '2026-10-31 12:10:20', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(556, 6, 'Outdoor 2', '2026-10-31 11:43:17', '2026-10-31 11:43:17', '2026-10-31 11:47:32', 'cash', 17.00, 'paid', '', 9, NULL, 0, ''),
(557, 2, 'Hut 2', '2026-10-31 12:08:26', '2026-10-31 12:08:26', '2026-10-31 12:09:41', 'online', 31.00, 'paid', '', 9, NULL, 0, ''),
(558, 1, 'Hut 1', '2026-10-31 13:04:39', '2026-10-31 13:04:39', '2026-10-31 13:16:50', 'online', 34.00, 'paid', '558.jpg', 9, NULL, 0, ''),
(559, 4, 'Hut 4', '2026-10-31 13:04:57', '2026-10-31 13:04:58', '2026-10-31 13:49:57', 'online', 81.00, 'paid', '559.jpg', 9, NULL, 0, ''),
(560, 1, 'Hut 1', '2026-10-31 13:34:49', '2026-10-31 13:34:49', '2026-10-31 16:58:56', 'online', 124.00, 'paid', '560.jpg', 9, NULL, 0, ''),
(561, 2, 'Hut 2', '2026-10-31 13:35:05', '2026-10-31 13:35:05', '2026-10-31 20:10:01', 'online', 24.00, 'paid', '561.jpg', 9, 36, 1, ''),
(562, 4, 'Hut 4', '2026-10-31 14:26:53', '2026-10-31 14:26:54', '2026-10-31 15:23:43', 'online', 19.00, 'paid', '562.jpg', 9, NULL, 0, ''),
(563, 3, 'Hut 3', '2026-10-31 14:41:40', '2026-10-31 14:41:40', '2026-10-31 15:25:17', 'online', 37.00, 'paid', '563.jpg', 9, NULL, 0, ''),
(564, 5, 'Outdoor 1', '2026-10-31 15:31:16', '2026-10-31 15:31:16', '2026-10-31 15:31:20', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(565, 5, 'Outdoor 1', '2026-10-31 15:45:51', '2026-10-31 15:45:51', '2026-10-31 16:21:04', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(566, 3, 'Hut 3', '2026-10-31 15:46:03', '2026-10-31 15:46:03', '2026-10-31 16:09:45', 'cash', 81.00, 'paid', '', 3, NULL, 0, ''),
(567, 6, 'Outdoor 2', '2026-10-31 16:00:10', '2026-10-31 16:00:11', '2026-10-31 16:00:18', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(568, 2, 'Hut 2', '2026-10-31 16:02:23', '2026-10-31 16:02:23', '2026-10-31 16:19:20', 'online', 75.00, 'paid', '568.jpg', 3, NULL, 0, ''),
(569, 4, 'Hut 4', '2026-10-31 16:02:51', '2026-10-31 16:02:52', '2026-10-31 16:10:21', 'cash', 35.00, 'paid', '', 3, NULL, 0, ''),
(570, 6, 'Outdoor 2', '2026-10-31 16:03:22', '2026-10-31 16:03:22', '2026-10-31 16:04:46', 'online', 87.00, 'paid', '570.jpg', 3, NULL, 0, ''),
(571, 3, 'Hut 3', '2026-10-31 16:55:35', '2026-10-31 16:55:35', '2026-10-31 18:08:08', 'online', 59.00, 'paid', '571.jpg', 3, NULL, 0, ''),
(572, 1, 'Hut 1', '2026-10-31 17:11:36', '2026-10-31 17:11:37', '2026-10-31 17:46:48', 'online', 60.00, 'paid', '572.jpg', 3, NULL, 0, ''),
(573, 8, 'Outdoor 4', '2026-10-31 17:13:16', '2026-10-31 17:13:16', '2026-10-31 20:24:02', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(574, 4, 'Hut 4', '2026-10-31 17:24:33', '2026-10-31 17:24:33', '2026-10-31 19:39:41', 'online', 111.00, 'paid', '574.jpg', 3, NULL, 0, ''),
(575, 2, 'Hut 2', '2026-10-31 17:46:53', '2026-10-31 17:46:53', '2026-10-31 18:13:00', 'online', 196.00, 'paid', '', 3, NULL, 0, ''),
(576, 1, 'Hut 1', '2026-10-31 18:15:08', '2026-10-31 18:15:08', '2026-10-31 20:05:17', 'online', 99.00, 'paid', '576.jpg', 3, NULL, 0, ''),
(577, 5, 'Outdoor 1', '2026-10-31 18:19:36', '2026-10-31 18:19:36', '2026-10-31 18:32:56', 'cash', 9.00, 'paid', '', 3, NULL, 0, ''),
(578, 6, 'Outdoor 2', '2026-10-31 18:27:21', '2026-10-31 18:27:22', '2026-10-31 18:52:09', 'cash', 52.00, 'paid', '', 3, NULL, 0, ''),
(579, 3, 'Hut 3', '2026-10-31 18:33:12', '2026-10-31 18:33:13', '2026-10-31 19:38:55', 'online', 54.00, 'paid', '579.jpg', 3, NULL, 0, ''),
(580, 6, 'Outdoor 2', '2026-10-31 18:52:18', '2026-10-31 18:52:18', '2026-10-31 20:02:42', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(581, 5, 'Outdoor 1', '2026-10-31 19:15:59', '2026-10-31 19:16:00', '2026-10-31 19:18:17', 'online', 17.00, 'paid', '581.jpg', 3, NULL, 0, ''),
(582, 6, 'Outdoor 2', '2026-10-31 20:02:49', '2026-10-31 20:02:49', '2026-10-31 20:02:55', 'cash', 7.00, 'paid', '', 9, NULL, 0, ''),
(583, 3, 'Hut 3', '2026-10-31 20:39:20', '2026-10-31 20:39:21', '2026-10-31 20:57:43', 'cash', 29.00, 'paid', '', 3, NULL, 0, ''),
(584, 1, 'Hut 1', '2026-10-31 20:39:37', '2026-10-31 20:39:37', '2026-10-31 21:34:16', 'online', 49.00, 'paid', '584.jpg', 3, NULL, 0, ''),
(585, 4, 'Hut 4', '2026-10-31 20:57:10', '2026-10-31 20:57:10', '2026-10-31 21:15:39', 'online', 5.00, 'paid', '585.jpg', 3, NULL, 0, ''),
(586, 9, 'Indoor 1', '2026-10-31 21:21:05', '2026-10-31 21:21:05', NULL, 'credit', 15.00, 'credit', '', 3, 21, 1, ''),
(587, 6, 'Outdoor 2', '2026-10-31 21:54:45', '2026-10-31 21:54:45', '2026-10-31 22:12:22', 'cash', 22.00, 'paid', '', 3, NULL, 0, ''),
(588, 5, 'Outdoor 1', '2026-10-31 22:08:47', '2026-10-31 22:08:48', '2026-10-31 22:08:53', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(589, 6, 'Outdoor 2', '2026-10-31 22:25:51', '2026-10-31 22:25:51', '2026-10-31 23:25:20', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(590, 1, 'Hut 1', '2026-10-31 22:39:48', '2026-10-31 22:39:49', '2026-11-01 00:03:07', 'online', 78.00, 'paid', '590.jpg', 9, NULL, 0, ''),
(591, 4, 'Hut 4', '2026-10-31 22:45:46', '2026-10-31 22:45:46', '2026-10-31 23:10:31', 'online', 30.00, 'paid', '591.jpg', 3, NULL, 0, ''),
(592, 4, 'Hut 4', '2026-10-31 23:41:44', '2026-10-31 23:41:44', '2026-11-01 00:04:24', 'online', 24.00, 'paid', '592.jpg', 9, NULL, 0, ''),
(593, 1, 'Hut 1', '2026-11-01 00:11:29', '2026-11-01 00:11:29', '2026-11-01 00:12:41', 'online', 25.00, 'paid', '593.jpg', 3, NULL, 0, ''),
(594, 1, 'Hut 1', '2026-11-01 00:32:24', '2026-11-01 00:32:24', '2026-11-01 00:33:38', 'online', 12.00, 'paid', '594.jpg', 3, NULL, 0, ''),
(595, 4, 'Hut 4', '2026-11-01 07:22:10', '2026-11-01 07:22:10', '2026-11-01 08:30:39', 'cash', 84.00, 'paid', '', 9, NULL, 0, ''),
(597, 3, 'Hut 3', '2026-11-01 08:39:51', '2026-11-01 08:39:51', '2026-11-01 08:58:53', 'online', 19.00, 'paid', '', 9, NULL, 0, ''),
(598, 2, 'Hut 2', '2026-11-01 08:40:02', '2026-11-01 08:40:02', '2026-11-01 08:55:26', 'online', 17.00, 'paid', '598.jpg', 9, NULL, 0, ''),
(599, 1, 'Hut 1', '2026-11-01 09:30:19', '2026-11-01 09:30:20', '2026-11-02 13:06:00', 'online', 24.00, 'paid', '', 9, 12, 1, ''),
(600, 2, 'Hut 2', '2026-11-01 09:30:30', '2026-11-01 09:30:31', '2026-11-02 06:55:02', 'online', 17.00, 'paid', '', 9, 52, 1, ''),
(601, 3, 'Hut 3', '2026-11-01 09:30:43', '2026-11-01 09:30:43', '2026-11-01 10:00:58', 'online', 26.00, 'paid', '601.jpg', 9, NULL, 0, ''),
(602, 4, 'Hut 4', '2026-11-01 09:30:54', '2026-11-01 09:30:54', '2026-11-01 09:38:54', 'online', 27.00, 'paid', '602.jpg', 9, NULL, 0, ''),
(603, 4, 'Hut 4', '2026-11-01 10:04:59', '2026-11-01 10:05:00', '2026-11-01 10:43:16', 'online', 39.00, 'paid', '603.jpg', 9, NULL, 0, ''),
(604, 1, 'Hut 1', '2026-11-01 10:34:46', '2026-11-01 10:34:46', '2026-11-01 11:06:50', 'online', 44.00, 'paid', '604.jpg', 9, NULL, 0, ''),
(605, 3, 'Hut 3', '2026-11-01 10:35:00', '2026-11-01 10:35:00', '2026-11-01 10:52:11', 'online', 24.00, 'paid', '605.jpg', 9, NULL, 0, ''),
(606, 5, 'Outdoor 1', '2026-11-01 10:48:13', '2026-11-01 10:48:13', '2026-11-01 11:38:02', 'online', 32.00, 'paid', '606.jpg', 9, NULL, 0, ''),
(607, 4, 'Hut 4', '2026-11-01 11:26:21', '2026-11-01 11:26:21', '2026-11-01 11:41:58', 'online', 53.00, 'paid', '607.jpg', 9, NULL, 0, ''),
(608, 6, 'Outdoor 2', '2026-11-01 12:09:55', '2026-11-01 12:09:55', '2026-11-01 12:10:00', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(609, 1, 'Hut 1', '2026-11-01 12:49:58', '2026-11-01 12:49:58', '2026-11-01 13:51:15', 'online', 57.00, 'paid', '609.jpg', 9, NULL, 0, ''),
(610, 4, 'Hut 4', '2026-11-01 12:50:17', '2026-11-01 12:50:17', '2026-11-03 17:55:58', 'online', 41.00, 'paid', '610.jpg', 9, 51, 1, ''),
(611, 3, 'Hut 3', '2026-11-01 13:30:06', '2026-11-01 13:30:06', '2026-11-01 13:38:47', 'cash', 20.00, 'paid', '', 9, 44, 1, ''),
(612, 5, 'Outdoor 1', '2026-11-01 13:41:50', '2026-11-01 13:41:50', '2026-11-01 13:43:45', 'online', 24.00, 'paid', '612.jpg', 9, NULL, 0, ''),
(613, 4, 'Hut 4', '2026-11-01 14:34:56', '2026-11-01 14:34:56', '2026-11-01 14:47:13', 'online', 19.00, 'paid', '613.jpg', 9, NULL, 0, ''),
(614, 1, 'Hut 1', '2026-11-01 14:35:11', '2026-11-01 14:35:12', '2026-11-01 14:51:18', 'online', 37.00, 'paid', '', 9, 37, 1, ''),
(615, 6, 'Outdoor 2', '2026-11-01 14:35:29', '2026-11-01 14:35:29', NULL, 'credit', 57.00, 'credit', '', 9, 3, 1, ''),
(616, 3, 'Hut 3', '2026-11-01 14:56:20', '2026-11-01 14:56:20', '2026-11-02 13:06:00', 'online', 36.00, 'paid', '', 9, 12, 1, ''),
(617, 5, 'Outdoor 1', '2026-11-01 15:08:40', '2026-11-01 15:08:40', '2026-11-01 15:08:49', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(618, 5, 'Outdoor 1', '2026-11-01 15:26:45', '2026-11-01 15:26:45', '2026-11-01 15:27:45', 'cash', 56.00, 'paid', '', 9, NULL, 0, ''),
(619, 6, 'Outdoor 2', '2026-11-01 15:38:35', '2026-11-01 15:38:35', '2026-11-01 15:39:05', 'online', 24.00, 'paid', '619.jpg', 9, NULL, 0, ''),
(620, 4, 'Hut 4', '2026-11-01 15:42:05', '2026-11-01 15:42:05', '2026-11-01 15:57:27', 'online', 59.00, 'paid', '620.jpg', 9, NULL, 0, ''),
(621, 6, 'Outdoor 2', '2026-11-01 15:44:31', '2026-11-01 15:44:31', '2026-11-01 15:56:36', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(622, 5, 'Outdoor 1', '2026-11-01 15:44:50', '2026-11-01 15:44:50', '2026-11-01 15:52:17', 'online', 7.00, 'paid', '', 9, 14, 1, ''),
(625, 5, 'Outdoor 1', '2026-11-01 15:56:46', '2026-11-01 15:56:47', '2026-11-01 15:56:58', 'cash', 15.00, 'paid', '', 9, NULL, 0, ''),
(626, 1, 'Hut 1', '2026-11-01 15:57:34', '2026-11-01 15:57:34', '2026-11-01 16:06:42', 'online', 31.00, 'paid', '626.jpg', 3, NULL, 0, ''),
(627, 2, 'Hut 2', '2026-11-01 16:02:40', '2026-11-01 16:02:40', '2026-11-01 16:20:33', 'online', 71.00, 'paid', '627.jpg', 3, NULL, 0, ''),
(628, 4, 'Hut 4', '2026-11-01 16:24:25', '2026-11-01 16:24:26', '2026-11-01 17:35:31', 'online', 128.00, 'paid', '628.jpg', 3, NULL, 0, ''),
(629, 3, 'Hut 3', '2026-11-01 16:36:45', '2026-11-01 16:36:45', '2026-11-01 16:53:11', 'online', 24.00, 'paid', '629.jpg', 3, NULL, 0, ''),
(631, 2, 'Hut 2', '2026-11-01 16:44:51', '2026-11-01 16:44:51', '2026-11-01 17:20:57', 'online', 159.00, 'paid', '631.jpg', 3, NULL, 0, ''),
(632, 2, 'Hut 2', '2026-11-01 17:27:00', '2026-11-01 17:27:01', '2026-11-01 17:55:50', 'cash', 27.00, 'paid', '', 3, NULL, 0, ''),
(633, 5, 'Outdoor 1', '2026-11-01 18:20:40', '2026-11-01 18:20:41', '2026-11-01 18:21:01', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(634, 1, 'Hut 1', '2026-11-01 18:22:41', '2026-11-01 18:22:41', NULL, 'credit', 45.00, 'credit', '', 3, 3, 1, ''),
(635, 3, 'Hut 3', '2026-11-01 18:28:11', '2026-11-01 18:28:11', '2026-11-01 18:56:32', 'online', 44.00, 'paid', '635.jpg', 3, NULL, 0, ''),
(636, 5, 'Outdoor 1', '2026-11-01 18:37:03', '2026-11-01 18:37:08', '2026-11-01 18:37:50', 'cash', 29.00, 'paid', '', 3, NULL, 0, ''),
(637, 5, 'Outdoor 1', '2026-11-01 18:37:56', '2026-11-01 18:37:57', '2026-11-01 18:45:38', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(638, 6, 'Outdoor 2', '2026-11-01 18:38:06', '2026-11-01 18:38:06', '2026-11-01 18:58:11', 'cash', 54.00, 'paid', '', 3, NULL, 0, ''),
(639, 4, 'Hut 4', '2026-11-01 18:45:43', '2026-11-01 18:45:44', '2026-11-01 18:51:08', 'cash', 39.00, 'paid', '', 3, NULL, 0, ''),
(640, 8, 'Outdoor 4', '2026-11-01 18:46:33', '2026-11-01 18:46:33', '2026-11-01 21:57:34', 'online', 11.00, 'paid', '640.jpg', 3, NULL, 0, ''),
(641, 5, 'Outdoor 1', '2026-11-01 18:56:50', '2026-11-01 18:56:50', '2026-11-01 19:28:10', 'cash', 18.00, 'paid', '', 3, NULL, 0, ''),
(642, 2, 'Hut 2', '2026-11-01 19:03:35', '2026-11-01 19:03:35', '2026-11-01 19:37:52', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(643, 3, 'Hut 3', '2026-11-01 19:05:43', '2026-11-01 19:05:44', '2026-11-01 19:36:25', 'online', 71.00, 'paid', '643.jpg', 3, NULL, 0, ''),
(644, 4, 'Hut 4', '2026-11-01 19:33:07', '2026-11-01 19:33:07', '2026-11-01 19:46:58', 'online', 29.00, 'paid', '644.jpg', 3, NULL, 0, ''),
(645, 2, 'Hut 2', '2026-11-01 19:43:16', '2026-11-01 19:43:16', '2026-11-01 20:11:19', 'online', 30.00, 'paid', '645.jpg', 3, NULL, 0, ''),
(646, 1, 'Hut 1', '2026-11-01 19:43:47', '2026-11-01 19:43:47', '2026-11-01 20:10:33', 'online', 12.00, 'paid', '646.jpg', 3, NULL, 0, ''),
(647, 6, 'Outdoor 2', '2026-11-01 20:00:07', '2026-11-01 20:00:07', '2026-11-01 20:07:05', 'cash', 32.00, 'paid', '', 3, NULL, 0, ''),
(648, 4, 'Hut 4', '2026-11-01 20:30:49', '2026-11-01 20:30:49', '2026-11-01 21:02:05', 'online', 45.00, 'paid', '648.jpg', 3, NULL, 0, ''),
(649, 4, 'Hut 4', '2026-11-01 21:48:03', '2026-11-01 21:48:03', '2026-11-01 21:55:05', 'cash', 27.00, 'paid', '', 3, NULL, 0, ''),
(651, 9, 'Indoor 1', '2026-11-01 22:54:11', '2026-11-01 22:54:11', NULL, 'credit', 15.00, 'credit', '', 3, 21, 1, ''),
(652, 5, 'Outdoor 1', '2026-11-01 23:04:59', '2026-11-01 23:05:00', '2026-11-01 23:19:03', 'cash', 34.00, 'paid', '', 3, NULL, 0, ''),
(653, 6, 'Outdoor 2', '2026-11-01 23:13:33', '2026-11-01 23:13:33', NULL, 'credit', 12.00, 'credit', '', 3, 40, 1, ''),
(654, 5, 'Outdoor 1', '2026-11-01 23:41:52', '2026-11-01 23:41:53', '2026-11-01 23:42:15', 'online', 30.00, 'paid', '654.jpg', 3, NULL, 0, ''),
(655, 1, 'Hut 1', '2026-11-01 23:49:51', '2026-11-01 23:49:51', '2026-11-02 00:03:07', 'online', 35.00, 'paid', '655.jpg', 9, NULL, 0, ''),
(656, 4, 'Hut 4', '2026-11-02 00:07:48', '2026-11-02 00:07:48', '2026-11-02 00:30:12', 'online', 20.00, 'paid', '656.jpg', 3, NULL, 0, ''),
(657, 2, 'Hut 2', '2026-11-02 00:32:02', '2026-11-02 00:32:02', '2026-11-02 00:32:43', 'online', 20.00, 'paid', '657.jpg', 3, NULL, 0, ''),
(658, 2, 'Hut 2', '2026-11-02 00:53:40', '2026-11-02 00:53:40', '2026-11-02 00:54:11', 'online', 15.00, 'paid', '658.jpg', 3, NULL, 0, ''),
(659, 3, 'Hut 3', '2026-11-02 07:04:22', '2026-11-02 07:04:22', '2026-11-02 07:37:57', 'online', 41.00, 'paid', '659.jpg', 9, NULL, 0, ''),
(660, 4, 'Hut 4', '2026-11-02 07:04:40', '2026-11-02 07:04:40', '2026-11-02 07:38:26', 'online', 37.00, 'paid', '660.jpg', 9, NULL, 0, ''),
(661, 2, 'Hut 2', '2026-11-02 07:26:21', '2026-11-02 07:26:21', '2026-11-02 07:26:25', 'cash', 5.00, 'paid', '', 9, NULL, 0, ''),
(662, 5, 'Outdoor 1', '2026-11-02 08:00:45', '2026-11-02 08:00:45', '2026-11-02 08:01:33', 'cash', 12.00, 'paid', '', 9, NULL, 0, ''),
(663, 4, 'Hut 4', '2026-11-02 08:09:18', '2026-11-02 08:09:18', '2026-11-02 08:16:19', 'online', 10.00, 'paid', '', 9, NULL, 0, ''),
(664, 4, 'Hut 4', '2026-11-02 09:18:48', '2026-11-02 09:18:48', '2026-11-02 09:39:09', 'online', 39.00, 'paid', '', 9, NULL, 0, ''),
(665, 5, 'Outdoor 1', '2026-11-02 09:49:33', '2026-11-02 09:49:33', '2026-11-02 10:35:54', 'cash', 37.00, 'paid', '', 9, NULL, 0, ''),
(666, 2, 'Hut 2', '2026-11-02 10:36:00', '2026-11-02 10:36:00', '2026-11-02 10:56:54', 'online', 24.00, 'paid', '', 9, NULL, 0, ''),
(667, 4, 'Hut 4', '2026-11-02 11:06:06', '2026-11-02 11:06:06', '2026-11-02 11:09:14', 'online', 24.00, 'paid', '667.jpg', 9, NULL, 0, ''),
(668, 1, 'Hut 1', '2026-11-02 11:06:22', '2026-11-02 11:06:22', '2026-11-04 11:00:59', 'online', 58.00, 'paid', '', 9, 37, 1, ''),
(669, 2, 'Hut 2', '2026-11-02 12:03:43', '2026-11-02 12:03:43', NULL, 'credit', 53.00, 'credit', '', 9, 18, 1, ''),
(670, 4, 'Hut 4', '2026-11-02 12:03:59', '2026-11-02 12:03:59', '2026-11-02 12:21:27', 'online', 31.00, 'paid', '', 9, NULL, 0, ''),
(671, 2, 'Hut 2', '2026-11-02 12:58:29', '2026-11-02 12:58:30', '2026-11-02 13:06:00', 'online', 14.00, 'paid', '', 2, 12, 1, ''),
(672, 4, 'Hut 4', '2026-11-02 13:50:15', '2026-11-02 13:50:16', '2026-11-02 14:11:46', 'online', 32.00, 'paid', '', 9, NULL, 0, ''),
(673, 5, 'Outdoor 1', '2026-11-02 13:50:36', '2026-11-02 13:50:36', NULL, 'credit', 5.00, 'credit', '', 9, 12, 1, ''),
(674, 2, 'Hut 2', '2026-11-02 14:02:28', '2026-11-02 14:02:29', '2026-11-02 14:29:41', 'cash', 12.00, 'paid', '', 2, NULL, 0, ''),
(675, 5, 'Outdoor 1', '2026-11-02 14:02:39', '2026-11-02 14:02:39', NULL, 'credit', 12.00, 'credit', '', 2, 14, 1, ''),
(676, 5, 'Outdoor 1', '2026-11-02 15:10:13', '2026-11-02 15:10:13', '2026-11-02 15:10:15', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(677, 1, 'Hut 1', '2026-11-02 15:22:06', '2026-11-02 15:22:06', '2026-11-02 15:23:45', 'online', 10.00, 'paid', '677.jpg', 3, NULL, 0, ''),
(678, 4, 'Hut 4', '2026-11-02 15:43:28', '2026-11-02 15:43:29', '2026-11-02 16:17:35', 'cash', 35.00, 'paid', '', 3, NULL, 0, ''),
(679, 6, 'Outdoor 2', '2026-11-02 15:45:13', '2026-11-02 15:45:14', '2026-11-02 15:45:25', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(680, 6, 'Outdoor 2', '2026-11-02 15:45:29', '2026-11-02 15:45:29', '2026-11-02 19:55:26', 'cash', 37.00, 'paid', '', 3, 53, 1, ''),
(682, 1, 'Hut 1', '2026-11-02 16:02:32', '2026-11-02 16:02:32', '2026-11-02 16:40:39', 'online', 34.00, 'paid', '682.jpg', 3, NULL, 0, ''),
(683, 6, 'Outdoor 2', '2026-11-02 16:03:06', '2026-11-02 16:03:06', '2026-11-02 16:25:49', 'cash', 55.00, 'paid', '', 3, NULL, 0, ''),
(684, 5, 'Outdoor 1', '2026-11-02 16:06:58', '2026-11-02 16:06:58', '2026-11-02 16:50:12', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(685, 7, 'Outdoor 3', '2026-11-02 16:21:47', '2026-11-02 16:21:47', '2026-11-02 16:23:35', 'cash', 13.00, 'paid', '', 3, NULL, 0, ''),
(686, 6, 'Outdoor 2', '2026-11-02 16:27:00', '2026-11-02 16:27:00', '2026-11-02 16:27:07', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(688, 6, 'Outdoor 2', '2026-11-02 16:31:51', '2026-11-02 16:31:52', '2026-11-02 16:51:29', 'online', 60.00, 'paid', '688.jpg', 3, NULL, 0, ''),
(689, 4, 'Hut 4', '2026-11-02 16:38:55', '2026-11-02 16:38:55', NULL, 'credit', 43.00, 'credit', '', 3, 54, 1, ''),
(690, 3, 'Hut 3', '2026-11-02 16:39:09', '2026-11-02 16:39:09', '2026-11-02 16:55:29', 'online', 30.00, 'paid', '690.jpg', 3, NULL, 0, ''),
(691, 2, 'Hut 2', '2026-11-02 16:51:38', '2026-11-02 16:51:38', '2026-11-02 17:11:54', 'online', 17.00, 'paid', '691.jpg', 3, NULL, 0, ''),
(692, 4, 'Hut 4', '2026-11-02 17:19:25', '2026-11-02 17:19:25', NULL, 'credit', 156.00, 'credit', '', 3, 46, 1, ''),
(693, 4, 'Hut 4', '2026-11-02 18:16:24', '2026-11-02 18:16:24', '2026-11-02 18:36:57', 'online', 45.00, 'paid', '693.jpg', 3, NULL, 0, ''),
(694, 6, 'Outdoor 2', '2026-11-02 18:16:46', '2026-11-02 18:16:46', '2026-11-02 19:46:48', 'online', 15.00, 'paid', '694.jpg', 3, NULL, 0, ''),
(696, 7, 'Outdoor 3', '2026-11-02 18:31:07', '2026-11-02 18:31:08', '2026-11-02 19:09:45', 'cash', 95.00, 'paid', '', 3, NULL, 0, ''),
(697, 1, 'Hut 1', '2026-11-02 18:32:18', '2026-11-02 18:32:18', '2026-11-02 18:36:01', 'cash', 37.00, 'paid', '', 3, NULL, 0, ''),
(698, 3, 'Hut 3', '2026-11-02 18:44:31', '2026-11-02 18:44:31', '2026-11-02 18:59:50', 'online', 60.00, 'paid', '698.jpg', 3, NULL, 0, ''),
(699, 2, 'Hut 2', '2026-11-02 18:52:23', '2026-11-02 18:52:23', '2026-11-02 19:37:50', 'online', 80.00, 'paid', '699.jpg', 3, NULL, 0, ''),
(700, 3, 'Hut 3', '2026-11-02 19:01:11', '2026-11-02 19:01:11', '2026-11-02 19:01:32', 'online', 5.00, 'paid', '700.jpg', 3, NULL, 0, ''),
(701, 1, 'Hut 1', '2026-11-02 19:05:59', '2026-11-02 19:05:59', '2026-11-02 19:07:01', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(702, 5, 'Outdoor 1', '2026-11-02 19:09:23', '2026-11-02 19:09:23', '2026-11-02 19:09:25', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(703, 3, 'Hut 3', '2026-11-02 19:12:24', '2026-11-02 19:12:25', '2026-11-02 20:40:31', 'online', 7.00, 'paid', '703.jpg', 3, NULL, 0, ''),
(705, 4, 'Hut 4', '2026-11-02 19:16:27', '2026-11-02 19:16:27', '2026-11-02 19:47:42', 'cash', 46.00, 'paid', '', 3, NULL, 0, ''),
(706, 7, 'Outdoor 3', '2026-11-02 19:38:18', '2026-11-02 19:38:19', '2026-11-02 20:04:29', 'cash', 109.00, 'paid', '', 3, NULL, 0, ''),
(707, 1, 'Hut 1', '2026-11-02 19:43:37', '2026-11-02 19:43:37', '2026-11-02 20:55:09', 'online', 52.00, 'paid', '707.jpg', 3, NULL, 0, ''),
(708, 5, 'Outdoor 1', '2026-11-02 20:20:41', '2026-11-02 20:20:42', '2026-11-02 21:42:06', 'cash', 18.00, 'paid', '', 3, 48, 1, ''),
(709, 9, 'Indoor 1', '2026-11-02 20:23:28', '2026-11-02 20:23:28', NULL, 'credit', 5.00, 'credit', '', 3, 21, 1, ''),
(710, 6, 'Outdoor 2', '2026-11-02 21:28:28', '2026-11-02 21:28:28', '2026-11-02 22:44:28', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(711, 4, 'Hut 4', '2026-11-02 21:54:42', '2026-11-02 21:54:43', '2026-11-02 21:54:55', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(712, 8, 'Outdoor 4', '2026-11-02 22:01:04', '2026-11-02 22:01:04', '2026-11-02 22:41:09', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(713, 3, 'Hut 3', '2026-11-02 22:12:51', '2026-11-02 22:12:51', '2026-11-02 23:07:35', 'online', 27.00, 'paid', '713.jpg', 3, NULL, 0, ''),
(714, 3, 'Hut 3', '2026-11-02 23:11:05', '2026-11-02 23:11:05', '2026-11-02 23:11:07', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(715, 5, 'Outdoor 1', '2026-11-02 23:30:16', '2026-11-02 23:30:16', '2026-11-02 23:30:21', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(716, 4, 'Hut 4', '2026-11-03 00:08:28', '2026-11-03 00:08:28', '2026-11-03 00:33:17', 'online', 55.00, 'paid', '716.jpg', 3, NULL, 0, ''),
(717, 2, 'Hut 2', '2026-11-03 00:11:08', '2026-11-03 00:11:08', '2026-11-03 00:19:44', 'online', 10.00, 'paid', '717.jpg', 3, NULL, 0, ''),
(718, 9, 'Indoor 1', '2026-11-03 09:12:49', '2026-11-03 09:12:50', NULL, 'credit', 10.00, 'credit', '', 2, 20, 1, ''),
(720, 1, 'Hut 1', '2026-11-03 09:14:59', '2026-11-03 15:25:23', '2026-11-03 15:53:18', 'online', 87.00, 'paid', '720.jpg', 2, NULL, 0, ''),
(721, 4, 'Hut 4', '2026-11-03 10:05:58', '2026-11-03 10:05:59', NULL, 'credit', 46.00, 'credit', '', 2, 18, 1, ''),
(722, 2, 'Hut 2', '2026-11-03 10:06:13', '2026-11-03 10:06:13', '2026-11-03 10:44:33', 'online', 34.00, 'paid', '722.jpg', 2, NULL, 0, ''),
(723, 5, 'Outdoor 1', '2026-11-03 10:13:34', '2026-11-03 10:13:34', '2026-11-03 10:21:32', 'cash', 35.00, 'paid', '', 2, NULL, 0, ''),
(724, 5, 'Outdoor 1', '2026-11-03 11:11:35', '2026-11-03 11:11:35', NULL, 'credit', 32.00, 'credit', '', 2, 55, 1, ''),
(725, 3, 'Hut 3', '2026-11-03 11:35:20', '2026-11-03 11:35:21', '2026-11-03 11:36:14', 'online', 34.00, 'paid', '725.jpg', 2, NULL, 0, ''),
(726, 2, 'Hut 2', '2026-11-03 11:36:24', '2026-11-03 11:36:24', '2026-11-03 11:39:33', 'online', 22.00, 'paid', '726.jpg', 2, NULL, 0, ''),
(728, 2, 'Hut 2', '2026-11-03 11:57:26', '2026-11-03 11:57:26', '2026-11-03 12:33:00', 'online', 41.00, 'paid', '', 2, NULL, 0, ''),
(729, 4, 'Hut 4', '2026-11-03 12:01:52', '2026-11-03 12:01:52', '2026-11-03 14:34:46', 'online', 88.00, 'paid', '729.jpg', 2, NULL, 0, ''),
(730, 6, 'Outdoor 2', '2026-11-03 12:02:15', '2026-11-03 12:02:15', '2026-11-03 12:07:47', 'cash', 49.00, 'paid', '', 2, NULL, 0, ''),
(731, 2, 'Hut 2', '2026-11-03 12:40:09', '2026-11-03 12:40:09', '2026-11-03 13:01:43', 'online', 17.00, 'paid', '731.jpg', 9, NULL, 0, ''),
(732, 3, 'Hut 3', '2026-11-03 12:40:22', '2026-11-03 12:40:22', NULL, 'credit', 52.00, 'credit', '', 9, 18, 1, ''),
(733, 6, 'Outdoor 2', '2026-11-03 12:53:55', '2026-11-03 15:22:44', '2026-11-03 15:59:10', 'cash', 52.00, 'paid', '', 9, NULL, 0, ''),
(734, 2, 'Hut 2', '2026-11-03 14:22:33', '2026-11-03 14:22:33', '2026-11-03 14:23:08', 'online', 43.00, 'paid', '734.jpg', 2, NULL, 0, ''),
(735, 7, 'Outdoor 3', '2026-11-03 14:47:27', '2026-11-03 14:47:27', '2026-11-03 14:47:38', 'cash', 17.00, 'paid', '', 9, NULL, 0, ''),
(736, 4, 'Hut 4', '2026-11-03 14:48:01', '2026-11-03 14:48:01', '2026-11-03 14:57:11', 'online', 69.00, 'paid', '736.jpg', 9, NULL, 0, ''),
(737, 4, 'Hut 4', '2026-11-03 15:22:22', '2026-11-03 15:22:22', '2026-11-03 15:42:31', 'online', 19.00, 'paid', '737.jpg', 2, NULL, 0, ''),
(738, 5, 'Outdoor 1', '2026-11-03 15:29:06', '2026-11-03 15:29:06', NULL, 'credit', 22.00, 'credit', '', 9, 10, 1, ''),
(740, 3, 'Hut 3', '2026-11-03 15:48:12', '2026-11-03 15:48:12', '2026-11-03 15:50:18', 'online', 34.00, 'paid', '740.jpg', 2, NULL, 0, ''),
(741, 8, 'Outdoor 4', '2026-11-03 15:55:38', '2026-11-03 15:55:38', '2026-11-03 15:56:19', 'cash', 20.00, 'paid', '', 2, NULL, 0, ''),
(742, 4, 'Hut 4', '2026-11-03 15:55:47', '2026-11-03 15:55:47', '2026-11-03 16:23:25', 'online', 87.00, 'paid', '742.jpg', 3, NULL, 0, ''),
(743, 8, 'Outdoor 4', '2026-11-03 15:57:12', '2026-11-03 15:57:12', '2026-11-03 16:17:28', 'cash', 88.00, 'paid', '', 3, NULL, 0, ''),
(744, 2, 'Hut 2', '2026-11-03 15:58:37', '2026-11-03 15:58:38', '2026-11-03 16:42:50', 'online', 61.00, 'paid', '744.jpg', 2, NULL, 0, ''),
(745, 5, 'Outdoor 1', '2026-11-03 16:00:15', '2026-11-03 16:00:15', NULL, 'credit', 10.00, 'credit', '', 9, 12, 1, ''),
(746, 7, 'Outdoor 3', '2026-11-03 16:15:19', '2026-11-03 16:15:20', '2026-11-03 16:18:41', 'online', 65.00, 'paid', '746.jpg', 3, NULL, 0, ''),
(747, 5, 'Outdoor 1', '2026-11-03 16:20:31', '2026-11-03 16:20:31', '2026-11-03 16:43:07', 'online', 18.00, 'paid', '747.jpg', 3, NULL, 0, ''),
(748, 7, 'Outdoor 3', '2026-11-03 16:27:49', '2026-11-03 16:27:49', '2026-11-03 16:28:38', 'online', 75.00, 'paid', '748.jpg', 3, NULL, 0, ''),
(749, 5, 'Outdoor 1', '2026-11-03 16:43:17', '2026-11-03 16:43:17', '2026-11-03 17:06:00', 'online', 59.00, 'paid', '749.jpg', 3, NULL, 0, ''),
(750, 6, 'Outdoor 2', '2026-11-03 17:03:20', '2026-11-03 17:03:21', '2026-11-03 18:12:26', 'online', 128.00, 'paid', '750.jpg', 3, NULL, 0, ''),
(751, 3, 'Hut 3', '2026-11-03 17:08:35', '2026-11-03 17:08:36', '2026-11-03 17:14:38', 'online', 58.00, 'paid', '751.jpg', 3, NULL, 0, ''),
(752, 4, 'Hut 4', '2026-11-03 17:12:53', '2026-11-03 17:12:53', '2026-11-03 17:27:48', 'online', 46.00, 'paid', '752.jpg', 3, NULL, 0, ''),
(754, 1, 'Hut 1', '2026-11-03 17:23:19', '2026-11-03 17:23:19', '2026-11-03 18:04:02', 'online', 60.00, 'paid', '754.jpg', 3, NULL, 0, ''),
(755, 2, 'Hut 2', '2026-11-03 17:31:44', '2026-11-03 17:31:44', '2026-11-03 17:41:02', 'cash', 71.00, 'paid', '', 3, NULL, 0, ''),
(756, 5, 'Outdoor 1', '2026-11-03 17:44:04', '2026-11-03 17:44:04', NULL, 'credit', 103.00, 'credit', '', 3, 56, 1, ''),
(757, 5, 'Outdoor 1', '2026-11-03 18:04:12', '2026-11-03 18:04:12', NULL, 'credit', 10.00, 'credit', '', 3, 57, 1, ''),
(758, 1, 'Hut 1', '2026-11-03 18:15:55', '2026-11-03 18:15:55', '2026-11-03 18:56:45', 'cash', 14.00, 'paid', '', 3, NULL, 0, ''),
(759, 3, 'Hut 3', '2026-11-03 18:34:46', '2026-11-03 18:34:46', '2026-11-03 18:58:23', 'online', 49.00, 'paid', '759.jpg', 3, NULL, 0, ''),
(760, 4, 'Hut 4', '2026-11-03 19:01:42', '2026-11-03 19:01:42', '2026-11-03 19:34:05', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(761, 6, 'Outdoor 2', '2026-11-03 19:07:11', '2026-11-03 19:07:12', '2026-11-03 19:07:17', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(762, 6, 'Outdoor 2', '2026-11-03 19:07:27', '2026-11-03 19:07:27', '2026-11-03 19:07:30', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(763, 6, 'Outdoor 2', '2026-11-03 19:08:39', '2026-11-03 19:08:39', '2026-11-03 19:21:31', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(764, 3, 'Hut 3', '2026-11-03 19:21:23', '2026-11-03 19:21:24', '2026-11-04 00:04:41', 'online', 46.00, 'paid', '764.jpg', 3, NULL, 0, ''),
(765, 1, 'Hut 1', '2026-11-03 19:26:22', '2026-11-03 19:26:23', NULL, 'credit', 7.00, 'credit', '', 3, 12, 1, ''),
(766, 6, 'Outdoor 2', '2026-11-03 19:38:25', '2026-11-03 19:38:26', '2026-11-03 20:57:01', 'cash', 9.00, 'paid', '', 3, NULL, 0, ''),
(768, 4, 'Hut 4', '2026-11-03 20:49:18', '2026-11-03 20:49:19', '2026-11-03 20:49:57', 'online', 7.00, 'paid', '768.jpg', 3, NULL, 0, ''),
(769, 4, 'Hut 4', '2026-11-03 20:50:23', '2026-11-03 20:50:24', NULL, 'credit', 46.00, 'credit', '', 3, 18, 1, ''),
(770, 1, 'Hut 1', '2026-11-03 21:12:10', '2026-11-03 21:12:11', '2026-11-03 21:12:42', 'cash', 27.00, 'paid', '', 3, NULL, 0, ''),
(771, 7, 'Outdoor 3', '2026-11-03 21:23:52', '2026-11-03 21:23:52', '2026-11-03 22:17:31', 'online', 40.00, 'paid', '771.jpg', 3, NULL, 0, ''),
(772, 6, 'Outdoor 2', '2026-11-03 21:25:05', '2026-11-03 21:25:06', '2026-11-03 22:59:44', 'cash', 10.00, 'paid', '', 3, NULL, 0, ''),
(773, 8, 'Outdoor 4', '2026-11-03 21:29:27', '2026-11-03 21:29:27', '2026-11-03 21:29:34', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(774, 2, 'Hut 2', '2026-11-03 21:38:52', '2026-11-03 21:38:52', '2026-11-03 22:42:58', 'online', 42.00, 'paid', '774.jpg', 3, NULL, 0, ''),
(775, 7, 'Outdoor 3', '2026-11-03 23:23:54', '2026-11-03 23:23:55', NULL, 'credit', 70.00, 'credit', '', 3, 18, 1, ''),
(776, 9, 'Indoor 1', '2026-11-04 00:29:46', '2026-11-04 00:29:46', NULL, 'credit', 10.00, 'credit', '', 3, 21, 1, ''),
(777, 4, 'Hut 4', '2026-11-04 07:20:31', '2026-11-04 07:20:32', '2026-11-04 11:25:36', 'online', 38.00, 'paid', '', 9, 31, 1, ''),
(778, 6, 'Outdoor 2', '2026-11-04 07:44:59', '2026-11-04 07:45:00', NULL, 'credit', 7.00, 'credit', '', 9, 14, 1, ''),
(779, 6, 'Outdoor 2', '2026-11-04 08:11:10', '2026-11-04 08:11:11', '2026-11-04 08:11:32', 'cash', 29.00, 'paid', '', 9, NULL, 0, ''),
(780, 2, 'Hut 2', '2026-11-04 08:58:31', '2026-11-04 08:58:31', '2026-11-04 09:58:04', 'online', 71.00, 'paid', '780.jpg', 9, NULL, 0, ''),
(781, 4, 'Hut 4', '2026-11-04 09:27:34', '2026-11-04 09:27:34', '2026-11-04 09:27:50', 'online', 7.00, 'paid', '781.jpg', 9, NULL, 0, ''),
(782, 1, 'Hut 1', '2026-11-04 10:56:07', '2026-11-04 10:56:07', '2026-11-04 11:00:59', 'online', 37.00, 'paid', '', 9, 37, 1, ''),
(783, 2, 'Hut 2', '2026-11-04 11:02:34', '2026-11-04 11:02:34', '2026-11-04 11:18:58', 'cash', 42.00, 'paid', '', 9, NULL, 0, ''),
(784, 4, 'Hut 4', '2026-11-04 11:03:01', '2026-11-04 11:03:02', NULL, 'credit', 12.00, 'credit', '', 9, 18, 1, ''),
(785, 1, 'Hut 1', '2026-11-04 11:19:10', '2026-11-04 11:19:10', '2026-11-04 11:19:56', 'online', 48.00, 'paid', '785.jpg', 9, NULL, 0, ''),
(786, 3, 'Hut 3', '2026-11-04 11:21:52', NULL, NULL, 'credit', 0.00, 'credit', '', 9, 14, 1, ''),
(787, 1, 'Hut 1', '2026-11-04 11:22:09', '2026-11-04 11:22:09', '2026-11-04 11:25:36', 'online', 19.00, 'paid', '', 9, 31, 1, ''),
(788, 1, 'Hut 1', '2026-11-04 12:02:27', '2026-11-04 12:02:27', '2026-11-04 12:23:13', 'online', 50.00, 'paid', '', 9, NULL, 0, ''),
(789, 4, 'Hut 4', '2026-11-04 12:02:52', '2026-11-04 12:02:52', '2026-11-04 12:35:49', 'online', 22.00, 'paid', '789.jpg', 9, NULL, 0, ''),
(790, 3, 'Hut 3', '2026-11-04 12:43:58', '2026-11-04 12:43:59', '2026-11-04 12:45:40', 'online', 38.00, 'paid', '790.jpg', 9, NULL, 0, ''),
(791, 4, 'Hut 4', '2026-11-04 13:51:06', '2026-11-04 13:51:06', '2026-11-04 13:51:23', 'online', 12.00, 'paid', '791.jpg', 9, NULL, 0, ''),
(792, 1, 'Hut 1', '2026-11-04 14:42:32', '2026-11-04 14:42:32', NULL, 'credit', 43.00, 'credit', '', 9, 12, 1, ''),
(793, 2, 'Hut 2', '2026-11-04 14:48:56', '2026-11-04 14:48:56', '2026-11-04 15:19:00', 'online', 34.00, 'paid', '793.jpg', 9, NULL, 0, ''),
(794, 3, 'Hut 3', '2026-11-04 14:49:10', '2026-11-04 14:49:10', '2026-11-04 15:42:35', 'cash', 37.00, 'paid', '', 9, NULL, 0, ''),
(795, 4, 'Hut 4', '2026-11-04 14:49:28', '2026-11-04 14:49:28', '2026-11-04 15:05:22', 'online', 36.00, 'paid', '', 9, 41, 1, ''),
(796, 6, 'Outdoor 2', '2026-11-04 15:15:52', '2026-11-04 15:43:11', '2026-11-04 15:43:31', 'online', 7.00, 'paid', '796.jpg', 3, NULL, 0, ''),
(797, 2, 'Hut 2', '2026-11-04 15:41:59', '2026-11-04 15:41:59', '2026-11-04 15:52:34', 'online', 19.00, 'paid', '797.jpg', 3, NULL, 0, ''),
(798, 5, 'Outdoor 1', '2026-11-04 15:46:57', '2026-11-04 15:46:57', '2026-11-04 17:08:12', 'online', 35.00, 'paid', '798.jpg', 3, NULL, 0, ''),
(799, 6, 'Outdoor 2', '2026-11-04 16:23:51', '2026-11-04 16:23:52', '2026-11-04 16:24:17', 'online', 10.00, 'paid', '799.jpg', 3, NULL, 0, ''),
(800, 2, 'Hut 2', '2026-11-04 16:24:46', '2026-11-04 16:24:46', '2026-11-04 16:27:09', 'online', 24.00, 'paid', '800.jpg', 3, NULL, 0, ''),
(801, 6, 'Outdoor 2', '2026-11-04 16:30:53', '2026-11-04 16:30:53', '2026-11-04 16:30:58', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(802, 5, 'Outdoor 1', '2026-11-04 17:12:41', '2026-11-04 17:12:41', '2026-11-04 17:13:29', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(803, 4, 'Hut 4', '2026-11-04 17:27:09', '2026-11-04 17:27:10', '2026-11-04 18:12:31', 'online', 100.00, 'paid', '803.jpg', 3, NULL, 0, ''),
(804, 2, 'Hut 2', '2026-11-04 17:38:40', '2026-11-04 17:38:40', '2026-11-04 18:01:57', 'online', 40.00, 'paid', '804.jpg', 3, NULL, 0, ''),
(805, 1, 'Hut 1', '2026-11-04 18:05:31', '2026-11-04 18:05:31', NULL, 'credit', 124.00, 'credit', '', 3, 11, 1, ''),
(806, 3, 'Hut 3', '2026-11-04 18:19:03', '2026-11-04 18:19:03', '2026-11-04 19:07:44', 'online', 36.00, 'paid', '806.jpg', 3, NULL, 0, ''),
(807, 5, 'Outdoor 1', '2026-11-04 18:25:51', '2026-11-04 18:25:52', '2026-11-04 18:47:36', 'cash', 52.00, 'paid', '', 3, NULL, 0, ''),
(808, 2, 'Hut 2', '2026-11-04 18:26:07', '2026-11-04 18:26:07', '2026-11-04 19:24:34', 'online', 24.00, 'paid', '808.jpg', 3, NULL, 0, ''),
(809, 6, 'Outdoor 2', '2026-11-04 18:54:11', '2026-11-04 18:54:11', '2026-11-04 19:00:09', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(810, 5, 'Outdoor 1', '2026-11-04 19:01:28', '2026-11-04 19:01:28', '2026-11-04 19:02:16', 'cash', 17.00, 'paid', '', 3, NULL, 0, ''),
(811, 8, 'Outdoor 4', '2026-11-04 19:06:08', '2026-11-04 19:06:09', '2026-11-04 19:49:58', 'cash', 23.00, 'paid', '', 3, NULL, 0, ''),
(812, 3, 'Hut 3', '2026-11-04 19:20:23', '2026-11-04 19:20:23', '2026-11-04 19:21:08', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(813, 4, 'Hut 4', '2026-11-04 19:24:45', '2026-11-04 19:24:45', '2026-11-04 19:44:53', 'online', 37.00, 'paid', '813.jpg', 3, NULL, 0, ''),
(814, 1, 'Hut 1', '2026-11-04 19:25:56', '2026-11-04 19:25:56', '2026-11-04 20:29:18', 'cash', 22.00, 'paid', '', 3, NULL, 0, ''),
(815, 3, 'Hut 3', '2026-11-04 19:29:56', '2026-11-04 19:29:56', '2026-11-04 20:06:22', 'online', 51.00, 'paid', '815.jpg', 3, NULL, 0, ''),
(816, 1, 'Hut 1', '2026-11-04 21:01:47', '2026-11-04 21:01:47', '2026-11-04 21:38:44', 'online', 50.00, 'paid', '816.jpg', 9, NULL, 0, ''),
(817, 4, 'Hut 4', '2026-11-04 21:39:54', '2026-11-04 21:39:54', '2026-11-04 22:10:19', 'cash', 30.00, 'paid', '', 3, NULL, 0, '');
INSERT INTO `sales` (`id`, `table_id`, `table_name`, `opened_at`, `service_started_at`, `closed_at`, `payment_method`, `total`, `status`, `slip_image`, `waiter_id`, `customer_id`, `held`, `pay_review`) VALUES
(818, 5, 'Outdoor 1', '2026-11-04 21:42:02', '2026-11-04 21:42:02', '2026-11-04 21:42:05', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(819, 3, 'Hut 3', '2026-11-04 21:58:01', '2026-11-04 21:58:02', '2026-11-04 21:58:28', 'online', 30.00, 'paid', '819.jpg', 3, NULL, 0, ''),
(820, 1, 'Hut 1', '2026-11-04 21:59:03', '2026-11-04 21:59:04', '2026-11-04 22:07:54', 'online', 15.00, 'paid', '820.jpg', 3, NULL, 0, ''),
(821, 2, 'Hut 2', '2026-11-04 22:12:52', '2026-11-04 22:12:52', '2026-11-04 22:14:08', 'online', 10.00, 'paid', '821.jpg', 3, NULL, 0, ''),
(822, 5, 'Outdoor 1', '2026-11-04 22:27:50', '2026-11-04 22:27:50', '2026-11-04 22:45:23', 'cash', 17.00, 'paid', '', 3, NULL, 0, ''),
(823, 1, 'Hut 1', '2026-11-04 22:51:28', '2026-11-04 22:51:28', '2026-11-04 23:31:07', 'online', 22.00, 'paid', '823.jpg', 3, NULL, 0, ''),
(824, 3, 'Hut 3', '2026-11-04 22:51:32', '2026-11-04 22:51:32', '2026-11-04 23:23:42', 'online', 7.00, 'paid', '824.jpg', 3, NULL, 0, ''),
(825, 5, 'Outdoor 1', '2026-11-04 23:17:44', '2026-11-04 23:17:44', '2026-11-04 23:17:46', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(826, 3, 'Hut 3', '2026-11-05 07:28:35', '2026-11-05 07:28:35', '2026-11-05 07:37:57', 'online', 24.00, 'paid', '826.jpg', 9, NULL, 0, ''),
(827, 9, 'Indoor 1', '2026-11-05 07:28:48', '2026-11-05 07:28:49', NULL, 'credit', 10.00, 'credit', '', 9, 20, 1, ''),
(828, 3, 'Hut 3', '2026-11-05 07:44:13', '2026-11-05 07:44:14', '2026-11-05 11:59:21', 'online', 14.00, 'paid', '', 9, 50, 1, ''),
(829, 4, 'Hut 4', '2026-11-05 08:37:05', '2026-11-05 08:37:05', NULL, 'credit', 54.00, 'credit', '', 9, 18, 1, ''),
(830, 3, 'Hut 3', '2026-11-05 09:32:16', '2026-11-05 09:32:17', '2026-11-05 09:33:15', 'online', 17.00, 'paid', '', 9, NULL, 0, ''),
(831, 1, 'Hut 1', '2026-11-05 09:37:41', '2026-11-05 09:37:41', '2026-11-05 09:37:53', 'online', 15.00, 'paid', '', 9, NULL, 0, ''),
(832, 4, 'Hut 4', '2026-11-05 09:38:32', '2026-11-05 09:38:32', NULL, 'credit', 14.00, 'credit', '', 9, 18, 1, ''),
(833, 3, 'Hut 3', '2026-11-05 09:48:42', '2026-11-05 09:48:42', NULL, 'credit', 27.00, 'credit', '', 9, 10, 1, ''),
(834, 1, 'Hut 1', '2026-11-05 10:18:55', '2026-11-05 10:18:55', NULL, 'credit', 24.00, 'credit', '', 9, 46, 1, ''),
(835, 1, 'Hut 1', '2026-11-05 10:35:44', '2026-11-05 10:35:44', '2026-11-05 10:35:50', 'online', 7.00, 'paid', '', 9, NULL, 0, ''),
(836, 1, 'Hut 1', '2026-11-05 10:35:58', '2026-11-05 10:35:58', '2026-11-05 11:09:26', 'online', 27.00, 'paid', '', 9, NULL, 0, ''),
(837, 4, 'Hut 4', '2026-11-05 10:43:25', '2026-11-05 10:43:25', '2026-11-05 11:02:36', 'online', 7.00, 'paid', '', 9, NULL, 0, ''),
(838, 4, 'Hut 4', '2026-11-05 11:54:26', '2026-11-05 11:54:26', '2026-11-05 11:59:21', 'online', 19.00, 'paid', '', 9, 50, 1, ''),
(839, 1, 'Hut 1', '2026-11-05 12:02:08', '2026-11-05 12:02:08', '2026-11-05 12:20:47', 'online', 63.00, 'paid', '', 9, NULL, 0, ''),
(840, 4, 'Hut 4', '2026-11-05 12:45:52', '2026-11-05 12:45:52', '2026-11-05 12:46:23', 'online', 24.00, 'paid', '', 9, NULL, 0, ''),
(841, 4, 'Hut 4', '2026-11-05 12:56:44', '2026-11-05 12:56:44', NULL, 'credit', 41.00, 'credit', '', 9, 18, 1, ''),
(842, 1, 'Hut 1', '2026-11-05 13:27:25', '2026-11-05 13:27:25', '2026-11-05 13:28:16', 'online', 27.00, 'paid', '', 9, NULL, 0, ''),
(843, 4, 'Hut 4', '2026-11-05 13:38:32', '2026-11-05 13:38:32', NULL, 'credit', 17.00, 'credit', '', 9, 18, 1, ''),
(844, 2, 'Hut 2', '2026-11-05 13:39:33', '2026-11-05 13:39:33', NULL, 'credit', 29.00, 'credit', '', 9, 12, 1, ''),
(845, 5, 'Outdoor 1', '2026-11-05 15:17:14', '2026-11-05 15:17:14', '2026-11-05 15:17:19', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(846, 4, 'Hut 4', '2026-11-05 15:18:03', '2026-11-05 15:18:03', '2026-11-05 15:58:39', 'online', 56.00, 'paid', '846.jpg', 9, NULL, 0, ''),
(847, 1, 'Hut 1', '2026-11-05 15:23:52', '2026-11-05 15:23:52', '2026-11-05 15:54:11', 'online', 40.00, 'paid', '847.jpg', 3, NULL, 0, ''),
(848, 5, 'Outdoor 1', '2026-11-05 15:25:05', '2026-11-05 15:25:06', NULL, 'credit', 30.00, 'credit', '', 9, 46, 1, ''),
(849, 2, 'Hut 2', '2026-11-05 15:29:34', '2026-11-05 15:29:34', '2026-11-05 15:29:55', 'cash', 24.00, 'paid', '', 9, NULL, 0, ''),
(850, 5, 'Outdoor 1', '2026-11-05 15:33:01', '2026-11-05 15:33:01', '2026-11-05 15:51:08', 'cash', 24.00, 'paid', '', 3, NULL, 0, ''),
(851, 3, 'Hut 3', '2026-11-05 15:44:47', '2026-11-05 15:44:47', '2026-11-05 16:37:40', 'online', 82.00, 'paid', '851.jpg', 3, NULL, 0, ''),
(852, 5, 'Outdoor 1', '2026-11-05 16:03:39', '2026-11-05 16:03:39', '2026-11-05 16:03:44', 'cash', 15.00, 'paid', '', 3, NULL, 0, ''),
(853, 5, 'Outdoor 1', '2026-11-05 16:04:02', '2026-11-05 16:04:02', '2026-11-05 16:04:11', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(854, 1, 'Hut 1', '2026-11-05 16:50:06', '2026-11-05 16:50:06', '2026-11-05 17:45:13', 'online', 30.00, 'paid', '854.jpg', 9, NULL, 0, ''),
(855, 8, 'Outdoor 4', '2026-11-05 16:50:49', '2026-11-05 16:50:49', '2026-11-05 16:50:58', 'cash', 25.00, 'paid', '', 9, NULL, 0, ''),
(856, 8, 'Outdoor 4', '2026-11-05 16:51:03', '2026-11-05 16:51:03', '2026-11-05 16:51:10', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(857, 6, 'Outdoor 2', '2026-11-05 17:01:37', '2026-11-05 17:01:37', '2026-11-05 17:02:15', 'cash', 30.00, 'paid', '', 3, NULL, 0, ''),
(858, 8, 'Outdoor 4', '2026-11-05 17:09:31', '2026-11-05 17:09:31', '2026-11-05 17:17:44', 'cash', 23.00, 'paid', '', 9, NULL, 0, ''),
(859, 2, 'Hut 2', '2026-11-05 17:09:45', '2026-11-05 17:09:45', '2026-11-05 17:21:39', 'online', 14.00, 'paid', '859.jpg', 9, NULL, 0, ''),
(860, 4, 'Hut 4', '2026-11-05 17:17:51', '2026-11-05 17:17:51', '2026-11-05 19:04:12', 'cash', 56.00, 'paid', '', 3, NULL, 0, ''),
(861, 8, 'Outdoor 4', '2026-11-05 17:24:37', '2026-11-05 17:24:37', '2026-11-05 17:25:00', 'online', 40.00, 'paid', '861.jpg', 9, NULL, 0, ''),
(862, 6, 'Outdoor 2', '2026-11-05 17:30:28', '2026-11-05 17:30:28', '2026-11-05 17:30:33', 'cash', 10.00, 'paid', '', 9, NULL, 0, ''),
(863, 8, 'Outdoor 4', '2026-11-05 17:36:57', '2026-11-05 17:36:58', '2026-11-05 17:44:43', 'cash', 50.00, 'paid', '', 9, NULL, 0, ''),
(864, 2, 'Hut 2', '2026-11-05 17:37:16', '2026-11-05 17:37:16', '2026-11-05 18:33:19', 'online', 27.00, 'paid', '864.jpg', 9, NULL, 0, ''),
(865, 5, 'Outdoor 1', '2026-11-05 17:51:31', '2026-11-05 17:51:31', '2026-11-05 17:51:46', 'cash', 120.00, 'paid', '', 9, NULL, 0, ''),
(866, 5, 'Outdoor 1', '2026-11-05 17:51:53', '2026-11-05 17:51:53', '2026-11-05 17:51:59', 'cash', 20.00, 'paid', '', 9, NULL, 0, ''),
(867, 1, 'Hut 1', '2026-11-05 17:58:11', '2026-11-05 17:58:11', '2026-11-05 18:44:32', 'online', 80.00, 'paid', '867.jpg', 9, NULL, 0, ''),
(868, 6, 'Outdoor 2', '2026-11-05 18:22:08', '2026-11-05 18:22:08', '2026-11-05 18:29:40', 'cash', 20.00, 'paid', '', 3, NULL, 0, ''),
(869, 3, 'Hut 3', '2026-11-05 18:29:20', '2026-11-05 18:29:20', NULL, 'credit', 25.00, 'credit', '', 3, 18, 1, ''),
(870, 3, 'Hut 3', '2026-11-05 18:44:38', '2026-11-05 18:44:38', '2026-11-05 19:03:48', 'cash', 34.00, 'paid', '', 3, NULL, 0, ''),
(871, 1, 'Hut 1', '2026-11-05 19:04:33', '2026-11-05 19:04:33', '2026-11-05 19:33:50', 'cash', 7.00, 'paid', '', 3, NULL, 0, ''),
(872, 4, 'Hut 4', '2026-11-05 19:10:54', '2026-11-05 19:10:54', '2026-11-05 19:50:57', 'online', 32.00, 'paid', '872.jpg', 3, NULL, 0, ''),
(873, 4, 'Hut 4', '2026-11-05 20:28:24', '2026-11-05 20:28:24', '2026-11-05 21:14:30', 'online', 7.00, 'paid', '873.jpg', 3, NULL, 0, ''),
(874, 3, 'Hut 3', '2026-11-05 21:14:33', '2026-11-05 21:14:33', '2026-11-05 21:33:11', 'cash', 49.00, 'paid', '', 9, NULL, 0, ''),
(875, 5, 'Outdoor 1', '2026-11-05 21:14:56', '2026-11-05 21:14:56', '2026-11-05 21:59:12', 'cash', 15.00, 'paid', '', 9, NULL, 0, ''),
(876, 8, 'Outdoor 4', '2026-11-05 21:15:15', '2026-11-05 21:15:16', '2026-11-05 23:50:14', 'online', 9.00, 'paid', '876.jpg', 9, NULL, 0, ''),
(877, 4, 'Hut 4', '2026-11-05 21:34:00', '2026-11-05 21:34:00', '2026-11-05 22:42:51', 'online', 41.00, 'paid', '877.jpg', 3, NULL, 0, ''),
(878, 1, 'Hut 1', '2026-11-05 21:59:18', '2026-11-05 21:59:18', '2026-11-05 22:39:14', 'online', 43.00, 'paid', '878.jpg', 3, NULL, 0, ''),
(879, 2, 'Hut 2', '2026-11-05 22:00:08', '2026-11-05 22:00:08', '2026-11-05 22:14:18', 'online', 26.00, 'paid', '879.jpg', 3, NULL, 0, ''),
(880, 3, 'Hut 3', '2026-11-05 22:14:28', '2026-11-05 22:14:28', '2026-11-05 22:15:16', 'online', 12.00, 'paid', '880.jpg', 3, NULL, 0, ''),
(881, 3, 'Hut 3', '2026-11-05 22:36:55', '2026-11-05 22:36:55', '2026-11-05 23:58:38', 'online', 30.00, 'paid', '881.jpg', 3, NULL, 0, ''),
(883, 2, 'Hut 2', '2026-11-05 22:39:48', '2026-11-05 22:39:48', '2026-11-05 23:04:37', 'online', 5.00, 'paid', '883.jpg', 3, NULL, 0, ''),
(884, 1, 'Hut 1', '2026-11-05 22:46:15', '2026-11-05 22:46:15', '2026-11-05 23:48:02', 'online', 30.00, 'paid', '884.jpg', 3, NULL, 0, ''),
(885, 4, 'Hut 4', '2026-11-05 23:07:51', '2026-11-05 23:07:52', '2026-11-05 23:41:31', 'online', 112.00, 'paid', '885.jpg', 3, NULL, 0, ''),
(886, 6, 'Outdoor 2', '2026-11-05 23:15:52', '2026-11-05 23:15:53', '2026-11-06 09:25:12', 'cash', 19.00, 'paid', '', 3, 58, 1, ''),
(887, 4, 'Hut 4', '2026-11-05 23:45:45', '2026-11-05 23:45:46', '2026-11-05 23:46:35', 'online', 5.00, 'paid', '887.jpg', 3, NULL, 0, ''),
(888, 6, 'Outdoor 2', '2026-11-06 10:59:51', '2026-11-06 10:59:51', '2026-11-06 10:59:54', 'cash', 5.00, 'paid', '', 3, NULL, 0, ''),
(889, 4, 'Hut 4', '2026-11-06 14:51:44', '2026-11-06 14:51:44', '2026-11-06 15:57:36', 'online', 97.00, 'paid', '889.jpg', 3, NULL, 0, ''),
(890, 1, 'Hut 1', '2026-11-06 15:35:56', '2026-11-06 15:35:56', '2026-11-06 15:57:42', 'cash', 46.00, 'paid', '', 3, NULL, 0, ''),
(891, 3, 'Hut 3', '2026-11-06 15:53:31', '2026-11-06 15:53:31', '2026-11-06 15:54:06', 'online', 19.00, 'paid', '891.jpg', 3, NULL, 0, ''),
(892, 2, 'Hut 2', '2026-11-06 15:57:45', '2026-11-06 15:57:45', '2026-11-06 16:04:10', 'online', 25.00, 'paid', '892.jpg', 3, NULL, 0, ''),
(893, 4, 'Hut 4', '2026-11-06 16:26:03', '2026-11-06 16:26:04', '2026-11-06 16:40:15', 'cash', 19.00, 'paid', '', 9, NULL, 0, ''),
(894, 3, 'Hut 3', '2026-11-06 17:06:00', '2026-11-06 17:06:00', '2026-11-06 17:12:18', 'cash', 19.00, 'paid', '', 9, NULL, 0, ''),
(895, 1, 'Hut 1', '2026-11-06 17:06:07', '2026-11-06 17:06:08', '2026-11-06 17:58:35', 'online', 31.00, 'paid', '895.jpg', 9, NULL, 0, ''),
(896, 3, 'Hut 3', '2026-11-06 17:12:22', '2026-11-06 17:12:22', '2026-11-06 18:19:44', 'online', 22.00, 'paid', '896.jpg', 3, NULL, 0, ''),
(897, 4, 'Hut 4', '2026-11-06 17:24:20', '2026-11-06 17:24:21', '2026-11-06 17:50:10', 'cash', 25.00, 'paid', '', 3, NULL, 0, ''),
(898, 2, 'Hut 2', '2026-11-06 17:39:51', '2026-11-06 17:39:51', '2026-11-06 17:39:54', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(899, 6, 'Outdoor 2', '2026-11-06 17:41:37', '2026-11-06 17:41:38', '2026-11-06 17:41:46', 'cash', 12.00, 'paid', '', 3, NULL, 0, ''),
(900, 2, 'Hut 2', '2026-11-06 18:01:09', '2026-11-06 18:01:10', '2026-11-06 18:21:49', 'online', 27.00, 'paid', '900.jpg', 3, NULL, 0, ''),
(901, 4, 'Hut 4', '2026-11-07 09:04:05', '2026-11-07 09:04:05', NULL, 'credit', 31.00, 'credit', '', 9, 18, 1, ''),
(902, 1, 'Hut 1', '2026-11-07 10:15:00', '2026-11-07 10:15:00', NULL, NULL, 19.00, 'open', '', 3, NULL, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `sale_lines`
--

CREATE TABLE `sale_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `item_name` varchar(120) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `added_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_lines`
--

INSERT INTO `sale_lines` (`id`, `sale_id`, `item_id`, `item_name`, `unit_price`, `qty`, `line_total`, `added_at`) VALUES
(4, 3, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-21 18:01:50'),
(5, 2, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-21 18:00:59'),
(6, 4, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-21 18:08:13'),
(7, 4, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-21 18:08:13'),
(8, 4, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-21 18:08:13'),
(10, 4, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 18:08:13'),
(15, 9, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-21 18:31:44'),
(16, 9, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-21 18:31:44'),
(18, 9, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 18:31:44'),
(20, 11, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-21 18:42:00'),
(24, 11, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-21 18:42:00'),
(25, 11, 12, 'Water Bottle Small', 5.00, 2, 10.00, '2026-10-21 18:42:00'),
(26, 11, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-21 18:42:00'),
(27, 15, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-21 19:02:41'),
(28, 15, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-21 19:02:41'),
(29, 15, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-21 19:02:41'),
(30, 15, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 19:02:41'),
(32, 16, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-21 19:03:38'),
(33, 16, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 19:03:38'),
(34, 16, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-21 19:03:38'),
(35, 16, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 19:03:38'),
(36, 17, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-21 19:04:43'),
(37, 17, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-21 19:04:43'),
(38, 17, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 19:04:43'),
(39, 17, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 19:04:43'),
(40, 18, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-21 19:13:22'),
(41, 18, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 19:13:22'),
(42, 18, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 19:13:22'),
(43, 18, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-21 19:13:22'),
(44, 16, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-21 19:03:38'),
(47, 21, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-21 19:21:50'),
(48, 21, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-21 19:21:50'),
(54, 11, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-21 18:42:00'),
(64, 30, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-21 20:44:40'),
(66, 31, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-21 21:56:53'),
(67, 31, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-21 21:56:53'),
(68, 31, 12, 'Water Bottle Small', 5.00, 3, 15.00, '2026-10-21 21:56:53'),
(69, 31, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 21:56:53'),
(70, 32, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-21 22:16:05'),
(71, 32, 3, 'Cereal Coffee', 15.00, 1, 15.00, '2026-10-21 22:16:05'),
(72, 33, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-21 22:22:29'),
(77, 38, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-21 22:51:51'),
(78, 39, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 23:15:49'),
(79, 39, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 23:15:49'),
(80, 39, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-21 23:15:49'),
(81, 40, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-21 23:36:31'),
(82, 40, 12, 'Water Bottle Small', 5.00, 1, 5.00, '2026-10-21 23:36:31'),
(84, 42, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-21 23:59:22'),
(93, 52, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 11:36:35'),
(94, 52, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 11:36:41'),
(98, 54, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-22 11:51:16'),
(99, 54, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 11:51:20'),
(100, 54, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-22 11:51:34'),
(101, 55, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-22 11:59:16'),
(102, 55, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 11:59:38'),
(103, 55, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 11:59:58'),
(104, 56, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-22 12:01:27'),
(105, 56, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 12:01:35'),
(108, 58, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 12:07:32'),
(109, 58, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 12:07:41'),
(110, 58, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 12:07:43'),
(112, 59, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-22 12:19:01'),
(113, 59, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 12:19:03'),
(114, 59, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-22 12:19:06'),
(115, 60, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 13:00:29'),
(116, 60, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 13:00:38'),
(117, 61, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 13:03:50'),
(118, 61, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 13:03:53'),
(119, 60, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 13:35:56'),
(120, 62, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 13:58:30'),
(121, 59, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-22 14:05:41'),
(123, 63, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-22 14:12:32'),
(124, 63, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 14:12:36'),
(126, 65, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 14:27:31'),
(127, 65, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 14:32:26'),
(128, 66, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-22 14:43:31'),
(130, 67, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 14:45:15'),
(131, 67, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-22 14:45:20'),
(138, 66, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:08:52'),
(140, 68, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 15:10:04'),
(145, 68, 7, 'Black Coffee Hard', 10.00, 7, 70.00, '2026-10-22 15:11:51'),
(146, 68, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-22 15:12:10'),
(147, 68, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-22 15:12:19'),
(148, 70, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 15:13:32'),
(149, 70, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:13:37'),
(150, 71, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-22 15:14:33'),
(151, 71, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:14:38'),
(152, 70, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 15:15:12'),
(154, 71, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-22 15:27:46'),
(156, 73, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-22 15:34:13'),
(157, 73, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:34:19'),
(159, 74, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 15:34:48'),
(160, 74, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-22 15:34:51'),
(161, 74, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:34:57'),
(162, 75, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 15:50:30'),
(163, 75, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-22 15:50:39'),
(164, 75, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-22 15:50:47'),
(165, 75, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-22 15:50:57'),
(166, 73, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 15:51:34'),
(167, 73, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-22 15:51:41'),
(168, 73, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-22 15:51:45'),
(169, 76, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-22 15:52:35'),
(170, 76, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-22 15:52:47'),
(171, 76, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 15:52:59'),
(172, 76, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 15:53:17'),
(173, 73, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-22 15:58:32'),
(174, 75, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 16:00:57'),
(175, 77, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 16:24:09'),
(176, 77, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 16:24:25'),
(177, 78, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-22 16:26:34'),
(178, 78, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-22 16:26:40'),
(179, 79, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-22 16:27:06'),
(180, 79, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-22 16:30:59'),
(181, 79, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 16:31:02'),
(182, 79, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 16:31:23'),
(183, 79, 6, 'Hedhika', 5.00, 15, 75.00, '2026-10-22 16:31:36'),
(184, 80, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-22 16:49:26'),
(185, 77, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 16:53:08'),
(192, 82, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-22 17:07:29'),
(193, 82, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 17:07:31'),
(194, 82, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 17:07:35'),
(195, 83, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-22 17:07:56'),
(196, 83, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-22 17:08:03'),
(197, 83, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 17:08:07'),
(198, 83, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-22 17:08:12'),
(199, 83, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 17:08:17'),
(200, 84, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-22 17:09:17'),
(201, 85, 6, 'Hedhika', 5.00, 15, 75.00, '2026-10-22 17:14:15'),
(202, 82, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 17:22:21'),
(203, 78, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 17:25:24'),
(204, 86, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-22 17:39:45'),
(205, 87, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 17:44:56'),
(206, 87, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-22 17:45:02'),
(210, 89, 6, 'Hedhika', 5.00, 12, 60.00, '2026-10-22 17:54:16'),
(211, 90, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-22 17:58:59'),
(212, 90, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-22 17:59:13'),
(213, 90, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 17:59:16'),
(214, 91, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-22 18:11:26'),
(215, 91, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-22 18:11:31'),
(216, 91, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 18:11:37'),
(217, 59, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-22 18:20:50'),
(218, 92, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-22 18:22:12'),
(219, 93, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-22 18:29:26'),
(220, 93, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-22 18:29:36'),
(221, 93, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 18:30:31'),
(222, 93, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-22 18:30:34'),
(223, 94, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-22 18:40:23'),
(224, 94, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 18:40:27'),
(225, 94, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 18:40:32'),
(226, 95, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-22 18:45:19'),
(227, 95, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-22 18:45:29'),
(228, 95, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 18:45:33'),
(229, 95, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 18:45:37'),
(233, 94, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 18:59:00'),
(234, 96, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-22 18:59:52'),
(235, 96, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-22 18:59:57'),
(236, 96, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-22 19:00:03'),
(237, 97, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 19:40:21'),
(238, 97, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 19:40:23'),
(239, 98, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 19:40:29'),
(240, 98, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 19:40:31'),
(241, 98, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 19:40:33'),
(242, 98, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 19:43:46'),
(243, 92, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 20:04:05'),
(244, 96, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 20:17:47'),
(245, 96, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-22 20:17:53'),
(246, 99, 7, 'Black Coffee Hard', 10.00, 41, 410.00, '2026-10-22 20:37:01'),
(247, 99, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-22 20:37:18'),
(249, 100, 7, 'Black Coffee Hard', 10.00, 25, 250.00, '2026-10-22 20:50:50'),
(250, 100, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-10-22 20:51:20'),
(253, 92, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-22 21:00:00'),
(254, 102, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-22 21:20:22'),
(255, 102, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 21:20:27'),
(256, 102, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 21:20:29'),
(257, 102, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 21:20:36'),
(258, 103, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-22 21:21:18'),
(259, 103, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-22 21:21:20'),
(260, 103, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 21:21:24'),
(261, 104, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-22 21:47:08'),
(263, 105, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-22 23:35:59'),
(264, 105, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-22 23:36:05'),
(265, 106, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-23 00:38:13'),
(266, 106, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-23 00:38:15'),
(267, 107, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-23 13:59:52'),
(268, 107, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 13:59:57'),
(269, 108, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-23 14:01:27'),
(270, 108, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 14:01:30'),
(271, 107, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 14:01:47'),
(275, 109, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-23 14:44:07'),
(276, 109, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-23 14:44:16'),
(277, 109, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-23 14:44:20'),
(278, 109, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 14:44:24'),
(279, 109, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-23 14:44:28'),
(280, 110, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-23 14:58:10'),
(281, 110, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 14:58:16'),
(282, 111, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-23 15:27:49'),
(283, 110, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 15:31:23'),
(285, 112, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 15:33:27'),
(286, 111, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 15:36:17'),
(287, 113, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-23 15:50:26'),
(289, 113, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 15:50:30'),
(290, 113, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 15:50:34'),
(291, 113, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 15:50:37'),
(292, 114, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-23 16:15:47'),
(293, 114, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 16:15:52'),
(294, 114, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 16:15:56'),
(295, 115, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-23 16:20:27'),
(296, 115, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-23 16:25:20'),
(297, 116, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-23 17:22:18'),
(298, 116, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 17:22:21'),
(300, 117, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-23 17:47:26'),
(301, 117, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 17:47:31'),
(302, 117, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 17:47:34'),
(303, 118, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-23 17:48:22'),
(304, 118, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 17:48:27'),
(306, 119, 7, 'Black Coffee Hard', 10.00, 16, 160.00, '2026-10-23 17:49:35'),
(307, 119, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-23 17:49:51'),
(309, 120, 7, 'Black Coffee Hard', 10.00, 9, 90.00, '2026-10-23 17:51:01'),
(310, 120, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-23 17:51:06'),
(312, 121, 7, 'Black Coffee Hard', 10.00, 12, 120.00, '2026-10-23 17:51:36'),
(313, 121, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-23 17:51:55'),
(315, 122, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-23 17:52:56'),
(316, 122, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 17:53:00'),
(317, 122, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 17:53:02'),
(318, 123, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 17:53:59'),
(319, 124, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-23 17:54:25'),
(320, 124, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 17:54:27'),
(321, 124, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 17:54:30'),
(322, 125, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-23 17:56:37'),
(323, 125, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 17:56:43'),
(325, 127, 7, 'Black Coffee Hard', 10.00, 11, 110.00, '2026-10-23 18:08:58'),
(326, 127, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-23 18:09:06'),
(328, 128, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-23 18:10:55'),
(329, 128, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-23 18:11:05'),
(330, 129, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 18:28:26'),
(331, 130, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-23 18:47:35'),
(332, 130, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-23 18:47:37'),
(333, 130, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 18:47:41'),
(335, 131, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 18:49:26'),
(336, 131, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-23 18:50:24'),
(337, 130, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 18:58:15'),
(338, 132, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 19:01:11'),
(339, 133, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-23 19:07:54'),
(340, 134, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-23 19:16:54'),
(341, 134, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-23 19:16:58'),
(342, 134, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-23 19:17:03'),
(343, 135, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-23 19:31:07'),
(344, 135, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-23 19:48:35'),
(345, 134, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-23 19:49:46'),
(346, 136, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-23 20:15:29'),
(347, 137, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-23 21:26:57'),
(348, 137, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-23 21:27:01'),
(349, 138, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 06:36:25'),
(350, 138, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 06:44:34'),
(351, 138, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 06:44:39'),
(352, 139, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-24 06:58:30'),
(353, 139, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 07:01:00'),
(355, 141, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 07:08:13'),
(356, 142, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 07:08:22'),
(357, 143, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-24 07:17:48'),
(358, 143, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-24 07:17:52'),
(359, 144, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 07:28:45'),
(360, 144, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 07:28:52'),
(361, 145, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 07:38:50'),
(362, 146, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-10-24 08:22:20'),
(363, 146, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 08:22:25'),
(364, 146, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 08:22:29'),
(365, 146, 16, 'Bisket', 5.00, 2, 10.00, '2026-10-24 08:22:33'),
(366, 147, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 08:23:14'),
(367, 147, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 08:23:17'),
(368, 146, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 08:25:38'),
(369, 147, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 08:37:18'),
(370, 148, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-24 08:37:35'),
(371, 148, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 08:37:38'),
(372, 148, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 08:37:42'),
(374, 146, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-24 09:10:22'),
(375, 149, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 09:29:00'),
(377, 150, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-24 09:29:25'),
(378, 150, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-24 09:29:29'),
(379, 151, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 09:31:30'),
(380, 151, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 09:31:32'),
(382, 149, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 09:51:36'),
(383, 150, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 10:13:40'),
(384, 152, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-24 10:13:55'),
(385, 153, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-10-24 10:29:28'),
(386, 153, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 10:29:31'),
(387, 153, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 10:29:33'),
(388, 154, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 10:53:36'),
(389, 154, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 10:53:37'),
(390, 155, 2, 'Milk Coffee', 10.00, 3, 30.00, '2026-10-24 10:55:51'),
(391, 155, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-24 10:55:57'),
(392, 155, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-24 10:56:02'),
(393, 155, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-24 10:56:09'),
(394, 156, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-24 11:19:49'),
(395, 156, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-24 11:19:52'),
(396, 156, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 11:19:54'),
(397, 156, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 11:43:36'),
(400, 157, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 12:05:51'),
(401, 158, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 12:16:55'),
(402, 159, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 12:54:35'),
(403, 159, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 12:54:40'),
(404, 160, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-24 12:54:58'),
(405, 160, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-24 12:55:06'),
(406, 161, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 12:55:23'),
(407, 161, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 12:55:27'),
(408, 161, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-24 12:55:30'),
(409, 162, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 12:55:40'),
(410, 162, 12, 'Water Bottle 500 ML', 5.00, 4, 20.00, '2026-10-24 12:55:45'),
(411, 160, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 12:59:35'),
(412, 163, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 13:10:49'),
(413, 163, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 13:10:51'),
(414, 164, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 13:11:00'),
(415, 165, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-24 13:14:59'),
(416, 162, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-24 13:20:01'),
(417, 166, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-24 13:57:14'),
(418, 166, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 13:57:17'),
(419, 166, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:07:25'),
(420, 167, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 14:23:55'),
(421, 167, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:23:57'),
(422, 168, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:24:51'),
(423, 169, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 14:26:13'),
(424, 169, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 14:26:16'),
(425, 169, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:26:18'),
(426, 170, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 14:33:56'),
(427, 170, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:33:58'),
(428, 171, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 14:58:54'),
(429, 171, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 14:58:56'),
(430, 172, 9, 'Lemon Tea', 10.00, 2, 20.00, '2026-10-24 15:33:11'),
(431, 172, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-24 15:33:21'),
(432, 172, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 15:33:24'),
(433, 172, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 15:33:26'),
(434, 172, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-24 15:33:28'),
(435, 173, 6, 'Hedhika', 5.00, 10, 50.00, '2026-10-24 15:50:17'),
(436, 173, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 15:50:26'),
(437, 173, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 15:50:29'),
(438, 173, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 15:50:32'),
(439, 174, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-24 15:51:04'),
(440, 174, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 15:51:10'),
(441, 174, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 15:51:13'),
(442, 173, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 15:56:02'),
(443, 175, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 16:02:34'),
(444, 176, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-24 16:02:45'),
(445, 176, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 16:02:50'),
(446, 177, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 16:09:23'),
(447, 177, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-24 16:09:27'),
(448, 178, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-24 16:11:36'),
(449, 179, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 16:18:45'),
(450, 179, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 16:18:47'),
(451, 180, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-24 16:26:16'),
(452, 180, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 16:26:22'),
(453, 180, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 16:26:29'),
(454, 181, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-24 16:27:16'),
(455, 181, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 16:27:21'),
(456, 182, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-24 16:29:30'),
(457, 182, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 16:29:34'),
(458, 182, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 16:29:37'),
(459, 180, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 16:40:08'),
(460, 183, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-24 16:46:47'),
(461, 183, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-24 16:46:56'),
(462, 183, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 16:46:59'),
(463, 183, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 16:47:02'),
(464, 183, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-24 16:58:19'),
(466, 185, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 17:45:16'),
(467, 185, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-24 17:45:24'),
(468, 185, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 17:45:29'),
(469, 185, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 17:45:31'),
(470, 185, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 17:45:33'),
(471, 186, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 17:46:47'),
(472, 186, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-24 17:46:58'),
(474, 187, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-24 18:05:58'),
(475, 187, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 18:06:11'),
(476, 188, 6, 'Hedhika', 5.00, 21, 105.00, '2026-10-24 18:18:59'),
(477, 188, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-24 18:19:14'),
(478, 188, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 18:19:16'),
(479, 188, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-24 18:19:18'),
(480, 188, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 18:19:20'),
(481, 188, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-24 18:19:25'),
(482, 189, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-24 18:21:22'),
(483, 190, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-24 18:34:30'),
(484, 190, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-24 18:38:57'),
(485, 190, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 18:39:07'),
(486, 190, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-24 18:39:10'),
(487, 191, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-24 18:39:25'),
(488, 191, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 18:39:33'),
(489, 191, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-24 18:39:34'),
(490, 191, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 18:39:38'),
(491, 191, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-24 18:39:40'),
(492, 188, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 18:39:58'),
(493, 192, 5, 'YeYe coffee', 5.00, 3, 15.00, '2026-10-24 18:43:45'),
(494, 193, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-24 18:43:55'),
(495, 193, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 18:44:01'),
(496, 193, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 18:44:03'),
(497, 194, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-24 18:44:14'),
(498, 194, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 18:44:18'),
(499, 194, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 18:44:20'),
(500, 194, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 18:44:23'),
(501, 195, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 18:44:33'),
(502, 193, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 18:54:34'),
(503, 196, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 18:59:05'),
(504, 197, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 19:00:17'),
(505, 197, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 19:00:20'),
(506, 198, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-24 19:06:21'),
(507, 198, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-24 19:06:26'),
(508, 199, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 19:12:58'),
(509, 199, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 19:12:59'),
(510, 199, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 19:13:03'),
(511, 199, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-24 19:13:05'),
(513, 200, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-24 19:20:31'),
(514, 200, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 19:20:35'),
(515, 200, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 19:20:37'),
(516, 201, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-24 19:23:56'),
(517, 195, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 19:27:08'),
(518, 200, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 19:34:09'),
(519, 199, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-24 19:34:24'),
(520, 202, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-24 19:54:55'),
(521, 202, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-24 19:55:00'),
(522, 202, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 19:55:02'),
(524, 203, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-24 20:52:33'),
(525, 204, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-24 21:40:28'),
(526, 204, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-24 21:40:33'),
(527, 204, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-24 21:40:35'),
(528, 205, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-25 00:29:23'),
(529, 205, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 00:29:27'),
(530, 205, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 00:29:30'),
(531, 206, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-25 07:22:31'),
(532, 206, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 07:22:36'),
(533, 207, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-25 07:24:03'),
(534, 208, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 07:29:21'),
(535, 208, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-25 07:29:25'),
(536, 208, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-25 07:29:27'),
(537, 209, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 07:39:17'),
(538, 206, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 07:51:31'),
(539, 206, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-25 07:51:35'),
(541, 210, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-25 07:51:47'),
(542, 211, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 08:52:51'),
(543, 212, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 09:17:06'),
(544, 213, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-25 10:13:46'),
(546, 213, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-25 10:13:56'),
(547, 214, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-25 10:17:49'),
(548, 214, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 10:17:54'),
(549, 214, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 10:34:50'),
(550, 215, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 11:24:55'),
(551, 215, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-25 11:25:15'),
(552, 215, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-25 11:25:18'),
(553, 215, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 11:25:23'),
(554, 216, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 11:27:56'),
(555, 216, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 11:27:59'),
(556, 216, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 12:02:06'),
(557, 217, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 12:07:36'),
(558, 217, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 12:11:37'),
(559, 218, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 12:31:05'),
(560, 218, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 12:31:07'),
(561, 219, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 12:33:51'),
(562, 219, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-25 12:33:54'),
(563, 219, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 12:33:56'),
(564, 218, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 12:43:57'),
(565, 219, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 12:45:24'),
(566, 220, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 12:54:13'),
(567, 220, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 12:54:17'),
(568, 220, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-25 12:54:18'),
(569, 221, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 13:02:21'),
(570, 220, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-25 13:06:40'),
(571, 222, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 13:19:30'),
(572, 222, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-25 13:19:35'),
(573, 222, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-10-25 13:49:45'),
(574, 222, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-25 14:15:02'),
(575, 223, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-25 14:32:23'),
(576, 223, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-25 14:32:26'),
(577, 224, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 14:32:36'),
(578, 224, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-25 14:32:38'),
(579, 224, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 14:32:41'),
(580, 223, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-25 14:34:43'),
(582, 225, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 14:35:00'),
(583, 226, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-25 14:59:52'),
(584, 226, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 14:59:53'),
(585, 226, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 14:59:55'),
(586, 223, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 15:00:07'),
(587, 227, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-25 15:07:43'),
(588, 227, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 15:07:46'),
(589, 228, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 15:08:25'),
(590, 229, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-25 15:35:26'),
(591, 230, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 15:51:23'),
(592, 230, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-25 15:51:27'),
(593, 230, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 15:51:31'),
(594, 231, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-25 15:51:41'),
(595, 222, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-25 15:51:52'),
(596, 222, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 15:51:57'),
(598, 233, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 16:17:20'),
(599, 233, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 16:17:23'),
(600, 233, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-25 16:17:27'),
(601, 233, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 16:17:32'),
(602, 233, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 16:17:34'),
(604, 234, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-25 16:20:02'),
(605, 234, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 16:20:16'),
(607, 234, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 16:20:25'),
(608, 234, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 16:20:26'),
(609, 235, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-25 16:22:48'),
(610, 235, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 16:22:49'),
(611, 236, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-25 16:38:12'),
(612, 236, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-25 16:38:22'),
(613, 236, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 16:38:26'),
(614, 237, 6, 'Hedhika', 5.00, 13, 65.00, '2026-10-25 16:39:22'),
(615, 237, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-25 16:43:15'),
(616, 237, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-25 16:43:19'),
(617, 237, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 16:50:23'),
(618, 237, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-25 16:50:32'),
(619, 238, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-25 17:29:11'),
(620, 238, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-25 17:29:17'),
(621, 238, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 17:29:23'),
(622, 238, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 17:29:24'),
(623, 239, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-25 17:36:09'),
(624, 239, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 17:36:28'),
(625, 239, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 17:36:30'),
(626, 239, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 17:36:32'),
(627, 240, 6, 'Hedhika', 5.00, 10, 50.00, '2026-10-25 17:36:45'),
(628, 240, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-25 17:36:57'),
(629, 240, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 17:37:01'),
(630, 240, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 17:37:04'),
(631, 241, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-25 18:09:14'),
(632, 241, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:09:17'),
(633, 242, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 18:21:28'),
(634, 242, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 18:21:29'),
(635, 242, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:21:31'),
(636, 242, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 18:21:34'),
(637, 243, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-25 18:27:25'),
(638, 243, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 18:27:43'),
(639, 243, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 18:27:48'),
(640, 243, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-25 18:27:50'),
(641, 243, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:28:13'),
(642, 244, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-25 18:28:28'),
(643, 244, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-25 18:28:38'),
(644, 244, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-25 18:28:39'),
(645, 244, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:28:41'),
(646, 244, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-25 18:28:45'),
(647, 243, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 18:33:38'),
(650, 245, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-25 18:42:20'),
(652, 246, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-25 18:43:23'),
(653, 246, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 18:43:41'),
(654, 246, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 18:43:44'),
(655, 246, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:43:45'),
(656, 246, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-25 18:43:49'),
(657, 247, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-25 18:58:29'),
(658, 247, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-25 18:58:32'),
(659, 247, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-25 18:58:33'),
(660, 245, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-25 18:59:16'),
(661, 245, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 18:59:27'),
(662, 245, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-25 18:59:30'),
(663, 248, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-25 19:02:53'),
(664, 248, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-25 19:02:56'),
(666, 88, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-25 19:19:44'),
(667, 88, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 19:19:50'),
(668, 88, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 19:19:52'),
(669, 88, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 19:19:56'),
(670, 249, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 19:37:09'),
(671, 249, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 19:37:11'),
(672, 250, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 19:39:51'),
(673, 251, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 20:37:35'),
(674, 251, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 20:37:38'),
(675, 251, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-25 20:37:43'),
(676, 251, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-25 20:37:51'),
(677, 252, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-25 20:45:20'),
(678, 252, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 20:45:29'),
(680, 253, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 20:51:35'),
(681, 253, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 20:51:43'),
(682, 254, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 21:37:57'),
(683, 255, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-25 21:50:39'),
(684, 256, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 21:50:57'),
(685, 256, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 22:01:31'),
(686, 256, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 22:01:33'),
(687, 257, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 22:02:52'),
(688, 257, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 22:02:55'),
(689, 257, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-25 22:02:56'),
(690, 256, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 22:20:04'),
(691, 258, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-25 22:28:52'),
(692, 258, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 22:28:54'),
(693, 259, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-25 22:39:02'),
(694, 260, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-25 22:39:55'),
(695, 260, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 22:40:01'),
(696, 260, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-25 22:40:10'),
(697, 260, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 22:40:29'),
(698, 261, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-25 22:48:35'),
(699, 261, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 22:48:39'),
(700, 262, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-25 23:03:11'),
(701, 262, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 23:03:14'),
(702, 263, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-25 23:52:01'),
(703, 263, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-25 23:52:29'),
(704, 263, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-25 23:52:32'),
(705, 264, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-26 07:06:02'),
(706, 264, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 07:06:04'),
(707, 264, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 07:06:07'),
(708, 264, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 07:06:10'),
(709, 265, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-26 07:10:44'),
(710, 266, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-26 07:41:52'),
(711, 266, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-26 07:41:55'),
(712, 266, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 07:42:00'),
(713, 266, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 07:42:07'),
(714, 266, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 07:42:11'),
(715, 267, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 08:18:18'),
(716, 267, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 08:18:25'),
(717, 268, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 08:18:34'),
(718, 268, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-26 08:18:36'),
(719, 269, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 08:34:50'),
(720, 269, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 08:34:52'),
(721, 270, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 09:01:31'),
(723, 271, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 09:01:43'),
(724, 269, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 09:40:54'),
(725, 272, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 09:48:28'),
(726, 272, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 09:48:30'),
(727, 272, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 09:48:32'),
(728, 272, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 09:55:10'),
(729, 273, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 10:33:33'),
(730, 273, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 10:33:35'),
(731, 273, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 10:33:37'),
(732, 274, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-26 10:33:42'),
(733, 274, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 10:33:44'),
(734, 275, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 10:52:34'),
(735, 276, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 10:52:45'),
(736, 276, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 10:52:50'),
(737, 274, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 11:24:08'),
(738, 277, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 11:27:45'),
(739, 277, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 11:27:47'),
(740, 278, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 11:46:21'),
(741, 278, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 11:46:23'),
(742, 279, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-26 12:16:41'),
(743, 280, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 12:42:01'),
(744, 280, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-26 12:42:03'),
(745, 280, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 12:42:05'),
(746, 280, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-26 12:42:12'),
(747, 279, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-26 12:45:37'),
(748, 279, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 12:45:39'),
(749, 279, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 12:45:43'),
(750, 281, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 12:48:06'),
(751, 281, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 12:48:08'),
(752, 281, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 12:48:11'),
(753, 281, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-26 12:53:53'),
(754, 282, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 13:13:49'),
(755, 282, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 13:13:54'),
(756, 283, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 13:14:09'),
(757, 283, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 13:14:16'),
(758, 283, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 13:22:09'),
(759, 284, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 13:29:25'),
(760, 284, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 13:44:03'),
(761, 285, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-26 13:45:09'),
(762, 285, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 13:45:15'),
(763, 286, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-26 13:45:23'),
(764, 286, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 13:45:32'),
(765, 286, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 13:48:44'),
(767, 282, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 13:57:40'),
(768, 287, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 14:33:35'),
(769, 287, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-26 14:33:39'),
(770, 287, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 14:33:41'),
(771, 288, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-26 14:36:32'),
(772, 288, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-26 14:36:46'),
(773, 288, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-26 14:36:53'),
(774, 288, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-26 14:36:55'),
(775, 288, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 14:37:00'),
(776, 289, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-26 14:37:21'),
(777, 289, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 14:37:27'),
(778, 288, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 14:49:08'),
(779, 290, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 14:56:10'),
(780, 291, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 15:17:18'),
(781, 291, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 15:17:20'),
(782, 291, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 15:17:23'),
(783, 292, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 15:19:31'),
(784, 292, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 15:26:16'),
(785, 293, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 15:38:15'),
(786, 293, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 15:38:18'),
(787, 294, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-26 15:41:20'),
(788, 294, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-26 15:41:33'),
(789, 294, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 15:41:39'),
(790, 294, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 15:41:43'),
(791, 294, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-26 15:41:46'),
(792, 295, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 15:56:24'),
(793, 295, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-26 15:56:27'),
(794, 295, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 15:56:31'),
(795, 296, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-26 15:59:35'),
(796, 296, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 15:59:37'),
(797, 296, 6, 'Hedhika', 5.00, 18, 90.00, '2026-10-26 16:00:49'),
(798, 296, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 16:03:04'),
(799, 297, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-26 16:03:44'),
(800, 297, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 16:03:50'),
(801, 297, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-26 16:03:51'),
(802, 297, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 16:03:53'),
(803, 297, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-26 16:04:00'),
(804, 297, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-26 16:04:05'),
(806, 298, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-26 16:09:52'),
(807, 299, 6, 'Hedhika', 5.00, 11, 55.00, '2026-10-26 16:15:14'),
(808, 300, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-26 16:27:17'),
(809, 300, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 16:27:27'),
(810, 301, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 16:27:38'),
(811, 301, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-26 16:27:42'),
(812, 302, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-26 16:55:02'),
(813, 302, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 16:55:09'),
(814, 303, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-26 16:55:29'),
(815, 303, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 16:55:32'),
(816, 303, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-26 16:55:37'),
(817, 304, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-26 17:03:15'),
(818, 304, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 17:03:18'),
(819, 304, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 17:07:48'),
(820, 303, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 17:13:19'),
(821, 305, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 17:13:25'),
(822, 305, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 17:13:27'),
(823, 306, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-26 17:18:02'),
(824, 306, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 17:18:22'),
(825, 306, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-26 17:25:12');
INSERT INTO `sale_lines` (`id`, `sale_id`, `item_id`, `item_name`, `unit_price`, `qty`, `line_total`, `added_at`) VALUES
(826, 306, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 17:25:13'),
(827, 307, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-26 17:29:06'),
(828, 308, 6, 'Hedhika', 5.00, 14, 70.00, '2026-10-26 17:32:38'),
(829, 309, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-26 17:35:35'),
(830, 310, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 17:50:59'),
(832, 310, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 17:51:08'),
(833, 311, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 18:01:59'),
(834, 311, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-26 18:02:01'),
(836, 311, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 18:06:54'),
(837, 311, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-26 18:06:59'),
(838, 312, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 18:24:55'),
(839, 312, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-26 18:24:56'),
(840, 312, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 18:24:57'),
(841, 312, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 18:24:58'),
(842, 312, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-26 18:25:01'),
(843, 313, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 18:26:42'),
(844, 314, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-26 18:29:29'),
(845, 314, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-26 18:29:31'),
(846, 314, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-26 18:29:34'),
(847, 315, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 18:36:31'),
(848, 315, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 18:36:33'),
(849, 316, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 19:25:39'),
(850, 317, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 19:55:18'),
(851, 317, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 19:55:21'),
(852, 318, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 19:55:28'),
(853, 318, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-26 19:55:31'),
(854, 318, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-26 19:56:09'),
(855, 319, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-26 19:59:02'),
(856, 320, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-26 20:04:54'),
(858, 319, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 20:11:01'),
(859, 319, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-26 20:11:03'),
(860, 322, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-26 20:12:04'),
(861, 322, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-26 20:13:01'),
(862, 322, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-26 20:13:04'),
(863, 322, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-26 20:13:05'),
(864, 317, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 20:18:06'),
(865, 323, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-26 20:20:29'),
(866, 322, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-26 20:26:51'),
(869, 318, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-26 20:42:47'),
(870, 325, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 21:14:04'),
(871, 325, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 21:14:06'),
(872, 326, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-26 21:45:26'),
(873, 327, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-26 21:46:02'),
(874, 327, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 21:46:06'),
(875, 320, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-26 22:17:32'),
(879, 329, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-26 23:16:11'),
(880, 329, 8, 'Black Coffee Normal', 7.00, 6, 42.00, '2026-10-26 23:16:14'),
(881, 329, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-26 23:16:19'),
(883, 330, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-27 00:08:55'),
(884, 331, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-27 07:28:52'),
(885, 331, 16, 'Bisket', 5.00, 2, 10.00, '2026-10-27 07:28:57'),
(886, 331, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 07:42:01'),
(887, 331, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 07:42:29'),
(888, 332, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 08:12:32'),
(889, 332, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 08:12:34'),
(890, 310, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-27 08:19:01'),
(891, 310, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 08:19:19'),
(892, 310, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 08:19:23'),
(893, 333, 8, 'Black Coffee Normal', 7.00, 8, 56.00, '2026-10-27 09:18:14'),
(894, 333, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-27 09:18:21'),
(895, 333, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-27 09:18:23'),
(896, 334, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 10:07:49'),
(897, 334, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 10:07:51'),
(898, 335, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-27 10:17:17'),
(899, 336, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 10:35:51'),
(900, 337, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 11:25:49'),
(901, 337, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-27 11:25:53'),
(902, 338, 8, 'Black Coffee Normal', 7.00, 6, 42.00, '2026-10-27 11:35:24'),
(903, 338, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-27 11:35:29'),
(904, 337, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-27 11:36:09'),
(905, 337, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 11:46:40'),
(906, 337, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 11:46:43'),
(907, 338, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 11:47:06'),
(908, 338, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-27 11:47:09'),
(909, 338, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 11:47:27'),
(910, 338, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-27 11:47:49'),
(911, 339, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 11:53:17'),
(912, 339, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 11:53:19'),
(913, 339, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 11:53:21'),
(914, 340, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 12:23:25'),
(915, 340, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 12:23:28'),
(916, 339, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-27 12:32:15'),
(918, 342, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 12:42:36'),
(919, 342, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 12:42:40'),
(920, 343, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 13:06:53'),
(921, 344, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-27 13:08:59'),
(922, 344, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-27 13:09:03'),
(923, 345, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-27 13:11:22'),
(924, 346, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 13:16:41'),
(925, 342, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 13:22:13'),
(926, 347, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 14:04:15'),
(927, 347, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 14:04:17'),
(928, 347, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-27 14:04:21'),
(929, 347, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-27 14:04:29'),
(930, 348, 8, 'Black Coffee Normal', 7.00, 8, 56.00, '2026-10-27 14:55:47'),
(931, 348, 15, 'Nuts Full', 10.00, 2, 20.00, '2026-10-27 14:55:57'),
(932, 348, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-27 14:56:00'),
(933, 349, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 14:56:09'),
(934, 349, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 14:56:12'),
(935, 349, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 14:56:15'),
(936, 350, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 15:37:36'),
(937, 350, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 15:37:38'),
(938, 351, 6, 'Hedhika', 5.00, 14, 70.00, '2026-10-27 15:38:43'),
(939, 352, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 15:39:02'),
(940, 353, 6, 'Hedhika', 5.00, 24, 120.00, '2026-10-27 15:47:57'),
(941, 353, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-27 15:48:17'),
(942, 353, 10, 'Black Tea Large', 5.00, 5, 25.00, '2026-10-27 15:49:21'),
(943, 353, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-27 15:49:28'),
(944, 353, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-27 15:49:32'),
(945, 353, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-27 15:50:50'),
(946, 354, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 15:50:55'),
(947, 354, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 15:50:59'),
(948, 355, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-27 15:58:17'),
(949, 356, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-27 15:58:19'),
(951, 357, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-27 16:04:02'),
(952, 357, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 16:04:04'),
(953, 353, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 16:06:21'),
(954, 358, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-27 16:07:22'),
(955, 358, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-27 16:07:33'),
(956, 358, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 16:07:40'),
(957, 358, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 16:07:42'),
(958, 359, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-27 16:13:00'),
(959, 359, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-27 16:13:02'),
(960, 356, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 16:15:38'),
(961, 356, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 16:15:43'),
(962, 356, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-27 16:16:41'),
(963, 360, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-27 16:27:15'),
(964, 361, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-27 16:32:28'),
(965, 360, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-27 16:34:26'),
(966, 360, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-27 16:34:27'),
(967, 360, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 16:34:28'),
(968, 360, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 16:46:39'),
(969, 362, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-27 16:47:32'),
(970, 362, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-27 16:47:40'),
(972, 364, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 16:50:53'),
(973, 364, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 16:50:56'),
(974, 364, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 16:50:58'),
(975, 365, 6, 'Hedhika', 5.00, 10, 50.00, '2026-10-27 17:06:50'),
(976, 366, 6, 'Hedhika', 5.00, 12, 60.00, '2026-10-27 17:12:11'),
(977, 367, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 17:24:25'),
(978, 367, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 17:24:27'),
(979, 368, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 17:33:00'),
(980, 368, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 17:33:01'),
(981, 368, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 17:33:03'),
(983, 369, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-27 17:40:22'),
(984, 369, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 17:40:34'),
(985, 370, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-27 17:48:32'),
(986, 370, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-27 17:48:37'),
(987, 370, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 17:48:40'),
(988, 371, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-27 17:59:01'),
(991, 372, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-27 18:00:55'),
(992, 372, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-27 18:01:02'),
(993, 372, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 18:01:18'),
(995, 369, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 18:14:11'),
(996, 374, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-27 18:28:02'),
(997, 375, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-27 18:30:59'),
(998, 375, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-27 18:31:03'),
(999, 375, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 18:31:08'),
(1000, 375, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 18:31:13'),
(1001, 376, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 18:34:41'),
(1002, 376, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 18:34:43'),
(1003, 376, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 18:34:45'),
(1004, 377, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 18:37:46'),
(1005, 377, 2, 'Milk Coffee', 10.00, 3, 30.00, '2026-10-27 18:37:55'),
(1006, 377, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 18:37:57'),
(1007, 375, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 18:43:58'),
(1009, 378, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-27 18:53:23'),
(1010, 379, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 19:02:45'),
(1011, 379, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-27 19:02:47'),
(1012, 379, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 19:02:49'),
(1013, 380, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 19:30:56'),
(1014, 381, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-27 19:35:42'),
(1015, 381, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-27 19:35:45'),
(1016, 381, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 19:35:46'),
(1018, 382, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-27 19:46:21'),
(1019, 382, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-27 19:46:23'),
(1020, 382, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 19:46:28'),
(1021, 383, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-27 19:47:54'),
(1022, 383, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 19:47:56'),
(1023, 383, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 19:48:00'),
(1024, 383, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 19:48:02'),
(1025, 382, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 19:53:26'),
(1026, 383, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 20:05:58'),
(1027, 384, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 20:12:55'),
(1028, 385, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-27 20:26:08'),
(1029, 386, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-27 20:32:56'),
(1030, 386, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 20:33:07'),
(1031, 386, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 20:33:17'),
(1032, 387, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 20:34:13'),
(1033, 388, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 22:06:11'),
(1034, 388, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 22:06:12'),
(1035, 389, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 22:09:07'),
(1036, 390, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-27 22:09:17'),
(1037, 391, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-27 22:23:32'),
(1038, 391, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 22:23:34'),
(1039, 392, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-27 22:23:40'),
(1040, 393, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 22:49:01'),
(1041, 393, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-27 22:49:04'),
(1042, 394, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-27 23:23:19'),
(1043, 394, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-27 23:26:30'),
(1044, 394, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-27 23:26:33'),
(1045, 394, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-27 23:26:41'),
(1046, 394, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-27 23:30:19'),
(1047, 395, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-10-28 07:54:13'),
(1048, 396, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 08:06:08'),
(1049, 396, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 08:06:12'),
(1050, 396, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-28 08:06:14'),
(1051, 397, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 08:35:22'),
(1052, 397, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 08:35:24'),
(1053, 398, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-28 08:35:29'),
(1054, 398, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 08:35:32'),
(1055, 398, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-28 08:35:34'),
(1056, 399, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-28 08:35:45'),
(1057, 399, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 08:35:48'),
(1058, 399, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 08:35:51'),
(1059, 400, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 08:42:12'),
(1060, 400, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-28 08:42:14'),
(1061, 400, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 08:42:16'),
(1063, 400, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-28 08:42:26'),
(1064, 400, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:00:18'),
(1065, 401, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 09:01:18'),
(1066, 401, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 09:01:19'),
(1067, 401, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 09:01:23'),
(1068, 402, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 09:01:32'),
(1069, 402, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 09:01:35'),
(1070, 398, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-28 09:06:06'),
(1071, 402, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:06:19'),
(1072, 403, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 09:34:14'),
(1073, 403, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 09:34:18'),
(1074, 404, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 09:34:25'),
(1075, 404, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:34:27'),
(1076, 398, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:34:43'),
(1077, 403, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:58:45'),
(1078, 405, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 09:58:54'),
(1079, 405, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 09:58:58'),
(1080, 405, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:58:59'),
(1081, 406, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 09:59:11'),
(1082, 406, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-28 09:59:12'),
(1083, 406, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 09:59:16'),
(1084, 406, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 09:59:18'),
(1085, 403, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-28 10:29:29'),
(1086, 403, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-28 10:29:31'),
(1087, 407, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 11:51:35'),
(1088, 407, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 11:51:40'),
(1089, 407, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 11:51:41'),
(1090, 408, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 11:52:22'),
(1091, 408, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 11:52:24'),
(1092, 408, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 12:07:00'),
(1094, 409, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 12:39:46'),
(1095, 409, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 12:39:50'),
(1096, 409, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 12:39:51'),
(1097, 410, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 12:40:09'),
(1098, 410, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 12:40:10'),
(1099, 410, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 12:40:11'),
(1100, 411, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 12:40:17'),
(1101, 411, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-28 12:40:20'),
(1102, 411, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 12:40:34'),
(1103, 412, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-28 12:40:41'),
(1104, 412, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 12:40:43'),
(1105, 412, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-28 12:40:45'),
(1106, 412, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 12:40:48'),
(1107, 412, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-28 12:46:12'),
(1108, 412, 15, 'Nuts Full', 10.00, 2, 20.00, '2026-10-28 12:46:14'),
(1109, 413, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 12:47:36'),
(1110, 413, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 12:47:37'),
(1112, 410, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 12:53:20'),
(1113, 415, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 14:36:49'),
(1114, 415, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-28 14:36:52'),
(1115, 415, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 14:36:56'),
(1116, 415, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 14:36:59'),
(1117, 416, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 14:37:10'),
(1118, 416, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 14:37:12'),
(1119, 416, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 14:37:16'),
(1120, 417, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 14:37:26'),
(1121, 417, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-28 14:37:31'),
(1122, 417, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 14:37:34'),
(1123, 418, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 14:44:20'),
(1124, 418, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 14:44:21'),
(1125, 419, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 15:07:19'),
(1126, 419, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 15:07:25'),
(1127, 419, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 15:07:27'),
(1128, 420, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 15:09:09'),
(1129, 420, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 15:09:10'),
(1130, 420, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 15:09:17'),
(1131, 420, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 15:09:23'),
(1132, 421, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 15:29:52'),
(1133, 421, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-28 15:30:05'),
(1134, 421, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 15:30:13'),
(1135, 421, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 15:30:17'),
(1136, 422, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-28 15:33:12'),
(1137, 422, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-28 15:33:32'),
(1138, 423, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-28 15:43:43'),
(1139, 423, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-28 15:43:49'),
(1140, 423, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 15:43:51'),
(1141, 424, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-28 15:45:11'),
(1142, 425, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-28 15:47:09'),
(1143, 425, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-28 15:47:29'),
(1144, 425, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 15:47:31'),
(1145, 425, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-28 15:47:34'),
(1146, 423, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 15:52:33'),
(1147, 426, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-28 16:09:10'),
(1148, 426, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-28 16:09:14'),
(1149, 426, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-28 16:09:17'),
(1150, 427, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-28 16:23:33'),
(1151, 427, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-28 16:23:44'),
(1152, 427, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 16:23:46'),
(1153, 427, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-28 16:23:47'),
(1154, 428, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-28 16:32:02'),
(1155, 427, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 16:39:34'),
(1156, 429, 16, 'Bisket', 5.00, 2, 10.00, '2026-10-28 16:43:07'),
(1157, 429, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-28 16:43:41'),
(1158, 429, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 16:43:45'),
(1159, 430, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-28 16:55:59'),
(1160, 430, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-28 16:56:02'),
(1161, 430, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 16:57:15'),
(1162, 430, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-28 16:57:23'),
(1163, 430, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 17:03:14'),
(1164, 431, 6, 'Hedhika', 5.00, 11, 55.00, '2026-10-28 17:18:59'),
(1165, 431, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 17:19:17'),
(1166, 431, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-28 17:21:06'),
(1167, 432, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-28 17:22:34'),
(1168, 432, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-28 17:23:25'),
(1169, 433, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 17:43:19'),
(1170, 433, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 17:44:09'),
(1171, 434, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 18:00:24'),
(1172, 435, 6, 'Hedhika', 5.00, 20, 100.00, '2026-10-28 18:20:41'),
(1173, 436, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-28 18:31:15'),
(1174, 436, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 18:31:28'),
(1175, 436, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-28 18:31:30'),
(1176, 437, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-28 18:31:39'),
(1177, 437, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 18:31:41'),
(1178, 437, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-28 18:31:43'),
(1179, 437, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-28 18:31:47'),
(1180, 438, 7, 'Black Coffee Hard', 10.00, 6, 60.00, '2026-10-28 18:36:56'),
(1181, 438, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-28 18:37:01'),
(1182, 437, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 18:40:08'),
(1183, 436, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 18:40:13'),
(1184, 439, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-28 19:02:05'),
(1185, 439, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-28 19:02:09'),
(1186, 438, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 19:06:48'),
(1187, 440, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-28 19:13:16'),
(1188, 441, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 19:13:39'),
(1189, 442, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 19:15:13'),
(1190, 442, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-28 19:15:14'),
(1191, 443, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-28 19:15:33'),
(1192, 444, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-28 20:02:16'),
(1193, 444, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 20:02:23'),
(1194, 444, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-28 20:02:30'),
(1195, 445, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-28 20:03:11'),
(1196, 445, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 20:03:20'),
(1197, 445, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 20:03:25'),
(1198, 445, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-28 20:03:52'),
(1199, 446, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-28 20:06:20'),
(1200, 446, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-28 20:06:24'),
(1201, 446, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-28 20:06:28'),
(1202, 446, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 20:06:29'),
(1203, 447, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-28 20:19:14'),
(1204, 447, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 20:19:17'),
(1205, 447, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 20:26:50'),
(1206, 448, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-28 20:37:00'),
(1207, 448, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-28 20:37:04'),
(1208, 449, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-28 21:08:11'),
(1209, 449, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 21:08:21'),
(1210, 449, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 21:08:25'),
(1211, 449, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-28 21:08:26'),
(1212, 450, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-28 21:08:40'),
(1213, 443, 5, 'YeYe coffee', 5.00, 3, 15.00, '2026-10-28 21:13:14'),
(1214, 451, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 21:32:02'),
(1215, 451, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 21:32:04'),
(1216, 452, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-10-28 21:50:47'),
(1217, 452, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 21:50:51'),
(1218, 452, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 21:50:53'),
(1219, 453, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 22:21:28'),
(1220, 454, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-28 23:02:59'),
(1221, 455, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-28 23:21:04'),
(1222, 455, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-28 23:21:07'),
(1223, 454, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-28 23:31:54'),
(1224, 455, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 23:34:25'),
(1225, 454, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-28 23:35:38'),
(1226, 456, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-29 06:55:52'),
(1227, 456, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 06:55:54'),
(1228, 457, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 08:56:17'),
(1229, 457, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 08:56:23'),
(1230, 457, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-29 08:56:27'),
(1231, 457, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 09:14:48'),
(1232, 458, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 09:33:58'),
(1233, 459, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 09:35:41'),
(1234, 459, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 09:35:48'),
(1235, 459, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 09:35:57'),
(1236, 460, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-10-29 09:53:28'),
(1237, 460, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 09:53:31'),
(1238, 460, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 09:53:33'),
(1239, 461, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-29 10:42:41'),
(1240, 461, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 10:42:44'),
(1241, 461, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 10:42:45'),
(1242, 461, 15, 'Nuts Full', 10.00, 2, 20.00, '2026-10-29 10:42:48'),
(1243, 462, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 10:42:58'),
(1244, 463, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-29 10:45:54'),
(1245, 461, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-29 11:26:38'),
(1246, 464, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-29 11:29:30'),
(1247, 464, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 11:29:32'),
(1248, 464, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-29 11:29:38'),
(1249, 464, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 11:29:43'),
(1250, 465, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 11:30:03'),
(1251, 465, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 11:30:08'),
(1252, 465, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 11:30:13'),
(1253, 466, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 11:30:20'),
(1254, 466, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 11:30:25'),
(1255, 467, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 11:43:39'),
(1256, 467, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 11:43:41'),
(1257, 467, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 11:43:43'),
(1258, 466, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-29 11:44:02'),
(1259, 466, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 11:44:07'),
(1260, 468, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 11:44:19'),
(1261, 468, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-29 11:44:21'),
(1262, 468, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 11:54:35'),
(1263, 469, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-29 12:46:51'),
(1264, 469, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-29 12:46:58'),
(1265, 469, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-29 12:47:03'),
(1266, 469, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 12:47:05'),
(1267, 469, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 12:47:09'),
(1268, 470, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 12:49:13'),
(1269, 470, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 12:49:14'),
(1270, 471, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-29 12:56:23'),
(1271, 472, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 13:21:24'),
(1272, 473, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-29 13:43:57'),
(1273, 473, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 13:44:03'),
(1274, 473, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 13:44:06'),
(1275, 474, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-29 13:44:19'),
(1277, 474, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 13:44:30'),
(1278, 475, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 13:44:44'),
(1279, 475, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 13:44:50'),
(1280, 475, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-29 13:55:23'),
(1281, 476, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-29 14:04:44'),
(1282, 477, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-29 14:11:13'),
(1283, 477, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 14:11:14'),
(1284, 477, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-29 14:11:17'),
(1286, 478, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-29 14:11:31'),
(1287, 478, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 14:11:36'),
(1288, 477, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-29 14:26:06'),
(1289, 479, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-29 14:35:39'),
(1290, 479, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 14:35:45'),
(1291, 479, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 14:35:48'),
(1292, 480, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 14:48:55'),
(1293, 480, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 14:48:57'),
(1294, 480, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-29 14:48:58'),
(1295, 481, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-29 14:54:07'),
(1296, 481, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-29 14:54:09'),
(1297, 481, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 14:54:12'),
(1298, 481, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 15:01:22'),
(1299, 482, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-29 15:23:01'),
(1300, 482, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-29 15:23:09'),
(1301, 482, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 15:23:15'),
(1304, 484, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-29 15:24:09'),
(1305, 484, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 15:24:11'),
(1307, 484, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 15:34:19'),
(1308, 482, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 15:34:52'),
(1309, 480, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-29 15:35:07'),
(1310, 484, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 15:38:53'),
(1311, 486, 6, 'Hedhika', 5.00, 11, 55.00, '2026-10-29 15:41:15'),
(1312, 486, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-29 15:43:21'),
(1313, 486, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-29 15:43:23'),
(1314, 486, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 15:43:34'),
(1315, 486, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-29 15:53:45'),
(1316, 486, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 15:53:46'),
(1317, 487, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 16:01:07'),
(1320, 488, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 16:18:12'),
(1321, 489, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-29 16:20:01'),
(1322, 489, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 16:20:02'),
(1323, 490, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-29 16:24:31'),
(1324, 490, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-29 16:24:35'),
(1325, 490, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 16:24:37'),
(1326, 490, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 16:24:39'),
(1327, 490, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 16:24:42'),
(1328, 491, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 16:32:28'),
(1329, 491, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 16:32:31'),
(1330, 491, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 16:32:33'),
(1331, 492, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-29 16:44:32'),
(1332, 492, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 16:44:33'),
(1334, 492, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-29 16:45:45'),
(1335, 493, 6, 'Hedhika', 5.00, 10, 50.00, '2026-10-29 16:46:48'),
(1336, 493, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-29 16:46:59'),
(1337, 493, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 16:47:00'),
(1338, 493, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 16:47:01'),
(1339, 493, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 16:52:35'),
(1340, 494, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-29 16:55:56'),
(1341, 495, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-29 16:56:56'),
(1342, 495, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 16:57:05'),
(1343, 495, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 16:57:06'),
(1344, 496, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 17:02:13'),
(1345, 496, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 17:02:18'),
(1346, 496, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 17:02:20'),
(1347, 496, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 17:02:25'),
(1348, 497, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 17:02:34'),
(1349, 498, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-29 17:10:16'),
(1350, 498, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 17:10:23'),
(1351, 498, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-29 17:10:25'),
(1352, 499, 6, 'Hedhika', 5.00, 14, 70.00, '2026-10-29 17:21:09'),
(1353, 500, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-29 17:27:01'),
(1354, 500, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-29 17:27:19'),
(1355, 501, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 17:32:33'),
(1356, 501, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-29 17:32:41'),
(1357, 501, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 17:33:27'),
(1358, 502, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-29 17:38:15'),
(1359, 502, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 17:38:22'),
(1360, 502, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 17:38:22'),
(1361, 502, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 17:38:25'),
(1362, 503, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 17:51:47'),
(1363, 503, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-29 17:51:52'),
(1365, 505, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-29 18:15:27'),
(1366, 505, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 18:15:31'),
(1367, 505, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-29 18:15:33'),
(1368, 506, 6, 'Hedhika', 5.00, 6, 30.00, '2026-10-29 18:41:22'),
(1369, 506, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 18:41:28'),
(1370, 506, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-29 18:42:17'),
(1371, 506, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 18:42:20'),
(1372, 507, 2, 'Milk Coffee', 10.00, 3, 30.00, '2026-10-29 18:48:23'),
(1373, 508, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 19:13:52'),
(1374, 508, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 19:13:56'),
(1375, 508, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-29 19:14:19'),
(1376, 509, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 20:17:12'),
(1377, 509, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-29 20:17:21'),
(1378, 507, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-29 20:17:31'),
(1379, 510, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 20:17:57'),
(1380, 509, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 20:29:11'),
(1381, 511, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-29 20:29:20'),
(1382, 511, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-29 20:29:27'),
(1383, 512, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-29 20:53:52'),
(1384, 512, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-29 20:54:04'),
(1385, 512, 8, 'Black Coffee Normal', 7.00, 8, 56.00, '2026-10-29 20:54:06'),
(1386, 513, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-29 21:06:07'),
(1387, 514, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 21:11:09'),
(1388, 513, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 21:13:26'),
(1389, 515, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-29 23:06:52'),
(1390, 515, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 23:06:56'),
(1391, 515, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 23:06:58'),
(1392, 516, 6, 'Hedhika', 5.00, 2, 10.00, '2026-10-29 23:07:25'),
(1393, 516, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 23:07:30'),
(1394, 516, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-29 23:07:31'),
(1395, 517, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-29 23:54:29'),
(1396, 517, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-29 23:54:43'),
(1397, 518, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-10-29 23:55:03'),
(1398, 518, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 23:55:08'),
(1399, 518, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 23:55:10'),
(1400, 517, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-29 23:55:46'),
(1401, 519, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-29 23:56:38'),
(1402, 518, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 00:06:24'),
(1403, 518, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 00:22:49'),
(1405, 520, 7, 'Black Coffee Hard', 10.00, 6, 60.00, '2026-10-30 00:32:34'),
(1406, 520, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-30 00:32:42'),
(1407, 521, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-30 00:35:06'),
(1408, 522, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-30 14:33:03'),
(1409, 522, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 14:33:06'),
(1410, 522, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 14:33:08'),
(1411, 523, 7, 'Black Coffee Hard', 10.00, 6, 60.00, '2026-10-30 14:33:18'),
(1412, 523, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-30 14:33:22'),
(1413, 524, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-30 14:35:46'),
(1414, 524, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 14:35:49'),
(1415, 523, 15, 'Nuts Full', 10.00, 2, 20.00, '2026-10-30 14:37:59'),
(1416, 525, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 14:49:56'),
(1417, 525, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-30 14:49:58'),
(1418, 525, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 14:50:01'),
(1419, 524, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 14:52:18'),
(1420, 526, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-30 14:57:01'),
(1421, 526, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 14:57:04'),
(1422, 526, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 14:57:06'),
(1424, 523, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 15:10:37'),
(1425, 528, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 15:15:25'),
(1426, 528, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 15:16:16'),
(1427, 529, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-30 15:19:12'),
(1428, 525, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 15:19:26'),
(1429, 529, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 15:48:38'),
(1430, 529, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-30 15:48:41'),
(1431, 529, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 15:48:44'),
(1432, 530, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-30 15:48:53'),
(1433, 530, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 15:48:57'),
(1434, 530, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 16:00:59'),
(1435, 531, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 16:27:52'),
(1436, 531, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 16:27:53'),
(1437, 531, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 16:27:55'),
(1438, 532, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-30 16:28:06'),
(1439, 532, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-30 16:28:07'),
(1440, 532, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 16:28:09'),
(1441, 533, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-30 16:44:17'),
(1442, 533, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-10-30 16:44:29'),
(1443, 533, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-30 16:44:36'),
(1444, 534, 5, 'YeYe coffee', 5.00, 3, 15.00, '2026-10-30 17:09:36'),
(1445, 535, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 17:58:42'),
(1446, 535, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 17:58:46'),
(1447, 536, 12, 'Water Bottle 500 ML', 5.00, 4, 20.00, '2026-10-30 18:22:36'),
(1448, 537, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-30 18:25:43'),
(1449, 537, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 18:25:45'),
(1450, 537, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 18:25:47'),
(1451, 538, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 18:51:20'),
(1452, 538, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-10-30 18:51:22'),
(1453, 538, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-30 18:51:24'),
(1454, 538, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 18:51:30'),
(1455, 538, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-30 18:56:51'),
(1456, 539, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 19:18:07'),
(1457, 539, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-30 19:18:10'),
(1458, 539, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 19:18:12'),
(1459, 539, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 19:30:28'),
(1460, 540, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-10-30 19:31:50'),
(1461, 540, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 19:31:53'),
(1462, 540, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 19:31:57'),
(1463, 540, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-30 19:32:07'),
(1464, 541, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-30 19:39:17'),
(1465, 541, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-30 19:39:20'),
(1466, 540, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 19:39:35'),
(1467, 541, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-30 19:45:32'),
(1468, 542, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 20:04:47'),
(1469, 542, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 20:04:48'),
(1470, 543, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-30 20:04:57'),
(1471, 543, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 20:11:14'),
(1472, 544, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-30 21:34:51'),
(1473, 544, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-30 21:34:56'),
(1474, 544, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-30 21:37:34'),
(1475, 545, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-30 22:07:52'),
(1476, 546, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-10-30 22:08:15'),
(1477, 546, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 22:08:21'),
(1478, 547, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-30 22:31:45'),
(1479, 548, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-30 23:06:39'),
(1480, 549, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 08:28:49'),
(1481, 549, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 08:28:54'),
(1482, 549, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 08:29:00'),
(1483, 550, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 09:02:34'),
(1484, 550, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-31 09:02:35'),
(1485, 550, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 09:02:38'),
(1486, 550, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 09:02:46'),
(1487, 551, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 10:42:11'),
(1488, 552, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 10:55:31'),
(1489, 552, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 10:55:34'),
(1490, 553, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-31 10:55:39'),
(1491, 553, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 10:55:45'),
(1492, 554, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-31 11:07:24'),
(1493, 554, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-31 11:07:28'),
(1494, 554, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 11:07:31'),
(1495, 554, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 11:07:34'),
(1496, 553, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 11:07:42'),
(1497, 555, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 11:23:06'),
(1498, 555, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 11:23:09'),
(1499, 555, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 11:23:12'),
(1500, 556, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 11:43:17'),
(1501, 556, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 11:43:22'),
(1502, 556, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-31 11:47:26'),
(1503, 557, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-10-31 12:08:26'),
(1504, 557, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 12:08:30'),
(1505, 557, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 12:08:32'),
(1506, 558, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 13:04:39'),
(1507, 558, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-31 13:04:41'),
(1508, 558, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 13:04:45'),
(1509, 558, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 13:04:48'),
(1510, 559, 8, 'Black Coffee Normal', 7.00, 8, 56.00, '2026-10-31 13:04:58'),
(1511, 559, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 13:05:05'),
(1512, 559, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-10-31 13:05:08'),
(1513, 560, 7, 'Black Coffee Hard', 10.00, 8, 80.00, '2026-10-31 13:34:49'),
(1514, 560, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-10-31 13:34:54'),
(1515, 560, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 13:34:58'),
(1516, 561, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 13:35:05'),
(1517, 561, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 13:35:10'),
(1518, 561, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 13:35:14'),
(1519, 562, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 14:26:54'),
(1520, 562, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 14:26:57'),
(1521, 563, 9, 'Lemon Tea', 10.00, 2, 20.00, '2026-10-31 14:41:40'),
(1522, 563, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 14:41:47'),
(1523, 563, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 14:41:50'),
(1524, 563, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 14:41:55'),
(1525, 564, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 15:31:16'),
(1526, 564, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 15:31:17'),
(1527, 560, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 15:32:22'),
(1528, 565, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-31 15:45:51'),
(1529, 565, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 15:45:56'),
(1530, 566, 6, 'Hedhika', 5.00, 10, 50.00, '2026-10-31 15:46:03'),
(1531, 566, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-31 15:46:12'),
(1532, 566, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-31 15:46:14'),
(1533, 566, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 15:46:18'),
(1534, 567, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 16:00:11'),
(1535, 567, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 16:00:14'),
(1536, 566, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 16:02:05'),
(1537, 568, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-31 16:02:23'),
(1538, 568, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-31 16:02:40'),
(1539, 568, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 16:02:44'),
(1540, 569, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-31 16:02:52'),
(1541, 569, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 16:03:00'),
(1543, 569, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-31 16:03:05'),
(1544, 570, 6, 'Hedhika', 5.00, 9, 45.00, '2026-10-31 16:03:22'),
(1545, 570, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 16:03:34'),
(1546, 570, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 16:03:37'),
(1547, 570, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-10-31 16:03:39'),
(1548, 570, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 16:03:53'),
(1549, 569, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 16:04:52'),
(1550, 569, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 16:07:12'),
(1551, 560, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 16:15:53'),
(1552, 568, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 16:16:07'),
(1553, 571, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 16:55:35');
INSERT INTO `sale_lines` (`id`, `sale_id`, `item_id`, `item_name`, `unit_price`, `qty`, `line_total`, `added_at`) VALUES
(1554, 571, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 16:55:37'),
(1555, 571, 6, 'Hedhika', 5.00, 7, 35.00, '2026-10-31 16:55:39'),
(1556, 571, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 16:55:50'),
(1557, 572, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 17:11:37'),
(1558, 572, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-10-31 17:11:39'),
(1560, 572, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-31 17:21:15'),
(1561, 574, 6, 'Hedhika', 5.00, 8, 40.00, '2026-10-31 17:24:33'),
(1562, 574, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 17:24:43'),
(1563, 574, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-10-31 17:24:44'),
(1564, 574, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-31 17:24:49'),
(1565, 572, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 17:31:55'),
(1566, 574, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 17:36:07'),
(1567, 575, 6, 'Hedhika', 5.00, 26, 130.00, '2026-10-31 17:46:53'),
(1569, 575, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 17:47:01'),
(1570, 575, 15, 'Nuts Full', 10.00, 3, 30.00, '2026-10-31 18:11:18'),
(1571, 575, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-10-31 18:11:44'),
(1572, 575, 10, 'Black Tea Large', 5.00, 4, 20.00, '2026-10-31 18:11:46'),
(1573, 576, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-31 18:15:08'),
(1575, 576, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-10-31 18:15:36'),
(1576, 576, 8, 'Black Coffee Normal', 7.00, 7, 49.00, '2026-10-31 18:15:39'),
(1577, 574, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-31 18:19:05'),
(1578, 577, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-10-31 18:19:36'),
(1579, 578, 6, 'Hedhika', 5.00, 5, 25.00, '2026-10-31 18:27:22'),
(1580, 578, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 18:27:29'),
(1581, 578, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-10-31 18:27:34'),
(1582, 579, 8, 'Black Coffee Normal', 7.00, 7, 49.00, '2026-10-31 18:33:13'),
(1583, 579, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 18:33:17'),
(1584, 578, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 18:34:30'),
(1585, 573, 6, 'Hedhika', 5.00, 4, 20.00, '2026-10-31 18:36:51'),
(1586, 573, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 18:36:57'),
(1587, 578, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 18:51:32'),
(1588, 580, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-31 18:52:18'),
(1589, 580, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-10-31 18:53:49'),
(1590, 574, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-31 19:13:38'),
(1591, 574, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 19:13:52'),
(1592, 581, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-10-31 19:16:00'),
(1593, 581, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-10-31 19:16:01'),
(1594, 576, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-10-31 19:49:30'),
(1595, 576, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 19:49:34'),
(1596, 582, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 20:02:49'),
(1597, 583, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 20:39:21'),
(1598, 583, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 20:39:23'),
(1599, 583, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 20:39:26'),
(1600, 584, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 20:39:37'),
(1601, 584, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 20:39:39'),
(1602, 584, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-31 20:53:59'),
(1603, 585, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 20:57:10'),
(1604, 584, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 21:01:41'),
(1605, 586, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-10-31 21:21:05'),
(1606, 586, 16, 'Bisket', 5.00, 1, 5.00, '2026-10-31 21:21:08'),
(1607, 587, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 21:54:45'),
(1608, 587, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 21:54:48'),
(1609, 587, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-10-31 21:54:49'),
(1610, 588, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 22:08:48'),
(1611, 589, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 22:25:51'),
(1612, 589, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-10-31 22:25:53'),
(1613, 590, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-31 22:39:49'),
(1614, 590, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-10-31 22:39:50'),
(1615, 590, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 22:39:55'),
(1616, 590, 6, 'Hedhika', 5.00, 3, 15.00, '2026-10-31 22:40:14'),
(1617, 591, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 22:45:46'),
(1618, 591, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-10-31 22:45:49'),
(1619, 590, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-10-31 22:48:07'),
(1620, 591, 6, 'Hedhika', 5.00, 1, 5.00, '2026-10-31 23:05:07'),
(1621, 592, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-10-31 23:41:44'),
(1622, 592, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-10-31 23:41:47'),
(1623, 592, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-10-31 23:51:43'),
(1624, 593, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 00:11:29'),
(1625, 593, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 00:11:31'),
(1626, 594, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 00:32:24'),
(1627, 594, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 00:32:26'),
(1628, 595, 8, 'Black Coffee Normal', 7.00, 7, 49.00, '2026-11-01 07:22:10'),
(1629, 595, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 07:22:15'),
(1630, 595, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-11-01 07:22:19'),
(1631, 595, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 08:12:57'),
(1634, 597, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 08:39:51'),
(1635, 597, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 08:39:55'),
(1636, 598, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 08:40:02'),
(1637, 598, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 08:40:05'),
(1638, 598, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 08:40:07'),
(1639, 599, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 09:30:20'),
(1640, 599, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 09:30:23'),
(1641, 600, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-11-01 09:30:31'),
(1642, 600, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 09:30:32'),
(1643, 600, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 09:30:35'),
(1644, 601, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-01 09:30:43'),
(1645, 601, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 09:30:48'),
(1646, 602, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 09:30:54'),
(1647, 602, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 09:30:55'),
(1648, 602, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 09:30:59'),
(1649, 602, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 09:31:04'),
(1650, 599, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 09:37:12'),
(1651, 603, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 10:05:00'),
(1652, 603, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 10:05:03'),
(1653, 603, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 10:05:07'),
(1654, 603, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 10:34:37'),
(1655, 604, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 10:34:46'),
(1656, 604, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 10:34:50'),
(1657, 604, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 10:34:53'),
(1658, 605, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 10:35:00'),
(1659, 605, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 10:35:03'),
(1660, 604, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 10:48:04'),
(1661, 606, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 10:48:13'),
(1662, 606, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 10:48:14'),
(1663, 606, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 10:48:18'),
(1664, 606, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-01 10:48:23'),
(1665, 605, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 10:48:39'),
(1666, 607, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-01 11:26:21'),
(1667, 607, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 11:26:28'),
(1668, 607, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 11:26:33'),
(1669, 607, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 11:38:15'),
(1670, 608, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 12:09:55'),
(1671, 608, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 12:09:57'),
(1672, 609, 8, 'Black Coffee Normal', 7.00, 6, 42.00, '2026-11-01 12:49:58'),
(1673, 609, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 12:50:03'),
(1674, 609, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 12:50:07'),
(1675, 610, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-01 12:50:17'),
(1676, 610, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 12:50:21'),
(1677, 610, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 12:50:26'),
(1678, 610, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 12:50:31'),
(1679, 611, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-01 13:30:06'),
(1680, 611, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 13:30:09'),
(1681, 612, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 13:41:50'),
(1682, 612, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 13:41:54'),
(1683, 612, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 13:42:03'),
(1684, 613, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 14:34:56'),
(1685, 613, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 14:35:01'),
(1686, 614, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 14:35:12'),
(1687, 614, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 14:35:14'),
(1688, 614, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 14:35:17'),
(1689, 614, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 14:35:20'),
(1690, 615, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-11-01 14:35:29'),
(1691, 615, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 14:35:34'),
(1692, 615, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 14:35:35'),
(1693, 615, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 14:35:39'),
(1694, 615, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 14:35:44'),
(1695, 616, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-01 14:56:20'),
(1696, 616, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 14:56:26'),
(1697, 616, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 14:56:33'),
(1698, 617, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 15:08:40'),
(1699, 617, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 15:08:43'),
(1700, 618, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-01 15:26:45'),
(1701, 618, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-01 15:26:51'),
(1702, 618, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-01 15:26:55'),
(1703, 618, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 15:27:05'),
(1704, 618, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-01 15:27:08'),
(1705, 619, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 15:38:35'),
(1706, 619, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 15:38:38'),
(1707, 619, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 15:38:41'),
(1708, 620, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 15:42:05'),
(1709, 620, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-11-01 15:42:07'),
(1710, 620, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-01 15:42:16'),
(1711, 620, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 15:42:20'),
(1712, 621, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 15:44:31'),
(1713, 621, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 15:44:41'),
(1714, 622, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 15:44:50'),
(1718, 621, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 15:54:55'),
(1719, 625, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-01 15:56:47'),
(1720, 626, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-01 15:57:34'),
(1721, 626, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 15:57:37'),
(1722, 627, 6, 'Hedhika', 5.00, 10, 50.00, '2026-11-01 16:02:40'),
(1723, 627, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 16:02:54'),
(1724, 627, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-01 16:02:57'),
(1726, 626, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 16:06:06'),
(1727, 627, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 16:08:22'),
(1728, 627, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-01 16:19:36'),
(1729, 628, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 16:24:26'),
(1730, 628, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-01 16:24:28'),
(1731, 629, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 16:36:45'),
(1732, 628, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-01 16:37:08'),
(1733, 628, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 16:37:10'),
(1734, 628, 6, 'Hedhika', 5.00, 11, 55.00, '2026-11-01 16:37:20'),
(1736, 631, 6, 'Hedhika', 5.00, 29, 145.00, '2026-11-01 16:44:51'),
(1737, 631, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 16:45:09'),
(1738, 631, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-01 16:45:15'),
(1739, 628, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-01 16:50:13'),
(1740, 629, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 16:52:34'),
(1741, 629, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 16:52:38'),
(1742, 632, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-01 17:27:01'),
(1743, 632, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-01 17:27:12'),
(1744, 632, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 17:27:13'),
(1745, 632, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 17:40:19'),
(1746, 633, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-01 18:20:41'),
(1747, 634, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-01 18:22:41'),
(1748, 634, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 18:23:47'),
(1749, 635, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-01 18:28:11'),
(1750, 634, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 18:28:47'),
(1752, 634, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 18:28:53'),
(1754, 636, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 18:37:08'),
(1755, 636, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 18:37:12'),
(1756, 636, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-01 18:37:14'),
(1757, 637, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-01 18:37:57'),
(1758, 638, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-01 18:38:06'),
(1759, 638, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-01 18:38:11'),
(1760, 638, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 18:38:29'),
(1761, 638, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-01 18:45:03'),
(1762, 639, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-01 18:45:44'),
(1763, 639, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 18:45:54'),
(1764, 639, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 18:45:57'),
(1765, 639, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 18:46:02'),
(1766, 640, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-01 18:46:33'),
(1767, 635, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-01 18:49:41'),
(1768, 635, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 18:49:48'),
(1769, 641, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-01 18:56:50'),
(1770, 641, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-01 18:56:58'),
(1771, 642, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-01 19:03:35'),
(1772, 642, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 19:03:45'),
(1773, 642, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 19:03:47'),
(1774, 643, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-01 19:05:44'),
(1775, 643, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 19:05:47'),
(1776, 643, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-01 19:05:51'),
(1777, 643, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 19:22:30'),
(1778, 644, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-01 19:33:07'),
(1779, 644, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 19:33:11'),
(1780, 644, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 19:33:16'),
(1781, 643, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-01 19:35:43'),
(1782, 645, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 19:43:16'),
(1783, 645, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 19:43:20'),
(1784, 645, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-01 19:43:23'),
(1785, 646, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 19:43:47'),
(1786, 646, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 19:43:49'),
(1787, 647, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-01 20:00:07'),
(1788, 647, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-11-01 20:00:25'),
(1789, 647, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-01 20:00:38'),
(1790, 648, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 20:30:49'),
(1791, 648, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-11-01 20:30:51'),
(1792, 648, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 20:30:53'),
(1793, 648, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-01 20:30:56'),
(1794, 640, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-01 21:34:58'),
(1795, 649, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 21:48:03'),
(1796, 649, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 21:48:04'),
(1797, 649, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 21:48:05'),
(1798, 649, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 21:54:16'),
(1800, 651, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-01 22:54:11'),
(1801, 651, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-01 22:54:14'),
(1802, 652, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-01 23:05:00'),
(1803, 652, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 23:05:03'),
(1804, 652, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-01 23:05:05'),
(1805, 653, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-01 23:13:33'),
(1806, 653, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 23:13:36'),
(1807, 652, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-01 23:17:20'),
(1808, 654, 12, 'Water Bottle 500 ML', 5.00, 6, 30.00, '2026-11-01 23:41:53'),
(1809, 655, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-01 23:49:51'),
(1810, 655, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-01 23:49:55'),
(1811, 655, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-01 23:49:58'),
(1812, 656, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 00:07:48'),
(1813, 656, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 00:07:52'),
(1814, 656, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 00:07:54'),
(1815, 657, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 00:32:02'),
(1816, 657, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 00:32:04'),
(1817, 657, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 00:32:08'),
(1818, 658, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 00:53:40'),
(1819, 658, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 00:53:42'),
(1820, 659, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 07:04:22'),
(1821, 659, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-02 07:04:26'),
(1822, 659, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 07:04:28'),
(1823, 659, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 07:04:31'),
(1824, 660, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-02 07:04:40'),
(1825, 660, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 07:04:43'),
(1826, 660, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 07:04:45'),
(1827, 660, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 07:04:49'),
(1828, 661, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 07:26:21'),
(1829, 662, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 08:00:45'),
(1830, 662, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 08:00:48'),
(1831, 663, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 08:09:18'),
(1832, 663, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 08:09:22'),
(1833, 664, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 09:18:48'),
(1834, 664, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 09:18:52'),
(1835, 664, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 09:18:54'),
(1836, 664, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-02 09:38:36'),
(1837, 665, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 09:49:33'),
(1838, 665, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 09:49:36'),
(1839, 665, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-02 09:49:38'),
(1840, 665, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-02 09:49:43'),
(1841, 666, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 10:36:00'),
(1842, 666, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 10:36:04'),
(1843, 666, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 10:36:07'),
(1844, 667, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 11:06:06'),
(1845, 667, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 11:06:11'),
(1846, 667, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 11:06:17'),
(1847, 668, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 11:06:22'),
(1848, 668, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 11:06:26'),
(1849, 668, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-02 11:06:27'),
(1850, 668, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-02 11:06:29'),
(1851, 669, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-02 12:03:43'),
(1852, 669, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 12:03:48'),
(1853, 669, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-02 12:03:53'),
(1854, 670, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 12:03:59'),
(1855, 670, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-02 12:04:03'),
(1856, 670, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 12:04:06'),
(1857, 669, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 12:23:48'),
(1858, 671, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 12:58:30'),
(1859, 672, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 13:50:16'),
(1860, 672, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 13:50:17'),
(1861, 672, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 13:50:22'),
(1862, 672, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 13:50:28'),
(1863, 673, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 13:50:36'),
(1864, 674, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 14:02:29'),
(1865, 674, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 14:02:31'),
(1866, 675, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 14:02:39'),
(1867, 675, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 14:02:40'),
(1868, 676, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 15:10:13'),
(1869, 677, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 15:22:06'),
(1871, 678, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-02 15:43:29'),
(1872, 678, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-02 15:43:30'),
(1873, 678, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 15:43:39'),
(1874, 679, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-02 15:45:14'),
(1875, 679, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-02 15:45:20'),
(1876, 680, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-02 15:45:29'),
(1877, 680, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-02 15:45:34'),
(1878, 680, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 15:45:34'),
(1881, 680, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 15:54:26'),
(1882, 680, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 15:54:28'),
(1883, 682, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 16:02:32'),
(1884, 682, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 16:02:33'),
(1885, 682, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:02:46'),
(1886, 682, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 16:02:58'),
(1887, 683, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 16:03:06'),
(1888, 683, 6, 'Hedhika', 5.00, 7, 35.00, '2026-11-02 16:03:10'),
(1889, 683, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-02 16:03:17'),
(1890, 683, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:03:19'),
(1891, 684, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-02 16:06:58'),
(1892, 684, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:06:59'),
(1893, 684, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-02 16:07:03'),
(1894, 678, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 16:10:03'),
(1895, 685, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-02 16:21:47'),
(1896, 685, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-02 16:21:51'),
(1897, 686, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-02 16:27:00'),
(1901, 688, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-02 16:31:52'),
(1902, 688, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-02 16:31:56'),
(1903, 688, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 16:31:59'),
(1904, 688, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-02 16:32:02'),
(1905, 689, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-02 16:38:55'),
(1906, 689, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:39:01'),
(1907, 690, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-02 16:39:09'),
(1908, 690, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:39:13'),
(1909, 690, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-02 16:39:15'),
(1910, 690, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 16:39:17'),
(1911, 688, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-02 16:50:59'),
(1912, 691, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 16:51:38'),
(1913, 691, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 16:51:40'),
(1914, 691, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 16:51:43'),
(1915, 689, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-02 16:52:48'),
(1916, 692, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-02 17:19:25'),
(1917, 692, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-02 17:19:33'),
(1918, 692, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-02 17:19:34'),
(1919, 692, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-02 17:19:40'),
(1920, 692, 6, 'Hedhika', 5.00, 18, 90.00, '2026-11-02 17:19:44'),
(1922, 692, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-02 17:31:01'),
(1923, 693, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-02 18:16:24'),
(1924, 693, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 18:16:31'),
(1925, 693, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-02 18:16:33'),
(1926, 694, 11, 'Black Tea Small', 3.00, 5, 15.00, '2026-11-02 18:16:46'),
(1928, 696, 6, 'Hedhika', 5.00, 13, 65.00, '2026-11-02 18:31:08'),
(1929, 696, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-11-02 18:31:36'),
(1930, 696, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 18:31:40'),
(1931, 697, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-02 18:32:18'),
(1932, 697, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 18:32:26'),
(1933, 697, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 18:32:29'),
(1934, 697, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 18:32:36'),
(1935, 698, 6, 'Hedhika', 5.00, 9, 45.00, '2026-11-02 18:44:31'),
(1936, 698, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 18:44:41'),
(1937, 698, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-02 18:44:43'),
(1938, 696, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 18:48:01'),
(1939, 696, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-02 18:49:41'),
(1940, 699, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-02 18:52:23'),
(1942, 699, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 18:52:29'),
(1943, 699, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-02 18:52:33'),
(1944, 699, 17, 'Cereal Coffee (Light)', 10.00, 2, 20.00, '2026-11-02 18:52:35'),
(1945, 700, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 19:01:11'),
(1946, 701, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 19:05:59'),
(1947, 701, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 19:06:03'),
(1948, 702, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 19:09:23'),
(1949, 699, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 19:10:07'),
(1950, 703, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 19:12:25'),
(1954, 705, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-02 19:16:27'),
(1955, 705, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-02 19:16:34'),
(1956, 705, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 19:16:37'),
(1957, 699, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 19:25:58'),
(1958, 705, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 19:29:45'),
(1959, 706, 6, 'Hedhika', 5.00, 14, 70.00, '2026-11-02 19:38:19'),
(1960, 706, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-02 19:38:32'),
(1961, 706, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-11-02 19:38:35'),
(1962, 706, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 19:38:39'),
(1963, 707, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 19:43:37'),
(1964, 707, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-02 19:43:38'),
(1965, 707, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-02 19:43:41'),
(1966, 707, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-02 19:43:45'),
(1967, 706, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 19:49:45'),
(1968, 707, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 20:16:27'),
(1969, 708, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-02 20:20:42'),
(1970, 708, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 20:20:43'),
(1972, 708, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 20:20:48'),
(1973, 709, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-02 20:23:28'),
(1974, 710, 5, 'YeYe coffee', 5.00, 3, 15.00, '2026-11-02 21:28:28'),
(1975, 711, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-02 21:54:43'),
(1976, 711, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 21:54:44'),
(1977, 711, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-02 21:54:48'),
(1978, 711, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 21:54:51'),
(1979, 712, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 22:01:04'),
(1980, 713, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-02 22:12:51'),
(1981, 713, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-02 22:12:53'),
(1982, 713, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 22:12:55'),
(1983, 713, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 22:46:11'),
(1984, 714, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 23:11:05'),
(1985, 715, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-02 23:30:16'),
(1986, 715, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-02 23:30:18'),
(1987, 716, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-11-03 00:08:28'),
(1988, 716, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 00:08:32'),
(1989, 717, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 00:11:08'),
(1990, 717, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 00:11:09'),
(1991, 716, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-03 00:20:04'),
(1992, 718, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-03 09:12:50'),
(1996, 721, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 10:05:59'),
(1997, 721, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 10:06:01'),
(1998, 721, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 10:06:03'),
(1999, 721, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 10:06:05'),
(2000, 722, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 10:06:13'),
(2001, 722, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 10:06:15'),
(2002, 722, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 10:06:16'),
(2003, 722, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 10:06:18'),
(2004, 723, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-11-03 10:13:34'),
(2005, 723, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 10:16:40'),
(2006, 724, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-03 11:11:35'),
(2007, 724, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 11:11:37'),
(2008, 724, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 11:11:39'),
(2009, 724, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 11:11:41'),
(2010, 725, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 11:35:21'),
(2011, 725, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 11:35:22'),
(2012, 725, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 11:35:25'),
(2013, 725, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 11:35:26'),
(2014, 726, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 11:36:24'),
(2015, 726, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 11:36:25'),
(2016, 726, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 11:36:27'),
(2020, 728, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 11:57:26'),
(2021, 728, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-03 11:57:29'),
(2022, 728, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 11:57:33'),
(2023, 729, 7, 'Black Coffee Hard', 10.00, 4, 40.00, '2026-11-03 12:01:52'),
(2024, 729, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-03 12:01:54'),
(2025, 729, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 12:01:56'),
(2026, 730, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-03 12:02:15'),
(2027, 730, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 12:02:17'),
(2028, 730, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 12:02:20'),
(2029, 730, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 12:02:22'),
(2030, 730, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 12:02:25'),
(2031, 728, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 12:31:37'),
(2032, 731, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 12:40:09'),
(2033, 731, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 12:40:11'),
(2035, 731, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-03 12:40:16'),
(2036, 732, 16, 'Bisket', 5.00, 2, 10.00, '2026-11-03 12:40:22'),
(2037, 732, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-11-03 12:40:33'),
(2038, 732, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 12:40:40'),
(2039, 732, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 12:40:44'),
(2043, 729, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 12:54:19'),
(2044, 734, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-03 14:22:33'),
(2045, 734, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 14:22:38'),
(2046, 734, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 14:22:40'),
(2047, 735, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 14:47:27'),
(2048, 735, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-03 14:47:29'),
(2049, 735, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 14:47:32'),
(2050, 736, 3, 'Cereal Coffee (hard)', 15.00, 2, 30.00, '2026-11-03 14:48:01'),
(2051, 736, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 14:48:05'),
(2052, 736, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 14:48:07'),
(2053, 736, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 14:48:11'),
(2054, 736, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 14:52:40'),
(2055, 737, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 15:22:22'),
(2056, 737, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:22:25'),
(2057, 733, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-03 15:22:44'),
(2058, 733, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-03 15:22:50'),
(2059, 733, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:22:52'),
(2060, 733, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 15:25:07'),
(2061, 720, 7, 'Black Coffee Hard', 10.00, 5, 50.00, '2026-11-03 15:25:23'),
(2062, 720, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-03 15:25:27'),
(2063, 720, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-11-03 15:25:31'),
(2064, 720, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 15:25:55'),
(2065, 738, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 15:29:06'),
(2066, 738, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 15:29:09'),
(2067, 738, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:29:11'),
(2072, 740, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 15:48:12'),
(2073, 740, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 15:48:13'),
(2074, 740, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 15:48:17'),
(2075, 740, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 15:48:20'),
(2076, 741, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-03 15:55:38'),
(2077, 741, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:55:42'),
(2078, 741, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 15:55:44'),
(2079, 742, 6, 'Hedhika', 5.00, 10, 50.00, '2026-11-03 15:55:47'),
(2080, 742, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 15:56:09'),
(2081, 742, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-03 15:56:10'),
(2082, 742, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:56:12'),
(2083, 742, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 15:56:12'),
(2084, 733, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 15:56:53'),
(2085, 743, 6, 'Hedhika', 5.00, 9, 45.00, '2026-11-03 15:57:12'),
(2086, 743, 10, 'Black Tea Large', 5.00, 3, 15.00, '2026-11-03 15:57:35'),
(2087, 743, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-03 15:57:38'),
(2088, 743, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:57:39'),
(2089, 744, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 15:58:38'),
(2090, 744, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-03 15:58:42'),
(2091, 744, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-03 15:58:44'),
(2092, 744, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 15:58:46'),
(2093, 744, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 15:58:49'),
(2094, 745, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 16:00:15'),
(2095, 745, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 16:00:18'),
(2096, 743, 15, 'Nuts Full', 10.00, 2, 20.00, '2026-11-03 16:04:18'),
(2097, 742, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 16:04:42'),
(2098, 746, 6, 'Hedhika', 5.00, 13, 65.00, '2026-11-03 16:15:20'),
(2099, 747, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-03 16:20:31'),
(2100, 747, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-03 16:20:37'),
(2101, 748, 6, 'Hedhika', 5.00, 15, 75.00, '2026-11-03 16:27:49'),
(2102, 749, 6, 'Hedhika', 5.00, 7, 35.00, '2026-11-03 16:43:17'),
(2103, 749, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 16:43:22'),
(2104, 749, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 16:43:25'),
(2105, 749, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 17:03:15'),
(2106, 750, 6, 'Hedhika', 5.00, 18, 90.00, '2026-11-03 17:03:21'),
(2107, 750, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-11-03 17:03:29'),
(2108, 750, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 17:03:30'),
(2110, 751, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 17:08:36'),
(2111, 751, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-03 17:08:39'),
(2112, 751, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 17:08:43'),
(2113, 751, 6, 'Hedhika', 5.00, 8, 40.00, '2026-11-03 17:09:03'),
(2114, 751, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 17:12:47'),
(2115, 752, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-03 17:12:53'),
(2116, 752, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 17:12:57'),
(2117, 752, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-03 17:12:59'),
(2122, 750, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-03 17:19:02'),
(2123, 754, 6, 'Hedhika', 5.00, 7, 35.00, '2026-11-03 17:23:19'),
(2124, 754, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 17:23:33'),
(2125, 754, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-03 17:23:34'),
(2126, 754, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-03 17:23:36'),
(2127, 752, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-03 17:23:48'),
(2128, 755, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 17:31:44'),
(2129, 755, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-03 17:31:46'),
(2130, 755, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 17:31:49'),
(2132, 755, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-03 17:36:44'),
(2133, 755, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 17:39:26'),
(2134, 756, 6, 'Hedhika', 5.00, 7, 35.00, '2026-11-03 17:44:04'),
(2135, 756, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-03 17:44:16'),
(2137, 756, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-11-03 17:45:00'),
(2138, 754, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 17:52:57'),
(2139, 756, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 17:53:30'),
(2140, 756, 16, 'Bisket', 5.00, 2, 10.00, '2026-11-03 17:53:36'),
(2141, 756, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 17:58:11'),
(2142, 757, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 18:04:12'),
(2143, 750, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 18:07:00'),
(2144, 750, 11, 'Black Tea Small', 3.00, 2, 6.00, '2026-11-03 18:07:34'),
(2145, 758, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 18:15:55'),
(2147, 759, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-03 18:34:46'),
(2148, 759, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 18:34:47'),
(2149, 759, 2, 'Milk Coffee', 10.00, 2, 20.00, '2026-11-03 18:34:49'),
(2150, 759, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-03 18:34:52'),
(2151, 760, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-03 19:01:42'),
(2152, 760, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 19:01:44'),
(2153, 761, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-03 19:07:12'),
(2154, 762, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 19:07:27'),
(2155, 763, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-03 19:08:39'),
(2156, 760, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 19:16:54'),
(2157, 764, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 19:21:24'),
(2158, 765, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 19:26:23'),
(2159, 764, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-03 19:38:18'),
(2160, 766, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-03 19:38:26'),
(2165, 768, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 20:49:19'),
(2166, 769, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-03 20:50:24'),
(2167, 769, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-03 20:50:33'),
(2168, 769, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 20:50:46'),
(2169, 769, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 20:50:49'),
(2170, 770, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 21:12:11'),
(2171, 770, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 21:12:11'),
(2172, 770, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 21:12:14'),
(2173, 770, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 21:12:15'),
(2174, 771, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-11-03 21:23:52'),
(2175, 772, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-03 21:25:06'),
(2176, 773, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 21:29:27'),
(2177, 774, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-03 21:38:52'),
(2178, 774, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-03 21:38:53'),
(2179, 774, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-03 21:38:56'),
(2180, 771, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 21:44:57'),
(2181, 771, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 21:45:00'),
(2182, 774, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 22:03:55'),
(2183, 775, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-03 23:23:55'),
(2184, 775, 2, 'Milk Coffee', 10.00, 3, 30.00, '2026-11-03 23:23:56'),
(2185, 775, 12, 'Water Bottle 500 ML', 5.00, 5, 25.00, '2026-11-03 23:24:00'),
(2186, 775, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-03 23:30:43'),
(2188, 764, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-03 23:35:30'),
(2189, 764, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 00:03:50'),
(2190, 776, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-04 00:29:46'),
(2191, 777, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-04 07:20:32'),
(2192, 777, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 07:20:38'),
(2193, 777, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 07:20:43'),
(2194, 778, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 07:44:59'),
(2195, 779, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 08:11:10'),
(2196, 779, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-04 08:11:16'),
(2197, 779, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 08:11:25'),
(2198, 780, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 08:58:31'),
(2199, 780, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 08:58:35'),
(2200, 780, 12, 'Water Bottle 500 ML', 5.00, 3, 15.00, '2026-11-04 08:58:38'),
(2201, 780, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-04 08:58:43'),
(2202, 781, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 09:27:34'),
(2203, 780, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 09:28:14'),
(2204, 782, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 10:56:07'),
(2205, 782, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 10:56:09'),
(2206, 782, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 10:56:11'),
(2207, 782, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 10:56:13'),
(2208, 783, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 11:02:34'),
(2209, 783, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-04 11:02:39'),
(2210, 783, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 11:02:51'),
(2211, 784, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 11:03:02'),
(2212, 784, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 11:03:05'),
(2213, 783, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 11:15:32'),
(2214, 785, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-04 11:19:10'),
(2215, 785, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-04 11:19:14'),
(2216, 785, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 11:19:16'),
(2219, 787, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 11:22:09'),
(2220, 787, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 11:22:11'),
(2221, 788, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-11-04 12:02:27'),
(2222, 788, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 12:02:32'),
(2223, 788, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 12:02:37'),
(2224, 789, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 12:02:52'),
(2225, 789, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 12:02:55'),
(2226, 789, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 12:03:00'),
(2227, 790, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-04 12:43:59'),
(2228, 790, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 12:44:03'),
(2229, 790, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 12:44:06'),
(2230, 791, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 13:51:06'),
(2231, 791, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 13:51:07'),
(2232, 792, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-04 14:42:32'),
(2233, 792, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 14:42:41'),
(2234, 792, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 14:42:45'),
(2235, 793, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 14:48:56'),
(2236, 793, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 14:48:58'),
(2237, 793, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 14:49:00'),
(2238, 793, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 14:49:03'),
(2239, 794, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 14:49:10'),
(2241, 794, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 14:49:16'),
(2242, 794, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 14:49:18'),
(2243, 795, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 14:49:28'),
(2244, 795, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 14:49:29'),
(2245, 795, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 14:49:32'),
(2249, 757, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 15:20:01'),
(2250, 794, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 15:40:22'),
(2251, 797, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 15:41:59'),
(2252, 797, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 15:42:02'),
(2253, 796, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 15:43:11'),
(2254, 798, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-04 15:46:57'),
(2255, 798, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 15:47:04'),
(2256, 799, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 16:23:52'),
(2257, 800, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 16:24:46'),
(2258, 800, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 16:24:49'),
(2259, 800, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 16:26:41'),
(2260, 801, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-04 16:30:53'),
(2261, 802, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-04 17:12:41'),
(2262, 803, 6, 'Hedhika', 5.00, 10, 50.00, '2026-11-04 17:27:10'),
(2263, 803, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-04 17:27:20'),
(2264, 803, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 17:27:22'),
(2265, 803, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-04 17:36:43'),
(2266, 804, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 17:38:40'),
(2267, 804, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-04 17:38:42'),
(2268, 804, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 17:38:43'),
(2269, 804, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-04 17:40:41'),
(2270, 805, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-04 18:05:31'),
(2271, 805, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 18:05:33'),
(2272, 805, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 18:05:35'),
(2273, 805, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-04 18:05:45'),
(2274, 805, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-04 18:05:52'),
(2275, 805, 6, 'Hedhika', 5.00, 14, 70.00, '2026-11-04 18:05:54'),
(2276, 805, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-04 18:06:22'),
(2277, 803, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 18:10:52'),
(2278, 806, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-04 18:19:03'),
(2279, 806, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 18:19:07'),
(2280, 806, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 18:19:13'),
(2281, 807, 6, 'Hedhika', 5.00, 6, 30.00, '2026-11-04 18:25:52'),
(2282, 807, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 18:25:58'),
(2283, 807, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-04 18:25:59'),
(2284, 808, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 18:26:07'),
(2285, 808, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-04 18:26:10'),
(2287, 807, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 18:39:29'),
(2288, 808, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 18:43:28'),
(2293, 809, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-04 18:54:11'),
(2294, 809, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 18:54:15'),
(2295, 809, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-04 18:54:16'),
(2296, 810, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 19:01:28'),
(2297, 810, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 19:01:33'),
(2298, 810, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 19:01:34'),
(2299, 811, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-04 19:06:09'),
(2300, 811, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-04 19:09:29'),
(2301, 811, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 19:09:30'),
(2302, 812, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-04 19:20:23'),
(2303, 812, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 19:20:27'),
(2304, 812, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-04 19:20:29'),
(2305, 813, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 19:24:45'),
(2306, 813, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 19:24:47'),
(2307, 813, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 19:24:47'),
(2308, 814, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 19:25:56');
INSERT INTO `sale_lines` (`id`, `sale_id`, `item_id`, `item_name`, `unit_price`, `qty`, `line_total`, `added_at`) VALUES
(2309, 815, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-04 19:29:56'),
(2310, 815, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-04 19:30:01'),
(2311, 814, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 19:32:40'),
(2312, 814, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 19:32:42'),
(2313, 813, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 19:44:14'),
(2314, 815, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-04 19:50:19'),
(2315, 815, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-04 19:50:23'),
(2316, 816, 8, 'Black Coffee Normal', 7.00, 5, 35.00, '2026-11-04 21:01:47'),
(2317, 816, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 21:01:48'),
(2318, 816, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-04 21:25:19'),
(2319, 817, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 21:39:54'),
(2320, 817, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 21:39:56'),
(2321, 817, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 21:39:59'),
(2322, 818, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 21:42:02'),
(2323, 819, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-04 21:58:02'),
(2324, 819, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 21:58:04'),
(2325, 819, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 21:58:05'),
(2326, 820, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 21:59:04'),
(2327, 820, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 21:59:06'),
(2328, 821, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-04 22:12:52'),
(2330, 822, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-04 22:27:50'),
(2331, 822, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 22:27:53'),
(2332, 822, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 22:27:54'),
(2333, 823, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 22:51:28'),
(2334, 824, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-04 22:51:32'),
(2335, 823, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-04 23:14:33'),
(2336, 823, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-04 23:14:43'),
(2337, 825, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-04 23:17:44'),
(2338, 826, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 07:28:35'),
(2339, 826, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 07:28:40'),
(2340, 826, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 07:28:43'),
(2341, 827, 17, 'Cereal Coffee (Light)', 10.00, 1, 10.00, '2026-11-05 07:28:49'),
(2342, 828, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 07:44:14'),
(2343, 829, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 08:37:05'),
(2344, 829, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 08:37:07'),
(2345, 829, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 08:37:11'),
(2346, 829, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-05 08:37:15'),
(2347, 830, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 09:32:17'),
(2348, 830, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 09:32:18'),
(2349, 830, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 09:32:23'),
(2350, 831, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 09:37:41'),
(2351, 831, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 09:37:44'),
(2352, 832, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 09:38:32'),
(2353, 833, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-05 09:48:42'),
(2354, 833, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 09:48:47'),
(2355, 833, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 09:48:49'),
(2356, 834, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 10:18:55'),
(2357, 834, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 10:18:59'),
(2358, 834, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 10:19:02'),
(2359, 835, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 10:35:44'),
(2360, 836, 9, 'Lemon Tea', 10.00, 1, 10.00, '2026-11-05 10:35:58'),
(2361, 836, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 10:35:59'),
(2362, 837, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 10:43:25'),
(2363, 836, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 11:08:39'),
(2364, 838, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 11:54:26'),
(2365, 838, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 11:54:28'),
(2366, 839, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-05 12:02:08'),
(2367, 839, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 12:02:11'),
(2368, 839, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 12:02:17'),
(2369, 839, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 12:02:22'),
(2370, 839, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-05 12:12:14'),
(2371, 840, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 12:45:52'),
(2372, 840, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 12:45:54'),
(2373, 840, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 12:45:56'),
(2374, 841, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-11-05 12:56:44'),
(2375, 841, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 12:56:51'),
(2376, 841, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-05 12:56:56'),
(2377, 842, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-05 13:27:25'),
(2378, 842, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 13:27:31'),
(2379, 842, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 13:27:34'),
(2380, 843, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 13:38:32'),
(2381, 843, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 13:38:34'),
(2382, 843, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 13:38:39'),
(2383, 844, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 13:39:33'),
(2384, 844, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 13:39:35'),
(2385, 844, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 13:39:38'),
(2386, 845, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 15:17:14'),
(2387, 845, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 15:17:17'),
(2388, 846, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 15:18:03'),
(2389, 846, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-05 15:18:07'),
(2390, 846, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 15:18:11'),
(2391, 846, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 15:18:19'),
(2392, 847, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 15:23:52'),
(2393, 847, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 15:24:28'),
(2394, 847, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-05 15:24:44'),
(2395, 847, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-05 15:24:52'),
(2396, 848, 12, 'Water Bottle 500 ML', 5.00, 6, 30.00, '2026-11-05 15:25:06'),
(2397, 849, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 15:29:34'),
(2398, 849, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 15:29:39'),
(2399, 849, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 15:29:43'),
(2400, 850, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 15:33:01'),
(2401, 850, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 15:33:05'),
(2402, 850, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 15:33:06'),
(2403, 851, 6, 'Hedhika', 5.00, 8, 40.00, '2026-11-05 15:44:47'),
(2404, 852, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-05 16:03:39'),
(2405, 853, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-05 16:04:02'),
(2406, 853, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-05 16:04:07'),
(2407, 853, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 16:04:09'),
(2408, 851, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-05 16:07:24'),
(2409, 851, 11, 'Black Tea Small', 3.00, 4, 12.00, '2026-11-05 16:07:26'),
(2410, 851, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 16:08:03'),
(2411, 851, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-05 16:08:08'),
(2412, 851, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 16:08:12'),
(2413, 854, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-11-05 16:50:06'),
(2414, 854, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 16:50:08'),
(2415, 854, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-05 16:50:10'),
(2416, 855, 6, 'Hedhika', 5.00, 5, 25.00, '2026-11-05 16:50:49'),
(2417, 856, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-05 16:51:03'),
(2418, 857, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 17:01:37'),
(2419, 857, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 17:01:40'),
(2420, 857, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-05 17:01:42'),
(2421, 857, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 17:01:47'),
(2422, 857, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-05 17:01:48'),
(2423, 858, 6, 'Hedhika', 5.00, 1, 5.00, '2026-11-05 17:09:31'),
(2424, 858, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 17:09:34'),
(2425, 858, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-05 17:09:36'),
(2426, 859, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 17:09:45'),
(2428, 858, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-05 17:17:03'),
(2429, 860, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 17:17:51'),
(2430, 860, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 17:17:53'),
(2431, 861, 6, 'Hedhika', 5.00, 8, 40.00, '2026-11-05 17:24:37'),
(2432, 862, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-05 17:30:28'),
(2433, 863, 6, 'Hedhika', 5.00, 7, 35.00, '2026-11-05 17:36:58'),
(2434, 863, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-05 17:37:06'),
(2435, 863, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 17:37:07'),
(2436, 864, 6, 'Hedhika', 5.00, 3, 15.00, '2026-11-05 17:37:16'),
(2437, 864, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 17:37:23'),
(2438, 864, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 17:37:25'),
(2439, 865, 6, 'Hedhika', 5.00, 24, 120.00, '2026-11-05 17:51:31'),
(2440, 866, 6, 'Hedhika', 5.00, 4, 20.00, '2026-11-05 17:51:53'),
(2441, 867, 6, 'Hedhika', 5.00, 10, 50.00, '2026-11-05 17:58:11'),
(2442, 867, 10, 'Black Tea Large', 5.00, 2, 10.00, '2026-11-05 17:58:21'),
(2443, 867, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 17:58:23'),
(2444, 867, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 18:18:08'),
(2445, 868, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-05 18:22:08'),
(2446, 868, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-05 18:22:13'),
(2447, 868, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 18:22:13'),
(2448, 869, 6, 'Hedhika', 5.00, 2, 10.00, '2026-11-05 18:29:20'),
(2449, 869, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 18:33:28'),
(2450, 869, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-05 18:33:29'),
(2451, 869, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 18:33:30'),
(2452, 860, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-05 18:37:20'),
(2453, 860, 10, 'Black Tea Large', 5.00, 1, 5.00, '2026-11-05 18:37:24'),
(2454, 867, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 18:38:42'),
(2455, 870, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 18:44:38'),
(2456, 870, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 18:44:40'),
(2457, 870, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 18:44:42'),
(2458, 870, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 18:47:51'),
(2459, 871, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 19:04:33'),
(2460, 872, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 19:10:54'),
(2461, 872, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 19:10:55'),
(2462, 872, 14, 'Nuts Half', 5.00, 2, 10.00, '2026-11-05 19:10:57'),
(2463, 872, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-05 19:49:40'),
(2464, 873, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 20:28:24'),
(2465, 874, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 21:14:33'),
(2466, 874, 5, 'YeYe coffee', 5.00, 1, 5.00, '2026-11-05 21:14:36'),
(2468, 874, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 21:14:40'),
(2469, 875, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-05 21:14:56'),
(2470, 875, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 21:14:58'),
(2471, 876, 11, 'Black Tea Small', 3.00, 3, 9.00, '2026-11-05 21:15:16'),
(2472, 874, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-05 21:32:13'),
(2473, 874, 3, 'Cereal Coffee (hard)', 15.00, 1, 15.00, '2026-11-05 21:32:29'),
(2474, 877, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-05 21:34:00'),
(2475, 877, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 21:34:04'),
(2476, 877, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 21:34:05'),
(2477, 877, 15, 'Nuts Full', 10.00, 1, 10.00, '2026-11-05 21:42:57'),
(2478, 878, 8, 'Black Coffee Normal', 7.00, 4, 28.00, '2026-11-05 21:59:18'),
(2479, 878, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-05 21:59:21'),
(2480, 879, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-05 22:00:08'),
(2481, 879, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 22:00:13'),
(2482, 880, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 22:14:28'),
(2483, 880, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 22:14:30'),
(2484, 878, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 22:15:34'),
(2485, 881, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 22:36:55'),
(2486, 881, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-05 22:36:57'),
(2488, 883, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 22:39:48'),
(2489, 884, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 22:46:15'),
(2490, 885, 12, 'Water Bottle 500 ML', 5.00, 7, 35.00, '2026-11-05 23:07:52'),
(2491, 885, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-05 23:08:00'),
(2492, 885, 2, 'Milk Coffee', 10.00, 4, 40.00, '2026-11-05 23:08:03'),
(2493, 885, 5, 'YeYe coffee', 5.00, 2, 10.00, '2026-11-05 23:08:33'),
(2494, 886, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-05 23:15:53'),
(2495, 886, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 23:15:56'),
(2496, 884, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-05 23:24:20'),
(2497, 884, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 23:24:25'),
(2498, 885, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-05 23:40:20'),
(2499, 887, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-05 23:45:46'),
(2500, 888, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 10:59:51'),
(2501, 889, 7, 'Black Coffee Hard', 10.00, 3, 30.00, '2026-11-06 14:51:44'),
(2502, 889, 8, 'Black Coffee Normal', 7.00, 6, 42.00, '2026-11-06 14:51:46'),
(2503, 889, 14, 'Nuts Half', 5.00, 3, 15.00, '2026-11-06 14:51:48'),
(2504, 889, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-06 14:51:51'),
(2505, 890, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-06 15:35:56'),
(2506, 890, 12, 'Water Bottle 500 ML', 5.00, 2, 10.00, '2026-11-06 15:36:00'),
(2507, 890, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-06 15:36:01'),
(2508, 890, 2, 'Milk Coffee', 10.00, 1, 10.00, '2026-11-06 15:42:15'),
(2509, 891, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-06 15:53:31'),
(2510, 891, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 15:53:32'),
(2511, 892, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-06 15:57:45'),
(2512, 892, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 15:57:47'),
(2513, 893, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-06 16:26:04'),
(2514, 893, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 16:26:06'),
(2515, 894, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-06 17:06:00'),
(2516, 894, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 17:06:03'),
(2517, 895, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-06 17:06:08'),
(2518, 895, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 17:06:10'),
(2519, 896, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-06 17:12:22'),
(2520, 897, 7, 'Black Coffee Hard', 10.00, 2, 20.00, '2026-11-06 17:24:21'),
(2521, 897, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 17:24:23'),
(2522, 896, 16, 'Bisket', 5.00, 1, 5.00, '2026-11-06 17:32:22'),
(2523, 896, 11, 'Black Tea Small', 3.00, 1, 3.00, '2026-11-06 17:32:28'),
(2524, 898, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-06 17:39:51'),
(2525, 898, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 17:39:52'),
(2526, 899, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-06 17:41:38'),
(2527, 899, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-06 17:41:44'),
(2528, 895, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-06 17:58:13'),
(2529, 900, 8, 'Black Coffee Normal', 7.00, 1, 7.00, '2026-11-06 18:01:10'),
(2530, 900, 7, 'Black Coffee Hard', 10.00, 1, 10.00, '2026-11-06 18:01:11'),
(2531, 900, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-06 18:01:12'),
(2532, 900, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-06 18:08:15'),
(2533, 901, 8, 'Black Coffee Normal', 7.00, 3, 21.00, '2026-11-07 09:04:05'),
(2534, 901, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-07 09:04:07'),
(2535, 901, 14, 'Nuts Half', 5.00, 1, 5.00, '2026-11-07 09:04:09'),
(2536, 902, 8, 'Black Coffee Normal', 7.00, 2, 14.00, '2026-11-07 10:15:00'),
(2537, 902, 12, 'Water Bottle 500 ML', 5.00, 1, 5.00, '2026-11-07 10:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `owner_password_hash` varchar(255) NOT NULL,
  `staff_pin_hash` varchar(255) NOT NULL,
  `currency_symbol` varchar(8) NOT NULL DEFAULT 'MVR',
  `cafe_name` varchar(80) NOT NULL DEFAULT 'Ithaa Corner',
  `bank_image` varchar(80) NOT NULL DEFAULT '',
  `order_host` varchar(80) NOT NULL DEFAULT '',
  `default_credit_limit` decimal(10,2) DEFAULT 20.00,
  `cafe_public_ips` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `owner_password_hash`, `staff_pin_hash`, `currency_symbol`, `cafe_name`, `bank_image`, `order_host`, `default_credit_limit`, `cafe_public_ips`) VALUES
(1, '$2y$10$ouBAc8omL24ZdTKzNCsCzev1qYGlWlVb0PRSrkMG39bHaiwAMgZDi', '$2y$10$U1eomksT1F1qMPevLhM/rOFX.YloThxuMFe9vL.4c4jOlfkAqwerW', 'MVR', 'Ithaa Corner', 'bank.jpg', '192.168.18.8', 200.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `settlements`
--

CREATE TABLE `settlements` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(24) NOT NULL DEFAULT '',
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','online') NOT NULL,
  `payment_slip` varchar(80) NOT NULL DEFAULT '',
  `settled_by` int(10) UNSIGNED DEFAULT NULL,
  `settled_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settlements`
--

INSERT INTO `settlements` (`id`, `reference`, `customer_id`, `total_amount`, `payment_method`, `payment_slip`, `settled_by`, `settled_at`, `created_at`) VALUES
(2, 'S00002', 3, 42.00, 'cash', '', 9, '2026-10-25 15:20:06', '2026-10-25 15:20:06'),
(3, 'S00003', 14, 61.00, 'online', '', 9, '2026-10-26 15:06:39', '2026-10-26 15:06:39'),
(4, 'S00004', 45, 112.00, 'cash', '', 9, '2026-10-27 10:51:31', '2026-10-27 10:51:31'),
(5, 'S00005', 3, 385.00, 'online', '', 9, '2026-10-28 11:10:17', '2026-10-28 11:10:17'),
(6, 'S00006', 31, 482.00, 'online', '', 9, '2026-10-28 12:08:01', '2026-10-28 12:08:01'),
(7, 'S00007', 39, 187.00, 'online', '', 9, '2026-10-29 12:00:59', '2026-10-29 12:00:59'),
(8, 'S00008', 50, 21.00, 'online', '', 9, '2026-10-29 12:49:47', '2026-10-29 12:49:47'),
(9, 'S00009', 36, 31.00, 'online', '', 2, '2026-10-30 15:34:16', '2026-10-30 15:34:16'),
(10, 'S00010', 38, 95.00, 'online', '', 9, '2026-10-31 08:36:46', '2026-10-31 08:36:46'),
(11, 'S00011', 46, 122.00, 'online', '', 9, '2026-10-31 11:13:57', '2026-10-31 11:13:57'),
(12, 'S00012', 44, 94.00, 'cash', '', 9, '2026-11-01 13:38:47', '2026-11-01 13:38:47'),
(13, 'S00013', 37, 84.00, 'online', '', 9, '2026-11-01 14:51:18', '2026-11-01 14:51:18'),
(14, 'S00014', 14, 61.00, 'online', '', 9, '2026-11-01 15:52:17', '2026-11-01 15:52:17'),
(15, 'S00015', 52, 17.00, 'online', '', 9, '2026-11-02 06:55:02', '2026-11-02 06:55:02'),
(16, 'S00016', 12, 386.00, 'online', '', 2, '2026-11-02 13:06:00', '2026-11-02 13:06:00'),
(17, 'S00017', 37, 95.00, 'online', '', 9, '2026-11-04 11:00:59', '2026-11-04 11:00:59'),
(18, 'S00018', 31, 57.00, 'online', '', 9, '2026-11-04 11:25:36', '2026-11-04 11:25:36'),
(19, 'S00019', 41, 63.00, 'online', '', 9, '2026-11-04 15:05:22', '2026-11-04 15:05:22'),
(20, 'S00020', 50, 33.00, 'online', '', 9, '2026-11-05 11:59:21', '2026-11-05 11:59:21');

-- --------------------------------------------------------

--
-- Table structure for table `settlement_bills`
--

CREATE TABLE `settlement_bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `settlement_id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settlement_bills`
--

INSERT INTO `settlement_bills` (`id`, `settlement_id`, `sale_id`, `amount_applied`) VALUES
(4, 2, 226, 42.00),
(5, 3, 122, 24.00),
(6, 3, 182, 23.00),
(7, 3, 228, 7.00),
(8, 3, 290, 7.00),
(9, 4, 166, 31.00),
(10, 4, 333, 81.00),
(11, 5, 16, 59.00),
(12, 5, 73, 102.00),
(13, 5, 119, 163.00),
(14, 5, 403, 61.00),
(15, 6, 99, 415.00),
(16, 6, 386, 36.00),
(17, 6, 408, 31.00),
(18, 7, 121, 141.00),
(19, 7, 457, 46.00),
(20, 8, 378, 9.00),
(21, 8, 470, 12.00),
(22, 9, 117, 31.00),
(23, 10, 120, 95.00),
(24, 11, 286, 31.00),
(25, 11, 327, 26.00),
(26, 11, 445, 29.00),
(27, 11, 554, 36.00),
(28, 12, 146, 74.00),
(29, 12, 611, 20.00),
(30, 13, 118, 47.00),
(31, 13, 614, 37.00),
(32, 14, 356, 54.00),
(33, 14, 622, 7.00),
(34, 15, 600, 17.00),
(35, 16, 68, 101.00),
(36, 16, 136, 5.00),
(37, 16, 195, 19.00),
(38, 16, 204, 29.00),
(39, 16, 225, 7.00),
(40, 16, 253, 19.00),
(41, 16, 318, 74.00),
(42, 16, 433, 19.00),
(43, 16, 434, 5.00),
(44, 16, 475, 22.00),
(45, 16, 535, 12.00),
(46, 16, 599, 24.00),
(47, 16, 616, 36.00),
(48, 16, 671, 14.00),
(49, 17, 668, 58.00),
(50, 17, 782, 37.00),
(51, 18, 777, 38.00),
(52, 18, 787, 19.00),
(53, 19, 125, 27.00),
(54, 19, 795, 36.00),
(55, 20, 828, 14.00),
(56, 20, 838, 19.00);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `clock_in` datetime NOT NULL,
  `clock_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `user_id`, `clock_in`, `clock_out`) VALUES
(3, 3, '2026-10-22 11:35:06', NULL),
(4, 9, '2026-10-24 21:24:27', '2026-10-24 21:24:34'),
(5, 9, '2026-10-30 14:15:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supply_items`
--

CREATE TABLE `supply_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supply_lines`
--

CREATE TABLE `supply_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `week_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `requested_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `quote_qty` decimal(10,2) DEFAULT NULL,
  `quote_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supply_weeks`
--

CREATE TABLE `supply_weeks` (
  `id` int(10) UNSIGNED NOT NULL,
  `week_start` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'requested',
  `requested_by` int(10) UNSIGNED DEFAULT NULL,
  `quoted_by` int(10) UNSIGNED DEFAULT NULL,
  `accepted_by` int(10) UNSIGNED DEFAULT NULL,
  `expense_id` int(10) UNSIGNED DEFAULT NULL,
  `requested_at` datetime DEFAULT NULL,
  `quoted_at` datetime DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `expires_at`, `user_id`, `customer_id`) VALUES
(148, '70975a1f50353668b70a7dd18de279aa664c180ba9aa96d08acffd807297d8c4', '2026-11-07 18:00:48', 9, NULL),
(149, 'a661e7c0bd95783e884d1e9cbc4d30e6200bab1354c4ca031ed058fd47e55fbb', '2026-11-07 21:04:17', 3, NULL),
(150, 'd0de790ac070a2415ffbc7bd0babbc5f6da1c100329a072836ca8de32aa44353', '2026-11-07 22:30:22', 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(40) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `hourly_rate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password_hash`, `role`, `active`, `hourly_rate`) VALUES
(1, 'AdhuHaam', 'adhuhaam', '$2y$10$Pe7mopiOOFbrRcY9DXpf6OyRsHSXEHjsMoiXL8KQw0RbgOJ/unwUG', 'superadmin', 1, NULL),
(2, 'Suman', 'suman', '$2y$10$dtLy0K2g0vbABodyUg3qs.8wyRTXIqw71zBLn7L50qZmpwYCIVxdW', 'waiter', 1, NULL),
(3, 'Sakib', 'sakib', '$2y$10$dlX8ImiG9A90pSxayb6fSumAOcgmv0VL5y.27bABLAN8wJqr9aDkO', 'waiter', 1, NULL),
(9, 'manager', 'manager', '$2y$10$wBkptzadSYRi1zqNQvc/oO1VrsNs7psNT3LvKuFx2A6qVtKjumShu', 'manager', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_categories`
--
ALTER TABLE `book_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_categories_name` (`name`);

--
-- Indexes for table `book_days`
--
ALTER TABLE `book_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_days_date` (`sale_date`);

--
-- Indexes for table `book_expenses`
--
ALTER TABLE `book_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_expenses_date` (`spent_on`);

--
-- Indexes for table `book_partners`
--
ALTER TABLE `book_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_people`
--
ALTER TABLE `book_people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book_recurring`
--
ALTER TABLE `book_recurring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_recurring_category` (`category_id`);

--
-- Indexes for table `book_recurring_months`
--
ALTER TABLE `book_recurring_months`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_recurring_month` (`recurring_id`,`year_num`,`month_num`);

--
-- Indexes for table `book_salaries`
--
ALTER TABLE `book_salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `book_salary_month` (`person_id`,`year_num`,`month_num`);

--
-- Indexes for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cafe_tables_area` (`area_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_phone` (`phone`);

--
-- Indexes for table `floor_areas`
--
ALTER TABLE `floor_areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `floor_areas_name` (`name`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_category` (`category_id`);

--
-- Indexes for table `open_tables`
--
ALTER TABLE `open_tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `open_tables_sale` (`sale_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug` (`slug`);

--
-- Indexes for table `role_access`
--
ALTER TABLE `role_access`
  ADD PRIMARY KEY (`role_id`,`area`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_closed` (`closed_at`),
  ADD KEY `sales_table_status` (`table_id`,`status`);

--
-- Indexes for table `sale_lines`
--
ALTER TABLE `sale_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_lines_sale` (`sale_id`),
  ADD KEY `sale_lines_item_fk` (`item_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settlements`
--
ALTER TABLE `settlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settlements_reference` (`reference`);

--
-- Indexes for table `settlement_bills`
--
ALTER TABLE `settlement_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settlement_bills_sale` (`sale_id`),
  ADD KEY `settlement_bills_settlement` (`settlement_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_user_open` (`user_id`,`clock_out`),
  ADD KEY `shifts_user_in` (`user_id`,`clock_in`);

--
-- Indexes for table `supply_items`
--
ALTER TABLE `supply_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_lines`
--
ALTER TABLE `supply_lines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supply_lines_item` (`week_id`,`item_id`);

--
-- Indexes for table `supply_weeks`
--
ALTER TABLE `supply_weeks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supply_weeks_start` (`week_start`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tokens_token` (`token`),
  ADD KEY `tokens_customer` (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_categories`
--
ALTER TABLE `book_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `book_days`
--
ALTER TABLE `book_days`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `book_expenses`
--
ALTER TABLE `book_expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `book_partners`
--
ALTER TABLE `book_partners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `book_people`
--
ALTER TABLE `book_people`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `book_recurring`
--
ALTER TABLE `book_recurring`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book_recurring_months`
--
ALTER TABLE `book_recurring_months`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `book_salaries`
--
ALTER TABLE `book_salaries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `floor_areas`
--
ALTER TABLE `floor_areas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1848509;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=903;

--
-- AUTO_INCREMENT for table `sale_lines`
--
ALTER TABLE `sale_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2538;

--
-- AUTO_INCREMENT for table `settlements`
--
ALTER TABLE `settlements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `settlement_bills`
--
ALTER TABLE `settlement_bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supply_items`
--
ALTER TABLE `supply_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supply_lines`
--
ALTER TABLE `supply_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supply_weeks`
--
ALTER TABLE `supply_weeks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `open_tables`
--
ALTER TABLE `open_tables`
  ADD CONSTRAINT `open_tables_sale_fk` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `open_tables_table_fk` FOREIGN KEY (`table_id`) REFERENCES `cafe_tables` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_table_fk` FOREIGN KEY (`table_id`) REFERENCES `cafe_tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_lines`
--
ALTER TABLE `sale_lines`
  ADD CONSTRAINT `sale_lines_item_fk` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_lines_sale_fk` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


Point of sale for Ithaa Corner. It runs on this PC in XAMPP. Waiters use a phone app on the cafe Wi-Fi. The owner uses the admin site on this computer, or from another device on Tailscale.

The cafe is not GST-registered. Bills are receipts. There is no tax, no inventory, and no receipt printer. Currency is MVR. The clock is Maldives time (`Indian/Maldives`, UTC+5).

Dates on screen are `DD MMM YYYY`, for example `21 Oct 2026`. Times are `21 Oct 2026 8:32 PM`. Reports, sales, books, expenses, and hours use a calendar picker for choosing a day or month. Amounts show two decimals and thousands commas, for example `MVR 1,234.50`. Typed amounts may include commas (`1,234.50` or `1000`). Stored amounts stay plain decimals.

## Where to open it

| Who | Address |
| --- | --- |
| This PC | `http://localhost/pos/` |
| Waiter phone on cafe Wi-Fi | `http://192.168.18.8/pos/waiter/` |
| Owner over Tailscale | `http://100.116.152.20/pos/` |

`192.168.18.8` is this PC’s cafe-network address and can change when the router hands out a new one. Check it with `ipconfig`. The Tailscale address is this machine (`ithaa`, `ithaa.snowy-pride.ts.net`) and is not the public internet.

`http://localhost/pos/` sends a signed-in superadmin to the admin site and a signed-in waiter to the phone app. Everyone else goes to the shared sign-in page.

## Sign in

One sign-in page: `http://localhost/pos/login.php`.

- **Superadmin** opens the admin site and can change every module.
- **Manager** opens the phone app, with the same floor as a waiter, and can add an expense or a supply from that app. The admin site stays with the superadmin.
- **Waiter** opens the phone app and keeps a token on that phone.

A brand-new database creates two accounts the first time the site runs, and only if they are missing:

- Superadmin `admin` / `admin`
- Waiter `waiter` / `1234`

Change both before service, under **Users**. This cafe’s superadmin username is `adhuhaam`. Use the password saved on that account. Any waiter still using `1234` should get a new password.

The last active superadmin cannot be demoted, deactivated, or deleted. The account you are signed in with cannot be deleted.

## Who can connect

Waiter and API requests are accepted from:

- this PC (`127.0.0.1`)
- cafe Wi-Fi (`10.x`, `172.16–31.x`, `192.168.x`)
- Tailscale (`100.64–127.x`)

A connection from the public internet is refused. The admin pages are for this PC and Tailscale.

Windows Firewall must allow Apache on private networks (inbound TCP port 80). If a phone cannot open the app, that rule is the first thing to check.

## Waiter app

Install it from Chrome on the phone: menu, then **Install app** or **Add to Home screen**. On iPhone, use Share, then Add to Home Screen. The icon is the Ithaa shell. Installed, it opens without the browser bar, stays upright, and does not zoom. Hold the phone upright. Landscape shows a turn-the-phone message.

The app works in the browser on cafe Wi-Fi. Offline caching of the app shell starts on `localhost` or HTTPS. A plain `http://192.168…` address still runs the sales screens; it does not cache the shell for offline use.

English is the standard language. **বাং / EN** switches the waiter’s own buttons and labels. Receipts, print, and share stay in English. On an open bill in Bangla, the English item name stays first and the Bangla name is a small line under it.

Tabs along the bottom:

### Tables

Hut 1–4, Outdoor 1–4, and Indoor 1, grouped by area. Tap a free table, add items, then leave it. The timer starts when the first item is added.

A table that already has items belongs to the waiter who started it. Another waiter sees “This table is already open” and cannot add to it. An empty open table can be claimed. A superadmin can edit any open table.

### Items

The menu, with a photo, name, and price when a photo has been added. Items are added only after that waiter has their own table or an editable credit bill. Hidden items stay off this list.

### Bill

Every open table bill, refreshed every few seconds. The waiter who owns it can add items, change quantities, and take payment. Other waiters can view it. **Close** leaves the screen. The bill stays on the table until it is paid. An empty table with no items is cleared.

### Credit

Unpaid credit bills: customer, phone, table, item count, date, and the amount still open. Search by name or phone. Open a bill to add items or take cash or bank. Cash or bank closes it. There is no second Credit button on a bill that is already on credit.

If the customer has a limit, it is shown here and when that customer is chosen. A blank limit means no cap. `0.00` means nothing more can be put on credit. Adding items that would push the open total past the limit is blocked. Paying the bill down is always allowed. Lowering a limit does not erase what is already owed.

### Sales

A dashboard for the selected day. Move a day at a time. Future days are not available.

The top shows the day’s closed sales, the bill count, and a bar for cash, bank, and credit. Three tiles filter the list: **Cash**, **Bank**, and **Credit/Hold**. Tap the total to show every bill again. Tap the same tile again to clear that filter. **Day**, **Week**, and **Month** switch the range. Each range shows sales, expenses, Credit/Hold, net sales, and profit, with a small chart. Week and month also list each day and the expenses. A month includes that month’s salaries. Recorded transfer and cash from the books are shown when a period has them, and they are part of profit. Open credit is not part of sales or profit.

Credit on this dashboard is still open and is outside the sales total. Each credit row shows the customer, phone, table, time, limit, and that it is still open. Opening it goes to that bill. **Close** returns to Sales.

A closed bill opens as a receipt. The receipt shows when the bill was opened, when it was paid, and the payment. Items are grouped by the day they were added, so a Credit/Hold bill that grows over more than one day keeps those days. **Share** sends a picture (WhatsApp, Viber, or a saved file named like `ithaa-receipt.png`). **Print** uses the phone’s print dialog, laid out for an 80 mm slip. A bill opened on one day and paid after midnight counts as sales on the payment day.

## Taking payment

On a table bill the waiter chooses **Cash**, **Bank**, or **Credit/Hold**. Credit/Hold keeps the bill with the customer until cash or bank is taken. Those bills are the held bills. The receipt is an old-style bill: bill number from the sale, table, opened and paid times, payment, and a ruled item table with rate and amount. A manager has an Expenses tab for expenses and supplies. A waiter does not. A superadmin sets roles and their access on Users.

**Cash** asks for confirmation, then closes the bill and frees the table. The bill cannot be changed after that.

**Bank** shows the total and the QR or account photo from **Settings**. The customer scans it. The waiter can attach a slip with **Take photo** or **Gallery**, then tap **Customer paid**. The slip is optional. The bill closes without one. Slips are JPEG, up to 8 MB, and are shown on the admin sales cards.

**Credit** asks **Existing customer** or **New customer**.

- Existing: search by name or phone, see what is still open, tap the customer.
- New: name and phone. The phone is 5 to 15 digits and is unique.

The bill stays with that customer, the table is freed, and items can still be added until cash or bank is taken. Credit is not income until it is paid.

**Combine / Settle Bills** is on an open table bill and on a credit bill. The waiter can include or skip the current bill, search a customer by name or phone, and tick any of that customer's open credit bills. Cash or bank settles the ticked bills in one payment. Unticked bills stay as they are. The table is freed only when the current table bill is included. Each bill keeps its own number, items, and date. The receipt lists those bills under one total. Admin sales show the settlement number, such as S00012. Cancelling the screen does not change the bill.

## Receipts

The slip shows:

- Ithaa Corner
- Receipt
- Table, date, and time
- Server, when a waiter name is stored
- Customer, when the bill has one
- Item name, quantity, and line price
- Total
- CASH, BANK, or CREDIT
- THANK YOU

It does not show an address, phone number, tax, invoice title, barcode, cash tendered, or change. Those were never supplied.

## Admin site

Open `http://localhost/pos/admin/` after signing in as superadmin. The sidebar groups are Register, Menu, Floor, Cafe, and Books.

### Today

Open tables, open bills, today’s ticket count, total, cash, bank, and **Held, inside total**. Held is the part of today’s total that came from credit bills paid today. It is already inside the total. The list under the cards is today’s closed bills, with a Held mark and the customer name when those apply.

### Sales

The same figures for any day. The date is a date picker, and the arrows move one day. It opens on today. Filter the list by cash, bank, or credit, and search by table or item. Each card lists the lines and a bank slip when one was saved.

A superadmin can add a sale for a chosen day. Pick a table, cash, bank, or credit, and up to five menu items. Prices come from the menu. Cash and bank join that day’s sales. Credit stays open and needs a customer. A manager cannot add a sale. The bill does not open the table.

Held credit stays off the books until it is paid. It is then added to that day’s sales.

### Customers

A superadmin can add a customer here, with a name, phone, and an optional credit limit. Each credit customer shows what is still open, how much room is left, and a limit field. Save a limit such as `1,000.00`. Leave it blank for no cap. A row is marked when the open amount is already over the limit. Waiters can still add a customer while putting a bill on credit. Adding a customer does not create a sale.

### Items

The menu, grouped like the waiter app. Each group has its own shade. An item only says On menu or Hidden, in plain text. Each card shows the name, price, and photo. Edit, hide or show, and move it up or down inside its category. Search, category chips, and On menu / Hidden filters sit above the list. New items are added at the top.

**Photo** is optional. Choose a JPG, PNG, or WebP under 8 MB on the item form. The waiter menu shows it beside the item. A new photo replaces the old one. Tick **Remove the current photo** and save to take it off. Old bills do not show the photo.

Hiding an item removes it from the waiter menu. Old bills keep the name and price they were sold at. Old demo items stay in the database and stay hidden, so past sales are not deleted.

Current menu, in MVR:

| Item | Price |
| --- | --- |
| Black Coffee Hard | 10 |
| Black Coffee Normal | 7 |
| Milk Coffee | 10 |
| Cereal Coffee | 15 |
| Nuts Half | 5 |
| Nuts Full | 10 |
| Hedhika | 5 |
| Water Bottle Small | 5 |
| Water Bottle Big | 10 |
| Bisket | 5 |
| Lemon Tea | 10 |
| Black Tea Large | 5 |
| Black Tea Small | 3 |

### Categories

Drink and food groups for the menu, shown as cards. Each card opens that group on Items. Order here is the order waiters see.

### Tables

Add, rename, reorder, or hide a table. A hidden table stays off the floor unless it still has an open bill. The floor groups names that start with Hut, Outdoor, and Indoor.

### Users

Name, username, password, role (superadmin, manager, or waiter), and active. Usernames are stored in lowercase. A manager is created here by a superadmin.

### Settings

Currency symbol and the bank photo. The photo is what waiters show on the Bank sheet. Prices and receipts use the symbol. For this cafe it is MVR.

## Books

POS bills that are paid are added on their own, on the day and month of `closed_at`. The spreadsheet days are extra, for transfer and cash that were recorded by hand.

**Income** for a month is POS sales plus recorded transfer and cash.

**Expenses** are salaries plus Supply, Rent, Utility bills, Other, and any category you add.

**Profit** is income minus expenses.

Open credit is left out of income. When that bill is paid, the amount joins the settlement day and month as cash or bank, and the bill is marked Held.

### Dashboard

Year table of POS, transfer, cash, sales, supplies, salary, rent, utilities, other, and profit. The page opens on this month, and this month is first in the month list and the year table. The selected month also has its own summary above the table. Pick another month to edit that month’s recorded days. The day field is a date picker. Saving a date that already exists updates that day. Leave both amounts empty, or enter 0, to clear the day.

The old sales sheet is already entered by date. Each of those days is marked **Previous book** and keeps its transfer and cash. Register bills stay separate. Electricity and rent from that sheet are expenses on the first of the month, noted Previous book. Suman’s August and September salary from that sheet is on Salaries.

Partners have a name and a share percent. The year profit is split by those percents. Shares are edited on this page.

### Salaries

Add a person with a name and designation. Each person has twelve month amounts for the selected year. On the current year, this month is the first amount. Save updates the name, designation, and the months. Remove deletes that person from the books.

**Hours** is under Cafe. It opens on this month, with a month picker. Set an hourly rate, pick the Salaries person, and **Save into Salaries** writes that one month. Other months stay as they are. An open shift counts until clock-out and stops at 16 hours if the phone is left clocked in.

### Expenses

The list opens on this month. Use the month picker to change it, or clear the month and press Show for the whole year. Add or edit a date, category, kind, amount, and note. The date is a date picker. A manager adds an expense or a supply from the phone app, and does not open this site. **Edit** is on each row. **Proof** is an optional photo or PDF (JPG, PNG, WebP, GIF, or PDF, up to 8 MB), meant for a supply receipt and available on any category. The list and the monthly report open the file. Editing keeps the current file unless a new one is chosen or **Remove the current proof** is ticked. Removing the expense removes the file.

Categories start as Supply, Rent, Utility bills, and Other. More categories can be added on the expenses page.

Proof files sit outside the web root. Only a signed-in superadmin can open them.

### Reports

Opens on this month. The month control is a month picker. Cards show income, expenses, profit, POS, recorded days, credit opened this month, and credit still open across all months.

**Daily sales** lists each day that had cash, bank, or open credit. **Sales** lists each bill. Open credit uses the day the bill was opened and is marked Open. A paid bill that began as credit is marked Held and uses the day it was paid.

**Item sales** is under the month picker. From and To are date pickers and start as the first and last day of the month on screen. Clear **To**, or set it to the same day, for a single date. The result is quantity, amount, bill count, a row per date, and each bill that included the item. Hidden items remain in the list so old sales can still be filtered. Paid bills use the day they were closed. Open credit uses the day the bill was opened.

## Setup

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. The site folder is `C:\xampp\htdocs\pos`, served as `http://localhost/pos`. The working copy in Cursor is `C:\Users\User\Desktop\pos`. Copy changed files into the XAMPP folder or the live site keeps the old copy.
3. In phpMyAdmin (`http://localhost/phpmyadmin`), import `database/schema.sql` into a new database. If this cafe is already running, do not import that file again. Opening the admin site once updates older databases (credit, held bills, book tables, proof column).
4. Database settings are in `api/config.php`: host `127.0.0.1`, database `cafe_pos`, user `root`, empty password, timezone `Indian/Maldives`. Set `db_pass` if MySQL has a password.
5. Open `http://localhost/pos/login.php`.

Apache here is XAMPP’s `httpd.exe` (Apache 2.4 with PHP 8.2), not a Windows service named Apache2.4. PHP is `C:\xampp\php\php.exe`.

## What is stored

- **Users** and sign-in tokens. Roles are superadmin, manager, and waiter. An optional hourly rate sits on the user. Clock in and clock out are stored as shifts.
- **Menu** categories and items, with a hidden flag and a sort order.
- **Tables** and which table currently holds an open bill.
- **Sales** with lines. Status is open, credit, or paid. Payment is cash, bank (`online` in the database), or credit. `closed_at` is set when cash or bank is taken, and that day is the sales day. Credit keeps `closed_at` empty and `held` on until it is paid. Each line stores `added_at`, the day that quantity was added. Adding the same item on a later day keeps a separate line.
- **Customers** with name, unique phone, and an optional credit limit. A blank limit is no cap.
- **Books:** partners, recorded days, people, monthly salaries, expense categories, and expenses with an optional proof file.
- **Uploads** under `data/uploads/` (item photos, bank photo, payment slips, expense proofs). The folder is blocked by `.htaccess`. Admin opens files through `admin/media.php`. Waiters load item photos through the API.

Money is stored as decimal maloti and calculated in cents so `1,234.50` cannot drift.

## Day to day

1. Start Apache and MySQL in XAMPP.
2. Waiters open the installed app, or `http://192.168.18.8/pos/waiter/` if the address is still that one.
3. They take tables, cash, bank, and credit from the phone. **Light** switches the phone to a light theme. **Account** changes the signed-in name and password. **Clock in** and **Clock out** record the shift for Hours.
4. You review Today, Sales, Customers, and Reports on this PC.
5. You enter salaries, supplies, rent, and other costs under Books. Attach a photo or PDF when a supply needs proof.
6. Held credit shows up in sales on the day it is paid, not on the day the tab was opened.
