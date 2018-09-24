DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_accounts`()
BEGIN

SELECT * FROM accounts;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_current_budget`(IN `icp` VARCHAR(6), IN `fyr` INT(2))
BEGIN

SELECT plansschedule.scheduleID,planheader.icpNo,planheader.fy,plansschedule.AccNo,plansschedule.totalCost,plansschedule.details,plansschedule.approved FROM planheader LEFT JOIN plansschedule ON planheader.planHeaderID=plansschedule.planHeaderID WHERE planheader.fy = fyr AND planheader.icpNo = icp AND plansschedule.approved = 2;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cheques_utilized`(IN `icp` VARCHAR(6))
BEGIN
SELECT ChqNo FROM voucher_header WHERE icpNo = icp AND VType='CHQ';
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_icp_max_voucher`(IN `icp` VARCHAR(6))
BEGIN
SELECT * FROM voucher_header WHERE hID = (SELECT max(hID) FROM voucher_header WHERE icpNo = icp);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_max_report_submitted`(IN `icp` VARCHAR(6))
BEGIN
SELECT * FROM opfundsbalheader WHERE balHdID = (SELECT max(balHdID) FROM opfundsbalheader WHERE icpNo = icp);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_journal_transactions`(IN `icpNo` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.VNumber,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,SUM(Cost) as Cost FROM voucher_body LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID 
LEFT JOIN accounts ON voucher_body.AccNo = accounts.AccNo 	
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date 	
GROUP BY voucher_header.VNumber,voucher_body.AccNo;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_project_details`(IN `icp` VARCHAR(6))
BEGIN 

SELECT * FROM projectsdetails WHERE icpNo = icp;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_voucher_transactions`(IN `icpNo` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
select voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,voucher_body.Cost,voucher_body.scheduleID 
FROM voucher_body
LEFT JOIN voucher_header ON voucher_header.hID=voucher_body.hID  
LEFT JOIN accounts ON accounts.AccNo=voucher_body.AccNo 
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date;

END$$
DELIMITER ;
