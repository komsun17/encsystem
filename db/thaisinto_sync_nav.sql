-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Sep 13, 2025 at 02:31 AM
-- Server version: 5.7.44
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thaisinto_sync_nav`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesslogs`
--

CREATE TABLE `accesslogs` (
  `l_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `logindate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `c_id` int(11) NOT NULL,
  `nav_id` varchar(20) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `search_name` varchar(50) NOT NULL,
  `customer_name_2` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `address_2` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `post_code` varchar(20) NOT NULL,
  `country_region_code` varchar(20) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `phone_no` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `last_modified` bigint(20) NOT NULL,
  `synced_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_mapping`
--

CREATE TABLE `customer_mapping` (
  `id` int(11) NOT NULL,
  `nav_id` varchar(20) DEFAULT NULL,
  `system_name` varchar(50) DEFAULT NULL,
  `system_customer_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `system_customer_name` varchar(255) DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `sync_status` varchar(20) DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_note`
--

CREATE TABLE `customer_note` (
  `n_id` int(11) NOT NULL,
  `nav_id` varchar(20) NOT NULL,
  `note` text NOT NULL,
  `n_date` datetime NOT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `topic_detail` varchar(255) DEFAULT NULL,
  `ti_sales` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_balance`
--

CREATE TABLE `item_balance` (
  `item_no` varchar(20) NOT NULL,
  `item_description` varchar(100) DEFAULT NULL,
  `location_code` varchar(20) NOT NULL,
  `uom` varchar(20) DEFAULT '''''',
  `total_remaining_qty` decimal(18,4) DEFAULT NULL,
  `image_url` text,
  `last_modified` bigint(20) UNSIGNED NOT NULL,
  `synced_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_header`
--

CREATE TABLE `sales_header` (
  `sh_id` int(11) NOT NULL,
  `doc_no` varchar(20) NOT NULL,
  `document_type` int(11) NOT NULL,
  `nav_id` varchar(20) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `posting_date` datetime NOT NULL,
  `order_date` datetime NOT NULL,
  `document_date` datetime NOT NULL,
  `external_doc_no` varchar(50) NOT NULL,
  `project_code` varchar(50) DEFAULT NULL,
  `last_modified` bigint(20) NOT NULL,
  `synced_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_line`
--

CREATE TABLE `sales_line` (
  `sl_id` int(11) NOT NULL,
  `doc_no` varchar(20) NOT NULL,
  `line_no` varchar(20) NOT NULL,
  `item_no` varchar(20) NOT NULL,
  `item_description` varchar(50) NOT NULL,
  `quantity` decimal(38,20) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `project_code` varchar(20) NOT NULL,
  `nav_id` varchar(10) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `posting_date` datetime NOT NULL,
  `last_modified` bigint(20) NOT NULL,
  `synced_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sync_status`
--

CREATE TABLE `sync_status` (
  `view_name` varchar(100) NOT NULL,
  `last_sync_time` datetime NOT NULL,
  `last_sync_bigint` bigint(20) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('superadmin','admin') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_sales_header_project_count`
-- (See below for the actual view)
--
CREATE TABLE `vw_sales_header_project_count` (
`project_type` varchar(13)
,`project_count` bigint(21)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesslogs`
--
ALTER TABLE `accesslogs`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`c_id`),
  ADD UNIQUE KEY `idx_nav_id` (`nav_id`);

--
-- Indexes for table `customer_mapping`
--
ALTER TABLE `customer_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_name` (`system_name`,`system_customer_id`),
  ADD KEY `fk_nav_id` (`nav_id`);

--
-- Indexes for table `customer_note`
--
ALTER TABLE `customer_note`
  ADD PRIMARY KEY (`n_id`),
  ADD KEY `nav_id` (`nav_id`);

--
-- Indexes for table `item_balance`
--
ALTER TABLE `item_balance`
  ADD PRIMARY KEY (`item_no`,`location_code`);

--
-- Indexes for table `sales_header`
--
ALTER TABLE `sales_header`
  ADD PRIMARY KEY (`sh_id`);

--
-- Indexes for table `sales_line`
--
ALTER TABLE `sales_line`
  ADD PRIMARY KEY (`sl_id`);

--
-- Indexes for table `sync_status`
--
ALTER TABLE `sync_status`
  ADD PRIMARY KEY (`view_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesslogs`
--
ALTER TABLE `accesslogs`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_mapping`
--
ALTER TABLE `customer_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_note`
--
ALTER TABLE `customer_note`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_header`
--
ALTER TABLE `sales_header`
  MODIFY `sh_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_line`
--
ALTER TABLE `sales_line`
  MODIFY `sl_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `vw_sales_header_project_count`
--
DROP TABLE IF EXISTS `vw_sales_header_project_count`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_sales_header_project_count`  AS SELECT 'sales_service' AS `project_type`, count(distinct `sales_header`.`project_code`) AS `project_count` FROM `sales_header` WHERE ((`sales_header`.`project_code` like 'M%') AND (substr(`sales_header`.`project_code`,3,2) = convert(right(year(curdate()),2) using utf8)))union all select 'sales_part' AS `project_type`,count(distinct `sales_header`.`project_code`) AS `project_count` from `sales_header` where ((`sales_header`.`project_code` like 'P%') and (substr(`sales_header`.`project_code`,3,2) = convert(right(year(curdate()),2) using utf8))) union all select 'sales_machine' AS `project_type`,count(distinct `sales_header`.`project_code`) AS `project_count` from `sales_header` where ((`sales_header`.`project_code` like 'B%') and (substr(`sales_header`.`project_code`,3,2) = convert(right(year(curdate()),2) using utf8)))  ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_mapping`
--
ALTER TABLE `customer_mapping`
  ADD CONSTRAINT `fk_nav_id` FOREIGN KEY (`nav_id`) REFERENCES `customer` (`nav_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
