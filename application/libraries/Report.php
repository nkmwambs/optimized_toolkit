<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Init.php";

 
final class Journal extends Layout implements Init{
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
	
	
	protected function pre_render_show_report(){
		
		$data['view'] = "show_report";
		
		return $data;
	}
}
