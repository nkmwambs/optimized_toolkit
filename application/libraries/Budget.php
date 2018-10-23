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
	
	function list_budget_status(){
		return array("Draft","Submitted","Approved","Declined","Reinstated","Allow Delete");
		//0-Draft,1=Submitted,2=Approved,3=Declined,4=Reinstated,5=Allow Delete
	}
	
	protected function pre_render_show_budget(){
		
		$data['budget_items'] = $this->group_schedules_by_accno();
		$data['income_accounts'] = $this->group_income_accounts_by_accid();
		$data['budget_status'] = $this->list_budget_status();
		
		$data['view'] = "show_budget";
		
		return $data;
	}
	

}
