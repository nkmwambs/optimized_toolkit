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
	protected $default_assets_path 		= 'assets/finance';
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
	
	private $column_size;
	
	private $lang_strings = array();
	
	private $language;
	
	private $default_language_path = 'assets/finance/languages';
	
	private $lib_url;
	
	protected $basic_model = null;
	
	private $is_transacting_month = FALSE;
	
	protected $load_alone = FALSE;
	
	protected $has_sidebar;
	
	protected $default_view = "show_journal";
	
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
		
		/** Loading Config and Helpers anc Core Libraries**/
		$this->CI->config->load('finance');
		$this->CI->load->helper('url');
		$this->CI->load->database();
		$this->CI->load->library('session');
		
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
		return $this->config->column_size;
	}
	
	protected function get_sidebar_state(){
		return $this->config->sidebar_state;
	}
	
	protected function has_sidebar(){
		return $this->config->has_sidebar;
	}
	
	public function set_language($locale="english"){
		$this->language = $locale;
		return $this;
	}
	
	
	protected function _load_language()
	{
		$lang = array();
			
		if($this->language === null)
		{
			$this->language = strtolower($this->config->default_language);
		}
		include($this->default_language_path.'/'.$this->language.'.php');
		
		foreach($lang as $handle => $lang_string)
			if(!isset($this->lang_strings[$handle]))
				$this->lang_strings[$handle] = $lang_string;

	}
	
	
	protected function l($handle){

		if(!array_key_exists($handle, $this->lang_strings))
		{
			$phrase = ucwords(implode(" ",explode("_", $handle)));
			
			//Add the new lang phrase to the language file
			$new_lang_phrase = "	\$lang['".$handle."'] = '".$phrase."';".PHP_EOL;
			$fp = fopen($this->default_language_path.'/'.$this->language.'.php', 'a');
			fwrite($fp, $new_lang_phrase);
			fclose($fp);
			
			$this->lang_strings[$handle] = $phrase;
		}
			
		return $this->lang_strings[$handle];
	}	
	
	
	protected function _initialize_variables()
	{

		$this->CI->load->config('finance');
		
		$this->config = (object)array();
		
		/** Initialize all the config variables into this object */
		$this->config->default_language 	= $this->CI->config->item('finance_default_language');
		$this->config->fy_start_month	 	= $this->CI->config->item('fy_start_month');
		$this->config->column_size	 		= $this->CI->config->item('column_size');
		$this->config->sidebar_state 		= $this->CI->config->item('sidebar_state'); 
		$this->config->has_sidebar 			= $this->CI->config->item('has_sidebar'); 
		
		
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
		if(!isset($fy)) $fy = $this->get_current_fy();
			
		$this->lib_url = base_url().$this->get_controller()."/".$this->get_method()."?";
		$this->lib_url .= "assetview=".$assetview."&project=".$this->get_project_id();
		$this->lib_url .= "&startdate=".$startdate."&enddate=".$enddate;
		$this->lib_url .= "&lib=".$lib;
		$this->lib_url .= "&scroll=".$scroll;
		$this->lib_url .= "&fy=".$fy;
		
		if(isset($voucher)){
			$this->lib_url .= "&voucher=".$voucher;
		}
		
		if(isset($entry)){
			$this->lib_url .= "&entry=".$entry;
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
		return $this->CI->input->get('assetview')?$this->CI->input->get('assetview'):$this->default_view;
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
		$fy = date('y',strtotime("+".$count." months",$this->get_start_date_epoch()));
		if($this->CI->input->get('fy')) $fy = $this->CI->input->get('fy');
		return $fy;
	}
	
	protected function get_fy_start_date(){
		$count = $this->config->fy_start_month -1;
				
		$month = $this->config->fy_start_month < 10 ? "0".$this->config->fy_start_month : $this->config->fy_start_month;
		
		return date('Y-'.$month.'-01',strtotime("-".$count." months",$this->get_start_date_epoch()));
	}
	
	     /**
         * Gets list of months in an FY
         * @param  int $start Unix timestamp
         * @return array
         */
        function get_range_of_fy_months(){
        	
			$start = strtotime($this->get_fy_start_date());
        	$end = strtotime(date('Y-m-01',strtotime("+11 months",strtotime($this->get_fy_start_date()))));

            $current = $start;
            $ret = array(date("m",$start));

            while( $current<$end ){
                
                $next = date('Y-m-01', $current) . "+1 month";
                $current = strtotime($next);
                $ret[] = date("m",$current);
            }

            return $ret;
        }
		
	function get_month_name_from_number($month_num=""){
		$month_name = date("M", mktime(0, 0, 0, $month_num, 10));
		return $month_name;
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
	 
	protected function show_spinner($spinner_type="month",$lib="journal",$assetview="show_journal"){
		 include_once ($this->default_view_path."/spinner.php");
	}
	
	protected function set_view($data){
		
		if(empty($data)) die();
		
		extract($data);
		
		ob_start();
		
		if(file_exists($this->default_view_path.$this->asset_view_group.'/'.$view.".php")){
			include_once ($this->default_view_path.$this->asset_view_group.'/'.$view.".php");// This is the content of the view
			include_once ($this->default_assets_path.'/scripts/setup.php');
			include_once ($this->default_assets_path.'/scripts/'.lcfirst($this->asset_view_group).".php");//For view independent JS scripts
		}else{ 
			include_once ($this->default_view_path.$view.".php");
		}
		
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
	
	private function set_js_cdn($cdn_link){
		 $this->js_files[sha1($cdn_link)] = $cdn_link;
	}
	
	private function set_css_cdn($cdn_link){
		 $this->css_files[sha1($cdn_link)] = $cdn_link;
	}
	
	/**
	 * Load JS - Loads all JS files to the js_files parameter
	 *
	 * @param	null
	 * @return	array 	Returns an array of the JS files paths	
	 */
	 
	protected function load_js()
	{
		
		//$this->set_js_cdn("https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js");
		$this->set_js_files($this->default_js_path.'jquery-3.3.1.min.js');
		$this->set_js_files($this->default_js_path.'bootstrap/bootstrap.min.js');
		$this->set_js_files($this->default_js_path.'jquery.dataTables.min.js');
		//$this->set_js_files($this->default_js_path.'buttons.bootstrap.js');
		$this->set_js_cdn("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js");
		$this->set_js_files($this->default_js_path.'dataTables.bootstrap.min.js');
		$this->set_js_files($this->default_js_path.'jquery-ui.min.js');
		$this->set_js_files($this->default_js_path.'printThis.js');
		$this->set_js_files($this->default_js_path.'datepicker/js/bootstrap-datepicker.min.js');
		$this->set_js_files($this->default_js_path.'bootstrap-toggle/js/bootstrap-toggle.min.js');
		$this->set_js_files($this->default_js_path.'bootstrap-dialog/js/bootstrap-dialog.min.js');
		$this->set_js_files($this->default_js_path.'select2/select2.min.js');
		$this->set_js_files($this->default_js_path.'gsap/TweenMax.min.js');
		$this->set_js_files($this->default_js_path.'neon/resizeable.js');
		$this->set_js_files($this->default_js_path.'ckeditor/ckeditor.js');
		$this->set_js_files($this->default_js_path.'ckeditor/adapters/jquery.js');
		$this->set_js_files($this->default_js_path.'neon/neon-api.js');
		$this->set_js_files($this->default_js_path.'neon/neon-custom.js');
		$this->set_js_files($this->default_js_path.'neon/neon-demo.js');
		//$this->set_js_files($this->default_js_path.'selectboxit/jquery.selectBoxIt.min.js');
		
		
		//Used in Datatables
		// $this->set_js_cdn("https://code.jquery.com/jquery-3.3.1.js");
		//$this->set_js_cdn("https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js");
		$this->set_js_cdn("https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js");
		$this->set_js_cdn("https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js");
		$this->set_js_cdn("https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js");
		$this->set_js_cdn("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js");
		$this->set_js_cdn("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js");
		$this->set_js_cdn("https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js");
		$this->set_js_cdn("https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js");
		//$this->set_js_cdn("https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.min.js");
		
		
		//$this->set_js_files($this->default_js_path.'modules_js/'.lcfirst($this->asset_view_group).'.js?'.rand());
		
		$this->set_js_files($this->default_js_path.'sys.js?'.rand());
		
		return $this->js_files;
	}
	
	/**
	 * Load CSS - Loads all CSS files to the css_files parameter
	 *
	 * @param	null
	 * @return	array 	Returns an array of the CSS files paths	
	 */
	
	protected function load_css(){
		$this->set_css_files($this->default_css_path.'neon/neon-core.css');
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
		$this->set_css_files($this->default_css_path.'bootstrap-dialog/css/bootstrap-dialog.min.css');
		$this->set_css_files($this->default_css_path.'select2/select2-bootstrap.css');
		$this->set_css_files($this->default_css_path.'select2/select2.css');
		//$this->set_css_files($this->default_css_path.'selectboxit/jquery.selectBoxIt.css');
		
		//Used in Datatables
		$this->set_css_cdn("https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css");
		$this->set_css_cdn("https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css");
		//$this->set_css_cdn("https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.css");
		
		return $this->css_files;
	}

	function main_view($output){
		extract($output);
		include_once ($this->default_view_path."/main_view.php");	
	}
	
	function data_view($output){
		extract($output);
		include_once ($this->default_view_path."/data_view.php");	
	}
	
	function show_header($title="",$spinner_type="month"){
		include_once ($this->default_view_path."/utility_open_standalone.php");
	}
	
	function show_footer(){
		include_once ($this->default_view_path."/utility_close_standalone.php");
	}
	
	/**
	 * Get Layout - Arranges the output array ready for controller output
	 *
	 * @param	null
	 * @return	object 	Returns an object with the 4 keys to be used to populate the final view
	 */		
	protected function get_layout(){
		
		//$js_files = $this->load_js();
		//$css_files =  $this->load_css();
		
		$this->CI->benchmark->mark('profiler_end');
		
		if($this->echo_and_die == false){
			return (object) array(
					//'js_files' => $js_files,
					//'css_files' => $css_files,
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
		$output = (array)$this->get_layout(); 
		
		//return $this->CI->load->view("main",$output);
		
		
		return $this->main_view($output);
		 
		
	}


}
