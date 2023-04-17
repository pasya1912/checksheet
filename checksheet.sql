-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for checksheet
DROP DATABASE IF EXISTS `checksheet`;
CREATE DATABASE IF NOT EXISTS `checksheet` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `checksheet`;

-- Dumping structure for table checksheet.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table checksheet.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table checksheet.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table checksheet.migrations: ~4 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- Dumping structure for table checksheet.password_resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `npk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`npk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table checksheet.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;

-- Dumping structure for table checksheet.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table checksheet.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table checksheet.tm_checkarea
DROP TABLE IF EXISTS `tm_checkarea`;
CREATE TABLE IF NOT EXISTS `tm_checkarea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_checksheet` int(11) NOT NULL DEFAULT 1,
  `nama` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `min` float DEFAULT NULL,
  `max` float DEFAULT NULL,
  `tipe` enum('1','2','3') DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_checkarea_checksheet` (`id_checksheet`),
  CONSTRAINT `FK_checkarea_checksheet` FOREIGN KEY (`id_checksheet`) REFERENCES `tm_checksheet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table checksheet.tm_checkarea: ~53 rows (approximately)
DELETE FROM `tm_checkarea`;
INSERT INTO `tm_checkarea` (`id`, `id_checksheet`, `nama`, `deskripsi`, `min`, `max`, `tipe`) VALUES
	(1, 1, 'DATUM KNOCK HOLE 1', NULL, 7.963, 7.978, '2'),
	(2, 1, 'DATUM KNOCK HOLE 2', NULL, 7.963, 7.978, '2'),
	(3, 1, 'LEVEL GAUGE HOLE', NULL, 13, 13.06, '2'),
	(4, 1, 'STRAINER HOLE', NULL, 21.2, 21.252, '2'),
	(5, 1, 'PLUG HOLE', NULL, 25, 25.052, '2'),
	(6, 1, 'CRANK SHAFT', NULL, 105, 105.054, '2'),
	(7, 2, 'Dial 40 - POSITION CRANK SHAFT', NULL, NULL, 0.06, '2'),
	(8, 2, 'Dial 15 - HEIGHT OIL FILTER', NULL, 121.5, 122.5, '2'),
	(9, 2, 'Dial 6 - HEIGHT TCC SURFACE', NULL, 328.95, 329.05, '2'),
	(10, 2, 'Dial 8 - FLATNESS TCC SURFACE', NULL, NULL, 0.1, '2'),
	(11, 2, 'Dial 8 - HEIGHT PLUG HOLE', NULL, 338.5, 339.5, '2'),
	(12, 2, 'Dial 10 - HEIGHT DRAIN PLUG ', NULL, 178.2, 179.2, '2'),
	(13, 2, 'Dial 8 - POSITION A/C SURFACE', NULL, NULL, 0.2, '2'),
	(14, 2, 'Dial 8 & 10 - HEIGHT T/M SURFACE', NULL, 23.84, 24.03, '2'),
	(15, 2, 'Dial 8 & 10 - POSITION T/M SRF', NULL, NULL, 0.2, '2'),
	(16, 2, 'Dial 15 - HEIGHT SETTING SURFACE', NULL, 31.8, 32.2, '2'),
	(17, 2, 'Dial 6MLF - E/G BLOCK SURFACE', NULL, NULL, 0.5, '2'),
	(18, 2, 'CALIPER - DEPTH O-RING 12', NULL, 1.55, 1.75, '2'),
	(19, 2, 'CALIPER - DEPTH O-RING 24,6', NULL, 1.62, 1.82, '2'),
	(20, 2, 'CALIPER - DEPTH O-RING 34,3', NULL, 1.62, 1.82, '2'),
	(21, 2, 'PIN GAUGE NOMOR 3 AREA HOLE ENGINE TIGHTHENING', NULL, NULL, NULL, '1'),
	(22, 2, 'PIN GAUGE NOMOR 4 AREA HOLE DATUM ASSY 1', NULL, NULL, NULL, '1'),
	(23, 2, 'PIN GAUGE NOMOR 6 AREA HOLE ENGINE BOTTOM M8 1 & 2', NULL, NULL, NULL, '1'),
	(24, 2, 'PIN GAUGE NOMOR 7 AREA HOLE ENGINE BOTTOM M8 3 & HOLE AC BOLT ', NULL, NULL, NULL, '1'),
	(25, 2, 'PIN GAUGE NOMOR 9 AREA HOLE ENGINE BOTTOM M8 4 & 5', NULL, NULL, NULL, '1'),
	(26, 2, 'PIN GAUGE NOMOR 11 AREA HOLE DATUM CASTING', NULL, NULL, NULL, '1'),
	(27, 2, 'PIN GAUGE NOMOR 12 AREA HOLE DRAIN PLUG M12', NULL, NULL, NULL, '1'),
	(28, 2, 'PIN GAUGE NOMOR 17 AREA HOLE M12 TRANSMISSION SURFACE', NULL, NULL, NULL, '1'),
	(29, 2, 'PIN GAUGE NOMOR 18 AREA HOLE CENTER TRANSMISSION', NULL, NULL, NULL, '1'),
	(30, 2, 'PIN GAUGE NOMOR 19 AREA HOLE BESIDE TRANSMISSION 1', NULL, NULL, NULL, '1'),
	(31, 2, 'PIN GAUGE NOMOR 21 AREA HOLE BESIDE TRANSMISSION 2', NULL, NULL, NULL, '1'),
	(32, 2, 'PIN GAUGE NOMOR 22 AREA HOLE TCC BOLT', NULL, NULL, NULL, '1'),
	(33, 2, 'PIN GAUGE NOMOR 23 AREA HOLE O-RING 24,6 INSIDE', NULL, NULL, NULL, '1'),
	(34, 2, 'PIN GAUGE NOMOR 24 AREA HOLE O-RING 24,6 OUTSIDE', NULL, NULL, NULL, '1'),
	(35, 2, 'PIN GAUGE NOMOR 25 AREA HOLE O-RING 12 INSIDE', NULL, NULL, NULL, '1'),
	(36, 2, 'PIN GAUGE NOMOR 26 AREA HOLE O-RING 12 OUTSIDE', NULL, NULL, NULL, '1'),
	(37, 2, 'PIN GAUGE NOMOR 27 AREA HOLE O-RING 34,3 INSIDE', NULL, NULL, NULL, '1'),
	(38, 2, 'PIN GAUGE NOMOR 28 AREA HOLE O-RING 34,3 OUTSIDE', NULL, NULL, NULL, '1'),
	(39, 2, 'PIN GAUGE NOMOR 39 AREA HOLE OIL LEVEL', NULL, NULL, NULL, '1'),
	(40, 2, 'PIN GAUGE NOMOR 5 AREA HOLE DATUM ASSEMBLY 2', NULL, NULL, NULL, '1'),
	(41, 2, 'PIN GAUGE NOMOR 13 AREA HOLE OIL FILTER BOLT', NULL, NULL, NULL, '1'),
	(42, 2, 'PIN GAUGE NOMOR 14 AREA HOLE OIL FILTER BOTTOM', NULL, NULL, NULL, '1'),
	(43, 2, 'PIN GAUGE NOMOR16 AREA HOLE ENGINE BOTTOM 1', NULL, NULL, NULL, '1'),
	(44, 2, 'PIN GAUGE NOMOR 30 AREA STRAINER HOLE', NULL, NULL, NULL, '1'),
	(45, 2, 'PIN GAUGE NOMOR 31 AREA HOLE O-RING GROOVE 20,5', NULL, NULL, NULL, '1'),
	(46, 2, 'PIN GAUGE NOMOR 32 AREA HOLE BUFFLE PLATE M6', NULL, NULL, NULL, '1'),
	(48, 2, 'PIN GAUGE NOMOR 33 AREA HOLE BUFFLE PLATE M6', NULL, NULL, NULL, '1'),
	(49, 2, 'PIN GAUGE NOMOR 34 AREA HOLE BUFFLE PLATE M6', NULL, NULL, NULL, '1'),
	(50, 2, 'PIN GAUGE NOMOR 35 AREA HOLE BUFFLE PLATE M6', NULL, NULL, NULL, '1'),
	(51, 2, 'PIN GAUGE NOMOR 36 AREA HOLE BUFFLE PLATE M6', NULL, NULL, NULL, '1'),
	(52, 2, 'PIN GAUGE NOMOR 38 AREA HOLE BUFFLE PLATE M8 1', NULL, NULL, NULL, '1'),
	(53, 2, 'PIN GAUGE NOMOR 38 AREA HOLE BUFFLE PLATE M8 2', NULL, NULL, NULL, '1'),
	(54, 3, NULL, NULL, NULL, NULL, '3');

-- Dumping structure for table checksheet.tm_checksheet
DROP TABLE IF EXISTS `tm_checksheet`;
CREATE TABLE IF NOT EXISTS `tm_checksheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(11) NOT NULL,
  `nama` varchar(110) DEFAULT NULL,
  `jenis` enum('OIL PAN','TCC') NOT NULL DEFAULT 'OIL PAN',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table checksheet.tm_checksheet: ~23 rows (approximately)
DELETE FROM `tm_checksheet`;
INSERT INTO `tm_checksheet` (`id`, `line`, `code`, `nama`, `jenis`) VALUES
	(1, 'MA006', '889F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(2, 'MA007', '889F', 'CHECK SHEET JIG 1', 'OIL PAN'),
	(3, 'MA007', '889F', 'CHECK SHEET LOT DC & DIES', 'OIL PAN'),
	(4, 'MA001', 'D98E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(5, 'MA001', '889F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(6, 'MA001', 'D18E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(7, 'MA001', 'D72F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(8, 'MA002', 'D72F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(9, 'MA002', 'D41E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(10, 'MA002', '889F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(11, 'MA002', 'D13E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(12, 'MA002', 'D18E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(13, 'MA002', 'D98E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(14, 'MA003', 'D72F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(15, 'MA003', 'D05F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(16, 'MA003', 'D41E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(17, 'MA003', 'D13E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(18, 'MA004', '4A91', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(19, 'MA006', '922F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(20, 'MA006', 'D41E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(21, 'MA007', 'D72F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(22, 'MA008', 'D72F', 'CHECK SHEET INSIDE MICRO', 'OIL PAN'),
	(23, 'MA008', 'D41E', 'CHECK SHEET INSIDE MICRO', 'OIL PAN');

-- Dumping structure for table checksheet.tt_checkdata
DROP TABLE IF EXISTS `tt_checkdata`;
CREATE TABLE IF NOT EXISTS `tt_checkdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_checkarea` int(11) NOT NULL DEFAULT 0,
  `nama` varchar(50) NOT NULL DEFAULT 'm1',
  `barang` enum('first','last') NOT NULL DEFAULT 'first',
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `user` varchar(16) NOT NULL,
  `value` varchar(50) NOT NULL,
  `approval` enum('approved','wait','rejected') NOT NULL DEFAULT 'wait',
  `mark` enum('0','1') NOT NULL DEFAULT '0',
  `shift` enum('1','2','3','1-long','3-long') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_tt_checkdata_tm_checkarea` (`id_checkarea`),
  CONSTRAINT `FK_tt_checkdata_tm_checkarea` FOREIGN KEY (`id_checkarea`) REFERENCES `tm_checkarea` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table checksheet.tt_checkdata: ~37 rows (approximately)
DELETE FROM `tt_checkdata`;
INSERT INTO `tt_checkdata` (`id`, `id_checkarea`, `nama`, `barang`, `tanggal`, `user`, `value`, `approval`, `mark`, `shift`) VALUES
	(1, 10, 'm1', 'first', '2023-04-02 07:11:41', '123', '0.055', 'wait', '0', '1'),
	(2, 10, 'm2', 'first', '2023-04-05 07:15:25', '123', '1.25', 'approved', '0', '1'),
	(3, 30, 'm1', 'first', '2023-04-02 07:30:09', '123', 'ng', 'wait', '0', '1'),
	(4, 54, 'M1', 'first', '2023-03-29 09:38:08', '123', '150', 'wait', '0', '1'),
	(5, 1, 'Mw', 'first', '2023-03-29 06:46:17', '124', '7.962', 'wait', '0', '1'),
	(6, 6, 'M2', 'first', '2023-03-29 07:21:22', '123', '105.5', 'wait', '0', '1'),
	(7, 6, 'M2', 'last', '2023-03-29 07:22:25', '123', '105.054', 'wait', '0', '1'),
	(8, 2, 'M2', 'first', '2023-03-29 07:27:44', '123', '7.9644444', 'wait', '0', '1'),
	(9, 2, 'M2', 'last', '2023-03-29 07:28:47', '123', '7.864', 'wait', '0', '1'),
	(10, 1, 'M1', 'first', '2023-03-30 06:11:56', '123', '7.962', 'wait', '0', '1'),
	(11, 18, 'm1', 'first', '2023-04-05 08:50:24', '123', '2', 'wait', '0', '1'),
	(12, 6, 'm1', 'first', '2023-04-12 00:25:52', '123', '105', 'wait', '0', '1'),
	(13, 1, 'm1', 'first', '2023-04-12 00:26:49', '123', '7.963', 'wait', '0', '1'),
	(14, 2, 'm1', 'first', '2023-04-12 00:27:14', '123', '7.961', 'wait', '0', '1'),
	(15, 3, 'm1', 'first', '2023-04-12 00:49:37', '123', '12', 'wait', '0', '1'),
	(16, 5, 'm1', 'first', '2023-04-12 00:53:33', '123', '25', 'wait', '0', '1'),
	(17, 4, 'm1', 'first', '2023-04-12 00:54:20', '123', '25', 'wait', '0', '1'),
	(18, 18, 'm1', 'first', '2023-04-12 00:57:04', '123', '1.77', 'wait', '0', '1'),
	(19, 19, 'm1', 'first', '2023-04-12 00:58:21', '123', '1.83', 'wait', '0', '1'),
	(20, 20, 'm1', 'first', '2023-04-12 01:00:44', '123', '1.88', 'wait', '0', '1'),
	(21, 12, 'm1', 'first', '2023-04-12 01:01:22', '123', '179.23', 'wait', '0', '1'),
	(22, 8, 'm1', 'first', '2023-04-12 01:30:13', '123', '122.5', 'wait', '0', '1'),
	(23, 16, 'm1', 'first', '2023-04-12 01:33:45', '123', '32', 'wait', '0', '1'),
	(24, 7, 'm1', 'first', '2023-04-12 01:33:58', '123', '0.07', 'wait', '0', '1'),
	(25, 17, 'm1', 'first', '2023-04-12 01:34:17', '123', '-0.0001', 'wait', '0', '1'),
	(26, 15, 'm1', 'first', '2023-04-12 02:16:41', '123', '0.3', 'wait', '0', '1'),
	(27, 10, 'm1', 'first', '2023-04-12 02:17:21', '123', '0.05', 'wait', '0', '1'),
	(28, 6, 'm2', 'last', '2023-04-12 02:49:53', '123', '105.044', 'wait', '0', '3'),
	(29, 1, 'm2', 'last', '2023-04-12 02:50:08', '123', '7.962', 'wait', '0', '3'),
	(30, 6, 'm1', 'first', '2023-04-12 03:46:47', '123', '105', 'wait', '0', '1-long'),
	(31, 1, 'm1', 'first', '2023-04-12 03:47:09', '123', '788', 'wait', '0', '1-long'),
	(32, 2, 'm1', 'first', '2023-04-12 03:49:03', '123', '7888', 'wait', '0', '1-long'),
	(33, 3, 'm1', 'first', '2023-04-12 03:49:08', '123', '13', 'wait', '0', '1-long'),
	(34, 5, 'm1', 'first', '2023-04-12 03:50:15', '123', '25', 'wait', '0', '1-long'),
	(35, 4, 'm1', 'first', '2023-04-12 03:51:59', '123', '25', 'wait', '0', '1-long'),
	(36, 18, 'm1', 'first', '2023-04-17 04:32:08', '123', '1.45', 'wait', '0', '1'),
	(37, 54, 'm1', 'first', '2023-04-17 11:59:40', '123', '255', 'wait', '0', '1');

-- Dumping structure for table checksheet.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `npk` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`npk`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table checksheet.users: ~3 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `npk`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(2, 'admin', '123', 'admin', NULL, '$2y$10$nMDwo0PwrnDMrREHvy0iluWqnGhiChhmKvXW9ae8qdf75CAY6Fvue', NULL, '2023-03-26 20:22:54', '2023-03-26 20:22:54'),
	(3, 'user', '124', 'admin', NULL, '$2y$10$R9/WqxDCuAQEW1U1NixbE.fC5XvEUAgPjAFcrLlrWvBKdVDJpP7gu', NULL, '2023-03-28 23:44:38', '2023-03-28 23:44:38'),
	(4, 'Eva', '125', 'user', NULL, '$2y$10$EAJECu2ZLoGeLaeqwghP0.0D0juIKgr6GjZCNS7zAn8Zl.n4qaZaK', NULL, '2023-03-29 23:08:10', '2023-03-29 23:08:10');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
