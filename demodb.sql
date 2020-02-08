-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2020 at 04:57 PM
-- Server version: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demodb`
--
CREATE DATABASE IF NOT EXISTS `demodb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `demodb`;

-- --------------------------------------------------------

--
-- Table structure for table `jp_user`
--

CREATE TABLE `jp_user` (
  `userid` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `uname` char(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jp_user`
--
ALTER TABLE `jp_user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jp_user`
--
ALTER TABLE `jp_user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
