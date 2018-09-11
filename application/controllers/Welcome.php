<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	 
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper("url");
	}	 
	 
	public function index()
	{
		$data['item'] = "Welcome";
		$this->load->view('welcome_message',$data);
		
	}
	
	public function finance($view_type="",$icp="",$start_date="",$end_date=""){
		
		$this->load->library('journal');
		
		$this->journal->set_project_id($icp);
		
		$this->journal->set_date(array("START_DATE"=>date("Y-m-d",$start_date),"END_DATE"=>date("Y-m-d",$end_date)));
		
		$output = $this->journal->render();
	
		$this->load->view("finance",$output);
	}
}
