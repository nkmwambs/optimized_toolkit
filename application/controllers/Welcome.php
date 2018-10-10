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
	
	
	public function journal(){
		
		$this->load->library('Finance',array('mod_name'=>$this->input->get("lib")));			
				
		$output = $this->finance->render();
	
		$this->load->view("main",$output);
	}

	// public function report(){
// 		
		// $this->load->library('Finance',array('mod_name'=>'report'));			
// 				
		// $output = $this->finance->render();
// 	
		// $this->load->view("main",$output);
	// }	
// 	
	// public function budget(){
// 		
		// $this->load->library('Finance',array('mod_name'=>'budget'));			
// 				
		// $output = $this->finance->render();
// 	
		// $this->load->view("main",$output);
	// }
}
