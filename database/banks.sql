-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `banks`;
CREATE TABLE `banks` (
  `bankID` int(100) NOT NULL AUTO_INCREMENT,
  `bankName` varchar(100) NOT NULL,
  `swift_code` varchar(50) NOT NULL,
  PRIMARY KEY (`bankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `banks` (`bankID`, `bankName`, `swift_code`) VALUES
(1,	'BARCLAYS BANK OF KENYA',	''),
(2,	'KENYA COMMERCIAL BANK',	''),
(3,	'COOPERATIVE BANK OF KENYA',	''),
(4,	'K-REP BANK',	''),
(6,	'STANDARD CHARTERED BANK',	''),
(9,	'EQUITY BANK',	''),
(10,	'COMMERCIAL BANK OF AFRICA',	''),
(11,	'NATIONAL BANK OF KENYA',	''),
(12,	'CONSOLIDATED BANK OF KENYA',	''),
(23,	'STANDARD CHARTERED BANK',	'');

-- 2018-09-26 10:25:37
