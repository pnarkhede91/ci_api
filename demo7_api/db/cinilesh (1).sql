-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2017 at 03:43 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinilesh`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_app_users`
--

CREATE TABLE `tbl_app_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_app_users`
--

INSERT INTO `tbl_app_users` (`id`, `name`, `email`, `password`, `created`, `modified`) VALUES
(1, 'admin', 'admin@test.com', 'e10adc3949ba59abbe56e057f20f883e', '2017-07-18 00:00:00', '2017-07-18 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank`
--

CREATE TABLE `tbl_bank` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `branch_name` varchar(150) NOT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `account_name` varchar(250) NOT NULL,
  `account_number` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_bank`
--

INSERT INTO `tbl_bank` (`id`, `user_id`, `bank_name`, `branch_name`, `ifsc_code`, `account_name`, `account_number`, `created`, `modified`) VALUES
(1, 35, 'canara bank', 'gangapur road', 'ICIC0001872', 'saving', '43563323233344343', '2017-07-24 02:43:57', '2017-07-24 02:46:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brands`
--

CREATE TABLE `tbl_brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `image` text,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1-active ,0-Inactive',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '1-delete,0-not delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_brands`
--

INSERT INTO `tbl_brands` (`id`, `name`, `image`, `is_active`, `is_delete`, `created`, `modified`) VALUES
(1, 'Brand Name', NULL, 0, 1, '2017-10-01 02:41:40', '2017-10-29 03:22:15'),
(2, 'ss', NULL, 1, 1, '2017-10-01 02:42:30', '2017-10-01 02:42:30'),
(3, 'sds', NULL, 1, 1, '2017-10-01 02:42:44', '2017-10-01 02:42:44'),
(4, 'scs', NULL, 1, 1, '2017-10-01 02:44:47', '2017-10-01 02:44:47'),
(5, 'ghfgb', NULL, 1, 1, '2017-10-01 02:45:19', '2017-10-01 02:45:31'),
(6, 'wdwdwsd', NULL, 1, 1, '2017-10-01 02:46:48', '2017-10-01 02:46:48'),
(7, 'dsd', NULL, 1, 1, '2017-10-01 02:48:17', '2017-10-01 02:48:17'),
(8, 'Brand Name2', 'uploads/1029_024226download.jpg', 1, 0, '2017-10-03 03:30:49', '2017-10-29 02:42:26'),
(9, 'brand name', NULL, 1, 1, '2017-10-03 03:54:46', '2017-10-03 03:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(100) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`id`, `product_id`, `user_id`, `ip_address`, `created`) VALUES
(1, 1, 0, '::1', '2017-11-12 01:52:04'),
(2, 1, 0, '::1', '2017-11-12 02:01:37'),
(3, 1, 0, '::1', '2017-11-12 03:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1-active ,0-Inactive',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '1-delete,0-not delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `brand_id`, `name`, `is_active`, `is_delete`, `created`, `modified`) VALUES
(1, 8, 'category 1', 1, 0, '2017-10-03 04:01:21', '2017-10-03 04:01:21'),
(2, 8, 'category', 1, 1, '2017-10-03 04:01:28', '2017-10-03 04:01:28'),
(3, 8, 'category 2', 1, 0, '2017-10-03 04:27:11', '2017-10-03 04:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city`
--

CREATE TABLE `tbl_city` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_city`
--

INSERT INTO `tbl_city` (`id`, `country_id`, `state_id`, `name`, `is_active`) VALUES
(1, 1, 1, 'Nashik', 1),
(2, 1, 1, 'Mumbai', 1),
(3, 1, 1, 'Pune', 1),
(4, 1, 1, 'Kolhapur', 1),
(5, 1, 1, 'Aurangabad', 1),
(6, 1, 1, 'Thane', 1),
(7, 1, 1, 'Ahmadnagar', 1),
(8, 1, 1, 'Amaravati', 1),
(9, 1, 1, 'Delhi', 1),
(10, 1, 1, 'Bangalore', 1),
(11, 1, 1, 'Nagpur', 1),
(12, 1, 1, 'Jaipur', 1),
(13, 1, 4, 'Surat', 1),
(14, 1, 4, 'Baroda', 1),
(15, 1, 4, 'Patan', 1),
(16, 1, 4, 'Morvi', 1),
(18, 1, 5, 'Rajkot', 1),
(19, 1, 1, 'sdxsds', 1),
(20, 1, 5, 'cdxc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country`
--

CREATE TABLE `tbl_country` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_country`
--

INSERT INTO `tbl_country` (`id`, `name`, `is_active`) VALUES
(1, 'India', 1),
(2, 'US', 1),
(3, 'UK', 1),
(4, 'uk1', 1),
(5, 'sdsd', 1),
(6, 'cscs', 1),
(7, 'dfdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `product_name` varchar(150) DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text,
  `discount_price` double(10,2) NOT NULL,
  `discount_start_date` date DEFAULT NULL,
  `discount_end_date` date DEFAULT NULL,
  `product_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Latest Product, 2-Best Seller Product, 3-Featured Product',
  `product_image` text,
  `product_video` text,
  `admin_approved` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0-No,1-Yes',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active 0-In Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`id`, `brand_id`, `category_id`, `sub_category_id`, `product_name`, `price`, `quantity`, `description`, `discount_price`, `discount_start_date`, `discount_end_date`, `product_type`, `product_image`, `product_video`, `admin_approved`, `is_active`, `is_delete`, `created`, `modified`) VALUES
(1, 8, 3, 3, 'product name', 1250.00, 23, 'test', 56.76, '2017-10-09', '2017-10-27', 1, NULL, NULL, 1, 1, 0, '2017-10-29 11:07:35', '2017-10-29 11:07:35'),
(2, 8, 1, 4, 'women\'s top', 300.00, 12, 'test', 250.00, '2017-11-01', '2017-11-23', 1, NULL, NULL, 1, 1, 0, '2017-11-11 03:18:17', '2017-11-11 03:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_img_video`
--

CREATE TABLE `tbl_product_img_video` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `img_video` varchar(100) DEFAULT NULL,
  `file_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-image,1-video',
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_img_video`
--

INSERT INTO `tbl_product_img_video` (`id`, `product_id`, `img_video`, `file_type`, `created`) VALUES
(5, 1, '1111_035431download_1.jpg', 0, '2017-11-11 03:54:31'),
(3, 2, '1111_035007download.jpg', 0, '2017-11-11 03:50:07'),
(6, 1, '1111_035445download_(2).jpg', 0, '2017-11-11 03:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_review`
--

CREATE TABLE `tbl_product_review` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0',
  `quality` int(11) NOT NULL DEFAULT '0',
  `comment` text,
  `is_admin_approve` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-Approval Pending 1-Approved',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product_review`
--

INSERT INTO `tbl_product_review` (`id`, `product_id`, `user_id`, `title`, `price`, `value`, `quality`, `comment`, `is_admin_approve`, `is_deleted`, `created`, `modified`) VALUES
(1, 1, 4, 'product title', 200, 12, 13, 'test comment', 0, 1, '2017-10-18 03:09:00', '2017-10-29 01:41:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_state`
--

CREATE TABLE `tbl_state` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_state`
--

INSERT INTO `tbl_state` (`id`, `country_id`, `name`, `is_active`) VALUES
(1, 1, 'Maharashtra', 1),
(2, 1, 'Rajasthan', 1),
(3, 1, 'Uttar Pradesh', 1),
(4, 1, 'Gujarat', 1),
(5, 1, 'Assam', 1),
(6, 1, 'Bihar', 1),
(7, 1, 'Goa', 1),
(8, 1, 'Haryana', 1),
(9, 1, 'Kerala', 1),
(10, 1, 'Punjab', 1),
(11, 1, 'Tamil Nadu', 1),
(12, 1, 'West Bengal', 1),
(13, 1, 'Uttarakhand', 1),
(14, 2, 'California', 1),
(15, 2, 'Nevada', 1),
(16, 4, 'ddsd', 1),
(17, 2, 'dcfdc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscriber`
--

CREATE TABLE `tbl_subscriber` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_subscriber`
--

INSERT INTO `tbl_subscriber` (`id`, `email`, `is_deleted`, `created`, `modified`) VALUES
(1, 'jadhavchaitali@yahoo.com', 0, '2017-10-18 00:00:00', '2017-10-12 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_category`
--

CREATE TABLE `tbl_sub_category` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active 0-In Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sub_category`
--

INSERT INTO `tbl_sub_category` (`id`, `brand_id`, `category_id`, `name`, `is_active`, `is_delete`, `created`, `modified`) VALUES
(1, 1, 2, 'sub category 1', 1, 0, '2017-10-03 03:33:39', '2017-10-03 03:34:14'),
(2, 8, 1, 'sub category123', 1, 1, '2017-10-03 04:06:14', '2017-10-03 04:06:14'),
(3, 8, 3, 'sub category', 1, 0, '2017-10-03 04:06:24', '2017-10-03 04:27:21'),
(4, 8, 1, 'sub category 2', 1, 0, '2017-11-11 03:16:58', '2017-11-11 03:16:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `country_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `city_id` int(11) NOT NULL DEFAULT '0',
  `address` text,
  `profile_img` text,
  `dob` date DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '3' COMMENT '1-Admin 2-Marchant 3-Customer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active 0-In Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `fname`, `mname`, `lname`, `email`, `password`, `mobile`, `gender`, `country_id`, `state_id`, `city_id`, `address`, `profile_img`, `dob`, `type`, `is_active`, `is_delete`, `created`, `modified`) VALUES
(1, 'Admin', NULL, NULL, 'admin@test.com', 'e10adc3949ba59abbe56e057f20f883e', '9527483072', 0, 0, 0, 0, NULL, NULL, NULL, 1, 1, 0, NULL, NULL),
(2, 'Chaitali', 'A', 'Jadhav', 'chaitali12@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '9685745214', 1, 0, 0, 0, 'nashik', NULL, '2016-06-14', 3, 1, 0, '2017-10-01 02:16:01', '2017-10-03 03:26:21'),
(3, 'Chaitali', 'A', 'Jadhav', 'chaitali123@gmail.com', '25d55ad283aa400af464c76d713c07ad', '9876543213', 1, 0, 0, 0, 'nashik', NULL, '2017-10-09', 3, 1, 1, '2017-10-01 02:21:22', '2017-10-01 02:21:49'),
(4, 'Chaitali', 'A', 'Jadhav', 'chaitu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '9685741258', 1, 0, 0, 0, 'nashik', 'uploads/1003_0323410b88b66ecd2ae8a8a16f09ae63b744e4.jpg', '2017-10-13', 3, 1, 0, '2017-10-03 03:23:41', '2017-10-03 03:23:41'),
(5, 'maithili', 's', 'kolhe', 'maithili@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', '9687745214', 1, 0, 0, 0, 'nashik', NULL, '2017-10-04', 3, 1, 0, '2017-10-03 03:48:39', '2017-10-03 03:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wishlist`
--

CREATE TABLE `tbl_wishlist` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_brands`
--
ALTER TABLE `tbl_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_img_video`
--
ALTER TABLE `tbl_product_img_video`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_review`
--
ALTER TABLE `tbl_product_review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_state`
--
ALTER TABLE `tbl_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_sub_category`
--
ALTER TABLE `tbl_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_wishlist`
--
ALTER TABLE `tbl_wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_brands`
--
ALTER TABLE `tbl_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_city`
--
ALTER TABLE `tbl_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tbl_country`
--
ALTER TABLE `tbl_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_product_img_video`
--
ALTER TABLE `tbl_product_img_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_product_review`
--
ALTER TABLE `tbl_product_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_state`
--
ALTER TABLE `tbl_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_sub_category`
--
ALTER TABLE `tbl_sub_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_wishlist`
--
ALTER TABLE `tbl_wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
