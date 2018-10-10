<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Layout {
	
	protected $icpNo;
	protected $start_date;
	protected $end_date;
	protected $asset_view_group;
	
	/**
	 * Stores the value of CI Instance Object
	 * 
	 * @var Object
	 */
	protected $CI;
	
	/**
	 * Holds the a buffer of the HTML code/ view to be output to the Controller method
	 * @var String
	 */
	protected $view_as_string;
	
	/**
	 * Stores the Javascript assets path
	 * @var String
	 */
	protected $default_js_path;
	/**
	 * Stores the Cascading Style Sheets assets path
	 * @var String
	 */
	protected $default_css_path;
	/**
	 * Stores the views assets path
	 * @var String
	 */
	protected $default_view_path;
	/**
	 * This is the default path to the assets folder
	 * @var String
	 */
	protected $default_assets_path 		= 'assets/ifms_assets';
	/**
	 * Hold and array of all the CSS scripts to be used by the final view
	 * @var String
	 */
	protected $css_files				= array();
	/**
	 * Hold and array of all the JS scripts to be used by the final view
	 * @var String
	 */
	protected $js_files					= array();
	
	
	protected $echo_and_die				= false;
	
	protected $config;
	
	private $column_size = "col-sm-offset-1 col-sm-10 col-sm-offset-1";
	
	private $lang_strings = array();
	
	private $language;
	
	private $default_language_path = 'assets/ifms_assets/languages';
	
	private $lib_url;
	
	protected $basic_model = null;
	
	private $is_transacting_month = FALSE;
	
	protected $load_alone = FALSE;
	
	//private $fy_start_month = 7; 

	function __construct($params = array()){
		
		/** Initialize Codeigniter Instance **/	
		$this->CI=& get_instance();
		
		/** Load Finance Model **/
		$this->CI->load->model("Finance_model");
		$this->basic_model 	= new Finance_model();
		
		/** Initialize default paths */
		$this->default_js_path	= $this->default_assets_path.'/js/';
		$this->default_css_path			= $this->default_assets_path.'/css/';
		$this->default_view_path		= $this->default_assets_path.'/views/';
		
		/** Loading Config and Helpers **/
		$this->CI->config->load('finance');
		$this->CI->load->helper('url');
		$this->CI->load->database();
		
		/**Initialization of url segments **/
		
		$transaction_month = $this->basic_model->get_transacting_month($this->CI->input->get("project"));
		$this->icpNo = $this->CI->input->get("project");
		$this->start_date 	= date("Y-m-d",$transaction_month['start_date']);
		$this->end_date  	= date("Y-m-d",$transaction_month['end_date']);
		
		if(substr($this->get_view(),0,4) == "ajax")	$this->echo_and_die = true;
		
		/*cache control*/
		
		$this->CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->CI->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->CI->output->set_header("Pragma: no-cache"); 
		
	}

	protected function preloader(){
		return "<img style='position:relative;left:50%;width:65px;height:65px;' src='".base_url().$this->default_assets_path."/images/preloader4.gif'/>";
	}
	
	public function set_column_size($col_size=""){
		$this->column_size = $col_size;
		return $this;
	}
	
	protected function get_column_size(){
		return $this->column_size;
	}
	
	public function set_language($locale="english"){
		$this->language = $locale;
		return $this;
	}
	
	
	protected function _load_language()
	{
		if($this->language === null)
		{
			$this->language = strtolower($this->config->default_language);
		}
		include($this->default_language_path.'/'.$this->language.'.php');

		foreach($lang as $handle => $lang_string)
			if(!isset($this->lang_strings[$handle]))
				$this->lang_strings[$handle] = $lang_string;

	}
	
	
	public function l($handle){
		return $this->lang_strings[$handle];
	}	
	
	
	protected function _initialize_variables()
	{

		$this->CI->load->config('finance');
		
		$this->config = (object)array();
		
		/** Initialize all the config variables into this object */
		$this->config->default_language 	= $this->CI->config->item('finance_default_language');
		$this->config->fy_start_month	 	= $this->CI->config->item('fy_start_month');
		
	}	
	
	
		/** URL Segments Getters - Start **/
	
	/**
	 * Get URL - Construct a URL to be used in view href tags
	 * 
	 * @param string 	pre_render_control	Sets the URI segment 3 of the URL. Mandatory Argument
	 * @param int 		start_date 			Sets a specified month start date in unix timestamp. Can be empty
	 * @param int 		end_date			Sets a specified month  end date in unix timestamp
	 * @param string	extra_segment_one 	This is an extra uri segment. Can be empty
	 * @param string 	extra_segment_two   This is an extra uri segment. Can be empty
	 */
	
	protected function set_asset_view_group($group=""){
		$this->asset_view_group = $group;
		return $this;
	}
	
	protected function get_asset_view_group(){
		return $this->asset_view_group;
	}
	
	protected function get_url($params=array()){
		
		if(is_array($params)) extract($params);
			
		if(!isset($startdate)) $startdate = $this->get_start_date_epoch();
		if(!isset($enddate)) $enddate = $this->get_end_date_epoch();
		if(!isset($lib)) $lib = 'journal';
		if(!isset($scroll)) $scroll = 0;
		if(!isset($assetview)) $assetview = 'show_journal';
			
		$this->lib_url = base_url().$this->get_controller()."/".$this->get_method()."?";
		$this->lib_url .= "assetview=".$assetview."&project=".$this->get_project_id();
		$this->lib_url .= "&startdate=".$startdate."&enddate=".$enddate;
		$this->lib_url .= "&lib=".$lib;
		$this->lib_url .= "&scroll=".$scroll;
		
		if(isset($voucher)){
			$this->lib_url .= "&voucher=".$voucher;
		}
		
		return $this->lib_url;
	}
	
	protected function get_controller(){
		return $this->CI->router->fetch_class();
	}
	
	protected function get_method(){
		return $this->CI->router->fetch_method();
	}	
	
	protected function get_scroll(){
		return $this->CI->input->get("scroll")?$this->CI->input->get("scroll"):"0";
	}
	
	protected function get_selected_voucher_number(){
		return $this->CI->input->get("voucher")?$this->CI->input->get("voucher"):"";
	}
	
	protected function get_view(){
		//return $this->CI->uri->segment(3);
		return $this->CI->input->get('assetview');
	}
	
	/** URL Segments Getters - End **/
	
	
		
	/** General setters & getters - Start**/		
	
	private function set_project_id($project_id=""){
		$this->icpNo = $project_id;
	}

	private function set_date($params=array()){
		$this->start_date	= $params['START_DATE'];
		$this->end_date 	= $params['END_DATE'];
	}
	
	/** General setters & getters - End**/	
	
	
	protected function get_project_id(){
		return $this->icpNo;
	}
	
	/** Date Related Getters - Start **/

	protected function get_start_date(){
		return $this->start_date;
	}
	
	protected function get_end_date(){
		return $this->end_date;
	}
	
	protected function get_start_date_epoch(){
		return strtotime($this->start_date);
	}
	
	protected function get_end_date_epoch(){
		return strtotime($this->end_date);
	}
	
	/**
	 * Test this part with the finance config
	 */
	protected function get_current_fy(){
		$count = $this->config->fy_start_month - 1;
		return date('y',strtotime("+".$count." months",$this->get_start_date_epoch()));
	}
	
	protected function get_fy_start_date(){
		$count = $this->config->fy_start_month -1;
				
		$month = $this->config->fy_start_month < 10 ? "0".$this->config->fy_start_month : $this->config->fy_start_month;
		
		return date('Y-'.$month.'-01',strtotime("-".$count." months",$this->get_start_date_epoch()));
	}
	
	protected function get_months_elapsed(){
	 	// @link http://www.php.net/manual/en/class.datetime.php
		$d1 = new DateTime($this->get_fy_start_date());
		$d2 = new DateTime($this->get_start_date());
		
		// @link http://www.php.net/manual/en/class.dateinterval.php
		$interval = $d2->diff($d1);
		
		return $interval->format('%m')+1;
	 }
	
	/**
	 * Test this part with the finance config
	 */
	
	protected function get_transacting_month(){
		return $this->basic_model->get_transacting_month($this->CI->input->get("project"));;
	}
	
	protected function check_transacting_month(){
		$transacting_month = $this->get_transacting_month();
		return $this->is_transacting_month  = 
		$transacting_month['start_date'] == $this->get_start_date_epoch()?TRUE:FALSE;
	}
	
	/**
	 * Set View Buffer - Set the view as string parameter that hold the views content as plain text
	 *
	 * @param	array	$data				A return array from a pre-render 
	 * @return	void
	 */
	protected function set_view($data){
		
		if(empty($data)) die();
		
		extract($data);
		
		ob_start();
		
		if(file_exists($this->default_view_path.$this->asset_view_group.'/'.$view.".php"))
			include_once ($this->default_view_path.$this->asset_view_group.'/'.$view.".php");
		else 
			include_once ($this->default_view_path.$view.".php");
		
		
		$buffered_view = ob_get_contents();
		
		ob_end_clean();
		
		
		$this->view_as_string .= $buffered_view;
	}
	
	/**
	 * Sets CSS files paths array - Sets the css_files parameter that hold an array of all CSS files
	 *
	 * @param	string	$css_file			Absolute path to a CSS file 
	 * @return	void	
	 */
	
	private function set_css_files($css_file){
		$this->css_files[sha1($css_file)] = base_url().$css_file;
	}
	
	/**
	 * Sets JS files paths array - Sets the css_files parameter that hold an array of all JS files
	 *
	 * @param	string	$js_file			Absolute path to a JS file 
	 * @return	void	
	 */
	
	private function set_js_files($js_file){
		$this->js_files[sha1($js_file)] = base_url().$js_file;		
	}
	
	/**
	 * Load JS - Loads all JS files to the js_files parameter
	 *
	 * @param	null
	 * @return	array 	Returns an array of the JS files paths	
	 */
	 
	private function load_js()
	{
		
		$this->set_js_files($this->default_js_path.'jquery-3.3.1.min.js');
		$this->set_js_files($this->default_js_path.'bootstrap/bootstrap.min.js');
		$this->set_js_files($this->default_js_path.'jquery.dataTables.min.js');
		//$this->set_js_files($this->default_js_path.'buttons.bootstrap.js');
		$this->set_js_files($this->default_js_path.'dataTables.bootstrap.min.js');
		$this->set_js_files($this->default_js_path.'jquery-ui.min.js');
		$this->set_js_files($this->default_js_path.'printThis.js');
		$this->set_js_files($this->default_js_path.'datepicker/js/bootstrap-datepicker.min.js');
		$this->set_js_files($this->default_js_path.'bootstrap-toggle/js/bootstrap-toggle.min.js');
		$this->set_js_files($this->default_js_path.'custom.js');
		
		return $this->js_files;
	}
	
	/**
	 * Load CSS - Loads all CSS files to the css_files parameter
	 *
	 * @param	null
	 * @return	array 	Returns an array of the CSS files paths	
	 */
	
	private function load_css(){
		$this->set_css_files($this->default_css_path.'bootstrap/bootstrap.min.css');
		$this->set_css_files($this->default_css_path.'dataTables.bootstrap.min.css');
		$this->set_css_files($this->default_css_path.'custom.css');
		$this->set_css_files($this->default_css_path.'jquery-ui-themes/base/jquery-ui.min.css');
		$this->set_css_files($this->default_css_path.'jquery-ui-themes/base/theme.css');
		$this->set_css_files($this->default_css_path.'font-icons/font-awesome/css/font-awesome.css');
		$this->set_css_files($this->default_css_path.'font-icons/entypo/css/entypo.css');
		$this->set_css_files($this->default_css_path.'font-icons/font-awesome/css/font-awesome.css');
		$this->set_css_files($this->default_css_path.'datepicker/css/bootstrap-datepicker.min.css');
		$this->set_css_files($this->default_css_path.'bootstrap-toggle/css/bootstrap-toggle.min.css');
		
		return $this->css_files;
	}
	
	/**
	 * Get Layout - Arranges the output array ready for controller output
	 *
	 * @param	null
	 * @return	object 	Returns an object with the 4 keys to be used to populate the final view
	 */		
	protected function get_layout(){
		
		$js_files = $this->load_js();
		$css_files =  $this->load_css();
		
		$this->CI->benchmark->mark('profiler_end');
		
		if($this->echo_and_die == false){
			return (object) array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output' => $this->view_as_string,
					'profiler'=>$this->CI->benchmark->elapsed_time('profiler_start', 'profiler_end'),
			);
		}elseif($this->echo_and_die == true){
			echo $this->view_as_string;
			die();
		}
		
		
	}	

	/**
	 * Render 
	 * 
	 */
	
	public function rendering(){
		
		$this->CI->benchmark->mark('profiler_start');
		
		/**Initialization for page rendering**/
		$this->_initialize_variables();
		$this->_load_language();
		
		/**Carries pre_render data and view name**/
		$preference_data = array();
		
		/**Set new date ranges when scrolling out of the current transacting month**/
		if($this->CI->input->get("scroll")){
			$start_date = date("Y-m-01",strtotime($this->CI->input->get("scroll")." months",$this->get_start_date_epoch()));
			$end_date = date("Y-m-t",strtotime($this->CI->input->get("scroll")." months",$this->get_start_date_epoch()));
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
