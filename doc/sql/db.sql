-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 19. Apr 2015 um 11:59
-- Server Version: 5.5.37
-- PHP-Version: 5.5.21-1~dotdeb.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `dbrouter`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbr_extentsiontype`
--

CREATE TABLE IF NOT EXISTS `dbr_extentsiontype` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbr_segmenttype`
--

CREATE TABLE IF NOT EXISTS `dbr_segmenttype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `dbr_segmenttype`
--

INSERT INTO `dbr_segmenttype` (`id`, `name`) VALUES
(1, 'path'),
(2, 'file'),
(3, 'wildcard');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbr_url`
--

CREATE TABLE IF NOT EXISTS `dbr_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segmentcount` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbr_urlsegment`
--

CREATE TABLE IF NOT EXISTS `dbr_urlsegment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `segment` varchar(255) NOT NULL,
  `dbr_segmenttype_id` tinyint(3) unsigned NOT NULL,
  `dbr_extentsiontype_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `segment` (`segment`),
  KEY `dbr_segmenttype_id` (`dbr_segmenttype_id`),
  KEY `dbr_extentsiontype_id` (`dbr_extentsiontype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbr_url_urlsegment`
--

CREATE TABLE IF NOT EXISTS `dbr_url_urlsegment` (
  `dbr_url_id` int(10) unsigned NOT NULL,
  `dbr_urlsegment_id` int(10) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`dbr_url_id`,`dbr_urlsegment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
