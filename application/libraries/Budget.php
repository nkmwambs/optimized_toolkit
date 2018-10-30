<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Initialization.php";

 
final class Budget extends Layout implements Initialization{
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
	
	private function get_budget_schedules(){
		return $this->basic_model->get_budget_schedules($this->icpNo,$this->get_current_fy());
	}
	
	private function group_schedules_by_accno(){
		$schedules = $this->get_budget_schedules();
		
		$grouped = array();
		
		$i=0;
		
		foreach($schedules as $row){			
			$grouped[$row['parentAccID']][$row['AccNo']][] = $row;			
			$i++;
		}
		
		return $grouped;
	}
	
	private function get_income_accounts(){
		return $this->basic_model->account_for_vouchers(1);
	}
	
	private function group_income_accounts_by_accid(){
		$income_accounts = $this->get_income_accounts();
		
		$grouped = array();
				
		foreach($income_accounts as $row){			
			$grouped[$row->accID] = (array)$row;			
			
		}
		
		return $grouped;
	}
	
	private function get_budgeted_income_accounts(){
		return $this->basic_model->get_budgeted_accounts(1,1);
	} 
	
	private function get_budgeted_expense_accounts(){
		return $this->basic_model->get_budgeted_accounts(1,0);
	}
	
	private function get_accounts_grouped_by_income(){
		$grouped = array();
		foreach($this->get_budgeted_income_accounts() as $income){
			foreach($this->get_budgeted_expense_accounts() as $expense){
				if($expense->parentAccID == $income->accID){
					$grouped[$income->AccText.' - '.$income->AccName][] = $expense;
				}
			}
		}
		
		return $grouped;
	}
	
	function list_budget_status(){
		return array("Draft","Submitted","Approved","Declined","Reinstated","Allow Delete");
		//0-Draft,1=Submitted,2=Approved,3=Declined,4=Reinstated,5=Allow Delete
	}
	
	private function get_account_choices(){
		return $this->basic_model->get_account_choices($this->CI->input->post('AccNo'));
	}
	
	function pre_render_ajax_mass_submit_budget(){
		echo "Success";
		
	}
	
	function pre_render_ajax_delete_budget(){
		echo "Success";
		
	}
	
	function pre_render_clone_budget(){
		$data['view'] = 'clone_budget';
		
		return $data;
	}
	
	function pre_render_ajax_mass_submit_budget_items(){
		$msg = 1;
		$fy = $this->CI->input->post('fy');
		$result = $this->basic_model->mass_submit_draft_budget_items($this->get_project_id(),$fy);
		
		if(!$result){
			$msg = "No item updated";
		}
		
		echo $msg;
	}
	
	function pre_render_ajax_submit_budget_item(){
		$msg = 1;
		$scheduleID = $this->CI->input->post('scheduleID');
		$result = $this->basic_model->submit_draft_budget_item($scheduleID);
		
		if(!$result){
			$msg = "No item updated";
		}
		
		echo $msg;
	}
	
	function pre_render_ajax_get_choices_for_account(){
			
		$choices = $this->get_account_choices();
		
		$option ="<option value=''>Select a Description</option>";
		
		foreach($choices as $choice){
			$option .= '<option value='.$choice->name.'>'.$choice->name.'</option>';
		}
		
		echo $option;
	}
	
	function pre_render_ajax_budget_item_posting(){
		$msg = "Operation failed";
		$continue_operation = false;		
			//Check if the budget for the FY is present, if not create a header
		if(count($this->get_budget_schedules())==0){
			//Create a budget header
			$continue_operation = $this->basic_model->create_budget_header($this->get_project_id(),$this->CI->input->post('fy'));
		}else{
			$continue_operation = true;
		}
		
		if($continue_operation){
			//Post the schedule item
			$msg = $this->basic_model->insert_budget_schedule($this->get_project_id(),$this->CI->input->post());
		}
		return $msg;
	}
	
	protected function pre_render_show_budget(){
		
		$data['budget_items'] = $this->group_schedules_by_accno();
		$data['income_accounts'] = $this->group_income_accounts_by_accid();
		$data['budget_status'] = $this->list_budget_status();
		
		$data['view'] = "show_budget";
		
		return $data;
	}
	
	protected function pre_render_show_budget_schedules(){
		$data['budget_items'] = $this->group_schedules_by_accno();
		$data['income_accounts'] = $this->group_income_accounts_by_accid();
		$data['budget_status'] = $this->list_budget_status();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_budget_schedules";
		
		return $data;
	}
	
	protected function pre_render_show_budget_summary(){
		$data['budget_items'] = $this->group_schedules_by_accno();
		$data['income_accounts'] = $this->group_income_accounts_by_accid();
		$data['budget_status'] = $this->list_budget_status();
		$this->load_alone = TRUE;
		
		$data['view'] = "show_budget_summary";
		
		return $data;
	}
	
	protected function pre_render_create_budget_item(){
		$data['choices'] = $this->get_account_choices();
		$data['budget_items'] = $this->group_schedules_by_accno();
		$data['income_accounts'] = $this->group_income_accounts_by_accid();
		$data['accounts'] = $this->get_accounts_grouped_by_income();
		$data['view'] = "create_budget_item";
		
		return $data;
	}
	

}
