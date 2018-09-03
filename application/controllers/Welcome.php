<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		$this->load->helper("url");
	}	 
	 
	public function index()
	{
		
		$this->load->view('welcome_message');
	}
	
	public function cash_journal(){
		
		$this->load->library('journal');
		$this->journal->set_project_id("KE345");
		$this->journal->set_date(array("START_DATE"=>"2018-01-01","END_DATE"=>"2018-01-31"));
		
		$output = $this->journal->render();
		
		$this->load->view("cash_journal",$output);
	}
}
