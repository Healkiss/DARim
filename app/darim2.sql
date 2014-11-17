-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2014 at 11:03 PM
-- Server version: 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `darim`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `activityType_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `commentary` varchar(255) NOT NULL,
  `isTodo` int(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`),
  KEY `activityType` (`activityType_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `client_id`, `activityType_id`, `user_id`, `task`, `commentary`, `isTodo`, `start`, `end`, `time`) VALUES
(1, 1, 1, 1, 'task1', 'commentary1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00'),
(2, 1, 2, 1, 'task2', 'commentary2', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00'),
(3, 1, 1, 1, 'SFE-3', 'azerty', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00'),
(4, 4, 1, 1, 'zdzd', 'eazf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00'),
(5, 1, 3, 1, 'sqd', 'qsf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00'),
(6, 1, 4, 1, 'qsfqsgf', 'qsgqsgf', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `activityType`
--

CREATE TABLE IF NOT EXISTS `activityType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `activityType`
--

INSERT INTO `activityType` (`id`, `name`) VALUES
(1, 'Développement'),
(2, 'Relationnel client'),
(3, 'R&D'),
(4, 'Reunion interne');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) NOT NULL,
  `ref2` varchar(3) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `ref`, `ref2`, `name`) VALUES
(1, 'd550', 'FNA', 'Fnac'),
(2, 'd180', 'MAT', 'MATY'),
(4, 'd590', 'SFE', 'SimplyFeu'),
(5, 'N/A', 'N/A', 'N/A'),
(6, 'd600', 'ABi', 'ABIX'),
(7, 'd421', 'ALR', 'Allrun'),
(8, 'd420', 'ALT', 'Alltricks'),
(9, 'd570', 'ALS', 'Alsatis'),
(10, 'd511', 'BIA', 'Biotherm - Allemagne'),
(11, 'd512', 'BIE', 'Biotherm - Espagne'),
(12, 'd514', 'BII', 'Biotherm - Italie'),
(13, 'd62', 'BLU', 'Bluebox'),
(14, 'd130', 'CHC', 'Chouette Cards'),
(15, 'd160', 'CDB', 'Comtesse Du Barry');

-- --------------------------------------------------------

--
-- Table structure for table `run`
--

CREATE TABLE IF NOT EXISTS `run` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `run`
--

INSERT INTO `run` (`id`, `activity_id`, `start`, `end`, `time`) VALUES
(1, 1, '2014-11-12 19:24:58', '0000-00-00 00:00:00', '00:00:00'),
(2, 2, '2014-11-12 19:24:58', '0000-00-00 00:00:00', '00:00:00'),
(3, 1, '2014-11-14 17:40:47', '2014-11-14 17:40:51', '00:00:00'),
(4, 1, '2014-11-14 17:42:51', '2014-11-14 17:42:53', '00:00:00'),
(5, 2, '2014-11-14 17:42:54', '2014-11-14 17:43:00', '00:00:00'),
(6, 1, '2014-11-15 08:48:49', '2014-11-15 09:05:02', '00:00:00'),
(7, 2, '2014-11-15 09:05:02', '2014-11-15 09:05:03', '00:00:00'),
(8, 3, '2014-11-15 09:05:03', '2014-11-15 09:05:04', '00:00:00'),
(9, 4, '2014-11-15 09:05:04', '2014-11-15 09:15:38', '00:00:00'),
(10, 6, '2014-11-15 09:15:38', '2014-11-15 09:15:39', '00:00:00'),
(11, 5, '2014-11-15 09:15:39', '2014-11-15 09:15:40', '00:00:00'),
(12, 4, '2014-11-15 09:15:40', '0000-00-00 00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`) VALUES
(2, 'Antoine Lesqueren'),
(1, 'François Schneider');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
