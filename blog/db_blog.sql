-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               9.6.0 - MySQL Community Server - GPL
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


-- Dumping database structure for db_blog
DROP DATABASE IF EXISTS `db_blog`;
CREATE DATABASE IF NOT EXISTS `db_blog` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_blog`;

-- Dumping structure for table db_blog.artikel
DROP TABLE IF EXISTS `artikel`;
CREATE TABLE IF NOT EXISTS `artikel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_penulis` int NOT NULL,
  `id_kategori` int NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari_tanggal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_artikel_penulis` (`id_penulis`),
  KEY `fk_artikel_kategori` (`id_kategori`),
  CONSTRAINT `fk_artikel_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_artikel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_artikel_penulis` FOREIGN KEY (`id_penulis`) REFERENCES `penulis` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_blog.artikel: ~3 rows (approximately)
INSERT INTO `artikel` (`id`, `id_penulis`, `id_kategori`, `judul`, `isi`, `gambar`, `hari_tanggal`) VALUES
	(5, 13, 2, 'Makanan di Malang', 'Malang terkenal dengan kuliner bakso dan cwie mie, serta jajanan berbasis apel dan tempe. Kuliner ikonik lainnya termasuk orem-orem, rawon, sate kelinci, dan bakpao telo. Kota ini juga menawarkan camilan legendaris seperti keripik buah, carang mas, dan puthu lanang', 'default.png', 'Selasa, 28 April 2026 | 13:36'),
	(7, 12, 5, 'Cafe ternyaman di Malang', 'Inilah top 5 cafe di Malang', 'default.png', 'Selasa, 28 April 2026 | 14:03'),
	(8, 11, 6, 'Wisata Alam Terindah di Jawa Timur', 'Ini lah 3 wisata alam yang paling top di JAWA TIMUR!!', 'default.png', 'Selasa, 28 April 2026 | 14:04');

-- Dumping structure for table db_blog.kategori_artikel
DROP TABLE IF EXISTS `kategori_artikel`;
CREATE TABLE IF NOT EXISTS `kategori_artikel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_blog.kategori_artikel: ~4 rows (approximately)
INSERT INTO `kategori_artikel` (`id`, `nama_kategori`, `keterangan`) VALUES
	(2, 'kuliner', 'mencari makanan terbaik'),
	(3, 'Drink', 'mencari minuman terbaik'),
	(5, 'Cafe', 'Tempat nongkrong yang ada kopinya'),
	(6, 'Alam', 'Wisata alam');

-- Dumping structure for table db_blog.penulis
DROP TABLE IF EXISTS `penulis`;
CREATE TABLE IF NOT EXISTS `penulis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_depan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_belakang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_blog.penulis: ~4 rows (approximately)
INSERT INTO `penulis` (`id`, `nama_depan`, `nama_belakang`, `user_name`, `password`, `foto`) VALUES
	(11, 'Budi', 'Doremi', 'budisaja', '$2y$10$w22pPSq2bILkyU9KKKdfVupGaxw1dCow33kUxr.2o8EADjztpiabG', 'default.png'),
	(12, 'Dhito', 'Arkana', 'dhitooo', '$2y$10$SKYUXzv4CYy.1SSHalGqI.vlLl89q5ZDqdrIJdR3SMUNrFPn/38Mi', 'default.png'),
	(13, 'Alam', 'Wijaya', 'wijaya80', '$2y$10$v7qAcO3.uOwVb5shqJqZvOFjIHD5Cg8/rnA3hgJBdDMbHQFy3vV8K', 'default.png'),
	(14, 'Muktibaskara', 'Kusbianto', 'baskara', '$2y$10$vjOIM7ZEujqBen2mNnmmUelbOKU00kG5DURffx28.toP3SZUth4m.', '69f056146e432.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
