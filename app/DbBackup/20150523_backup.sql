/*
SQLyog Ultimate v9.20 
MySQL - 5.5.43-0ubuntu0.14.04.1 : Database - flatnflatmates
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`flatnflatmates` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `flatnflatmates`;

/*Table structure for table `house_details` */

DROP TABLE IF EXISTS `house_details`;

CREATE TABLE `house_details` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `house_type_id` tinyint(4) NOT NULL,
  `max_persons` int(2) DEFAULT NULL,
  `bath_rooms` int(2) DEFAULT NULL,
  `bed_rooms` int(2) DEFAULT NULL,
  `kitchens` int(1) DEFAULT NULL,
  `total_rent` int(4) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `minimum_stay` int(3) DEFAULT NULL,
  `security_deposit` int(4) DEFAULT NULL,
  `instrunctions` varchar(500) DEFAULT NULL,
  `rating` int(1) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `house_space_available` */

DROP TABLE IF EXISTS `house_space_available`;

CREATE TABLE `house_space_available` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` int(11) NOT NULL,
  `rooms_available` tinyint(4) DEFAULT NULL,
  `is_shared` enum('0','1') DEFAULT '0' COMMENT '0=> No, 1=> Yes',
  `attached_bathroom` enum('0','1') DEFAULT '0' COMMENT '0=> No, 1=> Yes',
  `bed` enum('0','1') DEFAULT '0' COMMENT '0=> No, 1=> Yes',
  `balcony` enum('0','1') DEFAULT '0' COMMENT '0=> No, 1=> Yes',
  `rent` int(11) DEFAULT NULL,
  `security_deposit` int(11) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `total_views` int(11) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `house_type` */

DROP TABLE IF EXISTS `house_type`;

CREATE TABLE `house_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `is_active` enum('0','1') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `owner_preferences` */

DROP TABLE IF EXISTS `owner_preferences`;

CREATE TABLE `owner_preferences` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `house_id` int(11) DEFAULT NULL,
  `smoking` enum('0','1') DEFAULT '1',
  `drinking` enum('0','1') DEFAULT '1',
  `pets` enum('0','1') DEFAULT '1',
  `gender` enum('0','1','2') DEFAULT '2',
  `guests` enum('0','1') DEFAULT '1',
  `food_pref` enum('0','1','2') DEFAULT '2',
  `profession` enum('0','1','2','3') DEFAULT '3',
  `is_active` enum('0','1') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `sex` enum('0','1','2') DEFAULT '0' COMMENT '0=> Male, 1=> Female, 2 => Another',
  `email_id` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `pin_code` varchar(6) DEFAULT NULL,
  `user_image` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `facebook_user_id` varchar(50) DEFAULT NULL,
  `is_owner` enum('0','1') DEFAULT '0' COMMENT '0=> No, 1=> Yes',
  `is_active` enum('0','1') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
