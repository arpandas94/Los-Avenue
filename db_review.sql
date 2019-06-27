-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2017 at 11:47 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_review`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(100) NOT NULL,
  `comment_sender` int(100) NOT NULL,
  `comment_entry` int(100) NOT NULL,
  `comment_time` datetime NOT NULL,
  `comment_content` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_sender`, `comment_entry`, `comment_time`, `comment_content`) VALUES
(1, 1, 3, '2017-03-18 16:25:25', 'hu'),
(2, 1, 3, '2017-03-18 16:24:44', 'yo'),
(7, 2, 1, '2017-03-19 13:19:38', 'yo'),
(8, 3, 3, '2017-03-20 12:43:38', 'hey guys'),
(9, 2, 4, '2017-03-21 10:49:54', 'hey whassup?'),
(19, 2, 7, '2017-06-13 12:02:44', '*BSNL customer care* Lady : hello, 3 din se internet bandh hai, aap hi bataiye main kya karu ? BSNL customer care : Mam ghar ka kuch kaam kar lijiyeðŸ™„ðŸ˜‚ *speechless*'),
(20, 2, 2, '2017-06-14 19:26:16', 'yo'),
(21, 3, 7, '2017-06-14 19:26:43', 'uploaderId'),
(22, 1, 7, '2017-06-15 12:09:16', 'Focus on the following topics to get a good score, I must say a very good score (above 90 percentile).  â€“ Number Systems  â€“ Geometry  â€“ Probability  â€“ Permutation and Combination'),
(23, 1, 1, '2017-06-27 18:43:09', 'd'),
(24, 1, 1, '2017-06-27 18:43:13', 'd'),
(25, 1, 1, '2017-06-27 18:43:17', '5'),
(26, 1, 1, '2017-06-27 18:43:32', 'g'),
(27, 1, 2, '2017-06-28 20:33:15', 'sil vous plait'),
(28, 1, 2, '2017-06-28 20:33:33', 'parley fracais?'),
(29, 1, 2, '2017-06-28 20:33:40', 'cambien?'),
(31, 1, 2, '2017-06-28 20:33:52', 'yo'),
(32, 2, 5, '2017-06-30 11:40:40', 'imgpic-uploads3.jpg'),
(33, 2, 5, '2017-06-30 11:40:45', 'imgpic-uploads3.jpg'),
(34, 2, 8, '2017-07-02 12:37:22', 'cool'),
(35, 2, 5, '2017-07-04 14:59:05', 'who am i?'),
(36, 2, 5, '2017-07-04 15:00:02', 'B\'day \"w\" '),
(37, 2, 9, '2017-07-05 13:23:36', 'df'),
(38, 2, 9, '2017-07-05 13:24:35', 'sd'),
(39, 2, 10, '2017-07-22 11:49:53', 'Camera\'s bigger than your face...LOL'),
(40, 2, 11, '2017-07-23 13:18:12', 'Is it even real? #fakeusers'),
(41, 1, 11, '2017-07-23 13:33:16', 'Yes u jerk!'),
(42, 2, 11, '2017-07-23 13:36:37', 'Relax man, just kidding! '),
(43, 3, 11, '2017-07-23 21:57:29', 'yo nice lambo bro!');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(100) NOT NULL,
  `country_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`) VALUES
(1, 'Andorra'),
(2, 'United Arab Emirates'),
(3, 'Afghanistan'),
(4, 'Antigua and Barbuda'),
(5, 'Anguilla'),
(6, 'Albania'),
(7, 'Armenia'),
(8, 'Angola'),
(9, 'Antarctica'),
(10, 'Argentina'),
(11, 'American Samoa'),
(12, 'Austria'),
(13, 'Australia'),
(14, 'Aruba'),
(15, '&Aring;land Islands'),
(16, 'Azerbaijan'),
(17, 'Bosnia and Herzegovina'),
(18, 'Barbados'),
(19, 'Bangladesh'),
(20, 'Belgium'),
(21, 'Burkina Faso'),
(22, 'Bulgaria'),
(23, 'Bahrain'),
(24, 'Burundi'),
(25, 'Benin'),
(26, 'Saint Barthélemy'),
(27, 'Bermuda'),
(28, 'Brunei'),
(29, 'Bolivia'),
(30, 'Bonaire'),
(31, 'Brazil'),
(32, 'Bahamas'),
(33, 'Bhutan'),
(34, 'Bouvet Island'),
(35, 'Botswana'),
(36, 'Belarus'),
(37, 'Belize'),
(38, 'Canada'),
(39, 'Cocos [Keeling] Islands'),
(40, 'Democratic Republic of the Congo'),
(41, 'Central African Republic'),
(42, 'Republic of the Congo'),
(43, 'Switzerland'),
(44, 'Ivory Coast'),
(45, 'Cook Islands'),
(46, 'Chile'),
(47, 'Cameroon'),
(48, 'China'),
(49, 'Colombia'),
(50, 'Costa Rica'),
(51, 'Cuba'),
(52, 'Cape Verde'),
(53, 'Curacao'),
(54, 'Christmas Island'),
(55, 'Cyprus'),
(56, 'Czechia'),
(57, 'Germany'),
(58, 'Djibouti'),
(59, 'Denmark'),
(60, 'Dominica'),
(61, 'Dominican Republic'),
(62, 'Algeria'),
(63, 'Ecuador'),
(64, 'Estonia'),
(65, 'Egypt'),
(66, 'Western Sahara'),
(67, 'Eritrea'),
(68, 'Spain'),
(69, 'Ethiopia'),
(70, 'Finland'),
(71, 'Fiji'),
(72, 'Falkland Islands'),
(73, 'Micronesia'),
(74, 'Faroe Islands'),
(75, 'France'),
(76, 'Gabon'),
(77, 'United Kingdom'),
(78, 'Grenada'),
(79, 'Georgia'),
(80, 'French Guiana'),
(81, 'Guernsey'),
(82, 'Ghana'),
(83, 'Gibraltar'),
(84, 'Greenland'),
(85, 'Gambia'),
(86, 'Guinea'),
(87, 'Guadeloupe'),
(88, 'Equatorial Guinea'),
(89, 'Greece'),
(90, 'South Georgia and the South Sandwich Islands'),
(91, 'Guatemala'),
(92, 'Guam'),
(93, 'Guinea-Bissau'),
(94, 'Guyana'),
(95, 'Hong Kong'),
(96, 'Heard Island and McDonald Islands'),
(97, 'Honduras'),
(98, 'Croatia'),
(99, 'Haiti'),
(100, 'Hungary'),
(101, 'Indonesia'),
(102, 'Ireland'),
(103, 'Israel'),
(104, 'Isle of Man'),
(105, 'India'),
(106, 'British Indian Ocean Territory'),
(107, 'Iraq'),
(108, 'Iran'),
(109, 'Iceland'),
(110, 'Italy'),
(111, 'Jersey'),
(112, 'Jamaica'),
(113, 'Jordan'),
(114, 'Japan'),
(115, 'Kenya'),
(116, 'Kyrgyzstan'),
(117, 'Cambodia'),
(118, 'Kiribati'),
(119, 'Comoros'),
(120, 'Saint Kitts and Nevis'),
(121, 'North Korea'),
(122, 'South Korea'),
(123, 'Kuwait'),
(124, 'Cayman Islands'),
(125, 'Kazakhstan'),
(126, 'Laos'),
(127, 'Lebanon'),
(128, 'Saint Lucia'),
(129, 'Liechtenstein'),
(130, 'Sri Lanka'),
(131, 'Liberia'),
(132, 'Lesotho'),
(133, 'Lithuania'),
(134, 'Luxembourg'),
(135, 'Latvia'),
(136, 'Libya'),
(137, 'Morocco'),
(138, 'Monaco'),
(139, 'Moldova'),
(140, 'Montenegro'),
(141, 'Saint Martin'),
(142, 'Madagascar'),
(143, 'Marshall Islands'),
(144, 'Macedonia'),
(145, 'Mali'),
(146, 'Myanmar [Burma]'),
(147, 'Mongolia'),
(148, 'Macao'),
(149, 'Northern Mariana Islands'),
(150, 'Martinique'),
(151, 'Mauritania'),
(152, 'Montserrat'),
(153, 'Malta'),
(154, 'Mauritius'),
(155, 'Maldives'),
(156, 'Malawi'),
(157, 'Mexico'),
(158, 'Malaysia'),
(159, 'Mozambique'),
(160, 'Namibia'),
(161, 'New Caledonia'),
(162, 'Niger'),
(163, 'Norfolk Island'),
(164, 'Nigeria'),
(165, 'Nicaragua'),
(166, 'Netherlands'),
(167, 'Norway'),
(168, 'Nepal'),
(169, 'Nauru'),
(170, 'Niue'),
(171, 'New Zealand'),
(172, 'Oman'),
(173, 'Panama'),
(174, 'Peru'),
(175, 'French Polynesia'),
(176, 'Papua New Guinea'),
(177, 'Philippines'),
(178, 'Pakistan'),
(179, 'Poland'),
(180, 'Saint Pierre and Miquelon'),
(181, 'Pitcairn Islands'),
(182, 'Puerto Rico'),
(183, 'Palestine'),
(184, 'Portugal'),
(185, 'Palau'),
(186, 'Paraguay'),
(187, 'Qatar'),
(188, 'Réunion'),
(189, 'Romania'),
(190, 'Serbia'),
(191, 'Russia'),
(192, 'Rwanda'),
(193, 'Saudi Arabia'),
(194, 'Solomon Islands'),
(195, 'Seychelles'),
(196, 'Sudan'),
(197, 'Sweden'),
(198, 'Singapore'),
(199, 'Saint Helena'),
(200, 'Slovenia'),
(201, 'Svalbard and Jan Mayen'),
(202, 'Slovakia'),
(203, 'Sierra Leone'),
(204, 'San Marino'),
(205, 'Senegal'),
(206, 'Somalia'),
(207, 'Suriname'),
(208, 'South Sudan'),
(209, 'São Tomé and Príncipe'),
(210, 'El Salvador'),
(211, 'Sint Maarten'),
(212, 'Syria'),
(213, 'Swaziland'),
(214, 'Turks and Caicos Islands'),
(215, 'Chad'),
(216, 'French Southern Territories'),
(217, 'Togo'),
(218, 'Thailand'),
(219, 'Tajikistan'),
(220, 'Tokelau'),
(221, 'East Timor'),
(222, 'Turkmenistan'),
(223, 'Tunisia'),
(224, 'Tonga'),
(225, 'Turkey'),
(226, 'Trinidad and Tobago'),
(227, 'Tuvalu'),
(228, 'Taiwan'),
(229, 'Tanzania'),
(230, 'Ukraine'),
(231, 'Uganda'),
(232, 'U.S. Minor Outlying Islands'),
(233, 'United States'),
(234, 'Uruguay'),
(235, 'Uzbekistan'),
(236, 'Vatican City'),
(237, 'Saint Vincent and the Grenadines'),
(238, 'Venezuela'),
(239, 'British Virgin Islands'),
(240, 'U.S. Virgin Islands'),
(241, 'Vietnam'),
(242, 'Vanuatu'),
(243, 'Wallis and Futuna'),
(244, 'Samoa'),
(245, 'Kosovo'),
(246, 'Yemen'),
(247, 'Mayotte'),
(248, 'South Africa'),
(249, 'Zambia'),
(250, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `currency_id` int(100) NOT NULL,
  `currency_name` varchar(255) NOT NULL,
  `currency_symbol` varchar(10) CHARACTER SET utf8 NOT NULL,
  `currency_short_form` varchar(5) NOT NULL,
  `currency_exchange_rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `currency_name`, `currency_symbol`, `currency_short_form`, `currency_exchange_rate`) VALUES
(1, 'United States Dollar', '$', 'USD', 1),
(2, 'Indian Rupee', 'Rs', 'INR', 64.32),
(3, 'Argentine Peso', '$', 'ARS', 17.399),
(4, 'Australian Dollar', '$', 'AUD', 1.2618),
(5, 'Azerbaijani Manat', 'Ð¼Ð°Ð½', 'AZN', 1.7007),
(6, 'Bulgarian Lev', 'Ð»Ð²', 'BGN', 1.68),
(7, 'Bahraini Dinar', '.Ø¯.Ø¨', 'BHD', 0.3771),
(8, 'Brunei Dollar', '$', 'BND', 1.3615),
(9, 'Brazilian Real', 'R$', 'BRL', 3.1414),
(10, 'Canadian Dollar', 'C$', 'CAD', 1.2537),
(11, 'Swiss Franc', 'SFr', 'CHF', 0.9451),
(12, 'Chilean Peso', '$', 'CLP', 651.12),
(13, 'Chinese Yuan', 'Â¥', 'CNY', 6.7662),
(14, 'Czech Koruna', 'KÄ', 'CZK', 22.318),
(15, 'Danish Krone', 'kr', 'DKK', 6.3741),
(16, 'Egyptian Pound', 'EÂ£', 'EGP', 17.81),
(17, 'Euro', 'â‚¬', 'EUR', 0.8572),
(18, 'Fiji Dollar', 'FJ$', 'FJD', 2.0175),
(19, 'Pound Sterling', ' Â£', 'GBP', 0.7695),
(20, 'Hong Kong Dollar', 'HK$', 'HKD', 7.8084),
(21, 'Hungarian Forint', 'Ft', 'HUF', 261.52),
(22, 'Indonesian rupiah', 'Rp', 'IDR', 13310),
(23, 'Israeli Shekel', 'â‚ª', 'ILS', 3.5655),
(24, 'Japanese Yen', 'Â¥', 'JPY', 111.153),
(25, 'South Korean Won', 'â‚©', 'KRW', 1117.33),
(26, 'Kuwaiti Dinar', 'Ø¯.Ùƒ', 'KWD', 0.3021),
(27, 'Sri Lankan Ruppee', 'Rp', 'LKR', 153.65),
(28, 'Moroccan Dirham', 'Ø¯.Ù…', 'MAD', 9.5128),
(29, 'Malagasy Ariary', 'Ar', 'MGA', 3185),
(30, 'Mexican Peso', 'Mex$', 'MXN', 17.653),
(31, 'Malaysian Ringgit', 'RM', 'MYR', 4.282),
(32, 'Norwegian Krone', 'kr', 'NOK', 8.0329),
(33, 'New Zealand Dollar', '$', 'NZD', 1.3407),
(34, 'Omani Rial', 'Ø±.Ø¹', 'OMR', 0.3847),
(35, 'Peruvian Sol', 'S/', 'PEN', 3.2485),
(36, 'Papua New Guinea Kina', 'K', 'PGK', 3.28),
(37, 'Philippines Peso', 'â‚±', 'PHP', 50.7),
(38, 'Pakistani Rupee', 'Rs', 'PKR', 105.32),
(39, 'Polish Zloty', 'zÅ‚', 'PLN', 3.6552),
(40, 'Russian Ruble', '&#8381;', 'RUB', 59.309),
(41, 'Saudi Riyal', 'ï·¼', 'SAR', 3.7498),
(42, 'Solomon Islands Dollar', 'SI$', 'SBD', 7.7735),
(43, 'Seychellois Rupee', 'SRe', 'SCR', 13.403),
(44, 'Swedish Krona', 'â€Žkr', 'SEK', 8.2384),
(45, 'Singapore Dollar', 'S$', 'SGD', 1.3613),
(46, 'Thai Baht', 'à¸¿', 'THB', 33.44),
(47, 'Tongan PaÊ»anga', 'T$', 'TOP', 2.2173),
(48, 'Turkish Lira', 'â‚º', 'TRY', 3.5353),
(49, 'New Taiwan Dollar', 'â€ŽNT$', 'TWD', 30.43),
(50, 'Tanzanian Shilling', 'TSh', 'TZS', 2233),
(51, 'Venezuelan BolÃ­var', 'Bs', 'VEF', 9.975),
(52, 'Vietnamese Dong', 'â‚«', 'VND', 22699),
(53, 'Vanuatu Vatu', 'â€ŽVT', 'VUV', 103.18),
(54, 'Samoan Tala', 'WS$', 'WST', 2.5025),
(55, 'West African CFA franc', 'CFA', 'XOF', 569),
(56, 'South African Rand', 'R', 'ZAR', 12.9006),
(57, 'Bitcoin', 'Éƒ', 'BTC', 0.0004);

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE `entries` (
  `entry_id` int(100) NOT NULL,
  `entry_uploader` int(100) NOT NULL,
  `entry_brand_name` varchar(255) NOT NULL,
  `entry_name` varchar(255) NOT NULL,
  `entry_currency` int(100) NOT NULL,
  `entry_time` datetime NOT NULL,
  `entry_tags` varchar(255) NOT NULL,
  `entry_price_in_usd` float NOT NULL,
  `entry_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `entries`
--

INSERT INTO `entries` (`entry_id`, `entry_uploader`, `entry_brand_name`, `entry_name`, `entry_currency`, `entry_time`, `entry_tags`, `entry_price_in_usd`, `entry_status`) VALUES
(1, 1, 'TAGHeuer', 'Heuer Carera 01', 1, '2017-05-16 08:12:21', 'tag, heuer, carera, expensive, lol', 85, 'dhw'),
(2, 2, 'Apple', 'I-Phone 7', 2, '2017-05-23 07:22:15', 'you cant see me', 649, 'sdsdsf'),
(3, 1, 'Rayban', 'Wayfarers', 1, '2017-07-01 13:00:00', 'tags', 200, 'Cool shades!'),
(5, 2, 'Samsung', '32\" HD Series 4003', 3, '2017-07-01 11:37:16', 'tv, samsung, led', 537.634, 'Bought this on my b\'day!!'),
(6, 3, 'Hitachi', 'Fridge', 5, '2017-03-26 18:13:57', 'hitachi, fridge, japanese', 134.989, 'Went to Japan, brought a fridge! Bite me!!'),
(7, 1, 'Nokia', 'Lumia 620', 2, '2017-06-12 02:12:17', 'nokia,lumia,620,windows', 169.231, 'My old phone'),
(8, 2, 'Rayban', 'Aviators', 2, '2017-07-02 11:20:49', 'rayban, aviators', 92.1538, 'Went to vidcon this weekend wearing this!! So cool !!'),
(9, 3, 'Generic', 'Fidget Spinner', 2, '2017-06-04 15:13:07', 'fidget,spinner,cheap', 2.50769, 'Everyone\'s buying these nowadays!! \"Trend\"'),
(10, 1, 'Canon', '1300D DSLR', 23, '2017-07-21 19:59:44', 'canon,dslr,camera', 140.233, 'My first camera!!'),
(11, 1, 'Lamborghini', 'Galardo', 1, '2017-07-23 13:03:35', 'lamborghini,galardo', 149800, 'Finally! a Lambo!');

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `follow_id` int(100) NOT NULL,
  `follow_uid` int(100) NOT NULL,
  `follow_fid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`follow_id`, `follow_uid`, `follow_fid`) VALUES
(44, 1, 3),
(128, 2, 1),
(129, 3, 1),
(158, 2, 3),
(159, 3, 2),
(160, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `forgotpassword`
--

CREATE TABLE `forgotpassword` (
  `fpass_id` int(11) NOT NULL,
  `fpass_code` varchar(255) NOT NULL,
  `fpass_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forgotpassword`
--

INSERT INTO `forgotpassword` (`fpass_id`, `fpass_code`, `fpass_user_id`) VALUES
(1, 'Recovered', 1),
(4, 'Recovered', 2);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `like_user_id` int(11) NOT NULL,
  `like_entry_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `like_user_id`, `like_entry_id`) VALUES
(2, 3, 2),
(32, 1, 1),
(41, 2, 6),
(47, 2, 1),
(52, 1, 5),
(57, 1, 7),
(58, 1, 6),
(60, 1, 3),
(61, 1, 2),
(63, 3, 3),
(65, 2, 2),
(68, 2, 5),
(73, 3, 8),
(74, 3, 9),
(75, 3, 5),
(79, 2, 3),
(80, 1, 8),
(81, 2, 8),
(82, 2, 7),
(83, 2, 9),
(84, 1, 10),
(85, 2, 10),
(86, 2, 11),
(87, 3, 11),
(88, 3, 1),
(91, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `notif_user_id` int(11) NOT NULL,
  `notif_text` varchar(255) NOT NULL,
  `notif_date` datetime NOT NULL,
  `notif_status` tinyint(1) NOT NULL,
  `notif_type` varchar(50) NOT NULL,
  `notif_link` varchar(255) NOT NULL,
  `notif_pic_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notif_id`, `notif_user_id`, `notif_text`, `notif_date`, `notif_status`, `notif_type`, `notif_link`, `notif_pic_link`) VALUES
(84, 2, 'Random Model followed you.', '2017-07-06 16:08:34', 0, '', 'profile?id=1', '1'),
(85, 3, 'Random Model followed you.', '2017-07-06 16:16:43', 0, '', 'profile?id=1', '1'),
(105, 1, 'Arpan Das followed you.', '2017-07-16 19:31:58', 0, '', 'profile?id=2', '2'),
(109, 2, 'Third User followed you.', '2017-07-19 18:54:30', 0, '', 'profile?id=3', '3'),
(110, 1, 'Arpan Das liked your entry', '2017-07-20 12:22:41', 0, 'like', 'comment?sid=7', '2'),
(112, 3, 'Arpan Das followed you.', '2017-07-20 22:09:17', 0, '', 'profile?id=2', '2'),
(113, 1, 'Arpan Das liked your entry', '2017-07-21 22:06:49', 0, 'like', 'comment?sid=10', '2'),
(114, 1, 'Arpan Das commented on your post', '2017-07-22 11:49:54', 0, '', 'comment?sid=10', '2'),
(115, 1, 'Arpan Das followed you.', '2017-07-23 11:35:03', 0, '', 'profile?id=2', '2'),
(117, 1, 'Arpan Das commented on your post', '2017-07-23 13:18:13', 0, '', 'comment?sid=11', '2'),
(118, 1, 'Arpan Das commented on your post', '2017-07-23 13:36:37', 0, '', 'comment?sid=11', '2'),
(120, 1, 'Third User commented on your post', '2017-07-23 21:57:29', 0, '', 'comment?sid=11', '3'),
(121, 1, 'Third User liked your entry', '2017-07-23 21:57:47', 0, 'like', 'comment?sid=1', '3');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(100) NOT NULL,
  `user_first_name` varchar(255) NOT NULL,
  `user_last_name` varchar(255) NOT NULL,
  `user_gender` int(1) NOT NULL,
  `user_dn` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_country` int(100) NOT NULL,
  `user_currency` int(100) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_first_name`, `user_last_name`, `user_gender`, `user_dn`, `user_email`, `user_country`, `user_currency`, `user_password`) VALUES
(1, 'Rich', 'Model', 1, 'imsofabulous', 'alan@turing.com', 57, 26, 'qwertyu'),
(2, 'Arpan', 'Das', 1, 'armansky94', 'armansky94@yahoo.in', 122, 25, '@lphaHE5'),
(3, 'Third', 'User', 2, 'noprofilepicture', 'anujadas2004@gmail.com', 2, 5, 'qqqqqq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`),
  ADD UNIQUE KEY `currency_id` (`currency_id`);

--
-- Indexes for table `entries`
--
ALTER TABLE `entries`
  ADD PRIMARY KEY (`entry_id`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`follow_id`);

--
-- Indexes for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  ADD PRIMARY KEY (`fpass_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `entries`
--
ALTER TABLE `entries`
  MODIFY `entry_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `follow_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `forgotpassword`
--
ALTER TABLE `forgotpassword`
  MODIFY `fpass_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
