-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.27-0ubuntu0.18.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for testDB
#DROP DATABASE IF EXISTS `testDB`;
#CREATE DATABASE IF NOT EXISTS `testDB` /*!40100 DEFAULT CHARACTER SET latin1 */;
#USE `testDB`;

-- Dumping structure for table testDB.projectConnections
DROP TABLE IF EXISTS `projectConnections`;
CREATE TABLE IF NOT EXISTS `projectConnections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(16) unsigned NOT NULL,
  `projectId` int(16) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `checkedInAt` int(16) NOT NULL DEFAULT '0',
  `timeSpent` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.projectMeta
DROP TABLE IF EXISTS `projectMeta`;
CREATE TABLE IF NOT EXISTS `projectMeta` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `projectId` int(16) unsigned NOT NULL,
  `metaKey` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.projects
DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.teamMeta
DROP TABLE IF EXISTS `teamMeta`;
CREATE TABLE IF NOT EXISTS `teamMeta` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `teamId` int(12) unsigned NOT NULL,
  `metaKey` varchar(16) NOT NULL,
  `value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.teams
DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.userMeta
DROP TABLE IF EXISTS `userMeta`;
CREATE TABLE IF NOT EXISTS `userMeta` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(16) unsigned NOT NULL,
  `metaKey` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

-- Dumping structure for table testDB.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `userId` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
