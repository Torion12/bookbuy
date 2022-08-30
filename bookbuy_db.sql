-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table bookbuy_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` varchar(150) DEFAULT NULL,
  `description` text,
  `active` varchar(50) DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.categories: ~0 rows (approximately)
DELETE FROM `categories`;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `genre`, `description`, `active`) VALUES
	(1, 'BSIS', 'TEST', 'Y');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.notifications: ~10 rows (approximately)
DELETE FROM `notifications`;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `created_at`) VALUES
	(11, 2, 'staff-notification', 'Edwin TEST added an order #EAITAOz50t.', '2021-05-08 04:35:52'),
	(12, 2, 'staff-notification', 'Edwin TEST added an order #SGoKPhmxtg.', '2021-05-08 04:38:31'),
	(13, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:31:21'),
	(14, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:32:44'),
	(15, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:40:32'),
	(16, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:41:18'),
	(17, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:42:06'),
	(18, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:43:40'),
	(19, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:43:59'),
	(20, 2, 'staff-notification', 'Edwin TEST added an order.', '2021-05-12 01:48:55');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `textbook_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `total` double NOT NULL DEFAULT '0',
  `order_id` varchar(50) NOT NULL DEFAULT '0',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.order_details: ~10 rows (approximately)
DELETE FROM `order_details`;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` (`id`, `user_id`, `status`, `textbook_id`, `quantity`, `total`, `order_id`, `price`) VALUES
	(5, 2, 'paid', 1, 12, 1800, 'SGoKPhmxtg', 150),
	(6, 2, 'paid', 1, 1, 150, 'oCn4A1f92g', 150),
	(7, 2, 'paid', 1, 1, 150, '4w2ZKpCyPK', 150),
	(8, 2, 'paid', 1, 12, 1800, 'E3U5u4FduH', 150),
	(9, 2, 'paid', 1, 11, 1650, 'Kd2fBQZFHH', 150),
	(10, 2, 'paid', 1, 11, 1650, 'aVuwu44831', 150),
	(11, 2, 'pending', 1, 11, 1650, 'YhSHhXFg53', 150),
	(12, 2, 'pending', 1, 11, 1650, '4cwH33NIh6', 150),
	(13, 2, 'pending', 1, 11, 1650, 'xjBjRmEBsr', 150),
	(14, 2, 'pending', 1, 11, 1650, 'ZOGXakN51f', 150);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.payment_history
CREATE TABLE IF NOT EXISTS `payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL DEFAULT '0',
  `payment_type` varchar(50) NOT NULL DEFAULT '0',
  `payment_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.payment_history: ~0 rows (approximately)
DELETE FROM `payment_history`;
/*!40000 ALTER TABLE `payment_history` DISABLE KEYS */;
INSERT INTO `payment_history` (`id`, `order_id`, `payment_type`, `payment_date`) VALUES
	(1, 'aVuwu44831', 'cash', '2021-05-12');
/*!40000 ALTER TABLE `payment_history` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `permissions` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.roles: ~3 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `name`, `permissions`) VALUES
	(1, 'Admin', '{"admin": 1}'),
	(2, 'Dean', '{"dean": 1}'),
	(3, 'Staff', '{"staff": 1}'),
	(4, 'Student', '{"student": 1}');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.textbooks
CREATE TABLE IF NOT EXISTS `textbooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `textbook_name` varchar(150) DEFAULT NULL,
  `textbook_desc` text,
  `category_id` int(11) DEFAULT NULL,
  `quantity_per_unit` int(11) DEFAULT NULL,
  `textbook_stock` int(11) DEFAULT NULL,
  `textbook_order` varchar(150) DEFAULT NULL,
  `textbook_status` varchar(150) DEFAULT NULL,
  `discount` varchar(150) DEFAULT NULL,
  `textbook_price` double DEFAULT NULL,
  `textbook_img` text,
  `department` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.textbooks: ~4 rows (approximately)
DELETE FROM `textbooks`;
/*!40000 ALTER TABLE `textbooks` DISABLE KEYS */;
INSERT INTO `textbooks` (`id`, `textbook_name`, `textbook_desc`, `category_id`, `quantity_per_unit`, `textbook_stock`, `textbook_order`, `textbook_status`, `discount`, `textbook_price`, `textbook_img`, `department`) VALUES
	(1, 'How To Cook a Beef using Meat Fats Oil  - Vol 1', 'This is a test', 1, 1000, 999, '1000', 'active', '0', 150, 'uploads/book-1.png', 'CCS'),
	(2, 'How To Cook a Beef using Meat Fats Oil  - Vol 2', 'This is a test', 1, 1000, 1000, '1000', 'active', '0', 150, 'uploads/book-2.png', 'CCS'),
	(3, 'How To Cook a Beef using Meat Fats Oil  - Vol 3', 'This is a test', 1, 1000, 1000, '1000', 'active', '0', 150, 'uploads/book-2.png', 'CCS'),
	(4, 'How To Cook a Beef using Meat Fats Oil  - Vol 4', 'This is a test', 1, 1000, 1000, '1000', 'active', '0', 150, 'uploads/book-2.png', 'Nursing');
/*!40000 ALTER TABLE `textbooks` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_number` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.users: ~2 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `id_number`, `password`, `role_id`, `email`, `first_name`, `last_name`, `middle_name`, `address`, `created_at`) VALUES
	(1, '123123', '$2y$10$WlWMtA2NKpaOgTasjjgwkeKpFoHd0c.2qXDmf9JUiydhEH6KVoBB2', 4, '234@gmail.com', '234', '123123', '234234', '123123123123123', '2021-04-09 06:15:46'),
	(2, '11223344', '$2y$10$TEKvG3GwRjsMgLy0nbrCw.sYwWjmnjB3MZcs9UcNjSpWomsPtkKLa', 4, 'edwin@gmail.com', 'Edwin', 'TEST', 'TEST', 'test address 1q23123123', '2021-04-09 06:16:53'),
	(3, '0000', '$2y$10$m1VHQVwQ4d/t4wJ3ucgO3O8KESFcSAas/zmbPuT2wDDqXO/IkNUSS', 1, 'admin@admin.com', 'admin', 'user', '', '', '2021-04-17 02:24:59'),
	(4, '1111', '$2y$10$m1VHQVwQ4d/t4wJ3ucgO3O8KESFcSAas/zmbPuT2wDDqXO/IkNUSS', 3, 'staff@staff.com', 'staff', 'user', 'test', '1122', '2021-04-17 02:24:59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table bookbuy_db.user_sessions
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table bookbuy_db.user_sessions: ~0 rows (approximately)
DELETE FROM `user_sessions`;
/*!40000 ALTER TABLE `user_sessions` DISABLE KEYS */;
INSERT INTO `user_sessions` (`id`, `user_id`, `hash`) VALUES
	(2, 2, 'b4d0b1ce108c740dd187a47678ed3ac864762ad2f6388211a57a2c081b117563');
/*!40000 ALTER TABLE `user_sessions` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
