-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 12. Jun 2018 um 09:03
-- Server-Version: 10.1.21-MariaDB
-- PHP-Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: urlfinder
--
CREATE DATABASE urlfinder DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE urlfinder;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle kategorien
--

CREATE TABLE kategorien (
  id int(11) NOT NULL AUTO_INCREMENT,
  parent_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  ebene int(11) NOT NULL,
  url varchar(70) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Daten für Tabelle kategorien
--

INSERT INTO kategorien (id, parent_id, name, ebene, url) VALUES
(1, 0, 'Google', 0, 'http://google.de'),
(2, 0, 'Facebook', 0, 'https://example.com'),
(3, 1, 'GooglePlus', 1, 'https://googleplus.com'),
(4, 2, 'examplePerson1', 1, 'https://example.com'),
(5, 4, 'friendlist', 2, 'https://example.com'),
(6, 5, 'John Doe', 3, 'https://example.com'),
(7, 5, 'Jane Doe', 3, 'https://example.com/Jane'),
(8, 5, 'Max Mustermann', 3, 'https://example.com'),
(12, 20, 'youtuber', 2, 'http://'),
(13, 12, 'profile', 3, 'https://example.com'),
(14, 12, 'playlists', 3, 'https://example.com'),
(15, 20, 'youtuber', 2, 'https://example.com'),
(16, 15, 'profile', 3, 'https://example.com'),
(18, 15, 'playlists', 3, 'https://example.com'),
(19, 20, 'youtuber', 2, 'https://example.com'),
(20, 1, 'Youtube', 1, 'http://youtube.com'),
(21, 19, 'profile', 3, 'https://example.com'),
(22, 19, 'playlists', 3, 'https://example.com'),
(23, 20, 'youtuber', 2, 'https://example.com'),
(24, 20, 'youtuber', 2, 'https://example.com'),
(26, 1, 'Maps', 1, 'https://example.com'),
(27, 2, 'examplePerson', 1, 'https://example.com')
