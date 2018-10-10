-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `accID` int(200) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` int(200) unsigned NOT NULL,
  `parentAccID` int(200) unsigned NOT NULL,
  `AccName` varchar(100) NOT NULL,
  `AccText` varchar(10) NOT NULL,
  `AccNo` int(20) NOT NULL,
  `AccGrp` int(2) NOT NULL,
  `prg` int(2) NOT NULL,
  `track` int(2) NOT NULL DEFAULT '0',
  `budget` int(2) NOT NULL DEFAULT '1',
  `Active` int(1) NOT NULL DEFAULT '1',
  `is_admin` int(5) NOT NULL,
  PRIMARY KEY (`accID`),
  KEY `AccNo` (`AccNo`),
  KEY `AccGrp` (`AccGrp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `banks`;
CREATE TABLE `banks` (
  `bankID` int(100) NOT NULL AUTO_INCREMENT,
  `bankName` varchar(100) NOT NULL,
  `swift_code` varchar(50) NOT NULL,
  PRIMARY KEY (`bankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `bssubmitted`;
CREATE TABLE `bssubmitted` (
  `bsID` int(100) NOT NULL AUTO_INCREMENT,
  `bsKeys` varchar(50) NOT NULL,
  `icpNo` varchar(10) NOT NULL,
  `month` date NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`bsID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cashbal`;
CREATE TABLE `cashbal` (
  `balID` int(100) NOT NULL AUTO_INCREMENT,
  `month` date NOT NULL,
  `icpNo` varchar(6) NOT NULL,
  `accNo` varchar(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`balID`),
  KEY `month` (`month`),
  KEY `icpNo` (`icpNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cheque_book`;
CREATE TABLE `cheque_book` (
  `cheque_book_id` int(100) NOT NULL AUTO_INCREMENT,
  `bankID` int(100) NOT NULL,
  `start_date` date NOT NULL,
  `start_serial` int(10) NOT NULL,
  `pages` int(10) NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cheque_book_id`),
  KEY `bankID` (`bankID`),
  CONSTRAINT `cheque_book_ibfk_1` FOREIGN KEY (`bankID`) REFERENCES `banks` (`bankID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `civa`;
CREATE TABLE `civa` (
  `civaID` int(100) NOT NULL AUTO_INCREMENT,
  `accID` int(10) NOT NULL,
  `AccNoCIVA` varchar(100) NOT NULL,
  `AccTextCIVA` varchar(100) NOT NULL,
  `allocate` longtext NOT NULL,
  `open` int(1) NOT NULL DEFAULT '1',
  `closureDate` date NOT NULL,
  PRIMARY KEY (`civaID`),
  KEY `AccNoCIVA` (`AccNoCIVA`),
  KEY `AccTextCIVA` (`AccTextCIVA`),
  KEY `accID` (`accID`),
  KEY `open` (`open`),
  KEY `closureDate` (`closureDate`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clusters`;
CREATE TABLE `clusters` (
  `clusters_id` int(100) NOT NULL AUTO_INCREMENT,
  `clusterName` varchar(100) NOT NULL,
  PRIMARY KEY (`clusters_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `opfundsbal`;
CREATE TABLE `opfundsbal` (
  `balID` int(100) NOT NULL AUTO_INCREMENT,
  `balHdID` int(100) NOT NULL,
  `funds` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`balID`),
  KEY `balHdID` (`balHdID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `opfundsbalheader`;
CREATE TABLE `opfundsbalheader` (
  `balHdID` int(100) NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(6) NOT NULL,
  `totalBal` decimal(10,2) NOT NULL,
  `closureDate` date NOT NULL,
  `allowEdit` int(1) NOT NULL DEFAULT '1',
  `submitted` int(1) NOT NULL DEFAULT '0',
  `systemOpening` int(1) NOT NULL DEFAULT '0',
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`balHdID`),
  KEY `closureDate` (`closureDate`),
  KEY `icpNo` (`icpNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `oschqbf`;
CREATE TABLE `oschqbf` (
  `osBfID` int(11) NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(6) NOT NULL,
  `ChqNo` varchar(50) NOT NULL,
  `TDate` date NOT NULL,
  `TDescription` varchar(100) NOT NULL DEFAULT 'None',
  `VNumber` int(11) NOT NULL,
  `totals` decimal(10,2) NOT NULL,
  `chqState` int(1) NOT NULL,
  `clrMonth` date NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`osBfID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pfdetails`;
CREATE TABLE `pfdetails` (
  `pfID` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `oname` varchar(50) NOT NULL,
  `cname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`pfID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `planheader`;
CREATE TABLE `planheader` (
  `planHeaderID` int(100) NOT NULL AUTO_INCREMENT,
  `fy` int(2) NOT NULL,
  `icpNo` varchar(6) NOT NULL,
  `smtp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`planHeaderID`),
  KEY `fy` (`fy`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `plansrequests`;
CREATE TABLE `plansrequests` (
  `requestID` int(11) NOT NULL AUTO_INCREMENT,
  `senderID` int(10) NOT NULL,
  `scheduleID` int(10) NOT NULL,
  `rqMessage` longtext,
  `closed` int(1) DEFAULT '0',
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`requestID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `plansschedule`;
CREATE TABLE `plansschedule` (
  `scheduleID` int(11) NOT NULL AUTO_INCREMENT,
  `planHeaderID` int(11) NOT NULL,
  `planType` int(1) NOT NULL DEFAULT '1',
  `AccNo` int(10) NOT NULL,
  `progStage` int(10) NOT NULL DEFAULT '2' COMMENT '1 = Early Childhood, 2 = Childhood, 3 = Youth',
  `plan_item_tag_id` int(10) NOT NULL,
  `details` varchar(100) NOT NULL,
  `qty` int(5) NOT NULL,
  `unitCost` double NOT NULL,
  `often` int(10) NOT NULL,
  `totalCost` double NOT NULL,
  `validate` double NOT NULL,
  `month_1_amount` double NOT NULL,
  `month_2_amount` double NOT NULL,
  `month_3_amount` double NOT NULL,
  `month_4_amount` double NOT NULL,
  `month_5_amount` double NOT NULL,
  `month_6_amount` double NOT NULL,
  `month_7_amount` double NOT NULL,
  `month_8_amount` double NOT NULL,
  `month_9_amount` double NOT NULL,
  `month_10_amount` double NOT NULL,
  `month_11_amount` double NOT NULL,
  `month_12_amount` double NOT NULL,
  `notes` longtext NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0' COMMENT '0-Draft,1=Submitted,2=Approved,3=Declined,4=Reinstated,5=Allow Delete',
  `submitDate` date NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`scheduleID`),
  KEY `planHeaderID` (`planHeaderID`),
  KEY `approved` (`approved`),
  KEY `AccNo` (`AccNo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `projectsdetails`;
CREATE TABLE `projectsdetails` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(5) NOT NULL,
  `icpName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cluster_id` int(10) NOT NULL,
  `bankID` int(5) NOT NULL,
  `system_start_date` date NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `statementbal`;
CREATE TABLE `statementbal` (
  `balID` int(11) NOT NULL AUTO_INCREMENT,
  `month` date NOT NULL,
  `statementDate` date NOT NULL,
  `actualDate` date NOT NULL DEFAULT '0000-00-00',
  `icpNo` varchar(6) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`balID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `transitfundsbf`;
CREATE TABLE `transitfundsbf` (
  `transitBfID` int(11) NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(6) NOT NULL,
  `TDate` date NOT NULL,
  `Details` varchar(100) NOT NULL DEFAULT 'None',
  `VNumber` int(10) NOT NULL DEFAULT '0',
  `amount` decimal(10,0) NOT NULL,
  `TState` int(1) NOT NULL DEFAULT '0',
  `clrMonth` date NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`transitBfID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `varjustify`;
CREATE TABLE `varjustify` (
  `justifyID` int(100) NOT NULL AUTO_INCREMENT,
  `icpNo` varchar(10) NOT NULL,
  `AccNo` int(5) NOT NULL,
  `Details` varchar(500) NOT NULL DEFAULT 'Notes not available',
  `reportMonth` date NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`justifyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `voucherfootnotes`;
CREATE TABLE `voucherfootnotes` (
  `footnoteID` int(100) NOT NULL AUTO_INCREMENT,
  `hID` int(100) NOT NULL,
  `VDate` date NOT NULL,
  `icpNo` varchar(10) NOT NULL,
  `VNumber` varchar(10) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `msg_from` varchar(100) NOT NULL,
  `stmp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`footnoteID`)
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
  `scheduleID` int(100) unsigned NOT NULL,
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

DROP TABLE IF EXISTS `voucher_body_trash`;
CREATE TABLE `voucher_body_trash` (
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

DROP TABLE IF EXISTS `voucher_header_trash`;
CREATE TABLE `voucher_header_trash` (
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


-- 2018-10-10 09:25:37
