-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: sql8.netmark.pl:3306
-- Czas wygenerowania: 12 Gru 2016, 00:54
-- Wersja serwera: 5.1.65-cll
-- Wersja PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `filipmar_eaglecms`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_items`
--

CREATE TABLE IF NOT EXISTS `eagle_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `header_1_pl` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_1_en` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_2_pl` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_2_en` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_3_pl` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_3_en` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_4_pl` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_4_en` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_5_pl` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `header_5_en` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `content_1_pl` text COLLATE utf8_polish_ci,
  `content_1_en` text COLLATE utf8_polish_ci,
  `content_2_pl` text COLLATE utf8_polish_ci,
  `content_2_en` text COLLATE utf8_polish_ci,
  `content_3_pl` text COLLATE utf8_polish_ci,
  `content_3_en` text COLLATE utf8_polish_ci,
  `category` int(10) unsigned NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
