/*
SQLyog Ultimate v12.2.6 (64 bit)
MySQL - 5.7.25-0ubuntu0.16.04.2 : Database - bot_bd
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `bot` */

DROP TABLE IF EXISTS `bot`;

CREATE TABLE `bot` (
  `dialog_id` text NOT NULL,
  `stage` int(11) NOT NULL DEFAULT '0',
  `first_name` text,
  `last_name` text,
  `phone` text,
  `city` text,
  `gragdanstvo` text,
  `age` text,
  `vagno_v_rabote` text,
  `zarplata` text,
  `pochemu` text,
  `vacancy` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
