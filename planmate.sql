/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.32-MariaDB : Database - planmate
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`planmate` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `planmate`;

/*Table structure for table `agenda` */

DROP TABLE IF EXISTS `agenda`;

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `judul` varchar(70) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `lokasi` varchar(70) DEFAULT NULL,
  `status` enum('belum','selesai') DEFAULT NULL,
  `keterangan` varchar(99) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `agenda` */

insert  into `agenda`(`id`,`user_id`,`judul`,`tanggal`,`waktu`,`lokasi`,`status`,`keterangan`) values 
(1,1,'demo project akhir pbw','2025-06-14','10:00:00','LAB TI','',NULL),
(2,2,'rapat','2025-06-12','00:00:00',NULL,NULL,NULL),
(3,3,'maen','0000-00-00','00:00:00',NULL,NULL,NULL),
(5,1,'maen','2025-06-12','11:20:00','kos orang','',NULL),
(10,5,'matkul','2025-06-12','14:50:00','LAB TI','selesai','bawa laptop'),
(11,5,'rapat','2025-06-12','15:15:00','cafe','selesai','nanaan'),
(12,5,'matkul','2025-06-12','15:25:00','LAB TI','selesai','njahjs'),
(13,5,'matkul','2025-06-12','15:47:00','RKBF','selesai','nanana'),
(14,5,'matkul','2025-06-12','15:57:00','LAB TI','selesai','dhsk'),
(16,5,'matkul','2025-06-12','16:13:00','RKBF','selesai','yayayay'),
(17,5,'rapat','2025-06-20','16:15:00','cafe','belum','yyyy'),
(18,7,'Matkul PAI','2025-06-13','07:00:00','RKB-E','belum','presentasi makalah'),
(19,7,'Kerkom PAI','2025-06-12','18:50:00','cafe','selesai','Bawa uang');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`email`,`password`) values 
(1,'amina','','$2y$10$f4KbdDiQGjtoTEp6zD/NfezPou9b7kiL6xr9Ysk5U1X4HTwpQnC9C'),
(2,'aminahh','amina678@gmail.com','$2y$10$SOCa3afsNEJCmhhokZuS6OpYH22ZtrFIbnNaRjxCHzJ6Icnvrhayy'),
(3,'aminaii','siti15388@smk.belajar.id','$2y$10$gfNBFn6uZJ1UbUYjI.JFEu2D5hYQwD2hmfIDhONsI2iXYNJMuw2ay'),
(4,'tesi','amina8@gmail.com','$2y$10$yE2Dj2inNeJoOqanUVw4Z.Zzc7GAE14xmrDi0lv/3DoWY6kLMaNlG'),
(5,'alivia','alivia2@gmail.com','$2y$10$93czKzRGyfCjlQa4lJdVQ.RagRTFXL2UMcsd1Cn0Y58f26w.A6Z7.'),
(6,'ahah','aminasiti6366@gmail.com','$2y$10$3bL8XrIZ5JL4yR0FPMCWOeB6/2F1.qOeZX/mww2lGVUsN/2yZ.Twm'),
(7,'alfia','alfia@gmail.com','$2y$10$EQI5dpzsisSa7qoztNXhLOEWBiUBk4vPa.nYZtNaINnariq7TdbzG');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
