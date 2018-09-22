<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_model extends CI_Model{
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
			echo "Error Occurred!";
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
			echo "Error Occurred!";
		}
		
		return $result;
	}
	
	// private function get_current_voucher(){
		// return (array)$this->get_icp_max_voucher($this->get_project_id());
	// }
// 	
	// private function get_current_financial_report(){
		// return (array)$this->get_max_report_submitted($this->get_project_id());
	// }
	
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
		}else{
			$params['start_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
			$params['end_date'] = strtotime(date("Y-m-d",strtotime("first day of next month",$current_report_date)));
		}
		
		return $params;
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
