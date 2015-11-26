-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2015 at 03:34 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ktph`
--

-- --------------------------------------------------------

--
-- Table structure for table `mockdata`
--

CREATE TABLE IF NOT EXISTS `mockdata` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `NRIC` varchar(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `ScreeningDate` varchar(30) NOT NULL,
  `HealthStatus` varchar(30) NOT NULL,
  `PolyVisitDate` varchar(30) NOT NULL,
  `HouseVisitDate` varchar(30) NOT NULL,
  `TeleconsultDate` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `mockdata`
--

INSERT INTO `mockdata` (`id`, `NRIC`, `Name`, `ScreeningDate`, `HealthStatus`, `PolyVisitDate`, `HouseVisitDate`, `TeleconsultDate`) VALUES
(1, '1', 'Justin', '2013-11-24', 'Unhealthy', '2014-02-02', '2013-12-24', 'NA'),
(2, '1', 'Justin', '2014-05-24', 'Healthy', 'NA', 'NA', 'NA'),
(3, '2', 'Ken', '2013-10-24', 'Unhealthy', 'NA', '2014-01-24', '2013-11-28'),
(4, '2', 'Ken', '2014-06-24', 'Unhealthy', 'NA', 'NA', '2014-07-24'),
(5, '3', 'Jasmine', '2014-05-24', 'Unhealthy', 'NA', 'NA', 'NA'),
(6, '3', 'Kenji', '2014-05-24', 'Unhealthy', 'NA', 'NA', 'NA'),
(7, '3', 'Bastari', '2014-05-24', 'Unhealthy', 'NA', 'NA', 'NA'),
(8, '3', 'Yee Boon', '2014-05-24', 'Unhealthy', 'NA', 'NA', 'NA'),
(9, '3', 'Eric', '2014-05-24', 'Unhealthy', 'NA', 'NA', 'NA');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
