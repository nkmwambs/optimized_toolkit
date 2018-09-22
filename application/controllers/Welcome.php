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
	
	/**
	 * To use the Journal library, you need to:
	 * a) Create a method in the class. In this scenario its finance.
	 * b) Load the Jourmal Library i.e. $this->load->library('journal');
	 * c) Call the set_project_id method of the Journal libarary class. This is a setter method of the Jornal Library. It received the argument of ICP ID
	 * d) Call the set_date method. This is a setter method of the Journal Library. It receives the argument as an array with START_DATE and END_DATE keys. 
	 **/
	
	public function finance($view_type="",$icp="",$start_date="",$end_date=""){		
				
		$this->load->library('journal');
				
		$output = $this->journal->render();
	
		$this->load->view("finance",$output);
	}
}
