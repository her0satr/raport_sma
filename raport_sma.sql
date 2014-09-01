-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2014 at 12:59 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `raport_sma`
--
CREATE DATABASE IF NOT EXISTS `raport_sma` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `raport_sma`;

-- --------------------------------------------------------

--
-- Table structure for table `discipline`
--

CREATE TABLE IF NOT EXISTS `discipline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `discipline`
--

INSERT INTO `discipline` (`id`, `title`) VALUES
(1, 'PPKn'),
(2, 'Bahasa Indonesia'),
(3, 'Bahasa Inggris'),
(4, 'Matematika');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `uh` int(11) NOT NULL,
  `uts` int(11) NOT NULL,
  `uas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `tahun`, `semester`, `student_id`, `discipline_id`, `uh`, `uts`, `uas`) VALUES
(3, 2013, 1, 3, 2, 61, 62, 63),
(4, 2013, 1, 3, 3, 70, 80, 80);

-- --------------------------------------------------------

--
-- Table structure for table `homeroom`
--

CREATE TABLE IF NOT EXISTS `homeroom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `homeroom`
--

INSERT INTO `homeroom` (`id`, `tahun`, `semester`, `teacher_id`) VALUES
(2, 2013, 1, 4),
(3, 2013, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `nisn` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `name`, `nisn`, `address`, `birthdate`, `birthplace`, `gender`, `phone`) VALUES
(2, 'Budiman', '215488', 'Malang', '2014-08-26', 'Surabaya', 'l', '034'),
(3, 'Siska', '00221144', '', '2014-08-01', '', 'l', '0341');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `birthdate` text NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `gender`, `birthdate`, `birthplace`, `address`, `phone`) VALUES
(3, 'Bu Ratmi', 'p', '2014-08-01', 'Malang', 'Sukun', '0341'),
(4, 'Pak Budi', 'l', '2014-08-07', 'Palembang', '-', '033'),
(5, 'Yogi', 'l', '2014-05-06', '-', '-', '-');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_display` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `user_display`, `user_pass`, `is_active`) VALUES
(1, 'admin', 'Administrator', 'dbbe81d7daaf1e5792a698a54f45c2de', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
