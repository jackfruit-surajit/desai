-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 06, 2026 at 07:09 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desaibesthr_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_id` bigint(20) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `image` text DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `m_pin` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `activation_token` text DEFAULT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `type_id`, `role_id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `image`, `area_id`, `vehicle_id`, `m_pin`, `remember_token`, `activation_token`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Admin', 'admin@admin.com', '9876543210', NULL, '$2y$10$7pc6FU.fN/koNZkCyDMoSOuZK4EI/JQqmuICTseHKO.doywx/iT.u', 'UBnYHOMKavg3xu.png', NULL, NULL, NULL, NULL, NULL, '1', '2026-10-06 19:05:45', '2024-07-11 05:29:36', '2026-10-06 13:35:45'),
(32, 2, 2, 'Sales Executive', 'sales100@gmail.com', '9876789023', NULL, '$2y$10$7pc6FU.fN/koNZkCyDMoSOuZK4EI/JQqmuICTseHKO.doywx/iT.u', 'O9SkJv4CZ2uxlG.jpg', NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, '2026-10-05 11:21:09'),
(33, 2, 3, 'Surajit M', 'driver100@gmail.com', '9876543211', NULL, '$2y$10$7pc6FU.fN/koNZkCyDMoSOuZK4EI/JQqmuICTseHKO.doywx/iT.u', 'hx885jpLn04qZw.jpg', 2, 7, NULL, NULL, NULL, '1', NULL, NULL, '2026-10-05 12:41:29'),
(35, 2, 4, 'AB Roy', 'ab100@gmail.com', '9551883025', NULL, '$2y$10$480uzvTpXRJBEcsoFfBAT.ZAgd1C.P1ducIpL0ubC9sIST1cYQo3C', '0grSdZYJCA6E9V.jpg', NULL, NULL, NULL, NULL, NULL, '1', '2026-09-28 14:37:08', '2026-09-22 06:59:52', '2026-09-28 09:07:08'),
(36, 2, 5, 'Manu Das', 'manufacture100@gmail.com', '6551883025', NULL, '$2y$10$480uzvTpXRJBEcsoFfBAT.ZAgd1C.P1ducIpL0ubC9sIST1cYQo3C', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2026-09-26 21:18:06', '2026-09-22 07:01:21', '2026-09-26 15:48:06'),
(37, 2, 4, 'South Goa Warehouse', 'sgw100@gmail.com', '9876545609', NULL, '$2y$10$r9sxXRtkdMtO5svXhIklaOHbpcZV55hphu0Z1y4W4lLUhH.jRyIGi', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '2026-09-26 05:22:48', '2026-10-02 10:56:31'),
(38, 2, 3, 'Test Driver', 'test100@gmail.com', '7551883025', NULL, '$2y$10$KfJ03KVJFjsxDYYDbsSCsutmU68wI7QJCc.yUMXs5yW1VB4/IpbzG', NULL, NULL, 4, NULL, NULL, NULL, '1', NULL, '2026-10-02 10:49:18', '2026-10-02 10:53:07'),
(39, 2, 2, 'Dhruv Dabolkar', 'dhruvsales@gmail.com', '9637881199', NULL, '$2y$10$i7PRHYlMnWRHdgansWBbLOw6xsD1T9Mzpfqv82PG30sfqYzoq3L5a', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '2026-10-03 10:26:52', '2026-10-03 10:32:25'),
(40, 2, 2, 'Sachin Velip', 'sachinsales@gmail.com', '9637401199', NULL, '$2y$10$XWrlx1Nu7MeW3.rGXfASS.DE8X3B./PSshW6i3GQII9b8jnsI834q', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '2026-10-03 10:29:28', '2026-10-03 10:32:42'),
(41, 2, 2, 'Pratiksha Gaonkar', 'pratikshasales@gmail.com', '9637331199', NULL, '$2y$10$0SzdOS87gdOq/SkVUK4L5.B38kVdFqtQZQgJmWWKi5kg.urB7.q1y', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, '2026-10-03 10:30:53', '2026-10-03 10:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `area_name` varchar(555) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `area_name`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Benaulim', 1, '2026-10-02 09:40:55', '2026-10-02 09:40:55'),
(7, 'Mandrem', 1, '2026-10-02 09:43:18', '2026-10-02 09:43:18'),
(8, 'Pernem', 1, '2026-10-02 09:43:31', '2026-10-02 09:43:31'),
(9, 'Bicholim', 1, '2026-10-02 09:43:41', '2026-10-02 09:43:41'),
(10, 'Thivim', 1, '2026-10-02 09:43:52', '2026-10-02 09:43:52'),
(11, 'Mapusa', 1, '2026-10-02 09:44:04', '2026-10-02 09:44:04'),
(12, 'Siolim', 1, '2026-10-02 09:44:27', '2026-10-02 09:44:27'),
(13, 'Saligao', 1, '2026-10-02 09:44:37', '2026-10-02 09:44:37'),
(14, 'Calangute', 1, '2026-10-02 09:44:48', '2026-10-02 09:44:48'),
(15, 'Porvorim', 1, '2026-10-02 09:45:00', '2026-10-02 09:45:00'),
(16, 'Aldona', 1, '2026-10-02 09:45:09', '2026-10-02 09:45:09'),
(17, 'Panaji', 1, '2026-10-02 09:45:21', '2026-10-02 09:45:21'),
(18, 'Talegaon', 1, '2026-10-02 09:45:44', '2026-10-02 09:45:44'),
(19, 'St. Cruz', 1, '2026-10-02 09:45:55', '2026-10-02 09:45:55'),
(20, 'St. Andre', 1, '2026-10-02 09:46:07', '2026-10-02 09:46:07'),
(21, 'Cumbarjua', 1, '2026-10-02 09:46:20', '2026-10-02 09:46:20'),
(22, 'Mayem', 1, '2026-10-02 09:46:31', '2026-10-02 09:46:31'),
(23, 'Sanquelim', 1, '2026-10-02 09:46:44', '2026-10-02 09:46:44'),
(24, 'Poriem', 1, '2026-10-02 09:46:54', '2026-10-02 09:46:54'),
(25, 'Valpoi', 1, '2026-10-02 09:47:02', '2026-10-02 09:47:02'),
(26, 'Priol', 1, '2026-10-02 09:47:15', '2026-10-02 09:47:15'),
(27, 'Ponda', 1, '2026-10-02 09:47:27', '2026-10-02 09:47:27'),
(29, 'Marciam', 1, '2026-10-02 09:47:50', '2026-10-02 09:47:50'),
(30, 'Marmugao', 1, '2026-10-02 09:48:02', '2026-10-02 09:48:02'),
(31, 'Vasco', 1, '2026-10-02 09:48:11', '2026-10-02 09:48:11'),
(32, 'Dabolim', 1, '2026-10-02 09:48:24', '2026-10-02 09:48:24'),
(33, 'Cortalim', 1, '2026-10-02 09:48:36', '2026-10-02 09:48:36'),
(34, 'Nuvem', 1, '2026-10-02 09:48:47', '2026-10-02 09:48:47'),
(35, 'Curtorim', 1, '2026-10-02 09:48:57', '2026-10-02 09:48:57'),
(36, 'Fatorda', 1, '2026-10-02 09:49:05', '2026-10-02 09:49:05'),
(37, 'Margao', 1, '2026-10-02 09:49:16', '2026-10-02 09:49:16'),
(38, 'Navelim', 1, '2026-10-02 09:49:28', '2026-10-02 09:49:28'),
(39, 'Cuncolim', 1, '2026-10-02 09:49:38', '2026-10-02 09:49:38'),
(40, 'Velim', 1, '2026-10-02 09:49:46', '2026-10-02 09:49:46'),
(41, 'Quepem', 1, '2026-10-02 09:50:03', '2026-10-02 09:50:03'),
(42, 'Curchorem', 1, '2026-10-02 09:50:17', '2026-10-02 09:50:17'),
(43, 'Sanvordem', 1, '2026-10-02 09:50:28', '2026-10-02 09:50:28'),
(44, 'Sanguem', 1, '2026-10-02 09:50:43', '2026-10-02 09:50:43'),
(45, 'Canacona', 1, '2026-10-02 09:50:56', '2026-10-02 09:50:56'),
(46, 'Majali', 1, '2026-10-02 09:51:08', '2026-10-02 09:51:08'),
(47, 'Karwar', 1, '2026-10-02 09:51:20', '2026-10-02 09:51:20'),
(48, 'Ankola', 1, '2026-10-02 09:51:29', '2026-10-02 09:51:29'),
(49, 'Shiroda', 1, '2026-10-02 11:35:58', '2026-10-02 11:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` longtext DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `short_description` longtext DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `short_description`, `status`, `created_at`, `updated_at`) VALUES
(2, 'JackFruitTechnologies Ltd', '7o5VZQSCsx1qEc.jpeg', 'Laravel provides robust built-in validation rules for image uploads, which can be used in your controllers or Form Requests', '1', '2026-01-29 12:15:58', '2026-01-29 12:15:58'),
(3, 'Test', 'l9w9p9ItsOPJ8V.jpeg', 'Test', '1', '2026-01-29 12:16:34', '2026-01-29 12:16:34');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) NOT NULL,
  `college_id` bigint(20) DEFAULT NULL,
  `course_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `college_id`, `course_id`, `name`, `email`, `phone`, `city`, `state`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(7, 4, 3, 'Raju Debnath', 'test@gmail.com', '9787989788', 'Hooghly', 'West Bengal', NULL, NULL, '2025-04-02 13:11:09', '2025-04-02 13:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `first_name` varchar(555) DEFAULT NULL,
  `last_name` varchar(555) DEFAULT NULL,
  `email` varchar(555) DEFAULT NULL,
  `password` varchar(555) DEFAULT NULL,
  `gender` varchar(555) DEFAULT NULL,
  `mobile_no` varchar(191) DEFAULT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `city` varchar(555) DEFAULT NULL,
  `pin_code` varchar(555) DEFAULT NULL,
  `landmark` longtext DEFAULT NULL,
  `address` varchar(555) DEFAULT NULL,
  `shop_name` varchar(555) DEFAULT NULL,
  `shop_photo` varchar(555) DEFAULT NULL,
  `gst_no` varchar(555) DEFAULT NULL,
  `pan_no` varchar(555) DEFAULT NULL,
  `latitude` varchar(555) DEFAULT NULL,
  `longitude` varchar(555) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `road_id` int(11) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT '1',
  `sequence` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `first_name`, `last_name`, `email`, `password`, `gender`, `mobile_no`, `photo`, `state`, `city`, `pin_code`, `landmark`, `address`, `shop_name`, `shop_photo`, `gst_no`, `pan_no`, `latitude`, `longitude`, `area_id`, `road_id`, `status`, `sequence`, `created_by`, `created_at`, `updated_at`) VALUES
(7, 'Surajit Mondal', 'Surajit', 'Mondal', 'surajitmondal1800@gmail.com', '$2y$10$7pc6FU.fN/koNZkCyDMoSOuZK4EI/JQqmuICTseHKO.doywx/iT.u', NULL, '7551883025', NULL, 'West bengal', 'Kolkata', '743357', NULL, 'Narendrapur, Kolkata', 'Kakali Furniture', NULL, '8392498234832', 'HGS786HGS', NULL, NULL, NULL, NULL, '1', 2, 33, '2026-06-02 06:57:32', '2026-09-01 14:50:02'),
(8, 'Kane Glover', NULL, NULL, 'your.email+fakedata48525@gmail.com', '$2y$10$nsMlydFzVJVsTOHU6hqZ6e7fyj4emflE3Wc8MeLud60nUw4sMaszW', NULL, '4014795689', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 1, 33, '2026-06-02 06:59:24', '2026-09-01 14:50:02'),
(9, 'Ollie Zieme', NULL, NULL, 'surajitmondal1800@gmail.com', '$2y$10$YtWieneYYu9Q6xWGJd2xuOdR2AuW4iWKK9aTgy8SC7cS22.7Rk64y', NULL, '7016043900', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 3, 33, '2026-06-02 07:13:34', '2026-06-02 07:13:34'),
(10, 'Sougat Basu', 'Sougat', 'Basu', 'sougat@gmail.com', '$2y$10$H5qAR.s/43eRr.8gyrVma.v/kg3sj7.Czh3c5HLZnXwY8Kt4TFnO.', NULL, '9898989890', NULL, NULL, NULL, NULL, NULL, 'Narendrapur', 'JackFruit Web', 'Yg7fFe4s921JES.jpg', NULL, NULL, '8765435', '87657', 1, 1, '1', 4, 33, '2026-07-18 13:32:07', '2026-08-08 12:29:33'),
(11, 'Surajit Mondal', NULL, NULL, NULL, NULL, NULL, '9876789098', NULL, NULL, NULL, NULL, NULL, 'Rajnagar, Namkhana', 'Snehalata Motors', 'WCMe1sF8NaEqb7.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', 5, 33, '2026-08-10 08:12:45', '2026-08-10 08:12:45'),
(12, 'SBS', NULL, NULL, NULL, NULL, NULL, '8888888888', NULL, NULL, NULL, NULL, NULL, 'mera address', 'Shop Namer', '26qixh8lw1mvJG.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', 6, 33, '2026-08-19 12:47:33', '2026-08-19 12:47:33'),
(13, 'mera name', NULL, NULL, 'ramchandra@evolute-cleantech.in', NULL, NULL, '9999999999', NULL, NULL, NULL, NULL, NULL, 'mera address', 'mera store', '1G6Di37XeP1SOs.jpg', '45465433212345', NULL, '8765435', '87657', 2, 1, '1', 7, 33, '2026-08-29 14:06:56', '2026-09-03 10:33:02'),
(14, 'Laron Mohr', NULL, NULL, 'your.email+fakedata10833@gmail.com', NULL, NULL, '3619638861', NULL, NULL, NULL, NULL, NULL, '62858 Kyle Summit', 'Jesse Dare', 'LSykM1OE1t12vl.jpg', '410', NULL, 'D\'Amore', 'Aut sortitus ceno suscipit aegre admiratio absorbeo mollitia temporibus cui.', 1, 4, '1', 8, NULL, '2026-09-03 10:34:11', '2026-09-03 10:34:11'),
(15, 'SP Ray', NULL, NULL, 'sm100@gmail.com', NULL, NULL, '9876787090', NULL, 'West Bengal', 'kolkata', '876678', 'Rajnagar biswambhar high school', 'VIP Road 1, Goa, Rajnagar biswambhar high school, West Bengal, kolkata, 876678', 'SM Motors', 'Nmx1q2pZT5Mj3c.jpg', '987657876567', 'JHGGH76GHH', '8765789', '8765678', NULL, 1, '1', NULL, 33, '2026-09-03 14:18:25', '2026-09-03 14:18:25'),
(16, 'hello bs', NULL, NULL, NULL, NULL, NULL, '9898989898', NULL, 'Goa', 'Mountain View', '940435', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'VIP Road 1, Goa, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, Goa, Mountain View, 940435', 'Apna kirana', 'b0GqEO624dcu3M.jpg', 'ABCV2745GFGFGFG', 'EABCF2592J', '37.4219983', '-122.084', NULL, 1, '1', 6, 33, '2026-09-06 09:31:52', '2026-09-06 09:31:52'),
(17, 'Mera shop', NULL, NULL, NULL, NULL, NULL, '8888888888', NULL, 'Goa', 'Mountain View', '741548', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'VIP Road 1, Goa, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, Goa, Mountain View, 741548', 'Shop 1', 'i9MzpudV0hfe8g.jpg', '27FBDFBJFJ155AD', 'EBHSV1299J', '37.4219983', '-122.084', NULL, 1, '1', 7, 33, '2026-09-07 16:36:00', '2026-09-07 16:36:00'),
(18, 'Test Name', NULL, NULL, NULL, NULL, NULL, '7654678790', NULL, 'Goa', 'Goa', '987689', 'Mobile tower', 'VIP Road 1, Goa, Mobile tower, Goa, Goa, 987689', 'Mobile care', '4let9Tn3GzA1or.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', 8, 32, '2026-09-17 12:11:03', '2026-09-17 12:11:03'),
(19, 'Test Name', NULL, NULL, NULL, NULL, NULL, '7654678790', NULL, 'Goa', 'Goa', '987689', 'Mobile tower', 'VIP Road 1, Goa, Mobile tower, Goa, Goa, 987689', 'Realme Mobile care', 'T9xL1H8KBIzm6R.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', NULL, 32, '2026-09-17 12:13:00', '2026-09-17 12:13:00'),
(20, 'Test Name', NULL, NULL, NULL, NULL, NULL, '7654678790', NULL, 'Goa', 'Goa', '987689', 'Mobile tower', 'VIP Road 1, Goa, Mobile tower, Goa, Goa, 987689', 'Realme Mobile care-2', 'Jl4Mvrfj1kgATN.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', NULL, 32, '2026-09-25 09:06:34', '2026-09-25 09:06:34'),
(21, 'Test Name', NULL, NULL, NULL, NULL, NULL, '7654678790', NULL, 'Goa', 'Goa', '987689', 'Mobile tower', 'VIP Road 1, Goa, Mobile tower, Goa, Goa, 987689', 'TMT Mobile care-2', 'qY40J91OC91x37.jpg', NULL, NULL, NULL, NULL, NULL, 1, '1', 1, 32, '2026-09-26 08:30:14', '2026-09-26 08:30:14'),
(22, 'suchita', NULL, NULL, NULL, NULL, NULL, '7745073187', NULL, 'Goa', 'Jua', '403106', 'Akhada H. No. 1127, St. Estevam, Jua, Golwada, Goa 403106, India', 'St. Estev, Priol, Akhada H. No. 1127, St. Estevam, Jua, Golwada, Goa 403106, India, Goa, Jua, 403106', 'suchita general store', '77G5ABDdMnp6vx.jpg', NULL, NULL, '15.53508', '73.9329517', NULL, 122, '1', 2, 41, '2026-10-05 03:26:55', '2026-10-05 03:26:55'),
(23, 'tulshi', NULL, NULL, NULL, NULL, NULL, '9764435431', NULL, 'Goa', 'saverdem', '403401', 'opp Somnath school', 'Shigao, Sanvordem, opp Somnath school, Goa, saverdem, 403401', 'tulshi restaurant', 'IsKiOHf4X1V7k1.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 3, 39, '2026-10-05 03:29:02', '2026-10-05 03:29:02'),
(24, 'sarita gaokar', NULL, NULL, NULL, NULL, NULL, '9765197763', NULL, 'Goa', 'saverdem', '403407', 'opp Somnath school', 'Shigao, Sanvordem, opp Somnath school, Goa, saverdem, 403407', 'shantadurga stor', 'V2Mr1fZFK2WynX.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 4, 39, '2026-10-05 03:34:22', '2026-10-05 03:34:22'),
(25, 'shivanand Gawakar', NULL, NULL, NULL, NULL, NULL, '8698582230', NULL, 'West Bengal', 'saverdem', '403706', 'opp Somnath school', 'Shigao, Sanvordem, opp Somnath school, West Bengal, saverdem, 403706', 'Harichandra mini super market', 'fn5X04Ql1CMiF1.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 5, 39, '2026-10-05 03:40:26', '2026-10-05 03:40:26'),
(26, 'nayan naik', NULL, NULL, NULL, NULL, NULL, '8459556277', NULL, 'Goa', 'Saint Estevam Island', '403106', '476, Palmar, St Estevam, St Estevam Island, Jua, Goa 403106, India', 'St. Estev, Priol, 476, Palmar, St Estevam, St Estevam Island, Jua, Goa 403106, India, Goa, Saint Estevam Island, 403106', 'Hotel laxmi', 'od436KgZxR71NJ.jpg', NULL, NULL, '15.536773', '73.9497532', NULL, 122, '1', 6, 41, '2026-10-05 04:04:56', '2026-10-05 04:04:56'),
(27, 'venkatesh volvoikar', NULL, NULL, NULL, NULL, NULL, '8007440280', NULL, 'Goa', 'Jua', '403106', 'GXP4+XCP, St Estevam, Jua, Goa 403106, India', 'St. Estev, Priol, GXP4+XCP, St Estevam, Jua, Goa 403106, India, Goa, Jua, 403106', 'Vaishanvi general store', 'Vq0M3pyA26NF1H.jpg', NULL, NULL, '15.5373172', '73.9561135', NULL, 122, '1', 7, 41, '2026-10-05 04:15:11', '2026-10-05 04:15:11'),
(28, 'SP Ray', NULL, NULL, 'sm100@gmail.com', NULL, NULL, '9876787090', NULL, 'West Bengal', 'kolkata', '876678', 'Rajnagar biswambhar high school', 'High Road - 1179,Benaulim,Rajnagar biswambhar high school,West Bengal,kolkata,876678', 'SM Motors', 'IG4mzSAXlT3e5O.jpg', '987657876567', 'JHGGH76GHH', '8765789', '8765678', NULL, 6, '1', 4, 33, '2026-10-05 04:26:03', '2026-10-05 04:26:03'),
(29, 'ferreira', NULL, NULL, NULL, NULL, NULL, '9881663674', NULL, 'Goa', 'Khandola', '403107', '533/4, Ameyawada, Marcel, Khandola, Goa 403107, India', 'St. Estev, Priol, 533/4, Ameyawada, Marcel, Khandola, Goa 403107, India, Goa, Khandola, 403107', 'ferreira shop', 'T1N3SA9pd0VMzZ.jpg', NULL, NULL, '15.5242054', '73.9566554', NULL, 122, '1', 5, 41, '2026-10-05 04:39:30', '2026-10-05 04:39:30'),
(30, 'Ravi', NULL, NULL, NULL, NULL, NULL, '9021512221', NULL, 'Goa', 'Khandola', '403107', 'GXF5+C2J, Khandola, Goa 403107, India', 'St. Estev, Priol, GXF5+C2J, Khandola, Goa 403107, India, Goa, Khandola, 403107', 'Arviksha cafe', '158rmyz1Qlw0dC.jpg', NULL, NULL, '15.5235694', '73.9576453', NULL, 122, '1', 6, 41, '2026-10-05 04:41:33', '2026-10-05 04:41:33'),
(31, 'Ajit velip', NULL, NULL, NULL, NULL, NULL, '9022471187', NULL, 'Goa', 'saverdem', '403704', 'near mahadev temple', 'Shigao, Sanvordem, near mahadev temple, Goa, saverdem, 403704', 'Aradya restaurant', 'F2iQmKb0C9Df9e.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 7, 39, '2026-10-05 04:48:33', '2026-10-05 04:48:33'),
(32, 'Ashok dessai', NULL, NULL, NULL, NULL, NULL, '8698689508', NULL, 'Goa', 'saverdem', '403704', 'near mahadev temple', 'Shigao, Sanvordem, near mahadev temple, Goa, saverdem, 403704', 'Dessai trader', 'scuj3YXtrCHU87.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 8, 39, '2026-10-05 04:53:08', '2026-10-05 04:53:08'),
(33, 'Amit naik', NULL, NULL, NULL, NULL, NULL, '7517820834', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GW7W+VG6, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GW7W+VG6, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'gandhar store', 'H81CqZv26KIoOQ.jpg', NULL, NULL, '15.5152316', '73.9463055', NULL, 122, '1', 9, 41, '2026-10-05 04:55:42', '2026-10-05 04:55:42'),
(34, 'ajay chodhankar', NULL, NULL, NULL, NULL, NULL, '8605456440', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GW7W+F7G, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GW7W+F7G, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'arya general store', 'GVcnEWxKMkuPeh.jpg', NULL, NULL, '15.5134006', '73.9460406', NULL, 122, '1', 10, 41, '2026-10-05 05:01:14', '2026-10-05 05:01:14'),
(35, 'Nelson Dsouza', NULL, NULL, NULL, NULL, NULL, '7350852665', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GW7W+CRC, Malagwada, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GW7W+CRC, Malagwada, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'nelson super store', '4j1SbndI7V7OKJ.jpg', NULL, NULL, '15.5133369', '73.9471328', NULL, 122, '1', 11, 41, '2026-10-05 05:03:40', '2026-10-05 05:03:40'),
(36, 'Sachin naik', NULL, NULL, NULL, NULL, NULL, '7798519479', NULL, 'Goa', 'saverdem', '403704', 'near mahadev temple', 'Shigao, Sanvordem, near mahadev temple, Goa, saverdem, 403704', 'Rudra General Store', 'JyNmikwBHhQLAM.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 12, 39, '2026-10-05 05:04:14', '2026-10-05 05:04:14'),
(37, 'sunitra', NULL, NULL, NULL, NULL, NULL, '8698260172', NULL, 'Goa', 'saverdem', '403704', 'opp Mahade temple', 'Shigao, Sanvordem, opp Mahade temple, Goa, saverdem, 403704', 'sumitra g stor', '3mvB21RrqY1KiQ.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 13, 39, '2026-10-05 05:12:48', '2026-10-05 05:12:48'),
(38, 'shubhas gawade', NULL, NULL, NULL, NULL, NULL, '9527023155', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GX54+74, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GX54+74, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'nagesh store', 'K7zn2Xro9qxC1p.jpg', NULL, NULL, '15.507015', '73.9542317', NULL, 122, '1', 14, 41, '2026-10-05 05:13:22', '2026-10-05 05:13:22'),
(39, 'chandrash shet', NULL, NULL, NULL, NULL, NULL, '9011854894', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GX52+VGX, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GX52+VGX, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'mahalsa store', 'y5pdvzU6Qa0qKo.jpg', NULL, NULL, '15.5097145', '73.9508283', NULL, 122, '1', 15, 41, '2026-10-05 05:21:14', '2026-10-05 05:21:14'),
(40, 'prashant', NULL, NULL, NULL, NULL, NULL, '9922729693', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GW6V+6VP, ICDS, Tiswadi, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GW6V+6VP, ICDS, Tiswadi, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'cafe mangirish', 'mzRQYt86yS57jV.jpg', NULL, NULL, '15.5136542', '73.9482825', NULL, 122, '1', 16, 41, '2026-10-05 05:27:11', '2026-10-05 05:27:11'),
(41, 'shanta laxmi store', NULL, NULL, NULL, NULL, NULL, '7218461486', NULL, 'Goa', 'Kumbhar Juven', '403107', 'GW6V+6VP, ICDS, Tiswadi, Kumbhar Juven, Goa 403107, India', 'St. Estev, Priol, GW6V+6VP, ICDS, Tiswadi, Kumbhar Juven, Goa 403107, India, Goa, Kumbhar Juven, 403107', 'shanta laxmi store', 'FTYrse9ClcR7ki.jpg', NULL, NULL, '15.5137339', '73.948422', NULL, 122, '1', 17, 41, '2026-10-05 05:29:54', '2026-10-05 05:29:54'),
(42, 'kavlekar', NULL, NULL, NULL, NULL, NULL, '9022520669', NULL, 'Goa', 'saverdem', '403704', 'collem', 'Shigao, Sanvordem, collem, Goa, saverdem, 403704', 'Horticulture', 'Id93YeZiynotjF.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 18, 39, '2026-10-05 05:56:18', '2026-10-05 05:56:18'),
(43, 'rajesh naik', NULL, NULL, NULL, NULL, NULL, '9588429393', NULL, 'Goa', 'saverdem', '403704', 'near punchar shop', 'Shigao, Sanvordem, near punchar shop, Goa, saverdem, 403704', 'Bar& Restaurant', '090uM2HmZI5D0o.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 19, 39, '2026-10-05 06:02:00', '2026-10-05 06:02:00'),
(44, 'kanekar', NULL, NULL, NULL, NULL, NULL, '9545355021', NULL, 'Goa', 'saverdem', '403704', 'collem', 'Shigao, Sanvordem, collem, Goa, saverdem, 403704', 'G stor', '4G6n7oxrO28leB.jpg', NULL, NULL, NULL, NULL, NULL, 218, '1', 20, 39, '2026-10-05 06:37:44', '2026-10-05 06:37:44'),
(45, 'lawrenc dcosta', NULL, NULL, NULL, NULL, NULL, '9922509785', NULL, 'Goa', 'Jua', '403106', 'GXP2+66F, St Estevam, Jua, Goa 403106, India', 'St. Estev, Priol, GXP2+66F, St Estevam, Jua, Goa 403106, India, Goa, Jua, 403106', 'Jaycina store', 'Ni5dcrPoITJpf3.jpg', NULL, NULL, '15.5359029', '73.9513337', NULL, 122, '1', 21, 41, '2026-10-05 06:39:35', '2026-10-05 06:39:35'),
(46, 'Raju deshpande', NULL, NULL, NULL, NULL, NULL, '9881526907', NULL, 'Goa', 'Khandola', '403107', 'GXC6+XVX SDRA Children Park, Devlay, Candola, Khandola, Goa 403107, India', 'St. Estev, Priol, GXC6+XVX SDRA Children Park, Devlay, Candola, Khandola, Goa 403107, India, Goa, Khandola, 403107', 'sushila general store', '89VK81fdm1Mg2A.jpg', NULL, NULL, '15.5227184', '73.9625147', NULL, 122, '1', 22, 41, '2026-10-05 06:41:11', '2026-10-05 06:41:11'),
(47, 'rajendra', NULL, NULL, NULL, NULL, NULL, '9823899045', NULL, 'Goa', 'Khandola', '403107', 'GXC7+GVC, Khandola, Goa 403107, India', 'St. Estev, Priol, GXC7+GVC, Khandola, Goa 403107, India, Goa, Khandola, 403107', 'shubham enterprise', 'tRyHEo793jfYB3.jpg', NULL, NULL, '15.5211837', '73.964361', NULL, 122, '1', 23, 41, '2026-10-05 06:54:28', '2026-10-05 06:54:28'),
(48, 'HElo', NULL, NULL, NULL, NULL, NULL, '7458745871', NULL, 'West Bengal', 'Mountain View', '940437', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940437', 'Abc', 'CBgh7FIPEXJ1M5.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 24, 32, '2026-10-05 09:19:27', '2026-10-05 09:19:27'),
(49, 'Rowena', NULL, NULL, NULL, NULL, NULL, '8805226517', NULL, 'Goa', 'Carmona', '403717', '6X42+R4X, Carmona, Goa 403717, India', 'Carmona, Benaulim, 6X42+R4X, Carmona, Goa 403717, India, Goa, Carmona, 403717', 'pastry palace', 'DIqABfWGTaEr0J.jpg', NULL, NULL, '15.2071409', '73.9503391', NULL, 9, '1', 25, 40, '2026-10-06 04:41:44', '2026-10-06 04:41:44'),
(50, 'Dinesh', NULL, NULL, NULL, NULL, NULL, '9783814465', NULL, 'Goa', 'Carmona', '403717', '6X42+X48, Carmona, Goa 403717, India', 'Carmona, Benaulim, 6X42+X48, Carmona, Goa 403717, India, Goa, Carmona, 403717', 'Royal Sweet mart', 'HYC7M9QsgBKT5v.jpg', NULL, NULL, '15.2074714', '73.950439', NULL, 9, '1', 26, 40, '2026-10-06 04:45:59', '2026-10-06 04:45:59'),
(51, 'Mendes', NULL, NULL, NULL, NULL, NULL, '9890319168', NULL, 'Goa', 'Carmona', '403717', '6X52+G3M, Carmona, Goa 403717, India', 'Carmona, Benaulim, 6X52+G3M, Carmona, Goa 403717, India, Goa, Carmona, 403717', 'Mendes General Store& Wine store', 'HuN9SYkDc1ftdA.jpg', NULL, NULL, '15.2085565', '73.950308', NULL, 9, '1', 27, 40, '2026-10-06 04:51:54', '2026-10-06 04:51:54'),
(52, 'Priya', NULL, NULL, NULL, NULL, NULL, '7798156888', NULL, 'Goa', 'Carmona', '403717', '6X52+H2H, Carmona, Goa 403717, India', 'Carmona, Benaulim, 6X52+H2H, Carmona, Goa 403717, India, Goa, Carmona, 403717', 'Priya General Store', 'Ak2tgcq3CXsMuw.jpg', NULL, NULL, '15.209001', '73.950067', NULL, 9, '1', 28, 40, '2026-10-06 04:58:54', '2026-10-06 04:58:54'),
(53, 'sanjay', NULL, NULL, NULL, NULL, NULL, '7276604253', NULL, 'Goa', 'Mollem', '403410', 'Mollem,', 'Mollem, Sanvordem, Mollem,, Goa, Mollem, 403410', 'wine store', 'cmgMUtJyIT8x7F.jpg', NULL, NULL, '15.3787721', '74.2237102', NULL, 219, '1', 29, 39, '2026-10-06 06:12:40', '2026-10-06 06:12:40'),
(54, 'sonali shetverekar', NULL, NULL, NULL, NULL, NULL, '8390320459', NULL, 'Goa', 'Sakvorde', '403410', '96GG+8R9, Collem Rd, Sakvorde, Mollem, Goa 403410, India', 'Mollem, Sanvordem, 96GG+8R9, Collem Rd, Sakvorde, Mollem, Goa 403410, India, Goa, Sakvorde, 403410', 'General Store', 'cLE7M1gJ5BjK6S.jpg', NULL, NULL, '15.3759977', '74.2272812', NULL, 219, '1', 30, 39, '2026-10-06 06:24:26', '2026-10-06 06:24:26'),
(55, 'mera ownwe', NULL, NULL, NULL, NULL, NULL, '4578954254', NULL, 'West Bengal', 'Mountain View', '940434', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940434', 'mera', 'V1d2KkODpl09n2.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 31, 32, '2026-10-06 07:04:36', '2026-10-06 07:04:36'),
(56, 'aa', NULL, NULL, NULL, NULL, NULL, '4678678678', NULL, 'West Bengal', 'Mountain View', '940434', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940434', 'abcqas', '0c961NMq0gKu8D.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 32, 32, '2026-10-06 07:10:31', '2026-10-06 07:10:31'),
(57, 'SBSq', NULL, NULL, NULL, NULL, NULL, '8899889989', NULL, 'West Bengal', 'Mountain View', '940439', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940439', 'helo abc', 'j2F1l2cHsmeTa5.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 33, 32, '2026-10-06 07:13:31', '2026-10-06 07:13:31'),
(58, 'dd', NULL, NULL, NULL, NULL, NULL, '7567257124', NULL, 'West Bengal', 'Mountain View', '940435', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940435', 'demmmmm', '92m6kShfqW5jPy.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 34, 32, '2026-10-06 07:30:00', '2026-10-06 07:30:00'),
(59, 'wqwq', NULL, NULL, NULL, NULL, NULL, '4546546545', NULL, 'West Bengal', 'Mountain View', '940436', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940436', 'aqa', 'hlfPU2y15v12bS.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 35, 32, '2026-10-06 08:26:02', '2026-10-06 08:26:02'),
(60, 'me2ksj', NULL, NULL, NULL, NULL, NULL, '3165467676', NULL, 'West Bengal', 'Kolkata', '700127', 'Madhyamgram, Kolkata, West Bengal 700982, India', 'Morjim, Mandrem, Madhyamgram, Kolkata, West Bengal 700982, India, West Bengal, Kolkata, 700127', 'me', 'ZNXb0lc8j7wvWq.jpg', NULL, NULL, '22.7052427', '88.4691158', NULL, 14, '1', 36, 32, '2026-10-06 08:36:26', '2026-10-06 08:36:26'),
(61, 'okkuok', NULL, NULL, NULL, NULL, NULL, '8748745100', NULL, 'West Bengal', 'Mountain View', '940433', '1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA', 'Morjim, Mandrem, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA, West Bengal, Mountain View, 940433', 'kkkk', '0jhv4A4o2glVSs.jpg', NULL, NULL, '37.4219983', '-122.084', NULL, 14, '1', 37, 32, '2026-10-06 08:48:09', '2026-10-06 08:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `customer_inquiries`
--

CREATE TABLE `customer_inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(555) NOT NULL,
  `email` varchar(555) NOT NULL,
  `phone` varchar(555) NOT NULL,
  `message` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer_inquiries`
--

INSERT INTO `customer_inquiries` (`id`, `name`, `email`, `phone`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Adrian Altenwerth', 'your.email+fakedata89917@gmail.com', '23273598', 'Supra socius sponte abscido comes comprehendo cena.', 1, '2026-07-21 10:06:53', '2026-07-21 10:06:53'),
(2, 'Dana Walker', 'your.email+fakedata46658@gmail.com', '30530867', 'Surgo repudiandae adversus surculus aiunt somnus.', 1, '2026-07-21 10:14:41', '2026-07-21 10:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `driver_products`
--

CREATE TABLE `driver_products` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `total_products` int(11) NOT NULL,
  `grand_total` varchar(555) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `driver_products`
--

INSERT INTO `driver_products` (`id`, `driver_id`, `warehouse_id`, `total_products`, `grand_total`, `created_at`, `updated_at`) VALUES
(1, 33, 35, 2, NULL, '2026-09-26 10:01:39', '2026-09-26 10:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `driver_product_details`
--

CREATE TABLE `driver_product_details` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `driver_products_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `driver_product_details`
--

INSERT INTO `driver_product_details` (`id`, `driver_id`, `driver_products_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 33, 1, 1, 15, '2026-09-26 10:01:39', '2026-09-26 10:01:39'),
(2, 33, 1, 4, 10, '2026-09-26 10:01:39', '2026-09-26 10:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `driver_punch_in`
--

CREATE TABLE `driver_punch_in` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selfie` varchar(555) NOT NULL,
  `punch_in_time` datetime DEFAULT NULL,
  `break_time` datetime DEFAULT NULL,
  `punch_out_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `driver_punch_in`
--

INSERT INTO `driver_punch_in` (`id`, `user_id`, `selfie`, `punch_in_time`, `break_time`, `punch_out_time`, `created_at`, `updated_at`) VALUES
(1, 33, 'o57wNs7Shytqz6.jpg', NULL, NULL, NULL, '2026-08-10 07:09:37', '2026-08-10 07:09:37'),
(2, 33, '4b2Rz1l0N67Ewq.png', NULL, NULL, NULL, '2026-08-11 09:33:44', '2026-08-11 09:33:44'),
(3, 33, '2iMVJp74jRq3HN.jpg', NULL, NULL, NULL, '2026-08-11 09:39:49', '2026-08-11 09:39:49'),
(4, 33, 'tRIUwSA4WxHu0l.jpg', NULL, NULL, NULL, '2026-08-11 15:03:53', '2026-08-11 15:03:53'),
(5, 33, 'YPk3sf8pHCw0Gi.jpg', NULL, NULL, NULL, '2026-08-11 15:24:21', '2026-08-11 15:24:21'),
(6, 33, '6RvM8t634JF2IS.jpg', NULL, NULL, NULL, '2026-08-11 15:27:04', '2026-08-11 15:27:04'),
(7, 33, 'l8rfypu8h6ScZz.jpg', NULL, NULL, NULL, '2026-08-11 15:44:38', '2026-08-11 15:44:38'),
(8, 33, '2JswWcdzPMSKg9.jpg', NULL, NULL, NULL, '2026-08-15 11:15:48', '2026-08-15 11:15:48'),
(9, 33, 'U9cx2wye5Xki8N.jpg', '2026-08-16 09:12:00', '2026-08-16 09:32:54', '2026-08-16 09:23:28', '2026-08-16 03:42:19', '2026-08-16 04:02:54'),
(10, 33, '47aQix8eFYb1pZ.jpg', '2026-08-19 17:51:21', NULL, '2026-08-19 19:52:42', '2026-08-19 12:21:21', '2026-08-19 14:22:42'),
(11, 33, 'rf2kh8W5P8Ay3D.jpg', '2026-08-19 18:01:29', NULL, '2026-08-19 19:52:42', '2026-08-19 12:31:29', '2026-08-19 14:22:42'),
(12, 33, 'S3R7046q3vL1Vz.jpg', '2026-08-19 18:12:00', NULL, '2026-08-19 19:52:42', '2026-08-19 12:42:00', '2026-08-19 14:22:42'),
(13, 33, 'z43HM5E8haRDcW.jpg', '2026-08-19 18:20:50', NULL, '2026-08-19 19:52:42', '2026-08-19 12:50:50', '2026-08-19 14:22:42'),
(14, 33, 'pfVikn8aDy05zj.jpg', '2026-08-19 19:14:56', NULL, '2026-08-19 19:52:42', '2026-08-19 13:44:56', '2026-08-19 14:22:42'),
(15, 33, 'hXjM3Kg76uPN1b.jpg', '2026-08-19 19:22:48', NULL, '2026-08-19 19:52:42', '2026-08-19 13:52:48', '2026-08-19 14:22:42'),
(16, 33, 'kzK1bDOwT7naVN.jpg', '2026-08-19 19:45:24', NULL, '2026-08-19 19:52:42', '2026-08-19 14:15:24', '2026-08-19 14:22:42'),
(17, 33, 'rsSaqf2jT35Q77.jpg', '2026-08-19 20:05:12', NULL, NULL, '2026-08-19 14:35:12', '2026-08-19 14:35:12'),
(18, 33, 'o48SzBapJexC9b.jpg', '2026-08-19 20:11:07', NULL, NULL, '2026-08-19 14:41:07', '2026-08-19 14:41:07'),
(19, 33, 'z7Ep1CIAyKH38f.jpg', '2026-08-20 19:55:33', NULL, NULL, '2026-08-20 14:25:33', '2026-08-20 14:25:33'),
(20, 33, 'uGs8pm0JboKQ40.jpg', '2026-08-21 13:54:04', NULL, NULL, '2026-08-21 08:24:04', '2026-08-21 08:24:04'),
(21, 33, 'JiI7c2nskRVa0f.jpg', '2026-08-21 14:13:16', NULL, NULL, '2026-08-21 08:43:16', '2026-08-21 08:43:16'),
(22, 33, '83t1KlZ3e71QP6.jpg', '2026-08-21 14:22:10', NULL, NULL, '2026-08-21 08:52:10', '2026-08-21 08:52:10'),
(23, 33, 'EJ3Re8PG65781N.jpg', '2026-08-21 14:47:29', NULL, NULL, '2026-08-21 09:17:29', '2026-08-21 09:17:29'),
(24, 33, 'UcnAavXqSN32R1.jpg', '2026-08-21 15:12:13', NULL, NULL, '2026-08-21 09:42:13', '2026-08-21 09:42:13'),
(25, 33, 'JoGv287b81shWQ.jpg', '2026-08-28 20:17:30', NULL, NULL, '2026-08-28 14:47:30', '2026-08-28 14:47:30'),
(26, 33, 'I0718bSki7rFOq.jpg', '2026-08-28 20:30:57', NULL, NULL, '2026-08-28 15:00:57', '2026-08-28 15:00:57'),
(27, 33, 'kZ5A79mqB0wE2O.jpg', '2026-08-28 20:33:49', NULL, NULL, '2026-08-28 15:03:49', '2026-08-28 15:03:49'),
(28, 33, 'f7xoWFnuTi12g3.jpg', '2026-08-28 21:15:03', NULL, NULL, '2026-08-28 15:45:03', '2026-08-28 15:45:03'),
(29, 33, 'QI1jmuSel7vxCT.jpg', '2026-08-29 19:32:58', NULL, NULL, '2026-08-29 14:02:58', '2026-08-29 14:02:58'),
(30, 33, 'c6InK34PCNBi8m.jpg', '2026-08-29 19:46:48', NULL, NULL, '2026-08-29 14:16:48', '2026-08-29 14:16:48'),
(31, 33, 'E8bYj8IHVn0Ocw.jpg', '2026-08-31 11:14:41', NULL, NULL, '2026-08-31 05:44:41', '2026-08-31 05:44:41'),
(32, 33, 'wCEQDBgzl87q7T.jpg', '2026-08-31 11:47:54', NULL, NULL, '2026-08-31 06:17:54', '2026-08-31 06:17:54'),
(33, 33, 'e1RhbHTWpFQX64.jpg', '2026-08-31 21:00:23', NULL, NULL, '2026-08-31 15:30:23', '2026-08-31 15:30:23'),
(34, 33, '4q8ZT6bnI87JXv.jpg', '2026-09-01 17:44:28', NULL, NULL, '2026-09-01 12:14:28', '2026-09-01 12:14:28'),
(35, 33, '4868Qq0f3zS58F.jpg', '2026-09-01 18:20:04', NULL, NULL, '2026-09-01 12:50:04', '2026-09-01 12:50:04'),
(36, 33, '9Pyb7HZ06vTGz3.jpg', '2026-09-01 18:20:54', NULL, NULL, '2026-09-01 12:50:54', '2026-09-01 12:50:54'),
(37, 33, 'Jtd6hqU70uPCB1.jpg', '2026-09-01 18:26:16', NULL, NULL, '2026-09-01 12:56:16', '2026-09-01 12:56:16'),
(38, 33, 'kD817BgS4xn8U0.jpg', '2026-09-01 18:28:25', NULL, NULL, '2026-09-01 12:58:25', '2026-09-01 12:58:25'),
(39, 33, '6fgFjd97lcBDIz.jpg', '2026-09-02 17:11:28', NULL, NULL, '2026-09-02 11:41:28', '2026-09-02 11:41:28'),
(40, 33, '1ZH6dzJ9bo8EvN.jpg', '2026-09-03 11:57:55', NULL, NULL, '2026-09-03 06:27:55', '2026-09-03 06:27:55'),
(41, 33, '8xzfcCJ7Yb1I5l.jpg', '2026-09-03 12:09:49', NULL, NULL, '2026-09-03 06:39:49', '2026-09-03 06:39:49'),
(42, 33, 'ZG4F1BdtmpvT78.jpg', '2026-09-03 12:16:13', NULL, NULL, '2026-09-03 06:46:13', '2026-09-03 06:46:13'),
(43, 33, '78v5nhYHAbG13x.jpg', '2026-09-03 12:19:12', NULL, NULL, '2026-09-03 06:49:12', '2026-09-03 06:49:12'),
(44, 33, 'pyhSPAxYL8wIG1.jpg', '2026-09-03 12:22:12', NULL, NULL, '2026-09-03 06:52:12', '2026-09-03 06:52:12'),
(45, 33, '3USI7MlCBn6pJt.jpg', '2026-09-03 13:00:56', NULL, NULL, '2026-09-03 07:30:56', '2026-09-03 07:30:56'),
(46, 33, '9woMvYlLOHIj40.jpg', '2026-09-03 13:44:46', NULL, NULL, '2026-09-03 08:14:46', '2026-09-03 08:14:46'),
(47, 33, 'fV8nld6Pq7Sk7s.jpg', '2026-09-03 14:14:36', NULL, NULL, '2026-09-03 08:44:36', '2026-09-03 08:44:36'),
(48, 33, 'Bwt88D4YErnRXJ.jpg', '2026-09-04 14:32:43', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 09:02:43', '2026-09-04 13:24:16'),
(49, 33, 'X41K58yApSe3ln.jpg', '2026-09-04 14:53:21', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 09:23:21', '2026-09-04 13:24:16'),
(50, 33, '1UtJp4rGvEiyL2.jpg', '2026-09-04 15:05:47', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 09:35:47', '2026-09-04 13:24:16'),
(51, 33, 'y72psfxMvjhBn5.jpg', '2026-09-04 15:17:28', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 09:47:28', '2026-09-04 13:24:16'),
(52, 33, 'cwOyTSE1RdCQp6.jpg', '2026-09-04 15:45:12', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 10:15:12', '2026-09-04 13:24:16'),
(53, 33, 'si4f8ZluDpNqPR.jpg', '2026-09-04 17:15:40', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 11:45:40', '2026-09-04 13:24:16'),
(54, 33, '585F6CxqXcen0H.jpg', '2026-09-04 18:23:31', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 12:53:31', '2026-09-04 13:24:16'),
(55, 33, 'HU6BvCdrybYc9a.jpg', '2026-09-04 18:36:26', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 13:06:26', '2026-09-04 13:24:16'),
(56, 33, 'O1v30DPM72oc4G.jpg', '2026-09-04 18:42:29', '2026-09-04 18:54:16', '2026-09-04 18:53:18', '2026-09-04 13:12:29', '2026-09-04 13:24:16'),
(57, 33, 'OVzNG8sbB25g18.jpg', '2026-09-04 18:53:38', '2026-09-04 18:54:16', NULL, '2026-09-04 13:23:38', '2026-09-04 13:24:16'),
(58, 33, 'tu7OkaA70eXM45.jpg', '2026-09-04 18:57:01', NULL, NULL, '2026-09-04 13:27:01', '2026-09-04 13:27:01'),
(59, 33, 'ecsPudajCYfl2G.jpg', '2026-09-04 19:12:22', NULL, NULL, '2026-09-04 13:42:22', '2026-09-04 13:42:22'),
(60, 33, '2oEDlY1qCTz1gu.jpg', '2026-09-04 19:43:42', NULL, NULL, '2026-09-04 14:13:42', '2026-09-04 14:13:42'),
(61, 33, 'lVT51vESZh2NUG.jpg', '2026-09-04 19:45:19', NULL, NULL, '2026-09-04 14:15:19', '2026-09-04 14:15:19'),
(62, 33, 'r1475FqQNs5MEU.jpg', '2026-09-04 19:48:58', NULL, NULL, '2026-09-04 14:18:58', '2026-09-04 14:18:58'),
(63, 33, 'Cq52LO8bYycs5d.jpg', '2026-09-05 12:15:59', NULL, NULL, '2026-09-05 06:45:59', '2026-09-05 06:45:59'),
(64, 33, 'wMzNSGZ2d7W7hn.jpg', '2026-09-05 20:35:59', NULL, NULL, '2026-09-05 15:05:59', '2026-09-05 15:05:59'),
(65, 33, 'g2n4GK649qEaI1.jpg', '2026-09-06 00:20:39', NULL, NULL, '2026-09-05 18:50:39', '2026-09-05 18:50:39'),
(66, 33, 'j6IgdMSRe3hD5Z.jpg', '2026-09-06 12:19:03', NULL, NULL, '2026-09-06 06:49:03', '2026-09-06 06:49:03'),
(67, 33, '7k0mty1g38H8PX.jpg', '2026-09-06 12:33:11', NULL, NULL, '2026-09-06 07:03:11', '2026-09-06 07:03:11'),
(68, 33, 'Ch57FJXwjM5N1D.jpg', '2026-09-06 12:36:15', NULL, NULL, '2026-09-06 07:06:15', '2026-09-06 07:06:15'),
(69, 33, 'Y6bZQOU69Nu3wA.jpg', '2026-09-06 12:48:06', NULL, NULL, '2026-09-06 07:18:06', '2026-09-06 07:18:06'),
(70, 33, 'VLfp1hGR6YJosU.jpg', '2026-09-06 12:53:31', NULL, NULL, '2026-09-06 07:23:31', '2026-09-06 07:23:31'),
(71, 33, 'oL8NWlnxiQ3XV6.jpg', '2026-09-06 12:54:44', NULL, NULL, '2026-09-06 07:24:44', '2026-09-06 07:24:44'),
(72, 33, 'GwKpFYb0X8tOgA.jpg', '2026-09-06 13:33:58', NULL, NULL, '2026-09-06 08:03:58', '2026-09-06 08:03:58'),
(73, 33, 'e4xP57U1d8WXTN.jpg', '2026-09-06 13:45:25', NULL, NULL, '2026-09-06 08:15:25', '2026-09-06 08:15:25'),
(74, 33, 'YdN0Qc719EMXwz.jpg', '2026-09-06 13:57:24', NULL, NULL, '2026-09-06 08:27:24', '2026-09-06 08:27:24'),
(75, 33, 'av6TMSzf6iLE8q.jpg', '2026-09-06 14:57:46', NULL, NULL, '2026-09-06 09:27:46', '2026-09-06 09:27:46'),
(76, 33, '657d7T1s8cxDN8.jpg', '2026-09-07 18:33:27', NULL, NULL, '2026-09-07 13:03:27', '2026-09-07 13:03:27'),
(77, 33, '9O78dxB8Sk43tr.jpg', '2026-09-07 18:57:13', NULL, NULL, '2026-09-07 13:27:13', '2026-09-07 13:27:13'),
(78, 33, 'HP3GqDnE15f8Ca.jpg', '2026-09-07 19:10:09', NULL, NULL, '2026-09-07 13:40:09', '2026-09-07 13:40:09'),
(79, 33, 'vLxS3KJfgy5Mdi.jpg', '2026-09-07 19:22:07', NULL, NULL, '2026-09-07 13:52:07', '2026-09-07 13:52:07'),
(80, 33, 'tSNrmPfsE51giv.jpg', '2026-09-07 21:39:34', NULL, NULL, '2026-09-07 16:09:34', '2026-09-07 16:09:34'),
(81, 33, '6VHv2ocpk901Qg.jpg', '2026-09-09 13:43:49', NULL, NULL, '2026-09-09 08:13:49', '2026-09-09 08:13:49'),
(82, 2, '8el0Iwcuo3PRjE.jpg', '2026-09-24 16:12:04', '2026-09-24 16:13:50', '2026-09-24 16:14:05', '2026-09-24 10:42:04', '2026-09-24 10:44:05'),
(83, 32, 'tIS9E01s1dcAl0.jpg', '2026-09-25 13:38:25', NULL, '2026-09-25 13:49:53', '2026-09-25 08:08:25', '2026-09-25 08:19:53'),
(84, 32, 'k8foa3K41PDz9O.jpg', '2026-09-25 13:49:45', NULL, '2026-09-25 13:49:53', '2026-09-25 08:19:45', '2026-09-25 08:19:53'),
(85, 32, 'C9e2UftKzcj790.jpg', '2026-09-25 13:57:29', NULL, NULL, '2026-09-25 08:27:29', '2026-09-25 08:27:29'),
(86, 32, 'Xx578g4yEQh9w0.jpg', '2026-09-25 14:00:59', NULL, NULL, '2026-09-25 08:30:59', '2026-09-25 08:30:59'),
(87, 32, '70Evxnsow3Kc2A.jpg', '2026-09-25 14:04:18', NULL, NULL, '2026-09-25 08:34:18', '2026-09-25 08:34:18'),
(88, 32, '4UKWdCFTfAMLwu.jpg', '2026-09-25 14:13:06', NULL, NULL, '2026-09-25 08:43:06', '2026-09-25 08:43:06'),
(89, 32, '13zdGB0ExfrmbT.jpg', '2026-09-25 14:31:01', NULL, NULL, '2026-09-25 09:01:01', '2026-09-25 09:01:01'),
(90, 32, 'euY9jcJgdkfVhF.jpg', '2026-09-25 17:58:04', NULL, NULL, '2026-09-25 12:28:04', '2026-09-25 12:28:04'),
(91, 32, 'Ny19o7P6xqzng3.jpg', '2026-09-25 18:07:44', NULL, NULL, '2026-09-25 12:37:44', '2026-09-25 12:37:44'),
(92, 32, '8PQXfaY3FT5B6A.jpg', '2026-09-26 13:30:04', NULL, NULL, '2026-09-26 08:00:04', '2026-09-26 08:00:04'),
(93, 32, '9q2GRf06j1KFwp.jpg', '2026-09-26 13:41:04', NULL, NULL, '2026-09-26 08:11:04', '2026-09-26 08:11:04'),
(94, 32, 'ergkK1zp9o0GIM.jpg', '2026-09-28 06:25:11', NULL, NULL, '2026-09-28 00:55:11', '2026-09-28 00:55:11'),
(95, 40, 'u9dvc3gBhkn7Ut.jpg', '2026-10-03 16:03:20', NULL, NULL, '2026-10-03 10:33:20', '2026-10-03 10:33:20'),
(96, 39, 'lApIOrt35xUo11.jpg', '2026-10-03 16:04:55', NULL, NULL, '2026-10-03 10:34:55', '2026-10-03 10:34:55'),
(97, 41, 'U8C8sR065wHDG1.jpg', '2026-10-03 16:06:44', NULL, NULL, '2026-10-03 10:36:44', '2026-10-03 10:36:44'),
(98, 39, '0Rf2n9dGUe8NXD.jpg', '2026-10-03 16:08:00', NULL, NULL, '2026-10-03 10:38:00', '2026-10-03 10:38:00'),
(99, 40, 'aKiYdW7h0D29ZM.jpg', '2026-10-03 16:10:27', NULL, NULL, '2026-10-03 10:40:27', '2026-10-03 10:40:27'),
(100, 39, '4HWg3uZ8vUio9y.jpg', '2026-10-03 16:20:46', NULL, NULL, '2026-10-03 10:50:46', '2026-10-03 10:50:46'),
(101, 40, 'C9HJ2S1h4e1U45.jpg', '2026-10-03 16:27:29', NULL, NULL, '2026-10-03 10:57:29', '2026-10-03 10:57:29'),
(102, 41, '1b1Ln7wZu4Mrxq.jpg', '2026-10-03 16:27:43', NULL, NULL, '2026-10-03 10:57:43', '2026-10-03 10:57:43'),
(103, 39, '4DIgoFC7Wf6i15.jpg', '2026-10-03 16:27:44', NULL, NULL, '2026-10-03 10:57:44', '2026-10-03 10:57:44'),
(104, 40, '51uN6U2C2aTQet.jpg', '2026-10-03 16:29:06', NULL, NULL, '2026-10-03 10:59:06', '2026-10-03 10:59:06'),
(105, 33, 'tH70o9yi3rWTRV.jpg', '2026-10-03 18:43:52', NULL, NULL, '2026-10-03 13:13:52', '2026-10-03 13:13:52'),
(106, 33, '9qwX1xh5jeBcRH.jpg', '2026-10-03 18:44:42', NULL, NULL, '2026-10-03 13:14:42', '2026-10-03 13:14:42'),
(107, 33, '7kZ4nz6CVb1Fd3.jpg', '2026-10-03 18:51:37', NULL, NULL, '2026-10-03 13:21:37', '2026-10-03 13:21:37'),
(108, 33, 'ajLoc7v4Xr5GJN.jpg', '2026-10-03 23:34:33', NULL, NULL, '2026-10-03 18:04:33', '2026-10-03 18:04:33'),
(109, 39, 'eWmbqo4I59HhvN.jpg', '2026-10-05 07:16:44', NULL, NULL, '2026-10-05 01:46:44', '2026-10-05 01:46:44'),
(110, 41, 'l9hym1Zd2q76XQ.jpg', '2026-10-05 08:30:12', NULL, NULL, '2026-10-05 03:00:12', '2026-10-05 03:00:12'),
(111, 41, 'jDYUig32FQtsqm.jpg', '2026-10-05 08:53:52', NULL, NULL, '2026-10-05 03:23:52', '2026-10-05 03:23:52'),
(112, 39, 's7z0jqQwG5SJen.jpg', '2026-10-05 09:19:09', NULL, NULL, '2026-10-05 03:49:09', '2026-10-05 03:49:09'),
(113, 41, 'AMZ71sPNT9CEth.jpg', '2026-10-05 09:41:09', NULL, NULL, '2026-10-05 04:11:09', '2026-10-05 04:11:09'),
(114, 33, '7Ae4kt45gouUsr.jpg', '2026-10-05 09:57:26', NULL, NULL, '2026-10-05 04:27:26', '2026-10-05 04:27:26'),
(115, 41, '0jKSIBV1m97JQL.jpg', '2026-10-05 09:58:57', NULL, NULL, '2026-10-05 04:28:57', '2026-10-05 04:28:57'),
(116, 41, 'fsNZa0giteSHuv.jpg', '2026-10-05 10:23:26', NULL, NULL, '2026-10-05 04:53:26', '2026-10-05 04:53:26'),
(117, 41, 'iPDghFlx6b71Mn.jpg', '2026-10-05 10:29:16', NULL, NULL, '2026-10-05 04:59:16', '2026-10-05 04:59:16'),
(118, 41, 'chn8B4K9l2sXLf.jpg', '2026-10-05 10:44:44', NULL, NULL, '2026-10-05 05:14:44', '2026-10-05 05:14:44'),
(119, 41, 'm2N59KwJ8HALob.jpg', '2026-10-05 10:58:11', NULL, NULL, '2026-10-05 05:28:11', '2026-10-05 05:28:11'),
(120, 32, 'zhg4wpVlX8DQn6.jpg', '2026-10-05 13:24:48', NULL, NULL, '2026-10-05 07:54:48', '2026-10-05 07:54:48'),
(121, 32, 'ThLdZgFMQpz9eG.jpg', '2026-10-05 14:54:54', NULL, NULL, '2026-10-05 09:24:54', '2026-10-05 09:24:54'),
(122, 33, 'bu5ZclCV8TAs7f.jpg', '2026-10-05 15:07:52', NULL, NULL, '2026-10-05 09:37:52', '2026-10-05 09:37:52'),
(123, 33, '242fxUh1eWGRt8.jpg', '2026-10-05 17:52:45', NULL, NULL, '2026-10-05 12:22:45', '2026-10-05 12:22:45'),
(124, 33, 'K802Js3yO1jPIH.jpg', '2026-10-05 17:53:27', NULL, NULL, '2026-10-05 12:23:27', '2026-10-05 12:23:27'),
(125, 33, 'own77LldgkiypD.jpg', '2026-10-05 17:55:17', NULL, NULL, '2026-10-05 12:25:17', '2026-10-05 12:25:17'),
(126, 39, 'yf7AEiLN1Z2Twz.jpg', '2026-10-06 07:22:13', NULL, NULL, '2026-10-06 01:52:13', '2026-10-06 01:52:13'),
(127, 41, 'YyB6gVsu7S7RFL.jpg', '2026-10-06 08:31:43', NULL, NULL, '2026-10-06 03:01:43', '2026-10-06 03:01:43'),
(128, 40, 'cj4JzI7C009wQs.jpg', '2026-10-06 08:33:21', NULL, NULL, '2026-10-06 03:03:21', '2026-10-06 03:03:21'),
(129, 40, 'Pb5K39SVqtZ575.jpg', '2026-10-06 08:35:44', NULL, NULL, '2026-10-06 03:05:44', '2026-10-06 03:05:44'),
(130, 40, 'JU6yA4BHueWx7K.jpg', '2026-10-06 08:49:09', NULL, NULL, '2026-10-06 03:19:09', '2026-10-06 03:19:09'),
(131, 40, 'ktO1Jy4g8oCaEG.jpg', '2026-10-06 09:22:45', NULL, NULL, '2026-10-06 03:52:45', '2026-10-06 03:52:45'),
(132, 40, '2P8F6iE1rIsDeS.jpg', '2026-10-06 09:56:09', NULL, NULL, '2026-10-06 04:26:09', '2026-10-06 04:26:09'),
(133, 39, 'Cz1UaGEd1Ihlnp.jpg', '2026-10-06 10:15:01', NULL, NULL, '2026-10-06 04:45:01', '2026-10-06 04:45:01'),
(134, 40, 'Ib96nD218fQ5Zp.jpg', '2026-10-06 10:17:31', NULL, NULL, '2026-10-06 04:47:31', '2026-10-06 04:47:31'),
(135, 40, 'td21Iw98saRhYB.jpg', '2026-10-06 11:18:13', NULL, NULL, '2026-10-06 05:48:13', '2026-10-06 05:48:13'),
(136, 39, 'ke6Bb1o52YWd8g.jpg', '2026-10-06 11:33:16', NULL, NULL, '2026-10-06 06:03:16', '2026-10-06 06:03:16'),
(137, 33, 'mZ36s2foUBjqQO.jpg', '2026-10-06 11:36:46', '2026-10-06 15:32:22', '2026-10-06 11:46:43', '2026-10-06 06:06:46', '2026-10-06 10:02:22'),
(138, 39, '4N6it7M7bvUJnm.jpg', '2026-10-06 11:44:36', NULL, NULL, '2026-10-06 06:14:36', '2026-10-06 06:14:36'),
(139, 33, 'E8BIk4vp7P4rOK.jpg', '2026-10-06 11:47:26', '2026-10-06 15:32:22', NULL, '2026-10-06 06:17:26', '2026-10-06 10:02:22'),
(140, 33, 'M1L0r2274FY7Dm.jpg', '2026-10-06 12:12:51', '2026-10-06 15:32:22', NULL, '2026-10-06 06:42:51', '2026-10-06 10:02:22'),
(141, 32, 'y817I7X2KCcjTF.jpg', '2026-10-06 13:55:21', NULL, NULL, '2026-10-06 08:25:21', '2026-10-06 08:25:21'),
(142, 32, 'b77D3ZMjRYN9u8.jpg', '2026-10-06 14:04:34', NULL, NULL, '2026-10-06 08:34:34', '2026-10-06 08:34:34'),
(143, 32, 'wsXo52MgIjZB7P.jpg', '2026-10-06 14:17:29', NULL, NULL, '2026-10-06 08:47:29', '2026-10-06 08:47:29'),
(144, 33, 'WlrpLmDKx5YZ94.jpg', '2026-10-06 15:20:55', '2026-10-06 15:32:22', NULL, '2026-10-06 09:50:55', '2026-10-06 10:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `driver_route`
--

CREATE TABLE `driver_route` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `road_id` int(11) NOT NULL,
  `serial_no` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `driver_route`
--

INSERT INTO `driver_route` (`id`, `driver_id`, `road_id`, `serial_no`, `created_at`, `updated_at`) VALUES
(1, 33, 1, 1, '2026-08-09 13:53:54', '2026-08-09 13:53:54'),
(2, 33, 5, 2, '2026-08-09 13:53:54', '2026-08-09 13:53:54'),
(5, 32, 4, 1, '2026-09-17 11:47:23', '2026-09-17 11:47:23'),
(6, 32, 6, 2, '2026-09-17 11:47:23', '2026-09-17 11:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `driver_warehouse`
--

CREATE TABLE `driver_warehouse` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `driver_warehouse`
--

INSERT INTO `driver_warehouse` (`id`, `warehouse_id`, `driver_id`, `created_at`, `updated_at`) VALUES
(2, 35, 33, '2026-09-26 05:32:28', '2026-09-26 05:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `email_content`
--

CREATE TABLE `email_content` (
  `id` int(11) NOT NULL,
  `email_code` varchar(250) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `body` text DEFAULT NULL,
  `status` enum('0','1','3') DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `email_content`
--

INSERT INTO `email_content` (`id`, `email_code`, `about`, `subject`, `body`, `status`, `created_at`, `updated_at`) VALUES
(1, 'user_registration', 'Thank you for Your inquiry.', 'Thank you for Your inquiry.', '<p><strong>Hello&nbsp;{{NAME}},</strong></p>\r\n\r\n<p>Congratulations!! You have successfully submitted the Inquiry with <strong>Evoflex</strong>.</p>\r\n\r\n<p>We will get back to You soon.</p>', '1', '2023-06-27 13:10:31', '2022-12-14 19:22:54'),
(2, 'reset-passwords', 'Reset Password', 'Reset Password', '<p><strong>Hello&nbsp;{{NAME}},</strong></p><p>Your OTP to reset your password &nbsp;is {{OTP}}</p>', '3', '2023-06-23 12:26:46', '2023-06-23 12:26:46'),
(3, 'new_enquiry', 'New Enquiry', 'New Enquiry', '<p><strong><span style=\"color:rgb(178, 34, 34)\"><span style=\"font-size:18px\"><span style=\"font-family:arial,helvetica,sans-serif\">Hello</span>&nbsp;Super Admin,</span></span></strong> someone enquire for New Website. Please follow the below details :</p>\r\n\r\n<p>Name : {{NAME}}</p>\r\n\r\n<p>Email : {{EMAIL}}</p>\r\n\r\n<p>Phone : {{PHONE}}</p>\r\n\r\n<p>Message: {{MSG}}</p>\r\n\r\n<p>Please check Your Super Admin Dashboard.</p>', '1', '2023-09-25 18:13:06', '2023-09-25 18:13:06'),
(4, 'send_otp', 'Verify Your Email', 'Verify Your Email', '<p><strong>Hello&nbsp;{{NAME}},</strong></p>\n\n<p>Please verify your email account with the following OTP number.</p>\n\n<p>Your OTP for verification is: {{OTP}}</p>', '1', '2023-09-25 18:13:06', '2023-09-25 18:13:06'),
(5, 'forgot_password', 'Forgot Password', 'Reset Your Password', '<p><strong><span style=\"color:rgb(178, 34, 34)\"><span style=\"font-size:18px\"><span style=\"font-family:arial,helvetica,sans-serif\">Hello</span>&nbsp;{{NAME}},</span></span></strong></p>\n\n<p>Please click the below link to change reset your password.</p>\n\n<p><strong><a href=\"{{LINK}}\" style=\"text-decoration: none;\">Click here</a></strong></p>', '3', '2023-03-17 19:22:01', '2023-03-17 19:22:01'),
(6, 'send_service_link', 'Detailed Service Requirements', 'Detailed Service Requirements', '<p><strong><span style=\"color:rgb(178, 34, 34)\"><span style=\"font-size:18px\"><span style=\"font-family:arial,helvetica,sans-serif\">Hello</span>&nbsp;{{NAME}},</span></span></strong></p>\r\n\r\n<p>Please click the below link to fill the form of - <b>Detailed Service Requirements</b>.</p>\r\n\r\n<p><strong><a href=\"{{LINK}}\" style=\"text-decoration: none;\">Click here</a></strong></p>', '1', '2023-09-25 18:13:06', '2023-09-25 18:13:06'),
(7, 'send_template', 'Service Template', 'Service Template', '<p><strong><span style=\"color:rgb(178, 34, 34)\"><span style=\"font-size:18px\"><span style=\"font-family:arial,helvetica,sans-serif\">Hello</span>&nbsp;{{NAME}},</span></span></strong></p>\r\n\r\n<p>Please see the below service templates : </p>\r\n\r\n{{TEMP}}', '1', '2025-06-03 19:13:07', '2025-06-03 19:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(555) DEFAULT NULL,
  `driver_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `total_price` varchar(555) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_no`, `driver_id`, `shop_id`, `total_price`, `created_at`, `updated_at`) VALUES
(1, NULL, 33, 11, '130', '2026-09-04 13:49:26', '2026-09-04 13:49:26'),
(2, NULL, 33, 12, '960', '2026-09-04 14:28:05', '2026-09-04 14:28:05'),
(3, NULL, 33, 7, NULL, '2026-09-05 15:17:52', '2026-09-05 15:17:52'),
(4, NULL, 33, 7, '2300', '2026-09-05 15:19:38', '2026-09-05 15:19:38'),
(5, NULL, 33, 7, '2150', '2026-09-05 15:23:47', '2026-09-05 15:23:47'),
(6, NULL, 33, 7, '5750', '2026-09-05 15:27:22', '2026-09-05 15:27:22'),
(7, NULL, 33, 12, '6300', '2026-09-06 07:38:31', '2026-09-06 07:38:31'),
(8, NULL, 33, 13, '3330', '2026-09-06 09:33:51', '2026-09-06 09:33:52'),
(9, NULL, 33, 7, '5750', '2026-09-07 07:52:36', '2026-09-07 07:52:36'),
(10, NULL, 33, 13, '4000', '2026-09-07 13:53:06', '2026-09-07 13:53:06'),
(11, NULL, 33, 11, '7390', '2026-09-07 16:41:04', '2026-09-07 16:41:04'),
(12, NULL, 33, 15, '3150', '2026-09-09 08:19:20', '2026-09-09 08:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_data`
--

CREATE TABLE `invoice_data` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_data`
--

INSERT INTO `invoice_data` (`id`, `invoice_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(10, 6, 1, 50, '2026-09-05 15:27:22', '2026-09-05 15:27:22'),
(11, 6, 3, 25, '2026-09-05 15:27:22', '2026-09-05 15:27:22'),
(12, 7, 1, 30, '2026-09-06 07:38:31', '2026-09-06 07:38:31'),
(13, 7, 3, 110, '2026-09-06 07:38:31', '2026-09-06 07:38:31'),
(14, 8, 1, 1, '2026-09-06 09:33:51', '2026-09-06 09:33:51'),
(15, 8, 3, 1, '2026-09-06 09:33:51', '2026-09-06 09:33:51'),
(16, 8, 4, 16, '2026-09-06 09:33:51', '2026-09-06 09:33:51'),
(17, 9, 1, 50, '2026-09-07 07:52:36', '2026-09-07 07:52:36'),
(18, 9, 3, 25, '2026-09-07 07:52:36', '2026-09-07 07:52:36'),
(19, 10, 1, 31, '2026-09-07 13:53:06', '2026-09-07 13:53:06'),
(20, 10, 3, 30, '2026-09-07 13:53:06', '2026-09-07 13:53:06'),
(21, 11, 1, 70, '2026-09-07 16:41:04', '2026-09-07 16:41:04'),
(22, 11, 3, 13, '2026-09-07 16:41:04', '2026-09-07 16:41:04'),
(23, 12, 1, 30, '2026-09-09 08:19:20', '2026-09-09 08:19:20'),
(24, 12, 3, 5, '2026-09-09 08:19:20', '2026-09-09 08:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_history`
--

CREATE TABLE `invoice_history` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `invoice_id` varchar(555) DEFAULT NULL,
  `driver_id` int(11) NOT NULL,
  `file_name` longtext NOT NULL,
  `path` longtext DEFAULT NULL,
  `invoice_grand_total` varchar(555) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `invoice_history`
--

INSERT INTO `invoice_history` (`id`, `shop_id`, `invoice_id`, `driver_id`, `file_name`, `path`, `invoice_grand_total`, `created_at`, `updated_at`) VALUES
(20, 7, '6', 33, '1788622051-kakali-furniture_05-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788622051-kakali-furniture_05-09-2026.pdf', '6920', '2026-09-05 15:27:32', '2026-09-05 15:27:32'),
(21, 7, '6', 33, '1788622141-kakali-furniture_05-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788622141-kakali-furniture_05-09-2026.pdf', '6920', '2026-09-05 15:29:02', '2026-09-05 15:29:02'),
(22, 12, '7', 33, '1788680312-shop-namer_06-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788680312-shop-namer_06-09-2026.pdf', '8028', '2026-09-06 07:38:34', '2026-09-06 07:38:34'),
(23, 13, '8', 33, '1788687233-mera-store_06-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788687233-mera-store_06-09-2026.pdf', '3679', '2026-09-06 09:33:55', '2026-09-06 09:33:55'),
(24, 7, '9', 33, '1788767576-kakali-furniture_07-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788767576-kakali-furniture_07-09-2026.pdf', '6920', '2026-09-07 07:52:57', '2026-09-07 07:52:57'),
(25, 13, '10', 33, '1788789187-mera-store_07-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788789187-mera-store_07-09-2026.pdf', '4882', '2026-09-07 13:53:08', '2026-09-07 13:53:08'),
(26, 11, '11', 33, '1788799265-snehalata-motors_07-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788799265-snehalata-motors_07-09-2026.pdf', '8790', '2026-09-07 16:41:06', '2026-09-07 16:41:06'),
(27, 15, '12', 33, '1788941960-sm-motors_09-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1788941960-sm-motors_09-09-2026.pdf', '3744', '2026-09-09 08:19:21', '2026-09-09 08:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` bigint(20) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `school_id` bigint(20) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `type`, `user_id`, `school_id`, `ip`, `created_at`) VALUES
(1, 'logout', 1, NULL, '::1', '2024-07-13 06:26:46'),
(2, 'login', 1, NULL, '::1', '2024-07-13 06:26:49'),
(3, 'logout', 1, NULL, '::1', '2024-07-13 06:28:01'),
(4, 'login', 1, NULL, '::1', '2024-07-13 07:29:15'),
(5, 'logout', 1, NULL, '::1', '2024-07-13 07:30:03'),
(6, 'login', 1, NULL, '::1', '2024-07-13 07:30:09'),
(7, 'logout', 1, NULL, '::1', '2024-07-13 07:41:26'),
(8, 'login', 1, NULL, '::1', '2024-07-13 07:44:00'),
(9, 'logout', 1, NULL, '::1', '2024-07-13 08:03:08'),
(10, 'login', 1, NULL, '::1', '2024-07-13 08:03:26'),
(11, 'logout', 1, NULL, '::1', '2024-07-13 08:27:01'),
(12, 'login', 1, NULL, '::1', '2024-07-13 08:27:46'),
(13, 'login', 1, NULL, '::1', '2024-07-13 10:29:14'),
(14, 'login', 1, NULL, '::1', '2024-07-13 10:42:49'),
(15, 'login', 1, NULL, '::1', '2024-07-13 12:55:32'),
(16, 'login', 1, NULL, '::1', '2024-07-15 05:19:16'),
(17, 'login', 1, NULL, '::1', '2024-07-15 06:29:42'),
(18, 'login', 1, NULL, '::1', '2024-07-15 07:12:34'),
(19, 'login', 1, NULL, '::1', '2024-07-15 07:30:49'),
(20, 'login', 1, NULL, '::1', '2024-07-15 08:00:27'),
(21, 'logout', 1, NULL, '::1', '2024-07-15 08:21:31'),
(22, 'login', 1, NULL, '::1', '2024-07-15 08:21:37'),
(23, 'logout', 1, NULL, '::1', '2024-07-15 09:43:49'),
(24, 'login', 1, NULL, '::1', '2024-07-15 10:00:46'),
(25, 'login', 1, NULL, '::1', '2024-07-16 05:28:42'),
(26, 'login', 1, NULL, '::1', '2024-07-16 08:11:54'),
(27, 'login', 1, NULL, '::1', '2024-07-25 11:09:36'),
(28, 'login', 1, NULL, '::1', '2024-07-31 06:08:25'),
(29, 'login', 1, NULL, '::1', '2024-08-01 06:53:38'),
(30, 'login', 1, NULL, '::1', '2024-08-01 08:12:52'),
(31, 'login', 1, NULL, '::1', '2024-08-02 07:01:34'),
(32, 'login', 1, NULL, '::1', '2024-08-03 07:33:14'),
(33, 'logout', 1, NULL, '::1', '2024-08-03 07:37:46'),
(34, 'login', 1, NULL, '::1', '2024-08-03 07:38:05'),
(35, 'logout', 1, NULL, '::1', '2024-08-03 07:38:21'),
(36, 'login', 1, NULL, '::1', '2024-08-03 07:46:09'),
(37, 'login', 1, NULL, '::1', '2024-08-05 11:57:40'),
(38, 'login', 1, NULL, '::1', '2024-08-06 05:15:36'),
(39, 'login', 1, NULL, '::1', '2024-08-07 07:06:22'),
(40, 'login', 1, NULL, '::1', '2024-08-08 10:04:49'),
(41, 'login', 1, NULL, '::1', '2024-08-09 09:17:12'),
(42, 'login', 1, NULL, '::1', '2024-08-09 15:02:49'),
(43, 'login', 1, NULL, '::1', '2024-08-10 05:10:35'),
(44, 'login', 1, NULL, '::1', '2024-08-10 08:23:38'),
(45, 'login', 1, NULL, '116.206.202.71', '2024-08-10 14:54:02'),
(46, 'login', 1, NULL, '121.241.210.182', '2024-08-12 05:27:17'),
(47, 'logout', 1, NULL, '121.241.210.182', '2024-08-12 09:40:33'),
(48, 'login', 1, NULL, '121.241.210.182', '2024-08-12 09:40:33'),
(49, 'login', 1, NULL, '115.187.42.141', '2024-08-12 10:18:09'),
(50, 'login', 1, NULL, '202.8.112.25', '2024-08-12 10:46:27'),
(51, 'login', 1, NULL, '122.161.52.78', '2024-08-12 12:48:34'),
(52, 'logout', 1, NULL, '115.187.42.141', '2024-08-12 13:18:27'),
(53, 'login', 1, NULL, '115.187.42.141', '2024-08-12 13:18:27'),
(54, 'logout', 1, NULL, '115.187.42.141', '2024-08-12 13:21:29'),
(55, 'login', 1, NULL, '115.187.42.141', '2024-08-12 13:21:29'),
(56, 'login', 1, NULL, '157.40.69.149', '2024-08-12 15:43:40'),
(57, 'logout', 1, NULL, '121.241.210.182', '2024-08-13 05:35:11'),
(58, 'login', 1, NULL, '121.241.210.182', '2024-08-13 05:35:11'),
(59, 'logout', 1, NULL, '115.187.42.141', '2024-08-13 07:32:48'),
(60, 'login', 1, NULL, '115.187.42.141', '2024-08-13 07:32:48'),
(61, 'logout', 1, NULL, '121.241.210.182', '2024-08-13 08:36:12'),
(62, 'login', 1, NULL, '121.241.210.182', '2024-08-13 08:36:13'),
(63, 'logout', 1, NULL, '115.187.42.141', '2024-08-13 13:22:02'),
(64, 'login', 1, NULL, '115.187.42.141', '2024-08-13 13:22:02'),
(65, 'logout', 1, NULL, '115.187.42.141', '2024-08-13 15:45:11'),
(66, 'login', 1, NULL, '115.187.42.141', '2024-08-13 15:45:11'),
(67, 'logout', 1, NULL, '121.241.210.182', '2024-08-14 03:59:51'),
(68, 'login', 1, NULL, '121.241.210.182', '2024-08-14 03:59:51'),
(69, 'login', 1, NULL, '115.187.42.14', '2024-08-14 08:34:53'),
(70, 'logout', 1, NULL, '121.241.210.182', '2024-08-14 08:49:23'),
(71, 'login', 1, NULL, '121.241.210.182', '2024-08-14 08:49:23'),
(72, 'logout', 1, NULL, '121.241.210.182', '2024-08-14 10:57:08'),
(73, 'login', 1, NULL, '121.241.210.182', '2024-08-14 10:57:08'),
(74, 'logout', 1, NULL, '115.187.42.14', '2024-08-14 14:38:44'),
(75, 'login', 1, NULL, '115.187.42.14', '2024-08-14 14:38:44'),
(76, 'login', 1, NULL, '122.161.51.139', '2024-08-15 11:18:00'),
(77, 'logout', 1, NULL, '121.241.210.182', '2024-08-16 09:45:47'),
(78, 'login', 1, NULL, '121.241.210.182', '2024-08-16 09:45:47'),
(79, 'logout', 1, NULL, '121.241.210.182', '2024-08-16 10:02:51'),
(80, 'logout', 1, NULL, '121.241.210.182', '2024-08-16 11:05:02'),
(81, 'login', 1, NULL, '121.241.210.182', '2024-08-16 11:05:02'),
(82, 'logout', 1, NULL, '121.241.210.182', '2024-08-16 11:25:44'),
(83, 'login', NULL, 1, '121.241.210.182', '2024-08-16 14:00:17'),
(84, 'logout', 1, NULL, '115.187.42.14', '2024-08-16 14:44:18'),
(85, 'login', 1, NULL, '115.187.42.14', '2024-08-16 14:44:18'),
(86, 'login', NULL, 1, '115.187.42.14', '2024-08-16 14:46:35'),
(87, 'logout', 1, NULL, '121.241.210.182', '2024-08-17 05:36:42'),
(88, 'login', 1, NULL, '121.241.210.182', '2024-08-17 05:36:42'),
(89, 'login', 1, NULL, '115.187.42.39', '2024-08-17 20:26:22'),
(90, 'login', 1, NULL, '122.161.53.18', '2024-08-20 10:06:27'),
(91, 'logout', 1, NULL, '122.161.53.18', '2024-08-20 15:50:07'),
(92, 'login', 1, NULL, '122.161.53.18', '2024-08-20 15:50:07'),
(93, 'login', 1, NULL, '103.87.143.197', '2024-08-24 17:12:09'),
(94, 'logout', 1, NULL, '121.241.210.182', '2024-08-28 13:59:39'),
(95, 'login', 1, NULL, '121.241.210.182', '2024-08-28 13:59:39'),
(96, 'logout', 1, NULL, '121.241.210.182', '2024-08-28 16:13:09'),
(97, 'login', 1, NULL, '121.241.210.182', '2024-08-28 16:13:09'),
(98, 'logout', 1, NULL, '121.241.210.182', '2024-08-28 18:29:43'),
(99, 'login', 1, NULL, '121.241.210.182', '2024-08-28 18:29:43'),
(100, 'logout', 1, NULL, '121.241.210.182', '2024-08-29 11:25:04'),
(101, 'login', 1, NULL, '121.241.210.182', '2024-08-29 11:25:04'),
(102, 'login', 1, NULL, '103.87.143.247', '2024-08-31 17:15:38'),
(103, 'login', 1, NULL, '103.87.143.89', '2024-09-05 11:12:48'),
(104, 'logout', 1, NULL, '103.87.143.89', '2024-09-06 19:02:23'),
(105, 'login', 1, NULL, '103.87.143.89', '2024-09-06 19:02:23'),
(106, 'login', 1, NULL, '115.187.42.42', '2024-09-06 20:19:21'),
(107, 'logout', 1, NULL, '115.187.42.42', '2024-09-07 15:57:16'),
(108, 'login', 1, NULL, '115.187.42.42', '2024-09-07 15:57:16'),
(109, 'logout', 1, NULL, '103.87.143.89', '2024-09-07 18:57:26'),
(110, 'login', 1, NULL, '103.87.143.89', '2024-09-07 18:57:26'),
(111, 'logout', 1, NULL, '115.187.42.42', '2024-09-07 19:44:27'),
(112, 'login', 1, NULL, '115.187.42.42', '2024-09-07 19:44:27'),
(113, 'login', 1, NULL, '202.8.112.85', '2024-09-10 12:03:31'),
(114, 'login', 1, NULL, '115.187.42.246', '2024-09-12 14:27:52'),
(115, 'logout', 1, NULL, '121.241.210.182', '2024-09-13 12:03:44'),
(116, 'login', 1, NULL, '121.241.210.182', '2024-09-13 12:03:44'),
(117, 'logout', 1, NULL, '115.187.42.246', '2024-09-13 13:46:01'),
(118, 'login', 1, NULL, '115.187.42.246', '2024-09-13 13:46:01'),
(119, 'logout', 1, NULL, '115.187.42.246', '2024-09-13 22:23:44'),
(120, 'login', 1, NULL, '115.187.42.246', '2024-09-13 22:23:44'),
(121, 'logout', 1, NULL, '103.87.143.89', '2024-09-21 11:44:36'),
(122, 'login', 1, NULL, '103.87.143.89', '2024-09-21 11:44:36'),
(123, 'logout', 1, NULL, '115.187.42.246', '2024-09-21 16:28:11'),
(124, 'login', 1, NULL, '115.187.42.246', '2024-09-21 16:28:11'),
(125, 'logout', 1, NULL, '115.187.42.246', '2024-09-21 18:37:07'),
(126, 'login', 1, NULL, '115.187.42.246', '2024-09-21 18:37:07'),
(127, 'logout', 1, NULL, '115.187.42.246', '2024-09-21 20:36:30'),
(128, 'login', 1, NULL, '115.187.42.246', '2024-09-21 20:36:30'),
(129, 'logout', 1, NULL, '121.241.210.182', '2024-09-23 12:23:47'),
(130, 'login', 1, NULL, '121.241.210.182', '2024-09-23 12:23:47'),
(131, 'logout', 1, NULL, '115.187.42.246', '2024-09-23 20:25:30'),
(132, 'login', 1, NULL, '115.187.42.246', '2024-09-23 20:25:30'),
(133, 'logout', 1, NULL, '115.187.42.246', '2024-09-24 16:31:27'),
(134, 'login', 1, NULL, '115.187.42.246', '2024-09-24 16:31:27'),
(135, 'logout', 1, NULL, '115.187.42.246', '2024-09-24 16:33:06'),
(136, 'logout', 1, NULL, '103.87.143.89', '2024-09-25 17:52:36'),
(137, 'login', 1, NULL, '103.87.143.89', '2024-09-25 17:52:36'),
(138, 'logout', 1, NULL, '121.241.210.182', '2024-09-26 13:18:04'),
(139, 'login', 1, NULL, '121.241.210.182', '2024-09-26 13:18:04'),
(140, 'login', 1, NULL, '115.187.42.158', '2024-09-27 18:17:49'),
(141, 'logout', 1, NULL, '115.187.42.158', '2024-09-28 11:11:39'),
(142, 'login', 1, NULL, '115.187.42.158', '2024-09-28 11:11:39'),
(143, 'login', 1, NULL, '103.87.143.169', '2024-09-28 11:49:58'),
(144, 'logout', 1, NULL, '115.187.42.158', '2024-09-28 17:43:51'),
(145, 'login', 1, NULL, '115.187.42.158', '2024-09-28 17:43:51'),
(146, 'logout', 1, NULL, '115.187.42.158', '2024-09-28 19:55:50'),
(147, 'login', 1, NULL, '115.187.42.158', '2024-09-28 19:55:50'),
(148, 'logout', 1, NULL, '103.87.143.169', '2024-10-02 11:12:33'),
(149, 'login', 1, NULL, '103.87.143.169', '2024-10-02 11:12:33'),
(150, 'logout', 1, NULL, '115.187.42.158', '2024-10-02 13:52:38'),
(151, 'login', 1, NULL, '115.187.42.158', '2024-10-02 13:52:38'),
(152, 'logout', 1, NULL, '115.187.42.158', '2024-10-02 18:00:13'),
(153, 'login', 1, NULL, '115.187.42.158', '2024-10-02 18:00:13'),
(154, 'logout', 1, NULL, '115.187.42.158', '2024-10-02 20:34:03'),
(155, 'login', 1, NULL, '115.187.42.158', '2024-10-02 20:34:03'),
(156, 'logout', 1, NULL, '121.241.210.182', '2024-10-03 11:07:57'),
(157, 'login', 1, NULL, '121.241.210.182', '2024-10-03 11:07:57'),
(158, 'logout', 1, NULL, '121.241.210.182', '2024-10-03 12:31:11'),
(159, 'login', 1, NULL, '121.241.210.182', '2024-10-03 12:31:11'),
(160, 'login', 1, NULL, '116.206.202.10', '2024-10-03 19:21:07'),
(161, 'login', 1, NULL, '122.161.51.56', '2024-10-04 12:17:28'),
(162, 'login', 1, NULL, '122.161.193.120', '2024-10-04 12:51:58'),
(163, 'logout', NULL, 1, '121.241.210.182', '2024-10-04 13:59:45'),
(164, 'login', NULL, 1, '121.241.210.182', '2024-10-04 13:59:45'),
(165, 'logout', NULL, 1, '121.241.210.182', '2024-10-04 14:29:14'),
(166, 'login', NULL, 1, '121.241.210.182', '2024-10-04 14:29:14'),
(167, 'logout', 1, NULL, '121.241.210.182', '2024-10-04 15:28:24'),
(168, 'login', 1, NULL, '121.241.210.182', '2024-10-04 15:28:24'),
(169, 'logout', NULL, 1, '121.241.210.182', '2024-10-04 16:12:39'),
(170, 'logout', NULL, 1, '121.241.210.182', '2024-10-04 16:22:49'),
(171, 'login', NULL, 1, '121.241.210.182', '2024-10-04 16:22:49'),
(172, 'logout', NULL, 1, '121.241.210.182', '2024-10-04 16:22:58'),
(173, 'login', NULL, 2, '116.206.202.10', '2024-10-04 16:26:18'),
(174, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 16:30:07'),
(175, 'login', NULL, 2, '116.206.202.10', '2024-10-04 16:30:07'),
(176, 'logout', 1, NULL, '116.206.202.10', '2024-10-04 16:30:12'),
(177, 'login', 1, NULL, '116.206.202.10', '2024-10-04 16:30:12'),
(178, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 16:30:44'),
(179, 'logout', 1, NULL, '116.206.202.10', '2024-10-04 16:31:15'),
(180, 'logout', 1, NULL, '116.206.202.10', '2024-10-04 16:33:12'),
(181, 'login', 1, NULL, '116.206.202.10', '2024-10-04 16:33:12'),
(182, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 16:33:24'),
(183, 'login', NULL, 2, '116.206.202.10', '2024-10-04 16:33:24'),
(184, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 16:33:39'),
(185, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 16:35:18'),
(186, 'login', NULL, 2, '116.206.202.10', '2024-10-04 16:35:18'),
(187, 'logout', 1, NULL, '115.187.42.158', '2024-10-04 18:52:52'),
(188, 'login', 1, NULL, '115.187.42.158', '2024-10-04 18:52:52'),
(189, 'logout', NULL, 2, '116.206.202.10', '2024-10-04 20:27:53'),
(190, 'login', NULL, 1, '115.187.42.158', '2024-10-04 20:28:17'),
(191, 'logout', NULL, 2, '116.206.202.10', '2024-10-05 10:25:02'),
(192, 'login', NULL, 2, '116.206.202.10', '2024-10-05 10:25:02'),
(193, 'logout', 1, NULL, '116.206.202.10', '2024-10-05 11:13:23'),
(194, 'login', 1, NULL, '116.206.202.10', '2024-10-05 11:13:23'),
(195, 'logout', NULL, 1, '115.187.42.158', '2024-10-05 13:05:15'),
(196, 'login', NULL, 1, '115.187.42.158', '2024-10-05 13:05:15'),
(197, 'logout', NULL, 1, '115.187.42.158', '2024-10-05 13:11:33'),
(198, 'login', NULL, 2, '115.187.42.158', '2024-10-05 13:11:47'),
(199, 'logout', 1, NULL, '115.187.42.158', '2024-10-05 13:13:00'),
(200, 'login', 1, NULL, '115.187.42.158', '2024-10-05 13:13:00'),
(201, 'logout', 1, NULL, '115.187.42.158', '2024-10-05 17:06:04'),
(202, 'login', 1, NULL, '115.187.42.158', '2024-10-05 17:06:04'),
(203, 'logout', NULL, 2, '115.187.42.158', '2024-10-05 18:28:20'),
(204, 'login', NULL, 2, '115.187.42.158', '2024-10-05 18:28:20'),
(205, 'logout', 1, NULL, '122.161.193.120', '2024-10-07 09:46:07'),
(206, 'login', 1, NULL, '122.161.193.120', '2024-10-07 09:46:07'),
(207, 'login', 1, NULL, '103.87.143.75', '2024-10-08 11:12:05'),
(208, 'logout', 1, NULL, '122.161.193.120', '2024-10-08 12:05:30'),
(209, 'login', 1, NULL, '122.161.193.120', '2024-10-08 12:05:30'),
(210, 'logout', 1, NULL, '115.187.42.158', '2024-10-08 20:17:11'),
(211, 'login', 1, NULL, '115.187.42.158', '2024-10-08 20:17:11'),
(212, 'logout', 1, NULL, '103.87.143.75', '2024-10-09 10:13:35'),
(213, 'login', 1, NULL, '103.87.143.75', '2024-10-09 10:13:35'),
(214, 'logout', 1, NULL, '122.161.193.120', '2024-10-09 10:36:49'),
(215, 'login', 1, NULL, '122.161.193.120', '2024-10-09 10:36:49'),
(216, 'logout', 1, NULL, '115.187.42.158', '2024-10-09 11:47:45'),
(217, 'login', 1, NULL, '115.187.42.158', '2024-10-09 11:47:45'),
(218, 'logout', 1, NULL, '122.161.193.120', '2024-10-09 14:23:26'),
(219, 'login', 1, NULL, '122.161.193.120', '2024-10-09 14:23:26'),
(220, 'logout', 1, NULL, '115.187.42.158', '2024-10-09 16:20:07'),
(221, 'login', 1, NULL, '115.187.42.158', '2024-10-09 16:20:07'),
(222, 'logout', 1, NULL, '115.187.42.158', '2024-10-09 16:45:55'),
(223, 'login', 1, NULL, '115.187.42.158', '2024-10-09 16:45:55'),
(224, 'login', 1, NULL, '27.59.69.180', '2024-10-15 10:44:15'),
(225, 'logout', 1, NULL, '122.161.193.120', '2024-10-15 11:40:43'),
(226, 'login', 1, NULL, '122.161.193.120', '2024-10-15 11:40:43'),
(227, 'logout', 1, NULL, '122.161.193.120', '2024-10-15 16:34:11'),
(228, 'login', 1, NULL, '122.161.193.120', '2024-10-15 16:34:11'),
(229, 'logout', 1, NULL, '122.161.193.120', '2024-10-16 09:46:13'),
(230, 'login', 1, NULL, '122.161.193.120', '2024-10-16 09:46:13'),
(231, 'login', 1, NULL, '116.206.202.7', '2024-10-16 12:00:38'),
(232, 'logout', 1, NULL, '122.161.193.120', '2024-10-16 15:32:49'),
(233, 'login', 1, NULL, '122.161.193.120', '2024-10-16 15:32:49'),
(234, 'logout', 1, NULL, '115.187.42.158', '2024-10-16 17:21:40'),
(235, 'login', 1, NULL, '115.187.42.158', '2024-10-16 17:21:40'),
(236, 'logout', 1, NULL, '116.206.202.7', '2024-10-17 12:39:47'),
(237, 'login', 1, NULL, '116.206.202.7', '2024-10-17 12:39:47'),
(238, 'logout', 1, NULL, '115.187.42.158', '2024-10-17 19:47:21'),
(239, 'login', 1, NULL, '115.187.42.158', '2024-10-17 19:47:21'),
(240, 'logout', 1, NULL, '122.161.193.120', '2024-10-18 09:50:53'),
(241, 'login', 1, NULL, '122.161.193.120', '2024-10-18 09:50:53'),
(242, 'logout', 1, NULL, '116.206.202.7', '2024-10-18 11:55:01'),
(243, 'login', 1, NULL, '116.206.202.7', '2024-10-18 11:55:01'),
(244, 'logout', 1, NULL, '122.161.193.120', '2024-10-18 15:11:38'),
(245, 'login', 1, NULL, '122.161.193.120', '2024-10-18 15:11:38'),
(246, 'logout', 1, NULL, '121.241.210.182', '2024-10-18 18:23:47'),
(247, 'login', 1, NULL, '121.241.210.182', '2024-10-18 18:23:47'),
(248, 'logout', 1, NULL, '122.161.193.120', '2024-10-19 09:36:24'),
(249, 'login', 1, NULL, '122.161.193.120', '2024-10-19 09:36:24'),
(250, 'logout', 1, NULL, '115.187.42.158', '2024-10-19 09:37:42'),
(251, 'login', 1, NULL, '115.187.42.158', '2024-10-19 09:37:42'),
(252, 'logout', 1, NULL, '116.206.202.7', '2024-10-19 10:04:10'),
(253, 'login', 1, NULL, '116.206.202.7', '2024-10-19 10:04:10'),
(254, 'login', 1, NULL, '49.37.39.27', '2024-10-19 10:26:09'),
(255, 'login', 1, NULL, '157.40.163.226', '2024-10-19 11:44:19'),
(256, 'logout', 1, NULL, '115.187.42.158', '2024-10-19 11:45:55'),
(257, 'login', 1, NULL, '115.187.42.158', '2024-10-19 11:45:55'),
(258, 'logout', 1, NULL, '115.187.42.158', '2024-10-19 12:18:06'),
(259, 'login', 1, NULL, '115.187.42.158', '2024-10-19 12:18:07'),
(260, 'logout', 1, NULL, '122.161.193.120', '2024-10-21 13:49:33'),
(261, 'login', 1, NULL, '122.161.193.120', '2024-10-21 13:49:33'),
(262, 'logout', 1, NULL, '122.161.193.120', '2024-10-22 09:34:25'),
(263, 'login', 1, NULL, '122.161.193.120', '2024-10-22 09:34:25'),
(264, 'logout', 1, NULL, '122.161.193.120', '2024-10-22 12:55:42'),
(265, 'login', 1, NULL, '122.161.193.120', '2024-10-22 12:55:42'),
(266, 'login', 1, NULL, '116.206.202.15', '2024-10-24 10:30:39'),
(267, 'login', 1, NULL, '115.187.42.78', '2024-10-24 10:47:05'),
(268, 'logout', 1, NULL, '122.161.193.120', '2024-10-29 12:36:43'),
(269, 'login', 1, NULL, '122.161.193.120', '2024-10-29 12:36:43'),
(270, 'login', NULL, 2, '122.161.193.120', '2024-10-29 12:37:48'),
(271, 'login', 1, NULL, '103.87.143.114', '2024-10-29 18:48:21'),
(272, 'logout', 1, NULL, '49.37.39.27', '2024-10-29 19:28:42'),
(273, 'login', 1, NULL, '49.37.39.27', '2024-10-29 19:28:42'),
(274, 'logout', 1, NULL, '122.161.193.120', '2024-10-30 10:09:10'),
(275, 'login', 1, NULL, '122.161.193.120', '2024-10-30 10:09:10'),
(276, 'login', 1, NULL, '116.206.202.121', '2024-10-30 18:06:49'),
(277, 'logout', 1, NULL, '115.187.42.78', '2024-10-30 20:09:39'),
(278, 'login', 1, NULL, '115.187.42.78', '2024-10-30 20:09:39'),
(279, 'login', 1, NULL, '122.161.73.189', '2024-11-05 11:43:11'),
(280, 'logout', 1, NULL, '122.161.193.120', '2024-11-05 15:18:14'),
(281, 'login', 1, NULL, '122.161.193.120', '2024-11-05 15:18:14'),
(282, 'logout', 1, NULL, '122.161.193.120', '2024-11-09 16:33:28'),
(283, 'login', 1, NULL, '122.161.193.120', '2024-11-09 16:33:28'),
(284, 'logout', 1, NULL, '122.161.193.120', '2024-11-11 11:12:27'),
(285, 'login', 1, NULL, '122.161.193.120', '2024-11-11 11:12:27'),
(286, 'login', 1, NULL, '115.187.42.108', '2024-11-11 12:31:27'),
(287, 'logout', 1, NULL, '122.161.193.120', '2024-11-11 13:58:11'),
(288, 'login', 1, NULL, '122.161.193.120', '2024-11-11 13:58:11'),
(289, 'logout', NULL, 2, '122.161.193.120', '2024-11-11 14:01:39'),
(290, 'login', NULL, 2, '122.161.193.120', '2024-11-11 14:01:39'),
(291, 'login', NULL, 2, '115.187.42.108', '2024-11-11 14:49:31'),
(292, 'login', 1, NULL, '27.59.79.57', '2024-11-11 15:01:41'),
(293, 'logout', 1, NULL, '122.161.193.120', '2024-11-12 10:24:49'),
(294, 'login', 1, NULL, '122.161.193.120', '2024-11-12 10:24:49'),
(295, 'logout', 1, NULL, '122.161.193.120', '2024-11-15 10:24:19'),
(296, 'login', 1, NULL, '122.161.193.120', '2024-11-15 10:24:19'),
(297, 'logout', 1, NULL, '122.161.193.120', '2024-11-18 15:57:10'),
(298, 'login', 1, NULL, '122.161.193.120', '2024-11-18 15:57:10'),
(299, 'logout', 1, NULL, '122.161.193.120', '2024-11-19 09:58:04'),
(300, 'login', 1, NULL, '122.161.193.120', '2024-11-19 09:58:04'),
(301, 'logout', 1, NULL, '122.161.193.120', '2024-11-21 10:20:01'),
(302, 'login', 1, NULL, '122.161.193.120', '2024-11-21 10:20:01'),
(303, 'logout', 1, NULL, '122.161.193.120', '2024-11-21 13:49:46'),
(304, 'login', 1, NULL, '122.161.193.120', '2024-11-21 13:49:46'),
(305, 'logout', 1, NULL, '122.161.193.120', '2024-11-22 15:34:08'),
(306, 'login', 1, NULL, '122.161.193.120', '2024-11-22 15:34:08'),
(307, 'login', 1, NULL, '103.87.143.132', '2024-11-23 20:39:46'),
(308, 'logout', 1, NULL, '122.161.193.120', '2024-11-25 11:29:41'),
(309, 'login', 1, NULL, '122.161.193.120', '2024-11-25 11:29:41'),
(310, 'logout', 1, NULL, '122.161.193.120', '2024-11-25 14:00:57'),
(311, 'logout', 1, NULL, '122.161.193.120', '2024-11-25 14:19:04'),
(312, 'login', 1, NULL, '122.161.193.120', '2024-11-25 14:19:04'),
(313, 'logout', 1, NULL, '122.161.193.120', '2024-11-26 11:55:13'),
(314, 'login', 1, NULL, '122.161.193.120', '2024-11-26 11:55:13'),
(315, 'logout', 1, NULL, '122.161.193.120', '2024-11-26 14:17:27'),
(316, 'login', 1, NULL, '122.161.193.120', '2024-11-26 14:17:27'),
(317, 'logout', 1, NULL, '122.161.193.120', '2024-11-27 10:15:31'),
(318, 'login', 1, NULL, '122.161.193.120', '2024-11-27 10:15:31'),
(319, 'logout', 1, NULL, '122.161.193.120', '2024-11-28 10:57:13'),
(320, 'login', 1, NULL, '122.161.193.120', '2024-11-28 10:57:13'),
(321, 'login', 1, NULL, '116.206.202.51', '2024-11-28 11:46:28'),
(322, 'logout', 1, NULL, '122.161.193.120', '2024-11-28 14:00:36'),
(323, 'login', 1, NULL, '122.161.193.120', '2024-11-28 14:00:36'),
(324, 'logout', 1, NULL, '122.161.193.120', '2024-11-28 15:45:46'),
(325, 'login', 1, NULL, '122.161.193.120', '2024-11-28 15:45:46'),
(326, 'logout', 1, NULL, '121.241.210.182', '2024-11-29 10:30:18'),
(327, 'login', 1, NULL, '121.241.210.182', '2024-11-29 10:30:18'),
(328, 'logout', 1, NULL, '122.161.193.120', '2024-11-29 10:55:25'),
(329, 'login', 1, NULL, '122.161.193.120', '2024-11-29 10:55:25'),
(330, 'logout', 1, NULL, '116.206.202.51', '2024-11-29 20:34:56'),
(331, 'login', 1, NULL, '116.206.202.51', '2024-11-29 20:34:56'),
(332, 'logout', 1, NULL, '122.161.193.120', '2024-11-30 11:15:07'),
(333, 'login', 1, NULL, '122.161.193.120', '2024-11-30 11:15:07'),
(334, 'logout', 1, NULL, '122.161.193.120', '2024-12-02 11:24:09'),
(335, 'login', 1, NULL, '122.161.193.120', '2024-12-02 11:24:09'),
(336, 'logout', 1, NULL, '122.161.193.120', '2024-12-02 13:58:39'),
(337, 'login', 1, NULL, '122.161.193.120', '2024-12-02 13:58:39'),
(338, 'logout', 1, NULL, '122.161.193.120', '2024-12-03 11:15:47'),
(339, 'login', 1, NULL, '122.161.193.120', '2024-12-03 11:15:47'),
(340, 'login', 1, NULL, '122.161.73.134', '2024-12-03 11:41:27'),
(341, 'logout', 1, NULL, '122.161.193.120', '2024-12-03 14:02:19'),
(342, 'login', 1, NULL, '122.161.193.120', '2024-12-03 14:02:19'),
(343, 'logout', 1, NULL, '122.161.193.120', '2024-12-03 16:03:26'),
(344, 'login', 1, NULL, '122.161.193.120', '2024-12-03 16:03:26'),
(345, 'logout', 1, NULL, '122.161.193.120', '2024-12-04 09:36:04'),
(346, 'login', 1, NULL, '122.161.193.120', '2024-12-04 09:36:04'),
(347, 'logout', 1, NULL, '122.161.193.120', '2024-12-04 10:20:33'),
(348, 'login', 1, NULL, '122.161.193.120', '2024-12-04 10:20:33'),
(349, 'logout', 1, NULL, '121.241.210.182', '2024-12-04 12:08:57'),
(350, 'login', 1, NULL, '121.241.210.182', '2024-12-04 12:08:57'),
(351, 'logout', 1, NULL, '121.241.210.182', '2024-12-04 20:13:14'),
(352, 'login', 1, NULL, '121.241.210.182', '2024-12-04 20:13:14'),
(353, 'logout', 1, NULL, '122.161.193.120', '2024-12-05 11:16:54'),
(354, 'login', 1, NULL, '122.161.193.120', '2024-12-05 11:16:54'),
(355, 'logout', 1, NULL, '122.161.193.120', '2024-12-05 11:19:40'),
(356, 'login', 1, NULL, '122.161.193.120', '2024-12-05 11:19:40'),
(357, 'logout', 1, NULL, '121.241.210.182', '2024-12-05 12:24:27'),
(358, 'login', 1, NULL, '121.241.210.182', '2024-12-05 12:24:27'),
(359, 'logout', 1, NULL, '122.161.193.120', '2024-12-05 14:35:56'),
(360, 'login', 1, NULL, '122.161.193.120', '2024-12-05 14:35:56'),
(361, 'login', 1, NULL, '115.187.42.103', '2024-12-05 20:14:34'),
(362, 'logout', 1, NULL, '122.161.193.120', '2024-12-06 09:39:29'),
(363, 'login', 1, NULL, '122.161.193.120', '2024-12-06 09:39:29'),
(364, 'login', NULL, 8, '122.161.193.120', '2024-12-06 09:47:22'),
(365, 'logout', 1, NULL, '122.161.193.120', '2024-12-06 10:12:18'),
(366, 'login', 1, NULL, '122.161.193.120', '2024-12-06 10:12:18'),
(367, 'logout', 1, NULL, '116.206.202.51', '2024-12-06 10:43:17'),
(368, 'login', 1, NULL, '116.206.202.51', '2024-12-06 10:43:17'),
(369, 'login', NULL, 8, '116.206.202.51', '2024-12-06 10:46:34'),
(370, 'logout', 1, NULL, '121.241.210.182', '2024-12-06 12:54:49'),
(371, 'logout', 1, NULL, '121.241.210.182', '2024-12-06 12:55:08'),
(372, 'login', 1, NULL, '121.241.210.182', '2024-12-06 12:55:08'),
(373, 'logout', NULL, 8, '122.161.193.120', '2024-12-06 14:31:55'),
(374, 'login', NULL, 8, '122.161.193.120', '2024-12-06 14:31:55'),
(375, 'logout', 1, NULL, '121.241.210.182', '2024-12-06 14:50:48'),
(376, 'login', 1, NULL, '121.241.210.182', '2024-12-06 14:50:48'),
(377, 'logout', 1, NULL, '122.161.193.120', '2024-12-06 14:52:57'),
(378, 'login', 1, NULL, '122.161.193.120', '2024-12-06 14:52:57'),
(379, 'logout', 1, NULL, '115.187.42.103', '2024-12-06 14:53:05'),
(380, 'login', 1, NULL, '115.187.42.103', '2024-12-06 14:53:05'),
(381, 'logout', 1, NULL, '121.241.210.182', '2024-12-06 14:57:48'),
(382, 'login', 1, NULL, '121.241.210.182', '2024-12-06 14:57:48'),
(383, 'logout', 1, NULL, '121.241.210.182', '2024-12-06 17:49:55'),
(384, 'login', 1, NULL, '121.241.210.182', '2024-12-06 17:49:55'),
(385, 'logout', 1, NULL, '115.187.42.103', '2024-12-06 20:41:09'),
(386, 'login', 1, NULL, '115.187.42.103', '2024-12-06 20:41:09'),
(387, 'logout', 1, NULL, '122.161.193.120', '2024-12-07 09:42:32'),
(388, 'login', 1, NULL, '122.161.193.120', '2024-12-07 09:42:32'),
(389, 'logout', 1, NULL, '116.206.202.51', '2024-12-07 10:48:39'),
(390, 'login', 1, NULL, '116.206.202.51', '2024-12-07 10:48:39'),
(391, 'logout', 1, NULL, '122.161.193.120', '2024-12-07 11:06:03'),
(392, 'login', 1, NULL, '122.161.193.120', '2024-12-07 11:06:03'),
(393, 'login', 1, NULL, '157.40.163.125', '2024-12-07 13:07:03'),
(394, 'logout', 1, NULL, '116.206.202.51', '2024-12-07 20:20:33'),
(395, 'login', 1, NULL, '116.206.202.51', '2024-12-07 20:20:33'),
(396, 'logout', 1, NULL, '115.187.42.103', '2024-12-07 20:53:03'),
(397, 'login', 1, NULL, '115.187.42.103', '2024-12-07 20:53:03'),
(398, 'logout', 1, NULL, '121.241.210.182', '2024-12-09 11:18:42'),
(399, 'login', 1, NULL, '121.241.210.182', '2024-12-09 11:18:42'),
(400, 'login', 2, NULL, '121.241.210.182', '2024-12-09 11:32:07'),
(401, 'logout', 1, NULL, '122.161.193.120', '2024-12-09 14:32:35'),
(402, 'login', 1, NULL, '122.161.193.120', '2024-12-09 14:32:35'),
(403, 'logout', NULL, 8, '122.161.193.120', '2024-12-09 14:32:54'),
(404, 'login', NULL, 8, '122.161.193.120', '2024-12-09 14:32:54'),
(405, 'logout', 1, NULL, '122.161.193.120', '2024-12-09 17:19:59'),
(406, 'login', 1, NULL, '122.161.193.120', '2024-12-09 17:19:59'),
(407, 'logout', 1, NULL, '115.187.42.103', '2024-12-09 20:19:24'),
(408, 'login', 1, NULL, '115.187.42.103', '2024-12-09 20:19:24'),
(409, 'login', 1, NULL, '103.87.143.225', '2024-12-09 20:19:32'),
(410, 'logout', 1, NULL, '115.187.42.103', '2024-12-09 20:21:04'),
(411, 'login', 2, NULL, '115.187.42.103', '2024-12-09 20:21:13'),
(412, 'logout', 1, NULL, '115.187.42.103', '2024-12-09 20:21:31'),
(413, 'login', 1, NULL, '115.187.42.103', '2024-12-09 20:21:31'),
(414, 'logout', 1, NULL, '103.87.143.225', '2024-12-11 10:42:52'),
(415, 'login', 1, NULL, '103.87.143.225', '2024-12-11 10:42:52'),
(416, 'login', 2, NULL, '103.87.143.225', '2024-12-11 11:17:14'),
(417, 'login', 1, NULL, '122.161.72.99', '2024-12-12 10:11:39'),
(418, 'login', 1, NULL, '::1', '2024-12-17 11:25:09'),
(419, 'login', 1, NULL, '::1', '2024-12-17 17:20:39'),
(420, 'login', 1, NULL, '::1', '2024-12-18 11:30:47'),
(421, 'logout', 1, NULL, '::1', '2024-12-18 11:31:47'),
(422, 'login', 1, NULL, '::1', '2024-12-18 11:31:52'),
(423, 'login', 1, NULL, '::1', '2024-12-18 15:47:32'),
(424, 'login', 1, NULL, '::1', '2024-12-19 11:18:12'),
(425, 'login', 1, NULL, '::1', '2024-12-20 16:51:11'),
(426, 'login', 1, NULL, '::1', '2024-12-20 19:27:41'),
(427, 'login', 1, NULL, '::1', '2024-12-21 15:28:20'),
(428, 'login', 1, NULL, '::1', '2024-12-23 12:08:53'),
(429, 'login', 1, NULL, '::1', '2024-12-23 16:35:24'),
(430, 'login', 1, NULL, '::1', '2024-12-24 17:44:01'),
(431, 'login', 1, NULL, '::1', '2024-12-26 11:27:09'),
(432, 'login', 1, NULL, '::1', '2024-12-27 11:39:39'),
(433, 'login', 1, NULL, '::1', '2024-12-27 16:23:58'),
(434, 'login', 1, NULL, '::1', '2024-12-27 17:52:39'),
(435, 'login', 1, NULL, '::1', '2024-12-28 11:13:45'),
(436, 'login', 1, NULL, '::1', '2024-12-28 15:48:25'),
(437, 'login', 1, NULL, '::1', '2024-12-30 12:03:19'),
(438, 'login', 1, NULL, '::1', '2024-12-30 19:08:49'),
(439, 'login', 1, NULL, '::1', '2025-01-02 17:15:36'),
(440, 'login', 1, NULL, '::1', '2025-01-03 12:48:04'),
(441, 'login', 1, NULL, '::1', '2025-01-03 15:55:26'),
(442, 'login', 1, NULL, '::1', '2025-01-03 23:37:46'),
(443, 'login', 1, NULL, '::1', '2025-01-06 12:30:21'),
(444, 'login', 1, NULL, '::1', '2025-01-06 16:21:24'),
(445, 'login', 1, NULL, '::1', '2025-01-06 18:37:32'),
(446, 'login', 1, NULL, '::1', '2025-01-07 11:45:58'),
(447, 'login', 1, NULL, '::1', '2025-01-07 16:03:08'),
(448, 'login', 1, NULL, '::1', '2025-01-07 18:49:55'),
(449, 'login', 1, NULL, '::1', '2025-01-08 11:30:38'),
(450, 'login', 1, NULL, '::1', '2025-01-09 12:32:19'),
(451, 'login', 1, NULL, '::1', '2025-01-10 16:02:00'),
(452, 'login', 1, NULL, '::1', '2025-01-11 11:47:46'),
(453, 'login', 1, NULL, '::1', '2025-01-11 15:55:35'),
(454, 'login', 1, NULL, '::1', '2025-01-14 14:06:27'),
(455, 'login', 1, NULL, '::1', '2025-01-15 18:51:02'),
(456, 'login', 1, NULL, '::1', '2025-01-28 19:52:39'),
(457, 'login', 1, NULL, '::1', '2025-01-31 16:58:32'),
(458, 'login', 1, NULL, '::1', '2025-02-01 12:45:45'),
(459, 'login', 1, NULL, '::1', '2025-02-04 18:05:35'),
(460, 'login', 1, NULL, '::1', '2025-02-05 19:07:54'),
(461, 'login', 1, NULL, '::1', '2025-02-08 12:08:50'),
(462, 'login', 1, NULL, '152.58.126.254', '2025-02-11 22:55:51'),
(463, 'logout', 1, NULL, '152.58.126.254', '2025-02-11 23:05:04'),
(464, 'login', 1, NULL, '202.8.112.109', '2025-02-15 11:02:39'),
(465, 'login', 1, NULL, '::1', '2025-02-19 18:00:50'),
(466, 'login', 1, NULL, '::1', '2025-02-27 20:31:30'),
(467, 'login', 1, NULL, '::1', '2025-02-28 11:19:28'),
(468, 'login', 1, NULL, '::1', '2025-03-04 15:54:59'),
(469, 'login', 1, NULL, '::1', '2025-03-06 18:00:47'),
(470, 'login', 1, NULL, '::1', '2025-03-08 11:51:05'),
(471, 'login', 1, NULL, '::1', '2025-03-19 20:59:08'),
(472, 'login', 1, NULL, '::1', '2025-03-20 13:01:50'),
(473, 'login', 1, NULL, '::1', '2025-03-21 11:06:38'),
(474, 'login', 1, NULL, '::1', '2025-03-21 15:44:07'),
(475, 'login', 1, NULL, '::1', '2025-03-22 11:46:25'),
(476, 'login', 1, NULL, '::1', '2025-03-22 15:57:51'),
(477, 'login', 1, NULL, '::1', '2025-03-24 11:27:59'),
(478, 'login', 1, NULL, '::1', '2025-03-24 18:16:43'),
(479, 'login', 1, NULL, '::1', '2025-03-25 13:21:23'),
(480, 'login', 1, NULL, '::1', '2025-03-27 12:43:56'),
(481, 'login', 1, NULL, '::1', '2025-03-27 15:26:00'),
(482, 'login', 1, NULL, '::1', '2025-03-28 13:41:26'),
(483, 'login', 1, NULL, '::1', '2025-03-29 15:19:41'),
(484, 'login', 1, NULL, '::1', '2025-03-31 16:19:53'),
(485, 'login', 1, NULL, '::1', '2025-04-01 13:02:12'),
(486, 'login', 1, NULL, '::1', '2025-04-01 18:41:53'),
(487, 'login', 1, NULL, '::1', '2025-04-02 11:28:15'),
(488, 'login', 1, NULL, '::1', '2025-04-22 16:46:03'),
(489, 'login', 1, NULL, '::1', '2025-04-23 17:55:34'),
(490, 'login', 1, NULL, '::1', '2025-04-24 11:26:45'),
(491, 'login', 1, NULL, '::1', '2025-04-24 16:48:19'),
(492, 'login', 1, NULL, '::1', '2025-04-25 11:23:33'),
(493, 'login', 1, NULL, '::1', '2025-04-25 17:48:20'),
(494, 'login', 1, NULL, '::1', '2025-04-26 17:25:04'),
(495, 'login', 1, NULL, '::1', '2025-04-28 11:24:56'),
(496, 'login', 1, NULL, '::1', '2025-05-16 11:30:45'),
(497, 'login', 1, NULL, '::1', '2025-05-20 18:07:42'),
(498, 'login', 1, NULL, '::1', '2025-05-23 19:10:05'),
(499, 'login', 1, NULL, '::1', '2025-05-24 20:00:52'),
(500, 'login', 1, NULL, '::1', '2025-06-02 17:28:21'),
(501, 'login', 1, NULL, '::1', '2025-06-03 12:20:21'),
(502, 'login', 1, NULL, '::1', '2025-06-03 16:34:26'),
(503, 'login', 1, NULL, '::1', '2025-06-06 18:57:37'),
(504, 'login', 1, NULL, '::1', '2025-06-07 11:07:28'),
(505, 'login', 1, NULL, '202.8.112.193', '2025-06-07 13:01:10'),
(506, 'login', 1, NULL, '116.206.202.243', '2025-06-09 14:31:04'),
(507, 'logout', 1, NULL, '202.8.112.193', '2025-06-10 17:32:11'),
(508, 'login', 1, NULL, '202.8.112.193', '2025-06-10 17:32:11'),
(509, 'logout', 1, NULL, '202.8.112.193', '2025-06-10 20:53:23'),
(510, 'login', 1, NULL, '202.8.112.193', '2025-06-10 20:53:23'),
(511, 'logout', 1, NULL, '116.206.202.243', '2025-06-11 10:32:09'),
(512, 'login', 1, NULL, '116.206.202.243', '2025-06-11 10:32:09'),
(513, 'login', 1, NULL, '103.102.117.163', '2025-06-13 15:30:50'),
(514, 'logout', 1, NULL, '103.102.117.163', '2025-06-15 11:11:20'),
(515, 'login', 1, NULL, '103.102.117.163', '2025-06-15 11:11:20'),
(516, 'login', 1, NULL, '103.102.117.176', '2025-06-17 17:07:58'),
(517, 'logout', 1, NULL, '103.102.117.176', '2025-06-18 16:55:02'),
(518, 'login', 1, NULL, '103.102.117.176', '2025-06-18 16:55:02'),
(519, 'logout', 1, NULL, '103.102.117.176', '2025-06-19 18:16:13'),
(520, 'login', 1, NULL, '103.102.117.176', '2025-06-19 18:16:13'),
(521, 'logout', 1, NULL, '103.102.117.176', '2025-06-19 18:29:30'),
(522, 'login', 2, NULL, '103.102.117.176', '2025-06-19 18:30:04'),
(523, 'logout', 1, NULL, '103.102.117.176', '2025-06-19 18:31:20'),
(524, 'login', 1, NULL, '103.102.117.176', '2025-06-19 18:31:20'),
(525, 'logout', 1, NULL, '103.102.117.176', '2025-06-20 11:57:05'),
(526, 'login', 1, NULL, '103.102.117.176', '2025-06-20 11:57:05'),
(527, 'logout', 1, NULL, '103.102.117.176', '2025-06-20 15:29:01'),
(528, 'login', 1, NULL, '103.102.117.176', '2025-06-20 15:29:01'),
(529, 'logout', 1, NULL, '103.102.117.176', '2025-06-23 09:48:02'),
(530, 'login', 1, NULL, '103.102.117.176', '2025-06-23 09:48:02'),
(531, 'logout', 1, NULL, '103.102.117.176', '2025-06-23 10:35:07'),
(532, 'login', 3, NULL, '103.102.117.176', '2025-06-23 10:35:23'),
(533, 'logout', 1, NULL, '103.102.117.176', '2025-06-23 10:36:41'),
(534, 'login', 1, NULL, '103.102.117.176', '2025-06-23 10:36:41'),
(535, 'logout', 1, NULL, '103.102.117.176', '2025-06-27 10:07:19'),
(536, 'login', 1, NULL, '103.102.117.176', '2025-06-27 10:07:19'),
(537, 'logout', 3, NULL, '103.102.117.176', '2025-06-27 11:52:28'),
(538, 'login', 3, NULL, '103.102.117.176', '2025-06-27 11:52:28'),
(539, 'login', 1, NULL, '115.187.42.95', '2025-06-27 14:42:03'),
(540, 'logout', 1, NULL, '103.102.117.176', '2025-06-27 14:42:31'),
(541, 'login', 1, NULL, '103.102.117.176', '2025-06-27 14:42:31'),
(542, 'login', 4, NULL, '103.102.117.176', '2025-06-27 15:43:54'),
(543, 'logout', 1, NULL, '103.102.117.176', '2025-06-28 13:28:58'),
(544, 'login', 1, NULL, '103.102.117.176', '2025-06-28 13:28:58'),
(545, 'logout', 1, NULL, '103.102.117.176', '2025-06-28 13:34:31'),
(546, 'logout', 3, NULL, '103.102.117.176', '2025-06-28 13:34:40'),
(547, 'login', 3, NULL, '103.102.117.176', '2025-06-28 13:34:40'),
(548, 'login', 1, NULL, '45.64.226.72', '2025-06-30 11:29:25'),
(549, 'logout', 1, NULL, '45.64.226.72', '2025-06-30 12:07:10'),
(550, 'login', 1, NULL, '49.248.174.146', '2025-06-30 12:51:53'),
(551, 'login', 1, NULL, '103.217.139.82', '2025-06-30 14:40:14'),
(552, 'login', 1, NULL, '36.255.232.2', '2025-06-30 17:11:08'),
(553, 'login', 1, NULL, '115.69.248.194', '2025-07-01 17:41:19'),
(554, 'logout', 1, NULL, '115.69.248.194', '2025-07-08 12:13:21'),
(555, 'login', 1, NULL, '115.69.248.194', '2025-07-08 12:13:21'),
(556, 'logout', 1, NULL, '103.217.139.82', '2025-07-08 16:50:51'),
(557, 'login', 1, NULL, '103.217.139.82', '2025-07-08 16:50:51'),
(558, 'logout', 1, NULL, '103.217.139.82', '2025-07-09 17:11:32'),
(559, 'login', 1, NULL, '103.217.139.82', '2025-07-09 17:11:32'),
(560, 'login', 1, NULL, '116.206.202.221', '2025-07-10 16:17:00'),
(561, 'logout', 1, NULL, '103.217.139.82', '2025-07-10 17:49:32'),
(562, 'login', 1, NULL, '103.217.139.82', '2025-07-10 17:49:32'),
(563, 'logout', 1, NULL, '116.206.202.221', '2025-07-10 20:08:32'),
(564, 'login', 1, NULL, '116.206.202.221', '2025-07-10 20:08:32'),
(565, 'logout', 1, NULL, '116.206.202.221', '2025-07-10 20:14:49'),
(566, 'login', 3, NULL, '116.206.202.221', '2025-07-10 20:15:10'),
(567, 'logout', 1, NULL, '116.206.202.221', '2025-07-10 20:16:40'),
(568, 'login', 1, NULL, '116.206.202.221', '2025-07-10 20:16:40'),
(569, 'logout', 1, NULL, '116.206.202.221', '2025-07-10 21:08:36'),
(570, 'login', 1, NULL, '116.206.202.221', '2025-07-10 21:08:36'),
(571, 'logout', 1, NULL, '116.206.202.221', '2025-07-11 07:32:11'),
(572, 'login', 1, NULL, '116.206.202.221', '2025-07-11 07:32:11'),
(573, 'logout', 1, NULL, '116.206.202.221', '2025-07-11 12:08:21'),
(574, 'login', 1, NULL, '116.206.202.221', '2025-07-11 12:08:21'),
(575, 'logout', 1, NULL, '116.206.202.221', '2025-07-11 14:57:23'),
(576, 'login', 1, NULL, '116.206.202.221', '2025-07-11 14:57:23'),
(577, 'logout', 3, NULL, '116.206.202.221', '2025-07-11 14:58:51'),
(578, 'login', 3, NULL, '116.206.202.221', '2025-07-11 14:58:51'),
(579, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 11:20:06'),
(580, 'login', 1, NULL, '116.206.202.221', '2025-07-12 11:20:06'),
(581, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 16:26:09'),
(582, 'login', 1, NULL, '116.206.202.221', '2025-07-12 16:26:09'),
(583, 'logout', 3, NULL, '116.206.202.221', '2025-07-12 16:26:46'),
(584, 'login', 3, NULL, '116.206.202.221', '2025-07-12 16:26:46'),
(585, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 17:05:46'),
(586, 'login', 2, NULL, '116.206.202.221', '2025-07-12 17:05:53'),
(587, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 17:06:16'),
(588, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 17:06:24'),
(589, 'login', 1, NULL, '116.206.202.221', '2025-07-12 17:06:24'),
(590, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 17:14:26'),
(591, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 17:14:34'),
(592, 'login', 2, NULL, '116.206.202.221', '2025-07-12 17:14:34'),
(593, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:34:46'),
(594, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:36:07'),
(595, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:36:07'),
(596, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:36:19'),
(597, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:41:05'),
(598, 'login', 1, NULL, '116.206.202.221', '2025-07-12 18:41:05'),
(599, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:41:39'),
(600, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:43:28'),
(601, 'login', 1, NULL, '116.206.202.221', '2025-07-12 18:43:28'),
(602, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:43:38'),
(603, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:43:46'),
(604, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:43:46'),
(605, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:43:56'),
(606, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:44:24'),
(607, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:44:24'),
(608, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:45:26'),
(609, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:45:33'),
(610, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:45:33'),
(611, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:45:56'),
(612, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:47:36'),
(613, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:47:36'),
(614, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:49:10'),
(615, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:49:33'),
(616, 'login', 1, NULL, '116.206.202.221', '2025-07-12 18:49:33'),
(617, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:49:43'),
(618, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:52:35'),
(619, 'login', 2, NULL, '116.206.202.221', '2025-07-12 18:52:35'),
(620, 'logout', 2, NULL, '116.206.202.221', '2025-07-12 18:52:45'),
(621, 'logout', 1, NULL, '116.206.202.221', '2025-07-12 18:52:53'),
(622, 'login', 1, NULL, '116.206.202.221', '2025-07-12 18:52:53'),
(623, 'logout', 1, NULL, '116.206.202.221', '2025-07-14 11:16:18'),
(624, 'login', 1, NULL, '116.206.202.221', '2025-07-14 11:16:18'),
(625, 'login', 3, NULL, '103.102.117.144', '2025-07-14 19:57:29'),
(626, 'login', 1, NULL, '103.102.117.144', '2025-07-15 17:13:32'),
(627, 'login', 2, NULL, '103.102.117.144', '2025-07-16 11:29:35'),
(628, 'logout', 1, NULL, '103.102.117.144', '2025-07-16 11:56:31'),
(629, 'login', 1, NULL, '103.102.117.144', '2025-07-16 11:56:31'),
(630, 'logout', 2, NULL, '103.102.117.144', '2025-07-16 19:06:03'),
(631, 'login', 2, NULL, '103.102.117.144', '2025-07-16 19:06:03'),
(632, 'logout', 2, NULL, '103.102.117.144', '2025-07-16 19:06:16'),
(633, 'logout', 2, NULL, '103.102.117.144', '2025-07-16 19:06:25'),
(634, 'login', 2, NULL, '103.102.117.144', '2025-07-16 19:06:25'),
(635, 'logout', 1, NULL, '103.102.117.144', '2025-07-16 19:07:22'),
(636, 'login', 1, NULL, '103.102.117.144', '2025-07-16 19:07:22'),
(637, 'logout', 1, NULL, '103.102.117.144', '2025-07-16 20:58:05'),
(638, 'logout', 3, NULL, '103.102.117.144', '2025-07-16 20:58:33'),
(639, 'login', 3, NULL, '103.102.117.144', '2025-07-16 20:58:33'),
(640, 'logout', 2, NULL, '103.102.117.144', '2025-07-17 10:45:17'),
(641, 'login', 2, NULL, '103.102.117.144', '2025-07-17 10:45:17'),
(642, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 10:46:13'),
(643, 'login', 3, NULL, '103.102.117.144', '2025-07-17 10:46:13'),
(644, 'logout', 1, NULL, '103.102.117.144', '2025-07-17 15:43:48'),
(645, 'login', 1, NULL, '103.102.117.144', '2025-07-17 15:43:48'),
(646, 'login', 5, NULL, '103.102.117.144', '2025-07-17 16:07:53'),
(647, 'logout', 1, NULL, '103.102.117.144', '2025-07-17 17:56:13'),
(648, 'logout', 2, NULL, '103.102.117.144', '2025-07-17 17:56:27'),
(649, 'login', 2, NULL, '103.102.117.144', '2025-07-17 17:56:27'),
(650, 'logout', 5, NULL, '103.102.117.144', '2025-07-17 18:14:43'),
(651, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 18:15:14'),
(652, 'login', 3, NULL, '103.102.117.144', '2025-07-17 18:15:14'),
(653, 'logout', 2, NULL, '103.102.117.144', '2025-07-17 18:51:26'),
(654, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 18:51:37'),
(655, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 18:52:10'),
(656, 'login', 3, NULL, '103.102.117.144', '2025-07-17 18:52:10'),
(657, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 18:56:52'),
(658, 'logout', 3, NULL, '103.102.117.144', '2025-07-17 18:57:12'),
(659, 'login', 3, NULL, '103.102.117.144', '2025-07-17 18:57:12'),
(660, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 11:13:03'),
(661, 'login', 1, NULL, '103.102.117.144', '2025-07-18 11:13:03'),
(662, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 11:14:06'),
(663, 'logout', 3, NULL, '103.102.117.144', '2025-07-18 11:14:24'),
(664, 'login', 3, NULL, '103.102.117.144', '2025-07-18 11:14:24'),
(665, 'logout', 3, NULL, '103.102.117.144', '2025-07-18 11:27:08'),
(666, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 11:27:25'),
(667, 'login', 1, NULL, '103.102.117.144', '2025-07-18 11:27:25'),
(668, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 11:30:40'),
(669, 'logout', 2, NULL, '103.102.117.144', '2025-07-18 11:30:59'),
(670, 'login', 2, NULL, '103.102.117.144', '2025-07-18 11:30:59'),
(671, 'logout', 2, NULL, '103.102.117.144', '2025-07-18 11:39:05'),
(672, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 11:39:56'),
(673, 'login', 1, NULL, '103.102.117.144', '2025-07-18 11:39:56'),
(674, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 13:30:15'),
(675, 'logout', 2, NULL, '103.102.117.144', '2025-07-18 13:31:12'),
(676, 'login', 2, NULL, '103.102.117.144', '2025-07-18 13:31:12'),
(677, 'logout', 5, NULL, '103.102.117.144', '2025-07-18 13:35:47'),
(678, 'login', 5, NULL, '103.102.117.144', '2025-07-18 13:35:47'),
(679, 'logout', 2, NULL, '103.102.117.144', '2025-07-18 14:06:49'),
(680, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 14:07:10'),
(681, 'login', 1, NULL, '103.102.117.144', '2025-07-18 14:07:10'),
(682, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 14:12:08'),
(683, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 14:12:21'),
(684, 'login', 1, NULL, '103.102.117.144', '2025-07-18 14:12:21'),
(685, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 14:30:59'),
(686, 'logout', 2, NULL, '103.102.117.144', '2025-07-18 14:31:26'),
(687, 'login', 2, NULL, '103.102.117.144', '2025-07-18 14:31:26'),
(688, 'logout', 1, NULL, '115.69.248.194', '2025-07-18 14:48:05'),
(689, 'login', 1, NULL, '115.69.248.194', '2025-07-18 14:48:05'),
(690, 'logout', 1, NULL, '115.69.248.194', '2025-07-18 16:53:32'),
(691, 'login', 1, NULL, '115.69.248.194', '2025-07-18 16:53:32'),
(692, 'logout', 1, NULL, '103.102.117.144', '2025-07-18 18:13:42'),
(693, 'login', 1, NULL, '103.102.117.144', '2025-07-18 18:13:42'),
(694, 'logout', 1, NULL, '103.217.139.82', '2025-07-19 16:47:37'),
(695, 'login', 1, NULL, '103.217.139.82', '2025-07-19 16:47:37'),
(696, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 16:55:48'),
(697, 'login', 1, NULL, '103.102.117.144', '2025-07-19 16:55:48'),
(698, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 17:01:47'),
(699, 'login', 6, NULL, '103.102.117.144', '2025-07-19 17:01:54'),
(700, 'logout', 6, NULL, '103.102.117.144', '2025-07-19 17:11:16'),
(701, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 17:11:24'),
(702, 'login', 1, NULL, '103.102.117.144', '2025-07-19 17:11:24'),
(703, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 17:14:24'),
(704, 'logout', 6, NULL, '103.102.117.144', '2025-07-19 17:14:32'),
(705, 'login', 6, NULL, '103.102.117.144', '2025-07-19 17:14:32'),
(706, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 17:22:16'),
(707, 'login', 1, NULL, '103.102.117.144', '2025-07-19 17:22:16'),
(708, 'logout', 1, NULL, '103.102.117.144', '2025-07-19 17:36:38'),
(709, 'login', 7, NULL, '103.102.117.144', '2025-07-19 17:36:50'),
(710, 'logout', 1, NULL, '103.102.117.144', '2025-07-21 11:13:18'),
(711, 'login', 1, NULL, '103.102.117.144', '2025-07-21 11:13:18'),
(712, 'logout', 1, NULL, '115.69.248.194', '2025-07-21 12:59:41'),
(713, 'login', 1, NULL, '115.69.248.194', '2025-07-21 12:59:41'),
(714, 'logout', 2, NULL, '103.102.117.144', '2025-07-21 18:18:33'),
(715, 'login', 2, NULL, '103.102.117.144', '2025-07-21 18:18:33'),
(716, 'logout', 2, NULL, '103.102.117.144', '2025-07-21 18:22:02'),
(717, 'logout', 2, NULL, '103.102.117.144', '2025-07-21 18:24:26'),
(718, 'login', 2, NULL, '103.102.117.144', '2025-07-21 18:24:26'),
(719, 'logout', 2, NULL, '103.102.117.144', '2025-07-21 19:06:35'),
(720, 'logout', 1, NULL, '103.102.117.144', '2025-07-21 19:06:47'),
(721, 'login', 1, NULL, '103.102.117.144', '2025-07-21 19:06:47'),
(722, 'logout', 1, NULL, '103.217.139.82', '2025-07-22 15:31:41'),
(723, 'login', 1, NULL, '103.217.139.82', '2025-07-22 15:31:41'),
(724, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:06:20'),
(725, 'login', 1, NULL, '103.102.117.144', '2025-07-22 17:06:20'),
(726, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:07:52'),
(727, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:08:20'),
(728, 'login', 1, NULL, '103.102.117.144', '2025-07-22 17:08:20'),
(729, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:08:51'),
(730, 'login', 8, NULL, '103.102.117.144', '2025-07-22 17:09:07'),
(731, 'logout', 8, NULL, '103.102.117.144', '2025-07-22 17:10:55'),
(732, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:11:21'),
(733, 'login', 3, NULL, '103.102.117.144', '2025-07-22 17:11:21'),
(734, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:30:23'),
(735, 'logout', 2, NULL, '103.102.117.144', '2025-07-22 17:30:39'),
(736, 'login', 2, NULL, '103.102.117.144', '2025-07-22 17:30:39'),
(737, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:32:00'),
(738, 'login', 1, NULL, '103.102.117.144', '2025-07-22 17:32:00'),
(739, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:33:11'),
(740, 'login', 9, NULL, '103.102.117.144', '2025-07-22 17:33:25'),
(741, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 17:34:12'),
(742, 'login', 9, NULL, '103.102.117.144', '2025-07-22 17:34:12'),
(743, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:35:01'),
(744, 'login', 3, NULL, '103.102.117.144', '2025-07-22 17:35:01'),
(745, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:38:40'),
(746, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:38:52'),
(747, 'login', 1, NULL, '103.102.117.144', '2025-07-22 17:38:52'),
(748, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:46:45'),
(749, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:47:09'),
(750, 'login', 3, NULL, '103.102.117.144', '2025-07-22 17:47:09'),
(751, 'logout', 3, NULL, '103.102.117.144', '2025-07-22 17:56:39'),
(752, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:56:57'),
(753, 'login', 1, NULL, '103.102.117.144', '2025-07-22 17:56:57'),
(754, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 17:57:29'),
(755, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 17:57:48'),
(756, 'login', 9, NULL, '103.102.117.144', '2025-07-22 17:57:48'),
(757, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:01:15'),
(758, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:01:29'),
(759, 'login', 1, NULL, '103.102.117.144', '2025-07-22 18:01:29'),
(760, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:02:13'),
(761, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:02:37'),
(762, 'login', 9, NULL, '103.102.117.144', '2025-07-22 18:02:37'),
(763, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:05:53'),
(764, 'login', 1, NULL, '103.102.117.144', '2025-07-22 18:05:53'),
(765, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:07:10'),
(766, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:07:26'),
(767, 'login', 9, NULL, '103.102.117.144', '2025-07-22 18:07:26'),
(768, 'logout', 2, NULL, '103.102.117.144', '2025-07-22 18:12:46'),
(769, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:13:00'),
(770, 'login', 1, NULL, '103.102.117.144', '2025-07-22 18:13:00'),
(771, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:15:28'),
(772, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:15:40'),
(773, 'login', 1, NULL, '103.102.117.144', '2025-07-22 18:15:40'),
(774, 'logout', 1, NULL, '103.102.117.144', '2025-07-22 18:21:57'),
(775, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:22:09'),
(776, 'login', 9, NULL, '103.102.117.144', '2025-07-22 18:22:09'),
(777, 'logout', 9, NULL, '103.102.117.144', '2025-07-22 18:34:40'),
(778, 'login', 10, NULL, '103.102.117.144', '2025-07-22 18:34:49'),
(779, 'logout', 1, NULL, '103.102.117.144', '2025-07-23 11:29:12'),
(780, 'login', 1, NULL, '103.102.117.144', '2025-07-23 11:29:12'),
(781, 'login', 12, NULL, '103.102.117.144', '2025-07-23 11:34:28'),
(782, 'logout', 12, NULL, '103.102.117.144', '2025-07-23 12:19:42'),
(783, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 12:20:01'),
(784, 'login', 2, NULL, '103.102.117.144', '2025-07-23 12:20:01'),
(785, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 12:40:12'),
(786, 'login', 13, NULL, '103.102.117.144', '2025-07-23 13:51:37'),
(787, 'logout', 13, NULL, '103.102.117.144', '2025-07-23 13:53:55'),
(788, 'logout', 13, NULL, '103.102.117.144', '2025-07-23 13:54:21'),
(789, 'login', 13, NULL, '103.102.117.144', '2025-07-23 13:54:21'),
(790, 'logout', 13, NULL, '103.102.117.144', '2025-07-23 13:54:33'),
(791, 'logout', 13, NULL, '103.102.117.144', '2025-07-23 14:10:38'),
(792, 'login', 13, NULL, '103.102.117.144', '2025-07-23 14:10:38');
INSERT INTO `login_history` (`id`, `type`, `user_id`, `school_id`, `ip`, `created_at`) VALUES
(793, 'logout', 13, NULL, '103.102.117.144', '2025-07-23 14:11:20'),
(794, 'login', 14, NULL, '103.102.117.144', '2025-07-23 14:23:03'),
(795, 'logout', 14, NULL, '103.102.117.144', '2025-07-23 14:33:24'),
(796, 'logout', 14, NULL, '103.102.117.144', '2025-07-23 14:33:32'),
(797, 'login', 14, NULL, '103.102.117.144', '2025-07-23 14:33:32'),
(798, 'logout', 14, NULL, '103.102.117.144', '2025-07-23 14:37:02'),
(799, 'logout', 14, NULL, '103.102.117.144', '2025-07-23 14:46:00'),
(800, 'login', 14, NULL, '103.102.117.144', '2025-07-23 14:46:00'),
(801, 'logout', 14, NULL, '103.102.117.144', '2025-07-23 14:46:39'),
(802, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 14:47:40'),
(803, 'login', 2, NULL, '103.102.117.144', '2025-07-23 14:47:40'),
(804, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 14:53:44'),
(805, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 14:54:09'),
(806, 'login', 2, NULL, '103.102.117.144', '2025-07-23 14:54:09'),
(807, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 14:59:53'),
(808, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 15:00:18'),
(809, 'login', 2, NULL, '103.102.117.144', '2025-07-23 15:00:18'),
(810, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 15:12:40'),
(811, 'logout', 3, NULL, '103.102.117.144', '2025-07-23 15:12:56'),
(812, 'login', 3, NULL, '103.102.117.144', '2025-07-23 15:12:56'),
(813, 'logout', 1, NULL, '103.102.117.144', '2025-07-23 15:40:53'),
(814, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 15:41:17'),
(815, 'login', 2, NULL, '103.102.117.144', '2025-07-23 15:41:17'),
(816, 'logout', 2, NULL, '103.102.117.144', '2025-07-23 17:12:15'),
(817, 'logout', 1, NULL, '103.102.117.144', '2025-07-23 17:12:28'),
(818, 'login', 1, NULL, '103.102.117.144', '2025-07-23 17:12:28'),
(819, 'logout', 3, NULL, '103.102.117.144', '2025-07-23 17:13:22'),
(820, 'logout', 12, NULL, '103.102.117.144', '2025-07-23 17:13:33'),
(821, 'login', 12, NULL, '103.102.117.144', '2025-07-23 17:13:33'),
(822, 'logout', 1, NULL, '103.217.139.82', '2025-07-24 11:25:44'),
(823, 'login', 1, NULL, '103.217.139.82', '2025-07-24 11:25:44'),
(824, 'logout', 1, NULL, '103.102.117.144', '2025-07-24 12:01:50'),
(825, 'login', 1, NULL, '103.102.117.144', '2025-07-24 12:01:50'),
(826, 'logout', 12, NULL, '103.102.117.144', '2025-07-24 12:02:11'),
(827, 'login', 12, NULL, '103.102.117.144', '2025-07-24 12:02:11'),
(828, 'logout', 12, NULL, '103.102.117.144', '2025-07-24 14:20:07'),
(829, 'logout', 13, NULL, '103.102.117.144', '2025-07-24 14:20:31'),
(830, 'login', 13, NULL, '103.102.117.144', '2025-07-24 14:20:31'),
(831, 'logout', 1, NULL, '103.102.117.144', '2025-07-24 14:31:21'),
(832, 'login', 1, NULL, '103.102.117.144', '2025-07-24 14:31:21'),
(833, 'logout', 13, NULL, '103.102.117.144', '2025-07-24 15:03:56'),
(834, 'login', 15, NULL, '103.102.117.144', '2025-07-24 15:10:14'),
(835, 'logout', 15, NULL, '103.102.117.144', '2025-07-24 15:57:00'),
(836, 'logout', 15, NULL, '103.102.117.144', '2025-07-24 15:57:09'),
(837, 'login', 15, NULL, '103.102.117.144', '2025-07-24 15:57:09'),
(838, 'logout', 1, NULL, '103.102.117.144', '2025-07-24 17:10:51'),
(839, 'login', 1, NULL, '103.102.117.144', '2025-07-24 17:10:51'),
(840, 'logout', 1, NULL, '103.102.117.144', '2025-07-25 16:00:03'),
(841, 'login', 1, NULL, '103.102.117.144', '2025-07-25 16:00:03'),
(842, 'logout', 15, NULL, '103.102.117.144', '2025-07-25 16:03:16'),
(843, 'login', 15, NULL, '103.102.117.144', '2025-07-25 16:03:16'),
(844, 'logout', 15, NULL, '103.102.117.144', '2025-07-25 16:29:43'),
(845, 'logout', 12, NULL, '103.102.117.144', '2025-07-25 16:30:05'),
(846, 'login', 12, NULL, '103.102.117.144', '2025-07-25 16:30:05'),
(847, 'logout', 12, NULL, '103.102.117.144', '2025-07-25 17:22:34'),
(848, 'logout', 13, NULL, '103.102.117.144', '2025-07-25 17:22:52'),
(849, 'login', 13, NULL, '103.102.117.144', '2025-07-25 17:22:52'),
(850, 'logout', 13, NULL, '103.102.117.144', '2025-07-25 19:51:49'),
(851, 'logout', 15, NULL, '103.102.117.144', '2025-07-25 19:52:03'),
(852, 'login', 15, NULL, '103.102.117.144', '2025-07-25 19:52:03'),
(853, 'logout', 15, NULL, '103.102.117.144', '2025-07-25 20:03:30'),
(854, 'logout', 13, NULL, '103.102.117.144', '2025-07-25 20:04:17'),
(855, 'login', 13, NULL, '103.102.117.144', '2025-07-25 20:04:17'),
(856, 'logout', 1, NULL, '103.102.117.144', '2025-07-26 12:05:24'),
(857, 'login', 1, NULL, '103.102.117.144', '2025-07-26 12:05:24'),
(858, 'logout', 1, NULL, '103.102.117.144', '2025-07-26 14:32:12'),
(859, 'login', 1, NULL, '103.102.117.144', '2025-07-26 14:32:12'),
(860, 'logout', 14, NULL, '103.102.117.144', '2025-07-26 14:33:03'),
(861, 'login', 14, NULL, '103.102.117.144', '2025-07-26 14:33:03'),
(862, 'logout', 1, NULL, '103.102.117.144', '2025-07-26 17:37:12'),
(863, 'login', 1, NULL, '103.102.117.144', '2025-07-26 17:37:12'),
(864, 'logout', 14, NULL, '103.102.117.144', '2025-07-26 17:37:35'),
(865, 'login', 14, NULL, '103.102.117.144', '2025-07-26 17:37:35'),
(866, 'login', 1, NULL, '116.206.202.233', '2025-07-28 11:31:33'),
(867, 'login', 12, NULL, '116.206.202.233', '2025-07-28 11:32:43'),
(868, 'logout', 12, NULL, '116.206.202.233', '2025-07-28 12:44:55'),
(869, 'login', 15, NULL, '116.206.202.233', '2025-07-28 12:45:09'),
(870, 'login', 2, NULL, '116.206.202.233', '2025-07-28 15:36:25'),
(871, 'logout', 1, NULL, '116.206.202.233', '2025-07-28 15:48:18'),
(872, 'login', 1, NULL, '116.206.202.233', '2025-07-28 15:48:18'),
(873, 'logout', 1, NULL, '116.206.202.233', '2025-07-28 15:49:00'),
(874, 'login', 5, NULL, '116.206.202.233', '2025-07-28 15:49:14'),
(875, 'logout', 1, NULL, '116.206.202.233', '2025-07-28 18:45:04'),
(876, 'login', 1, NULL, '116.206.202.233', '2025-07-28 18:45:04'),
(877, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 14:14:20'),
(878, 'login', 1, NULL, '116.206.202.233', '2025-07-30 14:14:20'),
(879, 'login', 16, NULL, '116.206.202.233', '2025-07-30 14:59:00'),
(880, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 14:59:22'),
(881, 'logout', 2, NULL, '116.206.202.233', '2025-07-30 14:59:37'),
(882, 'login', 2, NULL, '116.206.202.233', '2025-07-30 14:59:37'),
(883, 'logout', 2, NULL, '116.206.202.233', '2025-07-30 15:14:23'),
(884, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 15:15:12'),
(885, 'login', 1, NULL, '116.206.202.233', '2025-07-30 15:15:12'),
(886, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 15:19:09'),
(887, 'logout', 12, NULL, '116.206.202.233', '2025-07-30 15:19:31'),
(888, 'login', 12, NULL, '116.206.202.233', '2025-07-30 15:19:31'),
(889, 'logout', 16, NULL, '116.206.202.233', '2025-07-30 15:25:03'),
(890, 'logout', 15, NULL, '116.206.202.233', '2025-07-30 15:25:29'),
(891, 'login', 15, NULL, '116.206.202.233', '2025-07-30 15:25:29'),
(892, 'logout', 12, NULL, '116.206.202.233', '2025-07-30 15:28:35'),
(893, 'logout', 15, NULL, '116.206.202.233', '2025-07-30 15:29:18'),
(894, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 15:29:32'),
(895, 'login', 1, NULL, '116.206.202.233', '2025-07-30 15:29:32'),
(896, 'logout', 1, NULL, '116.206.202.233', '2025-07-30 15:30:10'),
(897, 'login', 13, NULL, '116.206.202.233', '2025-07-30 15:30:25'),
(898, 'logout', 13, NULL, '116.206.202.233', '2025-07-30 15:33:43'),
(899, 'login', 14, NULL, '116.206.202.233', '2025-07-30 15:34:14'),
(900, 'logout', 2, NULL, '116.206.202.233', '2025-07-30 16:26:51'),
(901, 'login', 2, NULL, '116.206.202.233', '2025-07-30 16:26:51'),
(902, 'logout', 14, NULL, '116.206.202.233', '2025-07-30 16:28:30'),
(903, 'logout', 16, NULL, '116.206.202.233', '2025-07-30 17:17:28'),
(904, 'login', 16, NULL, '116.206.202.233', '2025-07-30 17:17:28'),
(905, 'logout', 16, NULL, '116.206.202.233', '2025-07-30 17:20:00'),
(906, 'logout', 12, NULL, '116.206.202.233', '2025-07-30 17:20:13'),
(907, 'login', 12, NULL, '116.206.202.233', '2025-07-30 17:20:13'),
(908, 'logout', 2, NULL, '116.206.202.233', '2025-07-30 17:22:14'),
(909, 'logout', 15, NULL, '116.206.202.233', '2025-07-30 17:22:32'),
(910, 'login', 15, NULL, '116.206.202.233', '2025-07-30 17:22:32'),
(911, 'logout', 12, NULL, '116.206.202.233', '2025-07-30 17:25:27'),
(912, 'logout', 13, NULL, '116.206.202.233', '2025-07-30 17:25:44'),
(913, 'login', 13, NULL, '116.206.202.233', '2025-07-30 17:25:44'),
(914, 'logout', 15, NULL, '116.206.202.233', '2025-07-30 17:27:45'),
(915, 'logout', 14, NULL, '116.206.202.233', '2025-07-30 17:28:27'),
(916, 'login', 14, NULL, '116.206.202.233', '2025-07-30 17:28:27'),
(917, 'login', 2, NULL, '116.206.202.226', '2025-07-31 11:29:34'),
(918, 'logout', 2, NULL, '116.206.202.226', '2025-07-31 16:00:45'),
(919, 'login', 2, NULL, '116.206.202.226', '2025-07-31 16:00:45'),
(920, 'logout', 2, NULL, '116.206.202.226', '2025-07-31 17:02:17'),
(921, 'logout', 2, NULL, '116.206.202.226', '2025-07-31 19:39:14'),
(922, 'login', 2, NULL, '116.206.202.226', '2025-07-31 19:39:14'),
(923, 'login', 1, NULL, '127.0.0.1', '2025-08-01 16:39:43'),
(924, 'logout', 1, NULL, '127.0.0.1', '2025-08-01 19:52:16'),
(925, 'login', 1, NULL, '127.0.0.1', '2025-08-01 19:52:16'),
(926, 'logout', 1, NULL, '127.0.0.1', '2025-08-02 10:42:55'),
(927, 'login', 1, NULL, '127.0.0.1', '2025-08-02 10:42:55'),
(928, 'logout', 1, NULL, '127.0.0.1', '2025-08-02 14:46:40'),
(929, 'login', 1, NULL, '127.0.0.1', '2025-08-02 14:46:40'),
(930, 'login', 17, NULL, '127.0.0.1', '2025-08-02 15:52:30'),
(931, 'logout', 1, NULL, '127.0.0.1', '2025-08-02 15:57:41'),
(932, 'logout', 1, NULL, '127.0.0.1', '2025-08-02 15:57:49'),
(933, 'login', 1, NULL, '127.0.0.1', '2025-08-02 15:57:49'),
(934, 'logout', 17, NULL, '127.0.0.1', '2025-08-02 19:20:24'),
(935, 'login', 17, NULL, '127.0.0.1', '2025-08-02 19:20:24'),
(936, 'logout', 1, NULL, '127.0.0.1', '2025-08-03 10:07:54'),
(937, 'login', 1, NULL, '127.0.0.1', '2025-08-03 10:07:54'),
(938, 'logout', 1, NULL, '127.0.0.1', '2025-08-04 10:58:03'),
(939, 'login', 1, NULL, '127.0.0.1', '2025-08-04 10:58:03'),
(940, 'logout', 1, NULL, '127.0.0.1', '2025-08-05 17:01:03'),
(941, 'login', 1, NULL, '127.0.0.1', '2025-08-05 17:01:03'),
(942, 'logout', 1, NULL, '127.0.0.1', '2025-08-05 18:57:47'),
(943, 'login', 1, NULL, '127.0.0.1', '2025-08-05 18:57:47'),
(944, 'logout', 1, NULL, '127.0.0.1', '2025-08-05 19:05:09'),
(945, 'logout', 17, NULL, '127.0.0.1', '2025-08-05 19:06:28'),
(946, 'login', 17, NULL, '127.0.0.1', '2025-08-05 19:06:28'),
(947, 'logout', 1, NULL, '127.0.0.1', '2025-08-06 11:14:11'),
(948, 'login', 1, NULL, '127.0.0.1', '2025-08-06 11:14:11'),
(949, 'logout', 1, NULL, '121.241.210.182', '2025-08-07 18:13:06'),
(950, 'login', 1, NULL, '121.241.210.182', '2025-08-07 18:13:06'),
(951, 'login', 1, NULL, '116.206.202.148', '2025-08-07 18:29:04'),
(952, 'logout', 1, NULL, '116.206.202.148', '2025-08-07 18:29:16'),
(953, 'logout', 1, NULL, '116.206.202.148', '2025-08-08 11:27:35'),
(954, 'login', 1, NULL, '116.206.202.148', '2025-08-08 11:27:35'),
(955, 'logout', 1, NULL, '116.206.202.148', '2025-08-08 11:35:13'),
(956, 'login', 19, NULL, '116.206.202.148', '2025-08-08 11:35:22'),
(957, 'logout', 19, NULL, '116.206.202.148', '2025-08-08 13:35:25'),
(958, 'login', 1, NULL, '115.187.42.55', '2025-08-08 20:52:29'),
(959, 'login', 1, NULL, '115.187.42.52', '2025-08-19 19:47:43'),
(960, 'login', 1, NULL, '116.206.202.225', '2025-08-19 19:57:42'),
(961, 'logout', 1, NULL, '116.206.202.225', '2025-08-20 18:45:30'),
(962, 'login', 1, NULL, '116.206.202.225', '2025-08-20 18:45:30'),
(963, 'login', 1, NULL, '115.187.42.68', '2025-08-20 18:53:07'),
(964, 'login', 1, NULL, '103.175.55.155', '2025-08-20 19:33:41'),
(965, 'logout', 1, NULL, '115.187.42.68', '2025-08-20 19:38:32'),
(966, 'login', 1, NULL, '115.187.42.68', '2025-08-20 19:38:32'),
(967, 'logout', 1, NULL, '103.175.55.155', '2025-08-21 00:35:20'),
(968, 'login', 1, NULL, '103.175.55.155', '2025-08-21 00:35:20'),
(969, 'logout', 1, NULL, '103.175.55.155', '2025-08-21 00:38:29'),
(970, 'logout', 1, NULL, '103.175.55.155', '2025-08-21 00:41:57'),
(971, 'login', 1, NULL, '103.175.55.155', '2025-08-21 00:41:57'),
(972, 'logout', 1, NULL, '103.175.55.155', '2025-08-21 00:57:10'),
(973, 'login', 1, NULL, '59.184.120.119', '2025-08-21 12:56:04'),
(974, 'login', 1, NULL, '115.187.42.54', '2025-08-22 12:56:00'),
(975, 'login', 1, NULL, '103.102.117.131', '2025-08-22 17:22:35'),
(976, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 13:22:18'),
(977, 'login', 1, NULL, '103.102.117.131', '2025-08-25 13:22:18'),
(978, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 13:30:24'),
(979, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 13:37:05'),
(980, 'login', 1, NULL, '103.102.117.131', '2025-08-25 13:37:05'),
(981, 'login', 1, NULL, '115.187.42.41', '2025-08-25 13:55:18'),
(982, 'login', 1, NULL, '103.175.55.220', '2025-08-25 13:55:34'),
(983, 'logout', 1, NULL, '103.175.55.220', '2025-08-25 16:13:49'),
(984, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 17:47:10'),
(985, 'login', 1, NULL, '103.102.117.131', '2025-08-25 17:47:10'),
(986, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 18:00:04'),
(987, 'login', 17, NULL, '103.102.117.131', '2025-08-25 18:00:43'),
(988, 'logout', 17, NULL, '103.102.117.131', '2025-08-25 18:00:49'),
(989, 'login', 20, NULL, '103.102.117.131', '2025-08-25 18:01:03'),
(990, 'logout', 20, NULL, '103.102.117.131', '2025-08-25 18:01:25'),
(991, 'login', 21, NULL, '103.102.117.131', '2025-08-25 18:01:42'),
(992, 'logout', 21, NULL, '103.102.117.131', '2025-08-25 18:03:27'),
(993, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 18:08:19'),
(994, 'login', 1, NULL, '103.102.117.131', '2025-08-25 18:08:19'),
(995, 'logout', 21, NULL, '103.102.117.131', '2025-08-25 18:08:39'),
(996, 'login', 21, NULL, '103.102.117.131', '2025-08-25 18:08:39'),
(997, 'logout', 1, NULL, '103.102.117.131', '2025-08-25 18:09:56'),
(998, 'logout', 21, NULL, '103.102.117.131', '2025-08-25 18:11:58'),
(999, 'login', 19, NULL, '103.102.117.131', '2025-08-25 18:13:08'),
(1000, 'login', 1, NULL, '103.175.55.175', '2025-09-11 11:51:30'),
(1001, 'logout', 1, NULL, '103.175.55.175', '2025-09-11 13:03:30'),
(1002, 'login', 19, NULL, '103.175.55.175', '2025-09-11 13:03:42'),
(1003, 'logout', 19, NULL, '103.175.55.175', '2025-09-11 13:07:22'),
(1004, 'login', 1, NULL, '27.59.102.243', '2025-09-12 08:28:10'),
(1005, 'login', 1, NULL, '117.248.233.219', '2025-09-26 11:28:45'),
(1006, 'login', 1, NULL, '103.157.183.50', '2025-10-24 15:37:19'),
(1007, 'logout', 1, NULL, '103.157.183.50', '2025-10-24 15:42:45'),
(1008, 'login', 19, NULL, '103.157.183.50', '2025-10-24 15:43:25'),
(1009, 'logout', 19, NULL, '103.157.183.50', '2025-10-24 15:53:55'),
(1010, 'login', 17, NULL, '103.157.183.50', '2025-10-24 15:54:36'),
(1011, 'logout', 17, NULL, '103.157.183.50', '2025-10-24 15:56:09'),
(1012, 'login', 20, NULL, '103.157.183.50', '2025-10-24 15:56:31'),
(1013, 'logout', 20, NULL, '103.157.183.50', '2025-10-24 16:07:44'),
(1014, 'logout', 1, NULL, '103.157.183.50', '2025-10-24 16:11:15'),
(1015, 'login', 1, NULL, '103.157.183.50', '2025-10-24 16:11:15'),
(1016, 'logout', 1, NULL, '103.157.183.50', '2025-10-24 16:12:19'),
(1017, 'login', 21, NULL, '103.157.183.50', '2025-10-24 16:14:27'),
(1018, 'logout', 21, NULL, '103.157.183.50', '2025-10-24 16:18:01'),
(1019, 'logout', 17, NULL, '103.157.183.50', '2025-10-24 16:20:00'),
(1020, 'login', 17, NULL, '103.157.183.50', '2025-10-24 16:20:00'),
(1021, 'login', 1, NULL, '117.222.85.251', '2025-10-24 16:20:28'),
(1022, 'logout', 17, NULL, '103.157.183.50', '2025-10-24 16:24:32'),
(1023, 'login', 17, NULL, '117.222.85.251', '2025-10-24 16:31:31'),
(1024, 'logout', 17, NULL, '117.222.85.251', '2025-10-24 16:40:05'),
(1025, 'logout', 1, NULL, '117.222.85.251', '2025-10-24 16:40:12'),
(1026, 'login', 1, NULL, '117.222.85.251', '2025-10-24 16:40:12'),
(1027, 'logout', 1, NULL, '117.222.85.251', '2025-10-24 16:40:27'),
(1028, 'logout', 1, NULL, '117.222.85.251', '2025-10-24 16:45:35'),
(1029, 'login', 1, NULL, '117.222.85.251', '2025-10-24 16:45:35'),
(1030, 'logout', 1, NULL, '117.222.85.251', '2025-10-24 16:45:43'),
(1031, 'login', 17, NULL, '164.100.214.50', '2025-10-24 17:04:05'),
(1032, 'login', 20, NULL, '103.102.117.144', '2025-10-24 17:39:11'),
(1033, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 17:40:09'),
(1034, 'login', 19, NULL, '103.102.117.144', '2025-10-24 17:40:26'),
(1035, 'logout', 19, NULL, '103.102.117.144', '2025-10-24 17:51:00'),
(1036, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 17:51:19'),
(1037, 'login', 20, NULL, '103.102.117.144', '2025-10-24 17:51:19'),
(1038, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 17:52:19'),
(1039, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 17:52:48'),
(1040, 'login', 20, NULL, '103.102.117.144', '2025-10-24 17:52:48'),
(1041, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 17:57:42'),
(1042, 'login', 1, NULL, '103.102.117.144', '2025-10-24 17:57:42'),
(1043, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 19:13:22'),
(1044, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 19:13:38'),
(1045, 'login', 1, NULL, '103.102.117.144', '2025-10-24 19:13:38'),
(1046, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 19:19:10'),
(1047, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 19:19:49'),
(1048, 'login', 20, NULL, '103.102.117.144', '2025-10-24 19:19:49'),
(1049, 'login', 17, NULL, '103.102.117.144', '2025-10-24 19:26:12'),
(1050, 'logout', 20, NULL, '103.102.117.144', '2025-10-24 19:26:49'),
(1051, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 19:27:19'),
(1052, 'login', 1, NULL, '103.102.117.144', '2025-10-24 19:27:19'),
(1053, 'logout', 1, NULL, '103.102.117.144', '2025-10-24 19:47:51'),
(1054, 'logout', 19, NULL, '103.102.117.144', '2025-10-24 19:47:56'),
(1055, 'login', 19, NULL, '103.102.117.144', '2025-10-24 19:47:56'),
(1056, 'logout', 19, NULL, '103.102.117.144', '2025-10-24 19:48:19'),
(1057, 'logout', 1, NULL, '103.157.183.50', '2025-10-27 16:43:48'),
(1058, 'login', 1, NULL, '103.157.183.50', '2025-10-27 16:43:48'),
(1059, 'logout', 1, NULL, '103.157.183.50', '2025-10-27 16:44:14'),
(1060, 'login', 1, NULL, '115.187.42.189', '2025-10-28 18:07:59'),
(1061, 'logout', 1, NULL, '115.187.42.189', '2025-10-28 18:08:28'),
(1062, 'login', 21, NULL, '115.187.42.189', '2025-10-28 18:12:32'),
(1063, 'login', 17, NULL, '106.221.211.55', '2025-10-30 10:42:21'),
(1064, 'login', 17, NULL, '117.248.158.52', '2025-10-30 15:20:23'),
(1065, 'login', 21, NULL, '223.228.134.201', '2025-10-30 15:30:34'),
(1066, 'login', 20, NULL, '106.192.127.181', '2025-10-30 15:32:49'),
(1067, 'logout', 17, NULL, '117.248.158.52', '2025-10-30 16:03:08'),
(1068, 'logout', 17, NULL, '106.221.211.55', '2025-10-30 16:11:11'),
(1069, 'login', 17, NULL, '106.221.211.55', '2025-10-30 16:11:11'),
(1070, 'logout', 19, NULL, '103.157.183.50', '2025-10-31 11:21:46'),
(1071, 'login', 19, NULL, '103.157.183.50', '2025-10-31 11:21:46'),
(1072, 'logout', 19, NULL, '103.157.183.50', '2025-10-31 11:23:02'),
(1073, 'logout', 17, NULL, '103.157.183.50', '2025-10-31 11:23:32'),
(1074, 'login', 17, NULL, '103.157.183.50', '2025-10-31 11:23:32'),
(1075, 'logout', 17, NULL, '103.157.183.50', '2025-10-31 11:24:37'),
(1076, 'logout', 20, NULL, '103.157.183.50', '2025-10-31 11:24:57'),
(1077, 'login', 20, NULL, '103.157.183.50', '2025-10-31 11:24:57'),
(1078, 'logout', 20, NULL, '103.157.183.50', '2025-10-31 11:25:05'),
(1079, 'logout', 21, NULL, '103.157.183.50', '2025-10-31 11:25:25'),
(1080, 'login', 21, NULL, '103.157.183.50', '2025-10-31 11:25:25'),
(1081, 'logout', 21, NULL, '103.157.183.50', '2025-10-31 11:26:45'),
(1082, 'login', 19, NULL, '103.40.74.174', '2025-10-31 12:07:30'),
(1083, 'logout', 19, NULL, '103.40.74.174', '2025-10-31 12:12:41'),
(1084, 'login', 19, NULL, '103.40.74.174', '2025-10-31 12:12:41'),
(1085, 'login', 1, NULL, '103.102.117.154', '2025-11-03 12:41:53'),
(1086, 'logout', 1, NULL, '115.187.42.189', '2025-11-03 13:33:05'),
(1087, 'login', 1, NULL, '115.187.42.189', '2025-11-03 13:33:05'),
(1088, 'login', 1, NULL, '117.222.93.67', '2025-11-06 10:18:34'),
(1089, 'login', 19, NULL, '103.40.74.187', '2025-11-07 11:44:56'),
(1090, 'logout', 1, NULL, '115.187.42.189', '2025-11-08 17:09:53'),
(1091, 'login', 1, NULL, '115.187.42.189', '2025-11-08 17:09:53'),
(1092, 'logout', 17, NULL, '164.100.214.50', '2025-11-10 15:48:31'),
(1093, 'login', 17, NULL, '164.100.214.50', '2025-11-10 15:48:31'),
(1094, 'login', 20, NULL, '223.228.136.161', '2025-11-17 12:57:20'),
(1095, 'login', 20, NULL, '164.100.212.57', '2025-11-17 13:00:46'),
(1096, 'login', 17, NULL, '164.100.212.56', '2025-11-24 16:32:09'),
(1097, 'login', 19, NULL, '103.40.73.207', '2025-11-28 16:51:27'),
(1098, 'login', 1, NULL, '117.248.238.7', '2025-12-09 12:14:43'),
(1099, 'login', 17, NULL, '164.100.212.57', '2025-12-09 16:08:49'),
(1100, 'login', 19, NULL, '103.40.73.250', '2025-12-12 12:19:29'),
(1101, 'login', 20, NULL, '117.99.246.141', '2026-01-07 16:42:29'),
(1102, 'login', 20, NULL, '116.206.202.216', '2026-01-07 17:32:29'),
(1103, 'logout', 1, NULL, '127.0.0.1', '2026-01-07 18:59:57'),
(1104, 'login', 1, NULL, '127.0.0.1', '2026-01-07 18:59:57'),
(1105, 'logout', 1, NULL, '127.0.0.1', '2026-01-07 19:34:07'),
(1106, 'login', 1, NULL, '127.0.0.1', '2026-01-07 19:34:07'),
(1107, 'logout', 1, NULL, '127.0.0.1', '2026-01-08 10:23:49'),
(1108, 'login', 1, NULL, '127.0.0.1', '2026-01-08 10:23:49'),
(1109, 'logout', 1, NULL, '127.0.0.1', '2026-01-08 15:45:29'),
(1110, 'login', 1, NULL, '127.0.0.1', '2026-01-08 15:45:29'),
(1111, 'logout', 1, NULL, '127.0.0.1', '2026-01-09 11:04:29'),
(1112, 'login', 1, NULL, '127.0.0.1', '2026-01-09 11:04:29'),
(1113, 'logout', 1, NULL, '127.0.0.1', '2026-01-09 15:35:26'),
(1114, 'login', 1, NULL, '127.0.0.1', '2026-01-09 15:35:26'),
(1115, 'login', 23, NULL, '127.0.0.1', '2026-01-09 20:51:21'),
(1116, 'logout', 1, NULL, '127.0.0.1', '2026-01-10 11:10:31'),
(1117, 'login', 1, NULL, '127.0.0.1', '2026-01-10 11:10:31'),
(1118, 'logout', 1, NULL, '127.0.0.1', '2026-01-10 14:59:48'),
(1119, 'login', 1, NULL, '127.0.0.1', '2026-01-10 14:59:48'),
(1120, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 15:21:49'),
(1121, 'login', 23, NULL, '127.0.0.1', '2026-01-10 15:21:49'),
(1122, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 18:10:48'),
(1123, 'login', 23, NULL, '127.0.0.1', '2026-01-10 18:10:48'),
(1124, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 19:08:37'),
(1125, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 19:09:36'),
(1126, 'login', 23, NULL, '127.0.0.1', '2026-01-10 19:09:36'),
(1127, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 19:09:44'),
(1128, 'logout', 23, NULL, '127.0.0.1', '2026-01-10 19:09:50'),
(1129, 'login', 23, NULL, '127.0.0.1', '2026-01-10 19:09:50'),
(1130, 'login', 1, NULL, '116.206.202.216', '2026-01-12 11:23:18'),
(1131, 'logout', 1, NULL, '116.206.202.216', '2026-01-12 11:24:56'),
(1132, 'login', 1, NULL, '116.206.202.216', '2026-01-12 11:24:56'),
(1133, 'login', 1, NULL, '115.187.42.63', '2026-01-12 14:46:29'),
(1134, 'login', 1, NULL, '5.195.118.208', '2026-01-12 14:54:46'),
(1135, 'logout', 1, NULL, '5.195.118.208', '2026-01-12 15:00:01'),
(1136, 'login', 1, NULL, '5.195.118.208', '2026-01-12 15:00:01'),
(1137, 'login', 1, NULL, '116.206.202.224', '2026-01-12 16:46:16'),
(1138, 'logout', 1, NULL, '116.206.202.224', '2026-01-12 19:33:16'),
(1139, 'login', 1, NULL, '116.206.202.224', '2026-01-12 19:33:16'),
(1140, 'logout', 1, NULL, '116.206.202.224', '2026-01-15 09:51:33'),
(1141, 'login', 1, NULL, '116.206.202.224', '2026-01-15 09:51:33'),
(1142, 'logout', 1, NULL, '116.206.202.224', '2026-01-15 12:47:17'),
(1143, 'login', 1, NULL, '116.206.202.224', '2026-01-15 12:47:17'),
(1144, 'logout', 1, NULL, '116.206.202.224', '2026-01-15 15:31:58'),
(1145, 'login', 1, NULL, '116.206.202.224', '2026-01-15 15:31:58'),
(1146, 'login', 1, NULL, '103.102.117.157', '2026-01-21 12:23:47'),
(1147, 'login', 25, NULL, '103.102.117.157', '2026-01-21 12:25:53'),
(1148, 'logout', 25, NULL, '103.102.117.157', '2026-01-21 12:27:50'),
(1149, 'login', 23, NULL, '103.102.117.157', '2026-01-21 12:27:55'),
(1150, 'login', 1, NULL, '92.97.56.109', '2026-01-22 17:47:32'),
(1151, 'logout', 1, NULL, '115.187.42.63', '2026-01-22 17:48:50'),
(1152, 'login', 1, NULL, '115.187.42.63', '2026-01-22 17:48:50'),
(1153, 'login', 1, NULL, '103.102.117.135', '2026-01-22 18:01:30'),
(1154, 'logout', 1, NULL, '127.0.0.1', '2026-01-28 16:24:36'),
(1155, 'login', 1, NULL, '127.0.0.1', '2026-01-28 16:24:36'),
(1156, 'logout', 1, NULL, '127.0.0.1', '2026-01-28 16:27:24'),
(1157, 'logout', 1, NULL, '127.0.0.1', '2026-01-28 16:27:35'),
(1158, 'login', 1, NULL, '127.0.0.1', '2026-01-28 16:27:35'),
(1159, 'logout', 1, NULL, '127.0.0.1', '2026-01-29 11:03:16'),
(1160, 'login', 1, NULL, '127.0.0.1', '2026-01-29 11:03:17'),
(1161, 'logout', 1, NULL, '127.0.0.1', '2026-01-29 17:00:16'),
(1162, 'login', 1, NULL, '127.0.0.1', '2026-01-29 17:00:16'),
(1163, 'logout', 1, NULL, '127.0.0.1', '2026-01-30 11:41:47'),
(1164, 'login', 1, NULL, '127.0.0.1', '2026-01-30 11:41:47'),
(1165, 'logout', 1, NULL, '127.0.0.1', '2026-01-30 18:00:17'),
(1166, 'login', 1, NULL, '127.0.0.1', '2026-01-30 18:00:17'),
(1167, 'login', 1, NULL, '103.102.117.174', '2026-02-07 18:06:56'),
(1168, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:13:10'),
(1169, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:14:12'),
(1170, 'login', 1, NULL, '103.102.117.174', '2026-02-07 18:14:12'),
(1171, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:37:50'),
(1172, 'login', 1, NULL, '103.102.117.174', '2026-02-07 18:37:50'),
(1173, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:39:40'),
(1174, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:39:47'),
(1175, 'login', 1, NULL, '103.102.117.174', '2026-02-07 18:39:47'),
(1176, 'logout', 1, NULL, '103.102.117.174', '2026-02-07 18:39:54'),
(1177, 'login', 1, NULL, '49.43.26.87', '2026-02-07 18:48:05'),
(1178, 'logout', 1, NULL, '49.43.26.87', '2026-02-07 18:49:13'),
(1179, 'logout', 1, NULL, '103.102.117.174', '2026-02-10 11:15:46'),
(1180, 'login', 1, NULL, '103.102.117.174', '2026-02-10 11:15:46'),
(1181, 'login', 1, NULL, '116.206.202.181', '2026-02-18 18:23:49'),
(1182, 'login', 1, NULL, '116.206.202.184', '2026-02-21 11:32:52'),
(1183, 'logout', 1, NULL, '116.206.202.184', '2026-02-23 11:07:01'),
(1184, 'login', 1, NULL, '116.206.202.184', '2026-02-23 11:07:01'),
(1185, 'login', 1, NULL, '116.206.202.130', '2026-02-24 12:01:35'),
(1186, 'logout', 1, NULL, '116.206.202.130', '2026-02-24 16:36:15'),
(1187, 'login', 1, NULL, '116.206.202.130', '2026-02-24 16:36:15'),
(1188, 'logout', 1, NULL, '116.206.202.130', '2026-02-25 19:39:47'),
(1189, 'login', 1, NULL, '116.206.202.130', '2026-02-25 19:39:47'),
(1190, 'login', 1, NULL, '116.206.202.204', '2026-03-02 19:30:10'),
(1191, 'login', 1, NULL, '115.187.42.232', '2026-03-02 21:42:51'),
(1192, 'login', 1, NULL, '104.28.86.115', '2026-03-02 21:42:53'),
(1193, 'logout', 1, NULL, '116.206.202.204', '2026-03-04 10:32:26'),
(1194, 'login', 1, NULL, '116.206.202.204', '2026-03-04 10:32:26'),
(1195, 'login', 1, NULL, '49.36.103.217', '2026-03-04 17:49:10'),
(1196, 'logout', 1, NULL, '116.206.202.204', '2026-03-04 19:30:23'),
(1197, 'login', 1, NULL, '116.206.202.204', '2026-03-04 19:30:23'),
(1198, 'logout', 1, NULL, '116.206.202.204', '2026-03-04 19:44:32'),
(1199, 'logout', 1, NULL, '116.206.202.204', '2026-03-04 19:44:40'),
(1200, 'login', 1, NULL, '116.206.202.204', '2026-03-04 19:44:40'),
(1201, 'login', 1, NULL, '103.102.117.138', '2026-03-05 17:30:50'),
(1202, 'logout', 1, NULL, '103.102.117.138', '2026-03-06 10:59:38'),
(1203, 'login', 1, NULL, '103.102.117.138', '2026-03-06 10:59:38'),
(1204, 'logout', 1, NULL, '103.102.117.138', '2026-03-06 16:52:09'),
(1205, 'login', 1, NULL, '103.102.117.138', '2026-03-06 16:52:09'),
(1206, 'logout', 1, NULL, '103.102.117.138', '2026-03-07 11:31:29'),
(1207, 'login', 1, NULL, '103.102.117.138', '2026-03-07 11:31:29'),
(1208, 'logout', 1, NULL, '103.102.117.138', '2026-03-07 16:44:35'),
(1209, 'login', 1, NULL, '103.102.117.138', '2026-03-07 16:44:35'),
(1210, 'logout', 1, NULL, '103.102.117.138', '2026-03-07 19:34:39'),
(1211, 'logout', 1, NULL, '103.102.117.138', '2026-03-07 19:36:02'),
(1212, 'login', 1, NULL, '103.102.117.138', '2026-03-07 19:36:02'),
(1213, 'login', 1, NULL, '49.36.97.51', '2026-03-07 20:47:26'),
(1214, 'logout', 1, NULL, '115.187.42.232', '2026-03-07 20:47:36'),
(1215, 'login', 1, NULL, '115.187.42.232', '2026-03-07 20:47:36'),
(1216, 'logout', 1, NULL, '49.36.97.51', '2026-03-07 20:49:17'),
(1217, 'logout', 1, NULL, '49.36.97.51', '2026-03-07 21:07:07'),
(1218, 'login', 1, NULL, '49.36.97.51', '2026-03-07 21:07:07'),
(1219, 'login', 1, NULL, '152.58.14.213', '2026-03-07 21:10:59'),
(1220, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 10:39:33'),
(1221, 'login', 1, NULL, '103.102.117.138', '2026-03-09 10:39:33'),
(1222, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 11:17:17'),
(1223, 'login', 1, NULL, '49.37.39.27', '2026-03-09 11:17:17'),
(1224, 'login', 1, NULL, '150.242.205.210', '2026-03-09 13:25:45'),
(1225, 'logout', 1, NULL, '150.242.205.210', '2026-03-09 13:27:33'),
(1226, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 13:32:43'),
(1227, 'login', 1, NULL, '103.102.117.138', '2026-03-09 13:32:43'),
(1228, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 14:50:34'),
(1229, 'logout', 1, NULL, '115.187.42.232', '2026-03-09 14:50:44'),
(1230, 'login', 1, NULL, '115.187.42.232', '2026-03-09 14:50:44'),
(1231, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 14:51:13'),
(1232, 'login', 1, NULL, '49.37.39.27', '2026-03-09 14:51:13'),
(1233, 'login', 1, NULL, '49.36.101.223', '2026-03-09 15:27:10'),
(1234, 'logout', 1, NULL, '49.36.101.223', '2026-03-09 15:27:53'),
(1235, 'login', 1, NULL, '110.226.181.150', '2026-03-09 15:40:47'),
(1236, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 15:44:49'),
(1237, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 15:57:26'),
(1238, 'login', 1, NULL, '49.37.39.27', '2026-03-09 15:57:26'),
(1239, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 15:57:34'),
(1240, 'logout', 1, NULL, '115.187.42.232', '2026-03-09 16:00:13'),
(1241, 'login', 1, NULL, '115.187.42.232', '2026-03-09 16:00:13'),
(1242, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 16:04:29'),
(1243, 'login', 1, NULL, '49.37.39.27', '2026-03-09 16:04:29'),
(1244, 'logout', 1, NULL, '49.37.39.27', '2026-03-09 16:07:39'),
(1245, 'login', 1, NULL, '152.58.28.240', '2026-03-09 16:10:49'),
(1246, 'logout', 1, NULL, '152.58.28.240', '2026-03-09 16:13:53'),
(1247, 'logout', 1, NULL, '152.58.28.240', '2026-03-09 16:14:14'),
(1248, 'login', 1, NULL, '152.58.28.240', '2026-03-09 16:14:14'),
(1249, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 16:21:30'),
(1250, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 16:21:48'),
(1251, 'login', 1, NULL, '103.102.117.138', '2026-03-09 16:21:48'),
(1252, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 16:21:56'),
(1253, 'logout', 1, NULL, '152.58.28.240', '2026-03-09 16:24:53'),
(1254, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 16:53:43'),
(1255, 'login', 1, NULL, '103.102.117.138', '2026-03-09 16:53:43'),
(1256, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 17:24:43'),
(1257, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 18:00:25'),
(1258, 'login', 1, NULL, '103.102.117.138', '2026-03-09 18:00:25'),
(1259, 'logout', 1, NULL, '103.102.117.138', '2026-03-09 21:06:39'),
(1260, 'logout', 1, NULL, '103.102.117.138', '2026-03-10 11:33:44'),
(1261, 'login', 1, NULL, '103.102.117.138', '2026-03-10 11:33:44'),
(1262, 'logout', 1, NULL, '103.102.117.138', '2026-03-10 15:56:39'),
(1263, 'login', 1, NULL, '103.102.117.138', '2026-03-10 15:56:39'),
(1264, 'logout', 1, NULL, '103.102.117.138', '2026-03-11 14:51:52'),
(1265, 'login', 1, NULL, '103.102.117.138', '2026-03-11 14:51:52'),
(1266, 'logout', 1, NULL, '103.102.117.138', '2026-03-18 16:07:53'),
(1267, 'login', 1, NULL, '103.102.117.138', '2026-03-18 16:07:53'),
(1268, 'login', 1, NULL, '115.187.42.75', '2026-03-20 14:48:40'),
(1269, 'logout', 1, NULL, '49.37.39.27', '2026-03-20 14:49:08'),
(1270, 'login', 1, NULL, '49.37.39.27', '2026-03-20 14:49:08'),
(1271, 'logout', 1, NULL, '103.102.117.138', '2026-03-20 16:47:26'),
(1272, 'login', 1, NULL, '103.102.117.138', '2026-03-20 16:47:26'),
(1273, 'logout', 1, NULL, '103.102.117.138', '2026-03-20 16:47:54'),
(1274, 'logout', 1, NULL, '115.187.42.75', '2026-03-20 18:47:37'),
(1275, 'login', 1, NULL, '115.187.42.75', '2026-03-20 18:47:37'),
(1276, 'logout', 1, NULL, '103.102.117.138', '2026-03-20 19:15:46'),
(1277, 'login', 1, NULL, '103.102.117.138', '2026-03-20 19:15:46'),
(1278, 'logout', 1, NULL, '103.102.117.138', '2026-03-20 19:29:43'),
(1279, 'logout', 1, NULL, '103.102.117.138', '2026-03-20 19:30:08'),
(1280, 'login', 1, NULL, '103.102.117.138', '2026-03-20 19:30:08'),
(1281, 'login', 1, NULL, '36.255.91.107', '2026-03-21 11:32:09'),
(1282, 'login', 1, NULL, '49.36.57.20', '2026-03-21 11:43:42'),
(1283, 'logout', 1, NULL, '49.36.57.20', '2026-03-21 15:10:59'),
(1284, 'login', 1, NULL, '49.36.57.20', '2026-03-21 15:10:59'),
(1285, 'logout', 1, NULL, '103.102.117.138', '2026-03-21 16:27:42'),
(1286, 'login', 1, NULL, '103.102.117.138', '2026-03-21 16:27:42'),
(1287, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 11:14:22'),
(1288, 'login', 1, NULL, '103.102.117.138', '2026-03-23 11:14:22'),
(1289, 'login', 1, NULL, '110.226.176.206', '2026-03-23 11:46:07'),
(1290, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 15:45:28'),
(1291, 'login', 1, NULL, '103.102.117.138', '2026-03-23 15:45:28'),
(1292, 'login', 1, NULL, '49.37.38.131', '2026-03-23 18:46:46'),
(1293, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 18:47:32'),
(1294, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 18:47:47'),
(1295, 'login', 1, NULL, '103.102.117.138', '2026-03-23 18:47:47'),
(1296, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 18:49:09'),
(1297, 'logout', 1, NULL, '103.102.117.138', '2026-03-23 18:49:26'),
(1298, 'login', 1, NULL, '103.102.117.138', '2026-03-23 18:49:26'),
(1299, 'logout', 1, NULL, '49.37.38.131', '2026-03-23 18:49:32'),
(1300, 'login', 1, NULL, '49.37.38.131', '2026-03-23 18:49:32'),
(1301, 'logout', 1, NULL, '103.102.117.138', '2026-03-24 10:41:03'),
(1302, 'login', 1, NULL, '103.102.117.138', '2026-03-24 10:41:03'),
(1303, 'logout', 1, NULL, '103.102.117.138', '2026-03-24 17:14:41'),
(1304, 'login', 1, NULL, '103.102.117.138', '2026-03-24 17:14:41'),
(1305, 'logout', 1, NULL, '110.226.176.206', '2026-03-24 17:41:40'),
(1306, 'login', 1, NULL, '110.226.176.206', '2026-03-24 17:41:40'),
(1307, 'login', 1, NULL, '103.197.75.247', '2026-03-24 17:42:28'),
(1308, 'logout', 1, NULL, '103.102.117.138', '2026-03-24 19:42:04'),
(1309, 'login', 1, NULL, '103.102.117.138', '2026-03-24 19:42:04'),
(1310, 'logout', 1, NULL, '103.102.117.138', '2026-03-25 11:06:40'),
(1311, 'login', 1, NULL, '103.102.117.138', '2026-03-25 11:06:40'),
(1312, 'logout', 1, NULL, '127.0.0.1', '2026-05-12 18:18:20'),
(1313, 'login', 1, NULL, '127.0.0.1', '2026-05-12 18:18:20'),
(1314, 'logout', 1, NULL, '127.0.0.1', '2026-05-27 11:47:59'),
(1315, 'login', 1, NULL, '127.0.0.1', '2026-05-27 11:47:59'),
(1316, 'logout', 1, NULL, '127.0.0.1', '2026-05-27 15:22:39'),
(1317, 'login', 1, NULL, '127.0.0.1', '2026-05-27 15:22:39'),
(1318, 'logout', 1, NULL, '127.0.0.1', '2026-05-27 15:32:14'),
(1319, 'login', 1, NULL, '127.0.0.1', '2026-05-27 15:32:14'),
(1320, 'logout', 1, NULL, '127.0.0.1', '2026-05-28 10:03:59'),
(1321, 'login', 1, NULL, '127.0.0.1', '2026-05-28 10:03:59'),
(1322, 'logout', 1, NULL, '127.0.0.1', '2026-05-28 14:58:43'),
(1323, 'login', 1, NULL, '127.0.0.1', '2026-05-28 14:58:43'),
(1324, 'logout', 1, NULL, '127.0.0.1', '2026-05-28 20:19:58'),
(1325, 'login', 1, NULL, '127.0.0.1', '2026-05-28 20:19:58'),
(1326, 'logout', 1, NULL, '127.0.0.1', '2026-05-29 11:04:47'),
(1327, 'login', 1, NULL, '127.0.0.1', '2026-05-29 11:04:47'),
(1328, 'logout', 1, NULL, '127.0.0.1', '2026-05-30 16:15:00'),
(1329, 'login', 1, NULL, '127.0.0.1', '2026-05-30 16:15:00'),
(1330, 'logout', 1, NULL, '127.0.0.1', '2026-06-01 12:40:08'),
(1331, 'login', 1, NULL, '127.0.0.1', '2026-06-01 12:40:08'),
(1332, 'logout', 1, NULL, '127.0.0.1', '2026-06-01 15:31:42'),
(1333, 'login', 1, NULL, '127.0.0.1', '2026-06-01 15:31:42'),
(1334, 'logout', 1, NULL, '127.0.0.1', '2026-06-01 16:02:10'),
(1335, 'login', 1, NULL, '116.206.202.211', '2026-06-01 20:40:08'),
(1336, 'login', 1, NULL, '115.187.42.222', '2026-06-01 21:10:51'),
(1337, 'login', 1, NULL, '106.200.221.168', '2026-06-02 22:55:40'),
(1338, 'logout', 1, NULL, '106.200.221.168', '2026-06-02 23:03:13'),
(1339, 'login', 1, NULL, '106.200.221.168', '2026-06-02 23:03:13'),
(1340, 'login', 1, NULL, '152.59.48.190', '2026-06-03 09:27:56'),
(1341, 'logout', 1, NULL, '116.206.202.211', '2026-06-03 15:22:45'),
(1342, 'login', 1, NULL, '116.206.202.211', '2026-06-03 15:22:45'),
(1343, 'logout', 1, NULL, '116.206.202.211', '2026-06-03 19:25:48'),
(1344, 'login', 1, NULL, '116.206.202.211', '2026-06-03 19:25:48'),
(1345, 'login', 1, NULL, '49.37.37.253', '2026-06-05 17:39:38'),
(1346, 'logout', 1, NULL, '49.37.37.253', '2026-06-05 17:39:54'),
(1347, 'login', 1, NULL, '49.37.37.253', '2026-06-05 17:39:54'),
(1348, 'logout', 1, NULL, '116.206.202.211', '2026-06-06 16:05:30'),
(1349, 'login', 1, NULL, '116.206.202.211', '2026-06-06 16:05:30'),
(1350, 'logout', 1, NULL, '116.206.202.211', '2026-06-06 16:05:49'),
(1351, 'login', 1, NULL, '152.59.153.46', '2026-06-06 16:05:50'),
(1352, 'logout', 1, NULL, '116.206.202.211', '2026-06-06 16:07:00'),
(1353, 'login', 1, NULL, '116.206.202.211', '2026-06-06 16:07:00'),
(1354, 'logout', 1, NULL, '152.59.153.46', '2026-06-06 16:19:31'),
(1355, 'logout', 1, NULL, '116.206.202.211', '2026-06-06 21:02:27'),
(1356, 'login', 1, NULL, '116.206.202.211', '2026-06-06 21:02:27'),
(1357, 'logout', 1, NULL, '116.206.202.211', '2026-06-08 08:56:36'),
(1358, 'login', 1, NULL, '116.206.202.211', '2026-06-08 08:56:36'),
(1359, 'logout', 1, NULL, '116.206.202.211', '2026-06-08 20:18:54'),
(1360, 'login', 1, NULL, '116.206.202.211', '2026-06-08 20:18:54'),
(1361, 'logout', 1, NULL, '116.206.202.211', '2026-06-09 15:16:37'),
(1362, 'login', 1, NULL, '116.206.202.211', '2026-06-09 15:16:37'),
(1363, 'login', 1, NULL, '115.187.49.86', '2026-06-13 15:52:05'),
(1364, 'logout', 1, NULL, '115.187.49.86', '2026-06-13 20:26:25'),
(1365, 'login', 1, NULL, '115.187.49.86', '2026-06-13 20:26:25'),
(1366, 'logout', 1, NULL, '49.37.37.253', '2026-06-15 17:56:59'),
(1367, 'login', 1, NULL, '49.37.37.253', '2026-06-15 17:56:59'),
(1368, 'login', 1, NULL, '116.206.202.176', '2026-07-09 11:51:57'),
(1369, 'logout', 1, NULL, '116.206.202.176', '2026-07-09 19:43:13'),
(1370, 'login', 1, NULL, '116.206.202.176', '2026-07-09 19:43:13'),
(1371, 'login', 1, NULL, '49.37.38.245', '2026-07-20 16:16:45'),
(1372, 'logout', 1, NULL, '49.37.38.245', '2026-07-20 16:44:31'),
(1373, 'logout', 1, NULL, '103.102.117.178', '2026-07-23 18:25:40'),
(1374, 'login', 1, NULL, '103.102.117.178', '2026-07-23 18:25:53'),
(1375, 'logout', 1, NULL, '103.102.117.178', '2026-07-23 19:16:45'),
(1376, 'logout', 1, NULL, '49.37.38.245', '2026-07-27 13:42:58'),
(1377, 'login', 1, NULL, '49.37.38.245', '2026-07-27 13:42:58'),
(1378, 'login', 1, NULL, '103.102.117.178', '2026-07-27 16:11:10'),
(1379, 'logout', 1, NULL, '49.37.38.245', '2026-07-27 16:21:19'),
(1380, 'login', 1, NULL, '103.102.117.178', '2026-07-28 07:39:55'),
(1381, 'login', 1, NULL, '103.102.117.178', '2026-07-31 15:39:15'),
(1382, 'logout', 1, NULL, '103.102.117.178', '2026-07-31 15:39:27'),
(1383, 'login', 1, NULL, '103.102.117.178', '2026-07-31 15:40:18'),
(1384, 'login', 1, NULL, '115.187.42.134', '2026-07-31 15:40:30'),
(1385, 'logout', 1, NULL, '103.102.117.178', '2026-07-31 15:41:35'),
(1386, 'login', 1, NULL, '116.206.202.198', '2026-08-03 19:03:39'),
(1387, 'logout', 1, NULL, '116.206.202.198', '2026-08-05 12:29:24'),
(1388, 'login', 1, NULL, '116.206.202.198', '2026-08-05 12:29:24'),
(1389, 'logout', 1, NULL, '116.206.202.198', '2026-08-05 16:08:23'),
(1390, 'login', 1, NULL, '116.206.202.198', '2026-08-05 16:08:23'),
(1391, 'logout', 1, NULL, '116.206.202.198', '2026-08-05 18:49:37'),
(1392, 'login', 1, NULL, '116.206.202.198', '2026-08-05 18:49:37'),
(1393, 'login', 1, NULL, '49.37.39.243', '2026-08-05 20:26:18'),
(1394, 'login', 1, NULL, '116.206.202.191', '2026-08-06 11:06:45'),
(1395, 'logout', 1, NULL, '49.37.39.243', '2026-08-06 12:09:17'),
(1396, 'login', 1, NULL, '49.37.39.243', '2026-08-06 12:09:17'),
(1397, 'logout', 1, NULL, '49.37.39.243', '2026-08-06 12:48:10'),
(1398, 'login', 1, NULL, '49.37.39.243', '2026-08-06 12:48:10'),
(1399, 'logout', 1, NULL, '116.206.202.191', '2026-08-06 20:22:54'),
(1400, 'login', 1, NULL, '116.206.202.191', '2026-08-06 20:22:54'),
(1401, 'logout', 1, NULL, '49.37.39.243', '2026-08-06 20:25:03'),
(1402, 'login', 1, NULL, '49.37.39.243', '2026-08-06 20:25:03'),
(1403, 'logout', 1, NULL, '116.206.202.191', '2026-08-07 11:01:58'),
(1404, 'login', 1, NULL, '116.206.202.191', '2026-08-07 11:01:58'),
(1405, 'logout', 1, NULL, '116.206.202.191', '2026-08-07 14:32:10'),
(1406, 'login', 1, NULL, '116.206.202.191', '2026-08-07 14:32:10'),
(1407, 'logout', 1, NULL, '116.206.202.191', '2026-08-07 17:52:53'),
(1408, 'login', 1, NULL, '116.206.202.191', '2026-08-07 17:52:53'),
(1409, 'logout', 1, NULL, '49.37.39.243', '2026-08-07 18:32:23'),
(1410, 'login', 1, NULL, '49.37.39.243', '2026-08-07 18:32:23'),
(1411, 'logout', 1, NULL, '49.37.39.243', '2026-08-07 21:37:23'),
(1412, 'logout', 1, NULL, '116.206.202.191', '2026-08-08 11:11:30'),
(1413, 'login', 1, NULL, '116.206.202.191', '2026-08-08 11:11:30'),
(1414, 'logout', 1, NULL, '49.37.39.243', '2026-08-08 11:21:38'),
(1415, 'login', 1, NULL, '49.37.39.243', '2026-08-08 11:21:38'),
(1416, 'logout', 1, NULL, '116.206.202.191', '2026-08-08 16:39:06'),
(1417, 'login', 1, NULL, '116.206.202.191', '2026-08-08 16:39:06'),
(1418, 'login', 1, NULL, '49.37.35.223', '2026-08-08 16:41:49'),
(1419, 'logout', 1, NULL, '49.37.39.243', '2026-08-08 18:52:06'),
(1420, 'login', 1, NULL, '49.37.39.243', '2026-08-08 18:52:06'),
(1421, 'logout', 1, NULL, '116.206.202.191', '2026-08-09 18:31:59'),
(1422, 'login', 1, NULL, '116.206.202.191', '2026-08-09 18:31:59'),
(1423, 'logout', 1, NULL, '116.206.202.191', '2026-08-10 11:24:06'),
(1424, 'login', 1, NULL, '116.206.202.191', '2026-08-10 11:24:06'),
(1425, 'logout', 1, NULL, '116.206.202.191', '2026-08-10 13:36:19'),
(1426, 'login', 1, NULL, '116.206.202.191', '2026-08-10 13:36:19'),
(1427, 'logout', 1, NULL, '116.206.202.191', '2026-08-10 17:29:32'),
(1428, 'login', 1, NULL, '116.206.202.191', '2026-08-10 17:29:32'),
(1429, 'login', 1, NULL, '116.206.202.232', '2026-08-18 17:36:03'),
(1430, 'logout', 1, NULL, '116.206.202.232', '2026-08-19 08:21:06'),
(1431, 'login', 1, NULL, '116.206.202.232', '2026-08-19 08:21:06'),
(1432, 'logout', 1, NULL, '116.206.202.232', '2026-08-27 11:31:53'),
(1433, 'login', 1, NULL, '116.206.202.232', '2026-08-27 11:31:53'),
(1434, 'logout', 1, NULL, '116.206.202.232', '2026-08-29 19:08:26'),
(1435, 'login', 1, NULL, '116.206.202.232', '2026-08-29 19:08:26'),
(1436, 'login', 1, NULL, '49.37.39.107', '2026-09-01 22:10:10'),
(1437, 'logout', 1, NULL, '49.37.39.107', '2026-09-02 11:28:50'),
(1438, 'login', 1, NULL, '49.37.39.107', '2026-09-02 11:28:50'),
(1439, 'login', 1, NULL, '49.37.1.79', '2026-09-02 14:47:30'),
(1440, 'logout', 1, NULL, '116.206.202.232', '2026-09-02 15:03:21'),
(1441, 'login', 1, NULL, '116.206.202.232', '2026-09-02 15:03:21'),
(1442, 'logout', 1, NULL, '116.206.202.232', '2026-09-02 17:32:41'),
(1443, 'logout', 1, NULL, '116.206.202.232', '2026-09-02 17:35:43'),
(1444, 'login', 1, NULL, '116.206.202.232', '2026-09-02 17:35:43'),
(1445, 'login', 1, NULL, '49.37.33.53', '2026-09-03 13:56:57'),
(1446, 'logout', 1, NULL, '116.206.202.232', '2026-09-03 15:48:39'),
(1447, 'login', 1, NULL, '116.206.202.232', '2026-09-03 15:48:39'),
(1448, 'logout', 1, NULL, '116.206.202.232', '2026-09-04 10:08:01'),
(1449, 'login', 1, NULL, '116.206.202.232', '2026-09-04 10:08:01'),
(1450, 'logout', 1, NULL, '116.206.202.232', '2026-09-04 12:50:36'),
(1451, 'login', 1, NULL, '116.206.202.232', '2026-09-04 12:50:36'),
(1452, 'logout', 1, NULL, '116.206.202.232', '2026-09-04 15:49:38'),
(1453, 'login', 1, NULL, '116.206.202.232', '2026-09-04 15:49:38'),
(1454, 'logout', 1, NULL, '116.206.202.232', '2026-09-04 18:52:10'),
(1455, 'logout', 1, NULL, '116.206.202.232', '2026-09-05 10:43:55'),
(1456, 'login', 1, NULL, '116.206.202.232', '2026-09-05 10:43:55'),
(1457, 'logout', 1, NULL, '116.206.202.232', '2026-09-05 16:21:54'),
(1458, 'login', 1, NULL, '116.206.202.232', '2026-09-05 16:21:54'),
(1459, 'logout', 1, NULL, '49.37.39.107', '2026-09-05 17:10:23'),
(1460, 'login', 1, NULL, '49.37.39.107', '2026-09-05 17:10:23'),
(1461, 'login', 1, NULL, '49.37.33.161', '2026-09-07 22:09:16'),
(1462, 'login', 1, NULL, '115.187.37.217', '2026-09-17 11:10:37'),
(1463, 'logout', 1, NULL, '115.187.37.217', '2026-09-17 16:52:52'),
(1464, 'login', 1, NULL, '115.187.37.217', '2026-09-17 16:52:52'),
(1465, 'logout', 1, NULL, '115.187.37.217', '2026-09-21 15:11:42'),
(1466, 'login', 1, NULL, '115.187.37.217', '2026-09-21 15:11:42'),
(1467, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 11:47:48'),
(1468, 'login', 1, NULL, '115.187.37.217', '2026-09-22 11:47:48'),
(1469, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 12:23:57'),
(1470, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 12:28:57'),
(1471, 'login', 1, NULL, '115.187.37.217', '2026-09-22 12:28:57'),
(1472, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 12:55:03'),
(1473, 'login', 36, NULL, '115.187.37.217', '2026-09-22 12:59:52'),
(1474, 'logout', 36, NULL, '115.187.37.217', '2026-09-22 16:04:46'),
(1475, 'login', 36, NULL, '115.187.37.217', '2026-09-22 16:04:46'),
(1476, 'logout', 36, NULL, '115.187.37.217', '2026-09-22 19:07:10'),
(1477, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 19:07:38'),
(1478, 'login', 1, NULL, '115.187.37.217', '2026-09-22 19:07:38'),
(1479, 'logout', 1, NULL, '115.187.37.217', '2026-09-22 19:07:58'),
(1480, 'logout', 36, NULL, '115.187.37.217', '2026-09-22 19:08:16'),
(1481, 'login', 36, NULL, '115.187.37.217', '2026-09-22 19:08:16'),
(1482, 'logout', 36, NULL, '115.187.37.217', '2026-09-22 19:20:58'),
(1483, 'logout', 36, NULL, '115.187.37.217', '2026-09-22 19:23:18'),
(1484, 'login', 36, NULL, '115.187.37.217', '2026-09-22 19:23:18'),
(1485, 'login', 35, NULL, '115.187.37.217', '2026-09-23 16:46:29'),
(1486, 'logout', 35, NULL, '115.187.37.217', '2026-09-23 19:35:50'),
(1487, 'logout', 1, NULL, '115.187.37.217', '2026-09-23 19:39:08'),
(1488, 'login', 1, NULL, '115.187.37.217', '2026-09-23 19:39:08'),
(1489, 'logout', 35, NULL, '115.187.37.217', '2026-09-23 19:43:56'),
(1490, 'login', 35, NULL, '115.187.37.217', '2026-09-23 19:43:56'),
(1491, 'logout', 1, NULL, '115.187.37.217', '2026-09-24 12:16:52'),
(1492, 'login', 1, NULL, '115.187.37.217', '2026-09-24 12:16:52'),
(1493, 'logout', 1, NULL, '115.187.37.217', '2026-09-24 16:16:27'),
(1494, 'login', 1, NULL, '115.187.37.217', '2026-09-24 16:16:27'),
(1495, 'login', 1, NULL, '116.206.202.235', '2026-09-26 10:08:49'),
(1496, 'login', 35, NULL, '116.206.202.235', '2026-09-26 10:58:47'),
(1497, 'logout', 1, NULL, '103.102.117.131', '2026-09-26 15:29:28'),
(1498, 'login', 1, NULL, '103.102.117.131', '2026-09-26 15:29:28'),
(1499, 'login', 35, NULL, '103.102.117.131', '2026-09-26 15:29:56'),
(1500, 'logout', 35, NULL, '103.102.117.131', '2026-09-26 21:13:19'),
(1501, 'login', 35, NULL, '103.102.117.131', '2026-09-26 21:13:19'),
(1502, 'logout', 1, NULL, '103.102.117.131', '2026-09-26 21:14:03'),
(1503, 'login', 1, NULL, '103.102.117.131', '2026-09-26 21:14:03'),
(1504, 'logout', 35, NULL, '103.102.117.131', '2026-09-26 21:15:36'),
(1505, 'logout', 1, NULL, '103.102.117.131', '2026-09-26 21:16:41'),
(1506, 'login', 36, NULL, '103.102.117.131', '2026-09-26 21:18:06'),
(1507, 'logout', 36, NULL, '103.102.117.131', '2026-09-26 21:18:21'),
(1508, 'logout', 1, NULL, '103.102.117.131', '2026-09-26 21:18:32'),
(1509, 'login', 1, NULL, '103.102.117.131', '2026-09-26 21:18:32'),
(1510, 'logout', 1, NULL, '103.102.117.131', '2026-09-28 10:36:29'),
(1511, 'login', 1, NULL, '103.102.117.131', '2026-09-28 10:36:29'),
(1512, 'logout', 1, NULL, '103.102.117.131', '2026-09-28 10:38:34'),
(1513, 'logout', 35, NULL, '103.102.117.131', '2026-09-28 10:38:49'),
(1514, 'login', 35, NULL, '103.102.117.131', '2026-09-28 10:38:49'),
(1515, 'logout', 1, NULL, '103.102.117.131', '2026-09-28 10:58:16'),
(1516, 'login', 1, NULL, '103.102.117.131', '2026-09-28 10:58:16'),
(1517, 'logout', 35, NULL, '103.102.117.131', '2026-09-28 14:37:08'),
(1518, 'login', 35, NULL, '103.102.117.131', '2026-09-28 14:37:08'),
(1519, 'login', 1, NULL, '43.249.187.195', '2026-10-02 15:10:03'),
(1520, 'logout', 1, NULL, '43.249.187.195', '2026-10-02 15:12:19'),
(1521, 'login', 1, NULL, '43.249.187.195', '2026-10-02 15:12:19'),
(1522, 'login', 1, NULL, '49.37.3.7', '2026-10-02 15:39:09'),
(1523, 'login', 1, NULL, '103.102.117.139', '2026-10-02 16:18:18'),
(1524, 'logout', 1, NULL, '43.249.187.195', '2026-10-03 08:35:50'),
(1525, 'login', 1, NULL, '43.249.187.195', '2026-10-03 08:35:50'),
(1526, 'logout', 1, NULL, '43.249.187.195', '2026-10-03 11:58:48'),
(1527, 'login', 1, NULL, '43.249.187.195', '2026-10-03 11:58:48'),
(1528, 'login', 1, NULL, '43.249.186.13', '2026-10-03 15:54:11'),
(1529, 'logout', 1, NULL, '103.102.117.139', '2026-10-05 13:46:41'),
(1530, 'login', 1, NULL, '103.102.117.139', '2026-10-05 13:46:41'),
(1531, 'logout', 1, NULL, '103.102.117.139', '2026-10-05 16:50:39'),
(1532, 'login', 1, NULL, '103.102.117.139', '2026-10-05 16:50:39'),
(1533, 'login', 1, NULL, '49.15.92.58', '2026-10-05 17:51:03'),
(1534, 'logout', 1, NULL, '103.102.117.139', '2026-10-06 19:05:45'),
(1535, 'login', 1, NULL, '103.102.117.139', '2026-10-06 19:05:45');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2025_03_05_115144_create_jobs_table', 1),
(10, '2025_08_02_161234_create_talukas_table', 3),
(11, '2025_08_02_161926_create_constituencies_table', 4),
(12, '2025_08_01_190249_create_applications_table', 5),
(13, '2025_08_05_193604_create_notings_table', 6),
(15, '2026_01_07_190532_create_leads_table', 7),
(16, '2026_01_08_190305_create_lead_users_table', 8),
(17, '2026_01_09_112851_create_packages_table', 9),
(19, '2026_01_09_155943_create_lead_packages_table', 10),
(21, '2026_01_09_181913_create_package_users_table', 11),
(22, '2026_01_28_170355_create_students_table', 12),
(23, '2026_01_28_174712_create_banners_table', 13),
(24, '2026_01_29_200947_create_food_categories_table', 14),
(26, '2026_01_29_201003_create_food_table', 15),
(27, '2026_01_30_181852_create_habits_table', 16),
(28, '2026_01_21_193729_create_schools_table', 17),
(29, '2026_05_28_113238_create_service_categories_table', 18),
(30, '2026_05_28_153342_create_services_table', 19),
(31, '2026_05_28_194154_create_service_providers_table', 20),
(32, '2026_05_28_202142_create_coupon_codes_table', 21);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Admin', 25, 'auth_token', '62882b78f548d554e5357365ec21e6cf256bbb96ee0369d88648c577c8f6741b', '[\"*\"]', NULL, NULL, '2026-03-05 00:45:56', '2026-03-05 00:45:56'),
(2, 'App\\Models\\Admin', 25, 'auth_token', '0a49c8898978dc69f2ccfd001366d92cdad0edf5034a10a4f9717dab81821f2e', '[\"*\"]', NULL, NULL, '2026-03-18 20:45:28', '2026-03-18 20:45:28'),
(3, 'App\\Models\\Admin', 25, 'auth_token', '0b8ba47c6d255ac28f541587b59f17d0538eb28359f29a338a272e4ad41a7983', '[\"*\"]', NULL, NULL, '2026-03-23 16:35:05', '2026-03-23 16:35:05'),
(4, 'App\\Models\\Admin', 25, 'auth_token', '1b11e07d7333dc1aa14ad77bf81088b8315b2f1dc1c095f4172a533e44050b18', '[\"*\"]', NULL, NULL, '2026-03-23 20:42:42', '2026-03-23 20:42:42'),
(5, 'App\\Models\\Admin', 25, 'auth_token', 'c7905f754b679f1c93687bbf93a8345cd9b097f9092665b4d4706c9ca028d23c', '[\"*\"]', NULL, NULL, '2026-03-23 20:56:24', '2026-03-23 20:56:24'),
(6, 'App\\Models\\Admin', 25, 'auth_token', '938b9edf581fcf322ff419b4894062fd8ce705a4f84d1c42db5e81f1163a5419', '[\"*\"]', NULL, NULL, '2026-03-23 21:02:48', '2026-03-23 21:02:48'),
(7, 'App\\Models\\Admin', 25, 'auth_token', '2525832a39742ecb4dee599a0466b91f3e3de18e925f421e25f5ed1c6e865c67', '[\"*\"]', NULL, NULL, '2026-03-23 21:13:50', '2026-03-23 21:13:50'),
(8, 'App\\Models\\Admin', 25, 'auth_token', '4edc107fccea1f428732ed035479d2f2f5e77973231feee7a0e177fd80291525', '[\"*\"]', NULL, NULL, '2026-03-23 22:07:44', '2026-03-23 22:07:44'),
(9, 'App\\Models\\Admin', 25, 'auth_token', '1402eba05cc6b49a73162d000e7fc90ab48df8104ccc9442ffe506186c058588', '[\"*\"]', NULL, NULL, '2026-03-23 23:33:58', '2026-03-23 23:33:58'),
(10, 'App\\Models\\Admin', 25, 'auth_token', '9cd9f72c3f649c71a93e8c9dc948413fe1131dc81e73bdfab9f0df1e150a2dfc', '[\"*\"]', NULL, NULL, '2026-03-24 03:21:02', '2026-03-24 03:21:02'),
(11, 'App\\Models\\Admin', 25, 'auth_token', '06c921ec31486e7655fd0d11642aedd91e2d8a283e077a0b9704f8832a930bd5', '[\"*\"]', NULL, NULL, '2026-03-24 03:31:43', '2026-03-24 03:31:43'),
(12, 'App\\Models\\Admin', 25, 'auth_token', '6491999924258004f7a8990f7498c3e4b39331d50fff1d2d9615a376c776da5e', '[\"*\"]', NULL, NULL, '2026-03-24 21:28:40', '2026-03-24 21:28:40'),
(13, 'App\\Models\\Admin', 25, 'auth_token', '7ec9034cdb1c1a0d00d3d4d5f1e383dead994c32ac330de81b627f51a786ec92', '[\"*\"]', NULL, NULL, '2026-03-24 21:29:18', '2026-03-24 21:29:18'),
(14, 'App\\Models\\Admin', 25, 'auth_token', 'b7557e0ee2f418bbf006e30b85da60b45f374737da568f811823a3e36530835f', '[\"*\"]', NULL, NULL, '2026-03-25 04:42:40', '2026-03-25 04:42:40'),
(15, 'App\\Models\\Admin', 25, 'auth_token', 'd12cc1a680750e3c9a46d193513abe04fc7d0b620d6ffec842881aea6329477c', '[\"*\"]', NULL, NULL, '2026-03-25 04:42:40', '2026-03-25 04:42:40'),
(16, 'App\\Models\\Admin', 31, 'auth_token', '1fc5536260544a3915a737f2f1fc8cfa3f7839363aae2326278478582d456993', '[\"*\"]', NULL, NULL, '2026-03-25 15:23:09', '2026-03-25 15:23:09'),
(17, 'App\\Models\\Admin', 31, 'auth_token', 'e785c51e8223a36226637b0f66deb166d0fa202667dcbc404fd670fd74e101ef', '[\"*\"]', NULL, NULL, '2026-03-25 15:57:41', '2026-03-25 15:57:41'),
(18, 'App\\Models\\Admin', 31, 'auth_token', 'b976ad704ca6a9e1fefe67823465a04c2d1238e724180cfd0060f0db788bb3ea', '[\"*\"]', NULL, NULL, '2026-03-25 15:58:52', '2026-03-25 15:58:52'),
(19, 'App\\Models\\Admin', 31, 'auth_token', 'c9d4f1c7dea7bba9408e5a37191218a0b9d04ef39e469363bea69a03082ec435', '[\"*\"]', NULL, NULL, '2026-03-25 16:08:03', '2026-03-25 16:08:03'),
(20, 'App\\Models\\Admin', 31, 'auth_token', '130c53b49890309767511b526cc776a781980f32e8973e3b0f1961843b5394bb', '[\"*\"]', NULL, NULL, '2026-03-25 16:08:57', '2026-03-25 16:08:57'),
(21, 'App\\Models\\Admin', 31, 'auth_token', 'b830044de630965fd3ef51dca88f9e1643cdfb228c441c349abc6a2ac68e39f7', '[\"*\"]', NULL, NULL, '2026-03-25 16:10:22', '2026-03-25 16:10:22'),
(22, 'App\\Models\\Admin', 31, 'auth_token', '427e75f9013ff5f15568559642f89d937b6fb2c34d0f52e505617114171eef73', '[\"*\"]', NULL, NULL, '2026-03-25 16:29:01', '2026-03-25 16:29:01'),
(23, 'App\\Models\\Admin', 32, 'auth_token', 'd94f75944664f104ad0dc0066bcb9e58165e9e1edbc87261fa6909c81aa828d7', '[\"*\"]', NULL, NULL, '2026-08-05 10:57:26', '2026-08-05 10:57:26'),
(24, 'App\\Models\\Admin', 32, 'auth_token', 'eb6f65385adc4ec5dec898c51db4e0c02a64127993bf45dd73e2f7e5e6eec1a1', '[\"*\"]', NULL, NULL, '2026-08-05 13:23:59', '2026-08-05 13:23:59'),
(25, 'App\\Models\\Admin', 32, 'auth_token', '0c6c65d0632fb1d1640ebf58fab6a84891900ab3a6d1f78ced5f5f6018d66ae9', '[\"*\"]', NULL, NULL, '2026-08-11 08:50:49', '2026-08-11 08:50:49'),
(26, 'App\\Models\\Admin', 33, 'auth_token', '713a4945eb28e046b83ab1cef77417e6c0812ec4e26e97a4fdeef6e905f97c99', '[\"*\"]', NULL, NULL, '2026-08-11 08:53:09', '2026-08-11 08:53:09'),
(27, 'App\\Models\\Admin', 32, 'auth_token', '04ad4b101251bcde940c4c2eda3f1ecf1de070164d55bc7e4f87667a3f008c6d', '[\"*\"]', NULL, NULL, '2026-08-11 08:54:33', '2026-08-11 08:54:33'),
(28, 'App\\Models\\Admin', 33, 'auth_token', '75822c46ff259326388a9ca8ad41d070f7d66b41b4539f8413bcc7bba3e6165b', '[\"*\"]', NULL, NULL, '2026-08-11 08:54:43', '2026-08-11 08:54:43'),
(29, 'App\\Models\\Admin', 33, 'auth_token', 'd726f9f7cb0988fe04c013e5bfedfcfbc2e421df44bad11cb92fcfe1648dfbf5', '[\"*\"]', NULL, NULL, '2026-08-11 08:54:47', '2026-08-11 08:54:47'),
(30, 'App\\Models\\Admin', 33, 'auth_token', '2b7b7bf0f4641b1f6485ad895c27ff390ccf28e5b7ebce28970d72e23aa677c8', '[\"*\"]', NULL, NULL, '2026-08-11 08:55:10', '2026-08-11 08:55:10'),
(31, 'App\\Models\\Admin', 32, 'auth_token', '46c914605fcabd835d34358b71486cf092681ea1094a5bdf1e9c9b8be3d93d44', '[\"*\"]', NULL, NULL, '2026-08-11 08:56:21', '2026-08-11 08:56:21'),
(32, 'App\\Models\\Admin', 33, 'auth_token', 'c1cb529a36d835c249acc64b3256fe581293d24c99a7563506db47ec3450290a', '[\"*\"]', NULL, NULL, '2026-08-11 08:56:40', '2026-08-11 08:56:40'),
(33, 'App\\Models\\Admin', 33, 'auth_token', '0107a78a7f286d68b122af007d1fdd9c1458bd1988ea37df4576fd9236330899', '[\"*\"]', NULL, NULL, '2026-08-11 09:14:10', '2026-08-11 09:14:10'),
(34, 'App\\Models\\Admin', 33, 'auth_token', '4d6ced7500f533c19c896d5944318c522ed08ed4fa1f45b25669bb0fa533129a', '[\"*\"]', NULL, NULL, '2026-08-11 09:16:31', '2026-08-11 09:16:31'),
(35, 'App\\Models\\Admin', 32, 'auth_token', 'f32a7c3cca34416bf997939e7f86fa057c363fa4501ec4b602a7d2e13c45f0f1', '[\"*\"]', NULL, NULL, '2026-08-11 09:17:14', '2026-08-11 09:17:14'),
(36, 'App\\Models\\Admin', 33, 'auth_token', '2a9645bd04d7245bbad4795ebc623d14eb585c803e61324936b897dce9ec17ba', '[\"*\"]', NULL, NULL, '2026-08-11 09:23:34', '2026-08-11 09:23:34'),
(37, 'App\\Models\\Admin', 33, 'auth_token', '6d1cb028d91429df917bef606b00077b649348de2401614a7b9f570a441e64e8', '[\"*\"]', NULL, NULL, '2026-08-11 09:33:23', '2026-08-11 09:33:23'),
(38, 'App\\Models\\Admin', 33, 'auth_token', 'b05ed61d13490e5e242695bec6d4469a9dc220edc6515f5626fc4ae5576f24c6', '[\"*\"]', NULL, NULL, '2026-08-11 09:33:32', '2026-08-11 09:33:32'),
(39, 'App\\Models\\Admin', 33, 'auth_token', 'e7eb86bf6f35c20e66e628bbc5ca1d21343610ff4ee1261dbbe05f9402775f22', '[\"*\"]', NULL, NULL, '2026-08-11 09:33:43', '2026-08-11 09:33:43'),
(40, 'App\\Models\\Admin', 33, 'auth_token', '32d93ff2de0a2a5124df86f0c2e57520eecce0f51b2bdeeb84408f305786bdb0', '[\"*\"]', NULL, NULL, '2026-08-11 09:37:25', '2026-08-11 09:37:25'),
(41, 'App\\Models\\Admin', 33, 'auth_token', '621fc17908540f5871a853197dfacc6881e2e6f911b05ba1f7a4dc41777e2c85', '[\"*\"]', NULL, NULL, '2026-08-11 15:03:21', '2026-08-11 15:03:21'),
(42, 'App\\Models\\Admin', 33, 'auth_token', '62458ab63520981c5f96c62be4668a01db9412661bbe65f2d99426f91fc0a038', '[\"*\"]', NULL, NULL, '2026-08-11 15:24:08', '2026-08-11 15:24:08'),
(43, 'App\\Models\\Admin', 33, 'auth_token', '1555be12ad69736a06f1ea9285d2e716aeca25f277fcd5280a54553ca6e0d7cc', '[\"*\"]', NULL, NULL, '2026-08-11 15:26:43', '2026-08-11 15:26:43'),
(44, 'App\\Models\\Admin', 33, 'auth_token', '0ead44dddfa1a61f2b2c53bf1d25c762a7d7d1137a85bd34dd46ed46475d9085', '[\"*\"]', NULL, NULL, '2026-08-11 15:43:48', '2026-08-11 15:43:48'),
(45, 'App\\Models\\Admin', 33, 'auth_token', 'fe55dc7019617d29f5d4c64fcbb2e642500eb0ad56c8caa75a1d225d956859d0', '[\"*\"]', NULL, NULL, '2026-08-15 11:12:58', '2026-08-15 11:12:58'),
(46, 'App\\Models\\Admin', 33, 'auth_token', '1128d55adc93389a623d83045a47608a735765ffc41878bcc00dee59b5f19058', '[\"*\"]', NULL, NULL, '2026-08-18 12:08:24', '2026-08-18 12:08:24'),
(47, 'App\\Models\\Admin', 33, 'auth_token', 'e49c69670eb37df9f2f918f0753caedae8cf2472ffe66f27adff47789b526f87', '[\"*\"]', NULL, NULL, '2026-08-18 12:08:42', '2026-08-18 12:08:42'),
(48, 'App\\Models\\Admin', 33, 'auth_token', 'd4ce7291699dda663b98e1e84c5f6118bc11d0f4a251603d77931e2f7866409e', '[\"*\"]', NULL, NULL, '2026-08-18 12:08:48', '2026-08-18 12:08:48'),
(49, 'App\\Models\\Admin', 33, 'auth_token', '0b555a954b8640b0bdec0271bbea603c9ca7439c24f438f557038c75b70b47fd', '[\"*\"]', NULL, NULL, '2026-08-19 10:45:16', '2026-08-19 10:45:16'),
(50, 'App\\Models\\Admin', 33, 'auth_token', '5181443dfdcb327bb41fc1512bfbb710a4cf5ccff59b180530f9a2242f96acd1', '[\"*\"]', NULL, NULL, '2026-08-19 10:45:22', '2026-08-19 10:45:22'),
(51, 'App\\Models\\Admin', 33, 'auth_token', 'd497de7d00870a61cb33127d4dd6af5dc8ebf7a41e65f480c5192a64ffd7b9e7', '[\"*\"]', NULL, NULL, '2026-08-19 10:45:44', '2026-08-19 10:45:44'),
(52, 'App\\Models\\Admin', 33, 'auth_token', '79eaf0d979edbda528ebcc6e7fe77dc3e9e2f141fbd2d85ef5911302fabb98c3', '[\"*\"]', NULL, NULL, '2026-08-19 12:19:26', '2026-08-19 12:19:26'),
(53, 'App\\Models\\Admin', 33, 'auth_token', '8e723fdf756b5e7a5be4740e1ae5bb333a98072a2e6530fd7e30883f34dd9e00', '[\"*\"]', NULL, NULL, '2026-08-19 12:20:46', '2026-08-19 12:20:46'),
(54, 'App\\Models\\Admin', 33, 'auth_token', '2cb5a4522cea07d75f9da314e6e6345ef6b79c84d9593ff9e2dea84fc7a6d8bd', '[\"*\"]', NULL, NULL, '2026-08-19 12:31:10', '2026-08-19 12:31:10'),
(55, 'App\\Models\\Admin', 33, 'auth_token', '8ed9891904b07a2bb4bdd6d532aa1924eaf32e586f925d19cf8582401dde7bbb', '[\"*\"]', NULL, NULL, '2026-08-19 12:41:48', '2026-08-19 12:41:48'),
(56, 'App\\Models\\Admin', 33, 'auth_token', '715b4ccc2e4aca677b3eb90079866333c571d8f4fd7f3d96b8649060dadcbbdf', '[\"*\"]', NULL, NULL, '2026-08-19 12:50:27', '2026-08-19 12:50:27'),
(57, 'App\\Models\\Admin', 33, 'auth_token', '10f3e5494484e4ac1789e88fdce8ba6268957ed622b96ca547e95e69e544ca0c', '[\"*\"]', NULL, NULL, '2026-08-19 13:43:37', '2026-08-19 13:43:37'),
(58, 'App\\Models\\Admin', 33, 'auth_token', '0bf87eb2e6141aaeb8d0f105ae02810e2cda24fb5b7004e9cb9d3bf96debc1de', '[\"*\"]', NULL, NULL, '2026-08-19 13:49:45', '2026-08-19 13:49:45'),
(59, 'App\\Models\\Admin', 33, 'auth_token', '9e32dd66099d7bb8b06b6496af1fa678f0736621ed2c9ad5e98f94836843187e', '[\"*\"]', NULL, NULL, '2026-08-19 13:52:31', '2026-08-19 13:52:31'),
(60, 'App\\Models\\Admin', 33, 'auth_token', '160542ea039c198cebb18e199ed4bf086b919c0b6f6f70177de72823c6273b02', '[\"*\"]', NULL, NULL, '2026-08-19 14:01:07', '2026-08-19 14:01:07'),
(61, 'App\\Models\\Admin', 33, 'auth_token', '1bd778db55c3740e9e9573b8bd40579be85100ca86c5c8459a4abf4984fe7d0e', '[\"*\"]', NULL, NULL, '2026-08-19 14:12:31', '2026-08-19 14:12:31'),
(62, 'App\\Models\\Admin', 33, 'auth_token', '6cfc2da330965b297a4746390c00dce26e4ed8931b5f8cbb4fe15455799c5ad9', '[\"*\"]', NULL, NULL, '2026-08-19 14:25:43', '2026-08-19 14:25:43'),
(63, 'App\\Models\\Admin', 33, 'auth_token', 'b483f68696510bb7de74968b6f81d77829f19ea738a50f75ceadb6bc13af61bb', '[\"*\"]', NULL, NULL, '2026-08-19 14:40:51', '2026-08-19 14:40:51'),
(64, 'App\\Models\\Admin', 33, 'auth_token', 'a0d9524ac3fbe1a26ed8e12f184b9c109c77d498435d34c31ec4a4a7e8ceaa4c', '[\"*\"]', NULL, NULL, '2026-08-19 14:47:13', '2026-08-19 14:47:13'),
(65, 'App\\Models\\Admin', 33, 'auth_token', '32dc8e0ec904d7f7aa5783171a77ef9cabb6275ddd867bfa893a99acef10d510', '[\"*\"]', NULL, NULL, '2026-08-20 14:23:14', '2026-08-20 14:23:14'),
(66, 'App\\Models\\Admin', 33, 'auth_token', 'e07240e63c2ea7491f268039f09ccb6c4d6315591473a3f31d2a8e4a76ed2d08', '[\"*\"]', NULL, NULL, '2026-08-21 06:27:15', '2026-08-21 06:27:15'),
(67, 'App\\Models\\Admin', 33, 'auth_token', 'b0587798fe3472b137a30a0503620905974620acd65055d056e8f2a781e6149e', '[\"*\"]', NULL, NULL, '2026-08-21 08:23:40', '2026-08-21 08:23:40'),
(68, 'App\\Models\\Admin', 33, 'auth_token', '7f0cffb769891c1695f76616348cb21a46438d7cfd08b3d9d3b7441bd10a3c66', '[\"*\"]', NULL, NULL, '2026-08-21 08:42:46', '2026-08-21 08:42:46'),
(69, 'App\\Models\\Admin', 33, 'auth_token', 'af24267525939e4719ed34454c0efb91379cd3b0b527ed89ee4f445d56c73ccd', '[\"*\"]', NULL, NULL, '2026-08-21 08:51:59', '2026-08-21 08:51:59'),
(70, 'App\\Models\\Admin', 33, 'auth_token', '78de13687edec53efa2b74fca41a504c3086edd4b69b67f1816fbf260d6bb888', '[\"*\"]', NULL, NULL, '2026-08-21 09:17:14', '2026-08-21 09:17:14'),
(71, 'App\\Models\\Admin', 33, 'auth_token', '9d3fddd5470dfe6e22b7209996248557d0ebd1e406e825eb1c8b188158e16839', '[\"*\"]', NULL, NULL, '2026-08-21 09:41:57', '2026-08-21 09:41:57'),
(72, 'App\\Models\\Admin', 33, 'auth_token', '64ee8d87c41f5f04ef4ca86970d410af192c2551de7cedaf67c7fe865c69e9a5', '[\"*\"]', NULL, NULL, '2026-08-28 09:20:04', '2026-08-28 09:20:04'),
(73, 'App\\Models\\Admin', 33, 'auth_token', 'c61964d0829b1cc35e51149f9868cb07b622fc1209a976c370d7658e68d518cb', '[\"*\"]', NULL, NULL, '2026-08-28 09:20:04', '2026-08-28 09:20:04'),
(74, 'App\\Models\\Admin', 33, 'auth_token', '2393dee88f3ad7672ac24e00b8c01da8ff879767948e4ca4d66b28db24dff179', '[\"*\"]', NULL, NULL, '2026-08-28 15:00:35', '2026-08-28 15:00:35'),
(75, 'App\\Models\\Admin', 33, 'auth_token', '637606297128e1616e3a07e9ad864bf7511aba32461442a28507af13cf83d7ba', '[\"*\"]', NULL, NULL, '2026-08-28 15:03:18', '2026-08-28 15:03:18'),
(76, 'App\\Models\\Admin', 33, 'auth_token', 'd6d29d85dc9d0ef46650d9b306d189e29dd2de17552cf47040f9f2b5adaf56de', '[\"*\"]', NULL, NULL, '2026-08-28 15:44:25', '2026-08-28 15:44:25'),
(77, 'App\\Models\\Admin', 33, 'auth_token', '2491ac1d6c10b1d5f580c7f1a09318e07181388ba3fbcf71a08f94bae32ad7cc', '[\"*\"]', NULL, NULL, '2026-08-29 14:02:22', '2026-08-29 14:02:22'),
(78, 'App\\Models\\Admin', 33, 'auth_token', 'edec5f7471dae4fd9dd184aab991a59616586269840b9733dcaf4a7f67ed6a25', '[\"*\"]', NULL, NULL, '2026-08-29 14:16:24', '2026-08-29 14:16:24'),
(79, 'App\\Models\\Admin', 33, 'auth_token', 'd3a8aa6bbd36c175fb2158048a14180f4a6962f017b8f76d5cc08b5f2721f41c', '[\"*\"]', NULL, NULL, '2026-08-31 05:37:56', '2026-08-31 05:37:56'),
(80, 'App\\Models\\Admin', 33, 'auth_token', '258d1a71c9fef1f219b639747e0a5963eda8ac72398bbc3791d346105276ec25', '[\"*\"]', NULL, NULL, '2026-08-31 05:40:36', '2026-08-31 05:40:36'),
(81, 'App\\Models\\Admin', 33, 'auth_token', '86d68ecf3e73fc7092004aaf32c7f7a2c47bc12165d75304b2722177c67056f3', '[\"*\"]', NULL, NULL, '2026-08-31 05:41:31', '2026-08-31 05:41:31'),
(82, 'App\\Models\\Admin', 33, 'auth_token', '0d47e14df4b8d710fe599fdd7dab2db8901bf66f3a317d6529fdc34c27565665', '[\"*\"]', NULL, NULL, '2026-08-31 05:42:59', '2026-08-31 05:42:59'),
(83, 'App\\Models\\Admin', 33, 'auth_token', '5aeeb1a542d494657a1688db0b2887ee28e685a73d3e6cb47218cdab46e9bb22', '[\"*\"]', NULL, NULL, '2026-08-31 05:44:24', '2026-08-31 05:44:24'),
(84, 'App\\Models\\Admin', 33, 'auth_token', 'ed72d4b95c00e9703f6fafda23f542e1d779e6f4ac85eb2661dcce7447bd1e05', '[\"*\"]', NULL, NULL, '2026-08-31 06:17:41', '2026-08-31 06:17:41'),
(85, 'App\\Models\\Admin', 33, 'auth_token', '1d2626225b508effcba510bd8dc87aac509866feb84194d61c080fe2d21c8136', '[\"*\"]', NULL, NULL, '2026-08-31 15:22:00', '2026-08-31 15:22:00'),
(86, 'App\\Models\\Admin', 33, 'auth_token', 'bcb147c4c501a6710b97252e2a5e1025ca001eb6a13904e2debcbeed93c0722f', '[\"*\"]', NULL, NULL, '2026-08-31 15:22:00', '2026-08-31 15:22:00'),
(87, 'App\\Models\\Admin', 33, 'auth_token', '1c9d843c4680fd8b6a33c01de1a661fe8aeefa4d06dbb551b668493c8198f761', '[\"*\"]', NULL, NULL, '2026-08-31 15:29:54', '2026-08-31 15:29:54'),
(88, 'App\\Models\\Admin', 33, 'auth_token', '342c101775faadfe845d5ffb8be45ee63f341ad6acf7acfbd0591ce6160f7dd8', '[\"*\"]', NULL, NULL, '2026-09-01 12:04:07', '2026-09-01 12:04:07'),
(89, 'App\\Models\\Admin', 33, 'auth_token', '6f2ca707c41ad59bd2ee1bb1512eb35681e3f2d8bed13a28d768b72f29433a4a', '[\"*\"]', NULL, NULL, '2026-09-01 12:49:54', '2026-09-01 12:49:54'),
(90, 'App\\Models\\Admin', 33, 'auth_token', '3882b72b711bdbd18558a49330988f84481bd2de4a3e878bcc81a4cbf538da8c', '[\"*\"]', NULL, NULL, '2026-09-01 12:50:38', '2026-09-01 12:50:38'),
(91, 'App\\Models\\Admin', 33, 'auth_token', '1c7bdc60f2cdd18882938e8259a53d6ed4a4d15ec74c765068e5c72859f40a51', '[\"*\"]', NULL, NULL, '2026-09-01 12:56:07', '2026-09-01 12:56:07'),
(92, 'App\\Models\\Admin', 33, 'auth_token', '8d324e346df1db2e19a62fb3d63d310c4894a7d29d195e60b29404dd1bfe1b21', '[\"*\"]', NULL, NULL, '2026-09-01 12:58:09', '2026-09-01 12:58:09'),
(93, 'App\\Models\\Admin', 33, 'auth_token', '362407e3b5467c94f2b91830227bc2c6a84b1bb7ccb2d90740ba13e17130a843', '[\"*\"]', NULL, NULL, '2026-09-01 13:43:33', '2026-09-01 13:43:33'),
(94, 'App\\Models\\Admin', 33, 'auth_token', 'e87de3b9440d9dbaca5f0538f32ab6fe5875aac5ac587efba957881538f327e7', '[\"*\"]', NULL, NULL, '2026-09-01 13:43:33', '2026-09-01 13:43:33'),
(95, 'App\\Models\\Admin', 1, 'auth_token', 'e5f75e3c92c6d12d82fab19843d41afcc816796fd43d1f8bb75e347f3d5160ac', '[\"*\"]', NULL, NULL, '2026-09-02 07:17:53', '2026-09-02 07:17:53'),
(96, 'App\\Models\\Admin', 1, 'auth_token', '584787cef2d74179ede14ad8841150e1ab4d4f6e79ead60a964765ebf295f7ad', '[\"*\"]', NULL, NULL, '2026-09-02 07:38:39', '2026-09-02 07:38:39'),
(97, 'App\\Models\\Admin', 1, 'auth_token', '0ffac359e1849ed35f0f728a38dfdcbb4419d9a9544284665e1842982f3934da', '[\"*\"]', NULL, NULL, '2026-09-02 07:38:46', '2026-09-02 07:38:46'),
(98, 'App\\Models\\Admin', 1, 'auth_token', 'e115f0b312e0ebaedee1fb26133e9784aab9c9a041f04719e4809cf7cce61783', '[\"*\"]', NULL, NULL, '2026-09-02 11:39:44', '2026-09-02 11:39:44'),
(99, 'App\\Models\\Admin', 33, 'auth_token', 'e75e9a0ba32ec5f749383a16ebac48d509be43966ef65320722528d57c8d34f5', '[\"*\"]', NULL, NULL, '2026-09-02 11:40:44', '2026-09-02 11:40:44'),
(100, 'App\\Models\\Admin', 1, 'auth_token', '066c948781165e5398b5f8c770afde14452664fab568a364329fcc636d8f4937', '[\"*\"]', NULL, NULL, '2026-09-02 12:31:57', '2026-09-02 12:31:57'),
(101, 'App\\Models\\Admin', 1, 'auth_token', '9528f34f27e23a1fb594fd6dcdad38f222abd38883d6b47dcfd7c10e7c16d638', '[\"*\"]', NULL, NULL, '2026-09-02 16:36:06', '2026-09-02 16:36:06'),
(102, 'App\\Models\\Admin', 1, 'auth_token', '1843688b1e3af339958e0611c65bfe78c2501c9c707f1878c196a1e5c1e107f4', '[\"*\"]', NULL, NULL, '2026-09-02 16:36:19', '2026-09-02 16:36:19'),
(103, 'App\\Models\\Admin', 33, 'auth_token', '220f0433f1d802d073a09a9d2c2763f7b23f66edc694e4b608992b50de2708c1', '[\"*\"]', NULL, NULL, '2026-09-02 16:39:44', '2026-09-02 16:39:44'),
(104, 'App\\Models\\Admin', 33, 'auth_token', 'f984664d7be6f56936c6c7f78c5b152d08efe36b353c8245032d342335757f32', '[\"*\"]', NULL, NULL, '2026-09-03 06:27:40', '2026-09-03 06:27:40'),
(105, 'App\\Models\\Admin', 33, 'auth_token', 'b1443e216ef9e555c83c17bd676f6210ffa2ea544b1b746b25b3866caf27cfdf', '[\"*\"]', NULL, NULL, '2026-09-03 06:37:28', '2026-09-03 06:37:28'),
(106, 'App\\Models\\Admin', 33, 'auth_token', '751d4eba422e43b2398b85af8d4ea4b6452aa9f1e2c9ffa3eebcd0b2fe3c6fe0', '[\"*\"]', NULL, NULL, '2026-09-03 06:39:30', '2026-09-03 06:39:30'),
(107, 'App\\Models\\Admin', 33, 'auth_token', 'b9639bf02f775f34cce1e2116a3adeb04bb4775bd0c17f764488e20db0f83133', '[\"*\"]', NULL, NULL, '2026-09-03 06:45:35', '2026-09-03 06:45:35'),
(108, 'App\\Models\\Admin', 33, 'auth_token', 'd39d984570b129422a0be0dcff4f936a9b09b8dea3130199c74e1f39c3e3273c', '[\"*\"]', NULL, NULL, '2026-09-03 06:48:54', '2026-09-03 06:48:54'),
(109, 'App\\Models\\Admin', 33, 'auth_token', '755c9d6f94156e242be4f2f285876d8b6e239ae0c4628494ce22d2a86bd1ef26', '[\"*\"]', NULL, NULL, '2026-09-03 06:52:03', '2026-09-03 06:52:03'),
(110, 'App\\Models\\Admin', 33, 'auth_token', 'f5f51091182bb02549b6f4342d857036c2b7a1f3020d778f0e639a7dc7df4ca2', '[\"*\"]', NULL, NULL, '2026-09-03 07:30:39', '2026-09-03 07:30:39'),
(111, 'App\\Models\\Admin', 33, 'auth_token', '7e4996342d924f84b6f840d1f146bcdd2427bcf6af54e488685b887d32d73bc0', '[\"*\"]', NULL, NULL, '2026-09-03 08:13:39', '2026-09-03 08:13:39'),
(112, 'App\\Models\\Admin', 33, 'auth_token', '562b8873f0baaceec14397b491bc1ad6f0629824a3d4de0ada20a6055e2212e5', '[\"*\"]', NULL, NULL, '2026-09-03 08:44:28', '2026-09-03 08:44:28'),
(113, 'App\\Models\\Admin', 33, 'auth_token', 'e5ae7400fd955ac6b4223a0db31a6395911f8723f5e39a21d135d70510796254', '[\"*\"]', NULL, NULL, '2026-09-04 09:02:30', '2026-09-04 09:02:30'),
(114, 'App\\Models\\Admin', 33, 'auth_token', '38c7a00cb9e5b659f8b7e353aaa2fb1141afbaa5368ac44a404beaefb9dcec1a', '[\"*\"]', NULL, NULL, '2026-09-04 09:22:35', '2026-09-04 09:22:35'),
(115, 'App\\Models\\Admin', 33, 'auth_token', '6b80b8a592d8f03763c4b99000a2ffb2a3ce9d2e22d36a972c88e4f37de0f3ec', '[\"*\"]', NULL, NULL, '2026-09-04 09:35:38', '2026-09-04 09:35:38'),
(116, 'App\\Models\\Admin', 33, 'auth_token', '5ab86d8a958ba3ee1a14abf650e1d0bd7c0fcc0ea2debae7c7d6abafd924e6d5', '[\"*\"]', NULL, NULL, '2026-09-04 09:47:21', '2026-09-04 09:47:21'),
(117, 'App\\Models\\Admin', 33, 'auth_token', '5b74eae5fa2b9d0273386ca4f3df4aad490f1020a3ed6b3fd0fe40d603042942', '[\"*\"]', NULL, NULL, '2026-09-04 10:15:06', '2026-09-04 10:15:06'),
(118, 'App\\Models\\Admin', 33, 'auth_token', '2af119f408b9cae7f67a33acd8eec6560bbeff0aad2330be9c3280f95e27872e', '[\"*\"]', NULL, NULL, '2026-09-04 11:43:50', '2026-09-04 11:43:50'),
(119, 'App\\Models\\Admin', 33, 'auth_token', '7341a9f6d2245b02b6a08831bf11316dd13b64e863361165b0216a4b1132ed46', '[\"*\"]', NULL, NULL, '2026-09-04 12:53:20', '2026-09-04 12:53:20'),
(120, 'App\\Models\\Admin', 33, 'auth_token', '70eda9f1f5fdf5cc314429642653919775372eb7ef5b5c7ce0d3cf1d528d2ef7', '[\"*\"]', NULL, NULL, '2026-09-04 13:06:16', '2026-09-04 13:06:16'),
(121, 'App\\Models\\Admin', 33, 'auth_token', '4c28e7dc006551cf23c67a0accc5d9267da960fc5b21cdb786e620ddc2a1b08f', '[\"*\"]', NULL, NULL, '2026-09-04 13:12:16', '2026-09-04 13:12:16'),
(122, 'App\\Models\\Admin', 1, 'auth_token', '573f4d22ea1473c97eba67dd788dc3f79e5056d74f7b1de790c5402daacf6bfb', '[\"*\"]', NULL, NULL, '2026-09-04 13:21:44', '2026-09-04 13:21:44'),
(123, 'App\\Models\\Admin', 33, 'auth_token', 'a76a3ba2dc815305c5f9d64dd4400c38ad92cc4163ccc01f454280e3206ed367', '[\"*\"]', NULL, NULL, '2026-09-04 13:23:27', '2026-09-04 13:23:27'),
(124, 'App\\Models\\Admin', 33, 'auth_token', 'e63bb30cf978465376390e96f436a9be203d1847cfdff74b13dfedd48d4bc601', '[\"*\"]', NULL, NULL, '2026-09-04 13:26:54', '2026-09-04 13:26:54'),
(125, 'App\\Models\\Admin', 33, 'auth_token', '99c40ad1d6c894d212a8e6089370934aed08bc714cfae1749872d96ba1159a1b', '[\"*\"]', NULL, NULL, '2026-09-04 13:42:12', '2026-09-04 13:42:12'),
(126, 'App\\Models\\Admin', 33, 'auth_token', '0efab3a3aa8a8080c601a5b821ec5190e58530e8b844da0b77652cd73baaa828', '[\"*\"]', NULL, NULL, '2026-09-04 14:13:33', '2026-09-04 14:13:33'),
(127, 'App\\Models\\Admin', 33, 'auth_token', '5fc170c8cbdf3ec400bc775ee35dc2ee7418b7d3b30d77983bfee91763144d19', '[\"*\"]', NULL, NULL, '2026-09-04 14:15:04', '2026-09-04 14:15:04'),
(128, 'App\\Models\\Admin', 33, 'auth_token', '5f02f43f423d542710dd07594b43613bb4277f29eaa9dd5bee63bcfbbc2b8014', '[\"*\"]', NULL, NULL, '2026-09-04 14:15:05', '2026-09-04 14:15:05'),
(129, 'App\\Models\\Admin', 33, 'auth_token', '479cac6456177f3846108eb94f87ae0067040c98b8b5180a1b724b64b4125270', '[\"*\"]', NULL, NULL, '2026-09-04 14:18:09', '2026-09-04 14:18:09'),
(130, 'App\\Models\\Admin', 33, 'auth_token', '39c4fb0d7d6e4fd1d2d743744535b676716a012ee9f986909c251c786524c392', '[\"*\"]', NULL, NULL, '2026-09-04 14:18:48', '2026-09-04 14:18:48'),
(131, 'App\\Models\\Admin', 33, 'auth_token', '0253967e50f998ba966350bc5089622dd46188cc6848bcb6cecba2eee2474fe1', '[\"*\"]', NULL, NULL, '2026-09-05 06:42:11', '2026-09-05 06:42:11'),
(132, 'App\\Models\\Admin', 33, 'auth_token', '84f08e12773b3b4ee5a8a3dc021edad7306d6d248c2127f0a2da4ba51bb6b547', '[\"*\"]', NULL, NULL, '2026-09-05 06:45:14', '2026-09-05 06:45:14'),
(133, 'App\\Models\\Admin', 33, 'auth_token', '7c091423e608ae204e1c7d02b185d4c9e64965c3259f171e72028840f0f01e56', '[\"*\"]', NULL, NULL, '2026-09-05 07:31:57', '2026-09-05 07:31:57'),
(134, 'App\\Models\\Admin', 33, 'auth_token', '01bc7baaa828c13477069a63e47fefe024685c04b8e00fe020f1dafc3c9ec688', '[\"*\"]', NULL, NULL, '2026-09-05 14:58:33', '2026-09-05 14:58:33'),
(135, 'App\\Models\\Admin', 33, 'auth_token', '033a4b0b6258cca87fcbbd48791876ed87d2e50d70cdbdbd98a6210855dde770', '[\"*\"]', NULL, NULL, '2026-09-05 18:50:25', '2026-09-05 18:50:25'),
(136, 'App\\Models\\Admin', 33, 'auth_token', '5d02c9ff26a6f2c060e6f11638e3eb2a9abbd8f498e407c0d1c7fb6afbffdc73', '[\"*\"]', NULL, NULL, '2026-09-06 06:47:42', '2026-09-06 06:47:42'),
(137, 'App\\Models\\Admin', 33, 'auth_token', '48aca38a5132b184f81a0deb5ae588c6bbe42d117f3b48c0e9d79adb62a145c9', '[\"*\"]', NULL, NULL, '2026-09-06 06:48:49', '2026-09-06 06:48:49'),
(138, 'App\\Models\\Admin', 33, 'auth_token', '83811b4f958cfdb9c1efb80fcc361dae4ff03fba47fcadd507b8da75915fe708', '[\"*\"]', NULL, NULL, '2026-09-06 07:03:02', '2026-09-06 07:03:02'),
(139, 'App\\Models\\Admin', 33, 'auth_token', '133830a7d2794857969cf1f8d817151add934b17c593646b277df3ee1c4e26e8', '[\"*\"]', NULL, NULL, '2026-09-06 07:06:05', '2026-09-06 07:06:05'),
(140, 'App\\Models\\Admin', 33, 'auth_token', '5e54fe4361a2a52f723b268fe5d2c66b8566ef440538ec3acb863427e870d5fe', '[\"*\"]', NULL, NULL, '2026-09-06 07:17:48', '2026-09-06 07:17:48'),
(141, 'App\\Models\\Admin', 33, 'auth_token', '4137f3059fecb9e1c22cd0ea2225c6e46eb8d992a9fb63befa4beaffd9dcd9ab', '[\"*\"]', NULL, NULL, '2026-09-06 07:23:21', '2026-09-06 07:23:21'),
(142, 'App\\Models\\Admin', 33, 'auth_token', '0ae8e3abb53b00a2868f8dd2a8dbce80fd273ee5ca1d0c089c60c92af2aaf838', '[\"*\"]', NULL, NULL, '2026-09-06 07:24:32', '2026-09-06 07:24:32'),
(143, 'App\\Models\\Admin', 33, 'auth_token', '407de6506ac80b0386061cdf89223974d67747f03b8fb35c6e1b38c25fb9ade0', '[\"*\"]', NULL, NULL, '2026-09-06 08:03:51', '2026-09-06 08:03:51'),
(144, 'App\\Models\\Admin', 33, 'auth_token', 'b44d0e7333703f6fa5914aa2a5bfecf3c4b7381b5822a26cf4ca1a636efef158', '[\"*\"]', NULL, NULL, '2026-09-06 08:14:54', '2026-09-06 08:14:54'),
(145, 'App\\Models\\Admin', 33, 'auth_token', '8c530c01a88d6ff0c6890d249d6b6e6b00547cde760ca2b2c9c4039823e53444', '[\"*\"]', NULL, NULL, '2026-09-06 08:23:20', '2026-09-06 08:23:20'),
(146, 'App\\Models\\Admin', 33, 'auth_token', '4e768b019bc9af7206137731b32b3d5d0e9990e4bb8e83fdfd65d3c29a95b5c7', '[\"*\"]', NULL, NULL, '2026-09-06 08:27:15', '2026-09-06 08:27:15'),
(147, 'App\\Models\\Admin', 1, 'auth_token', '192a858be8a2542ad8b78b10e8932391747f066316e33cc910917dee04bbd9c8', '[\"*\"]', NULL, NULL, '2026-09-06 08:46:36', '2026-09-06 08:46:36'),
(148, 'App\\Models\\Admin', 33, 'auth_token', '6d554641d218d0397045d2778f501b95b87d1a4f40f5d438f9a9a26ffda5fefc', '[\"*\"]', NULL, NULL, '2026-09-06 09:26:35', '2026-09-06 09:26:35'),
(149, 'App\\Models\\Admin', 33, 'auth_token', '8d064fbf90feaaa0ddbc1da37e16474974faae8c3599db6f78771deafb4b9730', '[\"*\"]', NULL, NULL, '2026-09-07 13:02:53', '2026-09-07 13:02:53'),
(150, 'App\\Models\\Admin', 33, 'auth_token', 'd56ebdee7ba90c134d74e217d95f7c5b14b3623c5f34a05df2e2f00575454db9', '[\"*\"]', NULL, NULL, '2026-09-07 13:25:42', '2026-09-07 13:25:42'),
(151, 'App\\Models\\Admin', 33, 'auth_token', '710faf623363f4c918946fe1794b36b48ddb1e4ff36d669f1f9ce3e5c5415622', '[\"*\"]', NULL, NULL, '2026-09-07 13:39:47', '2026-09-07 13:39:47'),
(152, 'App\\Models\\Admin', 33, 'auth_token', '758f6282caee0f45ee426a1f7749c4707143bd09447f19ccdb47855d40ce4946', '[\"*\"]', NULL, NULL, '2026-09-07 13:51:51', '2026-09-07 13:51:51'),
(153, 'App\\Models\\Admin', 33, 'auth_token', '357d5f8685e26307a4e3533df30d34c71a69fe1b1ae9392a451a563225024933', '[\"*\"]', NULL, NULL, '2026-09-07 16:09:24', '2026-09-07 16:09:24'),
(154, 'App\\Models\\Admin', 33, 'auth_token', 'b305edebd7f7e740a337d37355daeaea31f1373c19a78c938f9c7ad186ae51a8', '[\"*\"]', NULL, NULL, '2026-09-09 08:13:02', '2026-09-09 08:13:02'),
(155, 'App\\Models\\Admin', 33, 'auth_token', '72f04fe17e3d41b98785a9cae5beefebc3a428eca4f344d7a97cfc40c5edd42b', '[\"*\"]', NULL, NULL, '2026-09-12 02:43:20', '2026-09-12 02:43:20'),
(156, 'App\\Models\\Admin', 33, 'auth_token', '96ccf65518a3acc403846b719282228b6e842194598b9e1e604dcc295d612e4b', '[\"*\"]', NULL, NULL, '2026-09-14 19:24:04', '2026-09-14 19:24:04'),
(157, 'App\\Models\\Admin', 1, 'auth_token', '1e3d28081946fbb649eac085c26c9a6362cc43223939a47b69c84ccfc9d817f3', '[\"*\"]', NULL, NULL, '2026-09-17 11:19:23', '2026-09-17 11:19:23'),
(158, 'App\\Models\\Admin', 33, 'auth_token', '80e805e6fd66f53e9cfd19a4cd06b555e959d8b72cb8ddd39191ad6aa1a5edd2', '[\"*\"]', NULL, NULL, '2026-09-17 11:19:57', '2026-09-17 11:19:57'),
(159, 'App\\Models\\Admin', 33, 'auth_token', '571b4aa9f73e45238f7d393abfe4347494c2f20f3e8ea8949f076f7850f5b77c', '[\"*\"]', NULL, NULL, '2026-09-23 06:37:57', '2026-09-23 06:37:57'),
(160, 'App\\Models\\Admin', 1, 'auth_token', '6adda52060222df97ea684d36854ceda203c7bb508bf5ff92bc3e7431579ef3c', '[\"*\"]', NULL, NULL, '2026-09-23 06:41:53', '2026-09-23 06:41:53'),
(161, 'App\\Models\\Admin', 32, 'auth_token', 'cf3851ab40c3e5a1b67553ddcf0fff452d70d783d263652c20f40890430d033c', '[\"*\"]', NULL, NULL, '2026-09-23 06:50:35', '2026-09-23 06:50:35'),
(162, 'App\\Models\\Admin', 1, 'auth_token', '7cb4bfc192ed7a37242e301e6b606f05dc8bd0c80ac82deee29c1d95a6dba69f', '[\"*\"]', NULL, NULL, '2026-09-23 06:51:07', '2026-09-23 06:51:07'),
(163, 'App\\Models\\Admin', 32, 'auth_token', 'dc19d3f794b6ba792b4b99a7669d7eb1260625bf55498ca2b7e3ea5025be0af6', '[\"*\"]', NULL, NULL, '2026-09-23 06:53:05', '2026-09-23 06:53:05'),
(164, 'App\\Models\\Admin', 32, 'auth_token', '28dc07495a14a2995dfd527b2d54f25a9a189402d0707b67770477bf95003aeb', '[\"*\"]', NULL, NULL, '2026-09-23 06:53:58', '2026-09-23 06:53:58'),
(165, 'App\\Models\\Admin', 32, 'auth_token', '1b0529c9d562b6361139ec52da3f24e72b40e9efb607b455fae8f15214710b87', '[\"*\"]', NULL, NULL, '2026-09-23 06:59:06', '2026-09-23 06:59:06'),
(166, 'App\\Models\\Admin', 33, 'auth_token', '235b04cfc13142e5359ef4c33ee9cea91600eb79c8a379181542428e7bb32363', '[\"*\"]', NULL, NULL, '2026-09-23 07:33:48', '2026-09-23 07:33:48'),
(167, 'App\\Models\\Admin', 32, 'auth_token', 'e57edbf3ad826b2b34cd41b85a36a07d5abec6b8819f71f62285cd51d1f5eee4', '[\"*\"]', NULL, NULL, '2026-09-23 07:34:36', '2026-09-23 07:34:36'),
(168, 'App\\Models\\Admin', 32, 'auth_token', 'f4481b157ca5135c97ee0acd1078e5361e25969c1bd36a4fe52660cec6db2474', '[\"*\"]', NULL, NULL, '2026-09-23 07:53:30', '2026-09-23 07:53:30'),
(169, 'App\\Models\\Admin', 32, 'auth_token', '32f952a89e3fc067ec46ccbe420797587540451c714f01f38678b1f78775ac85', '[\"*\"]', NULL, NULL, '2026-09-23 07:54:54', '2026-09-23 07:54:54'),
(170, 'App\\Models\\Admin', 32, 'auth_token', 'd673527519be41a4c5dd0bf0bfed4e55813d2fc15d5debc55c40a229fea166fb', '[\"*\"]', NULL, NULL, '2026-09-23 08:03:53', '2026-09-23 08:03:53'),
(171, 'App\\Models\\Admin', 32, 'auth_token', 'a1835a4f98eddd1eafed32ae8c0f9c330487be39699474718294f7e3bc480da2', '[\"*\"]', NULL, NULL, '2026-09-23 08:32:41', '2026-09-23 08:32:41'),
(172, 'App\\Models\\Admin', 33, 'auth_token', 'db5c8891ff8c91add9f8595e9295d998b82d57e8e3b2e93bf4a2870d48379a2b', '[\"*\"]', NULL, NULL, '2026-09-23 08:34:29', '2026-09-23 08:34:29'),
(173, 'App\\Models\\Admin', 32, 'auth_token', '07784b8e15c20d5855764e487c60038053eb60e982e16d2df2ac25d6d7640a00', '[\"*\"]', NULL, NULL, '2026-09-23 08:36:08', '2026-09-23 08:36:08'),
(174, 'App\\Models\\Admin', 32, 'auth_token', 'a5f922a2cf709cd4b163b40f1abeda2a8b6bee22e8d7802c3fb7fdf9e6e35576', '[\"*\"]', NULL, NULL, '2026-09-24 06:52:37', '2026-09-24 06:52:37'),
(175, 'App\\Models\\Admin', 32, 'auth_token', 'e92df704592c0e6c6aa02d0f7f31ee1776fb1fd593479644f1acf0590328711b', '[\"*\"]', NULL, NULL, '2026-09-25 07:31:43', '2026-09-25 07:31:43'),
(176, 'App\\Models\\Admin', 32, 'auth_token', 'cfc595e72851929b8f59b7be5f7d3155a922f92eb08332bac3ed52b97828c97a', '[\"*\"]', NULL, NULL, '2026-09-25 08:08:14', '2026-09-25 08:08:14'),
(177, 'App\\Models\\Admin', 33, 'auth_token', '5d07874a690257624f9dd23cf5c255c501038f407774ffea66ac4648f5d2077c', '[\"*\"]', NULL, NULL, '2026-09-25 08:16:17', '2026-09-25 08:16:17'),
(178, 'App\\Models\\Admin', 1, 'auth_token', '9c82d7540f33b55301db03b7a98c6e6c187f675a37fb33a5815c2d867384c464', '[\"*\"]', NULL, NULL, '2026-09-25 08:16:42', '2026-09-25 08:16:42'),
(179, 'App\\Models\\Admin', 32, 'auth_token', 'ea92270144aaa51b95e6be290c5ffebc14b2e841422a45c5dd2bf2cbd8e1662a', '[\"*\"]', NULL, NULL, '2026-09-25 08:18:12', '2026-09-25 08:18:12'),
(180, 'App\\Models\\Admin', 32, 'auth_token', 'df6f558c26e12bfdd713048fd2b737600d251ab02b6001941f7715ba4bec77d9', '[\"*\"]', NULL, NULL, '2026-09-25 08:27:21', '2026-09-25 08:27:21'),
(181, 'App\\Models\\Admin', 33, 'auth_token', '031925846cb059e15cfe687c800dd7b868d6b9bb4d09dd9cfbeac000cf3cb686', '[\"*\"]', NULL, NULL, '2026-09-25 08:30:32', '2026-09-25 08:30:32'),
(182, 'App\\Models\\Admin', 32, 'auth_token', '0f09b20def27cd116e4644aefa649c88731cbc6191247f84fdede9e000e22d64', '[\"*\"]', NULL, NULL, '2026-09-25 08:30:53', '2026-09-25 08:30:53'),
(183, 'App\\Models\\Admin', 32, 'auth_token', '7ad70ce0ffe9d8e6725ae8ac4ffde08317fb095c33ee4382d0b6a5507cc581aa', '[\"*\"]', NULL, NULL, '2026-09-25 08:34:10', '2026-09-25 08:34:10'),
(184, 'App\\Models\\Admin', 33, 'auth_token', '3f4bccbdccfd955d83c14e5aa8c9bb8d1a0291a8f9ff7683aa7198b2342b67d3', '[\"*\"]', NULL, NULL, '2026-09-25 08:42:38', '2026-09-25 08:42:38'),
(185, 'App\\Models\\Admin', 32, 'auth_token', 'fc1647a60babf150ba3450b4d699fa0bfa5eb27c9b4f383da3e2c3f648d91c17', '[\"*\"]', NULL, NULL, '2026-09-25 08:43:00', '2026-09-25 08:43:00'),
(186, 'App\\Models\\Admin', 33, 'auth_token', 'a3755423974cc4e641a5dc2755be588daf3c88103548ac1df7d2587b37734826', '[\"*\"]', NULL, NULL, '2026-09-25 08:47:30', '2026-09-25 08:47:30'),
(187, 'App\\Models\\Admin', 33, 'auth_token', '0552f58f4d0cd5987a8dd05bd1333411e9befa858c40ea346ebe38800232dedf', '[\"*\"]', NULL, NULL, '2026-09-25 09:00:33', '2026-09-25 09:00:33'),
(188, 'App\\Models\\Admin', 32, 'auth_token', 'b6e4423d1875871d17e9fea1ab41b7a72af1b6f50ee523730bd9c83df6e79c01', '[\"*\"]', NULL, NULL, '2026-09-25 09:00:52', '2026-09-25 09:00:52'),
(189, 'App\\Models\\Admin', 33, 'auth_token', '427870cabef8bc53093071e11329e0c43c16dd899cace555d60fb7247f2e158e', '[\"*\"]', NULL, NULL, '2026-09-25 09:10:54', '2026-09-25 09:10:54'),
(190, 'App\\Models\\Admin', 32, 'auth_token', 'a2f18fc445b3f51cad3f122b1ff7d1d9511944da1dd3f088378af9389e8a55da', '[\"*\"]', NULL, NULL, '2026-09-25 12:27:29', '2026-09-25 12:27:29'),
(191, 'App\\Models\\Admin', 32, 'auth_token', 'c052ac43c95bcaab4b2b3b2d3c185ca07a8b9e8759139eb22764dfb1f6aa60af', '[\"*\"]', NULL, NULL, '2026-09-25 12:37:29', '2026-09-25 12:37:29'),
(192, 'App\\Models\\Admin', 33, 'auth_token', '4a910ff5dc9dea6f354ee10d74cdcfe74ff8e95c6a2db922cd2228ab399c3046', '[\"*\"]', NULL, NULL, '2026-09-26 07:56:55', '2026-09-26 07:56:55'),
(193, 'App\\Models\\Admin', 32, 'auth_token', 'def798211e5356e4ea7711e5f5bf2bac0452c32175cae14eacc3cc4612632bab', '[\"*\"]', NULL, NULL, '2026-09-26 07:59:54', '2026-09-26 07:59:54'),
(194, 'App\\Models\\Admin', 32, 'auth_token', 'db9a1abe6dfdceda2978d15823ef46bfb1828fec054b2855d8ff3dcc887131b4', '[\"*\"]', NULL, NULL, '2026-09-26 08:10:50', '2026-09-26 08:10:50'),
(195, 'App\\Models\\Admin', 33, 'auth_token', 'dd797d686ca4d228e6e04d9ef02871929a64917604cac2ca8507871753ad54dd', '[\"*\"]', NULL, NULL, '2026-09-26 09:14:05', '2026-09-26 09:14:05'),
(196, 'App\\Models\\Admin', 32, 'auth_token', 'f1b429ec57e87781270b699e1b53ede25348e181070beae7374eb17340d6481d', '[\"*\"]', NULL, NULL, '2026-09-28 00:54:36', '2026-09-28 00:54:36'),
(197, 'App\\Models\\Admin', 33, 'auth_token', '75666150922f69b28fced8334b682f92d3013359adb55ec9bf0edc3f76cc912b', '[\"*\"]', NULL, NULL, '2026-10-03 10:14:37', '2026-10-03 10:14:37'),
(198, 'App\\Models\\Admin', 33, 'auth_token', 'ddf9442bebed711c18f8ca1a177e63c0071c1623644b09b7f43a3ce4ad571ad7', '[\"*\"]', NULL, NULL, '2026-10-03 10:22:48', '2026-10-03 10:22:48'),
(199, 'App\\Models\\Admin', 33, 'auth_token', 'd9fabd31f56802d143a66f5c5897aab4b61882ec0ee64a1b5c8daf86af155cb1', '[\"*\"]', NULL, NULL, '2026-10-03 10:23:26', '2026-10-03 10:23:26'),
(200, 'App\\Models\\Admin', 33, 'auth_token', 'a232e843ff0b3427074d4899bd61a1c397fcefd16366244e1706f3a208dee6d1', '[\"*\"]', NULL, NULL, '2026-10-03 10:24:11', '2026-10-03 10:24:11'),
(201, 'App\\Models\\Admin', 33, 'auth_token', 'be2803bb7662a706819d6d0ef04ba64944a1e54221dcec451eea899e100e38be', '[\"*\"]', NULL, NULL, '2026-10-03 10:25:20', '2026-10-03 10:25:20'),
(202, 'App\\Models\\Admin', 33, 'auth_token', '53cf523485e2347ca11f6affea4dad4915389f1a508e3294bbd04aabb3e51522', '[\"*\"]', NULL, NULL, '2026-10-03 10:26:03', '2026-10-03 10:26:03'),
(203, 'App\\Models\\Admin', 33, 'auth_token', 'b0d8883ad76a94074b4db79bb800cc64ff449b91eaaf08084aafc17a1e01c806', '[\"*\"]', NULL, NULL, '2026-10-03 10:28:37', '2026-10-03 10:28:37'),
(204, 'App\\Models\\Admin', 33, 'auth_token', '809b3d91b99652ebaafdd358fd9064d676d001349707ebf87528a944fc9f84fc', '[\"*\"]', NULL, NULL, '2026-10-03 10:30:47', '2026-10-03 10:30:47'),
(205, 'App\\Models\\Admin', 33, 'auth_token', '728c11434956a14eeea30d68513ea40d0ca20133051ce55d25a7f48a11855d4d', '[\"*\"]', NULL, NULL, '2026-10-03 10:31:12', '2026-10-03 10:31:12'),
(206, 'App\\Models\\Admin', 33, 'auth_token', '517fdb3d6b0cdcb58fda4bbadbd3d0db76eae91b3f3cb679f2f8cd57c9f1e445', '[\"*\"]', NULL, NULL, '2026-10-03 10:32:48', '2026-10-03 10:32:48'),
(207, 'App\\Models\\Admin', 40, 'auth_token', '359e395267b27913842e0fae8bf6b3e7038d3e89ebb45ad38d552d3098d3bdaf', '[\"*\"]', NULL, NULL, '2026-10-03 10:33:03', '2026-10-03 10:33:03'),
(208, 'App\\Models\\Admin', 41, 'auth_token', 'c2ed96bdc3059d18c71bd2817fe8cc60e95789c9626abbe1fe393a529f8b9bbd', '[\"*\"]', NULL, NULL, '2026-10-03 10:33:17', '2026-10-03 10:33:17'),
(209, 'App\\Models\\Admin', 33, 'auth_token', '826100825b81bc12212e146cf1b52a1729296dc9436dd2683d4d73243da075f8', '[\"*\"]', NULL, NULL, '2026-10-03 10:33:38', '2026-10-03 10:33:38'),
(210, 'App\\Models\\Admin', 39, 'auth_token', '23389058634bc1c2355363cfc6f58d654a67afba838cd72934e6a37e5728385b', '[\"*\"]', NULL, NULL, '2026-10-03 10:34:23', '2026-10-03 10:34:23'),
(211, 'App\\Models\\Admin', 39, 'auth_token', '26912caed256edf287dc47c22a5ec2d114a507fb343cd2f5c1939aa11d59ee1e', '[\"*\"]', NULL, NULL, '2026-10-03 10:37:48', '2026-10-03 10:37:48'),
(212, 'App\\Models\\Admin', 33, 'auth_token', '92bf7f8b53b4d036a1247d61db8cd96fefda34e20d867f563e0da3073709dca2', '[\"*\"]', NULL, NULL, '2026-10-03 10:38:33', '2026-10-03 10:38:33'),
(213, 'App\\Models\\Admin', 40, 'auth_token', '619ab810775a2c5d452cd84e8b2ce1a549531badbdd89f6b62008bb3b3093e20', '[\"*\"]', NULL, NULL, '2026-10-03 10:40:12', '2026-10-03 10:40:12'),
(214, 'App\\Models\\Admin', 39, 'auth_token', 'f4e2a1567fd9f01f9d253537a2dc7726c3e6dcee8c35f69cc16c8d2a25c75379', '[\"*\"]', NULL, NULL, '2026-10-03 10:50:33', '2026-10-03 10:50:33'),
(215, 'App\\Models\\Admin', 33, 'auth_token', 'b564ee5a456b6541f8446635848c0ca05831c333512008a652f8655e10149cea', '[\"*\"]', NULL, NULL, '2026-10-03 10:55:59', '2026-10-03 10:55:59'),
(216, 'App\\Models\\Admin', 41, 'auth_token', '43fa5b4c4ba26ded4d75e35921b5ebe8f507f9e6f01da6200ee43d1be4178d69', '[\"*\"]', NULL, NULL, '2026-10-03 10:56:55', '2026-10-03 10:56:55'),
(217, 'App\\Models\\Admin', 40, 'auth_token', '786d1128b10047c38bb35dfd641578afe9af647a3d24ebe23003431dda15d69b', '[\"*\"]', NULL, NULL, '2026-10-03 10:57:16', '2026-10-03 10:57:16'),
(218, 'App\\Models\\Admin', 39, 'auth_token', 'e6c5f72a8dd7dc02dbcf45786b3c6be3778ce4242fdd0d2376a1f97b8e31d052', '[\"*\"]', NULL, NULL, '2026-10-03 10:57:32', '2026-10-03 10:57:32'),
(219, 'App\\Models\\Admin', 40, 'auth_token', 'e27fb3d8f415894372a5e0384b6f6dd8229346f6720b036ddb3b13f0b82f1013', '[\"*\"]', NULL, NULL, '2026-10-03 10:58:56', '2026-10-03 10:58:56'),
(220, 'App\\Models\\Admin', 33, 'auth_token', '30523bb8ea632d3e06d61e1512d1935e48d0bdbcd65883e95ff1c22c7fe987b0', '[\"*\"]', NULL, NULL, '2026-10-03 11:29:20', '2026-10-03 11:29:20'),
(221, 'App\\Models\\Admin', 33, 'auth_token', '31a255c3ac5a1c592732156a69cdc8a9464e604102a4f0975b1cc417ac99dad4', '[\"*\"]', NULL, NULL, '2026-10-03 11:29:40', '2026-10-03 11:29:40'),
(222, 'App\\Models\\Admin', 33, 'auth_token', '15579f18294a2cff5c83375380b061227feaa3f3bae30cf89595c89f5c8fd632', '[\"*\"]', NULL, NULL, '2026-10-03 13:12:56', '2026-10-03 13:12:56'),
(223, 'App\\Models\\Admin', 33, 'auth_token', '44ee6c25d39955f14d22f39f3f6e2c431794b1a5f8ee1d9fdb0766ab156a4f96', '[\"*\"]', NULL, NULL, '2026-10-03 13:13:35', '2026-10-03 13:13:35'),
(224, 'App\\Models\\Admin', 33, 'auth_token', '2ef2a73d79d41ea556571fb632d27a4fe53094da0d7ab910de59d7361717e241', '[\"*\"]', NULL, NULL, '2026-10-03 13:14:04', '2026-10-03 13:14:04'),
(225, 'App\\Models\\Admin', 33, 'auth_token', 'edce5ae62ecbe9edbd052e4d6dbe89a5131a14b51936a67accebd3eb4c0d69bb', '[\"*\"]', NULL, NULL, '2026-10-03 13:14:29', '2026-10-03 13:14:29'),
(226, 'App\\Models\\Admin', 33, 'auth_token', 'bfc751a9e960443396cc8a2c75d47fa727b754a52d2a0e492cbdb1a1fd1ab8ce', '[\"*\"]', NULL, NULL, '2026-10-03 13:21:16', '2026-10-03 13:21:16'),
(227, 'App\\Models\\Admin', 33, 'auth_token', 'c9fb676f66aeec92b6e74ce0a5d97eb3c268360e31fd1e4502fe1875e57a8013', '[\"*\"]', NULL, NULL, '2026-10-03 17:35:20', '2026-10-03 17:35:20'),
(228, 'App\\Models\\Admin', 33, 'auth_token', '89448c8d209fd0e58d4cd05b16a675de89074a75f21aa3e683f77766f7951d51', '[\"*\"]', NULL, NULL, '2026-10-03 17:58:07', '2026-10-03 17:58:07'),
(229, 'App\\Models\\Admin', 39, 'auth_token', 'd8bacbb043e8fc279fdf23cf3595142e97cef651dfa9244b870abb19ce2208dd', '[\"*\"]', NULL, NULL, '2026-10-05 01:46:25', '2026-10-05 01:46:25'),
(230, 'App\\Models\\Admin', 41, 'auth_token', 'e08af535cd83510ede2bcd1829e8aa2d1a4a949023f0a3453ff45c8547ff36e1', '[\"*\"]', NULL, NULL, '2026-10-05 02:59:30', '2026-10-05 02:59:30'),
(231, 'App\\Models\\Admin', 41, 'auth_token', '4fc66c833588c51489ad0aab5f17e7b860a0f5c690899ffe1b45a854111bce85', '[\"*\"]', NULL, NULL, '2026-10-05 03:23:32', '2026-10-05 03:23:32'),
(232, 'App\\Models\\Admin', 39, 'auth_token', 'a851062ca34844b30de6fe2a9e46bd88d9b1afaf13a02734c511d77b98b582a8', '[\"*\"]', NULL, NULL, '2026-10-05 03:48:35', '2026-10-05 03:48:35'),
(233, 'App\\Models\\Admin', 41, 'auth_token', '038ccd0f1f5364ab2939968934956635f457f9a4f92093add035e15f67c52f23', '[\"*\"]', NULL, NULL, '2026-10-05 04:10:54', '2026-10-05 04:10:54'),
(234, 'App\\Models\\Admin', 41, 'auth_token', 'c553400fbd535a1109bed13debd8493830a68249e2ad502dab46024bba0f866b', '[\"*\"]', NULL, NULL, '2026-10-05 04:26:29', '2026-10-05 04:26:29'),
(235, 'App\\Models\\Admin', 33, 'auth_token', 'a5713308a2fce29a1940a5fafaad5c001e2d5c0d8d0df20a8dec3a56319e78b4', '[\"*\"]', NULL, NULL, '2026-10-05 04:26:51', '2026-10-05 04:26:51'),
(236, 'App\\Models\\Admin', 41, 'auth_token', '311a10078ce4127270e5e7083b512448860c56dc51a02a2344c8f967716c2cc4', '[\"*\"]', NULL, NULL, '2026-10-05 04:52:59', '2026-10-05 04:52:59'),
(237, 'App\\Models\\Admin', 41, 'auth_token', 'ec879b2488eb5e0e292e814572425bf755b5511ad32ee422404501d08525ccf9', '[\"*\"]', NULL, NULL, '2026-10-05 04:58:42', '2026-10-05 04:58:42'),
(238, 'App\\Models\\Admin', 41, 'auth_token', 'f1eb20d398c032088bc9b93fb92bcec141aade65b99a47484a3f158b110813ad', '[\"*\"]', NULL, NULL, '2026-10-05 05:14:09', '2026-10-05 05:14:09'),
(239, 'App\\Models\\Admin', 41, 'auth_token', '9cab1dda17b90c7078d02f274d52f9c1c602f8e46a2db911eba3b31eadeefbd5', '[\"*\"]', NULL, NULL, '2026-10-05 05:27:53', '2026-10-05 05:27:53'),
(240, 'App\\Models\\Admin', 32, 'auth_token', '87cdd2fe94d85783df407864fafe343f4ac188ea4c50f47043dda9e987523ced', '[\"*\"]', NULL, NULL, '2026-10-05 07:54:36', '2026-10-05 07:54:36'),
(241, 'App\\Models\\Admin', 32, 'auth_token', 'c997f4ab8a4c079b7fed17ba2746c293f48b8df6b638de72e95acd77fe33c7bf', '[\"*\"]', NULL, NULL, '2026-10-05 09:24:42', '2026-10-05 09:24:42'),
(242, 'App\\Models\\Admin', 33, 'auth_token', '6db77b4b4af35f08bc5a61140d5459abe200c20ab2ebe5bbce3c8b2cd77d0ff3', '[\"*\"]', NULL, NULL, '2026-10-05 09:37:42', '2026-10-05 09:37:42'),
(243, 'App\\Models\\Admin', 33, 'auth_token', '607ed1d71d82b463a2d215b8b24afd1893f6db33d5f3af2c0b889ecbd44af9b0', '[\"*\"]', NULL, NULL, '2026-10-05 11:55:39', '2026-10-05 11:55:39'),
(244, 'App\\Models\\Admin', 1, 'auth_token', 'd2249e82d40a6b633f007b776e69d3e8bb764b2103c4bdde8a3414f2fc1d289a', '[\"*\"]', NULL, NULL, '2026-10-05 12:31:39', '2026-10-05 12:31:39'),
(245, 'App\\Models\\Admin', 33, 'auth_token', '6940dc7dba549f97bf99a7443a206113462b421ab9b17f723300403a40d58efd', '[\"*\"]', NULL, NULL, '2026-10-05 14:55:55', '2026-10-05 14:55:55'),
(246, 'App\\Models\\Admin', 39, 'auth_token', '7a95cc3d6193598fcbe903892a0f684732316b49573188255529dbf7ca42b452', '[\"*\"]', NULL, NULL, '2026-10-06 01:51:54', '2026-10-06 01:51:54'),
(247, 'App\\Models\\Admin', 41, 'auth_token', '8e5df2b1655182ee14cc1cc9aa5b52477b5a03e09c521d83a88d0fd5a7b212dc', '[\"*\"]', NULL, NULL, '2026-10-06 03:00:58', '2026-10-06 03:00:58'),
(248, 'App\\Models\\Admin', 40, 'auth_token', '76bab16a724fde4239390dcf03c14aa18861778d67e10a22d91151d1827c4ab3', '[\"*\"]', NULL, NULL, '2026-10-06 03:03:03', '2026-10-06 03:03:03'),
(249, 'App\\Models\\Admin', 40, 'auth_token', '6b8955593c89bd263c55077a1f9e275ad9b3610ca4e5ac494eca4e1d61b7b563', '[\"*\"]', NULL, NULL, '2026-10-06 03:05:30', '2026-10-06 03:05:30'),
(250, 'App\\Models\\Admin', 40, 'auth_token', 'e86ef51f7efa4d6248a12cbb19d8dd2618f1e940b478361f146c2a21e5deed65', '[\"*\"]', NULL, NULL, '2026-10-06 03:07:40', '2026-10-06 03:07:40'),
(251, 'App\\Models\\Admin', 40, 'auth_token', '56acc505190510b1c6aecf369e39b5106b89e0137a7ab796aba576b543f2718d', '[\"*\"]', NULL, NULL, '2026-10-06 03:52:24', '2026-10-06 03:52:24'),
(252, 'App\\Models\\Admin', 40, 'auth_token', 'f298664d62856f542928cce93b0b6810be1a98371ecb44eec850e16e85b25cff', '[\"*\"]', NULL, NULL, '2026-10-06 04:25:39', '2026-10-06 04:25:39'),
(253, 'App\\Models\\Admin', 39, 'auth_token', '84ff055826341a2281cfd83b514bff4c3621f55b3da1cf60f265471dbc31eb11', '[\"*\"]', NULL, NULL, '2026-10-06 04:40:41', '2026-10-06 04:40:41'),
(254, 'App\\Models\\Admin', 40, 'auth_token', 'a06a719e2b337d0116c6c0b774428f1986399e2e1f13ae975bd1a81a755112f4', '[\"*\"]', NULL, NULL, '2026-10-06 04:47:18', '2026-10-06 04:47:18'),
(255, 'App\\Models\\Admin', 40, 'auth_token', 'feb695d3c0089963bab23869ebf4b45a97df7fb20bd650d11ec5dffa64cb7a35', '[\"*\"]', NULL, NULL, '2026-10-06 05:42:16', '2026-10-06 05:42:16'),
(256, 'App\\Models\\Admin', 39, 'auth_token', '23a8565548b0167d8aedbac20345b8a59e51d8891053a0592479ae0f5ee258dd', '[\"*\"]', NULL, NULL, '2026-10-06 06:03:01', '2026-10-06 06:03:01'),
(257, 'App\\Models\\Admin', 33, 'auth_token', '4763c0304e5f4a0a6f7abf1e6fe5e83a03e512706820dd86efa81d5b463a9a12', '[\"*\"]', NULL, NULL, '2026-10-06 06:06:33', '2026-10-06 06:06:33'),
(258, 'App\\Models\\Admin', 39, 'auth_token', 'e01f65f25b80feb66905f765f27db739111ecbecf97e7cd6a1b00f8334ad2c8d', '[\"*\"]', NULL, NULL, '2026-10-06 06:14:14', '2026-10-06 06:14:14'),
(259, 'App\\Models\\Admin', 33, 'auth_token', 'bf78bf676f1bdc0e40e252482275a9855fe8d00e21cd73b79b1caf0423f53d4c', '[\"*\"]', NULL, NULL, '2026-10-06 06:41:06', '2026-10-06 06:41:06'),
(260, 'App\\Models\\Admin', 32, 'auth_token', 'a9b3af910c37236ba0c09298448d2e8e599c5f426b55790244aa9b5023e0e6fd', '[\"*\"]', NULL, NULL, '2026-10-06 06:46:35', '2026-10-06 06:46:35'),
(261, 'App\\Models\\Admin', 33, 'auth_token', '3f49c3a92233ed1d2eded2497c6359415c607bf0da14301cb6a027f2e120ed8f', '[\"*\"]', NULL, NULL, '2026-10-06 06:55:05', '2026-10-06 06:55:05'),
(262, 'App\\Models\\Admin', 32, 'auth_token', '0d6c2da547b6834c211583a3543fc5cc1e0bb02d297de04dd4f1e562ff343370', '[\"*\"]', NULL, NULL, '2026-10-06 06:55:42', '2026-10-06 06:55:42'),
(263, 'App\\Models\\Admin', 32, 'auth_token', '58ccb5e6e3ac33f45bfbea79a6745e941835a9a7c37f310c6809c7f4ce784a75', '[\"*\"]', NULL, NULL, '2026-10-06 07:50:15', '2026-10-06 07:50:15'),
(264, 'App\\Models\\Admin', 32, 'auth_token', '5b27d58d7d67eceb78da76481099601162385cff3831f414c44fb068efb39ff5', '[\"*\"]', NULL, NULL, '2026-10-06 08:05:10', '2026-10-06 08:05:10'),
(265, 'App\\Models\\Admin', 32, 'auth_token', 'ce418e1315a1b5415ae4852fda382429c5269946293f3122ba0eefc77cd3b50b', '[\"*\"]', NULL, NULL, '2026-10-06 08:34:22', '2026-10-06 08:34:22'),
(266, 'App\\Models\\Admin', 32, 'auth_token', '9f675b00d88a2a25f7c71b798fd8d48ecd3cd7d579f2e91ec9c32924c6e3e11d', '[\"*\"]', NULL, NULL, '2026-10-06 08:47:20', '2026-10-06 08:47:20'),
(267, 'App\\Models\\Admin', 33, 'auth_token', 'e68915485457d17d513e1b82b68489c3913a5d46e23c09d6d85ff49e4207c4a9', '[\"*\"]', NULL, NULL, '2026-10-06 09:49:24', '2026-10-06 09:49:24'),
(268, 'App\\Models\\Admin', 33, 'auth_token', 'ee2694b8baf0e90019471aacb254d02e7de88c316ee4311b7ae28a0970949e39', '[\"*\"]', NULL, NULL, '2026-10-06 09:50:28', '2026-10-06 09:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(555) NOT NULL,
  `hsn_code` varchar(555) DEFAULT NULL,
  `per_case_quantity` int(11) DEFAULT NULL,
  `per_case_price` varchar(555) NOT NULL,
  `per_bottle_price` varchar(555) NOT NULL,
  `cgst` varchar(555) DEFAULT NULL,
  `sgst` varchar(555) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `hsn_code`, `per_case_quantity`, `per_case_price`, `per_bottle_price`, `cgst`, `sgst`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FRUIZY MANGO 1000ML MRP 70', '22029020', 6, '300', '50.00', '2.5', '2.5', 1, '2026-08-19 03:36:30', '2026-10-03 04:02:15'),
(3, 'FRUIZY MANGO 500ML MRP 35', '22029020', 24, '600', '25.00', '2.5', '2.5', 1, '2026-08-29 13:49:57', '2026-10-02 12:06:45'),
(4, 'FRUIZY MANGO 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-09-02 09:58:15', '2026-10-02 12:07:06'),
(5, 'FRUIZY NIMBU PAANI 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 09:58:01', '2026-10-02 12:07:20'),
(6, 'FRUIZY KOKUM 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 09:58:44', '2026-10-02 12:07:31'),
(7, 'TRIK WATER 250ML MRP 8', '2201', 24, '130', '5.41', '2.5', '2.5', 1, '2026-10-02 12:09:15', '2026-10-02 12:16:46'),
(8, 'TRIK WATER 500ML MRP 10', '2201', 24, '160', '6.66', '2.5', '2.5', 1, '2026-10-02 12:10:33', '2026-10-02 12:17:25'),
(9, 'TRIK WATER 1000ML MRP 20', '2201', 12, '130', '10.83', '2.5', '2.5', 1, '2026-10-02 12:11:51', '2026-10-02 12:17:15'),
(10, 'FRUIZY MANGO 150ML MRP 10', '22029020', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-02 12:15:02', '2026-10-02 12:17:05'),
(11, 'FRUIZY NIMBU PANNI 150ML MRP 10', '22029020', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-02 12:16:29', '2026-10-02 12:16:56'),
(12, 'FRUIZY KOKUM 150ML MRP 10', '22029020', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-02 12:18:08', '2026-10-02 12:18:08'),
(13, 'FRUIZY APPLE 150ML MRP 10', '22029020', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-02 12:18:43', '2026-10-02 12:18:43'),
(14, 'FRUIZY LITCHI 150ML MRP 10', '22029020', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-02 12:19:27', '2026-10-02 12:19:27'),
(15, 'FRUIZY APPLE 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 12:20:06', '2026-10-02 12:21:11'),
(16, 'FRUIZY LITCHI 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 12:21:05', '2026-10-02 12:21:29'),
(17, 'FRUIZY ORANGE 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 12:22:07', '2026-10-02 12:22:07'),
(18, 'FRUIZY JEERA 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 12:22:54', '2026-10-02 12:22:54'),
(19, 'FRUIZY GINGER 250ML MRP 20', '22029020', 24, '360', '15.00', '2.5', '2.5', 1, '2026-10-02 12:23:37', '2026-10-02 12:23:37'),
(20, 'FRUIZY NIMBU PANNI 500ML MRP 35', '22029020', 24, '600', '25.00', '2.5', '2.5', 1, '2026-10-02 12:24:35', '2026-10-02 12:24:35'),
(21, 'FRUIZY NIMBU PAANI 1000ML MRP 70', '22029020', 6, '300', '50.00', '2.5', '2.5', 1, '2026-10-02 12:25:28', '2026-10-03 04:04:17'),
(22, 'TRIK LASSI 180ML MRP 25', '040310', 30, '600', '20.00', '2.5', '2.5', 1, '2026-10-02 12:27:06', '2026-10-02 12:27:06'),
(23, 'CRUNET SODA 300ML MRP 10', '2201', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-03 03:10:47', '2026-10-03 03:11:14'),
(24, 'CRUNET SODA 600ML MRP 18', '2201', 24, '280', '11.67', '2.5', '2.5', 1, '2026-10-03 03:12:26', '2026-10-03 04:05:26'),
(25, 'TRIK ORANGE 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:13:27', '2026-10-03 03:19:56'),
(26, 'TRIK LIME SODA 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:14:10', '2026-10-03 03:20:12'),
(27, 'TRIK COLA 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:14:44', '2026-10-03 03:21:30'),
(28, 'TRIK CLEAR LIME 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:15:18', '2026-10-03 03:21:23'),
(29, 'TRIK JEERA 150ML MRP 10', '220210', 24, '200', '8.33', '2.5', '2.5', 1, '2026-10-03 03:15:50', '2026-10-03 03:15:50'),
(30, 'TRIK GINGER 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:17:09', '2026-10-03 03:21:15'),
(31, 'TRIK ENERGY 9X 150ML MRP 10', '220210', 24, '200', '8.33', '20', '20', 1, '2026-10-03 03:18:32', '2026-10-03 03:39:46'),
(32, 'TRIK ORANGE 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:19:37', '2026-10-03 03:24:45'),
(33, 'TRIK LIME SODA 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:25:41', '2026-10-03 03:25:41'),
(34, 'TRIK COLA 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:26:14', '2026-10-03 03:26:25'),
(35, 'TRIK CLEAR LIME 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:26:59', '2026-10-03 03:26:59'),
(36, 'TRIK JEERA 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:27:43', '2026-10-03 04:07:08'),
(37, 'TRIK GINGER 300ML MRP 20', '220210', 12, '180', '15.00', '20', '20', 1, '2026-10-03 03:28:49', '2026-10-03 04:06:57'),
(38, '7HRS ENERGY 250ML MRP 60', '2202', 24, '1080', '45.00', '20', '20', 1, '2026-10-03 03:30:48', '2026-10-03 03:59:07'),
(39, 'SONAI MILK 1000ML MRP 80', '04014000', 12, '780', '65.00', '0', '0', 1, '2026-10-03 03:33:51', '2026-10-03 03:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `road`
--

CREATE TABLE `road` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `road_name` longtext NOT NULL,
  `full_address` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `road`
--

INSERT INTO `road` (`id`, `area_id`, `road_name`, `full_address`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'VIP Road 1', 'Kolkata-2', 1, '2026-08-07 06:22:36', '2026-08-07 13:53:04'),
(4, 5, '1170 High Road', 'Namkhana-2', 1, '2026-08-08 17:37:41', '2026-08-29 14:57:51'),
(5, 2, 'TD Road', 'South Goa', 1, '2026-08-08 18:03:42', '2026-08-08 18:03:42'),
(6, 6, 'High Road - 1179', 'kolkata', 1, '2026-08-29 13:42:37', '2026-08-29 14:57:13'),
(8, 6, 'Mobor', NULL, 1, '2026-10-02 09:43:11', '2026-10-02 09:43:11'),
(9, 6, 'Carmona', NULL, 1, '2026-10-02 09:52:25', '2026-10-02 09:52:25'),
(10, 6, 'Varca', NULL, 1, '2026-10-02 09:53:17', '2026-10-02 09:53:17'),
(11, 6, 'Benaulim', NULL, 1, '2026-10-02 09:53:56', '2026-10-02 09:53:56'),
(12, 6, 'Colva', NULL, 1, '2026-10-02 09:54:15', '2026-10-02 09:54:15'),
(13, 6, 'Bethalbhati', NULL, 1, '2026-10-02 09:54:34', '2026-10-02 09:54:34'),
(14, 7, 'Morjim', NULL, 1, '2026-10-02 09:56:05', '2026-10-02 09:56:05'),
(15, 7, 'Mandrem', NULL, 1, '2026-10-02 09:56:20', '2026-10-02 09:56:20'),
(16, 7, 'Harmal', NULL, 1, '2026-10-02 09:56:33', '2026-10-02 09:56:33'),
(17, 7, 'Keri', NULL, 1, '2026-10-02 09:56:46', '2026-10-02 09:56:46'),
(18, 7, 'Korgao', NULL, 1, '2026-10-02 09:57:02', '2026-10-02 09:57:02'),
(19, 7, 'Parshem', NULL, 1, '2026-10-02 09:57:17', '2026-10-02 09:57:17'),
(20, 8, 'Dhargal', NULL, 1, '2026-10-02 09:57:36', '2026-10-02 09:57:36'),
(21, 8, 'Pernem', NULL, 1, '2026-10-02 09:57:55', '2026-10-02 09:57:55'),
(22, 8, 'Patradevi', NULL, 1, '2026-10-02 09:58:13', '2026-10-02 09:58:13'),
(23, 8, 'Mopa', NULL, 1, '2026-10-02 09:58:24', '2026-10-02 09:58:24'),
(24, 8, 'Chandel', NULL, 1, '2026-10-02 09:58:37', '2026-10-02 09:58:37'),
(25, 8, 'Ibrampur', NULL, 1, '2026-10-02 09:58:56', '2026-10-02 09:58:56'),
(26, 9, 'Dodamarg', NULL, 1, '2026-10-02 09:59:16', '2026-10-02 09:59:16'),
(27, 9, 'Bicholim', NULL, 1, '2026-10-02 09:59:34', '2026-10-02 09:59:34'),
(28, 9, 'Mulgao', NULL, 1, '2026-10-02 09:59:46', '2026-10-02 09:59:46'),
(29, 9, 'Bordem', NULL, 1, '2026-10-02 10:00:03', '2026-10-02 10:00:03'),
(30, 9, 'Asnora', NULL, 1, '2026-10-02 10:00:20', '2026-10-02 10:00:20'),
(31, 9, 'Sonshi', NULL, 1, '2026-10-02 10:00:36', '2026-10-02 10:00:36'),
(32, 10, 'Pirna', NULL, 1, '2026-10-02 10:01:12', '2026-10-02 10:01:12'),
(33, 10, 'Thivim', NULL, 1, '2026-10-02 10:01:31', '2026-10-02 10:01:31'),
(34, 10, 'Colvale', NULL, 1, '2026-10-02 10:01:46', '2026-10-02 10:01:46'),
(35, 10, 'Kharaswada', NULL, 1, '2026-10-02 10:02:08', '2026-10-02 10:02:08'),
(36, 10, 'Camurlim', NULL, 1, '2026-10-02 10:02:25', '2026-10-02 10:02:25'),
(37, 10, 'Mencurem', NULL, 1, '2026-10-02 10:02:45', '2026-10-02 10:02:45'),
(38, 11, 'Mapusa City', NULL, 1, '2026-10-02 10:03:09', '2026-10-02 10:03:09'),
(39, 11, 'Mapusa Market', NULL, 1, '2026-10-02 10:03:26', '2026-10-02 10:03:26'),
(40, 11, 'Parra', NULL, 1, '2026-10-02 10:03:39', '2026-10-02 10:03:39'),
(41, 11, 'Khorlim', NULL, 1, '2026-10-02 10:03:59', '2026-10-02 10:03:59'),
(42, 11, 'Housing Board', NULL, 1, '2026-10-02 10:04:54', '2026-10-02 10:04:54'),
(43, 11, 'Dhuler', NULL, 1, '2026-10-02 10:05:24', '2026-10-02 10:05:24'),
(44, 12, 'Siolim', NULL, 1, '2026-10-02 10:05:49', '2026-10-02 10:05:49'),
(45, 12, 'Siolim Market', NULL, 1, '2026-10-02 10:06:29', '2026-10-02 10:06:29'),
(46, 12, 'Assagao', NULL, 1, '2026-10-02 10:06:53', '2026-10-02 10:06:53'),
(47, 12, 'Palyem', NULL, 1, '2026-10-02 10:07:41', '2026-10-02 10:07:41'),
(48, 12, 'Dhargal', NULL, 1, '2026-10-02 10:08:12', '2026-10-02 10:08:12'),
(49, 12, 'Tuvem', NULL, 1, '2026-10-02 10:08:26', '2026-10-02 10:08:26'),
(50, 13, 'Sangolda', NULL, 1, '2026-10-02 10:08:52', '2026-10-02 10:08:52'),
(51, 13, 'Saligao', NULL, 1, '2026-10-02 10:09:16', '2026-10-02 10:09:16'),
(52, 13, 'Pilerne', NULL, 1, '2026-10-02 10:09:47', '2026-10-02 10:09:47'),
(53, 13, 'Candolim', NULL, 1, '2026-10-02 10:10:11', '2026-10-02 10:10:11'),
(54, 13, 'Candolim II', NULL, 1, '2026-10-02 10:10:39', '2026-10-02 10:10:39'),
(55, 13, 'Siquerim', NULL, 1, '2026-10-02 10:11:08', '2026-10-02 10:11:08'),
(56, 14, 'Calangute I', NULL, 1, '2026-10-02 10:11:36', '2026-10-02 10:11:36'),
(57, 14, 'Calangute II', NULL, 1, '2026-10-02 10:12:02', '2026-10-02 10:12:02'),
(58, 14, 'Baga', NULL, 1, '2026-10-02 10:12:15', '2026-10-02 10:12:15'),
(59, 14, 'Arpora', NULL, 1, '2026-10-02 10:12:40', '2026-10-02 10:12:40'),
(60, 14, 'Anjuna', NULL, 1, '2026-10-02 10:13:01', '2026-10-02 10:13:01'),
(61, 14, 'Vagartor', NULL, 1, '2026-10-02 10:13:18', '2026-10-02 10:13:18'),
(62, 15, 'Betim', NULL, 1, '2026-10-02 10:13:48', '2026-10-02 10:13:48'),
(63, 15, 'Bitona', NULL, 1, '2026-10-02 10:14:14', '2026-10-02 10:14:14'),
(64, 15, 'Porvorim', NULL, 1, '2026-10-02 10:14:36', '2026-10-02 10:14:36'),
(65, 15, 'Chogam Road', NULL, 1, '2026-10-02 10:14:58', '2026-10-02 10:14:58'),
(66, 15, 'Nerul', NULL, 1, '2026-10-02 10:15:26', '2026-10-02 10:15:26'),
(67, 15, 'Socorro', NULL, 1, '2026-10-02 10:15:48', '2026-10-02 10:15:48'),
(68, 16, 'Bastora', NULL, 1, '2026-10-02 10:16:14', '2026-10-02 10:16:14'),
(69, 16, 'Ucassaim', NULL, 1, '2026-10-02 10:16:32', '2026-10-02 10:16:32'),
(70, 16, 'Moide', NULL, 1, '2026-10-02 10:16:48', '2026-10-02 10:16:48'),
(71, 16, 'Aldona', NULL, 1, '2026-10-02 10:17:03', '2026-10-02 10:17:03'),
(72, 16, 'Corjem', NULL, 1, '2026-10-02 10:17:25', '2026-10-02 10:17:25'),
(73, 16, 'Pomburpa', NULL, 1, '2026-10-02 10:17:52', '2026-10-02 10:17:52'),
(74, 17, 'Mala', NULL, 1, '2026-10-02 10:18:18', '2026-10-02 10:18:18'),
(75, 17, 'Altinho', NULL, 1, '2026-10-02 10:18:40', '2026-10-02 10:18:40'),
(76, 17, 'Panaji Market', NULL, 1, '2026-10-02 10:19:51', '2026-10-02 10:19:51'),
(77, 17, 'Panaji City', NULL, 1, '2026-10-02 10:20:14', '2026-10-02 10:20:14'),
(78, 17, 'St. Inez', NULL, 1, '2026-10-02 10:20:32', '2026-10-02 10:20:32'),
(79, 17, 'Miramar', NULL, 1, '2026-10-02 10:20:47', '2026-10-02 10:20:47'),
(80, 19, 'Pato', NULL, 1, '2026-10-02 10:21:11', '2026-10-02 10:21:11'),
(81, 19, 'St. Cruz', NULL, 1, '2026-10-02 10:21:27', '2026-10-02 10:21:27'),
(82, 19, 'Calapur', NULL, 1, '2026-10-02 10:21:48', '2026-10-02 10:21:48'),
(83, 19, 'Mershe', NULL, 1, '2026-10-02 10:22:06', '2026-10-02 10:22:06'),
(84, 19, 'Chimbal', NULL, 1, '2026-10-02 10:22:21', '2026-10-02 10:22:21'),
(85, 19, 'Raibandar', NULL, 1, '2026-10-02 10:22:44', '2026-10-02 10:22:44'),
(86, 20, 'Agaciam', NULL, 1, '2026-10-02 10:23:02', '2026-10-02 10:23:02'),
(87, 20, 'Pilar', NULL, 1, '2026-10-02 10:23:16', '2026-10-02 10:23:16'),
(88, 20, 'Sirdao', NULL, 1, '2026-10-02 10:23:34', '2026-10-02 10:23:34'),
(89, 20, 'Bambolim', NULL, 1, '2026-10-02 10:24:01', '2026-10-02 10:24:01'),
(90, 20, 'Talegao', NULL, 1, '2026-10-02 10:24:15', '2026-10-02 10:24:15'),
(91, 20, 'Caranzalim', NULL, 1, '2026-10-02 10:24:36', '2026-10-02 10:24:36'),
(92, 21, 'Diwar', NULL, 1, '2026-10-02 10:24:57', '2026-10-02 10:24:57'),
(93, 21, 'Corlim', NULL, 1, '2026-10-02 10:25:12', '2026-10-02 10:25:12'),
(94, 21, 'Old Goa', NULL, 1, '2026-10-02 10:25:29', '2026-10-02 10:25:29'),
(95, 21, 'Karmali', NULL, 1, '2026-10-02 10:25:51', '2026-10-02 10:25:51'),
(96, 21, 'Baigini', NULL, 1, '2026-10-02 10:26:06', '2026-10-02 10:26:06'),
(97, 21, 'Riabandar', NULL, 1, '2026-10-02 10:26:41', '2026-10-02 10:26:41'),
(98, 22, 'Mayem', NULL, 1, '2026-10-02 10:26:59', '2026-10-02 10:26:59'),
(99, 22, 'Shirgao', NULL, 1, '2026-10-02 10:27:14', '2026-10-02 10:27:14'),
(100, 22, 'Pilgao', NULL, 1, '2026-10-02 10:27:30', '2026-10-02 10:27:30'),
(101, 22, 'Chodna', NULL, 1, '2026-10-02 10:27:44', '2026-10-02 10:27:44'),
(102, 22, 'Narve', NULL, 1, '2026-10-02 10:27:58', '2026-10-02 10:27:58'),
(103, 22, 'Vargao', NULL, 1, '2026-10-02 10:28:13', '2026-10-02 10:28:13'),
(104, 23, 'Pali', NULL, 1, '2026-10-02 10:28:37', '2026-10-02 10:28:37'),
(105, 23, 'Surla', NULL, 1, '2026-10-02 10:28:59', '2026-10-02 10:28:59'),
(106, 23, 'Amona', NULL, 1, '2026-10-02 10:29:15', '2026-10-02 10:29:15'),
(107, 23, 'Harvalem', NULL, 1, '2026-10-02 10:29:34', '2026-10-02 10:29:34'),
(108, 23, 'Sanquelim', NULL, 1, '2026-10-02 10:29:53', '2026-10-02 10:29:53'),
(109, 23, 'Sarvana', NULL, 1, '2026-10-02 10:30:11', '2026-10-02 10:30:11'),
(110, 24, 'Curchelim', NULL, 1, '2026-10-02 10:30:57', '2026-10-02 10:30:57'),
(111, 24, 'Poriem', NULL, 1, '2026-10-02 10:31:23', '2026-10-02 10:31:23'),
(112, 24, 'Hoda', NULL, 1, '2026-10-02 10:31:44', '2026-10-02 10:31:44'),
(113, 24, 'Keri', NULL, 1, '2026-10-02 10:32:13', '2026-10-02 10:32:13'),
(114, 24, 'Pisurlem', NULL, 1, '2026-10-02 10:33:21', '2026-10-02 10:33:21'),
(115, 24, 'Guleli', NULL, 1, '2026-10-02 10:33:52', '2026-10-02 10:33:52'),
(116, 25, 'Valpoi', NULL, 1, '2026-10-02 10:34:15', '2026-10-02 10:34:15'),
(117, 25, 'Khadki', NULL, 1, '2026-10-02 10:34:31', '2026-10-02 10:34:31'),
(118, 25, 'Satrem', NULL, 1, '2026-10-02 10:34:45', '2026-10-02 10:34:45'),
(119, 25, 'Thane', NULL, 1, '2026-10-02 10:34:57', '2026-10-02 10:34:57'),
(120, 25, 'Corpodem', NULL, 1, '2026-10-02 10:35:16', '2026-10-02 10:35:16'),
(121, 25, 'Maushi', NULL, 1, '2026-10-02 10:35:31', '2026-10-02 10:35:31'),
(122, 26, 'St. Estev', NULL, 1, '2026-10-02 10:35:54', '2026-10-02 10:35:54'),
(123, 26, 'Marcel', NULL, 1, '2026-10-02 10:36:10', '2026-10-02 10:36:10'),
(124, 26, 'Banastari', NULL, 1, '2026-10-02 10:36:29', '2026-10-02 10:36:29'),
(125, 26, 'Volvoi', NULL, 1, '2026-10-02 10:36:47', '2026-10-02 10:36:47'),
(126, 26, 'Kundai', NULL, 1, '2026-10-02 10:37:00', '2026-10-02 10:37:00'),
(127, 26, 'Kundai IDC', NULL, 1, '2026-10-02 10:37:17', '2026-10-02 10:37:17'),
(128, 27, 'Shantinagar', NULL, 1, '2026-10-02 10:38:00', '2026-10-02 10:38:00'),
(129, 27, 'Khadpaband', NULL, 1, '2026-10-02 10:38:26', '2026-10-02 10:38:26'),
(130, 27, 'Uppar Bazaar', NULL, 1, '2026-10-02 10:38:47', '2026-10-02 10:38:47'),
(131, 27, 'Curti', NULL, 1, '2026-10-02 10:39:02', '2026-10-02 10:39:02'),
(132, 27, 'Farmagudi', NULL, 1, '2026-10-02 10:39:21', '2026-10-02 10:39:21'),
(133, 27, 'Khandepar', NULL, 1, '2026-10-02 10:39:43', '2026-10-02 10:39:43'),
(134, 29, 'Madkai', NULL, 1, '2026-10-02 10:41:12', '2026-10-02 10:41:12'),
(135, 29, 'Farmagudi', NULL, 1, '2026-10-02 10:41:39', '2026-10-02 10:41:39'),
(136, 29, 'Mardol', NULL, 1, '2026-10-02 10:41:54', '2026-10-02 10:41:54'),
(137, 29, 'Kumkaliem', NULL, 1, '2026-10-02 10:42:41', '2026-10-02 10:42:41'),
(138, 29, 'Durbhat', NULL, 1, '2026-10-02 10:43:03', '2026-10-02 10:43:03'),
(139, 29, 'Kavlem', NULL, 1, '2026-10-02 10:43:21', '2026-10-02 10:43:21'),
(140, 31, 'Sada', NULL, 1, '2026-10-02 10:43:47', '2026-10-02 10:43:47'),
(141, 31, 'Baina', NULL, 1, '2026-10-02 10:43:59', '2026-10-02 10:43:59'),
(142, 31, 'Vasco City', NULL, 1, '2026-10-02 10:44:19', '2026-10-02 10:44:19'),
(143, 31, 'Vasco Market', NULL, 1, '2026-10-02 10:44:34', '2026-10-02 10:44:34'),
(144, 31, 'New Vaddem', NULL, 1, '2026-10-02 10:44:51', '2026-10-02 10:44:51'),
(145, 31, 'Mangor', NULL, 1, '2026-10-02 10:45:03', '2026-10-02 10:45:03'),
(146, 32, 'Chicalim', NULL, 1, '2026-10-02 10:45:26', '2026-10-02 10:45:26'),
(147, 32, 'Consua', NULL, 1, '2026-10-02 10:45:41', '2026-10-02 10:45:41'),
(148, 32, 'Varunapuri', NULL, 1, '2026-10-02 10:46:06', '2026-10-02 10:46:06'),
(149, 32, 'Birla', NULL, 1, '2026-10-02 10:46:19', '2026-10-02 10:46:19'),
(150, 32, 'Dabolim', NULL, 1, '2026-10-02 10:46:36', '2026-10-02 10:46:36'),
(151, 32, 'Bogmalo', NULL, 1, '2026-10-02 10:46:55', '2026-10-02 10:46:55'),
(152, 33, 'Cansaulim', NULL, 1, '2026-10-02 10:47:24', '2026-10-02 10:47:24'),
(153, 33, 'Majorda', NULL, 1, '2026-10-02 10:47:42', '2026-10-02 10:47:42'),
(154, 33, 'Verna IDC', NULL, 1, '2026-10-02 10:48:05', '2026-10-02 10:48:05'),
(155, 33, 'Cortalim', NULL, 1, '2026-10-02 10:48:29', '2026-10-02 10:48:29'),
(156, 33, 'Sancoale', NULL, 1, '2026-10-02 10:48:44', '2026-10-02 10:48:44'),
(157, 33, 'Utorda', NULL, 1, '2026-10-02 10:49:11', '2026-10-02 10:49:11'),
(158, 34, 'Nuvem', NULL, 1, '2026-10-02 10:49:35', '2026-10-02 10:49:35'),
(159, 34, 'Verna', NULL, 1, '2026-10-02 10:49:50', '2026-10-02 10:49:50'),
(160, 34, 'Raia', NULL, 1, '2026-10-02 10:50:04', '2026-10-02 10:50:04'),
(161, 34, 'Loutolim', NULL, 1, '2026-10-02 10:50:21', '2026-10-02 10:50:21'),
(162, 34, 'Thane', NULL, 1, '2026-10-02 10:50:35', '2026-10-02 10:50:35'),
(163, 34, 'Rassai', NULL, 1, '2026-10-02 10:50:53', '2026-10-02 10:50:53'),
(164, 35, 'Nessai', NULL, 1, '2026-10-02 10:51:16', '2026-10-02 10:51:16'),
(165, 35, 'Chandor', NULL, 1, '2026-10-02 10:51:37', '2026-10-02 10:51:37'),
(166, 35, 'Curtorim', NULL, 1, '2026-10-02 10:51:56', '2026-10-02 10:51:56'),
(167, 35, 'Macazana', NULL, 1, '2026-10-02 10:52:16', '2026-10-02 10:52:16'),
(168, 35, 'Ramnagari', NULL, 1, '2026-10-02 10:52:41', '2026-10-02 10:52:41'),
(169, 35, 'Gudi', NULL, 1, '2026-10-02 10:52:54', '2026-10-02 10:52:54'),
(170, 36, 'Davorlim', NULL, 1, '2026-10-02 10:53:25', '2026-10-02 10:53:25'),
(171, 36, 'Housing Board', NULL, 1, '2026-10-02 10:53:41', '2026-10-02 10:53:41'),
(172, 36, 'Gogol', NULL, 1, '2026-10-02 10:53:53', '2026-10-02 10:53:53'),
(173, 36, 'Ambaji', NULL, 1, '2026-10-02 10:54:19', '2026-10-02 10:54:19'),
(174, 36, 'Fatorda', NULL, 1, '2026-10-02 10:54:35', '2026-10-02 10:54:35'),
(175, 36, 'Borda', NULL, 1, '2026-10-02 10:54:52', '2026-10-02 10:54:52'),
(176, 28, 'Vazze', NULL, 1, '2026-10-02 10:55:24', '2026-10-02 10:55:24'),
(177, 28, 'Shiroda', NULL, 1, '2026-10-02 10:55:40', '2026-10-02 10:55:40'),
(178, 28, 'Panchwadi', NULL, 1, '2026-10-02 10:56:00', '2026-10-02 10:56:00'),
(179, 28, 'Durbhat', NULL, 1, '2026-10-02 10:56:19', '2026-10-02 10:56:19'),
(180, 28, 'Borim', NULL, 1, '2026-10-02 10:56:33', '2026-10-02 10:56:33'),
(181, 28, 'Bethora', NULL, 1, '2026-10-02 10:56:49', '2026-10-02 10:56:49'),
(182, 37, 'Margao', NULL, 1, '2026-10-02 10:57:04', '2026-10-02 10:57:04'),
(183, 37, 'Comba', NULL, 1, '2026-10-02 10:57:17', '2026-10-02 10:57:17'),
(184, 37, 'Pajifond', NULL, 1, '2026-10-02 10:57:50', '2026-10-02 10:57:50'),
(185, 37, 'Malbhat', NULL, 1, '2026-10-02 10:58:04', '2026-10-02 10:58:04'),
(186, 37, 'Aquem', NULL, 1, '2026-10-02 10:58:24', '2026-10-02 10:58:24'),
(187, 37, 'KTC- Madel', NULL, 1, '2026-10-02 10:58:43', '2026-10-02 10:58:43'),
(188, 38, 'Mandopa', NULL, 1, '2026-10-02 10:59:13', '2026-10-02 10:59:13'),
(189, 38, 'Navelim', NULL, 1, '2026-10-02 10:59:32', '2026-10-02 10:59:32'),
(190, 38, 'Shirvodem', NULL, 1, '2026-10-02 10:59:49', '2026-10-02 10:59:49'),
(191, 38, 'Khareband', NULL, 1, '2026-10-02 11:00:08', '2026-10-02 11:00:08'),
(192, 38, 'Rawanfond', NULL, 1, '2026-10-02 11:00:28', '2026-10-02 11:00:28'),
(193, 38, 'Shantinagar', NULL, 1, '2026-10-02 11:00:47', '2026-10-02 11:00:47'),
(194, 39, 'Khola', NULL, 1, '2026-10-02 11:01:03', '2026-10-02 11:01:03'),
(195, 39, 'Fatorpa', NULL, 1, '2026-10-02 11:01:17', '2026-10-02 11:01:17'),
(196, 39, 'Cuncolim', NULL, 1, '2026-10-02 11:01:29', '2026-10-02 11:01:29'),
(197, 39, 'Cavrem', NULL, 1, '2026-10-02 11:01:49', '2026-10-02 11:01:49'),
(198, 39, 'Balli', NULL, 1, '2026-10-02 11:02:03', '2026-10-02 11:02:03'),
(199, 39, 'Barcem', NULL, 1, '2026-10-02 11:02:34', '2026-10-02 11:02:34'),
(200, 40, 'Betul', NULL, 1, '2026-10-02 11:03:06', '2026-10-02 11:03:06'),
(201, 40, 'Velim', NULL, 1, '2026-10-02 11:03:25', '2026-10-02 11:03:25'),
(202, 40, 'Assolna', NULL, 1, '2026-10-02 11:03:39', '2026-10-02 11:03:39'),
(203, 40, 'Chinchinim', NULL, 1, '2026-10-02 11:03:56', '2026-10-02 11:03:56'),
(204, 40, 'Sarzora', NULL, 1, '2026-10-02 11:04:14', '2026-10-02 11:04:14'),
(205, 40, 'Veroda', NULL, 1, '2026-10-02 11:04:26', '2026-10-02 11:04:26'),
(206, 41, 'Rivona', NULL, 1, '2026-10-02 11:05:00', '2026-10-02 11:05:00'),
(207, 41, 'Ambaulim', NULL, 1, '2026-10-02 11:05:22', '2026-10-02 11:05:22'),
(208, 41, 'Quepem', NULL, 1, '2026-10-02 11:05:37', '2026-10-02 11:05:37'),
(209, 41, 'Molcornem', NULL, 1, '2026-10-02 11:05:53', '2026-10-02 11:05:53'),
(210, 41, 'Tilamol', NULL, 1, '2026-10-02 11:06:11', '2026-10-02 11:06:11'),
(211, 41, 'Assolda', NULL, 1, '2026-10-02 11:06:29', '2026-10-02 11:06:29'),
(212, 42, 'Bansai', NULL, 1, '2026-10-02 11:06:54', '2026-10-02 11:06:54'),
(213, 42, 'Curchorem', NULL, 1, '2026-10-02 11:07:15', '2026-10-02 11:07:15'),
(214, 42, 'Curchorem Market', NULL, 1, '2026-10-02 11:07:40', '2026-10-02 11:07:40'),
(215, 42, 'Cacora', NULL, 1, '2026-10-02 11:07:53', '2026-10-02 11:07:53'),
(216, 42, 'Kalay', NULL, 1, '2026-10-02 11:08:05', '2026-10-02 11:08:05'),
(217, 42, 'Pontemol', NULL, 1, '2026-10-02 11:08:21', '2026-10-02 11:08:21'),
(218, 43, 'Shigao', NULL, 1, '2026-10-02 11:08:49', '2026-10-02 11:08:49'),
(219, 43, 'Mollem', NULL, 1, '2026-10-02 11:09:04', '2026-10-02 11:09:04'),
(220, 43, 'Anmod', NULL, 1, '2026-10-02 11:09:20', '2026-10-02 11:09:20'),
(221, 43, 'Dharbandora', NULL, 1, '2026-10-02 11:09:41', '2026-10-02 11:09:41'),
(222, 43, 'Usgao', NULL, 1, '2026-10-02 11:09:59', '2026-10-02 11:09:59'),
(223, 43, 'Dabal', NULL, 1, '2026-10-02 11:10:12', '2026-10-02 11:10:12'),
(224, 44, 'Vichundrem', NULL, 1, '2026-10-02 11:10:39', '2026-10-02 11:10:39'),
(225, 44, 'Netravali', NULL, 1, '2026-10-02 11:10:54', '2026-10-02 11:10:54'),
(226, 44, 'Nune', NULL, 1, '2026-10-02 11:11:10', '2026-10-02 11:11:10'),
(227, 44, 'Gaodongrim', NULL, 1, '2026-10-02 11:11:35', '2026-10-02 11:11:35'),
(228, 44, 'Ugem', NULL, 1, '2026-10-02 11:11:51', '2026-10-02 11:11:51'),
(229, 44, 'Vadem', NULL, 1, '2026-10-02 11:12:05', '2026-10-02 11:12:05'),
(230, 45, 'Agonda', NULL, 1, '2026-10-02 11:12:23', '2026-10-02 11:12:23'),
(231, 45, 'Gullem', NULL, 1, '2026-10-02 11:12:40', '2026-10-02 11:12:40'),
(232, 45, 'Palolem', NULL, 1, '2026-10-02 11:13:02', '2026-10-02 11:13:02'),
(233, 45, 'Chaudi', NULL, 1, '2026-10-02 11:13:16', '2026-10-02 11:13:16'),
(234, 45, 'Bhatpal', NULL, 1, '2026-10-02 11:13:30', '2026-10-02 11:13:30'),
(235, 45, 'Pollem', NULL, 1, '2026-10-02 11:13:44', '2026-10-02 11:13:44'),
(236, 46, 'Mazali', NULL, 1, '2026-10-02 11:14:26', '2026-10-02 11:14:26'),
(237, 46, 'Devbag', NULL, 1, '2026-10-02 11:14:40', '2026-10-02 11:14:40'),
(238, 46, 'Asnoti', NULL, 1, '2026-10-02 11:14:55', '2026-10-02 11:14:55'),
(239, 46, 'Halga', NULL, 1, '2026-10-02 11:15:11', '2026-10-02 11:15:11'),
(240, 46, 'Kundra', NULL, 1, '2026-10-02 11:15:31', '2026-10-02 11:15:31'),
(241, 46, 'Mallapur', NULL, 1, '2026-10-02 11:15:45', '2026-10-02 11:15:45'),
(242, 47, 'Kodibag', NULL, 1, '2026-10-02 11:16:03', '2026-10-02 11:16:03'),
(243, 47, 'Karwar City', NULL, 1, '2026-10-02 11:16:21', '2026-10-02 11:16:21'),
(244, 47, 'Nadangadda', NULL, 1, '2026-10-02 11:16:50', '2026-10-02 11:16:50'),
(245, 47, 'Sunkeri', NULL, 1, '2026-10-02 11:17:12', '2026-10-02 11:17:12'),
(246, 47, 'Shirwad', NULL, 1, '2026-10-02 11:17:26', '2026-10-02 11:17:26'),
(247, 47, 'Binga', NULL, 1, '2026-10-02 11:17:42', '2026-10-02 11:17:42'),
(248, 49, 'Vazze', NULL, 1, '2026-10-02 11:36:29', '2026-10-02 11:36:29'),
(249, 49, 'Shiroda', NULL, 1, '2026-10-02 11:36:45', '2026-10-02 11:36:45'),
(250, 49, 'Panchwadi', NULL, 1, '2026-10-02 11:37:02', '2026-10-02 11:37:02'),
(251, 49, 'Durbhat', NULL, 1, '2026-10-02 11:37:32', '2026-10-02 11:37:42'),
(252, 49, 'Borim', NULL, 1, '2026-10-02 11:37:55', '2026-10-02 11:37:55'),
(253, 49, 'Bethora', NULL, 1, '2026-10-02 11:38:11', '2026-10-02 11:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, '2025-07-12 13:21:08', '2025-07-12 13:21:08'),
(2, 'Sales executive', '', '2025-07-23 05:54:41', '2025-07-28 06:24:10'),
(3, 'Driver', NULL, '2026-01-28 16:38:05', '2026-01-28 16:38:06'),
(4, 'Warehouse ', NULL, '2026-01-28 16:38:37', '2026-01-28 16:38:37'),
(5, 'Manufacture', NULL, '2026-09-22 07:00:06', '2026-09-22 07:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `sales_executive_area`
--

CREATE TABLE `sales_executive_area` (
  `id` int(11) NOT NULL,
  `sales_executive_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales_executive_area`
--

INSERT INTO `sales_executive_area` (`id`, `sales_executive_id`, `area_id`, `created_at`, `updated_at`) VALUES
(12, 41, 26, '2026-10-03 10:34:31', '2026-10-03 10:34:31'),
(13, 39, 43, '2026-10-03 10:34:39', '2026-10-03 10:34:39'),
(14, 40, 6, '2026-10-03 10:34:48', '2026-10-03 10:34:48'),
(15, 32, 7, '2026-10-05 08:17:10', '2026-10-05 08:17:10'),
(16, 32, 8, '2026-10-05 08:17:10', '2026-10-05 08:17:10');

-- --------------------------------------------------------

--
-- Table structure for table `sales_executive_route`
--

CREATE TABLE `sales_executive_route` (
  `id` int(11) NOT NULL,
  `sales_executive_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales_executive_route`
--

INSERT INTO `sales_executive_route` (`id`, `sales_executive_id`, `area_id`, `route_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '2026-09-24 10:36:07', '2026-09-24 10:36:07'),
(2, 2, 2, 4, '2026-09-24 10:36:07', '2026-09-24 10:36:07'),
(3, 2, 1, 4, '2026-09-24 10:40:41', '2026-09-24 10:40:41');

-- --------------------------------------------------------

--
-- Table structure for table `sales_visit_history`
--

CREATE TABLE `sales_visit_history` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `executive_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `shop_photo` varchar(555) DEFAULT NULL,
  `customer_note` longtext NOT NULL,
  `shop_requirement` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `skip_reason` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sales_visit_history`
--

INSERT INTO `sales_visit_history` (`id`, `shop_id`, `executive_id`, `status`, `shop_photo`, `customer_note`, `shop_requirement`, `created_at`, `updated_at`, `skip_reason`) VALUES
(1, 18, 32, 1, 'shn9Z95Td07b3Q.jpg', 'Test Note', 'soft drink', '2026-09-18 11:09:36', '2026-09-18 11:09:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `type` set('text','textarea','password','select','select-multiple','radio','checkbox','file') NOT NULL,
  `default` text NOT NULL,
  `value` text DEFAULT NULL,
  `options` text NOT NULL,
  `is_required` int(11) NOT NULL,
  `is_gui` int(11) NOT NULL,
  `module` varchar(50) NOT NULL,
  `row_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `slug`, `title`, `description`, `type`, `default`, `value`, `options`, `is_required`, `is_gui`, `module`, `row_order`) VALUES
(1, 'site_email', 'Site Email', 'Site Email', 'text', '', 'info@evolute.in', '', 1, 1, 'General', 1),
(2, 'site_contact', 'Site Contact', 'Site Contact', 'text', '', '+91 22 2671 6682', '', 1, 1, 'General', 3),
(3, 'facebook_url', 'Facebook', '', 'text', 'https://www.facebook.com/', 'https://www.facebook.com/MCSaxena/', '', 1, 1, 'Social Link', 1),
(4, 'twitter_url', 'Twitter', '', 'text', 'https://twitter.com/', 'https://twitter.com/DrMCSGOC', '', 1, 1, 'Social Link', 2),
(5, 'instagram_url', 'Instagram', '', 'text', 'https://instagram.com/', NULL, '', 1, 1, 'Social Link', 3),
(6, 'site_title', 'Site Title', 'Site Title', 'text', 'demo', 'Desai', '', 1, 1, 'General', 5),
(7, 'site_logo', 'Site Logo White', 'Site Logo White', 'file', '', 'logo-fav.jpg', '', 1, 1, 'Image', 1),
(8, 'site_favicon', 'Site Favicon', 'Site Favicon', 'file', '', 'logo-fav.jpg', '', 1, 1, 'Image', 2),
(9, 'super_admin_email', 'Super Admin Email', '', 'text', '', 'albert102@yopmail.com', '', 1, 1, 'General', 6),
(10, 'youtube_url', 'Youtube', '', 'text', '', 'https://www.youtube.com/channel/UC1iZipj-84QnPNhsc7gQDyw/videos', '', 1, 1, 'Social Link', 4),
(13, 'site_shot_desc', 'Short Description of the Site', 'Short Description of the Site', 'textarea', '', 'With a legacy that pans four decades, Evolute Group has established itself as a leading Electronic Technology Conglomerate.', '', 1, 1, 'General', 7),
(14, 'address', 'Address', 'Address', 'textarea', '', '801, Grande Eddifice, Akruli Road, Kandivali east, Mumbai ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œ 400101', '', 1, 1, 'General', 8),
(15, 'site_logo_dark', 'Site Logo Dark', 'Site Logo Dark', 'file', '', 'logo-fav.jpg', '', 1, 1, 'Image', 3),
(18, 'maintainence_mode', 'Maintainence Mode', 'Maintainence Mode', 'radio', 'Yes', '0', '1=ON|0=OFF', 1, 1, 'Maintainence', 2);

-- --------------------------------------------------------

--
-- Table structure for table `shop_delivery_history`
--

CREATE TABLE `shop_delivery_history` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_status` int(11) NOT NULL DEFAULT 0,
  `selfie` varchar(555) DEFAULT NULL,
  `delivery_note` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shop_delivery_history`
--

INSERT INTO `shop_delivery_history` (`id`, `shop_id`, `driver_id`, `delivery_date`, `delivery_status`, `selfie`, `delivery_note`, `created_at`, `updated_at`) VALUES
(1, 10, 33, '2026-09-04', 2, NULL, NULL, '2026-09-04 13:22:04', '2026-09-04 13:22:04'),
(2, 11, 33, '2026-09-04', 1, 'q62EwU93szcHZC.jpg', NULL, '2026-09-04 13:46:57', '2026-09-04 13:46:57'),
(3, 12, 33, '2026-09-04', 1, 'oD2uRbtHLF3h5m.jpg', NULL, '2026-09-04 14:28:00', '2026-09-04 14:28:00'),
(4, 15, 33, '2026-09-05', 1, 'N6RCdJ600KYa95.jpg', NULL, '2026-09-05 06:50:56', '2026-09-05 06:50:56'),
(5, 10, 33, '2026-09-06', 1, 'M7aq7OL4iT8CRm.jpg', NULL, '2026-09-05 18:51:23', '2026-09-05 18:51:23'),
(6, 11, 33, '2026-09-06', 1, 'zMiT8x7wDsSkpI.jpg', NULL, '2026-09-06 07:25:08', '2026-09-06 07:25:08'),
(7, 12, 33, '2026-09-06', 1, '8UB1wH89NPMx8R.jpg', NULL, '2026-09-06 07:38:09', '2026-09-06 07:38:09'),
(8, 13, 33, '2026-09-06', 1, '081Lzf8IwltZb2.jpg', NULL, '2026-09-06 09:33:00', '2026-09-06 09:33:00'),
(9, 1, 33, '2026-08-09', 0, NULL, NULL, '2026-09-07 13:24:47', '2026-09-07 13:24:47'),
(10, 1, 33, '2026-08-09', 2, NULL, NULL, '2026-09-07 13:25:03', '2026-09-07 13:25:03'),
(11, 1, 33, '2026-08-09', 0, NULL, NULL, '2026-09-07 13:26:15', '2026-09-07 13:26:15'),
(12, 1, 33, '2026-08-09', 2, NULL, NULL, '2026-09-07 13:26:24', '2026-09-07 13:26:24'),
(13, 1, 33, '2026-08-09', 0, NULL, NULL, '2026-09-07 13:26:41', '2026-09-07 13:26:41'),
(14, 1, 33, '2026-08-09', 2, NULL, NULL, '2026-09-07 13:26:47', '2026-09-07 13:26:47'),
(15, 16, 33, '2026-09-07', 0, NULL, NULL, '2026-09-07 13:27:32', '2026-09-07 13:27:32'),
(16, 1, 33, '2026-08-09', 0, NULL, NULL, '2026-09-07 13:27:57', '2026-09-07 13:27:57'),
(17, 12, 33, '2026-09-07', 0, NULL, NULL, '2026-09-07 13:52:27', '2026-09-07 13:52:27'),
(18, 13, 33, '2026-09-07', 1, '181hfJzgMICP99.jpg', NULL, '2026-09-07 13:52:58', '2026-09-07 13:52:58'),
(19, 15, 33, '2026-09-07', 0, NULL, NULL, '2026-09-07 16:37:11', '2026-09-07 16:37:11'),
(20, 10, 33, '2026-09-07', 2, NULL, NULL, '2026-09-07 16:37:50', '2026-09-07 16:37:50'),
(21, 11, 33, '2026-09-07', 1, 'E4UC9q8bRKPa9M.jpg', NULL, '2026-09-07 16:38:19', '2026-09-07 16:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified` enum('0','1') NOT NULL DEFAULT '0',
  `email` varchar(555) NOT NULL,
  `phone_verified` int(11) NOT NULL DEFAULT 0,
  `email_otp` varchar(10) DEFAULT NULL,
  `phone_otp` varchar(10) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `activation_token` text DEFAULT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email_verified`, `email`, `phone_verified`, `email_otp`, `phone_otp`, `email_verified_at`, `password`, `image`, `remember_token`, `activation_token`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(30, 'Amesha Pandhari Tari', '9021154878', '0', 'tariamesha98@gmail.com', 0, NULL, NULL, NULL, '$2y$10$bzzDlwF//dJcwqt5YLZYYu4xR1GxkU51SDjlq5iCTVMYYoBjJA31a', NULL, NULL, NULL, '0', NULL, '2025-09-09 16:10:51', '2025-09-09 16:10:51'),
(31, 'testkashish naik', '7798533795', '0', 'naik.kashish@gmail.com', 0, NULL, NULL, NULL, '$2y$10$BukrD78dMELJP66.6LLKgeZ0DRfR/vu68dzzwblXur9H7fBB28kt2', NULL, NULL, NULL, '0', NULL, '2025-09-26 11:10:40', '2025-09-26 11:10:40'),
(32, 'RAFIGULLA SHIKILGAR', '7757015589', '0', 'shaikrafi7322@gmail.com', 0, NULL, NULL, NULL, '$2y$10$BLxv4rZtE2SjclupT8cAweIfU8Z9Ks5u19Uj8GvjuhOlVm1uIxxJS', NULL, NULL, NULL, '0', NULL, '2025-10-11 19:29:15', '2025-10-11 19:29:15'),
(33, 'Vaibhavi', '9158187034', '0', 'arondekarvaibhavi@gmail.com', 0, NULL, NULL, NULL, '$2y$10$j/7QUBKVR/4bWbbN.lZwguYUxW9eCqo1jNNs/pyiv83fAg98mMSZ2', NULL, NULL, NULL, '0', NULL, '2025-10-13 19:02:04', '2025-10-13 19:02:04'),
(34, 'Sarvesh', '9049665756', '0', 'sarveshnaik8800@gmail.com', 0, NULL, NULL, NULL, '$2y$10$HP7Ux88gIKrUWjxw03Kd8OMrHwQsHxyG8i2JSlEjcVpcpoXcBMKn2', NULL, NULL, NULL, '0', NULL, '2025-10-17 11:18:44', '2025-10-17 11:18:44'),
(35, 'Deeptesh Naik', '9637721972', '0', 'dipsynaik72@gmail.com', 0, NULL, NULL, NULL, '$2y$10$gbaiH3K3hHsf0q2jeVrx4OCYeT9a2A41gJxCGxdTO/kTEBde6iL0e', NULL, NULL, NULL, '0', NULL, '2025-10-30 21:20:15', '2025-10-30 21:20:15'),
(36, 'Deepak Madhav Borker', '9850459368', '0', 'deepakborker27968@gmail.com', 0, NULL, NULL, NULL, '$2y$10$XznGz6COAw3HX8LhhtNeGezjxwhULPysxiIlNIpp8FpA8l6maBcPG', NULL, NULL, NULL, '0', NULL, '2025-11-05 16:48:39', '2025-11-05 16:48:39'),
(37, 'Ankush jaidev satodkar', '8390811735', '0', 'jaidevsatodkar@gmail.com', 0, NULL, NULL, NULL, '$2y$10$Ybq1whHZXcmsUQsuenzQUOckGF3q.b3UGDstfF4YaBd3szWxj6i0S', NULL, NULL, NULL, '0', NULL, '2025-11-07 19:32:22', '2025-11-07 19:32:22'),
(38, 'Sushanti Shamba Kudav', '9923763903', '0', 'spruhakudav34@gmail.com', 0, NULL, NULL, NULL, '$2y$10$5EGSGKqH7eBLMcbgVR8Ije9YCfQ.r4IyITFhYCXv.U5WkWbPFTyX2', NULL, NULL, NULL, '0', NULL, '2025-11-08 23:31:09', '2025-11-08 23:31:09'),
(39, 'Nita Nandkishor Naik', '8669133699', '0', 'nikhilnaik2405@gmail.com', 0, NULL, NULL, NULL, '$2y$10$ySpV.UORDqLMKwsDwx6hJ.Qn.pWujl9.ggQfT1c.hPCmv4i/ceR2G', NULL, NULL, NULL, '0', NULL, '2025-11-09 11:45:29', '2025-11-09 11:45:29'),
(40, 'Sulabh International Social Service Organisation', '9145037067', '0', 'goa@sulabhinternational.org', 0, NULL, NULL, NULL, '$2y$10$ivH.SJMk1K8CBiAAQuwAmuKd.PHXpaGLxRysFta6UKz1tVuFSsat6', NULL, NULL, NULL, '0', NULL, '2025-12-12 12:35:19', '2025-12-12 12:35:19'),
(41, 'Sanjana Sunil Gaonkar', '9158440626', '0', 'sanjanagaonkar@gmail.com', 0, NULL, NULL, NULL, '$2y$10$.2.HVox6WUI2.uIifxjKk.UO/5KtgJ.J3ekpG.nRMblTrI0u1S9EC', NULL, NULL, NULL, '0', NULL, '2025-12-12 13:13:56', '2025-12-12 13:13:56'),
(42, 'ASMA NISAR SHAIKH', '7666724225', '0', 'asmashaikh5394@gmail.com', 0, NULL, NULL, NULL, '$2y$10$ERDeRdhLCl6e2GdVLKrH1OL9WvAFcWtJam362Dm8sW.qDEI3RkyWm', NULL, NULL, NULL, '0', NULL, '2025-12-17 11:06:06', '2025-12-17 11:06:06'),
(43, 'Yusuf Ali Khan', '9021319831', '0', 'fouziyak827@gmail.com', 0, NULL, NULL, NULL, '$2y$10$G.pA3v5sqkIzheFDb9p3D.SIpIlfPnlrZPu/20DYkA8mz9Zp22CKC', NULL, NULL, NULL, '0', NULL, '2025-12-22 16:15:25', '2025-12-22 16:15:25'),
(44, 'Chandru Rama Narulkar', '9545456872', '0', 'chandrunarulkar@gmail.com', 0, NULL, NULL, NULL, '$2y$10$kORmIAmtBrPgMWAwq6I/R.pNst0p6uTLGYPaJ23KFpSScka3Ii/yC', NULL, NULL, NULL, '0', NULL, '2025-12-23 15:43:24', '2025-12-23 15:43:24'),
(45, 'Nakul Fondu Gawas', '8262823208', '0', 'nakulgawas@gmail.com', 0, NULL, NULL, NULL, '$2y$10$NxieZOt3yF8.ImFrDWX5AunWhFALoP/0uKexc8h9ArZy5wFfdKKSa', NULL, NULL, NULL, '0', NULL, '2025-12-23 15:49:19', '2025-12-23 15:49:19'),
(46, 'Mohini Mohandas Gaonkar', '7263851330', '0', 'mohinigaonkar@gmail.com', 0, NULL, NULL, NULL, '$2y$10$60nEZhP8RMeFFxJuVLP6z.HEKqVSsvuCwro2c2x9Lk7evRwc/Y4EW', NULL, NULL, NULL, '0', NULL, '2025-12-24 12:22:37', '2025-12-24 12:22:37'),
(47, 'SUMITRA ULHAS GAWANDE', '9527332343', '0', 'alihanal143@gmail.com', 0, NULL, NULL, NULL, '$2y$10$sPqN.NCEc6XIHKZ4RCQ5MepegFCyBAqD2jj5MBn.kNp4TNg4xV.SK', NULL, NULL, NULL, '0', NULL, '2025-12-30 14:00:25', '2025-12-30 14:00:25'),
(48, 'Narendra  Gaonkar', '7020464933', '0', 'Narndragaonkar@Gmail.com', 0, NULL, NULL, NULL, '$2y$10$UxpxCfPdvOECLOnu7D/DDekZjNEK.V4UVcOFPxcjUuwZu0llY8tJq', NULL, NULL, NULL, '0', NULL, '2026-01-02 14:52:50', '2026-01-02 14:52:50'),
(49, 'Antonio Fernandes', '8088590546', '0', 'fernandeslucia323@gmail.com', 0, NULL, NULL, NULL, '$2y$10$Uvt/i3JRytR7x0kwbhrpdO.j1sORksrPl1/KDVVcnA5ahVQjf0N7.', NULL, NULL, NULL, '0', NULL, '2026-01-06 18:44:27', '2026-01-06 18:44:27'),
(50, 'Marilyn Devina Joaquina Fernnades', '7741831603', '0', 'Marilyndevina@gmail.com', 0, NULL, NULL, NULL, '$2y$10$UJHnNvjs7aWX.DAKl3.awONrBL6rYfX7j6yc6sJl3DGRcSJE/5K9O', NULL, NULL, NULL, '0', NULL, '2026-01-07 17:08:44', '2026-01-07 17:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `vehicle_name` varchar(555) NOT NULL,
  `vehicle_no` varchar(555) NOT NULL,
  `fuel_type` varchar(555) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vehicle`
--

INSERT INTO `vehicle` (`id`, `vehicle_name`, `vehicle_no`, `fuel_type`, `status`, `created_at`, `updated_at`) VALUES
(6, 'ECCO CARGO', 'GA 09 6650', 'Petrol', 1, '2026-10-02 11:35:04', '2026-10-02 11:35:04'),
(7, 'ECCO CARGO', 'GA 09 6390', 'Petrol', 1, '2026-10-02 11:39:04', '2026-10-02 11:39:04'),
(8, 'ECCO CARGO', 'GA 09 6410', 'Petrol', 1, '2026-10-02 11:39:53', '2026-10-02 11:39:53'),
(9, 'ECCO CARGO', 'GA 09 6219', 'Petrol', 1, '2026-10-02 11:40:16', '2026-10-02 11:40:16'),
(10, 'ECCO CARGO', 'GA 09 6391', 'Petrol', 1, '2026-10-02 11:40:42', '2026-10-02 11:40:42'),
(11, 'ECCO CARGO', 'GA 09 6364', 'Petrol', 1, '2026-10-02 11:41:08', '2026-10-02 11:41:08'),
(12, 'ECCO CARGO', 'GA 09 7236', 'Petrol', 1, '2026-10-02 11:41:27', '2026-10-02 11:41:27'),
(13, 'ECCO CARGO', 'GA 09 6436', 'Petrol', 1, '2026-10-02 11:41:45', '2026-10-02 11:41:45'),
(14, 'ECCO CARGO', 'GA 09 7194', 'Petrol', 1, '2026-10-02 11:42:06', '2026-10-02 11:42:06'),
(15, 'ECCO CARGO', 'GA 09 6496', 'Petrol', 1, '2026-10-02 11:43:25', '2026-10-02 11:43:25'),
(16, 'ECCO CARGO', 'GA 09 7235', 'Petrol', 1, '2026-10-02 11:43:45', '2026-10-02 11:43:45'),
(17, 'ECCO CARGO', 'GA 09 6372', 'Petrol', 1, '2026-10-02 11:44:15', '2026-10-02 11:44:15'),
(18, 'ECCO CARGO', 'GA 09 6848', 'Petrol', 1, '2026-10-02 11:44:38', '2026-10-02 11:44:38'),
(19, 'ECCO CARGO', 'GA 09 7238', 'Petrol', 1, '2026-10-02 11:45:00', '2026-10-02 11:45:00'),
(20, 'ECCO CARGO', 'GA 09 6505', 'Petrol', 1, '2026-10-02 11:45:16', '2026-10-02 11:45:16'),
(21, 'ECCO CARGO', 'GA 09 6642', 'Petrol', 1, '2026-10-02 11:45:36', '2026-10-02 11:45:36'),
(22, 'ECCO CARGO', 'GA 09 6662', 'Petrol', 1, '2026-10-02 11:45:54', '2026-10-02 11:45:54'),
(23, 'ECCO CARGO', 'GA 09 6660', 'Petrol', 1, '2026-10-02 11:46:23', '2026-10-02 11:46:23'),
(24, 'ECCO CARGO', 'GA 09 7234', 'Petrol', 1, '2026-10-02 11:46:43', '2026-10-02 11:46:43'),
(25, 'ECCO CARGO', 'GA 09 7237', 'Petrol', 1, '2026-10-02 11:47:03', '2026-10-02 11:47:03'),
(26, 'ECCO CARGO', 'GA 09 6504', 'Petrol', 1, '2026-10-02 11:47:26', '2026-10-02 11:47:26'),
(27, 'ECCO CARGO', 'GA 09 6492', 'Petrol', 1, '2026-10-02 11:47:47', '2026-10-02 11:47:47'),
(28, 'ECCO CARGO', 'GA 09 6619', 'Petrol', 1, '2026-10-02 11:48:13', '2026-10-02 11:48:13'),
(29, 'ECCO CARGO', 'GA 09 7195', 'Petrol', 1, '2026-10-02 11:48:39', '2026-10-02 11:48:39'),
(30, 'ECCO CARGO', 'GA 09 6837', 'Petrol', 1, '2026-10-02 11:48:57', '2026-10-02 11:48:57'),
(31, 'ECCO CARGO', 'GA 09 6691', 'Petrol', 1, '2026-10-02 11:49:14', '2026-10-02 11:49:14'),
(32, 'ECCO CARGO', 'GA 09 6617', 'Petrol', 1, '2026-10-02 11:49:31', '2026-10-02 11:49:31'),
(33, 'ECCO CARGO', 'GA 09 6527', 'Petrol', 1, '2026-10-02 11:50:30', '2026-10-02 11:50:30'),
(34, 'ECCO CARGO', 'GA 09 6847', 'Petrol', 1, '2026-10-02 11:50:55', '2026-10-02 11:50:55'),
(35, 'ECCO CARGO', 'GA 09 6838', 'Petrol', 1, '2026-10-02 11:51:20', '2026-10-02 11:51:20'),
(36, 'ECCO CARGO', 'GA 09 6898', 'Petrol', 1, '2026-10-02 11:51:36', '2026-10-02 11:51:36'),
(37, 'ECCO CARGO', 'GA 09 6766', 'Petrol', 1, '2026-10-02 11:51:54', '2026-10-02 11:51:54'),
(38, 'ECCO CARGO', 'GA 09 7065', 'Petrol', 1, '2026-10-02 11:52:52', '2026-10-02 11:52:52'),
(39, 'ECCO CARGO', 'GA 09 6768', 'Petrol', 1, '2026-10-02 11:53:18', '2026-10-02 11:53:18'),
(40, 'ECCO CARGO', 'GA 09 6761', 'Petrol', 1, '2026-10-02 11:53:36', '2026-10-02 11:53:36'),
(41, 'ECCO CARGO', 'GA 09 6954', 'Petrol', 1, '2026-10-02 11:54:01', '2026-10-02 11:54:01'),
(42, 'ECCO CARGO', 'GA 09 6929', 'Petrol', 1, '2026-10-02 11:54:21', '2026-10-02 11:54:21'),
(43, 'ECCO CARGO', 'GA 09 6763', 'Petrol', 1, '2026-10-02 11:54:39', '2026-10-02 11:54:39'),
(44, 'ECCO CARGO', 'GA 09 6549', 'Petrol', 1, '2026-10-02 11:55:08', '2026-10-02 11:55:08'),
(45, 'ECCO CARGO', 'GA 09 6423', 'Petrol', 1, '2026-10-02 11:55:28', '2026-10-02 11:55:28');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_invoice`
--

CREATE TABLE `warehouse_invoice` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `shop_name` varchar(555) NOT NULL,
  `contact_no` varchar(555) NOT NULL,
  `email` varchar(555) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `grand_total` varchar(555) DEFAULT NULL,
  `file_name` varchar(555) DEFAULT NULL,
  `path` longtext DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `gst_no` varchar(555) DEFAULT NULL,
  `pan_no` varchar(555) DEFAULT NULL,
  `state` varchar(555) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `warehouse_invoice`
--

INSERT INTO `warehouse_invoice` (`id`, `warehouse_id`, `shop_name`, `contact_no`, `email`, `created_at`, `updated_at`, `grand_total`, `file_name`, `path`, `address`, `gst_no`, `pan_no`, `state`) VALUES
(1, 35, 'Marie Hayes', '177', 'data39150@gmail.com', '2026-09-28 06:34:22', '2026-09-28 06:34:23', '15240', '1790577262-marie-hayes_28-09-2026.pdf', 'https://desai.besthr.in/public/invoice/1790577262-marie-hayes_28-09-2026.pdf', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_invoice_data`
--

CREATE TABLE `warehouse_invoice_data` (
  `id` int(11) NOT NULL,
  `warehouse_invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` varchar(555) NOT NULL,
  `unit` varchar(555) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `warehouse_invoice_data`
--

INSERT INTO `warehouse_invoice_data` (`id`, `warehouse_invoice_id`, `product_id`, `quantity`, `unit`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '60', 'bottle', '2026-09-28 06:34:22', '2026-09-28 06:34:22'),
(2, 1, 3, '50', 'bottle', '2026-09-28 06:34:22', '2026-09-28 06:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_products`
--

CREATE TABLE `warehouse_products` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `per_case_quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `comment` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `warehouse_products`
--

INSERT INTO `warehouse_products` (`id`, `warehouse_id`, `product_id`, `per_case_quantity`, `status`, `comment`, `created_at`, `updated_at`) VALUES
(1, 35, 1, 30, 1, 'Product recived', '2026-09-22 11:44:00', '2026-09-23 12:49:03'),
(2, 35, 3, 10, 0, NULL, '2026-09-22 11:44:00', '2026-09-22 11:44:00'),
(3, 35, 4, 40, 1, 'Product Recived', '2026-09-22 11:44:00', '2026-09-23 12:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_stock`
--

CREATE TABLE `warehouse_stock` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `warehouse_stock`
--

INSERT INTO `warehouse_stock` (`id`, `warehouse_id`, `product_id`, `stock`, `created_at`, `updated_at`) VALUES
(1, 35, 4, 30, '2026-09-23 12:48:15', '2026-09-26 10:01:39'),
(2, 35, 1, 15, '2026-09-23 12:49:03', '2026-09-26 10:01:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_inquiries`
--
ALTER TABLE `customer_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_products`
--
ALTER TABLE `driver_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_product_details`
--
ALTER TABLE `driver_product_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_punch_in`
--
ALTER TABLE `driver_punch_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_route`
--
ALTER TABLE `driver_route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_warehouse`
--
ALTER TABLE `driver_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_content`
--
ALTER TABLE `email_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_data`
--
ALTER TABLE `invoice_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_history`
--
ALTER TABLE `invoice_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `road`
--
ALTER TABLE `road`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_executive_area`
--
ALTER TABLE `sales_executive_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_executive_route`
--
ALTER TABLE `sales_executive_route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_visit_history`
--
ALTER TABLE `sales_visit_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slug` (`slug`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `shop_delivery_history`
--
ALTER TABLE `shop_delivery_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_invoice`
--
ALTER TABLE `warehouse_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_invoice_data`
--
ALTER TABLE `warehouse_invoice_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_products`
--
ALTER TABLE `warehouse_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `customer_inquiries`
--
ALTER TABLE `customer_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `driver_products`
--
ALTER TABLE `driver_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `driver_product_details`
--
ALTER TABLE `driver_product_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `driver_punch_in`
--
ALTER TABLE `driver_punch_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `driver_route`
--
ALTER TABLE `driver_route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `driver_warehouse`
--
ALTER TABLE `driver_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_content`
--
ALTER TABLE `email_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `invoice_data`
--
ALTER TABLE `invoice_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `invoice_history`
--
ALTER TABLE `invoice_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1536;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `road`
--
ALTER TABLE `road`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales_executive_area`
--
ALTER TABLE `sales_executive_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales_executive_route`
--
ALTER TABLE `sales_executive_route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales_visit_history`
--
ALTER TABLE `sales_visit_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `shop_delivery_history`
--
ALTER TABLE `shop_delivery_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `warehouse_invoice`
--
ALTER TABLE `warehouse_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouse_invoice_data`
--
ALTER TABLE `warehouse_invoice_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouse_products`
--
ALTER TABLE `warehouse_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
