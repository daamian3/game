-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 25 Lis 2018, 22:52
-- Wersja serwera: 10.1.31-MariaDB
-- Wersja PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `game`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `adventures`
--

CREATE TABLE `adventures` (
  `ID` tinyint(4) NOT NULL,
  `name` varchar(24) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `adventures`
--

INSERT INTO `adventures` (`ID`, `name`, `description`) VALUES
(1, 'Jaskinie wikingów', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
(2, 'Kopalnie gnomów', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
(3, 'Wieża bogactw', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
(4, 'Łąki śmierci', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'),
(5, 'Niebiosa aniołów', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `classes`
--

CREATE TABLE `classes` (
  `ID` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `vitality` float NOT NULL,
  `strength` float NOT NULL,
  `intelligence` float NOT NULL,
  `agility` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `classes`
--

INSERT INTO `classes` (`ID`, `name`, `vitality`, `strength`, `intelligence`, `agility`) VALUES
(1, 'warrior', 1.04, 1.02, 1, 1),
(2, 'ranger', 1.03, 1, 1, 1.03),
(3, 'mage', 1.01, 1, 1.05, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dungeons`
--

CREATE TABLE `dungeons` (
  `ID` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `stage_1` int(11) NOT NULL,
  `stage_2` int(11) NOT NULL,
  `stage_3` int(11) NOT NULL,
  `stage_4` int(11) NOT NULL,
  `stage_5` int(11) NOT NULL,
  `stage_6` int(11) NOT NULL,
  `stage_7` int(11) NOT NULL,
  `stage_8` int(11) NOT NULL,
  `stage_9` int(11) NOT NULL,
  `stage_10` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `dungeons`
--

INSERT INTO `dungeons` (`ID`, `name`, `stage_1`, `stage_2`, `stage_3`, `stage_4`, `stage_5`, `stage_6`, `stage_7`, `stage_8`, `stage_9`, `stage_10`) VALUES
(1, 'Basements', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
(2, 'Catacombs', 11, 12, 13, 14, 15, 16, 17, 18, 19, 20),
(3, 'Snakeground', 21, 22, 23, 24, 25, 26, 27, 28, 29, 30),
(4, 'Spidercave', 31, 32, 30, 34, 35, 36, 37, 38, 39, 40),
(5, 'Heaven', 41, 42, 43, 44, 45, 46, 47, 48, 49, 50),
(6, 'Demon Tower', 51, 52, 53, 54, 55, 56, 57, 58, 59, 60);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `eq`
--

CREATE TABLE `eq` (
  `ID` int(11) NOT NULL,
  `name` varchar(24) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `hero_id` int(11) NOT NULL,
  `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `attack_min` smallint(6) NOT NULL,
  `attack_max` smallint(6) NOT NULL,
  `defense` tinyint(4) NOT NULL,
  `vitality` int(11) NOT NULL,
  `strength` tinyint(4) NOT NULL,
  `intelligence` tinyint(4) NOT NULL,
  `agility` tinyint(4) NOT NULL,
  `luck` tinyint(4) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `cost` mediumint(9) NOT NULL DEFAULT '1',
  `img` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `eq`
--

INSERT INTO `eq` (`ID`, `name`, `hero_id`, `type`, `attack_min`, `attack_max`, `defense`, `vitality`, `strength`, `intelligence`, `agility`, `luck`, `state`, `cost`, `img`) VALUES
(1, 'Stalowy Hełm', 1, 'helmet', 0, 0, 1, 0, 0, 0, 0, 0, 2, 2, 'helmet_steel'),
(2, 'Stalowy Miecz', 1, 'sword', 3, 4, 0, 0, 0, 0, 0, 0, 2, 2, 'sword_steel'),
(3, 'Złoty Pierścień', 1, 'ring', 0, 0, 0, 2, 0, 0, 0, 0, 2, 4, 'ring_gold'),
(4, 'Srebrny Pierścień', 1, 'ring', 0, 0, 0, 0, 1, 3, 0, 2, 2, 9, 'ring_silver'),
(5, 'Stalowe Naramienniki', 1, 'arm', 0, 0, 1, 0, 0, 0, 0, 0, 2, 2, 'arm_steel'),
(6, 'Buty z Brązu', 1, 'feet', 0, 0, 1, 0, 0, 0, 0, 0, 2, 2, 'boots_bronze'),
(7, 'Stalowy Hełm', 1, 'helmet', 0, 0, 1, 0, 0, 0, 0, 0, 0, 2, 'helmet_steel'),
(8, 'Pas Giganta', 1, 'belt', 0, 0, 1, 0, 0, 0, 0, 2, 0, 4, 'belt_ruby'),
(9, 'Buty z Brązu', 1, 'feet', 0, 0, 1, 0, 0, 0, 0, 0, 0, 2, 'boots_bronze'),
(10, 'Stalowy Hełm', 1, 'helmet', 0, 0, 1, 0, 0, 0, 0, 0, 0, 2, 'helmet_steel');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `experience`
--

CREATE TABLE `experience` (
  `level` tinyint(4) NOT NULL DEFAULT '1',
  `experience` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `experience`
--

INSERT INTO `experience` (`level`, `experience`) VALUES
(1, 0),
(2, 30),
(3, 60),
(4, 75),
(5, 87),
(6, 98),
(7, 138),
(8, 184),
(9, 237),
(10, 296),
(11, 362),
(12, 435),
(13, 515),
(14, 601),
(15, 694),
(16, 793),
(17, 899),
(18, 1012),
(19, 1132),
(20, 1258),
(21, 1391),
(22, 1530),
(23, 1676),
(24, 1829),
(25, 1989),
(26, 2155),
(27, 2328),
(28, 2507),
(29, 2693),
(30, 2886),
(31, 3086),
(32, 3292),
(33, 3505),
(34, 3724),
(35, 3950),
(36, 4183),
(37, 4423),
(38, 4669),
(39, 4922),
(40, 5181),
(41, 5447),
(42, 5720),
(43, 6000),
(44, 6286),
(45, 6579),
(46, 6878),
(47, 7184),
(48, 7497),
(49, 7817),
(50, 8143),
(51, 8476),
(52, 8815),
(53, 9161),
(54, 9514),
(55, 9874),
(56, 10240),
(57, 10613),
(58, 10992),
(59, 11378),
(60, 11771),
(61, 12171),
(62, 12577),
(63, 12990),
(64, 13409),
(65, 13835),
(66, 14268),
(67, 14708),
(68, 15154),
(69, 15607),
(70, 16066),
(71, 16532),
(72, 17005),
(73, 17485),
(74, 17971),
(75, 18464),
(76, 18963),
(77, 19469),
(78, 19982),
(79, 20502),
(80, 21028),
(81, 21561),
(82, 22100),
(83, 22646),
(84, 23199),
(85, 23759),
(86, 24325),
(87, 24898),
(88, 25477),
(89, 26063),
(90, 26656),
(91, 27256),
(92, 27862),
(93, 28475),
(94, 29094),
(95, 29720),
(96, 30353),
(97, 30993),
(98, 31639),
(99, 32292),
(100, 32951);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `heroes`
--

CREATE TABLE `heroes` (
  `ID` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `race` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `class` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `strength` smallint(6) NOT NULL,
  `intelligence` smallint(6) NOT NULL,
  `agility` smallint(6) NOT NULL,
  `vitality` smallint(6) NOT NULL,
  `luck` smallint(4) NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '1',
  `experience` int(11) NOT NULL DEFAULT '0',
  `killed_monsters` smallint(6) NOT NULL DEFAULT '0',
  `beated_players` smallint(6) NOT NULL DEFAULT '0',
  `adventures_traveled` smallint(6) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  `dungeon` tinyint(4) NOT NULL DEFAULT '1',
  `dungeon_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adventures` tinytext COLLATE utf8_polish_ci,
  `adventure_duration` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `adventure_reward` varchar(36) COLLATE utf8_polish_ci NOT NULL,
  `adventure_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `heroes`
--

INSERT INTO `heroes` (`ID`, `name`, `race`, `class`, `strength`, `intelligence`, `agility`, `vitality`, `luck`, `level`, `experience`, `killed_monsters`, `beated_players`, `adventures_traveled`, `gold`, `dungeon`, `dungeon_time`, `adventures`, `adventure_duration`, `adventure_reward`, `adventure_time`) VALUES
(1, 'admin', 'demon', 'warrior', 3, 1, 1, 1, 1, 1, 13, 58, 0, 13, 100, 2, '2018-11-25 21:17:39', '4/3/1', '4/9/2', '40/30/10', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `items`
--

CREATE TABLE `items` (
  `ID` int(11) NOT NULL,
  `name` varchar(24) COLLATE utf8_polish_ci DEFAULT NULL,
  `type` varchar(10) COLLATE utf8_polish_ci DEFAULT NULL,
  `class` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `attack_min` smallint(6) DEFAULT NULL,
  `attack_max` varchar(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `defense` varchar(6) COLLATE utf8_polish_ci DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `img` varchar(24) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `items`
--

INSERT INTO `items` (`ID`, `name`, `type`, `class`, `attack_min`, `attack_max`, `defense`, `level`, `img`) VALUES
(1, 'Stalowy Miecz', 'sword', '0', 3, '4', '0', 1, 'sword_steel'),
(2, 'Smocza Zbroja', 'chestplate', 'warrior', 0, '0', '47', 42, 'chestplate_drahon'),
(3, 'Złoty Pierścień', 'ring', '0', 0, '0', '0', 1, 'ring_gold'),
(4, 'Srebrny Pierścień', 'ring', '0', 0, '0', '0', 1, 'ring_silver'),
(5, 'Smoczy Miecz', 'sword', 'warrior', 34, '42', '0', 42, 'sword_dragon'),
(6, 'Naszyjnik z rubinem', 'necklace', '0', 0, '0', '0', 1, 'necklace_ruby'),
(7, 'Stalowe Naramienniki', 'arm', '0', 0, '0', '1', 1, 'arm_steel'),
(8, 'Pas Giganta', 'belt', '0', 0, '0', '1', 1, 'belt_ruby'),
(9, 'Kościane Buty', 'feet', 'warrior', 0, '0', '5', 34, 'boots_skull'),
(10, 'Kościany Hełm', 'helmet', '0', 0, '0', '9', 34, 'helmet_skull'),
(11, 'Stalowy Hełm', 'helmet', '0', 0, '0', '1', 1, 'helmet_steel'),
(12, 'Krasnoludzka Zbroja', 'chestplate', 'warrior', 0, '0', '12', 9, 'chestplate_dwaf'),
(13, 'Krasnoludzkie Buty', 'feet', 'warrior', 0, '0', '6', 9, 'boots_dwarf'),
(14, 'Krasnoludzki Hełm', 'helmet', '0', 0, '0', '6', 9, 'helmet_dwarf'),
(15, 'Pancerz Rzezimieszka', 'chestplate', 'ranger', 0, '0', '6', 9, 'chestplate_rouge'),
(16, 'Elfia Szata', 'chestplate', 'mage', 0, '0', '6', 9, 'chestplate_elfin'),
(17, 'Pancerz Słowika', 'chestplate', 'ranger', 0, '0', '47', 34, 'chestplate_nightgale'),
(18, 'Pancerz Najemnika', 'chestplate', 'ranger', 0, '0', '29', 18, 'chestplate_mercenary'),
(19, 'Pancerz Złodzieja', 'chestplate', 'ranger', 0, '0', '38', 26, 'chestplate_thief'),
(20, 'Srebrna Zbroja', 'chestplate', 'warrior', 0, '0', '29', 18, 'chestplate_silver'),
(21, 'Zbroja Paladyna', 'chestplate', 'warrior', 0, '0', '38', 26, 'chestplate_paladin'),
(22, 'Kościana Zbroja', 'chestplate', 'warrior', 0, '0', '55', 34, 'chestplate_bone'),
(23, 'Szata Kapłana', 'chestplate', 'mage', 0, '0', '29', 18, 'chestplate_priest'),
(24, 'Buty z Brązu', 'feet', '0', 0, '0', '1', 1, 'boots_bronze'),
(25, 'Buty Paladyna', 'feet', 'warrior', 0, '0', '16', 26, 'boots_paladin'),
(26, 'Lwie Buty', 'feet', 'mage', 0, '0', '6', 9, 'boots_lion'),
(27, 'Buty Rzezimieszka', 'feet', 'ranger', 0, '0', '6', 9, 'boots_rouge'),
(28, 'Srebrny Hełm', 'helmet', '0', 0, '0', '14', 18, 'helmet_silver');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `monsters`
--

CREATE TABLE `monsters` (
  `ID` int(11) NOT NULL,
  `name` varchar(24) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL,
  `health` smallint(6) NOT NULL,
  `attack_min` smallint(6) NOT NULL,
  `attack_max` smallint(6) NOT NULL,
  `defense` smallint(6) NOT NULL,
  `critical` tinyint(4) NOT NULL,
  `miss` tinyint(4) NOT NULL,
  `img` varchar(64) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `monsters`
--

INSERT INTO `monsters` (`ID`, `name`, `health`, `attack_min`, `attack_max`, `defense`, `critical`, `miss`, `img`) VALUES
(1, 'Szczur', 6, 4, 5, 6, 30, 9, 'haunting'),
(2, 'Szlam', 14, 6, 9, 8, 20, 6, 'slime'),
(3, 'Larwa', 24, 11, 11, 10, 24, 15, 'werewolf'),
(4, 'Kupa kłopotów', 36, 12, 14, 12, 27, 18, 'trex'),
(5, 'Zmutowany tygrys', 50, 20, 22, 14, 18, 5, 'slime'),
(6, 'Świnia wampir', 66, 25, 33, 16, 5, 11, 'centaur'),
(7, 'Głodny Wilk', 84, 26, 33, 18, 23, 3, 'reaper'),
(8, 'Głodny nietoperz', 104, 31, 37, 20, 26, 1, 'bat'),
(9, 'Zmutowany tygrys', 126, 47, 45, 22, 19, 5, 'hydra'),
(10, 'Ogr', 150, 47, 56, 24, 14, 4, 'ogre'),
(11, 'Grabarz', 204, 65, 70, 28, 20, 11, 'gravedigger'),
(12, 'Szkielet', 234, 83, 94, 30, 21, 1, 'skeleton'),
(13, 'Ghoul', 266, 89, 106, 32, 5, 8, 'haunting'),
(14, 'Dusza Umęczonego', 300, 99, 103, 34, 26, 2, 'ghost'),
(15, 'Mumia', 336, 105, 124, 36, 12, 18, 'mummy'),
(16, 'Strażnik Krypty', 374, 126, 144, 38, 20, 9, 'guardian'),
(17, 'Banshee', 414, 139, 140, 40, 11, 14, 'haunting'),
(18, 'Cień', 456, 155, 173, 42, 14, 14, 'haunting'),
(19, 'Wampir', 500, 164, 173, 44, 28, 15, 'haunting'),
(20, 'Śmierć', 546, 177, 186, 46, 5, 2, 'reaper'),
(21, 'Wąż', 594, 206, 223, 48, 23, 3, 'haunting'),
(22, 'Kobra', 644, 204, 254, 50, 28, 16, 'haunting'),
(23, 'Krokodyl', 696, 218, 241, 52, 16, 11, 'haunting'),
(24, 'Jaszczur', 750, 248, 266, 54, 25, 3, 'salamander'),
(25, 'Bagiennik', 806, 278, 292, 56, 11, 8, 'buggy'),
(26, 'Wielka ważka', 864, 273, 310, 58, 29, 7, 'haunting'),
(27, 'Nimfa', 924, 307, 351, 60, 6, 1, 'haunting'),
(28, 'Troll', 986, 325, 363, 62, 19, 6, 'troll'),
(29, 'Raptor', 1050, 332, 372, 64, 21, 6, 'raptor'),
(30, 'Wywerna', 1116, 365, 430, 66, 21, 17, 'wyvern'),
(31, 'Pajęczak', 1974, 634, 699, 88, 25, 5, 'haunting'),
(32, 'Zatruty pająk', 2064, 693, 687, 90, 26, 16, 'haunting'),
(33, 'Rój', 2156, 704, 788, 92, 26, 18, 'haunting'),
(34, 'Tarantula', 2250, 763, 795, 94, 7, 4, 'haunting'),
(35, 'Kolos', 2346, 799, 777, 96, 20, 1, 'haunting'),
(36, 'Wielki pająk', 2444, 829, 812, 98, 22, 5, 'haunting'),
(37, 'Święty pająk', 2544, 864, 917, 100, 30, 12, 'haunting'),
(38, 'Akromantula', 2646, 877, 889, 102, 6, 5, 'haunting'),
(39, 'Czarna wdowa', 2750, 904, 920, 104, 13, 9, 'haunting'),
(40, 'Aragog', 2856, 961, 957, 106, 27, 18, 'haunting'),
(41, 'Dusza', 2964, 978, 1004, 108, 8, 8, 'spirit'),
(42, 'Strażnik', 3074, 1017, 1053, 110, 10, 1, 'haunting'),
(43, 'Niebianin', 3186, 1069, 1058, 112, 22, 16, 'haunting'),
(44, 'Stróż', 3300, 1098, 1119, 114, 27, 19, 'haunting'),
(45, 'Walkiria', 3416, 1141, 1226, 116, 25, 15, 'haunting'),
(46, 'Anioł', 3534, 1173, 1256, 118, 17, 4, 'haunting'),
(47, 'Archanioł', 3654, 1233, 1227, 120, 16, 8, 'haunting'),
(48, 'Cherubin', 3776, 1251, 1361, 122, 12, 3, 'haunting'),
(49, 'Tron', 3900, 1316, 1293, 124, 16, 9, 'haunting'),
(50, 'Serfain', 4026, 1332, 1335, 126, 30, 13, 'haunting'),
(51, 'Płomień', 4154, 1377, 1492, 128, 9, 12, 'ifrit'),
(52, 'Demon', 4284, 1428, 1428, 130, 20, 12, 'demon'),
(53, 'Ognisty tygrys', 4416, 1463, 1552, 132, 15, 17, 'haunting'),
(54, 'Mutant', 4550, 1512, 1601, 134, 22, 5, 'haunting'),
(55, 'Wojownik Chaosu', 4686, 1579, 1647, 136, 9, 11, 'haunting'),
(56, 'Piekielny pomiot', 4824, 1603, 1643, 138, 20, 11, 'haunting'),
(57, 'Diabeł', 4964, 1634, 1723, 140, 18, 20, 'devil'),
(58, 'Szatan', 5106, 1679, 1802, 142, 8, 4, 'satan'),
(59, 'Czart', 5250, 1719, 1768, 144, 28, 19, 'haunting'),
(60, 'Lewiatan', 5396, 1797, 1871, 146, 14, 8, 'haunting');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `races`
--

CREATE TABLE `races` (
  `ID` int(11) NOT NULL,
  `name` varchar(16) COLLATE utf8_polish_ci DEFAULT NULL,
  `vitality` float NOT NULL,
  `strength` float NOT NULL,
  `intelligence` float NOT NULL,
  `agility` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `races`
--

INSERT INTO `races` (`ID`, `name`, `vitality`, `strength`, `intelligence`, `agility`) VALUES
(1, 'human', 1.01, 1.01, 1.01, 1.01),
(2, 'elf', 1, 1.02, 1.02, 1),
(3, 'dwarf', 1.02, 0.99, 1.03, 1),
(4, 'demon', 1.04, 1, 0.99, 1.01),
(5, 'orc', 1.02, 0.99, 0.99, 1.04);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `verified` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `resettable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `roles_mask` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `registered` int(10) UNSIGNED NOT NULL,
  `last_login` int(10) UNSIGNED DEFAULT NULL,
  `force_logout` mediumint(7) UNSIGNED NOT NULL DEFAULT '0',
  `hero_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `status`, `verified`, `resettable`, `roles_mask`, `registered`, `last_login`, `force_logout`, `hero_id`) VALUES
(1, 'damianchojnacki@op.pl', '$2y$10$MnpB.7x6Y1vY4LseyBsWmudOOMX.j1Oay/.YPa2joOWacKabDCn.m', 'admin', 0, 1, 1, 0, 1541451560, 1543179241, 0, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_confirmations`
--

CREATE TABLE `users_confirmations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(249) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selector` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_remembered`
--

CREATE TABLE `users_remembered` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `selector` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_resets`
--

CREATE TABLE `users_resets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `selector` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_throttling`
--

CREATE TABLE `users_throttling` (
  `bucket` varchar(44) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `tokens` float UNSIGNED NOT NULL,
  `replenished_at` int(10) UNSIGNED NOT NULL,
  `expires_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `users_throttling`
--

INSERT INTO `users_throttling` (`bucket`, `tokens`, `replenished_at`, `expires_at`) VALUES
('ejWtPDKvxt-q7LZ3mFjzUoIWKJYzu47igC8Jd9mffFk', 74, 1543179241, 1543719241),
('CUeQSH1MUnRpuE3Wqv_fI3nADvMpK_cg6VpYK37vgIw', 4, 1541451563, 1541883563),
('sZYXdcyzJCIQjhLWDhxqtQgKYyGMgsFMjHNxwbpWOAE', 49, 1541451588, 1541523588),
('ICQq3JXH5FfwRUndNTyPR-try3wcm3XIAbSBuBvwvgA', 29, 1541451588, 1541523588),
('6E4BNDLk8UYLfRYWH8rAJhFc97wM6zJiSUORyRNJwqI', 29, 1541451588, 1541523588);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `adventures`
--
ALTER TABLE `adventures`
  ADD KEY `ID` (`ID`);

--
-- Indeksy dla tabeli `classes`
--
ALTER TABLE `classes`
  ADD UNIQUE KEY `rasa` (`name`),
  ADD KEY `ID` (`ID`);

--
-- Indeksy dla tabeli `dungeons`
--
ALTER TABLE `dungeons`
  ADD PRIMARY KEY (`ID`) USING BTREE,
  ADD KEY `stage_1` (`stage_1`),
  ADD KEY `stage_2` (`stage_2`),
  ADD KEY `stage_3` (`stage_3`),
  ADD KEY `stage_4` (`stage_4`),
  ADD KEY `stage_5` (`stage_5`),
  ADD KEY `stage_6` (`stage_6`),
  ADD KEY `stage_7` (`stage_7`),
  ADD KEY `stage_8` (`stage_8`),
  ADD KEY `stage_9` (`stage_9`),
  ADD KEY `stage_10` (`stage_10`);

--
-- Indeksy dla tabeli `eq`
--
ALTER TABLE `eq`
  ADD KEY `ID` (`ID`),
  ADD KEY `eq_ibfk_2` (`name`);

--
-- Indeksy dla tabeli `heroes`
--
ALTER TABLE `heroes`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- Indeksy dla tabeli `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `monsters`
--
ALTER TABLE `monsters`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- Indeksy dla tabeli `races`
--
ALTER TABLE `races`
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `ID` (`ID`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `users_confirmations`
--
ALTER TABLE `users_confirmations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `email_expires` (`email`,`expires`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `users_remembered`
--
ALTER TABLE `users_remembered`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user` (`user`);

--
-- Indeksy dla tabeli `users_resets`
--
ALTER TABLE `users_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user_expires` (`user`,`expires`);

--
-- Indeksy dla tabeli `users_throttling`
--
ALTER TABLE `users_throttling`
  ADD PRIMARY KEY (`bucket`),
  ADD KEY `expires_at` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `adventures`
--
ALTER TABLE `adventures`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `classes`
--
ALTER TABLE `classes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `dungeons`
--
ALTER TABLE `dungeons`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `eq`
--
ALTER TABLE `eq`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `heroes`
--
ALTER TABLE `heroes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `items`
--
ALTER TABLE `items`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT dla tabeli `monsters`
--
ALTER TABLE `monsters`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT dla tabeli `races`
--
ALTER TABLE `races`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `users_confirmations`
--
ALTER TABLE `users_confirmations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `users_remembered`
--
ALTER TABLE `users_remembered`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users_resets`
--
ALTER TABLE `users_resets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dungeons`
--
ALTER TABLE `dungeons`
  ADD CONSTRAINT `dungeons_ibfk_1` FOREIGN KEY (`stage_1`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_10` FOREIGN KEY (`stage_10`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_2` FOREIGN KEY (`stage_2`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_3` FOREIGN KEY (`stage_3`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_4` FOREIGN KEY (`stage_4`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_5` FOREIGN KEY (`stage_5`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_6` FOREIGN KEY (`stage_6`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_7` FOREIGN KEY (`stage_7`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_8` FOREIGN KEY (`stage_8`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `dungeons_ibfk_9` FOREIGN KEY (`stage_9`) REFERENCES `monsters` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ograniczenia dla tabeli `eq`
--
ALTER TABLE `eq`
  ADD CONSTRAINT `eq_ibfk_2` FOREIGN KEY (`name`) REFERENCES `items` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
