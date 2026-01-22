-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_tatadompet
CREATE DATABASE IF NOT EXISTS `db_tatadompet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_tatadompet`;

-- Dumping structure for table db_tatadompet.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id_transaction` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `type` enum('pemasukan','pengeluaran') NOT NULL,
  `date` date NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id_transaction`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_tatadompet.transactions: ~2 rows (approximately)
DELETE FROM `transactions`;
INSERT INTO `transactions` (`id_transaction`, `id_user`, `amount`, `type`, `date`, `category`, `note`) VALUES
	(2, 1, 2000000.00, 'pemasukan', '2026-01-18', 'Gaji', ''),
	(3, 1, 2000000.00, 'pemasukan', '2026-01-19', 'Gaji', 'Gaji Adalah Pokoknya'),
	(4, 1, 1000000.00, 'pengeluaran', '2026-01-20', 'makan', 'makan beling');

-- Dumping structure for table db_tatadompet.users
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table db_tatadompet.users: ~0 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id_user`, `username`, `password`, `created_at`) VALUES
	(1, 'admin', '$2y$10$cdwsacdhp/Ymd4yggxzTX.4qFEvIvBcFJRwT2Fuk1dJPFNiu9MWYK', '2026-01-18 02:58:44');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
