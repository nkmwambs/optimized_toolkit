<?php
defined('BASEPATH') OR exit('No direct script access allowed');

final class Finance{

	private $CI ;
	private $res = null;
	
	function __construct($module){
		$this->CI =& get_instance();
		$this->CI->load->config('finance');
		
		extract($module);
		
		!isset($mod_name)?$mod_name="journal":extract($module);
		
		include ucfirst($mod_name).".php";
		$this->res = new $mod_name();
		
		return $this;
	}
		
	function render(){
		return $this->res->rendering();
	}
}
