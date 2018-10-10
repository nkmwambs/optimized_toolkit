<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Initialization.php";

 
final class Report extends Layout implements Initialization{
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
		$this->asset_view_group = get_class();
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
	
	/** Develop the Cash Balance Table - Start **/
	
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
	
	protected function start_cash_balance(){
		return $this->basic_model->start_cash_balance($this->icpNo,$this->start_date);
	}
	
	private function opening_cash_balance(){
		$last_month_cash_balance = $this->start_cash_balance();
		
		if(count($last_month_cash_balance) == 0){
			$last_month_cash_balance = array(
				array('accNo'=>'PC',"amount"=>0),
				array('accNo'=>'BC',"amount"=>0)
			);
		}
		
		$cash_balance_columns = array_column($last_month_cash_balance, "accNo");
		return array_combine($cash_balance_columns, $last_month_cash_balance);
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
		$start_bal = $this->opening_cash_balance();
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
		$start_bal = $this->opening_cash_balance();
		$special_accounts_transaction = $this->group_special_accounts_transactions();
		$months_sum_accounts_grouped_by_vtype = $this->months_sum_transaction_grouped_by_vtype();
		
		$open_pc = $start_bal['PC']['amount'];
		$pc_deposit = isset($special_accounts_transaction['3'])?array_sum($special_accounts_transaction['3']):0;
		$pc_payment = isset($months_sum_accounts_grouped_by_vtype["PC"])?
							$months_sum_accounts_grouped_by_vtype["PC"]:0;
		$pc_rebanked = isset($special_accounts_transaction['4'])?array_sum($special_accounts_transaction['4']):0;
		
		return 	($open_pc+$pc_deposit)-($pc_payment+$pc_rebanked);				
	}
	
	/** Develop the Cash Balance Table - End **/
	
	protected function get_outstanding_effects(){
		return $this->basic_model->get_outstanding_effects($this->icpNo,$this->start_date,$this->end_date);
	}
	
	protected function get_cleared_effects(){
		return $this->basic_model->get_cleared_effects($this->icpNo,$this->start_date,$this->end_date);
	}
	
	protected function group_single_transaction_by_vtype(){
		$singles = $this->get_outstanding_effects();
		
		$grouping = array();
		
		foreach($singles as $row){
			
			$grouping[$row->VType][] = $row;
		}
		
		return $grouping; 
	}
	
	protected function group_cleared_effects_by_vtype(){
		$singles = $this->get_cleared_effects();
		
		$grouping = array();
		
		foreach($singles as $row){
			
			$grouping[$row->VType][] = $row;
		}
		
		return $grouping; 
	}
	
	protected function outstanding_cheques(){
		$grouped = $this->group_single_transaction_by_vtype();
		
		return isset($grouped['CHQ'])?$grouped['CHQ']:array();
	}
	
	protected function cleared_cheques(){
		$grouped = $this->group_cleared_effects_by_vtype();
		
		return isset($grouped['CHQ'])?$grouped['CHQ']:array();
	}
	
	protected function deposit_transit(){
		$grouped = $this->group_single_transaction_by_vtype();
		
		return isset($grouped['CR'])?$grouped['CR']:array();
	}
	
	protected function cleared_deposit_transit(){
		$grouped = $this->group_cleared_effects_by_vtype();
		
		return isset($grouped['CR'])?$grouped['CR']:array();
	}
	
	protected function pre_render_show_report(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['fund_balances'] 		= $this->get_fund_balances();
		
		$data['bank_balance'] 		= $this->get_end_bank_balance();
		$data['petty_balance']		= $this->get_end_petty_balance();
		$data['sum_cash']			= $this->get_end_bank_balance() + $this->get_end_petty_balance();
		
		$data['oustanding_cheques'] = $this->outstanding_cheques();
		$data['cleared_cheques']	= $this->cleared_cheques();
		$data['deposit_transit'] 	= $this->deposit_transit();
		$data['cleared_deposits']	= $this->cleared_deposit_transit();
		
		$data['view'] = "show_report";
		
		return $data;
	}
}
