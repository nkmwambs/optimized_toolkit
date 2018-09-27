<?php

/************************************************************************************************************
 * Journal Package
 * 
 * This is a Codeigniter Library developed by Compassion Africa GTS to enable portability of the Frontline
 * Church Partner Finance Application across Codeigniter Installations.
 * 
 * This Package comes with 1 Library  (Journal Library), 1 Model (Journal Model), Asset Directory
 * (ifms_assets) and A Config file (ifms).
 * 
 * This software is only developed for use within Compassion Assisted Churches and authorization from the
 * Compassion would be required to extend or use this piece of software anywhere else.
 * 
 * @package	Journal
 * @author	Compassion Africa
 * @copyright Copyright (c) 2018 , Compassion International Inc. (https://www.compassion.com/)
 * @link	https://www.compassion.com
 * @since	Version 2.0.0
 * @filesource
 * 
 * **********************************************************************************************************/

 defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Journal_Layout Class
 *
 * This class contains functions that sets the final view output object.
 *
 * @package		Journal 
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Compassion International Inc
 * @link		https://www.compassion.com/
 */

include_once "Layout.php";
include "Initialization.php";

 
final class Journal extends Layout implements Initialization{
	protected $icpNo;
	protected $start_date;
	protected $end_date;
	protected $opening_bank;
	protected $opening_petty;
	protected $start_bank;
	protected $start_petty;
	private $pre_render;	

/**
 * All modules should have the construct setting the initialize_entry method
 * The initialize entry method initializes the model and set the initial uri
 * segments sections
 */	
	
	function __construct(){
		parent::__construct();
		
		$this->initilize_entry();
	}
	
	function initilize_entry(){
		
		/** Load the Model - To be place in all Modules**/
		$this->CI->load->model("Finance_model");
		$this->basic_model 	= new Finance_model();
		
		/**Initialization of url segments **/
		if(substr($this->get_view(),0,4) !== "ajax"){
			$transaction_month = $this->basic_model->get_transacting_month($this->CI->uri->segment(4));
			$this->icpNo = $this->CI->uri->segment(4);
			$this->start_date 	= date("Y-m-d",$transaction_month['start_date']);
			$this->end_date  	= date("Y-m-d",$transaction_month['end_date']);
		}else{
			$this->echo_and_die = true;
		}
	}
	
		
	/** Start of Model Wrappers **/
	
	private function _get_banks(){
		return $this->basic_model->get_banks();
	}
	
	private function opening_cash_balance(){
		return $this->basic_model->start_cash_balance($this->icpNo,$this->start_date);
	}
	
	private function get_transacting_month(){
		return $this->basic_model->get_transacting_month($this->CI->uri->segment(4));
	}	
	
    private function get_current_month_transactions()
    {		
		return $this->basic_model
		->get_journal_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
	
	private function account_for_vouchers(){
		return $this->basic_model->account_for_vouchers();
	}
	
	private function get_current_approved_budget(){
		return $this->basic_model->get_current_approved_budget($this->get_project_id(),$this->get_current_fy());
	}
	
	
	private function get_civs(){
		return $this->basic_model->get_civs();
	}	
	
	private function get_next_voucher_number(){
		return $this->basic_model->get_next_voucher_number($this->get_project_id());
	}
	
	private function get_voucher_date_picker_control(){
		return $this->basic_model->get_voucher_date_picker_control($this->get_project_id());
	}
	
	private function get_coded_cheques_utilized(){
		return $this->basic_model->get_cheques_utilized_with_bank_code($this->get_project_id());
	}
	
	private function get_project_details(){
		return $this->basic_model->get_project_details($this->get_project_id());
	}
	
	private function insert_voucher_to_database($post_array=array()){
		return $this->basic_model->insert_voucher_to_database($post_array);
	}
	
    private function get_current_month_transactions_for_voucher()
    {	
		return $this->basic_model
		->get_voucher_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
		
	/** End of Model Wrappers **/		

	
	protected function budget_grouped_items(){
		$raw_budget = $this->get_current_approved_budget();
		
		$account_groups = array();
		
		foreach($raw_budget as $item){
			$account_groups[$item->AccNo][] = $item;
		}
		
		return $account_groups;
	}
	
	private function current_voucher_date(){
		return $this->basic_model->get_current_voucher_date($this->get_project_id());
	}
	
	protected function accounts_with_open_icp_civs(){
		$raw_accounts = $this->account_for_vouchers();
		$open_civs  = $this->get_civs();
		
		$combined_account_civ_array = array();
		
		foreach($raw_accounts as $account){
			foreach($open_civs as $civ){
				if($account->accID == $civ->accID){
					$icps_impacted = explode(",", $civ->allocate);
					
					// $control_dates = $this->get_voucher_date_picker_control();
					// if(in_array($this->get_project_id(), $icps_impacted) && strtotime($civ->closureDate) > $control_dates['end_date']){
						// $combined_account_civ_array[$account->AccNo][$civ->AccNoCIVA] = array("AccNo"=>$account->AccNo,"civaCode"=>$civ->civaID,"AccText"=>$civ->AccNoCIVA,"AccName"=>"(".$account->AccText.") ".$account->AccName,"closureDate"=>$civ->closureDate,"allocate"=>explode(",", $civ->allocate));	
					// }
					if(in_array($this->get_project_id(), $icps_impacted) && $civ->closureDate > $this->current_voucher_date()){
						$combined_account_civ_array[$account->AccNo][$civ->AccNoCIVA] = array("AccNo"=>$account->AccNo,"civaCode"=>$civ->civaID,"AccText"=>$civ->AccNoCIVA,"AccName"=>"(".$account->AccText.") ".$account->AccName,"closureDate"=>$civ->closureDate,"allocate"=>explode(",", $civ->allocate));	
					}
					
				}
			}
		}
		
		return $combined_account_civ_array;
	}

	protected function group_accounts(){
		$raw_accounts = $this->account_for_vouchers();
		$accounts_with_civs = $this->accounts_with_open_icp_civs();
		
		$AccGrps = array("expense","revenue","bankbalance","pcdeposits","rebanking");
		
		$grouped = array();
		
		foreach($raw_accounts as $account){
			if($account->Active == 1){
				$grouped[$AccGrps[$account->AccGrp]][] = $account;
			}else{
				if(isset($accounts_with_civs[$account->AccNo])){
					foreach($accounts_with_civs[$account->AccNo] as $civ_account){
						$grouped[$AccGrps[$account->AccGrp]][] = (object)$civ_account;
					}
				}
				
			}
			
		}
		
		return $grouped;
	}
	

	private function get_start_bank(){
		$balance = $this->opening_cash_balance();
		return $this->opening_bank = $balance['BC']['amount'];
	}
	
	private function get_start_petty(){
		$balance = $this->opening_cash_balance();
		return $this->opening_petty = $balance['PC']['amount'];
	}

	private function get_bank_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_bank = $balance['BC']['amount'];
	}
	
	private function get_petty_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_petty = $balance['PC']['amount'];
	}
		
	private function transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions();
		
		foreach($all_transactions as $rows){

			$removeKeys = array("AccNo","AccText","AccGrp","Cost");
			$transactions_container[$rows['VNumber']]['details'] = array_diff_key($rows, array_flip($removeKeys));
			
			$transactions_container[$rows['VNumber']][$rows['AccGrp']][$rows['AccNo']] = $rows['Cost'];
			
		}
		
		return $transactions_container;
	}
	

	 function voucher_transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions_for_voucher();
		
		$cnt = 0;
		
		foreach($all_transactions as $rows){
			$removeKeys = array("AccNo","AccText","AccGrp","Qty","Details","UnitCost","Cost","scheduleID","civaCode");
			$transactions_container[$rows['VNumber']]['details'] = array_diff_key($rows, array_flip($removeKeys));
			
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Qty'] = $rows['Qty'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Details'] = $rows['Details'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['UnitCost'] = $rows['UnitCost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Cost'] = $rows['Cost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['AccNo'] = $rows['AccText'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['scheduleID'] = $rows['scheduleID'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['civaCode'] = $rows['civaCode'];
			$cnt ++;
		}
		
		return $transactions_container;
	}
		
	private function get_utilized_accounts(){
		/** Retrieve Income Accounts **/
		$income_accounts_rows = array_column($this->transactions(), 1);
		$income_accounts_unsort = array();
		foreach($income_accounts_rows as $value){
			foreach($value as $row_key=>$row_value){
				$income_accounts_unsort[] = $row_key;
			}
		}
		
		$income_accounts = array_unique($income_accounts_unsort);
		sort($income_accounts);
		
		/** Retrieve Expense Accounts **/
		$expense_accounts_rows = array_column($this->transactions(), 0);
		$expense_accounts_unsort = array();
		foreach($expense_accounts_rows as $value){
			foreach($value as $row_key=>$row_value){
				$expense_accounts_unsort[] = $row_key;
			}
		}
		
		$expense_accounts = array_unique($expense_accounts_unsort);
		sort($expense_accounts);
		
		/** All accounts **/
		//$all_utilized_accounts = array_merge($income_accounts,$expense_accounts);
		$all_utilized_accounts = array("1"=>$income_accounts,"0"=>$expense_accounts);
		
		return $all_utilized_accounts;
	}
	
	function linear_accounts_utilized(){
		$all_transactions = $this->get_current_month_transactions();
		
		$linear_accounts_text = array();
		
		foreach($all_transactions as $row){
			$linear_accounts_text[$row['AccNo']] = $row['AccText']; 
		}
		
		return $linear_accounts_text;
	}
	
	private function construct_journal(){
		$this->get_start_bank();
		$this->get_start_petty();
		$accounts = $this->get_utilized_accounts();
		$transactions =  $this->transactions();
		$records = array();
		$i = 0;
		foreach($transactions as $value){
			$records[$i]['details'] = $value['details'];
			if(isset($value[1])){
				
				foreach($accounts[1] as $account){
					if(isset($value[1][$account])){
						$records[$i]['income_spread'][$account] = $value[1][$account];
					}else{
						$records[$i]['income_spread'][$account] = 0;
					}
					
				}
				
				/** Bnak and Cash Columns **/
				
				$records[$i]['bank']['bank_inc'] = 0;
				$records[$i]['bank']['bank_exp'] = 0;
				$records[$i]['bank']['bank_bal'] = 0;
				
				$records[$i]['petty']['petty_inc'] = 0;
				$records[$i]['petty']['petty_exp'] = 0;
				$records[$i]['petty']['petty_bal'] = 0;
				
				if($value['details']['VType'] == 'CR'){
					$records[$i]['bank']['bank_inc'] = array_sum($value[1]);	
					$this->opening_bank+=array_sum($value[1]);
				}
				
				$records[$i]['bank']['bank_bal'] = $this->opening_bank; 
				$records[$i]['petty']['petty_bal'] = $this->opening_petty; 
				
				
				/**Append Expense accounts **/
				foreach($accounts[0] as $account){ $records[$i]['expense_spread'][$account] = 0;}
				
				
			}else{
				/**Append Income accounts **/	
				foreach($accounts[1] as $account){ $records[$i]['income_spread'][$account] = 0;}
				
				/** Bnak and Cash Columns **/
				$records[$i]['bank']['bank_inc'] = 0;
				$records[$i]['bank']['bank_exp'] = 0;
				$records[$i]['bank']['bank_bal'] = 0;
				
				$records[$i]['petty']['petty_inc'] = 0;
				$records[$i]['petty']['petty_exp'] = 0;
				$records[$i]['petty']['petty_bal'] = 0;
				
				if(($value['details']['VType'] == 'CHQ' || $value['details']['VType'] == 'BCHG') && isset($value[0])){
					$records[$i]['bank']['bank_exp'] = array_sum($value[0]);
					$this->opening_bank-=array_sum($value[0]);
					
				}elseif($value['details']['VType'] == 'CHQ' && isset($value[3])){
						
					$records[$i]['bank']['bank_exp'] = array_sum($value[3]);	
					$records[$i]['petty']['petty_inc'] = array_sum($value[3]);	
					
					$this->opening_bank-=array_sum($value[3]);
					$this->opening_petty+=array_sum($value[3]);
					
				}elseif($value['details']['VType'] == 'PC'){
					$records[$i]['petty']['petty_exp'] = array_sum($value[0]);
					$this->opening_petty-=array_sum($value[0]);
					
				}elseif($value['details']['VType'] == 'PCR' && isset($value[3])){
					$records[$i]['petty']['petty_exp'] = array_sum($value[4]);
					$this->opening_petty-=array_sum($value[4]);
				}
				
				$records[$i]['bank']['bank_bal'] = $this->opening_bank; 
				$records[$i]['petty']['petty_bal'] = $this->opening_petty; 
				
				
				foreach($accounts[0] as $account){
					if(isset($value[0][$account])){
						$records[$i]['expense_spread'][$account] = $value[0][$account];
					}else{
						$records[$i]['expense_spread'][$account] = 0;
					}
					
				}
			}
			
			$i++;
		}
		
		return $records;
	}

	/** Cash Journal Getters (Used in Journal Related Pre-renders) - Start**/

	private function get_bank_deposit(){
		$bank = 0;	
		foreach($this->construct_journal() as $row){
			$bank+=$row['bank']['bank_inc'];
		}
		
		return $bank;
	}

	private function get_bank_payment(){
		$bank = 0;	
		foreach($this->construct_journal() as $row){
			$bank+=$row['bank']['bank_exp'];
		}
		
		return $bank;
	}	


	private function get_cash_deposit(){
		$cash= 0;	
		foreach($this->construct_journal() as $row){
			$cash+=$row['petty']['petty_inc'];
		}
		
		return $cash;
	}

	private function get_cash_payment(){
		$cash = 0;	
		foreach($this->construct_journal() as $row){
			$cash+=$row['petty']['petty_exp'];
		}
		
		return $cash;
	}

	
	
	private function readable_labels(){
		
		return $labels = array(
			"VType"=>$this->l("voucher_type"),
			"TDate"=>$this->l('date'),
			
			"VNumber"=>$this->l("voucher_number"),
			"Payee"=>$this->l("payee"),
			"Address"=>$this->l("address"),
			"TDescription"=>$this->l("details"),
			"bank_inc"=>$this->l("bank_deposits"),
			"bank_exp"=>$this->l("bank_payments"),
			"bank_bal"=>$this->l("bank_balance"),
			"petty_inc"=>$this->l("cash_deposits"),
			"petty_exp"=>$this->l("cash_payments"),
			"petty_bal"=>$this->l("cash_balance"),
			"ChqNo"=>$this->l("cheque_number")
		);
		
		
	}	

	
	/**
	 * Pre-render methods:
	 * These are private methods that set the variables to be used in a particular view i.e.
	 * - pre_render_journal
	 * - pre_render_view_voucher
	 * - pre_render_print_voucher
	 * - pre_render_create_voucher
	 */
	
	protected function pre_render_show_journal(){
		
		if(count($this->construct_journal())>0){
			$data['records'] =  $this->construct_journal();
		
			$data['end_bank_balance'] = $this->opening_bank;
			$data['end_petty_balance'] = $this->opening_petty;
			 		
			$data['opening_bank_balance'] = $this->get_bank_opening_balance();
			$data['opening_petty_balance'] = $this->get_petty_opening_balance();
	 		
			$data['total_bank_deposit'] = $this->get_bank_deposit();
			$data['total_bank_payment'] = $this->get_bank_payment();
	 		
			$data['total_cash_deposit'] = $this->get_cash_deposit();
			$data['total_cash_payment'] = $this->get_cash_payment();
	 		
			$data['labels'] = $this->readable_labels();
	 		
			$data['all_accounts_labels'] = $this->linear_accounts_utilized();
			
			$data['transacting_month'] = $this->get_transacting_month();
			
			$data['view'] = $this->get_view();
			
		}else{
			$data['view'] = "error";
		}
		
 		
 		return $data;
		
	}
	
	protected function pre_render_show_voucher(){
		
		if($this->CI->uri->segment(8)){
			$vouchers = $this->voucher_transactions();		
			$data['voucher'] = $vouchers[$this->CI->uri->segment(8)];
		}
		
		$data['view'] = $this->CI->uri->segment(8)?$this->get_view():"error";
		
		return $data;
	}

	protected function pre_render_print_vouchers(){
		
		if($this->CI->input->post()){
			$data['selected_vouchers'] = $this->CI->input->post();
			$data['all_vouchers'] = $this->voucher_transactions();	
			$view = $this->get_view();
		}
		
		$data['view'] = $this->CI->input->post()?$this->get_view():"error";
		
		return $data;
	}
	
	protected function pre_render_create_voucher(){
		
		$data['success'] = "";
		if(isset($_POST) && sizeof($_POST)>0){
			$data['success'] = $this->insert_voucher_to_database($_POST);
		}
		
		$data['accounts'] = $this->group_accounts();
		$data['approved_budget'] = $this->budget_grouped_items();
		$data['voucher_number'] = $this->get_next_voucher_number();
		$data['voucher_date_range'] = $this->get_voucher_date_picker_control();
		$data['cheques_utilized'] = $this->get_coded_cheques_utilized();
		$project_details = $this->get_project_details();
		$data['bank_code'] = $project_details->bankID;
		$data['civ_accounts'] = $this->accounts_with_open_icp_civs();// To be removed
		
		$data['view'] = $this->get_view();
		
		return $data;
	}

	protected function pre_render_cheque_book(){
		
		$data['success'] = "";
		if(isset($_POST) && sizeof($_POST)>0){
			$data['success'] = $this->basic_model->insert_cheque_booklet($_POST);
		}
		
		$data['view'] = 'cheque_book';	
		$data['voucher_date'] = $this->get_voucher_date_picker_control();
		$data['banks'] = $this->_get_banks();
		$project_details = $this->get_project_details();
		$data['project_bank_id'] = $project_details->bankID;
		return $data;
	}

	protected function pre_render_ajax_get_cheque_details(){
		
		echo json_encode($this->basic_model->get_cheque_details($_POST['icpNo'],$_POST['ChqNo']));
				
	}

}

