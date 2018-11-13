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
	
	
	protected function pre_render_show_expense_tracking_tags(){
		
		$data['view'] = "show_expense_tracking_tags";
		
		return $data;
	}
}
