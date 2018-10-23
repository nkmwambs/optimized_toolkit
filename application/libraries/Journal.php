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
	protected $end_bank;
	protected $end_petty;
	protected $start_bank;
	protected $start_petty;
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
		$this->asset_view_group = get_class();
	}
	
	/** Start of Model Wrapper methods**/
	
	private function _get_banks(){
		return $this->basic_model->get_banks();
	}
	
	function start_cash_balance(){
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
	
	/**
	 * This method returns all voucher transactions in the selected month.
	 */	
    private function get_current_month_transactions($params = array())
    {
    	//$params = array('start_date'=>"",'end_date'=>"",'voucher'=>"")	
    	
    	extract($params);
    	
    	if(!isset($start_date) || ($start_date > $end_date)) $start_date = $this->start_date;		
		if(!isset($end_date) || ($start_date > $end_date)) $end_date = $this->end_date;	
		if(!isset($voucher)) $voucher = null;	
		
		return $this->basic_model->get_journal_transactions($this->icpNo,$start_date,$end_date,$voucher);
    }
  	
	/**
	 * This method groups the results of the get_current_month_transactions method by Voucher Number. Each voucher number row
	 * has a details key holding the VType,TDate,VNumber,Payee,ChqNo and TDescription columns. Voucher number rows has the transaction costs
	 * grouped with the AccGrp as keys; the values being AccNo => Cost
	 * 
	 * Account Type		 		AccGrp
	 * --------------------------------
	 * Expense Accounts			0
	 * Income Accounts			1
	 * Petty Cash Deposit		3
	 * Petty Cash Rebanking		4
	 */
    
	private function get_journal_entries(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions();
		
		foreach($all_transactions as $rows){
			/**Columns to be removed in the details element value**/
			$removeKeys = array("AccNo","AccText","AccGrp","Cost","scheduleID","civaCode","Qty","Details","UnitCost");
			$transactions_container[$rows['VNumber']]['details'] = array_diff_key($rows, array_flip($removeKeys));
			
			$transactions_container[$rows['VNumber']][$rows['AccGrp']][$rows['AccNo']] = $rows['Cost'];
			
		}
		
		return $transactions_container;
	}
	
	/**
	 * Creates an array of accounts that have been transacted in the month gropued by their AccGrp value
	 */
	
	private function get_utilized_accounts(){
		/** Retrieve Income Accounts **/
		$income_accounts_rows = array_column($this->get_journal_entries(), 1);
		$income_accounts_unsort = array();
		
		foreach($income_accounts_rows as $value){
			$income_accounts_unsort = array_merge($income_accounts_unsort,array_keys($value));
		}
		
		$income_accounts = array_unique($income_accounts_unsort);
		sort($income_accounts);
		
		/** Retrieve Expense Accounts **/
		$expense_accounts_rows = array_column($this->get_journal_entries(), 0);
		$expense_accounts_unsort = array();
		foreach($expense_accounts_rows as $value){
			$expense_accounts_unsort = array_merge($expense_accounts_unsort,array_keys($value));
		}
		
		$expense_accounts = array_unique($expense_accounts_unsort);
		sort($expense_accounts);
		
		/** All accounts **/

		$all_utilized_accounts = array("1"=>$income_accounts,"0"=>$expense_accounts);
		
		return $all_utilized_accounts;
	}
	
	// private function get_utilized_accounts(){
            /** Retrieve Income Accounts **/
            /*$income_accounts_rows = array_column($this->get_journal_entries(), 1);
            $income_accounts_unsort = array();
            
            foreach($income_accounts_rows as $value){
                  $income_accounts_unsort = array_merge($income_accounts_unsort,array_keys($value));
            }
            
            $income_accounts = array_unique($income_accounts_unsort);
            sort($income_accounts);*/
            
            /** Retrieve Expense Accounts **/
            /*$expense_accounts_rows = array_column($this->get_journal_entries(), 0);
            $expense_accounts_unsort = array();
            foreach($expense_accounts_rows as $value){
                  $expense_accounts_unsort = array_merge($expense_accounts_unsort,array_keys($value));
            }
            
            $expense_accounts = array_unique($expense_accounts_unsort);
            sort($expense_accounts);*/
            
            /** All accounts **/
        	// $income_accounts=$this->pull_up_utilized_accounts(1);
            // $expense_accounts=$this->pull_up_utilized_accounts(0);
            // $all_utilized_accounts = array("1"=>$income_accounts,"0"=>$expense_accounts);
//             
            // return $all_utilized_accounts;
      // }
      //Buddling this
	  // private function pull_up_utilized_accounts($column_name)
	  // {
	        // $income_and_expense_accounts= array_column($this->get_journal_entries(), $column_name);
	            // $unsorted_and_income_accounts= array();
// 	            
	            // foreach($income_and_expense_accounts as $value)
	            // {
	                  // $unsorted_and_income_accounts = array_merge($unsorted_and_income_accounts,array_keys($value));
	            // }
// 	            
	            // $pulled_up_incomeaccounts = array_unique($unsorted_and_income_accounts);
	            // return sort($pulled_up_incomeaccounts);
	  // }
      /**
      * Creates an ungrouped array of all accounts that were trnsacted in the month
      */
	
	
	/**
	 * Creates an ungrouped array of all accounts that were trnsacted in the month
	 */
	
	private function linear_accounts_utilized(){
		$all_transactions = $this->get_current_month_transactions();
		
		$linear_accounts_text = array();
		
		foreach($all_transactions as $row){
			$linear_accounts_text[$row['AccNo']] = $row['AccText']; 
		}
		
		return $linear_accounts_text;
	}

	 function voucher_transactions($voucher=""){
		$transactions_container = array();
		$all_transactions = $voucher==""? $this->get_current_month_transactions(): $this->get_current_month_transactions(array("voucher"=>$voucher));
		
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
	
	private function change_cheque_booklet_status($icpNo){
		$remaining_leaves = $this->get_unused_cheque_leaves();
		$msg = "";
		
		if(count($remaining_leaves) == 0){
			if($this->basic_model->change_cheque_booklet_status($icpNo))
				$msg =  " ".$this->l('cheque_booklet_exhausted');
		}
		
		return $msg;
	}
	
	private function insert_voucher_to_database($post_array=array()){
		
		$msg = $this->l('vouching_failure');
		
		if($this->basic_model->insert_voucher_to_database($post_array)){
			$msg = $this->l('vouching_success');
			if($post_array['VType'] == "CHQ"){
				$msg .= $this->change_cheque_booklet_status($post_array['icpNo']);
			}
			
			
		}
		
		return $msg;
	}
	
	private function current_voucher_date(){
		return $this->basic_model->get_current_voucher_date($this->get_project_id());
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
	
	protected function accounts_with_open_icp_civs(){
		$raw_accounts = $this->account_for_vouchers();
		$open_civs  = $this->get_civs();
		
		$combined_account_civ_array = array();
		
		foreach($raw_accounts as $account){
			foreach($open_civs as $civ){
				if($account->accID == $civ->accID){
					$icps_impacted = explode(",", $civ->allocate);
					
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

	private function get_bank_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_bank = $balance['BC']['amount'];
	}
	
	private function get_petty_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_petty = $balance['PC']['amount'];
	}

	
	/**
	 * Compiles the final array to be used to populate the journal. Each row has 5 keys: details, income_spread, bank, petty and expense_spread.
	 * 
	 */	
	
	
	private function construct_journal(){
		$this->end_bank = $this->get_bank_opening_balance();
		$this->end_petty =  $this->get_petty_opening_balance();
		
		$utilized_accounts_in_a_month = $this->get_utilized_accounts();
		$voucher_transactions =  $this->get_journal_entries();
		$journal_rows = array();
		$i = 0;
		foreach($voucher_transactions as $voucher_value){
			//$value['details'] = to row with a key details in transactions
			$journal_rows[$i]['details'] = $voucher_value['details'];
			if(isset($voucher_value[1])){
				/**
				 * This part of the block picks the income transactions using key value of 1 (Income)
				 * All Utilized incomes accounts for the month = [1] => Array ( [0] => 100 [1] => 200 [2] => 330 [3] => 410 [4] => 415 [5] => 510 [6] => 520 ) 
				 * Actual accounts for a particular voucher = [1] => Array ( [100] => 645389.05 [200] => 93801.65 [330] => 15664.00 [410] => 1088.60 [415] => 8111.75 ) ) 
				 *
				 * The foreach below builds the income account spread for that particular voucher:
				 *  [income_spread] => Array ( [100] => 0 [200] => 0 [330] => 0 [410] => 0 [415] => 0 [510] => 0 [520] => 0 )
				 * 
				 */
				foreach($utilized_accounts_in_a_month[1] as $utilized_income_accounts){
					if(isset($voucher_value[1][$utilized_income_accounts])){//$value['1'][100] == $account['1'][100]
						$journal_rows[$i]['income_spread'][$utilized_income_accounts] = $voucher_value[1][$utilized_income_accounts];
					}else{//$value['1'][100] !== $account['1'][100]
						$journal_rows[$i]['income_spread'][$utilized_income_accounts] = 0;
					}
					
				}
				
				/** Bank and Cash Columns **/
				
				$journal_rows[$i]['bank']['bank_inc'] = 0;
				$journal_rows[$i]['bank']['bank_exp'] = 0;
				$journal_rows[$i]['bank']['bank_bal'] = 0;
				
				$journal_rows[$i]['petty']['petty_inc'] = 0;
				$journal_rows[$i]['petty']['petty_exp'] = 0;
				$journal_rows[$i]['petty']['petty_bal'] = 0;
				
				if($voucher_value['details']['VType'] == 'CR'){
					$journal_rows[$i]['bank']['bank_inc'] = array_sum($voucher_value[1]);	
					$this->end_bank+=array_sum($voucher_value[1]);
				}
				
				$journal_rows[$i]['bank']['bank_bal'] = $this->end_bank; 
				$journal_rows[$i]['petty']['petty_bal'] = $this->end_petty; 
				
				
				/**Append Expense accounts **/
				foreach($utilized_accounts_in_a_month[0] as $account_expense_accounts){
					 $journal_rows[$i]['expense_spread'][$account_expense_accounts] = 0;
				}
				
				
			}else{
				/**Append Income accounts **/	
				foreach($utilized_accounts_in_a_month[1] as $account_income_accounts){
					 $journal_rows[$i]['income_spread'][$account_income_accounts] = 0;
				}
				
				/** Bnak and Cash Columns **/
				$journal_rows[$i]['bank']['bank_inc'] = 0;
				$journal_rows[$i]['bank']['bank_exp'] = 0;
				$journal_rows[$i]['bank']['bank_bal'] = 0;
				
				$journal_rows[$i]['petty']['petty_inc'] = 0;
				$journal_rows[$i]['petty']['petty_exp'] = 0;
				$journal_rows[$i]['petty']['petty_bal'] = 0;
				
				if(($voucher_value['details']['VType'] == 'CHQ' || $voucher_value['details']['VType'] == 'BCHG') && isset($voucher_value[0])){
					//These are expenses affecting the bank as expenses to accounts and should not be petty cash deposits (Account group = 3) as petty cash deposits are income in petty cash
					 
					$journal_rows[$i]['bank']['bank_exp'] = array_sum($voucher_value[0]);
					$this->end_bank-=array_sum($voucher_value[0]);
					
				}elseif($voucher_value['details']['VType'] == 'CHQ' && isset($voucher_value[3])){
					//These are expenses affecting the bank as petty cash deposits. Money moves from a bank to petty cash. 
					
					$journal_rows[$i]['bank']['bank_exp'] = array_sum($voucher_value[3]);	
					$journal_rows[$i]['petty']['petty_inc'] = array_sum($voucher_value[3]);	
					
					$this->end_bank-=array_sum($voucher_value[3]);
					$this->end_petty+=array_sum($voucher_value[3]);
					
				}elseif($voucher_value['details']['VType'] == 'PC'){
					$journal_rows[$i]['petty']['petty_exp'] = array_sum($voucher_value[0]);
					$this->end_petty-=array_sum($voucher_value[0]);
					
				}elseif($voucher_value['details']['VType'] == 'PCR'){
					$journal_rows[$i]['petty']['petty_exp'] = array_sum($voucher_value[4]);
					$journal_rows[$i]['bank']['bank_inc'] = array_sum($voucher_value[4]);
					
					$this->end_petty-=array_sum($voucher_value[4]);
					$this->end_bank+=array_sum($voucher_value[4]);
				}
				
				$journal_rows[$i]['bank']['bank_bal'] = $this->end_bank; 
				$journal_rows[$i]['petty']['petty_bal'] = $this->end_petty; 
				
				
				foreach($utilized_accounts_in_a_month[0] as $account_expense_accounts){
					if(isset($voucher_value[0][$account_expense_accounts])){
						$journal_rows[$i]['expense_spread'][$account_expense_accounts] = $voucher_value[0][$account_expense_accounts];
					}else{
						$journal_rows[$i]['expense_spread'][$account_expense_accounts] = 0;
					}
					
				}
			}
			
			$i++;
		}
		
		return $journal_rows;
	}

	/** Calculate Running Balances in Cash Journal **/
	//This method calculates the total bank deposit for the month
	private function get_bank_deposit(){
		
		/**
		* The commented code below works similarly as the returned part of this method
		*	$bank = 0;	
		*		foreach($this->construct_journal() as $row){
		*			$bank+=$row['bank']['bank_inc'];
		*		}
		*		
		*	return $bank;
		* */
		
		return array_sum(array_column(array_column($this->construct_journal(), "bank"),"bank_inc"));
	}
	
	
	//This method calculates the total bank deposit for the month
	private function get_bank_payment(){
			
		/**
		* The commented code below works similarly as the returned part of this method
		*	$bank = 0;	
		*		foreach($this->construct_journal() as $row){
		*			$bank+=$row['bank']['bank_exp'];
		*		}
		*		
		*	return $bank;
		* */
		
		return array_sum(array_column(array_column($this->construct_journal(), "bank"),"bank_exp"));
	}	

	//This method calculates the total petty cash deposits for the month
	private function get_cash_deposit(){
		/**	
		*	The commented code below works similarly as the returned part of this method
		*  	$cash= 0;	
		*		foreach($this->construct_journal() as $row){
		*			$cash+=$row['petty']['petty_inc'];
		*		}
		* 	return $cash;
		* */
		return array_sum(array_column(array_column($this->construct_journal(), "petty"),"petty_inc"));
		
	}
	
	//This method calculates the total petty cash payments for the month
	private function get_cash_payment(){
		/**
		 * The commented code below works similarly as the returned part of this method
		 * 	$cash = 0;	
		 *	foreach($this->construct_journal() as $row){
		 *		$cash+=$row['petty']['petty_exp'];
		 *	}
		 *
		 *	return $cash; 
		 */	
		return array_sum(array_column(array_column($this->construct_journal(), "petty"),"petty_exp"));
	}
	
	/**
	 * Start - These methods calculates total incomes and expenses for the mont per account - Required to be optimized
	 */

	 function get_sum_per_income_account(){
	 	$utilized_accounts =  $this->get_utilized_accounts();
		$transactions = $this->get_journal_entries();
		
		$arr = array();
		$arr2 = array();		
		
		foreach($transactions as $row){
			if(array_key_exists('1', $row)){
				$arr[] = $row[1];
			}
		}
		
		foreach($utilized_accounts[1] as $value){
			$arr2[$value]=array_sum(array_column($arr, $value));
		}
		
		return $arr2;
	}
	 
	 function get_sum_per_expense_account(){
	 	$utilized_accounts =  $this->get_utilized_accounts();
		$transactions = $this->get_journal_entries();
		
		$arr = array();
		$arr2 = array();		
		
		foreach($transactions as $row){
			if(array_key_exists('0', $row)){
				$arr[] = $row[0];
			}
		}
		
		foreach($utilized_accounts[0] as $value){
			$arr2[$value]=array_sum(array_column($arr, $value));
		}
		
		return $arr2;
	}
	
	private function get_last_cheque_used(){
		return $this->basic_model->get_last_cheque_used($this->icpNo);
	}
	
	private function get_current_bank(){
		return $this->basic_model->get_current_bank($this->icpNo);
	}
	
	private function get_latest_cheque_book(){
		return $this->basic_model->get_latest_cheque_book($this->icpNo);
	}
	

	
	private function strip_bank_code($value){
		$explode = explode("-", $value);
		return $explode[0];
	}
	
	private function get_utilized_cheques(){
		$cheques = array_column($this->get_coded_cheques_utilized(),"ChqNo");
		
		return array_unique(array_map(array($this,"strip_bank_code"), $cheques));
	}
	
	
	private function get_bank_coded_utilized_cheques(){
		$cheques = array_column($this->get_coded_cheques_utilized(),"ChqNo");
		
		return array_unique($cheques);
	}
	
	protected function list_range_of_cheque_leaves(){
		$last_cheque_book = $this->get_latest_cheque_book();
		$chunk = array_chunk(range($last_cheque_book->start_serial+$last_cheque_book->pages, $last_cheque_book->pages),$last_cheque_book->pages+1);
		return $chunk[0];
	}
	
	private function get_unused_cheque_leaves(){
		return array_diff($this->list_range_of_cheque_leaves(), $this->get_utilized_cheques());
	}	
	
	
	/**
	 * End - These methods calculates total incomes and expenses for the mont per account - Required to be optimized
	 */
	
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
			//Check if in the current month	
			$this->check_transacting_month();
			
			$data['records'] =  $this->construct_journal();
		
			$data['end_bank_balance'] = $this->end_bank;
			$data['end_petty_balance'] = $this->end_petty;
			 		
			$data['opening_bank_balance'] = $this->get_bank_opening_balance();
			$data['opening_petty_balance'] = $this->get_petty_opening_balance();
	 		
			$data['total_bank_deposit'] = $this->get_bank_deposit();
			$data['total_bank_payment'] = $this->get_bank_payment();
	 		
			$data['total_cash_deposit'] = $this->get_cash_deposit();
			$data['total_cash_payment'] = $this->get_cash_payment();
			
			$data['sum_incomes'] = $this->get_sum_per_income_account();
			$data['sum_expenses'] = $this->get_sum_per_expense_account();
	 		
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
		$voucher_found = false;
		if($this->CI->input->get("voucher")){
			
			$vouchers = $this->voucher_transactions($this->CI->input->get("voucher"));		
			
			if(isset($vouchers[$this->CI->input->get("voucher")])){
				$data['voucher'] = $vouchers[$this->CI->input->get("voucher")];
				$voucher_found = true;
			}
			
		}
		
		$data['view'] = ($this->CI->input->get("voucher") && $voucher_found == true)?$this->get_view():"error";
		
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
			//$this->CI->session->set_flashdata('flash_message', $this->insert_voucher_to_database($_POST));
		}
		
		$data['voucher_date_range'] = $this->get_voucher_date_picker_control();
		$data['accounts'] = $this->group_accounts();
		$data['approved_budget'] = $this->budget_grouped_items();
		$data['voucher_number'] = $this->get_next_voucher_number();
		$data['cheques_utilized'] = $this->get_coded_cheques_utilized();
		$project_details = $this->get_project_details();
		$data['bank_code'] = $project_details->bankID;
		$data['civ_accounts'] = $this->accounts_with_open_icp_civs();// To be removed
		$data['last_cheque_book'] = $this->get_latest_cheque_book();
		$data['unused_cheque_leaves'] = $this->get_unused_cheque_leaves();
		
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
		$data['last_cheque_used'] = $this->get_last_cheque_used();
		$data['last_cheque_book'] = $this->get_latest_cheque_book();
		return $data;
	}

	protected function pre_render_ajax_get_cheque_details(){
		
		echo json_encode($this->basic_model->get_cheque_details($_POST['icpNo'],$_POST['ChqNo']));
				
	}
	
}

