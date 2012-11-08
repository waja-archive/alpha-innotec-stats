-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2012 at 11:20 AM
-- Server version: 5.1.63
-- PHP Version: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `heating`
--

-- --------------------------------------------------------

--
-- Table structure for table `operation`
--

CREATE TABLE IF NOT EXISTS `operation` (
  `timestamp` bigint(20) NOT NULL COMMENT 'timestamp',
  `56` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitVD1',
  `57` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitImpVD1',
  `60` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitZWE1',
  `63` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitWP',
  `64` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitHz',
  `65` bigint(20) NOT NULL COMMENT 'Zaehler_BetrZeitBW',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `temperature`
--

CREATE TABLE IF NOT EXISTS `temperature` (
  `timestamp` bigint(20) NOT NULL COMMENT 'timestamp',
  `10` int(4) NOT NULL COMMENT 'Temperatur_TVL',
  `11` int(4) NOT NULL COMMENT 'Temperatur_TRL',
  `12` int(4) NOT NULL COMMENT 'Sollwert_TRL_HZ',
  `13` int(4) NOT NULL COMMENT 'Temperatur_TRL_ext',
  `14` int(4) NOT NULL COMMENT 'Temperatur_THG',
  `15` int(4) NOT NULL COMMENT 'Temperatur_TA',
  `16` int(4) NOT NULL COMMENT 'Mitteltemperatur',
  `17` int(4) NOT NULL COMMENT 'Temperatur_TBW',
  `18` int(4) NOT NULL COMMENT 'Einst_BWS_akt',
  `19` int(4) NOT NULL COMMENT 'Temperatur_TWE',
  `20` int(4) NOT NULL COMMENT 'Temperatur_TWA',
  `21` int(4) NOT NULL COMMENT 'Temperatur_TFB1',
  `22` int(4) NOT NULL COMMENT 'Sollwert_TVL_MK',
  `23` int(4) NOT NULL COMMENT 'Temperatur_RFV',
  `24` int(4) NOT NULL COMMENT 'Temperatur_TFB2',
  `25` int(4) NOT NULL COMMENT 'Sollwert_TVL_MK2',
  `26` int(4) NOT NULL COMMENT 'Temperatur_TSK',
  `27` int(4) NOT NULL COMMENT 'Temperatur_TSS',
  `28` int(4) NOT NULL COMMENT 'Temperatur_TEE',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timing`
--

CREATE TABLE IF NOT EXISTS `timing` (
  `timestamp` bigint(20) NOT NULL COMMENT 'timestamp',
  `67` bigint(20) NOT NULL COMMENT 'Time_WPein_akt',
  `68` bigint(20) NOT NULL COMMENT 'Time_ZWE1_akt',
  `70` bigint(20) NOT NULL COMMENT 'Timer_EinschVerz',
  `71` bigint(20) NOT NULL COMMENT 'Time_SSPAUS_akt',
  `72` bigint(20) NOT NULL COMMENT 'Time_SSPEIN_akt',
  `73` bigint(20) NOT NULL COMMENT 'Time_VDStd_akt',
  `74` bigint(20) NOT NULL COMMENT 'Time_HRM_akt',
  `75` bigint(20) NOT NULL COMMENT 'Time_HRW_akt',
  `77` bigint(20) NOT NULL COMMENT 'Time_SBW_akt',
  `141` bigint(20) NOT NULL COMMENT 'Time_AbtIn',
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
