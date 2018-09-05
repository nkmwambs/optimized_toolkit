DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_voucher_transactions`(IN icpNo VARCHAR(6),start_date Date,end_date DATE)
BEGIN
select voucher_header.VType,voucher_header.TDate,voucher_header.VNumber,voucher_header.Payee,voucher_header.VNumber,voucher_header.Address,voucher_header.ChqNo,voucher_header.TDescription,
voucher_body.AccNo,accounts.AccText,accounts.AccGrp,voucher_body.Qty,voucher_body.Details,voucher_body.UnitCost,voucher_body.Cost 
FROM voucher_body
LEFT JOIN voucher_header ON voucher_header.hID = voucher_body.hID  
LEFT JOIN accounts ON accounts.AccNo = voucher_body.AccNo 
WHERE voucher_header.icpNo = icpNo AND voucher_header.TDate BETWEEN start_date AND end_date;

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
