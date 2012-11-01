-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: Nov 01, 2012 alle 15:04
-- Versione del server: 5.5.24
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
-- Struttura della tabella `tr_params`
--

DROP TABLE IF EXISTS `tr_params`;
CREATE TABLE IF NOT EXISTS `tr_params` (
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_role`
--

DROP TABLE IF EXISTS `tr_role`;
CREATE TABLE IF NOT EXISTS `tr_role` (
  `uid` int(6) unsigned NOT NULL,
  `role` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'user',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- RELATIONS FOR TABLE `tr_role`:
--   `uid`
--       `tr_user` -> `id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `tr_user`
--

DROP TABLE IF EXISTS `tr_user`;
CREATE TABLE IF NOT EXISTS `tr_user` (
  `username` varchar(40) COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8_bin NOT NULL,
  `active` int(1) NOT NULL,
  `code` varchar(128) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `tr_role`
--
ALTER TABLE `tr_role`
  ADD CONSTRAINT `tr_role_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `tr_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
