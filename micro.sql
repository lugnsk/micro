-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 19, 2014 at 02:18 PM
-- Server version: 5.5.39-MariaDB
-- PHP Version: 5.5.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `micro`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
`id` int(11) NOT NULL,
  `name` varchar(127) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `name`, `content`) VALUES
(1, 'setupher', 'setup create has been modified.'),
(2, 'setuper', 'setup create has been modified.'),
(3, 'Missis Garrison', 'good night white pride'),
(4, 'Ð¿Ñ€Ð¾ÑÑ‚Ð¾', 'ÑƒÐ´Ð¾Ð±Ð½Ð¾ Ð±Ñ‹ÑÑ‚Ñ€Ð¾'),
(5, 'Ð¼ÐµÑ€Ð»Ð¸Ð½', 'Ð²Ð¾Ð»ÑˆÐµÐ±Ð½Ð¸Ðº Ð¼ÐµÐ½ÑÐ¾Ð½'),
(6, 'Ð¿ÑƒÑ‚Ð¸Ð½', 'Ð¿Ñ€Ð¸Ð·ÐµÐ´ÐµÐ½Ñ‚ Ð¼ÐµÐ´Ð²ÐµÐ´ÐµÐ²Ð°'),
(7, 'Ð¼Ð°ÑˆÐ° ÐºÐ°Ð»Ð°ÑˆÐ°', 'Ð½Ðµ Ñ…Ð¾Ñ‚ÐµÐ»Ð° ÐºÑƒÑˆÐ°Ñ‚ÑŒ ÐºÐ°ÑˆÑƒ'),
(8, 'Ð¿Ñ€Ð¸Ð²ÐµÑ‚', 'Ñ Ð¿Ñ€Ð¾ÑÑ‚Ð¾ Ð±Ð»Ð¾Ð³'),
(9, 'Ð¿Ñ€Ð¸Ð²ÐµÑ‚', 'Ñ Ð¿Ñ€Ð¾ÑÑ‚Ð¾ Ð¿ÑƒÑ‚Ð¸Ð½ÐºÐ°'),
(10, 'Ð³Ð°Ð³Ð°Ñ€Ð¸Ð½', 'Ð¿ÐµÑ€Ð²Ñ‹Ð¹ Ñ‡ÐµÐ»Ð¾Ð²ÐµÐº Ð½Ð° Ð»ÑƒÐ½Ðµ'),
(11, 'ÐµÐ»ÑŒÑ†Ð¸Ð½', 'ÑÑ‚Ð°Ñ€Ð¸Ðº Ñ…Ð¾Ñ€Ð¾ÑˆÐ¸Ð¹'),
(12, 'Привет', 'привет привет');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `email` varchar(55) NOT NULL,
  `login` varchar(55) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `fio` varchar(127) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `login`, `pass`, `fio`) VALUES
(1, 'antivir88@mail.ru', 'Zcasper', '8a3a8e838b95a342876677db3692f323', 'Олег Лунегов'),
(2, 'zcasperx@gmail.com', 'Xcasper', '8a3a8e838b95a342876677db3692f323', 'Casper Phantom Antivir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
