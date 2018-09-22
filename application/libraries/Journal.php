<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Journal_Layout{

	protected $CI;
	protected $view_as_string;
	protected $default_javascript_path;
	protected $default_css_path;
	protected $default_view_path;
	protected $default_assets_path 		= 'assets/ifms_assets';
	protected $css_files				= array();
	protected $js_files					= array();
	protected $state_data = array();
	protected $profiler;

	function __construct($params = array()){
		/** Initialize Codeigniter Instance **/	
		
		$this->CI=& get_instance();
		
		/** Initialize default paths */
		$this->default_javascript_path				= $this->default_assets_path.'/js/';
		$this->default_css_path						= $this->default_assets_path.'/css/';
		$this->default_view_path					= $this->default_assets_path.'/views/';
		
		/** Loading Config and Helpers **/
		$this->CI->config->load('ifms');
		$this->CI->load->helper('url');
		$this->CI->load->database();
				
	}
	
/**
 *  It receives a file name with a view and the data array of what is intended to be used in the view
 *
 * @param String view : File name withouth the file extension. Assuming that its a PHP file
 * @param Array data : Array of dat ato be used in the view
 * return void
 */
	
	protected function set_view($view,$data){
		
		//Create variable from the data array
		extract($data);
		
		ob_start();
		
		include ($this->default_view_path.$view.".php");
		
		$view = ob_get_contents();
		
		ob_end_clean();
		
		
		$this->view_as_string .= $view;
	}
	
	private function set_css_files($css_file){
		$this->css_files[sha1($css_file)] = base_url().$css_file;
	}
	
	private function set_js_files($js_file){
		$this->js_files[sha1($js_file)] = base_url().$js_file;		
	}
	
	protected function load_js()
	{
		$this->set_js_files($this->default_javascript_path.'/jquery-3.3.1.min.js');
		$this->set_js_files($this->default_javascript_path.'/bootstrap.min.js');
		$this->set_js_files($this->default_javascript_path.'/jquery.dataTables.min.js');
		$this->set_js_files($this->default_javascript_path.'/buttons.bootstrap.js');
		$this->set_js_files($this->default_javascript_path.'/dataTables.bootstrap.min.js');
		$this->set_js_files($this->default_javascript_path.'/jquery-ui.min.js');
		$this->set_js_files($this->default_javascript_path.'/printThis.js');
		$this->set_js_files($this->default_javascript_path.'/datepicker/js/bootstrap-datepicker.min.js');
		$this->set_js_files($this->default_javascript_path.'/custom.js');
	}
	
	protected function load_css(){
		$this->set_css_files($this->default_css_path.'/bootstrap.css');
		$this->set_css_files($this->default_css_path.'/dataTables.bootstrap.css');
		$this->set_css_files($this->default_css_path.'/custom.css');
		$this->set_css_files($this->default_css_path.'/jquery-ui-themes/base/jquery-ui.min.css');
		$this->set_css_files($this->default_css_path.'/jquery-ui-themes/base/theme.css');
		$this->set_css_files($this->default_css_path.'/font-icons/font-awesome/css/font-awesome.css');
		$this->set_css_files($this->default_css_path.'/font-icons/entypo/css/entypo.css');
		$this->set_css_files($this->default_css_path.'/font-icons/font-awesome/css/font-awesome.css');
		$this->set_css_files($this->default_css_path.'/datepicker/js/bootstrap-datepicker.min.css');
	}
	
	
	private function get_css_files()
	{
		$this->load_css();	
		return $this->css_files;
	}

	private function get_js_files()
	{
		$this->load_js();	
		return $this->js_files;
	}
	
		
	protected function get_layout(){
		$js_files = $this->get_js_files();
		$css_files =  $this->get_css_files();
		
		$this->CI->benchmark->mark('profiler_end');
		
		$this->profiler = $this->CI->benchmark->elapsed_time('profiler_start', 'profiler_end');
		
		return (object) array(
					'js_files' => $js_files,
					'css_files' => $css_files,
					'output' => $this->view_as_string,
					'profiler'=>$this->CI->benchmark->elapsed_time('profiler_start', 'profiler_end'),
		);
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
	public $basic_model = null;		
	
	function __construct(){
		parent::__construct();
		$this->CI->load->model("Journal_model");
		$this->basic_model = new Journal_model();
		
		/**Initialization**/
		$this->icpNo = $this->CI->uri->segment(4);
		$this->start_date = "2018-06-01";//date("Y-m-d",$this->CI->uri->segment(5));
		$this->end_date = "2018-06-30";//date("Y-m-d",$this->CI->uri->segment(6));
	}
		
	public function set_project_id($project_id=""){
		$this->icpNo = $project_id;
	}

	public function set_date($params=array()){
		$this->start_date = $params['START_DATE'];
		$this->end_date = $params['END_DATE'];
	}
	
	function opening_cash_balance(){
		return $this->basic_model->start_cash_balance($this->icpNo,$this->start_date);
	}
	
	function get_start_bank(){
		$balance = $this->opening_cash_balance();
		return $this->opening_bank = $balance['BC']['amount'];
	}
	
	function get_start_petty(){
		$balance = $this->opening_cash_balance();
		return $this->opening_petty = $balance['PC']['amount'];
	}

	function get_bank_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_bank = $balance['BC']['amount'];
	}
	
	private function get_petty_opening_balance(){
		$balance = $this->opening_cash_balance();
		return $this->start_petty = $balance['PC']['amount'];
	}	
	
	protected function get_project_id(){
		return $this->icpNo;
	}
	

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
	
	protected function get_controller(){
		return $this->CI->router->fetch_class();;
	}
	
	protected function get_method(){
		return $this->CI->router->fetch_method();;
	}
	
	protected function get_first_extra_segment(){
		return $this->CI->uri->segment(7)?$this->CI->uri->segment(7):"";
	}
	
	protected function get_second_extra_segment(){
		return $this->CI->uri->segment(8)?$this->CI->uri->segment(8):"";
	}
	
	private function get_current_voucher(){
		return (array)$this->basic_model->get_icp_max_voucher($this->get_project_id());
	}
	
	private function get_current_financial_report(){
		return (array)$this->basic_model->get_max_report_submitted($this->get_project_id());
	}
	
	protected function get_current_financial_report_date(){
		extract($this->get_current_financial_report());
		
		return $closureDate;
	} 
	
	protected function get_current_financial_report_validated(){
		extract($this->get_current_financial_report());
		
		return $allowEdit == 1?false:true;
	} 
	
	protected function get_current_financial_report_submitted(){
		extract($this->get_current_financial_report());
		
		return $submitted == 1?true:false;
	}
	
	protected function get_current_financial_report_is_initial(){
		extract($this->get_current_financial_report());
		
		return $systemOpening == 1?true:false;
	}
	
	protected function get_current_financial_report_timestamp(){
		extract($this->get_current_financial_report());
		
		return strtotime($stmp);
	}
	
	protected function get_current_voucher_date(){
		extract($this->get_current_voucher());
		
		return $TDate;
	}
	
	protected function get_current_voucher_number(){
		extract($this->get_current_voucher());
		
		return $VNumber;
	}
	
	protected function get_current_voucher_timestamp(){
		extract($this->get_current_voucher());
		
		return $unixStmp;
	}
	
	protected function get_current_voucher_fy(){
		extract($this->get_current_voucher());
		
		return $Fy;
	}
	
	protected function get_transacting_month(){
		$current_voucher_date = strtotime($this->get_current_voucher_date());
		$current_report_date = strtotime($this->get_current_financial_report_date());
		
		$params = array();
		
		if($current_voucher_date > $current_report_date){
			$params['start_date'] = strtotime(date("Y-m-01",$current_voucher_date));
			$params['end_date'] = strtotime(date("Y-m-t",$current_voucher_date));
		}else{
			
		}
		
		return $params;
	}
	
	
	/**
	 * This is a getter that retrieves records from the database to popluate the Cash Journal
	 */
	
    private function get_current_month_transactions()
    {		
		return $this->basic_model
		->get_journal_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
	
	/**
	 * This is a getter that retrieve records from the database to populate vouchers
	 */
    public function get_current_month_transactions_for_voucher()
    {	
		return $this->basic_model
		->get_voucher_transactions($this->icpNo,$this->start_date,$this->end_date);
    }
	
	function transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions();
		
		foreach($all_transactions as $rows){
			$transactions_container[$rows['VNumber']]['details'] = $rows;
			unset($transactions_container[$rows['VNumber']]['details']['AccNo']);
			unset($transactions_container[$rows['VNumber']]['details']['AccText']);
			unset($transactions_container[$rows['VNumber']]['details']['AccGrp']);
			unset($transactions_container[$rows['VNumber']]['details']['Cost']);
			
			$transactions_container[$rows['VNumber']][$rows['AccGrp']][$rows['AccNo']] = $rows['Cost'];
			
		}
		
		return $transactions_container;
	}

	private function voucher_transactions(){
		$transactions_container = array();
		$all_transactions =  $this->get_current_month_transactions_for_voucher();
		
		$cnt = 0;
		
		foreach($all_transactions as $rows){
			$transactions_container[$rows['VNumber']]['details'] = $rows;
			unset($transactions_container[$rows['VNumber']]['details']['AccNo']);
			unset($transactions_container[$rows['VNumber']]['details']['AccText']);
			unset($transactions_container[$rows['VNumber']]['details']['AccGrp']);
			unset($transactions_container[$rows['VNumber']]['details']['Qty']);
			unset($transactions_container[$rows['VNumber']]['details']['Details']);
			unset($transactions_container[$rows['VNumber']]['details']['UnitCost']);
			unset($transactions_container[$rows['VNumber']]['details']['Cost']);

			
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Qty'] = $rows['Qty'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Details'] = $rows['Details'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['UnitCost'] = $rows['UnitCost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['Cost'] = $rows['Cost'];
			$transactions_container[$rows['VNumber']]['body'][$cnt]['AccNo'] = $rows['AccText'];
			
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
			"VType"=>"Voucher Type",
			"TDate"=>"Date",
			"VNumber"=>"Voucher Number",
			"Payee"=>"Payee/ Source",
			"Address"=>"Address",
			"TDescription"=>"Details",
			"bank_inc"=>"Bank Deposits",
			"bank_exp"=>"Bank Payments",
			"bank_bal"=>"Bank Balance",
			"petty_inc"=>"Cash Deposits",
			"petty_exp"=>"Cash Payments",
			"petty_bal"=>"Cash Balance",
			"ChqNo"=>"Cheque Number"
		);
		
		
	}
	
	/**
	 * Pre-render methods:
	 * These are private methods that set the variables to be used in a particular view i.e.
	 * - pre_render_journal
	 * - pre_render_view_voucher
	 * - pre_render_print_voucher
	 * - pre_render_create_voucher
	 */
	
	private function pre_render_journal(){
		
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
		
		//$data['segments'] = $this->CI->uri->segment_array();
		
		$data['view'] = "journal";
 		
 		return $data;
		
	}
	
	private function pre_render_view_voucher(){
		
		$vouchers = $this->voucher_transactions();
		
		$voucher_number = $this->CI->uri->segment(7);
		
		$data['view'] = "voucher_view";
		
		$data['segments'] = $this->CI->uri->segment_array();
		
		$data['voucher'] = $vouchers[$voucher_number];
		
		return $data;
	}

	private function pre_render_print_vouchers(){
		$data['view'] = "print_vouchers";
		
		//$data['segments'] = $this->CI->uri->segment_array();
		
		$data['selected_vouchers'] = $this->CI->input->post();
		
		$data['all_vouchers'] = $this->voucher_transactions();	
		
		return $data;
	}
	
	private function pre_render_create_voucher(){
		$data['view'] = "create_voucher";
		$data['segments'] = $this->CI->uri->segment_array();
		return $data;
	}

	/**
	 * Render Method:
	 * Its set the appropriate pre-render based on the 3rd segment of the url, 
	 * reset the set_date() method and render output to the Controller
	 * 
	 */
	
	public function render(){
		
		$this->CI->benchmark->mark('profiler_start');
		
		$preference_data = array();
		
		if($this->CI->uri->segment(3) == "show_voucher"){
		
			if($this->CI->uri->segment(8)){
				$start_date = date("Y-m-01",strtotime($this->CI->uri->segment(8)." months",strtotime($this->get_start_date())));
				$end_date = date("Y-m-t",strtotime($this->CI->uri->segment(8)." months",strtotime($this->get_end_date())));
			}else{	
				$start_date = date("Y-m-01",$this->CI->uri->segment(5));
				$end_date = date("Y-m-t",$this->CI->uri->segment(6));
			}
		
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
		
			$preference_data = $this->pre_render_view_voucher();
		
		}elseif($this->CI->uri->segment(3) == "show_journal"){
		
			$preference_data = $this->pre_render_journal();	
		
		}elseif($this->CI->uri->segment(3) == "scroll_journal"){
		
			$start_date = date("Y-m-01",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_start_date())));
			$end_date = date("Y-m-t",strtotime($this->CI->uri->segment(7)." months",strtotime($this->get_end_date())));
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
			
			$preference_data = $this->pre_render_journal();		
		
		}elseif($this->CI->uri->segment(3) == "print_vouchers"){
			
			$start_date = date("Y-m-01",$this->CI->uri->segment(5));
			$end_date = date("Y-m-t",$this->CI->uri->segment(6));
		
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
		
			$preference_data = $this->pre_render_print_vouchers();	
		
		}elseif($this->CI->uri->segment(3) == "create_voucher"){
			
			$start_date = date("Y-m-01",$this->CI->uri->segment(5));
			$end_date = date("Y-m-t",$this->CI->uri->segment(6));
		
			$this->set_date(array("START_DATE"=>date("Y-m-01",strtotime($start_date)),"END_DATE"=>date("Y-m-t",strtotime($end_date))));
		
			$preference_data = $this->pre_render_create_voucher();	
		
		}
		
		$this->set_view($preference_data['view'],$preference_data);
		
		$output = $this->get_layout();
		
		return $output;
	}
	
}

