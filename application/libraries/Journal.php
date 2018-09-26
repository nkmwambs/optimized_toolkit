<?php

/************************************************************************************************************
 * Journal Package
 * 
 * This is a Codeigniter Library developed by Compassion Africa GTS to enable portability of the Frontline
 * Church Partner Finance Application across Codeigniter Installations.
 * 
 * This Package comes with 1 Library  (Journal Library), 1 Model (Journal Model), Asset Directory
 * (ifms_assets) and A Config file (ifms).
 * 
 * This software is only developed for use within Compassion Assisted Churches and authorization from the
 * Compassion would be required to extend or use this piece of software anywhere else.
 * 
 * @package	Journal
 * @author	Compassion Africa
 * @copyright Copyright (c) 2018 , Compassion International Inc. (https://www.compassion.com/)
 * @link	https://www.compassion.com
 * @since	Version 2.0.0
 * @filesource
 * 
 * **********************************************************************************************************/

 defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Journal_Layout Class
 *
 * This class contains functions that sets the final view output object.
 *
 * @package		Journal 
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Compassion International Inc
 * @link		https://www.compassion.com/
 */
class Journal_Layout{
	
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
	protected $default_javascript_path;
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

	protected $locale					= "english";
	
	protected $language_phrases;
	
	protected $config;

	function __construct($params = array()){
		
		/** Initialize Codeigniter Instance **/	
		$this->CI=& get_instance();
		
		/** Initialize default paths */
		$this->default_javascript_path	= $this->default_assets_path.'/js/';
		$this->default_css_path			= $this->default_assets_path.'/css/';
		$this->default_view_path		= $this->default_assets_path.'/views/';
		
		/** Loading Config and Helpers **/
		$this->CI->config->load('ifms');
		$this->CI->load->helper('url');
		$this->CI->load->database();
		
		/*cache control*/
		
		$this->CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->CI->output->set_header("Cache-Control: post-check=0, pre-check=0");
		$this->CI->output->set_header("Pragma: no-cache"); 
				
	}
	
	protected function _initialize_variables()
	{

		$this->CI->load->config('ifms');
		
		$this->config = (object)array();
		
		/** Initialize all the config variables into this object */
		$this->config->default_language 	= $this->CI->config->item('ifms_default_language');
		
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
		$this->set_js_files($this->default_javascript_path.'jquery-3.3.1.min.js');
		$this->set_js_files($this->default_javascript_path.'bootstrap/bootstrap.min.js');
		$this->set_js_files($this->default_javascript_path.'jquery.dataTables.min.js');
		$this->set_js_files($this->default_javascript_path.'buttons.bootstrap.js');
		$this->set_js_files($this->default_javascript_path.'dataTables.bootstrap.min.js');
		$this->set_js_files($this->default_javascript_path.'jquery-ui.min.js');
		$this->set_js_files($this->default_javascript_path.'printThis.js');
		$this->set_js_files($this->default_javascript_path.'datepicker/js/bootstrap-datepicker.min.js');
		$this->set_js_files($this->default_javascript_path.'bootstrap-toggle/js/bootstrap-toggle.min.js');
		$this->set_js_files($this->default_javascript_path.'custom.js');
		
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
}


class Journal extends Journal_Layout{
	protected $icpNo;
	protected $start_date;
	protected $end_date;
	protected $opening_bank;
	protected $opening_petty;
	protected $start_bank;
	protected $start_petty;
	private $basic_model = null;
	private $pre_render;	
	private $column_size = "col-sm-offset-1 col-sm-10 col-sm-offset-1";
	private $lang_strings = array();
	private $language;
	private $default_language_path = 'assets/ifms_assets/languages';
	
	function __construct(){
		parent::__construct();
		$this->CI->load->model("Journal_model");
		$this->basic_model 	= new Journal_model();
		
		/**Initialization**/
		if(substr($this->get_view(),0,4) !== "ajax"){
			$transaction_month = $this->basic_model->get_transacting_month($this->CI->uri->segment(4));
			$this->icpNo = $this->CI->uri->segment(4);
			$this->start_date 	= date("Y-m-d",$transaction_month['start_date']);
			$this->end_date  	= date("Y-m-d",$transaction_month['end_date']);
		}else{
			$this->echo_and_die = true;
		}
		
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
	
	// public function set_lang_string($handle, $lang_string){
		// $this->lang_strings[$handle] = $lang_string;
// 
		// return $this;
	// }
		
	
	public function l($handle){
		return $this->lang_strings[$handle];
	}	
	
		
	/** Start of Model Wrappers **/
	
	private function opening_cash_balance(){
		return $this->basic_model->start_cash_balance($this->icpNo,$this->start_date);
	}
	
	private function get_transacting_month(){
		return $this->basic_model->get_transacting_month($this->CI->uri->segment(4));
	}	
	
    private function get_current_month_transactions()
    {		
		return $this->basic_model
		->get_journal_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
	
	private function account_for_vouchers(){
		return $this->basic_model->account_for_vouchers();
	}
	
	private function get_current_approved_budget(){
		return $this->basic_model->get_current_approved_budget($this->get_project_id(),$this->get_current_fy());
	}
	
	
	private function get_civs(){
		return $this->basic_model->get_civs();
	}	
	
	private function get_next_voucher_number(){
		return $this->basic_model->get_next_voucher_number($this->get_project_id());
	}
	
	private function get_voucher_date_picker_control(){
		return $this->basic_model->get_voucher_date_picker_control($this->get_project_id());
	}
	
	private function get_coded_cheques_utilized(){
		return $this->basic_model->get_cheques_utilized_with_bank_code($this->get_project_id());
	}
	
	private function get_project_details(){
		return $this->basic_model->get_project_details($this->get_project_id());
	}
	
	private function insert_voucher_to_database($post_array=array()){
		return $this->basic_model->insert_voucher_to_database($post_array);
	}
	
    private function get_current_month_transactions_for_voucher()
    {	
		return $this->basic_model
		->get_voucher_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
		
	/** End of Model Wrappers **/	
	
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
	
	protected function get_current_fy(){
		return 19;
	}

	/** Date Related Getters - End **/
	
	
	/** URL Segments Getters - Start **/
	
	protected function get_controller(){
		return $this->CI->router->fetch_class();
	}
	
	protected function get_method(){
		return $this->CI->router->fetch_method();
	}
	
	protected function get_first_extra_segment(){
		return $this->CI->uri->segment(7)?$this->CI->uri->segment(7):"0";
	}
	
	protected function get_second_extra_segment(){
		return $this->CI->uri->segment(8)?$this->CI->uri->segment(8):"";
	}	
	
	private function get_view(){
		return $this->CI->uri->segment(3);
	}
	
	/** URL Segments Getters - End **/
	
	
	/** Data Construction - Start**/
	

	
	protected function budget_grouped_items(){
		$raw_budget = $this->get_current_approved_budget();
		
		$account_groups = array();
		
		foreach($raw_budget as $item){
			$account_groups[$item->AccNo][] = $item;
		}
		
		return $account_groups;
	}
	
	private function current_voucher_date(){
		return $this->basic_model->get_current_voucher_date($this->get_project_id());
	}
	
	protected function accounts_with_open_icp_civs(){
		$raw_accounts = $this->account_for_vouchers();
		$open_civs  = $this->get_civs();
		
		$combined_account_civ_array = array();
		
		foreach($raw_accounts as $account){
			foreach($open_civs as $civ){
				if($account->accID == $civ->accID){
					$icps_impacted = explode(",", $civ->allocate);
					
					// $control_dates = $this->get_voucher_date_picker_control();
					// if(in_array($this->get_project_id(), $icps_impacted) && strtotime($civ->closureDate) > $control_dates['end_date']){
						// $combined_account_civ_array[$account->AccNo][$civ->AccNoCIVA] = array("AccNo"=>$account->AccNo,"civaCode"=>$civ->civaID,"AccText"=>$civ->AccNoCIVA,"AccName"=>"(".$account->AccText.") ".$account->AccName,"closureDate"=>$civ->closureDate,"allocate"=>explode(",", $civ->allocate));	
					// }
					if(in_array($this->get_project_id(), $icps_impacted) && $civ->closureDate > $this->current_voucher_date()){
						$combined_account_civ_array[$account->AccNo][$civ->AccNoCIVA] = array("AccNo"=>$account->AccNo,"civaCode"=>$civ->civaID,"AccText"=>$civ->AccNoCIVA,"AccName"=>"(".$account->AccText.") ".$account->AccName,"closureDate"=>$civ->closureDate,"allocate"=>explode(",", $civ->allocate));	
					}
					
				}
			}
		}
		
		return $combined_account_civ_array;
	}

	protected function group_accounts(){
		$raw_accounts = $this->account_for_vouchers();
		$accounts_with_civs = $this->accounts_with_open_icp_civs();
		
		$AccGrps = array("expense","revenue","bankbalance","pcdeposits","rebanking");
		
		$grouped = array();
		
		foreach($raw_accounts as $account){
			if($account->Active == 1){
				$grouped[$AccGrps[$account->AccGrp]][] = $account;
			}else{
				if(isset($accounts_with_civs[$account->AccNo])){
					foreach($accounts_with_civs[$account->AccNo] as $civ_account){
						$grouped[$AccGrps[$account->AccGrp]][] = (object)$civ_account;
					}
				}
				
			}
			
		}
		
		return $grouped;
	}
	

	private function get_start_bank(){
		$balance = $this->opening_cash_balance();
		return $this->opening_bank = $balance['BC']['amount'];
	}
	
	private function get_start_petty(){
		$balance = $this->opening_cash_balance();
		return $this->opening_petty = $balance['PC']['amount'];
	}

	private function get_bank_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_bank = $balance['BC']['amount'];
	}
	
	private function get_petty_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_petty = $balance['PC']['amount'];
	}
		
	private function transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions();
		
		foreach($all_transactions as $rows){

			$removeKeys = array("AccNo","AccText","AccGrp","Cost");
			$transactions_container[$rows['VNumber']]['details'] = array_diff_key($rows, array_flip($removeKeys));
			
			$transactions_container[$rows['VNumber']][$rows['AccGrp']][$rows['AccNo']] = $rows['Cost'];
			
		}
		
		return $transactions_container;
	}
	

	 function voucher_transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions_for_voucher();
		
		$cnt = 0;
		
		foreach($all_transactions as $rows){
			$removeKeys = array("AccNo","AccText","AccGrp","Qty","Details","UnitCost","Cost","scheduleID","civaCode");
			$transactions_container[$rows['VNumber']]['details'] = array_diff_key($rows, array_flip($removeKeys));
			
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Qty'] = $rows['Qty'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Details'] = $rows['Details'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['UnitCost'] = $rows['UnitCost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Cost'] = $rows['Cost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['AccNo'] = $rows['AccText'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['scheduleID'] = $rows['scheduleID'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['civaCode'] = $rows['civaCode'];
			$cnt ++;
		}
		
		return $transactions_container;
	}
		
	private function get_utilized_accounts(){
		/** Retrieve Income Accounts **/
		$income_accounts_rows = array_column($this->transactions(), 1);
		$income_accounts_unsort = array();
		foreach($income_accounts_rows as $value){
			foreach($value as $row_key=>$row_value){
				$income_accounts_unsort[] = $row_key;
			}
		}
		
		$income_accounts = array_unique($income_accounts_unsort);
		sort($income_accounts);
		
		/** Retrieve Expense Accounts **/
		$expense_accounts_rows = array_column($this->transactions(), 0);
		$expense_accounts_unsort = array();
		foreach($expense_accounts_rows as $value){
			foreach($value as $row_key=>$row_value){
				$expense_accounts_unsort[] = $row_key;
			}
		}
		
		$expense_accounts = array_unique($expense_accounts_unsort);
		sort($expense_accounts);
		
		/** All accounts **/
		//$all_utilized_accounts = array_merge($income_accounts,$expense_accounts);
		$all_utilized_accounts = array("1"=>$income_accounts,"0"=>$expense_accounts);
		
		return $all_utilized_accounts;
	}
	
	function linear_accounts_utilized(){
		$all_transactions = $this->get_current_month_transactions();
		
		$linear_accounts_text = array();
		
		foreach($all_transactions as $row){
			$linear_accounts_text[$row['AccNo']] = $row['AccText']; 
		}
		
		return $linear_accounts_text;
	}
	
	private function construct_journal(){
		$this->get_start_bank();
		$this->get_start_petty();
		$accounts = $this->get_utilized_accounts();
		$transactions =  $this->transactions();
		$records = array();
		$i = 0;
		foreach($transactions as $value){
			$records[$i]['details'] = $value['details'];
			if(isset($value[1])){
				
				foreach($accounts[1] as $account){
					if(isset($value[1][$account])){
						$records[$i]['income_spread'][$account] = $value[1][$account];
					}else{
						$records[$i]['income_spread'][$account] = 0;
					}
					
				}
				
				/** Bnak and Cash Columns **/
				
				$records[$i]['bank']['bank_inc'] = 0;
				$records[$i]['bank']['bank_exp'] = 0;
				$records[$i]['bank']['bank_bal'] = 0;
				
				$records[$i]['petty']['petty_inc'] = 0;
				$records[$i]['petty']['petty_exp'] = 0;
				$records[$i]['petty']['petty_bal'] = 0;
				
				if($value['details']['VType'] == 'CR'){
					$records[$i]['bank']['bank_inc'] = array_sum($value[1]);	
					$this->opening_bank+=array_sum($value[1]);
				}
				
				$records[$i]['bank']['bank_bal'] = $this->opening_bank; 
				$records[$i]['petty']['petty_bal'] = $this->opening_petty; 
				
				
				/**Append Expense accounts **/
				foreach($accounts[0] as $account){ $records[$i]['expense_spread'][$account] = 0;}
				
				
			}else{
				/**Append Income accounts **/	
				foreach($accounts[1] as $account){ $records[$i]['income_spread'][$account] = 0;}
				
				/** Bnak and Cash Columns **/
				$records[$i]['bank']['bank_inc'] = 0;
				$records[$i]['bank']['bank_exp'] = 0;
				$records[$i]['bank']['bank_bal'] = 0;
				
				$records[$i]['petty']['petty_inc'] = 0;
				$records[$i]['petty']['petty_exp'] = 0;
				$records[$i]['petty']['petty_bal'] = 0;
				
				if(($value['details']['VType'] == 'CHQ' || $value['details']['VType'] == 'BCHG') && isset($value[0])){
					$records[$i]['bank']['bank_exp'] = array_sum($value[0]);
					$this->opening_bank-=array_sum($value[0]);
					
				}elseif($value['details']['VType'] == 'CHQ' && isset($value[3])){
						
					$records[$i]['bank']['bank_exp'] = array_sum($value[3]);	
					$records[$i]['petty']['petty_inc'] = array_sum($value[3]);	
					
					$this->opening_bank-=array_sum($value[3]);
					$this->opening_petty+=array_sum($value[3]);
					
				}elseif($value['details']['VType'] == 'PC'){
					$records[$i]['petty']['petty_exp'] = array_sum($value[0]);
					$this->opening_petty-=array_sum($value[0]);
					
				}elseif($value['details']['VType'] == 'PCR' && isset($value[3])){
					$records[$i]['petty']['petty_exp'] = array_sum($value[4]);
					$this->opening_petty-=array_sum($value[4]);
				}
				
				$records[$i]['bank']['bank_bal'] = $this->opening_bank; 
				$records[$i]['petty']['petty_bal'] = $this->opening_petty; 
				
				
				foreach($accounts[0] as $account){
					if(isset($value[0][$account])){
						$records[$i]['expense_spread'][$account] = $value[0][$account];
					}else{
						$records[$i]['expense_spread'][$account] = 0;
					}
					
				}
			}
			
			$i++;
		}
		
		return $records;
	}

	/** Cash Journal Getters (Used in Journal Related Pre-renders) - Start**/

	private function get_bank_deposit(){
		$bank = 0;	
		foreach($this->construct_journal() as $row){
			$bank+=$row['bank']['bank_inc'];
		}
		
		return $bank;
	}

	private function get_bank_payment(){
		$bank = 0;	
		foreach($this->construct_journal() as $row){
			$bank+=$row['bank']['bank_exp'];
		}
		
		return $bank;
	}	


	private function get_cash_deposit(){
		$cash= 0;	
		foreach($this->construct_journal() as $row){
			$cash+=$row['petty']['petty_inc'];
		}
		
		return $cash;
	}

	private function get_cash_payment(){
		$cash = 0;	
		foreach($this->construct_journal() as $row){
			$cash+=$row['petty']['petty_exp'];
		}
		
		return $cash;
	}

	
	
	private function readable_labels(){
		
		return $labels = array(
			"VType"=>$this->l("voucher_type"),
			"TDate"=>$this->l('date'),
			
			"VNumber"=>$this->l("voucher_number"),
			"Payee"=>$this->l("payee"),
			"Address"=>$this->l("address"),
			"TDescription"=>$this->l("details"),
			"bank_inc"=>$this->l("bank_deposits"),
			"bank_exp"=>$this->l("bank_payments"),
			"bank_bal"=>$this->l("bank_balance"),
			"petty_inc"=>$this->l("cash_deposits"),
			"petty_exp"=>$this->l("cash_payments"),
			"petty_bal"=>$this->l("cash_balance"),
			"ChqNo"=>$this->l("cheque_number")
		);
		
		
	}
	
	/** Cash Journal Getters (Used in Journal Related Pre-renders) - Start**/
	
	/** Data Construction - End **/
	

	
	/**
	 * Pre-render methods:
	 * These are private methods that set the variables to be used in a particular view i.e.
	 * - pre_render_journal
	 * - pre_render_view_voucher
	 * - pre_render_print_voucher
	 * - pre_render_create_voucher
	 */
	
	private function pre_render_show_journal(){
		
		if(count($this->construct_journal())>0){
			$data['records'] =  $this->construct_journal();
		
			$data['end_bank_balance'] = $this->opening_bank;
			$data['end_petty_balance'] = $this->opening_petty;
			 		
			$data['opening_bank_balance'] = $this->get_bank_opening_balance();
			$data['opening_petty_balance'] = $this->get_petty_opening_balance();
	 		
			$data['total_bank_deposit'] = $this->get_bank_deposit();
			$data['total_bank_payment'] = $this->get_bank_payment();
	 		
			$data['total_cash_deposit'] = $this->get_cash_deposit();
			$data['total_cash_payment'] = $this->get_cash_payment();
	 		
			$data['labels'] = $this->readable_labels();
	 		
			$data['all_accounts_labels'] = $this->linear_accounts_utilized();
			
			$data['transacting_month'] = $this->get_transacting_month();
		}
		
		
		$data['view'] = count($this->construct_journal())>0?$this->get_view():"error";
 		
 		return $data;
		
	}
	
	private function pre_render_show_voucher(){
		
		if($this->CI->uri->segment(8)){
			$vouchers = $this->voucher_transactions();		
			$data['voucher'] = $vouchers[$this->CI->uri->segment(8)];
		}
		
		$data['view'] = $this->CI->uri->segment(8)?$this->get_view():"error";
		
		return $data;
	}

	private function pre_render_print_vouchers(){
		
		if($this->CI->input->post()){
			$data['selected_vouchers'] = $this->CI->input->post();
			$data['all_vouchers'] = $this->voucher_transactions();	
			$view = $this->get_view();
		}
		
		$data['view'] = $this->CI->input->post()?$this->get_view():"error";
		
		return $data;
	}
	
	private function pre_render_create_voucher(){
		
		$data['success'] = "";
		if(isset($_POST) && sizeof($_POST)>0){
			$data['success'] = $this->insert_voucher_to_database($_POST);
		}
		
		$data['accounts'] = $this->group_accounts();
		$data['approved_budget'] = $this->budget_grouped_items();
		$data['voucher_number'] = $this->get_next_voucher_number();
		$data['voucher_date_range'] = $this->get_voucher_date_picker_control();
		$data['cheques_utilized'] = $this->get_coded_cheques_utilized();
		$project_details = $this->get_project_details();
		$data['bank_code'] = $project_details->bankID;
		$data['civ_accounts'] = $this->accounts_with_open_icp_civs();// To be removed
		
		$data['view'] = $this->get_view();
		
		return $data;
	}

	function pre_render_ajax_get_cheque_details(){
		
		echo json_encode($this->basic_model->get_cheque_details($_POST['icpNo'],$_POST['ChqNo']));
				
	}

	/**
	 * Render 
	 * 
	 */
	
	public function render(){
		
		$this->CI->benchmark->mark('profiler_start');
		
		$this->_initialize_variables();
		$this->_load_language();
		
		$preference_data = array();
		
		if($this->CI->uri->segment(7)){
			$start_date = date("Y-m-01",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_start_date())));
			$end_date = date("Y-m-t",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_end_date())));
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
		}

		$preference_data = call_user_func(array($this, "pre_render_".$this->get_view()));
		
		$this->set_view($preference_data);
		
		return $this->get_layout(); 
	}
	
}

