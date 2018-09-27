<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Finance {

	private $CI ;
	private $res;
	private $mod ;
	
	function __construct($module){
		$this->CI =& get_instance();
		
		extract($module);
		
		include_once $mod_name.".php";
		return $this->res = new $mod_name();
	}
	
	function render(){
		return $this->res->rendering();
	}
}
