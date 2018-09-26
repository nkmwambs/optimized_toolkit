<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

		/**
		 * 
		 * Constructor method
		 * 
		 * This is a special method is loaded every time its constituent class create an object. To have the library work, make sure you add the parent::__construct().
		 * The parent::__construct() call the parent constructor of the extended class in this case, the CI_Controller
		 * 
		 * */
	 
	function __construct()
	{

		parent::__construct();
		
		$this->load->helper("url");
		
	}	 
	 
	 /**
	  * The index method is not mandatory for the IFMS library to work
	  * **/
	  
	public function index()
	{
		$data['item'] = "Welcome";
		$this->load->view('welcome_message',$data);
		
		
	}
	
	
	public function finance(){
		
		$this->load->library('Journal');			
					
		$ifms = new Journal();
		
		//$ifms->set_language("french");
		
		//$ifms->set_column_size("col-sm-offset-1 col-sm-10");
				
		$output = $ifms->render();
	
		$this->load->view("finance",$output);
	}
}
