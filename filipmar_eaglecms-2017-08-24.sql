-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: sql8.netmark.pl:3306
-- Czas generowania: 24 Sie 2017, 21:34
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
(6, 0, 1, 'Born to Rule', '', 40),
(7, 0, 1, 'Hammer of Justice', '', 60),
(8, 0, 1, 'A Legend Reborn', '', 50),
(9, 0, 1, 'Way of the Warrior', '', 70),
(10, 0, 1, 'Hearts on Fire', '', 80),
(11, 0, 1, 'Blood Bound', '', 90),
(12, 0, 1, 'Ravenlord', '', 100);

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
(1, 1, 0, 'Duda', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus.', NULL, NULL, NULL, NULL, NULL, '1,5,6,10,11,12', 1, 20),
(5, 1, 0, 'Zaraz zjem pyszne de voleille', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus.', NULL, NULL, NULL, NULL, NULL, '1,4,5,10,11,12', 1, 30),
(6, 1, 0, 'Sialalalaal - tu był dzik elo elo 23', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. 23', NULL, NULL, NULL, NULL, NULL, '1,4,5,6,10,11,12', 0, 10),
(8, 1, 0, 'No tak - przetapiam sztabki ~ dzik', NULL, 'Sialala działa!', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus.', NULL, NULL, NULL, NULL, NULL, '1,4,5,6', 1, 50),
(9, 2, 0, 'Lorem ipsum dolor sit amet neque.', '', '', '', '', '', '', '', '', '', '<p>Lorem ipsum dolor sit amet tellus. Aliquam gravida at, consequat vel, ornare id, nunc. Nunc sed justo consequat auctor quis, porttitor a, volutpat metus. Integer adipiscing mauris. Lorem ipsum primis in faucibus volutpat, velit non augue. Nulla et accumsan urna, id arcu. Maecenas eget leo eu libero. Donec faucibus, nulla ac erat. In turpis augue, id elementum euismod.</p>\r\n\r\n<p>Integer id nisl. Fusce aliquam turpis. Donec faucibus, erat sed enim. Cras dictum volutpat. Curabitur imperdiet, urna mattis neque. Etiam varius, scelerisque pede quis lorem ligula, elementum dui. Mauris convallis accumsan, libero odio lobortis velit. Vivamus ut porta neque, malesuada fames ac turpis vitae leo. Quisque et tellus. Quisque tortor. Phasellus sit amet.</p>\r\n\r\n<p>Pellentesque egestas ac, varius nec, arcu. Nam non enim quis venenatis nulla ut porta ac, vulputate mi, ut leo. Maecenas pulvinar mollis. Nulla augue pulvinar vulputate tortor venenatis blandit malesuada. Nullam in faucibus sit amet, massa. Nunc sed lorem id eros eros quis enim sed libero. Proin porttitor egestas. Cras non felis. Nam pharetra volutpat. Nam ultrices. Sed aliquet tincidunt tellus hendrerit metus feugiat nec, hendrerit et, scelerisque eu, cursus dignissim nibh. Etiam dapibus aliquam. Nunc elementum. Nunc a leo vitae felis blandit iaculis, diam placerat nec, nibh. Sed interdum consectetuer at, quam. Aliquam risus ut aliquet commodo ante. Duis mauris orci, aliquam euismod orci ac risus. Sed sed leo. Nulla diam lorem, id mi. Nam diam. Aliquam sem. Sed mattis. Pellentesque orci luctus elit, pulvinar semper egestas. Mauris vitae ante. Vivamus ornare, orci interdum euismod nulla ut venenatis lorem dapibus eget, blandit eros, varius quis.</p>', '', '', '', '', '', NULL, 1, 20),
(10, 2, 0, 'Litwo! Ojczyzno moja! Ty jesteś jak zdrowie.', '', '', '', '', '', '', '', '', '', '<p>Litwo! Ojczyzno moja! Ty jesteś jak zdrowie. Nazywał się na nim odszedł, wyskoczył na prawo, koziołka, z kształtu, jeśli nasza młodzie wyjeżdża za rarogiem zazdroszczono domowi, przed laty tenże sam król ją nudzi rzecz o tem gadać u wieczerzy będzie z lasu bawić się możemy na drugim końcu śród biesiadników siedział słuchał rozmowy grzeczne z ziemi włoskiej stara się ramieniu. Przeprosiwszy go nie zaś Gotem. Dość, że były czary przeciw czarów. Raz w tem nic nie po francusku. Biegali wszyscy siedli i czuł, że mi się nieco wylotów kontusza nalał węgrzyna.</p>\r\n\r\n<p>Przypadkiem oczy sąd, strony obie złotą na tem, Że nie zabawia przez płotki, przez Niemen rzekł: Do zobaczenia! tak mędrsi fircykom oprzeć się rąk muskała włosów pukle nie uszło baczności, Że gościnna i Sędziem przyszła nagle taż chętka, nie zarzuci, bym uchybił kom w ciąg powieści, pytań wykrzykników i po samotnej łące. Śród nich opis zwycięstwa lub papugą w której nigdy nie zabawia przez nosy, a między wierzycieli. Zamku żaden wziąść nie może. Widać, że Hrabia z któremi się dawniej zdały. I przyjezdny gość, krewny pański i stoi wypisany.</p>\r\n\r\n<p>Juracha z łowów wracając trafia się, że gotyckiej są architektury. Choć pani ta chwała należy chartu Sokołowi. Pytano zdania innych. więc szanują przyjaciół jak dziecko przestraszone we łzach i cztery ich się nagle, stronnicy Sokół na Tadeusza, rzucił wzrok jak mnich na siano. w nié dzwonił, znak dawał, że zdradza! Taka była żałoba, tylko się chce rozbierać. Woźny cicho wszedł.</p>', '', '', '', '', '', NULL, 1, 10),
(11, 1, 0, 'Sralala EagleCMS sie powoli będzie rozpędzał', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet', NULL, NULL, NULL, NULL, NULL, '0', 1, 60),
(12, 1, 0, 'No śmiga aż miło', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '0', 1, 70),
(13, 1, 0, 'Elegancko', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '0', 1, 80),
(16, 1, 0, 'Rower to dobra sprawa', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gdzie bursztynowy świerzop, gryka jak mnie to mówiąc, że zna równie chwytny. Chwytny? - Białe jej nie stało się. dziewica krzyknęła boleśnie niewyraźnie, jak śnieg biała gdzie podział się? szukać prawodawstwa w zastępstwie gospodarza, gdy przysięgał na boku. Panny tuż na swym dworze. Nikt go powitać. Dawno domu podobnych spraw bernardyńskie. cóż o wiejskiego pożycia nudach i Waszeć z palcami zadzwonił tabakiera ze cztery. Tymczasem na partyję Kusego bez żadnych ozdób, ale nigdzie nie mógł. Jak go nie stało wody pełne naczynie blaszane ale widzę mniej wielkie, mniej krzykliwy i kiedy do Warszawy! He! Ojczyzna! Ja nie może. Widać, że Hrabia ma narowu, Żałował, że przeszkadza kulturze, że go.', NULL, NULL, NULL, NULL, NULL, '10,11,12', 1, 90),
(19, 1, 0, 'Ravenlooooooord', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, '0', 1, 100),
(21, 3, 1, 'Lorem ipsum', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Znowu oczywiście 2 w nocy a ja ostro kodze ehh', NULL, NULL, NULL, NULL, NULL, NULL, 1, 10),
(22, 3, 1, 'Somebody!', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jakis tekst lorem ipsum dolor sit amet sit amaty no i co teraz', NULL, NULL, NULL, NULL, NULL, NULL, 1, 20),
(23, 3, 5, 'Dolor sit amet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lorem ipsum dolor sit amet', NULL, NULL, NULL, NULL, NULL, NULL, 1, 30);

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
(1, 'rotmistrz', 'filip.markiewicz96@gmail.com', '$2y$10$EZPA9bslxuu3p1pEUKX/.OqUTZhCuIaU3aA1s7Z783OeB7dqOhfIK');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `eagle_categories`
--
ALTER TABLE `eagle_categories`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT dla tabeli `eagle_items`
--
ALTER TABLE `eagle_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT dla tabeli `eagle_users`
--
ALTER TABLE `eagle_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
