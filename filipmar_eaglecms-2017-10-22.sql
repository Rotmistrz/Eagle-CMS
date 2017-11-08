-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: sql8.netmark.pl:3306
-- Czas generowania: 23 Paź 2017, 10:28
-- Wersja serwera: 5.5.52-cll
-- Wersja PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `filipmar_eaglecms`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_categories`
--

CREATE TABLE `eagle_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `header_1_pl` tinytext COLLATE utf8_polish_ci NOT NULL,
  `header_1_en` tinytext COLLATE utf8_polish_ci NOT NULL,
  `sort` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_galleries`
--

CREATE TABLE `eagle_galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8_polish_ci DEFAULT NULL,
  `title_pl` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `title_en` varchar(64) COLLATE utf8_polish_ci DEFAULT NULL,
  `description_pl` tinytext COLLATE utf8_polish_ci,
  `description_en` tinytext COLLATE utf8_polish_ci,
  `date` datetime NOT NULL,
  `sort` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_items`
--

CREATE TABLE `eagle_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
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
  `category` tinytext COLLATE utf8_polish_ci,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_users`
--

CREATE TABLE `eagle_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `email` tinytext COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `eagle_categories`
--
ALTER TABLE `eagle_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eagle_galleries`
--
ALTER TABLE `eagle_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eagle_items`
--
ALTER TABLE `eagle_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eagle_users`
--
ALTER TABLE `eagle_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `eagle_categories`
--
ALTER TABLE `eagle_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT dla tabeli `eagle_galleries`
--
ALTER TABLE `eagle_galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT dla tabeli `eagle_items`
--
ALTER TABLE `eagle_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT dla tabeli `eagle_users`
--
ALTER TABLE `eagle_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
