-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `access_id` int(100) NOT NULL AUTO_INCREMENT,
  `level` int(100) NOT NULL,
  `apps_id` int(100) NOT NULL,
  `status` int(5) NOT NULL,
  PRIMARY KEY (`access_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `voucher_body`;
CREATE TABLE `voucher_body` (
  `bID` int(200) unsigned NOT NULL AUTO_INCREMENT,
  `hID` int(100) NOT NULL,
  `icpNo` varchar(6) NOT NULL,
  `VNumber` varchar(10) NOT NULL,
  `TDate` date NOT NULL,
  `Qty` decimal(10,2) NOT NULL,
  `Details` text NOT NULL,
  `UnitCost` decimal(10,2) NOT NULL,
  `Cost` decimal(10,2) NOT NULL,
  `AccNo` varchar(20) NOT NULL,
  `civaCode` int(10) NOT NULL DEFAULT '0',
  `VType` varchar(5) NOT NULL,
  `ChqNo` varchar(20) NOT NULL,
  `unixStmp` int(100) NOT NULL,
  `tmStmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`bID`),
  KEY `icpNo` (`icpNo`),
  KEY `VNumber` (`VNumber`),
  KEY `TDate` (`TDate`),
  KEY `hID` (`hID`),
  KEY `VType` (`VType`),
  KEY `AccNo` (`AccNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `voucher_body_after_delete` AFTER DELETE ON `voucher_body` FOR EACH ROW
BEGIN

   -- Insert record into audit table
   INSERT INTO voucher_body_trash
   (bID,hID,icpNo,VNumber,TDate,Qty,Details,UnitCost,Cost,AccNo,civaCode,VType,ChqNo,unixStmp,tmStmp)
   VALUES(OLD.bID,OLD.hID,OLD.icpNo,OLD.VNumber,OLD.TDate,OLD.Qty,OLD.Details,OLD.UnitCost,OLD.Cost,OLD.AccNo,OLD.civaCode,OLD.VType,OLD.ChqNo,OLD.unixStmp,OLD.tmStmp);

END;;

DELIMITER ;

DROP TABLE IF EXISTS `voucher_header`;
CREATE TABLE `voucher_header` (
  `hID` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(6) NOT NULL,
  `TDate` date NOT NULL,
  `Fy` int(2) NOT NULL,
  `VNumber` int(6) NOT NULL,
  `Payee` varchar(200) NOT NULL,
  `Address` varchar(200) NOT NULL,
  `VType` varchar(50) NOT NULL,
  `ChqNo` varchar(10) DEFAULT NULL,
  `ChqState` int(5) NOT NULL,
  `clrMonth` date NOT NULL,
  `editable` int(5) NOT NULL DEFAULT '0',
  `TDescription` text NOT NULL,
  `totals` decimal(10,2) NOT NULL,
  `reqID` int(100) NOT NULL DEFAULT '0',
  `unixStmp` int(100) NOT NULL,
  `tmStmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`hID`),
  KEY `TDate` (`TDate`),
  KEY `icpNo` (`icpNo`),
  KEY `Fy` (`Fy`),
  KEY `VType` (`VType`),
  KEY `VNumber` (`VNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;

CREATE TRIGGER `voucher_header_after_delete` AFTER DELETE ON `voucher_header` FOR EACH ROW
BEGIN

   -- Insert record into audit table
   INSERT INTO voucher_header_trash
   (hID,icpNo,TDate,Fy,VNumber,Payee,Address,VType,ChqNo,ChqState,clrMonth,editable,TDescription,totals,reqID,unixStmp,tmStmp)
   VALUES(OLD.hID,OLD.icpNo,OLD.TDate,OLD.Fy,OLD.VNumber,OLD.Payee,OLD.Address,OLD.VType,OLD.ChqNo,OLD.ChqState,OLD.clrMonth,OLD.editable,OLD.TDescription,OLD.totals,OLD.reqID,OLD.unixStmp,OLD.tmStmp);

END;;

DELIMITER ;

-- 2018-09-03 14:45:39
