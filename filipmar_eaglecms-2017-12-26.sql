-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: sql8.netmark.pl:3306
-- Czas generowania: 26 Gru 2017, 15:10
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

--
-- Zrzut danych tabeli `eagle_categories`
--

INSERT INTO `eagle_categories` (`id`, `parent_id`, `type`, `header_1_pl`, `header_1_en`, `sort`) VALUES
(1, 0, 1, 'Lorem ipsum', '', 10),
(4, 0, 1, 'Stronger than all', '', 20),
(6, 0, 1, 'Born to Rule', '', 50),
(7, 0, 1, 'Hammer of Justice', '', 60),
(8, 0, 1, 'A Legend Reborn', '', 40),
(9, 0, 1, 'Way of the Warrior', '', 70),
(10, 0, 1, 'Hearts on Fire', '', 80),
(11, 0, 1, 'Blood Bound', '', 90),
(12, 0, 1, 'Ravenlord', '', 100),
(13, 0, 1, 'Iksded', '', 110);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_data_defined`
--

CREATE TABLE `eagle_data_defined` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `value_pl` tinytext COLLATE utf8_polish_ci,
  `value_en` tinytext COLLATE utf8_polish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `eagle_data_defined`
--

INSERT INTO `eagle_data_defined` (`id`, `code`, `value_pl`, `value_en`) VALUES
(1, 'phone_00', '726 124 381', '726 124 381'),
(2, 'phone_01', '123 456 789', '123 456 789'),
(3, 'hop', 'Siup siup hop', NULL),
(5, 'kolejna', 'Kolejna dana', NULL);

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

--
-- Zrzut danych tabeli `eagle_galleries`
--

INSERT INTO `eagle_galleries` (`id`, `item_id`, `type`, `title_pl`, `title_en`, `description_pl`, `description_en`, `date`, `sort`) VALUES
(29, 1, '1', 'Bum bum bum cyk cyk', NULL, '', NULL, '2017-10-14 23:41:04', 90),
(53, 0, 'image/jpeg', 'Untill the end of time', NULL, '', NULL, '2017-11-27 21:50:59', 10),
(48, 1, 'image/jpeg', 'Hop siup', NULL, '', NULL, '2017-10-15 15:02:12', 60),
(33, 1, 'image/jpeg', '', '', '', '', '2017-10-15 00:19:00', 120),
(50, 11, 'image/jpeg', '', NULL, '', NULL, '2017-10-16 09:51:18', 10),
(49, 1, 'image/jpeg', '', '', '', '', '2017-10-15 15:02:19', 10);

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

--
-- Zrzut danych tabeli `eagle_items`
--

INSERT INTO `eagle_items` (`id`, `type`, `parent_id`, `header_1_pl`, `header_1_en`, `header_2_pl`, `header_2_en`, `header_3_pl`, `header_3_en`, `header_4_pl`, `header_4_en`, `header_5_pl`, `header_5_en`, `content_1_pl`, `content_1_en`, `content_2_pl`, `content_2_en`, `content_3_pl`, `content_3_en`, `category`, `visible`, `sort`) VALUES
(1, 1, 0, 'Ipsum dolor', '', '', '', '', '', '', '', '', '', '<p style=\"text-align: center; \"><strong>Hop siup lalala</strong></p>', '', '<p style=\"text-align: right; \"><em>Bm cyk cyk</em></p>', '', '', '', '1,4,6,7', 1, 10),
(9, 2, 0, 'Lorem ipsum dolor sit amet neque.', '', '', '', '', '', '', '', '', '', '<p>Lorem ipsum dolor sit amet tellus. Aliquam gravida at, consequat vel, ornare id, nunc. Nunc sed justo consequat auctor quis, porttitor a, volutpat metus. Integer adipiscing mauris. Lorem ipsum primis in faucibus volutpat, velit non augue. Nulla et accumsan urna, id arcu. Maecenas eget leo eu libero. Donec faucibus, nulla ac erat. In turpis augue, id elementum euismod.</p>\r\n\r\n<p>Integer id nisl. Fusce aliquam turpis. Donec faucibus, erat sed enim. Cras dictum volutpat. Curabitur imperdiet, urna mattis neque. Etiam varius, scelerisque pede quis lorem ligula, elementum dui. Mauris convallis accumsan, libero odio lobortis velit. Vivamus ut porta neque, malesuada fames ac turpis vitae leo. Quisque et tellus. Quisque tortor. Phasellus sit amet.</p>\r\n\r\n<p>Pellentesque egestas ac, varius nec, arcu. Nam non enim quis venenatis nulla ut porta ac, vulputate mi, ut leo. Maecenas pulvinar mollis. Nulla augue pulvinar vulputate tortor venenatis blandit malesuada. Nullam in faucibus sit amet, massa. Nunc sed lorem id eros eros quis enim sed libero. Proin porttitor egestas. Cras non felis. Nam pharetra volutpat. Nam ultrices. Sed aliquet tincidunt tellus hendrerit metus feugiat nec, hendrerit et, scelerisque eu, cursus dignissim nibh. Etiam dapibus aliquam. Nunc elementum. Nunc a leo vitae felis blandit iaculis, diam placerat nec, nibh. Sed interdum consectetuer at, quam. Aliquam risus ut aliquet commodo ante. Duis mauris orci, aliquam euismod orci ac risus. Sed sed leo. Nulla diam lorem, id mi. Nam diam. Aliquam sem. Sed mattis. Pellentesque orci luctus elit, pulvinar semper egestas. Mauris vitae ante. Vivamus ornare, orci interdum euismod nulla ut venenatis lorem dapibus eget, blandit eros, varius quis.</p>', '', '', '', '', '', NULL, 1, 20),
(10, 2, 0, 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie.', '', '', '', '', '', '', '', '', '', '<p>Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Nazywał się na nim odszedł, wyskoczył na prawo, koziołka, z kształtu, jeśli nasza młodzie wyjeżdża za rarogiem zazdroszczono domowi, przed laty tenże sam król ją nudzi rzecz o tem gadać u wieczerzy będzie z lasu bawić się możemy na drugim końcu śród biesiadników siedział słuchał rozmowy grzeczne z ziemi włoskiej stara się ramieniu. Przeprosiwszy go nie zaś Gotem. Dość, że były czary przeciw czarów. Raz w tem nic nie po francusku. Biegali wszyscy siedli i czuł, że mi się nieco wylotów kontusza nalał węgrzyna.</p>\r\n\r\n<p>Przypadkiem oczy sąd, strony obie złotą na tem, Że nie zabawia przez płotki, przez Niemen rzekł: Do zobaczenia! tak mędrsi fircykom oprzeć się rąk muskała włosów pukle nie uszło baczności, Że gościnna i Sędziem przyszła nagle taż chętka, nie zarzuci, bym uchybił kom w ciąg powieści, pytań wykrzykników i po samotnej łące. Śród nich opis zwycięstwa lub papugą w której nigdy nie zabawia przez nosy, a między wierzycieli. Zamku żaden wziąść nie może. Widać, że Hrabia z któremi się dawniej zdały. I przyjezdny gość, krewny pański i stoi wypisany.</p>\r\n\r\n<p>Juracha z łowów wracając trafia się, że gotyckiej są architektury. Choć pani ta chwała należy chartu Sokołowi. Pytano zdania innych. więc szanują przyjaciół jak dziecko przestraszone we łzach i cztery ich się nagle, stronnicy Sokół na Tadeusza, rzucił wzrok jak mnich na siano. w nié dzwonił, znak dawał, że zdradza! Taka była żałoba, tylko się chce rozbierać. Woźny cicho wszedł.</p>', '', '', '', '', '', NULL, 1, 10),
(21, 3, 1, 'Lorem ipsum edited', '', '', '', '', '', '', '', '', '', 'Znowu oczywiście 2 w nocy a ja ostro kodze ehh', '', '', '', '', '', '', 1, 20),
(22, 3, 0, 'Somebody! edited', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jakis tekst lorem ipsum dolor sit amet sit amaty no i co teraz', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10),
(23, 3, 5, 'Dolor sit amet', '', '', '', '', '', '', '', '', '', 'Lorem ipsum dolor sit amet', '', '', '', '', '', '', 1, 40),
(26, 1, 0, 'Jeszcze nowszy hop', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 20),
(27, 4, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10),
(29, 1, 0, 'Forteca - Znani nieZnani', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '4,6,7', 1, 70),
(30, 1, 0, 'Forteca - Jedyna taka bitwa', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 80),
(31, 3, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 50),
(32, 3, 0, 'Forteca - Niedźwiadek', '', '', '', '', '', '', '', '', '', 'Hop siup', '', '', '', '', '', '', 1, 60),
(33, 3, 1, 'Może teraz', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 70),
(34, 3, 1, 'Forteca - Bezimienni bohaterowie', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 30),
(35, 1, 0, 'Iron Maiden - When Two Worlds Collide', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', NULL, NULL, NULL, NULL, 1, 90),
(37, 1, 0, 'New one', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', NULL, NULL, NULL, NULL, 1, 100);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eagle_pages`
--

CREATE TABLE `eagle_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `title_pl` tinytext COLLATE utf8_polish_ci,
  `title_en` tinytext COLLATE utf8_polish_ci,
  `description_pl` tinytext COLLATE utf8_polish_ci,
  `description_en` tinytext COLLATE utf8_polish_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `eagle_pages`
--

INSERT INTO `eagle_pages` (`id`, `slug`, `title_pl`, `title_en`, `description_pl`, `description_en`) VALUES
(1, 'subpage', 'Podstrona', 'Subpage', NULL, NULL),
(2, 'hop', 'Hop siup', NULL, '', NULL),
(16, 'Nowaaaaa', 'Nowa', NULL, '', NULL),
(10, 'general', 'Ooooo', NULL, '', NULL),
(11, 'podniebny-as', 'Podniebny as', NULL, '', NULL),
(13, 'new-page', 'Nowa strona', NULL, 'Hop siup siup lorem', NULL);

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
-- Zrzut danych tabeli `eagle_users`
--

INSERT INTO `eagle_users` (`id`, `login`, `email`, `password`) VALUES
(1, 'rotmistrz', 'filip.markiewicz96@gmail.com', '$2y$10$plobAjrWkMfOunhjBxKVAOcrnExNsNU6hN9K9Jom942B9q8/6dQGe');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `eagle_categories`
--
ALTER TABLE `eagle_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eagle_data_defined`
--
ALTER TABLE `eagle_data_defined`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

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
-- Indexes for table `eagle_pages`
--
ALTER TABLE `eagle_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slug` (`slug`);

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
-- AUTO_INCREMENT dla tabeli `eagle_data_defined`
--
ALTER TABLE `eagle_data_defined`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT dla tabeli `eagle_galleries`
--
ALTER TABLE `eagle_galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT dla tabeli `eagle_items`
--
ALTER TABLE `eagle_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT dla tabeli `eagle_pages`
--
ALTER TABLE `eagle_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT dla tabeli `eagle_users`
--
ALTER TABLE `eagle_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
