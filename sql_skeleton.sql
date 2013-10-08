-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Set 27, 2013 alle 15:40
-- Versione del server: 5.5.32
-- Versione PHP: 5.3.10-1ubuntu3.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tandem_new`
--

DELIMITER $$
--
-- Procedure
--
DROP PROCEDURE IF EXISTS `UserLearnLanguages`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UserLearnLanguages`(IN UserID BIGINT(20))
BEGIN
       SELECT lang_code, level FROM user_languages WHERE (user_id = UserID) AND (mother_tongue = 0);
    END$$

--
-- Funzioni
--
DROP FUNCTION IF EXISTS `user_id`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `user_id`() RETURNS int(11)
    NO SQL
    DETERMINISTIC
return @user_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `contents`
--

DROP TABLE IF EXISTS `contents`;
CREATE TABLE IF NOT EXISTS `contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `pinned` tinyint(1) NOT NULL DEFAULT '0',
  `html` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `contents`:
--   `page_id`
--       `pages` -> `page`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `interests`
--

DROP TABLE IF EXISTS `interests`;
CREATE TABLE IF NOT EXISTS `interests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `interest` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(50) NOT NULL,
  `lang_code` varchar(2) NOT NULL,
  `lang_icon` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lang_code` (`lang_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `languages`
--

INSERT INTO `languages` (`id`, `lang_name`, `lang_code`, `lang_icon`) VALUES
(1, 'Afrikaans', 'AF', 'African Union.png'),
(2, 'Albanian', 'SQ', 'Albania.png'),
(3, 'Arabic', 'AR', 'Arab League.png'),
(4, 'Armenian', 'HY', 'Armenia.png'),
(5, 'Basque', 'EU', 'Basque Country.png'),
(6, 'Bengali', 'BN', 'Bangladesh.png'),
(7, 'Bulgarian', 'BG', 'Bulgaria.png'),
(8, 'Catalan', 'CA', 'Catalonia.png'),
(9, 'Cambodian', 'KM', 'Cambodja.png'),
(10, 'Chinese (Mandarin)', 'ZH', 'China.png'),
(11, 'Croatian', 'HR', 'Croatia.png'),
(12, 'Czech', 'CS', 'Czech Republic.png'),
(13, 'Danish', 'DA', 'Denmark.png'),
(14, 'Dutch', 'NL', 'Netherlands.png'),
(15, 'English', 'EN', 'United Kingdom(Great Britain).png'),
(16, 'Estonian', 'ET', 'Estonia.png'),
(17, 'Fiji', 'FJ', 'Fiji.png'),
(18, 'Finnish', 'FI', 'Finland.png'),
(19, 'French', 'FR', 'France.png'),
(20, 'Georgian', 'KA', 'Georgia.png'),
(21, 'German', 'DE', 'Germany.png'),
(22, 'Greek', 'EL', 'Greece.png'),
(23, 'Gujarati', 'GU', 'India'),
(24, 'Hebrew', 'HE', 'Israel.png'),
(25, 'Hindi', 'HI', 'India.png'),
(26, 'Hungarian', 'HU', 'Hungary.png'),
(27, 'Icelandic', 'IS', 'Iceland.png'),
(28, 'Indonesian', 'ID', 'Indonesia.png'),
(29, 'Irish', 'GA', 'Ireland.png'),
(30, 'Italian', 'IT', 'Italy.png'),
(31, 'Japanese', 'JA', 'Japan.png'),
(32, 'Javanese', 'JW', 'Indonesia.png'),
(33, 'Korean', 'KO', 'South Korea.png'),
(34, 'Latin', 'LA', ''),
(35, 'Latvian', 'LV', ''),
(36, 'Lithuanian', 'LT', ''),
(37, 'Macedonian', 'MK', ''),
(38, 'Malay', 'MS', ''),
(39, 'Malayalam', 'ML', ''),
(40, 'Maltese', 'MT', ''),
(41, 'Maori', 'MI', ''),
(42, 'Marathi', 'MR', ''),
(43, 'Mongolian', 'MN', ''),
(44, 'Nepali', 'NE', ''),
(45, 'Norwegian', 'NO', ''),
(46, 'Persian', 'FA', ''),
(47, 'Polish', 'PL', ''),
(48, 'Portuguese', 'PT', ''),
(49, 'Punjabi', 'PA', ''),
(50, 'Quechua', 'QU', ''),
(51, 'Romanian', 'RO', ''),
(52, 'Russian', 'RU', ''),
(53, 'Samoan', 'SM', ''),
(54, 'Serbian', 'SR', ''),
(55, 'Slovak', 'SK', ''),
(56, 'Slovenian', 'SL', ''),
(57, 'Spanish', 'ES', ''),
(58, 'Swahili', 'SW', ''),
(59, 'Swedish', 'SV', ''),
(60, 'Tamil', 'TA', ''),
(61, 'Tatar', 'TT', ''),
(62, 'Telugu', 'TE', ''),
(63, 'Thai', 'TH', ''),
(64, 'Tibetan', 'BO', ''),
(65, 'Tonga', 'TO', ''),
(66, 'Turkish', 'TR', ''),
(67, 'Ukrainian', 'UK', ''),
(68, 'Urdu', 'UR', ''),
(69, 'Uzbek', 'UZ', ''),
(70, 'Vietnamese', 'VI', ''),
(71, 'Welsh', 'CY', ''),
(72, 'Xhosa', 'XH', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `from_user_id` bigint(20) unsigned NOT NULL,
  `to_user_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `messages`:
--   `to_user_id`
--       `users` -> `id`
--   `from_user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `page` varchar(50) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `top_menu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page` (`page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `socials`
--

DROP TABLE IF EXISTS `socials`;
CREATE TABLE IF NOT EXISTS `socials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `link` varchar(200) NOT NULL,
  `name_ext` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `social_toolbar`
--

DROP TABLE IF EXISTS `social_toolbar`;
CREATE TABLE IF NOT EXISTS `social_toolbar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `social` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `social` (`social`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `social_toolbar`:
--   `social`
--       `socials` -> `name`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` varchar(1) NOT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `about` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `invisible` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `note` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`email`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `users_available`
--
DROP VIEW IF EXISTS `users_available`;
CREATE TABLE IF NOT EXISTS `users_available` (
`id` bigint(20) unsigned
,`username` varchar(20)
,`password` varchar(100)
,`name` varchar(100)
,`surname` varchar(100)
,`email` varchar(100)
,`birthdate` date
,`gender` varchar(1)
,`facebook` varchar(150)
,`about` text
,`created` timestamp
,`active` tinyint(1)
,`invisible` tinyint(1)
,`admin` tinyint(1)
);
-- --------------------------------------------------------

--
-- Struttura della tabella `user_block`
--

DROP TABLE IF EXISTS `user_block`;
CREATE TABLE IF NOT EXISTS `user_block` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `block_user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`block_user_id`),
  KEY `lock_id` (`block_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `user_block`:
--   `block_user_id`
--       `users` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `user_friends`
--

DROP TABLE IF EXISTS `user_friends`;
CREATE TABLE IF NOT EXISTS `user_friends` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `friend_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `user_friends`:
--   `friend_id`
--       `users` -> `id`
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `user_interests`
--

DROP TABLE IF EXISTS `user_interests`;
CREATE TABLE IF NOT EXISTS `user_interests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `interest_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_index` (`user_id`),
  KEY `interest_id` (`interest_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `user_interests`:
--   `user_id`
--       `users` -> `id`
--   `interest_id`
--       `interests` -> `id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `user_languages`
--

DROP TABLE IF EXISTS `user_languages`;
CREATE TABLE IF NOT EXISTS `user_languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `lang_code` varchar(2) NOT NULL,
  `mother_tongue` tinyint(1) NOT NULL DEFAULT '1',
  `level` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `lang_id` (`lang_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- RELATIONS FOR TABLE `user_languages`:
--   `user_id`
--       `users` -> `id`
--   `lang_code`
--       `languages` -> `lang_code`
--

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `user_learn_languages`
--
DROP VIEW IF EXISTS `user_learn_languages`;
CREATE TABLE IF NOT EXISTS `user_learn_languages` (
`lang_code` varchar(2)
,`level` smallint(6)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `user_speak_languages`
--
DROP VIEW IF EXISTS `user_speak_languages`;
CREATE TABLE IF NOT EXISTS `user_speak_languages` (
`lang_code` varchar(2)
);
-- --------------------------------------------------------

--
-- Struttura per la vista `users_available`
--
DROP TABLE IF EXISTS `users_available`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `users_available` AS select `users`.`id` AS `id`,`users`.`username` AS `username`,`users`.`password` AS `password`,`users`.`name` AS `name`,`users`.`surname` AS `surname`,`users`.`email` AS `email`,`users`.`birthdate` AS `birthdate`,`users`.`gender` AS `gender`,`users`.`facebook` AS `facebook`,`users`.`about` AS `about`,`users`.`created` AS `created`,`users`.`active` AS `active`,`users`.`invisible` AS `invisible`,`users`.`admin` AS `admin` from `users` where ((`users`.`id` <> `user_id`()) and (`users`.`active` = 1) and (`users`.`admin` = 0) and (`users`.`invisible` = 0));

-- --------------------------------------------------------

--
-- Struttura per la vista `user_learn_languages`
--
DROP TABLE IF EXISTS `user_learn_languages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_learn_languages` AS select `user_languages`.`lang_code` AS `lang_code`,`user_languages`.`level` AS `level` from `user_languages` where ((`user_languages`.`user_id` = `user_id`()) and (`user_languages`.`mother_tongue` = 0));

-- --------------------------------------------------------

--
-- Struttura per la vista `user_speak_languages`
--
DROP TABLE IF EXISTS `user_speak_languages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_speak_languages` AS select `user_languages`.`lang_code` AS `lang_code` from `user_languages` where ((`user_languages`.`user_id` = `user_id`()) and (`user_languages`.`mother_tongue` = 1));

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `pages` (`page`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `social_toolbar`
--
ALTER TABLE `social_toolbar`
  ADD CONSTRAINT `social_toolbar_ibfk_1` FOREIGN KEY (`social`) REFERENCES `socials` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_block`
--
ALTER TABLE `user_block`
  ADD CONSTRAINT `user_block_ibfk_2` FOREIGN KEY (`block_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_block_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_friends`
--
ALTER TABLE `user_friends`
  ADD CONSTRAINT `user_friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_interests`
--
ALTER TABLE `user_interests`
  ADD CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_interests_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_languages`
--
ALTER TABLE `user_languages`
  ADD CONSTRAINT `user_languages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_languages_ibfk_2` FOREIGN KEY (`lang_code`) REFERENCES `languages` (`lang_code`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;