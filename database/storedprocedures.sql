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
CREATE PROCEDURE `get_approved_budget_spread`(IN `icp` VARCHAR(6), IN `fyr` INT(5))
BEGIN

SELECT plansschedule.scheduleID,planheader.icpNo,planheader.fy,plansschedule.AccNo,plansschedule.totalCost,plansschedule.details,plansschedule.approved,month_1_amount,month_2_amount,month_3_amount,month_4_amount,month_5_amount,month_6_amount,month_7_amount,month_8_amount,month_9_amount,month_10_amount,month_11_amount,month_12_amount 
FROM planheader LEFT JOIN plansschedule ON planheader.planHeaderID=plansschedule.planHeaderID WHERE planheader.fy = fyr AND planheader.icpNo = icp AND plansschedule.approved = 2;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_budget_schedules_by_id`(IN `schedule_id` INT(10))
BEGIN

SELECT planheader.icpNo,planheader.fy,accounts.AccText,accounts.AccName,plansschedule.AccNo,parentAccID,expense_tracking_tag_id,plansschedule.scheduleID,qty,unitCost,often,plansschedule.totalCost,plansschedule.details,plansschedule.approved,month_1_amount,month_2_amount,month_3_amount,month_4_amount,month_5_amount,month_6_amount,month_7_amount,month_8_amount,month_9_amount,month_10_amount,month_11_amount,month_12_amount,notes,approved,submitDate,stmp 
FROM planheader LEFT JOIN plansschedule ON 
planheader.planHeaderID=plansschedule.planHeaderID 
LEFT JOIN accounts ON 
accounts.AccNo=plansschedule.AccNo
WHERE scheduleID = schedule_id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_banks`()
BEGIN

SELECT * FROM banks;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_budget_schedules`(IN `icp` VARCHAR(6), IN `fyr` INT(2))
BEGIN

SELECT planheader.icpNo,planheader.fy,accounts.AccText,accounts.AccName,plansschedule.AccNo,parentAccID,plansschedule.expense_tracking_tag_id,plansschedule.scheduleID,qty,unitCost,often,plansschedule.totalCost,plansschedule.details,plansschedule.approved,month_1_amount,month_2_amount,month_3_amount,month_4_amount,month_5_amount,month_6_amount,month_7_amount,month_8_amount,month_9_amount,month_10_amount,month_11_amount,month_12_amount,notes,approved,submitDate,stmp,accounts_expense_tracking.accounts_expense_tracking_id as tag_id,accounts_expense_tracking.tag_description,accounts_expense_tracking.uk_expense_account_numeric_code as tag_account, accounts_expense_tracking.tag_status
FROM planheader LEFT JOIN plansschedule ON 
planheader.planHeaderID=plansschedule.planHeaderID 
LEFT JOIN accounts ON 
accounts.AccNo=plansschedule.AccNo
LEFT JOIN accounts_expense_tracking ON plansschedule.expense_tracking_tag_id = accounts_expense_tracking.accounts_expense_tracking_id 
WHERE planheader.fy = fyr AND planheader.icpNo = icp ORDER BY AccNo;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_account_choices`(IN `accNo` INT(5))
BEGIN

SELECT * FROM plan_item_choice WHERE AccNo = accNo ORDER By name;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_budgeted_accounts`(IN `reqbudget` INT(5), IN `type` INT(5))
BEGIN

IF type IS NULL THEN 
SELECT * FROM accounts WHERE budget = reqbudget  ORDER BY AccNo;
ELSE 
SELECT * FROM accounts WHERE AccGRP = type AND budget = reqbudget ORDER By AccNo;
END IF;
END$$
DELIMITER ;

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

SELECT plansschedule.scheduleID,planheader.icpNo,planheader.fy,plansschedule.AccNo,plansschedule.totalCost,plansschedule.details,plansschedule.approved, accounts_expense_tracking.accounts_expense_tracking_id as tag_id,accounts_expense_tracking.tag_description,accounts_expense_tracking.uk_expense_account_numeric_code as tag_account  FROM planheader 
LEFT JOIN plansschedule ON planheader.planHeaderID=plansschedule.planHeaderID 
LEFT JOIN accounts_expense_tracking ON plansschedule.expense_tracking_tag_id = accounts_expense_tracking.accounts_expense_tracking_id 
WHERE planheader.fy = fyr AND planheader.icpNo = icp AND plansschedule.approved = 2;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_expense_tracking_tag_by_id`(IN `id` INT(10))
BEGIN

SELECT tag_description,uk_expense_account_numeric_code as tag_account,tag_status FROM accounts_expense_tracking WHERE accounts_expense_tracking_id = id;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_expense_tracking_tags`(IN `status` VARCHAR(10), IN `tag_id` INT(100))
BEGIN

IF tag_id = 0 THEN

IF status = "open" THEN 

SELECT accounts_expense_tracking_id as tag_id,accounts.AccText as account_name,accounts.AccName as account_desc,accounts_expense_tracking_id as tag_id,tag_description as tag_desc,uk_expense_account_numeric_code as tag_account,tag_status, timestamp FROM `accounts_expense_tracking` LEFT JOIN accounts ON accounts_expense_tracking.uk_expense_account_numeric_code = accounts.AccNo WHERE tag_status = '1';

ELseIF status = "closed" THEN 

SELECT accounts_expense_tracking_id as tag_id,accounts.AccText as account_name,accounts.AccName as account_desc,accounts_expense_tracking_id as tag_id,tag_description as tag_desc,uk_expense_account_numeric_code as tag_account,tag_status, timestamp FROM `accounts_expense_tracking` LEFT JOIN accounts ON accounts_expense_tracking.uk_expense_account_numeric_code = accounts.AccNo WHERE tag_status = '0';

ELSE 

SELECT accounts_expense_tracking_id as tag_id,accounts.AccText as account_name,accounts.AccName as account_desc,accounts_expense_tracking_id as tag_id,tag_description as tag_desc,uk_expense_account_numeric_code as tag_account,tag_status, timestamp FROM `accounts_expense_tracking` LEFT JOIN accounts ON accounts_expense_tracking.uk_expense_account_numeric_code = accounts.AccNo;

END IF;

ELSE 


SELECT accounts_expense_tracking_id as tag_id,accounts.AccText as account_name,accounts.AccName as account_desc,accounts_expense_tracking_id as tag_id,tag_description as tag_desc,uk_expense_account_numeric_code as tag_account,tag_status, timestamp FROM `accounts_expense_tracking` LEFT JOIN accounts ON accounts_expense_tracking.uk_expense_account_numeric_code = accounts.AccNo WHERE accounts_expense_tracking_id = tag_id;


END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_icp_max_voucher`(IN `icp` VARCHAR(6), IN `vtyp` VARCHAR(6))
BEGIN
IF vtyp IS NULL THEN

SELECT TDate,Fy,VNumber FROM voucher_header WHERE hID = (SELECT max(hID) FROM voucher_header WHERE icpNo = icp);

ELSEIF vtyp = 'CHQ' THEN

SELECT TDate,Fy,VNumber,VType,ChqNo FROM voucher_header WHERE hID = (SELECT max(hID) FROM voucher_header WHERE icpNo = icp AND VType = vtyp AND ChqNo <> 0);

ELSE

SELECT TDate,Fy,VNumber,VType,ChqNo FROM voucher_header WHERE hID = (SELECT max(hID) FROM voucher_header WHERE icpNo = icp AND VType = vtyp);

END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_journal_transactions`(IN `icpNo` VARCHAR(6), IN `start_date` DATE, IN `end_date` DATE, IN `voucher` INT(8))
BEGIN

IF voucher IS NULL THEN 

SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,SUM(Cost) as Cost,voucher_body.scheduleID,plansschedule.details as scheduleDetail,plansschedule.expense_tracking_tag_id as tag_id,voucher_body.civaCode,accounts_expense_tracking.tag_description  FROM voucher_body 
LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID 
LEFT JOIN accounts ON voucher_body.AccNo = accounts.AccNo 
LEFT JOIN plansschedule ON voucher_body.scheduleID=plansschedule.scheduleID 
LEFT JOIN accounts_expense_tracking ON plansschedule.expense_tracking_tag_id = accounts_expense_tracking.accounts_expense_tracking_id 
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date 	
GROUP BY voucher_header.VNumber,voucher_body.AccNo;

ELSE

SELECT voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,SUM(Cost) as Cost,voucher_body.scheduleID,plansschedule.details as scheduleDetail,plansschedule.expense_tracking_tag_id as tag_id,voucher_body.civaCode,accounts_expense_tracking.tag_description   FROM voucher_body 
LEFT JOIN voucher_header ON voucher_body.hID = voucher_header.hID 
LEFT JOIN accounts ON voucher_body.AccNo = accounts.AccNo 
LEFT JOIN plansschedule ON voucher_body.scheduleID=plansschedule.scheduleID 
LEFT JOIN accounts_expense_tracking ON plansschedule.expense_tracking_tag_id = accounts_expense_tracking.accounts_expense_tracking_id 
WHERE voucher_header.icpNo = icpNo AND voucher_header.VNumber = voucher 
GROUP BY voucher_header.VNumber,voucher_body.Details;

END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_latest_cheque_book`(IN `icp` VARCHAR(6))
BEGIN
SELECT cheque_book.bankID,bankName,icpNo,start_date,start_serial,pages FROM cheque_book LEFT JOIN banks ON cheque_book.bankID = banks.bankID  WHERE cheque_book_id = (SELECT max(cheque_book_id) FROM cheque_book WHERE icpNo = icp AND status = 1);
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
CREATE PROCEDURE `get_statementbal`(IN `icp` VARCHAR(6), IN `end_date` DATE)
BEGIN

SELECT * FROM statementbal WHERE icpNo = icp AND month = end_date;

END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_trackable_expenses`(IN `icp` VARCHAR(6), IN `startdate` DATE, IN `enddate` DATE)
BEGIN
SELECT plansschedule.scheduleID,plansschedule.details,VNumber,SUM(Cost) as Cost FROM voucher_body 
LEFT JOIN plansschedule ON voucher_body.scheduleID=plansschedule.scheduleID 
WHERE TDate BETWEEN startdate AND enddate AND icpNo = icp AND expense_tracking_tag_id > 0 
GROUP BY scheduleID, VNumber ORDER BY VNumber;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `get_tracked_expenses_breakdown`(IN `vnum` INT(10), IN `schedid` INT(10))
BEGIN
SELECT expense_breakdown_id as tracking_id,VNumber,expense_breakdown.scheduleID,referenceNo,amount,details,tag_description FROM expense_breakdown  
LEFT JOIN plansschedule ON expense_breakdown.scheduleID=plansschedule.scheduleID 
LEFT JOIN accounts_expense_tracking ON plansschedule.expense_tracking_tag_id = accounts_expense_tracking.accounts_expense_tracking_id
WHERE VNumber = vnum AND expense_breakdown.scheduleID = schedid
ORDER BY VNumber;
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
