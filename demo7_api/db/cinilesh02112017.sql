-- MySQL dump 10.13  Distrib 5.7.11, for Win64 (x86_64)
--
-- Host: localhost    Database: cinilesh
-- ------------------------------------------------------
-- Server version	5.7.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_app_users`
--

DROP TABLE IF EXISTS `tbl_app_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_app_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_app_users`
--

LOCK TABLES `tbl_app_users` WRITE;
/*!40000 ALTER TABLE `tbl_app_users` DISABLE KEYS */;
INSERT INTO `tbl_app_users` VALUES (1,'admin','admin@test.com','e10adc3949ba59abbe56e057f20f883e','2017-07-18 00:00:00','2017-07-18 00:00:00');
/*!40000 ALTER TABLE `tbl_app_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_bank`
--

DROP TABLE IF EXISTS `tbl_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_bank`
--

LOCK TABLES `tbl_bank` WRITE;
/*!40000 ALTER TABLE `tbl_bank` DISABLE KEYS */;
INSERT INTO `tbl_bank` VALUES (1,35,'canara bank','gangapur road','ICIC0001872','saving','43563323233344343','2017-07-24 02:43:57','2017-07-24 02:46:43');
/*!40000 ALTER TABLE `tbl_bank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_brands`
--

DROP TABLE IF EXISTS `tbl_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `image` text,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1-active ,0-Inactive',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '1-delete,0-not delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_brands`
--

LOCK TABLES `tbl_brands` WRITE;
/*!40000 ALTER TABLE `tbl_brands` DISABLE KEYS */;
INSERT INTO `tbl_brands` VALUES (1,'Brand Name',NULL,1,1,'2017-10-01 02:41:40','2017-10-01 02:42:17'),(2,'ss',NULL,1,1,'2017-10-01 02:42:30','2017-10-01 02:42:30'),(3,'sds',NULL,1,1,'2017-10-01 02:42:44','2017-10-01 02:42:44'),(4,'scs',NULL,1,1,'2017-10-01 02:44:47','2017-10-01 02:44:47'),(5,'ghfgb',NULL,1,1,'2017-10-01 02:45:19','2017-10-01 02:45:31'),(6,'wdwdwsd',NULL,1,1,'2017-10-01 02:46:48','2017-10-01 02:46:48'),(7,'dsd',NULL,1,1,'2017-10-01 02:48:17','2017-10-01 02:48:17'),(8,'Brand Name2','uploads/1003_0330580b88b66ecd2ae8a8a16f09ae63b744e4.jpg',1,0,'2017-10-03 03:30:49','2017-10-03 05:17:02'),(9,'brand name',NULL,1,1,'2017-10-03 03:54:46','2017-10-03 03:54:46');
/*!40000 ALTER TABLE `tbl_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1-active ,0-Inactive',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '1-delete,0-not delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_category`
--

LOCK TABLES `tbl_category` WRITE;
/*!40000 ALTER TABLE `tbl_category` DISABLE KEYS */;
INSERT INTO `tbl_category` VALUES (1,8,'category 1',1,0,'2017-10-03 04:01:21','2017-10-03 04:01:21'),(2,8,'category',1,1,'2017-10-03 04:01:28','2017-10-03 04:01:28'),(3,8,'category 2',1,0,'2017-10-03 04:27:11','2017-10-03 04:27:11');
/*!40000 ALTER TABLE `tbl_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_city`
--

DROP TABLE IF EXISTS `tbl_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_city`
--

LOCK TABLES `tbl_city` WRITE;
/*!40000 ALTER TABLE `tbl_city` DISABLE KEYS */;
INSERT INTO `tbl_city` VALUES (1,1,1,'Nashik',1),(2,1,1,'Mumbai',1),(3,1,1,'Pune',1),(4,1,1,'Kolhapur',1),(5,1,1,'Aurangabad',1),(6,1,1,'Thane',1),(7,1,1,'Ahmadnagar',1),(8,1,1,'Amaravati',1),(9,1,1,'Delhi',1),(10,1,1,'Bangalore',1),(11,1,1,'Nagpur',1),(12,1,1,'Jaipur',1),(13,1,4,'Surat',1),(14,1,4,'Baroda',1),(15,1,4,'Patan',1),(16,1,4,'Morvi',1),(18,1,5,'Rajkot',1),(19,1,1,'sdxsds',1);
/*!40000 ALTER TABLE `tbl_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_country`
--

DROP TABLE IF EXISTS `tbl_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_country`
--

LOCK TABLES `tbl_country` WRITE;
/*!40000 ALTER TABLE `tbl_country` DISABLE KEYS */;
INSERT INTO `tbl_country` VALUES (1,'India',1),(2,'US',1),(3,'UK',1),(4,'uk1',1),(5,'sdsd',1),(6,'cscs',1);
/*!40000 ALTER TABLE `tbl_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_product`
--

DROP TABLE IF EXISTS `tbl_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_product`
--

LOCK TABLES `tbl_product` WRITE;
/*!40000 ALTER TABLE `tbl_product` DISABLE KEYS */;
INSERT INTO `tbl_product` VALUES (1,8,3,3,'product name',1250.00,25,'test',2.00,'2017-10-18','2017-10-31',1,'',NULL,1,1,0,'2017-10-03 04:37:26','2017-10-23 04:27:46');
/*!40000 ALTER TABLE `tbl_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_state`
--

DROP TABLE IF EXISTS `tbl_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_state`
--

LOCK TABLES `tbl_state` WRITE;
/*!40000 ALTER TABLE `tbl_state` DISABLE KEYS */;
INSERT INTO `tbl_state` VALUES (1,1,'Maharashtra',1),(2,1,'Rajasthan',1),(3,1,'Uttar Pradesh',1),(4,1,'Gujarat',1),(5,1,'Assam',1),(6,1,'Bihar',1),(7,1,'Goa',1),(8,1,'Haryana',1),(9,1,'Kerala',1),(10,1,'Punjab',1),(11,1,'Tamil Nadu',1),(12,1,'West Bengal',1),(13,1,'Uttarakhand',1),(14,2,'California',1),(15,2,'Nevada',1),(16,4,'ddsd',1);
/*!40000 ALTER TABLE `tbl_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_sub_category`
--

DROP TABLE IF EXISTS `tbl_sub_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_sub_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1-Active 0-In Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_sub_category`
--

LOCK TABLES `tbl_sub_category` WRITE;
/*!40000 ALTER TABLE `tbl_sub_category` DISABLE KEYS */;
INSERT INTO `tbl_sub_category` VALUES (1,1,2,'sub category 1',1,0,'2017-10-03 03:33:39','2017-10-03 03:34:14'),(2,8,1,'sub category123',1,1,'2017-10-03 04:06:14','2017-10-03 04:06:14'),(3,8,3,'sub category',1,0,'2017-10-03 04:06:24','2017-10-03 04:27:21');
/*!40000 ALTER TABLE `tbl_sub_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_subscriber`
--

DROP TABLE IF EXISTS `tbl_subscriber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_subscriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-Deleted 0-Not Delete',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_subscriber`
--

LOCK TABLES `tbl_subscriber` WRITE;
/*!40000 ALTER TABLE `tbl_subscriber` DISABLE KEYS */;
INSERT INTO `tbl_subscriber` VALUES (1,'kailasbedarkar@gmail.com',0,'2017-11-01 18:08:46','2017-11-01 18:08:46');
/*!40000 ALTER TABLE `tbl_subscriber` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_users`
--

LOCK TABLES `tbl_users` WRITE;
/*!40000 ALTER TABLE `tbl_users` DISABLE KEYS */;
INSERT INTO `tbl_users` VALUES (1,'Admin',NULL,NULL,'admin@test.com','e10adc3949ba59abbe56e057f20f883e','9527483072',0,0,0,0,NULL,NULL,NULL,1,1,0,NULL,NULL),(2,'Chaitali','A','Jadhav','chaitali12@gmail.com','e10adc3949ba59abbe56e057f20f883e','9685745214',1,0,0,0,'nashik',NULL,'2016-06-14',3,1,0,'2017-10-01 02:16:01','2017-10-03 03:26:21'),(3,'Chaitali','A','Jadhav','chaitali123@gmail.com','25d55ad283aa400af464c76d713c07ad','9876543213',1,0,0,0,'nashik',NULL,'2017-10-09',3,1,1,'2017-10-01 02:21:22','2017-10-01 02:21:49'),(4,'Chaitali','A','Jadhav','chaitu@gmail.com','e10adc3949ba59abbe56e057f20f883e','9685741258',1,0,0,0,'nashik','uploads/1003_0323410b88b66ecd2ae8a8a16f09ae63b744e4.jpg','2017-10-13',3,1,1,'2017-10-03 03:23:41','2017-10-03 03:23:41'),(5,'maithili','s','kolhe','maithili@yahoo.com','e10adc3949ba59abbe56e057f20f883e','9687745214',1,0,0,0,'nashik',NULL,'2017-10-04',3,1,0,'2017-10-03 03:48:39','2017-10-03 03:48:39');
/*!40000 ALTER TABLE `tbl_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-02  0:05:09
