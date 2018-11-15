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
	private function get_month_transactions()
    {		
		return $this->basic_model->get_journal_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
	
	private function get_approved_budget_spread(){
		return $this->basic_model->get_approved_budget_spread($this->icpNo,$this->get_current_fy());
	}
	
	private function get_fy_transactions(){
		return $this->basic_model->get_journal_transactions($this->icpNo,$this->get_fy_start_date(),$this->end_date);;
	}
		
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
	
	 function get_statementbal(){
		return $this->basic_model->get_statementbal($this->get_project_id(),date("Y-m-d",strtotime("last day of this month",$this->get_start_date_epoch())));
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
	
	protected function get_trackable_expenses(){
		return $this->basic_model->get_trackable_expenses($this->get_project_id(),$this->start_date,$this->end_date);
	}
	
	protected function group_single_transaction_by_vtype(){
		$singles = $this->get_outstanding_effects();
		
		$grouping = array();
		
		foreach($singles as $row){
			
			$grouping[$row['VType']][] = $row;
		}
		
		return $grouping; 
	}
	
	protected function group_cleared_effects_by_vtype(){
		$singles = $this->get_cleared_effects();
		
		$grouping = array();
		
		foreach($singles as $row){
			
			$grouping[$row['VType']][] = $row;
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
	
	private function get_statementbal_amount(){
		$statementbal = $this->get_statementbal();
		
		return empty($statementbal)? 0 : $statementbal->amount;
	}
	
	private function revenue_accounts(){
		return $this->basic_model->account_for_vouchers(1);
	}
	
	private function expense_accounts(){
		return $this->basic_model->account_for_vouchers(0);
	}
	
	private function budgeted_accounts(){
		$all_accounts = $this->revenue_accounts();
		
		$budgeted = array();
		
		foreach($all_accounts as $rows){
			if($rows->budget == 1){
				$budgeted[] = $rows;
			}
		}
		
		return $budgeted;
	}
	
	private function get_expense_accounts_by_accid(){
		$all_accounts = $this->expense_accounts();
		
		$grouped = array();
		
		foreach($all_accounts as $row){
			$grouped[$row->parentAccID][] = array("AccNo"=>$row->AccNo,"AccText"=>$row->AccText,"AccName"=>$row->AccName);
		}
		
		return $grouped;
	}
	
	 function month_transactions_by_accno(){
		$transactions = $this->get_month_transactions();
		
		$grouped = array();
		
		foreach($transactions as $rows){
			
			if($rows['AccGrp'] == 0){
				$grouped[$rows['AccNo']][] = $rows['Cost'];
			}
		}
		
		$summed_grouped = array();
		
		foreach($grouped as $key=>$value){
			$summed_grouped[$key] = array_sum($value);
		}
		
		return $summed_grouped;
	}
	 
	 function fy_transactions_by_accno(){
		$transactions = $this->get_fy_transactions();
		
		$grouped = array();
		
		foreach($transactions as $rows){
			
			if($rows['AccGrp'] == 0){
				$grouped[$rows['AccNo']][] = $rows['Cost'];
			}
		}
		
		$summed_grouped = array();
		
		foreach($grouped as $key=>$value){
			$summed_grouped[$key] = array_sum($value);
		}
		
		return $summed_grouped;
	} 
	 
	 /**
	  * A callback function to get_budget_to_date method
	  */
	 function calculate_budget_to_date($schedules_row){
	 	$month = $this->get_months_elapsed();
	 	$arr = array();
		
		foreach($schedules_row as $key=>$value){
			if($key <= $month){
				$arr[$key] = $value;
			}
		}
		
		return array_sum($arr);
	 }
	 
	 function get_budget_to_date(){
	 	return array_map(array($this,"calculate_budget_to_date"), $this->group_budget_spread_by_accno());
	 }
	 
	 function group_budget_spread_by_accno(){
	 	$spread = $this->get_approved_budget_spread();
		
		$grouped = array();
		
		foreach($spread as $row){
			for($i=1;$i<=12;$i++){
				$grouped[$row['AccNo']][$i][] =  $row['month_'.$i.'_amount'];
			}	
					
		}
		
		$summed = array();
		
		foreach($grouped as $key=>$value){
			foreach($value as $k=>$v){
				$summed[$key][$k] = array_sum($v);
			}
		}
		
		return $summed;
	 }
	 
	
	private function get_variancegrid($parentaccid){
		$grouped_expense_accounts = $this->get_expense_accounts_by_accid();
		$month_expenses = $this->month_transactions_by_accno();
		$fy_expenses = $this->fy_transactions_by_accno();
		$budget_to_date = $this->get_budget_to_date();
		
		$grid = array();
		
		foreach($grouped_expense_accounts[$parentaccid] as $row){
			$grid[$row['AccNo']]['account'] = $row;//fy_transactions_by_accno
			$grid[$row['AccNo']]['month_expenses'] = isset($month_expenses[$row['AccNo']])?$month_expenses[$row['AccNo']]:0;
			$grid[$row['AccNo']]['expenses_to_date'] = isset($fy_expenses[$row['AccNo']])?$fy_expenses[$row['AccNo']]:0;
			$grid[$row['AccNo']]['budget_to_date'] = isset($budget_to_date[$row['AccNo']])?$budget_to_date[$row['AccNo']]:0;
			$variance = $grid[$row['AccNo']]['budget_to_date'] - $grid[$row['AccNo']]['expenses_to_date'];
			$grid[$row['AccNo']]['variance'] = $variance;
			
			$per_variance = 0;
			
			if($grid[$row['AccNo']]['budget_to_date']!== 0){
				$per_variance = ($variance/$grid[$row['AccNo']]['budget_to_date'])*100;//number_format(($variance/$grid[$row['AccNo']]['budget_to_date'])*100,2);
			}elseif($grid[$row['AccNo']]['budget_to_date']== 0 && $variance!== 0){
				$per_variance = -100;
			}
			
			$grid[$row['AccNo']]['per_variance'] = $per_variance;
		}
		
		return $grid;
	}	

	private function get_tracked_expenses_breakdown(){
		return $this->basic_model->get_tracked_expenses_breakdown($this->CI->input->get("voucher"),
		$this->CI->input->get("scheduleID"));
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
		
		$data['month'] 					= $this->get_end_date();
		$data['statement_balance'] 		= $this->get_statementbal_amount();
		$data['outstanding_cheques'] 	= array_sum(array_column($this->outstanding_cheques(),"Cost"));
		$data['transit_deposit']		= array_sum(array_column($this->deposit_transit(),"Cost"));
		$data['journal_balance'] 		= $this->get_end_bank_balance();
		
		$data['trackable_expense'] = $this->get_trackable_expenses();
		
		$data['revenue_accounts'] = $this->budgeted_accounts();
		
		$data['view'] = "show_report";
		
		return $data;
	}
	
	protected function pre_render_show_fundbalance(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['fund_balances'] 		= $this->get_fund_balances();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_fundbalance";
		
		return $data;
	}	
	
	protected function pre_render_show_proofcash(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['bank_balance'] 		= $this->get_end_bank_balance();
		$data['petty_balance']		= $this->get_end_petty_balance();
		$data['sum_cash']			= $this->get_end_bank_balance() + $this->get_end_petty_balance();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_proofcash";
		
		return $data;
	}	
	
	protected function pre_render_show_outstandingcheques(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['oustanding_cheques'] = $this->outstanding_cheques();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_outstandingcheques";
		
		return $data;
	}	
	
	protected function pre_render_show_transitdeposit(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['deposit_transit'] 	= $this->deposit_transit();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_transitdeposit";
		
		return $data;
	}
	
	protected function pre_render_show_clearedcheques(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['cleared_cheques']	= $this->cleared_cheques();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_clearedcheques";
		
		return $data;
	}
	
	protected function pre_render_show_cleareddeposits(){
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['cleared_deposits']	= $this->cleared_deposit_transit();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_cleareddeposits";
		
		return $data;
	}

	protected function pre_render_show_bankreconcile(){
			$data['transacting_month'] 	= $this->get_transacting_month();
			
			$this->check_transacting_month();
			
			$data['month'] 					= $this->get_end_date();
			$data['statement_balance'] 		= $this->get_statementbal_amount();
			$data['outstanding_cheques'] 	= array_sum(array_column($this->outstanding_cheques(),"Cost"));
			$data['transit_deposit']		= array_sum(array_column($this->deposit_transit(),"Cost"));
			$data['journal_balance'] 		= $this->get_end_bank_balance();
			
			$this->load_alone = TRUE;
			
			$data['view'] = "show_bankreconcile";
			
			return $data;
	}
	
	protected function pre_render_ajax_variancereport(){
		$post_array = $this->CI->input->post();
		$grouped_expense_accounts = $this->get_expense_accounts_by_accid();
		$expenses = $this->month_transactions_by_accno();
		
		$data['transacting_month'] 	= $this->get_transacting_month();
		$data['expense_accounts'] = $grouped_expense_accounts[$post_array['accID']];
		$data['month_expenses'] = $this->month_transactions_by_accno();
		$data['variancegrid'] = $this->get_variancegrid($post_array['accID']);
		
		$data['view'] = "ajax_variancereport"; 
		
		return $data;
	}
	
	protected function pre_render_show_budgetvariance(){
			$this->check_transacting_month();
			$data['transacting_month'] 	= $this->get_transacting_month();
		
			$data['revenue_accounts'] = $this->budgeted_accounts();
			
			
			$this->load_alone = TRUE;
			$data['view'] = "show_budgetvariance";
			
			return $data;
	}	
	
	protected function pre_render_show_expensebreakdown(){
			$this->check_transacting_month();
			
			$data['trackable_expense'] = $this->get_trackable_expenses();
			
			$this->load_alone = TRUE;
			$data['view'] = "show_expensebreakdown";
			
			return $data;
	}	
	
	function pre_render_add_expensebreakdown(){
		$this->has_sidebar = false;
		
		$data['expense_breakdown'] = $this->get_tracked_expenses_breakdown();
		$data['scheduleID'] = $this->CI->input->get("scheduleID");
		$data['budgetItem'] = $this->CI->input->get("budgetItem");
		$data['voucher'] = $this->CI->input->get("voucher");
		$data['view'] = 'add_expensebreakdown';
		
		return $data;
	}

	function pre_render_ajax_post_expensebreakdown(){
		//echo "Posting Successful";
		echo ($this->basic_model->post_expense_breakdown($this->CI->input->post()))?$this->l('record_created'):$this->l('error_occurred');
		//print_r($this->CI->input->post());
	}
	
	function pre_render_ajax_update_expensebreakdown(){
		//echo "Posting Successful";
		echo ($this->basic_model->post_expense_breakdown($this->CI->input->post(),"update"))?$this->l('record_created'):$this->l('error_occurred');
		//print_r($this->CI->input->post());
	}
}
