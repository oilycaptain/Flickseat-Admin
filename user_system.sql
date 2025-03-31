-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 09:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'wala', '$2y$10$HEloKYH7CuVtc0H.suEJ1u2kfGdCURYXIFYy3PVj9yFJay./GdW6e'),
(2, 'nash', '$2y$10$vPXFifaMh15z5pIwdreuM.gJZhAf77Fy8YfOk52Gt6dbRSh1l7mmS'),
(4, 'flickadmin', '$2y$10$2fOcTV1U3qJxddM1JiDQrOlqnBVucWOA5XD3Uk81v0d5h4o4S.d.W'),
(6, 'asd', '$2y$10$i/qu5rr0wQLIFgnpvMuOH.8cmvB3PKDNcEEEnT.vp/7hpLLpyCYei'),
(7, 'pok', '$2y$10$sINGBGYrJeUwNhff2CxnYOYOVz7vhuiqcCaOETvpS5v.t/HwHjHi.'),
(8, 'nm', '$2y$10$PSdls90QVPvsv5hGCQAWw.GQlSG.lw.jH7m0BGUsEgF8aGTG8JqSq'),
(9, 'waasdasd', '$2y$10$iemBYgZ3wXxp6aLrfusWx.Eb/fXtXW96HFcHvJ5swqI.d3NtT9nPC'),
(10, 'hj', '$2y$10$DWD9qcwEx3QRwCbAyJ0xWOHWk9uQpY0glFeUBCxMug.VNwAl.ZNS6'),
(11, 'as', '$2y$10$/S89JuemVLIsdepgAclBG.CttPXRw1B1k2ev.fXkIdoBmEZ9r5Usm'),
(12, 'tyu', '$2y$10$ZpFhY09YlozQXqfxhtLPUumHU/i2VXvx3ohGXcwye8YP8uMQpWeFy'),
(13, 'gh', '$2y$10$Wz5wyuMQ2MJz/UASiD8niek/6DUOmTKXBy0othThFwJ8gyRawHV3C'),
(14, 'ty', '$2y$10$8kz5cW3vBd79pdFA1hFln.QJU8MV8cVbiGWZ7mXmj8.tVPFwj4S8W'),
(15, 'hjk', '$2y$10$Dc4rZ/1QQpJNW/NoM3ufluJyci/h4Ec9unnhFskL/4jt9FZT.UCeS'),
(16, 'ert', '$2y$10$1iXGjda9XG/nMdz4VocAk.D5LlUHjI9fYkAYTVw8w8JGF2bG7pcSq'),
(17, 'wawa', '$2y$10$YPSAyYbOnAlp5E.B1TgF8OxjkfQOeNZVZ4buADHLChk5rMu5SZjJ.'),
(18, 'asdasd', '$2y$10$3afhRWMJhjbMT2QbE/h/BeKZwAltpMfkuC1Hl9CL93wkuvaUGMv0O');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
