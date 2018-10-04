<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Init.php";

 
final class Report extends Layout implements Init{
	/**
 * All modules should have the construct setting the initialize_entry method
 * The initialize entry method initializes the model and set the initial uri
 * segments sections
 */	
	
	function __construct(){
		parent::__construct();
		
		$this->initilize_entry();
	}
	
	public function initilize_entry(){
		
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
	
	/** Develop the Fund Balance Report - Start**/
		
	private function get_income_accounts(){
		return $this->basic_model->account_for_vouchers(1);
	}
	
	private function get_last_month_fund_balance(){
		return $this->basic_model->get_month_fund_balance($this->get_project_id(),
		date('Y-m-d',strtotime('last day of previous month',$this->get_start_date_epoch())));
	}
	
	private function get_last_month_balance_grouped_by_account(){
		$opening_balance = $this->get_last_month_fund_balance();
		
		$grouped_open_balance = array();
		
		foreach($opening_balance as $row){
			$grouped_open_balance[$row->AccNo] = $row->amount;
		}
		
		return $grouped_open_balance;
	}
	
	 function get_months_sum_per_account(){
		return $this->basic_model->get_months_sum_per_account($this->get_project_id(),
		$this->get_start_date(),$this->get_end_date());
	}
	
	private function get_grouped_transacted_expense_accounts(){
		$all_transacted_accounts = $this->get_months_sum_per_account();
		$income_accounts = $this->get_income_accounts();
		
		$income_accounts_with_accid_key = array();
		
		foreach($income_accounts as $row){
			$income_accounts_with_accid_key[$row->accID] = $row->AccNo;
		}
		
		$grouped_by_parent_id = array();
		
		$cost = 0;
		foreach($all_transacted_accounts as $row){
			if($row->AccGrp == 0){
				$grouped_by_parent_id[$income_accounts_with_accid_key[$row->parentAccID]][] = $row->Cost;
			}
		}
		
		return $grouped_by_parent_id;
	}
	
	private function get_accounts_with_month_income(){
		$accounts_with_transaction = $this->get_months_sum_per_account();
		
		$has_income = array();
		
		foreach($accounts_with_transaction as $row){
			if($row->AccGrp == 1){
				$has_income[$row->AccNo] = $row->Cost;
			}
			
		}
		
		return $has_income;
	}
	
	
	private function get_fund_balances(){
		$income_accounts = $this->get_income_accounts();
		$grouped_open_balance = $this->get_last_month_balance_grouped_by_account();
		$grouped_income = $this->get_accounts_with_month_income();
		$grouped_expense = $this->get_grouped_transacted_expense_accounts();
		
		$grouped_income_accounts = array();
		
		foreach($income_accounts as $row){
			
			$grouped_income_accounts[$row->AccNo] = array("AccText"=>$row->AccText,"AccName"=>$row->AccName);
			$grouped_income_accounts[$row->AccNo]['Opening'] = isset($grouped_open_balance[$row->AccNo])?$grouped_open_balance[$row->AccNo]:0; 
			$grouped_income_accounts[$row->AccNo]['Income']  = isset($grouped_income[$row->AccNo])?$grouped_income[$row->AccNo]:0;
			$grouped_income_accounts[$row->AccNo]['Expense']  = isset($grouped_expense[$row->AccNo])?array_sum($grouped_expense[$row->AccNo]):0;
			$grouped_income_accounts[$row->AccNo]['Ending'] = 	$grouped_income_accounts[$row->AccNo]['Opening'] + 	
																$grouped_income_accounts[$row->AccNo]['Income']  - 	
																$grouped_income_accounts[$row->AccNo]['Expense'];
		}
		
		foreach($grouped_income_accounts as $key=>$value){
			if($value['Opening'] == 0 && $value['Income'] == 0 && $value['Expense'] == 0){
				unset($grouped_income_accounts[$key]);
			}
		}
		
		return $grouped_income_accounts;
	}

	/** Develop the Fund Balance Report - End **/
	
	 function get_months_sum_per_vtype(){
		return $this->basic_model->get_months_sum_per_vtype($this->get_project_id(),
		$this->get_start_date(),$this->get_end_date());
	}
	
	protected function get_special_accounts_sum(){
		return $this->basic_model->get_special_accounts_sum($this->icpNo,$this->start_date,$this->end_date);
	}
	
	protected function group_special_accounts_transactions(){
		$special_accounts = $this->get_special_accounts_sum();
		
		$grouping = array();
		
		foreach($special_accounts as $row){
			$grouping[$row->AccGrp][] = $row->Cost;
		}
		
		return $grouping;
	}
	
	protected function get_start_cash_balances(){
		return $this->basic_model->start_cash_balance($this->icpNo,$this->start_date);
	}
	
	protected function total_cash_received(){
		return array_sum($this->get_accounts_with_month_income());
	}
	
	protected function months_sum_transaction_grouped_by_vtype(){
		$sum_accounts = $this->get_months_sum_per_vtype();
		
		$grouping = array();
		
		foreach($sum_accounts as $row){
			$grouping[$row->VType] = $row->Cost; 
		}
		
		return $grouping;
	}
	
	protected function get_end_bank_balance(){
		$start_bal = $this->get_start_cash_balances();
		$special_accounts_transaction = $this->group_special_accounts_transactions();
		$months_sum_accounts_grouped_by_vtype = $this->months_sum_transaction_grouped_by_vtype();
		
		$open_bank = $start_bal['BC']['amount'];
		$cash_received = $this->total_cash_received();
		$pc_rebanked = isset($special_accounts_transaction['4'])?array_sum($special_accounts_transaction['4']):0;
		$pay_by_chq = isset($months_sum_accounts_grouped_by_vtype["CHQ"])?
							$months_sum_accounts_grouped_by_vtype["CHQ"]:0;
		$bank_charge = isset($months_sum_accounts_grouped_by_vtype["BCHG"])?
							$months_sum_accounts_grouped_by_vtype["BCHG"]:0;
							
		return 	($open_bank+$cash_received+$pc_rebanked)-($pay_by_chq+$bank_charge);				
	}
	
	protected function get_end_petty_balance(){
		$start_bal = $this->get_start_cash_balances();
		$special_accounts_transaction = $this->group_special_accounts_transactions();
		$months_sum_accounts_grouped_by_vtype = $this->months_sum_transaction_grouped_by_vtype();
		
		$open_pc = $start_bal['PC']['amount'];
		$pc_deposit = isset($special_accounts_transaction['3'])?array_sum($special_accounts_transaction['3']):0;
		$pc_payment = isset($months_sum_accounts_grouped_by_vtype["PC"])?
							$months_sum_accounts_grouped_by_vtype["PC"]:0;
		$pc_rebanked = isset($special_accounts_transaction['4'])?array_sum($special_accounts_transaction['4']):0;
		
		return 	($open_pc+$pc_deposit)-($pc_payment+$pc_rebanked);				
	}
	
	
	protected function pre_render_show_report(){
		$data['fund_balances'] 	= $this->get_fund_balances();
		$data['bank_balance'] 	= $this->get_end_bank_balance();
		$data['petty_balance']	= $this->get_end_petty_balance();
		$data['sum_cash']		= $this->get_end_bank_balance() + $this->get_end_petty_balance();
		
		$data['view'] = "show_report";
		
		return $data;
	}
}
