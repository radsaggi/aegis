-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 10, 2013 at 02:32 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Table structure for table `Companies`
--

CREATE TABLE IF NOT EXISTS `Companies` (
  `Company ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The Unique Company ID created by the database',
  `Company Name` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `Contact Name` char(26) NOT NULL,
  `Contact Number` char(13) NOT NULL,
  `Contact Designation` varchar(20) NOT NULL,
  `Last Meeting` date NOT NULL,
  `Next Meeting` date NOT NULL,
  `Sponsorship Category` int(11) NOT NULL,
  `Last Response` int(11) DEFAULT NULL,
  `Sponsorship For` varchar(50) DEFAULT NULL,
  `Probability Index ID` int(11) NOT NULL,
  PRIMARY KEY (`Company ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CompanyStudentAllocations`
--

CREATE TABLE IF NOT EXISTS `CompanyStudentAllocations` (
  `Student ID` int(11) NOT NULL,
  `Company ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE IF NOT EXISTS `Notifications` (
  `Notification ID` int(11) NOT NULL AUTO_INCREMENT,
  `Student ID` int(11) NOT NULL COMMENT 'Student who will receive the notification',
  `Type` varchar(11) NOT NULL,
  `Title` varchar(25) NOT NULL COMMENT 'Title of Notification',
  `Message` varchar(100) NOT NULL,
  `ID` int(11) NOT NULL COMMENT 'Comany ID related to the notification; this is the redirect indicator',
  `Show` varchar(5) NOT NULL,
  PRIMARY KEY (`Notification ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Probability Index`
--

CREATE TABLE IF NOT EXISTS `Probability Index` (
  `Probability Index ID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(50) NOT NULL,
  PRIMARY KEY (`Probability Index ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Responses`
--

CREATE TABLE IF NOT EXISTS `Responses` (
  `Response ID` int(11) NOT NULL AUTO_INCREMENT,
  `Company ID` int(11) NOT NULL,
  `Student ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Meeting Number` int(11) NOT NULL,
  `Previous Response` int(11) DEFAULT NULL,
  `Next Response` int(11) DEFAULT NULL,
  `Response` longtext NOT NULL,
  PRIMARY KEY (`Response ID`),
  UNIQUE KEY `Response ID` (`Response ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SponsorshipCategories`
--

CREATE TABLE IF NOT EXISTS `SponsorshipCategories` (
  `Category ID` int(11) NOT NULL AUTO_INCREMENT,
  `Category Name` varchar(10) NOT NULL,
  `Minimum Amount` int(11) NOT NULL,
  `Maximum Amount` int(11) NOT NULL,
  `Incentives` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Category ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StudentSenior`
--

CREATE TABLE IF NOT EXISTS `StudentSenior` (
  `Student ID` int(11) NOT NULL AUTO_INCREMENT,
  `Student Name` varchar(30) NOT NULL,
  `College Roll Number` char(8) NOT NULL,
  `Contact Number` char(10) NOT NULL,
  `College Email` varchar(20) NOT NULL,
  `Alternate Email` varchar(30) NOT NULL,
  PRIMARY KEY (`Student ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StudentVolunteer`
--

CREATE TABLE IF NOT EXISTS `StudentVolunteer` (
  `Student ID` int(11) NOT NULL AUTO_INCREMENT,
  `Student Name` varchar(30) NOT NULL,
  `College Roll Number` char(8) NOT NULL,
  `Contact Number` char(10) NOT NULL,
  `College Email` varchar(20) NOT NULL,
  `Alternate Email` varchar(30) NOT NULL,
  PRIMARY KEY (`Student ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `Student ID` int(11) NOT NULL,
  `Student Username` varchar(10) NOT NULL,
  `Salt` varchar(50) NOT NULL,
  `Hash` varchar(60) NOT NULL,
  PRIMARY KEY (`Student ID`),
  UNIQUE KEY `Student Username` (`Student Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


