DELIMITER $$
CREATE PROCEDURE `get_cash_balances`(IN `icp` VARCHAR(6), IN `start_date` DATE)
BEGIN

SELECT accNo,amount FROM cashbal WHERE icpNo = icp AND month = start_date;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_cheque_details`(IN `icp` VARCHAR(6), IN `chq` INT(10))
BEGIN

SELECT Qty,Details,UnitCost,Cost,AccNo,scheduleID,civaCode,ChqNo,VNumber FROM voucher_body 
WHERE ChqNo LIKE CONCAT(chq ,'-%') AND icpNo = icp;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_accounts`(IN `type` INT(5))
BEGIN

IF type IS NULL THEN 
SELECT * FROM accounts ORDER BY AccNo;
ELSE 
SELECT * FROM accounts WHERE AccGRP = type ORDER By AccNo;
END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_cheques_utilized`(IN `icp` VARCHAR(6))
BEGIN
SELECT ChqNo,sum(totals) as totals FROM voucher_header WHERE icpNo = icp AND VType='CHQ' GROUP BY ChqNo;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_civs`(IN `status` VARCHAR(10))
BEGIN

IF status = "open" THEN 

SELECT * FROM civa WHERE open = 1;

ELse 

SELECT * FROM civa WHERE open = 0;

END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_banks`()
BEGIN

SELECT * FROM banks;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_cleared_effects`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,voucher_header.ChqState,voucher_header.clrMonth,SUM(Cost) as Cost  
FROM voucher_body LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID WHERE voucher_header.icpNo = icp AND 
voucher_body.VType IN ('CHQ','CR')  AND ChqState = 1 AND  clrMonth BETWEEN start_date AND end_date GROUP BY voucher_header.VNumber;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_current_budget`(IN `icp` VARCHAR(6), IN `fyr` INT(2))
BEGIN

SELECT plansschedule.scheduleID,planheader.icpNo,planheader.fy,plansschedule.AccNo,plansschedule.totalCost,plansschedule.details,plansschedule.approved FROM planheader LEFT JOIN plansschedule ON planheader.planHeaderID=plansschedule.planHeaderID WHERE planheader.fy = fyr AND planheader.icpNo = icp AND plansschedule.approved = 2;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_icp_max_voucher`(IN `icp` VARCHAR(6))
BEGIN
SELECT TDate,Fy,VNumber FROM voucher_header WHERE hID = (SELECT max(hID) FROM voucher_header WHERE icpNo = icp);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_journal_transactions`(IN `icpNo` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,SUM(Cost) as Cost,voucher_body.scheduleID,voucher_body.civaCode  FROM voucher_body 
LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID 
LEFT JOIN accounts ON voucher_body.AccNo = accounts.AccNo 	
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date 	
GROUP BY voucher_header.VNumber,voucher_body.AccNo;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_max_report_submitted`(IN `icp` VARCHAR(6))
BEGIN
SELECT * FROM opfundsbalheader WHERE balHdID = (SELECT max(balHdID) FROM opfundsbalheader WHERE icpNo = icp);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_month_fund_balance`(IN `icp` VARCHAR(6), IN `closeDate` DATE)
BEGIN 

SELECT accID,AccText,AccName,AccNo,amount FROM opfundsbalheader LEFT JOIN opfundsbal ON opfundsbalheader.balHdID=opfundsbal.balHdID LEFT JOIN accounts ON opfundsbal.funds=accounts.AccNo WHERE icpNo = icp AND closureDate = closeDate AND amount != 0;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_months_sum_per_account`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN 

SELECT accID,accounts.AccNo,AccText,AccName,sum(Cost) as Cost,AccGrp,parentAccID,VType  FROM accounts LEFT JOIN voucher_body ON accounts.AccNo=voucher_body.AccNo WHERE icpNo = icp AND TDate BETWEEN start_date AND end_date AND AccGrp IN (0,1) GROUP BY AccNo ORDER BY AccNo;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_months_sum_per_vtype`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN 

SELECT sum(Cost) as Cost,VType  FROM voucher_body WHERE icpNo = icp AND TDate BETWEEN start_date AND end_date GROUP BY VType ORDER BY VType;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_outstanding_effects`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,voucher_header.ChqState,voucher_header.clrMonth,SUM(Cost) as Cost  
FROM voucher_body LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID WHERE voucher_header.icpNo = icp AND 
voucher_body.VType IN ('CHQ','CR')  AND (ChqState = 0 || (ChqState = 1 &&  clrMonth > end_date)) AND voucher_header.TDate <= end_date  GROUP BY voucher_header.VNumber;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_project_details`(IN `icp` VARCHAR(6))
BEGIN 

SELECT * FROM projectsdetails WHERE icpNo = icp;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_special_accounts_sum`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN 

SELECT accounts.AccNo,AccText,AccName,sum(Cost) as Cost,AccGrp  FROM accounts LEFT JOIN voucher_body ON accounts.AccNo=voucher_body.AccNo WHERE icpNo = icp AND TDate BETWEEN start_date AND end_date AND AccGrp NOT IN (0,1) GROUP BY AccNo ORDER BY AccNo;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_vnumber_grouped_month_transaction`(IN `icp` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,SUM(Cost) as Cost FROM voucher_body LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID 
LEFT JOIN accounts ON voucher_body.AccNo = accounts.AccNo 	
WHERE voucher_header.icpNo = icp AND voucher_header.TDate BETWEEN start_date AND end_date 	
GROUP BY voucher_header.VNumber;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_voucher_transactions`(IN `icpNo` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE)
BEGIN
select voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,voucher_body.Cost,voucher_body.scheduleID,voucher_body.civaCode 
FROM voucher_body
LEFT JOIN voucher_header ON voucher_header.hID=voucher_body.hID  
LEFT JOIN accounts ON accounts.AccNo=voucher_body.AccNo 
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date;

END$$
DELIMITER ;
