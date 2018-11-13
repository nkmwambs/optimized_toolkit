<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
//print_r($this->testing());
?>
<hr/>

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="well" style="text-align: center;"><?=$this->get_project_id()." ".$this->l('cash_journal_title')." ".date("j<\s\u\p>S</\s\u\p> F Y",strtotime("last day of this month",$this->get_start_date_epoch()));?></div>
	</div>	
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<ul class="nav nav-pills">
			<li>
				<div class="btn-group left-dropdown">
								<a class="btn btn-default" href="<?=$this->get_url(array("assetview"=>"show_report","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('report');?></a>
								<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?=$this->get_url(array("assetview"=>"show_fundbalance","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('fund_balance_report')?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_proofcash","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('proof_of_cash');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_outstandingcheques","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('outstanding_cheques');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_transitdeposit","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('transit_deposit');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_clearedcheques","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('cleared_cheques');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_cleareddeposits","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('cleared_deposits');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_bankreconcile","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('bank_reconcile');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>"show_budgetvariance","lib"=>"report","scroll"=>$this->get_scroll()));?>"><?=$this->l('expense_report');?></a></li>
								</ul>
				</div>
				
			</li>
			<li>
				<div class="btn-group left-dropdown">
					<a class="btn btn-default" href="<?=$this->get_url(array("assetview"=>"show_budget","lib"=>"budget"));?>"><?=$this->l('budget');?></a>
					<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								
								<ul class="dropdown-menu dropdown-green" role="menu">
									<li><a href="<?=$this->get_url(array("assetview"=>'show_budget_summary',"lib"=>'budget'));?>"><?=$this->l('budget_summary');?></a></li>
									<li class="divider"></li>
									<li><a href="<?=$this->get_url(array("assetview"=>'show_budget_schedules',"lib"=>'budget'));?>"><?=$this->l('budget_schedules');?></a></li>
									
								</ul>
							</div>
				</div>
			</li>
		</ul>
	</div>

<hr />

<div class="<?=$this->get_column_size();?>">
	
	<div class="row">
		<div class="col-xs-12">
			<ul class="nav nav-pills">
				<li><a href="#" class="btn btn-default btn-icon hidden-print pull-left" id="select_btn"><?=$this->l('select_all_vouchers');?> <i class="entypo-mouse"></i></a>
				</li>
				<li><a href="#"  class="btn btn-default btn-icon hidden-print pull-left" id="print_vouchers"><?=$this->l('print_selected_vouchers')?><i class="entypo-print"></i></a>
				</li>
				<?php 
					if($this->is_transacting_month){
				?>
					<!-- href="<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>?assetview=create_voucher&project=<?=$this->get_project_id();?>&startdate<?=$transacting_month['start_date'];?>&enddate=<?=$transacting_month['end_date'];?>&lib=journal"><?=$this->l('new_voucher');?> <i class="entypo-list-add"></i> -->
					<li><a class="btn btn-default btn-icon hidden-print pull-left"  
						href="<?=$this->get_url(array("assetview"=>"create_voucher"));?>"> <?=$this->l('new_voucher');?> <i class="entypo-list-add"></i> 
					</a>
					</li>		
				<?php
					}
					
				?>
				<li>
				<a href="<?=$this->get_url(array('assetview'=>'cheque_book'));?>" class="btn btn-default btn-icon hidden-print pull-left" id=""><?=$this->l('add_cheque_booklet');?> <i class="entypo-book"></i></a>
					</li>		
			</ul>

		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-xs-6">
			<?php $this->show_spinner("month");?>
		</div>
	</div>
	<hr />
</div>	
		

<div class="row">
	<div class="<?=$this->get_column_size();?>" id="display">
		<form id="frm_vouchers">

		<table class="table table-striped" id="ifms_journal_view">
			<thead>
				<tr>
					<?php
						//Creating columns of the journal table
						$details = array_keys($records[0]['details']);
						$bank = array_keys($records[0]['bank']);
						$petty = array_keys($records[0]['petty']);
						$income = 0;
						$expense = 0;
						if(isset($records[0]['income_spread'])) $income =  array_keys($records[0]['income_spread']);
						if($records[0]['expense_spread']) $expense = array_keys($records[0]['expense_spread']);
						
						foreach($details as $col){ echo "<th nowrap='nowrap'>".$labels[$col]."</th>";}
						foreach($bank as $col){ echo "<th nowrap='nowrap'>".$labels[$col]."</th>";}
						foreach($petty as $col){ echo "<th nowrap='nowrap'>".$labels[$col]."</th>";}
						if(isset($records[0]['income_spread'])) foreach($income as $col){ echo "<th nowrap='nowrap'>".$all_accounts_labels[$col]."</th>";}
						if($records[0]['expense_spread']) foreach($expense as $col){ echo "<th nowrap='nowrap'>".$all_accounts_labels[$col]."</th>";}
					?>
				</tr>
				<tr>
					<th colspan="<?=count($details);?>"><?=$this->l('balance_carried_forward');?>: </th>
					<th colspan="2"><?=$this->l('bank');?>:</th>
					<th><?=number_format($opening_bank_balance,2);?></th>
					
					<th colspan="2"><?=$this->l('cash');?>:</th>
					<th><?=number_format($opening_petty_balance,2);?></th>
					
					<th colspan="<?=count($income);?>" rowspan="" style="border-left:1px black solid;border-right:1px black solid;"><?=$this->l('income');?></th>
					
					<th colspan="<?=count($expense);?>" rowspan="" style="border-left:1px black solid;border-right:1px black solid;"><?=$this->l('expense');?></th>
					
				</tr>
				
				<tr>
					<th colspan="<?=count($details);?>"><?=$this->l('end_month_balance');?>: </th>
					<th><?=number_format($total_bank_deposit,2);?></th>
					<th><?=number_format($total_bank_payment,2);?></th>
					<th><?=number_format($end_bank_balance,2);?></th>
					
					<th><?=number_format($total_cash_deposit,2);?></th>
					<th><?=number_format($total_cash_payment,2);?></th>
					<th><?=number_format($end_petty_balance,2);?></th>
					
					<?php
						foreach($income as $cell){
					?>
						<th><?=number_format($sum_incomes[$cell],2);?></th>
					<?php
						}
					?>
					
					<?php
						foreach($expense as $cell){
					?>
						<th><?=number_format($sum_expenses[$cell],2);?></th>
					<?php
						}
					?>									
					
				</tr>
			</thead>
			<tbody>
				<?php
										
					for($i=0;$i<count($records);$i++){
							
						$details_values = $records[$i]['details'];
						$bank_values = $records[$i]['bank'];
						$petty_values = $records[$i]['petty'];
						if(isset($records[$i]['income_spread'])) $income_values = $records[$i]['income_spread'];
						if(isset($records[$i]['expense_spread'])) $expense_values = $records[$i]['expense_spread'];
					
						echo "<tr>";
						//Creating details columns
							foreach($details_values as $key=>$col){
								if($key == "VNumber"){
									$trackable = "";
										if(in_array($col, $trackable_vouchers)){
											$trackable = "fa fa-binoculars";
										}
										
							?>		
									<!-- <td><a href="<?=base_url().$this->get_controller().'/'.$this->get_method().'?assetview=show_voucher&project='.$this->get_project_id();?>&startdate=<?php echo $this->get_start_date_epoch();?>&enddate=<?php echo $this->get_end_date_epoch();?>&scroll=<?php echo $this->get_scroll();?>&voucher=<?=$col;?>&lib=journal" id="voucher_<?=$col;?>" class='btn btn-<?=$btn_color;?>'><input type='checkbox' name="vouchers[]" value="<?=$col;?>" class="check_voucher"/><?=$col;?></a></td> -->							
									<td><a href="<?=$this->get_url(array("assetview"=>'show_voucher','voucher'=>$col));?>" id="voucher_<?=$col;?>" class='btn btn-default btn-icon icon-right'><input type='checkbox' name="vouchers[]" value="<?=$col;?>" class="check_voucher"/><?=$col;?> <i style="color:red;" class="<?=$trackable;?>"></i></a> </td>
							<?php
								}elseif($key == "ChqNo"){
									$chq = explode("-",$col);
									echo "<td>".$chq[0]."</td>";
								}else{
									echo "<td>".$col."</td>";
								}
								 
							}
							
							//Creating bank columns
							foreach($bank_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							
							//Creating Petty Cash columns
							foreach($petty_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							
							//Creating income spread columns
							$j = 0;
							if(isset($records[$i]['income_spread'])) {
							
								$cnt =  count($income_values)-1;
								
								foreach($income_values as $col){
										
									$style = '';
									
									if($j == $cnt){ $style = 'style="border-right:1px black solid;"';}
									if($j == 0){ $style = 'style="border-left:1px black solid;"';}
									
									 echo "<td ".$style.">".number_format($col,2)."</td>";
									
									$j++;
								}
							}
							
							//Creating expense spread columns
							
							if(isset($records[$i]['expense_spread'])){	
								foreach($expense_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							}
						echo "</tr>";	
					}
				?>
								
			</tbody>
		</table>

		</form>
	</div>
</div>
<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<?php echo "Time Elapsed to load page: ".$this->get_layout()->profiler." seconds";?>
	</div>
</div>

<hr />


<script>

$("#select_btn").click(function(){
	$(".check_voucher").each(function(){
		$(this).prop( "checked", true );
	});
});	
	
$("#print_vouchers").click(function(){
		var url = '<?=$this->get_url(array('assetview'=>'print_vouchers'));?>';
		$("#frm_vouchers").prop("method","POST");
		$("#frm_vouchers").prop("action",url);
		$("#frm_vouchers").submit();
});	


</script>	
