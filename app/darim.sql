-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 18, 2014 at 02:13 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `client_id`, `activityType_id`, `user_id`, `task`, `commentary`, `isTodo`, `start`, `end`, `time`) VALUES
(23, 1, 1, 1, 'WHI-63', 'temlate eret affichage related', 0, '2014-11-18 08:18:42', '2014-11-18 13:00:00', '00:00:00'),
(24, 1, 1, 1, 'WHI-63', 'temlate eret affichage related', 0, '2014-11-18 08:18:42', '0000-00-00 00:00:00', '00:00:00'),
(25, 1, 1, 1, 'WHI-63', 'temlate eret affichage related', 0, '2014-11-19 08:14:20', '2014-11-19 11:38:12', '00:00:00'),
(36, 1, 1, 1, 'WHI-63', 'retouche eret 3', 0, '2014-11-19 15:25:37', '2014-11-19 16:25:39', '00:00:00'),
(38, 1, 1, 1, 'WHI-63', 'retouche eret 2', 0, '2014-11-19 12:00:28', '2014-11-19 15:25:32', '00:00:00'),
(39, 1, 1, 1, 'WHI-63', 'get related from new table', 0, '2014-11-19 16:36:05', '2014-11-19 16:38:24', '00:00:00'),
(41, 16, 1, 1, 'WHI-63', 'eret template', 0, '2014-11-19 16:38:15', '2014-11-19 16:38:41', '00:00:00'),
(42, 1, 1, 1, 'SFE-3', 'MEP', 0, '2014-11-19 16:38:38', '2014-11-19 16:59:02', '00:00:00'),
(43, 1, 2, 1, 'WHI-63', 'qsdqsd', 0, '2014-11-19 16:40:39', '2014-11-19 16:54:06', '00:00:00'),
(44, 16, 2, 1, 'WHI-63', 'sdqsf', 0, '2014-11-19 16:46:42', '0000-00-00 00:00:00', '00:00:00'),
(45, 1, 1, 1, 'FNA-2', 'fix conv problem', 0, '2014-11-20 08:01:14', '2014-11-20 08:25:17', '00:00:00'),
(46, 16, 1, 1, 'WHI-63', 'fix eret links', 0, '2014-11-20 08:25:31', '2014-11-20 09:24:10', '00:00:00'),
(47, 1, 1, 1, 'FNA-2', 'Carroussel bug', 0, '2014-11-20 09:24:18', '2014-11-20 10:15:38', '00:00:00'),
(48, 5, 4, 1, 'romulus', 'romulus', 0, '2014-11-20 10:15:00', '2014-11-20 10:40:17', '00:00:00'),
(49, 16, 1, 1, 'WHI-62', 'header both', 0, '2014-11-20 10:40:52', '2014-11-20 13:09:39', '00:00:00'),
(50, 16, 1, 1, 'WHI-63', 'service factorisation', 0, '2014-11-20 13:10:00', '2014-11-20 16:52:03', '00:00:00'),
(51, 16, 1, 1, 'WHI-62', 'fix outlook render', 0, '2014-11-25 08:00:09', '2014-11-25 08:50:17', '00:00:00'),
(52, 9, 1, 1, 'ALS-1', 'create service eret', 0, '2014-11-25 08:50:07', '2014-11-25 13:50:38', '00:00:00'),
(53, 4, 1, 1, 'SFE-5', 'ameliration geoloc', 0, '2014-11-25 13:51:01', '0000-00-00 00:00:00', '00:00:00'),
(54, 16, 1, 1, 'WHI-62', 'eret template', 0, '2014-11-27 08:00:12', '2014-11-27 09:24:53', '00:00:00'),
(55, 4, 1, 1, 'SFE-9', 'geoloc precise', 0, '2014-11-27 09:24:17', '2014-11-27 10:40:09', '00:00:00'),
(56, 1, 1, 1, 'FNA-5', 'blocks recsys', 0, '2014-11-27 10:40:52', '2014-11-27 11:45:42', '00:00:00'),
(57, 16, 1, 1, 'WHI-62', 'template sous outlook', 0, '2014-11-27 13:00:08', '2014-11-27 15:41:46', '00:00:00'),
(58, 4, 1, 1, 'SFE-5', 'testA/B + amelioration', 0, '2014-12-01 10:31:43', '2014-12-01 15:12:02', '00:00:00'),
(59, 1, 1, 1, 'FNA-5', 'Affichage des anciens produits si fallback ou timeout', 0, '2014-12-01 15:11:58', '0000-00-00 00:00:00', '00:00:00'),
(60, 1, 1, 1, 'FNA-5', 'affichage BestSell en cas de fail recsys', 0, '2014-12-02 08:00:53', '2014-12-02 09:00:22', '00:00:00'),
(61, 4, 1, 1, 'SFE-9', 'mep + debug pour mobile', 0, '2014-12-02 09:00:15', '2014-12-02 10:18:35', '00:00:00'),
(62, 5, 1, 1, 'Plateforme', 'Installation locale + comprehension', 0, '2014-12-02 14:00:11', '2014-12-02 15:48:13', '00:00:00'),
(63, 16, 1, 1, 'WHI-62', 'eret links', 0, '2014-12-02 15:48:46', '0000-00-00 00:00:00', '00:00:00'),
(64, 1, 1, 1, 'Recsys', 'chgmt ecom listenner', 0, '2014-12-03 10:36:17', '0000-00-00 00:00:00', '00:00:00'),
(65, 5, 1, 1, 'Plateforme', 'Installation locale', 0, '2014-12-03 10:36:32', '0000-00-00 00:00:00', '00:00:00'),
(66, 16, 1, 1, 'WHI-62', 'correction remindCommand', 0, '2014-12-03 11:11:04', '0000-00-00 00:00:00', '00:00:00'),
(67, 16, 1, 1, 'WHI-62', 'check item & relateds infos', 0, '2014-12-08 08:00:25', '2014-12-08 10:00:27', '00:00:00'),
(68, 4, 1, 1, 'SFE-5', 'support de la touche entrée', 0, '2014-12-08 10:00:16', '0000-00-00 00:00:00', '00:00:00'),
(69, 4, 1, 1, 'SFE-10', 'test meteo', 0, '2014-12-09 11:00:54', '2014-12-09 11:30:26', '00:00:00'),
(70, 4, 1, 1, 'SFE-11', 'fond ecran', 0, '2014-12-09 13:00:10', '2014-12-09 13:30:28', '00:00:00'),
(71, 4, 1, 1, 'SFE-8', 'postit offre decouverte', 0, '2014-12-09 08:00:23', '2014-12-09 11:00:26', '00:00:00'),
(72, 4, 1, 1, 'SFE-4', 'compte rendu chiffres', 0, '2014-12-09 13:30:07', '2014-12-09 16:10:22', '00:00:00'),
(74, 9, 1, 1, 'ALS-1', 'changement expediteur', 0, '2014-12-10 08:00:19', '2014-12-10 09:30:43', '00:00:00'),
(77, 4, 1, 1, 'SFE-8', 'mise en place bop', 0, '2014-12-15 08:19:30', '2014-12-15 08:19:38', '00:00:00'),
(78, 4, 1, 1, 'SFE-6', 'eret capture', 0, '2014-12-15 08:23:40', '0000-00-00 00:00:00', '00:00:00'),
(79, 17, 1, 1, 'TDS-1', 'Exit poll', 0, '2014-12-15 10:13:06', '2014-12-15 14:56:53', '00:00:00'),
(80, 17, 1, 1, 'TDS-1', 'visuels', 0, '2014-12-15 10:31:51', '2014-12-15 14:56:56', '00:00:00'),
(81, 4, 1, 1, 'SFE-9', 'enlèvement de bandit', 0, '2014-12-15 14:56:52', '2014-12-15 14:56:57', '00:00:00'),
(82, 4, 1, 1, 'SFE-8', 'fix bug', 0, '2014-12-15 14:57:22', '2014-12-15 14:57:24', '00:00:00'),
(83, 17, 1, 1, 'TDS-1', 'service2', 0, '2014-12-15 15:51:50', '0000-00-00 00:00:00', '00:00:00'),
(84, 4, 1, 1, 'SFE-8', 'recherche et fix de bug avec CG MR', 0, '2014-12-16 09:00:52', '2014-12-16 13:51:10', '00:00:00'),
(86, 17, 1, 1, 'TDS-2', 'visuels', 0, '2014-12-16 13:52:55', '2014-12-16 13:53:10', '00:00:00'),
(87, 4, 1, 1, 'SFE-8', '', 0, '2014-12-16 13:58:57', '0000-00-00 00:00:00', '00:00:00'),
(90, 4, 1, 1, 'SFE-8', 'test', 0, '2014-12-17 08:17:15', '2014-12-17 10:08:42', '00:00:00'),
(91, 1, 1, 1, 'sdq', 'qg', 0, '2014-12-17 09:12:47', '2014-12-17 13:05:07', '00:00:00'),
(92, 1, 1, 1, 'fdwg', 'f', 0, '2014-12-17 13:04:38', '0000-00-00 00:00:00', '00:00:00'),
(93, 1, 1, 1, 'reaffichage des recos fnac en cas de pb', 'FNA-6', 0, '2014-12-18 08:00:00', '2014-12-18 09:00:00', '00:00:00'),
(94, 17, 1, 1, 'changement d''adresse de test', 'TDS-1', 0, '2014-12-18 09:30:00', '2014-12-18 10:00:00', '00:00:00'),
(95, 17, 1, 1, 'découpe éléments', 'TDS-2', 0, '2014-12-18 10:00:00', '0000-00-00 00:00:00', '00:00:00'),
(98, 1, 1, 1, 'sdgsd', 'hgsqdhesdh', 1, '2014-12-18 10:06:38', '0000-00-00 00:00:00', '00:00:00'),
(99, 4, 1, 1, 'fix avec clement des callers', 'SFE-8', 0, '2014-12-18 09:00:00', '2014-12-18 09:30:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `activityType`
--

CREATE TABLE IF NOT EXISTS `activityType` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `activityType`
--

INSERT INTO `activityType` (`id`, `name`, `color`) VALUES
(1, 'Développement', 'primary'),
(2, 'Relationnel client', 'info'),
(3, 'R&D', 'warning'),
(4, 'Réunion interne', 'success');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

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
(15, 'd160', 'CDB', 'Comtesse Du Barry'),
(16, 'd261', 'WHI', 'Whistles'),
(17, 'd60', 'TDS', 'Teddy Smith');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

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
(12, 4, '2014-11-15 09:15:40', '0000-00-00 00:00:00', '00:00:00'),
(13, 14, '2014-11-19 08:35:14', '0000-00-00 00:00:00', '00:00:00');

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
