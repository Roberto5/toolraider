-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Nov 11, 2012 alle 01:39
-- Versione del server: 5.5.28
-- Versione PHP: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `toolraider`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_ally`
--

DROP TABLE IF EXISTS `tr_ally`;
CREATE TABLE `tr_ally` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_ally_pact`
--

DROP TABLE IF EXISTS `tr_ally_pact`;
CREATE TABLE `tr_ally_pact` (
  `aid` int(6) unsigned NOT NULL,
  `aid2` int(6) unsigned NOT NULL,
  `type` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`aid`,`aid2`,`type`),
  KEY `aid2` (`aid2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_ally_role`
--

DROP TABLE IF EXISTS `tr_ally_role`;
CREATE TABLE `tr_ally_role` (
  `aid` int(6) unsigned NOT NULL,
  `uid` int(6) unsigned NOT NULL,
  `role` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'WHAIT',
  PRIMARY KEY (`aid`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_params`
--

DROP TABLE IF EXISTS `tr_params`;
CREATE TABLE `tr_params` (
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_planet`
--

DROP TABLE IF EXISTS `tr_planet`;
CREATE TABLE `tr_planet` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(6) unsigned NOT NULL,
  `name` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'new village',
  `x` int(4) NOT NULL DEFAULT '0',
  `y` int(4) NOT NULL DEFAULT '0',
  `system` int(15) unsigned NOT NULL,
  `galaxy` int(1) NOT NULL DEFAULT '1',
  `bonus` text COLLATE utf8_bin NOT NULL,
  `type` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_role`
--

DROP TABLE IF EXISTS `tr_role`;
CREATE TABLE `tr_role` (
  `uid` int(6) unsigned NOT NULL,
  `role` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'user',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_ship`
--

DROP TABLE IF EXISTS `tr_ship`;
CREATE TABLE `tr_ship` (
  `type` int(2) NOT NULL,
  `quantity` int(10) NOT NULL DEFAULT '0',
  `pid` int(6) unsigned NOT NULL,
  PRIMARY KEY (`type`,`pid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_user`
--

DROP TABLE IF EXISTS `tr_user`;
CREATE TABLE `tr_user` (
  `username` varchar(40) COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `active` int(1) NOT NULL,
  `code` varchar(128) COLLATE utf8_bin NOT NULL,
  `code_time` int(32) NOT NULL,
  `aid` int(6) unsigned DEFAULT NULL,
  `race` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `ally` (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `tr_ally_pact`
--
ALTER TABLE `tr_ally_pact`
  ADD CONSTRAINT `tr_ally_pact_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `tr_ally` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_ally_pact_ibfk_2` FOREIGN KEY (`aid2`) REFERENCES `tr_ally` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tr_ally_role`
--
ALTER TABLE `tr_ally_role`
  ADD CONSTRAINT `tr_ally_role_ibfk_1` FOREIGN KEY (`aid`) REFERENCES `tr_ally` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tr_ally_role_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `tr_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tr_planet`
--
ALTER TABLE `tr_planet`
  ADD CONSTRAINT `tr_planet_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tr_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tr_role`
--
ALTER TABLE `tr_role`
  ADD CONSTRAINT `tr_role_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tr_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tr_ship`
--
ALTER TABLE `tr_ship`
  ADD CONSTRAINT `tr_ship_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `tr_planet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tr_user`
--
ALTER TABLE `tr_user`
  ADD CONSTRAINT `tr_user_ibfk_3` FOREIGN KEY (`aid`) REFERENCES `tr_ally` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
