<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Layout.php";
include "Initialization.php";

class Budget extends Layout implements Initialization{
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
	
	
	protected function pre_render_show_budget(){
		
		$data['view'] = "show_budget";
		
		return $data;
	}
	
		public function render(){
		
		$this->CI->benchmark->mark('profiler_start');
		
		/**Initialization for page rendering**/
		$this->_initialize_variables();
		$this->_load_language();
		
		/**Carries pre_render data and view name**/
		$preference_data = array();
		
		/**Set new date ranges when scrolling out of the current transacting month**/
		if($this->CI->uri->segment(7)){
			$start_date = date("Y-m-01",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_start_date())));
			$end_date = date("Y-m-t",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_end_date())));
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
		}
		
		/**Call the appropriate pre-render method based on the uri segment 3 set in the get_view method**/
		$preference_data = call_user_func(array($this, "pre_render_".$this->get_view()));
		
		/**Prepare a buffer that hold the view file contents **/
		$this->set_view($preference_data);
		
		/**Merges the view buffer, css, js and profiler results to an output object**/
		return $this->get_layout(); 
	}
}
