<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Initialization.php";

class Admin extends Layout implements Initialization{
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
	
	
	private function get_income_accounts(){
		return $this->basic_model->account_for_vouchers(1);
	}
	
	
	private function get_expense_accounts(){
		return $this->basic_model->account_for_vouchers(0);
	}
	
	private function group_expense_account_to_incomes(){
		$income_accounts = $this->get_income_accounts();
		$expense_accounts = $this->get_expense_accounts();
		
		$grouped_expense_accounts = array();
		
		foreach($income_accounts as $income){
			if($income->Active == 1){
			foreach($expense_accounts as $expense){
				if($income->accID == $expense->parentAccID){
					$grouped_expense_accounts[$income->AccText][] = array("AccNo"=>$expense->AccNo,"AccName"=>$expense->AccName,"AccText"=>$expense->AccText);
				}
			}
			}
		}
		
		return $grouped_expense_accounts;		
	}
	
	private function get_all_expense_tracking_tags(){
		return $this->basic_model->get_expense_tracking_tags("all");
	}
	
	private function get_expense_tracking_tag_by_id($id){
		return $this->basic_model->get_expense_tracking_tags(null,$id);
	}
	
	function pre_render_ajax_post_expense_tracking_tag(){
		$msg = "Failure!";//$this->l('vouching_failure');
		
		if($this->basic_model->post_expense_tracking_tag($this->CI->input->post())){
			$msg = "Success";//$this->l('vouching_success');
		}
		
		echo $msg;
	}
	
	protected function pre_render_add_expense_tracking_tags(){
		
		$data['accounts'] = $this->group_expense_account_to_incomes();
		$data['view'] = "add_expense_tracking_tags";	
		return $data;
	}
	
	protected function pre_render_edit_expense_tracking_tags(){
		
		$tag = $this->get_expense_tracking_tag_by_id($this->CI->input->get('tag_id'));
		
		$data['accounts'] 	= $this->group_expense_account_to_incomes();
		$data['tag_id']		= $this->CI->input->get('tag_id');
		$data['tag'] 		= $tag[0];
		$data['view'] 		= "edit_expense_tracking_tags";	
		return $data;
	}
	
	protected function pre_render_ajax_edit_expense_tracking_tag(){
		$msg = $this->l('update_failed');	
		if($this->basic_model->edit_expense_tracking_tag($this->CI->input->post())){
			$msg = $this->l('update_successful');	
		}
		
		echo $msg;
	}
	
	function pre_render_ajax_update_expense_tracking_tag_status(){
		$msg = $this->l('update_failed');	
		if($this->basic_model->update_expense_tracking_tag_status($this->CI->input->post())){
			$msg = $this->l('update_successful');	
		}
		
		echo $msg;
	}

	protected function pre_render_show_expense_tracking_tags(){
		
		$data['tags'] = $this->get_all_expense_tracking_tags();
		$data['view'] = "show_expense_tracking_tags";
		return $data;
	}	
}
