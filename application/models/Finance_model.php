<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//use Finance\Utilities\Layout;

class Finance_model extends CI_Model{
	function __construct()
    {
        parent::__construct();
	
    }
	
	public function get_journal_transactions($icpNo="",$start_date="",$end_date='')
    {
		/**
		 * To CALL a stored procedure, the database config file dbdriver should be set to pdo
		 * Define the dsn when using pdo dbdriver e.g  'dsn'	=>  'mysql:host=localhost;dbname=compatl8_mvc',
		 */	
					
		$month_transactions_obj = $this->db->query("CALL get_journal_transactions(?,?,?)",array($icpNo,$start_date,$end_date));	
		
		return $month_transactions_obj->num_rows()>0?$month_transactions_obj->result_array():array();
		
    }
	
	public function get_voucher_transactions($icpNo="",$start_date="",$end_date='')
    {	
		
		/**
		 * To CALL a stored procedure, the database config file dbdriver should be set to pdo
		 * Define the dsn when using pdo dbdriver e.g  'dsn'	=>  'mysql:host=localhost;dbname=compatl8_mvc',
		 */	
		 
		$month_transactions_obj = $this->db->query("CALL get_voucher_transactions(?,?,?)",array($icpNo,$start_date,$end_date));			
		
		return $month_transactions_obj->num_rows() > 0?$month_transactions_obj->result_array():array();
    }
	
	function get_icp_max_voucher ($icp=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_icp_max_voucher(?)",array($icp));
			$result = $query->num_rows()>0?$query->row():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_max_report_submitted($icp=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_max_report_submitted(?)",array($icp));
			$result = $query->num_rows()>0?$query->row():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	public function get_current_financial_report_date($icp=""){
		extract((array)$this->get_max_report_submitted($icp));
		
		return $closureDate;
	} 
	
	public function get_current_financial_report_validated($icp=""){
		extract((array)$this->get_max_report_submitted($icp));
		
		return $allowEdit == 1?false:true;
	} 
	
	public function get_current_financial_report_submitted($icp=""){
		extract((array)$this->get_max_report_submitted($icp=""));
		
		return $submitted == 1?true:false;
	}
	
	public function get_current_financial_report_is_initial($icp=""){
		extract((array)$this->get_max_report_submitted($icp));
		
		return $systemOpening == 1?true:false;
	}
	
	public function get_current_financial_report_timestamp($icp=""){
		extract((array)$this->get_max_report_submitted($icp));
		
		return strtotime($stmp);
	}
	
	public function get_current_voucher_date($icp=""){
		extract((array)$this->get_icp_max_voucher($icp));
		
		return $TDate;
	}
	
	public function get_current_voucher_number($icp=""){
		extract((array)$this->get_icp_max_voucher($icp));
		
		return $VNumber;
	}
	
	public function get_current_voucher_timestamp($icp=""){
		extract((array)$this->get_icp_max_voucher($icp));
		
		return $unixStmp;
	}
	
	public function get_current_voucher_fy($icp=""){
		extract((array)$this->get_icp_max_voucher($icp));
		
		return $Fy;
	}
	
	function get_transacting_month($icp=""){
		$current_voucher_date = strtotime($this->get_current_voucher_date($icp));
		$current_report_date = strtotime($this->get_current_financial_report_date($icp));
		
		$params = array();
		
		if($current_voucher_date > $current_report_date){
			$params['start_date'] = strtotime(date("Y-m-01",$current_voucher_date));
			$params['end_date'] = strtotime(date("Y-m-t",$current_voucher_date));
		}else{ //MFR = 2018-09-30, Voucher Date = 2018-09-29
			$params['start_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
			$params['end_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
		}
		
		return $params;
	}

	function get_next_voucher_number($icp = ""){
		
		$current_voucher_number = $this->get_current_voucher_number($icp);
		
		$current_voucher_date = strtotime($this->get_current_voucher_date($icp));
		
		$transacting_month 		= $this->get_transacting_month($icp);		
		
		
		$voucher_year = date("y",$transacting_month['start_date']);
		$voucher_month = date("m",$transacting_month['start_date']);
		$voucher_serial = substr($current_voucher_number, 4) + 1;
			

		if($current_voucher_date < $transacting_month['start_date']){
			$voucher_serial = "01";
		}
			
		$next_voucher_number = $voucher_year.$voucher_month.$voucher_serial;
		
		return $next_voucher_number;
	}
	
	function get_voucher_date_picker_control($icp = ""){
		$current_voucher_date = strtotime($this->get_current_voucher_date($icp));
		$current_report_date = strtotime($this->get_current_financial_report_date($icp));
		
		$params = array();
		
		if($current_voucher_date > $current_report_date){
			$params['start_date'] = strtotime(date("Y-m-01",$current_voucher_date));
			$params['end_date'] = strtotime($this->get_current_voucher_date($icp));
		}else{
			$params['start_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
			$params['end_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
		}
		
		return $params;
	}
	
		
	function account_for_vouchers($param=null){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_accounts(?)",array($param));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_current_approved_budget($icpNo="",$fy=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_current_budget(?,?)",array($icpNo,$fy));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	
	function get_cheques_utilized_with_bank_code($icpNo=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_cheques_utilized(?)",array($icpNo));
			$result = $query->num_rows()>0?$query->result_array():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_cheques_utilized_without_bank_code($icpNo=""){
		$raw_cheques = $this->get_cheques_utilized_with_bank_code($icpNo);
		
		$refined_cheques = array();
		
		foreach($raw_cheques as $cheque){
			$arr = explode("-", $cheque);
			$refined_cheques[] = $arr[0];
		}
		
		return $refined_cheques;
	}
	
	function get_project_details($icpNo=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_project_details(?)",array($icpNo));
			$result = $query->num_rows()>0?$query->row():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_civs($status="open"){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_civs(?)",array($status));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function insert_voucher_to_database($post_array=array()){
		
		$header['icpNo'] = $post_array['icpNo'];
		$header['TDate'] = $post_array['TDate'];
		$header['Fy'] = 19;
		$header['VNumber'] = $post_array['VNumber'];
		$header['Payee'] = $post_array['Payee'];
		$header['Address'] = $post_array['Address'];
		$header['VType'] = $post_array['VType'];
		$header['ChqNo'] = $post_array['ChqNo'];
		$header['ChqState'] = 0;
		$header['clrMonth'] = "0000-00-00";
		$header['editable'] = 0;
		$header['TDescription'] = $post_array['TDescription'];
		$header['totals'] = $post_array['totals'];
		$header['reqID'] = 0;
		$header['unixStmp'] = strtotime(date("y-m-d"));
		
		$body = array();
		
		for($i=0;$i<count($post_array['Qty']);$i++){
			$body[$i]['icpNo'] = $post_array['icpNo'];
			$body[$i]['VNumber'] = $post_array['VNumber'];
			$body[$i]['TDate'] = $post_array['TDate'];
			$body[$i]['VType'] = $post_array['VType'];
			
			$body[$i]['Qty'] = $post_array['Qty'][$i];
			$body[$i]['Details'] = $post_array['Details'][$i];
			$body[$i]['UnitCost'] = $post_array['UnitCost'][$i];
			$body[$i]['Cost'] = $post_array['Cost'][$i];
			$body[$i]['AccNo'] = $post_array['AccNo'][$i];
			$body[$i]['scheduleID'] = $post_array['scheduleID'][$i];
			$body[$i]['civaCode'] = $post_array['civaCode'][$i];
		}
		
		
		$query = $this->db->get_where("voucher_header",array("VNumber"=>$post_array['VNumber'],"icpNo"=>$post_array['icpNo']));
		
		if($query->num_rows() == 0){
			$this->db->trans_start();
				$this->db->insert("voucher_header",$header);
				
				$hID = $this->db->insert_id();
				
				for($i=0;$i<count($post_array['Qty']);$i++){
					$body[$i]['hID'] = $hID;
				}
				
				$this->db->insert_batch("voucher_body",$body);
		
			$this->db->trans_complete();
		}
		
		$rows = $this->db->affected_rows();
		
		if($rows>0){
			return "Voucher Created Successful";
		}
	}

	function insert_cheque_booklet($post_array=array()){
		
		//$this->db->trans_start();
			$this->db->insert("cheque_book",$post_array);
		//$this->db->trans_complete();	
		
		if($this->db->affected_rows()>0){
			return "Booklet Created Successful";
		}
		
	}

	function get_cheque_details($icp="",$chqno=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_cheque_details(?,?)",array($icp,$chqno));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_banks(){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_banks()");
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}	
	
	
	/**Financial Report Methods **/

	
	function get_month_fund_balance($icp="",$closeDate=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_month_fund_balance(?,?)",array($icp,$closeDate));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_special_accounts_sum($icp="",$start_date="",$end_date=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_special_accounts_sum(?,?,?)",array($icp,$start_date,$end_date));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}
	
	function get_months_sum_per_vtype($icp="",$start_date="",$end_date=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_months_sum_per_vtype(?,?,?)",array($icp,$start_date,$end_date));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}	
		
	function get_months_sum_per_account($icp="",$start_date="",$end_date=""){
		try{
			$this->db->reconnect();
			$query = $this->db->query("CALL get_months_sum_per_account(?,?,?)",array($icp,$start_date,$end_date));
			$result = $query->num_rows()>0?$query->result_object():array();
			$this->db->close();
		}catch(Exception $e){
			echo "Message: ".$e->getMessage();
		}
		
		return $result;
	}	
		
	function start_cash_balance($icpNo="",$month_start=""){
		
		$last_month_cash_balance = "";
		$last_month = date("Y-m-t",strtotime("last day of previous month",strtotime($month_start)));
		$this->db->where(array("icpNo"=>$icpNo,"month"=>$last_month));
		$this->db->select(array("accNo","amount"));
		$last_month_cash_balance_obj = $this->db->get_where("cashbal");
		
		if($last_month_cash_balance_obj->num_rows()>0){
			$last_month_cash_balance = $last_month_cash_balance_obj->result_array();
		}else{
			$last_month_cash_balance = array(
				array('accNo'=>'PC',"amount"=>0),
				array('accNo'=>'BC',"amount"=>0)
			);
		}
		
		$cash_balance_columns = array_column($last_month_cash_balance, "accNo");
		return array_combine($cash_balance_columns, $last_month_cash_balance);
	}
}
