<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<hr/>
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="well" style="text-align: center;"><?=$this->get_project_id()." ".$this->l('cash_journal_title')." ".date("j<\s\u\p>S</\s\u\p> F Y",strtotime("last day of this month",$this->get_start_date_epoch()));?></div>
	</div>	
</div>
<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url("show_report");?>">Report</a>
	</div>
</div>
<hr />

<div class="<?=$this->get_column_size();?>">
	
	<div class="row">
		<div class="col-xs-12">
			<div class="btn btn-default btn-icon icon-left hidden-print pull-left col-xs-3" id="select_btn"><?=$this->l('select_all_vouchers');?><i class="entypo-mouse"></i></div>
			<div  class="btn btn-default btn-icon icon-left hidden-print pull-left col-xs-3" id="print_vouchers"><?=$this->l('print_selected_vouchers')?><i class="entypo-print"></i></div>
			<?php 
				if($transacting_month['start_date'] == $this->get_start_date_epoch()){
			?>
				<a class="btn btn-default btn-icon icon-left hidden-print pull-left col-xs-3" href="<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>/create_voucher/<?=$this->get_project_id();?>/<?=$transacting_month['start_date'];?>/<?=$transacting_month['end_date'];?>"><?=$this->l('new_voucher');?> <i class="entypo-list-add"></i></a>		
			<?php
				}
			?>
			<a href="<?=$this->get_url('cheque_book');?>" class="btn btn-default btn-icon icon-left hidden-print pull-left col-xs-3" id=""><?=$this->l('add_cheque_booklet');?> <i class="entypo-book"></i></a>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-xs-6">
			<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
				<div class="form-group hidden-print">
					<label class="col-xs-3 pull-left control-label"><?=$this->l('scroll_months')?>: </label>	
						<div class="col-xs-5">
							<input class=" pull-left" type="text" id="spinner" name="spinned_months" value="<?php echo $this->get_first_extra_segment();?>" readonly="readonly"/>
										
						</div>
					<div class="col-xs-2">
						<button class="btn btn-default pull-left" id="spinner_btn"><?=$this->l('go');?></button>
					</div>	
				</div>
			</form>
		</div>
	</div>
</div>	
		

<div class="row">
	<div class="<?=$this->get_column_size();?>" id="display">
		<form id="frm_vouchers">

		<table class="table table-striped" id="ifms_journal_view">
			<thead>
				<tr>
					<?php
						$details = array_keys($records[0]['details']);
						$bank = array_keys($records[0]['bank']);
						$petty = array_keys($records[0]['petty']);
						$income = 0;
						$expense = 0;
						if(isset($records[0]['income_spread'])) $income =  array_keys($records[0]['income_spread']);
						if($records[0]['expense_spread']) $expense = array_keys($records[0]['expense_spread']);
						
						foreach($details as $col){ echo "<th>".$labels[$col]."</th>";}
						foreach($bank as $col){ echo "<th>".$labels[$col]."</th>";}
						foreach($petty as $col){ echo "<th>".$labels[$col]."</th>";}
						if(isset($records[0]['income_spread'])) foreach($income as $col){ echo "<th>".$all_accounts_labels[$col]."</th>";}
						if($records[0]['expense_spread']) foreach($expense as $col){ echo "<th>".$all_accounts_labels[$col]."</th>";}
					?>
				</tr>
				<tr>
					<th colspan="<?=count($details);?>"><?=$this->l('balance_carried_forward');?>: </th>
					<th colspan="2"><?=$this->l('bank');?>:</th>
					<th><?=number_format($opening_bank_balance,2);?></th>
					
					<th colspan="2"><?=$this->l('cash');?>:</th>
					<th><?=number_format($opening_petty_balance,2);?></th>
					
					<th colspan="<?=count($income);?>" rowspan="2" style="border-left:1px black solid;border-right:1px black solid;">Income</th>
					
					<th colspan="<?=count($expense);?>" rowspan="2">Expenses</th>
					
				</tr>
				
				<tr>
					<th colspan="<?=count($details);?>">End Month Balance: </th>
					<th><?=number_format($total_bank_deposit,2);?></th>
					<th><?=number_format($total_bank_payment,2);?></th>
					<th><?=number_format($end_bank_balance,2);?></th>
					
					<th><?=number_format($total_cash_deposit,2);?></th>
					<th><?=number_format($total_cash_payment,2);?></th>
					<th><?=number_format($end_petty_balance,2);?></th>
					
					
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
							foreach($details_values as $key=>$col){
								if($key == "VNumber"){
							?>		
									<td><a href="<?=base_url().$this->get_controller().'/'.$this->get_method().'/show_voucher/'.$this->get_project_id();?>/<?php echo $this->get_start_date_epoch();?>/<?php echo $this->get_end_date_epoch();?>/<?php echo $this->get_first_extra_segment();?>/<?=$col;?>" id="voucher_<?=$col;?>" class='btn btn-default'><input type='checkbox' name="vouchers[]" value="<?=$col;?>" class="check_voucher"/><?=$col;?></a></td>
							
							<?php
								}elseif($key == "ChqNo"){
									$chq = explode("-",$col);
									echo "<td>".$chq[0]."</td>";
								}else{
									echo "<td>".$col."</td>";
								}
								 
							}
							foreach($bank_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							foreach($petty_values as $col){ echo "<td>".number_format($col,2)."</td>";}
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
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		//alert($("#spinner").val());
		var url = "<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>/show_journal/<?=$this->get_project_id();?>/<?=$this->get_start_date_epoch();?>/<?=$this->get_end_date_epoch();?>/"+$("#spinner").val();
		$("#scroll").prop("method","POST");
		$("#scroll").prop("action",url);
		$("#scroll").submit();
		
		//ev.preventDefault();
	});
	
	
$("#print_vouchers").click(function(){
		var url = "<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>/print_vouchers/<?=$this->get_project_id();?>/<?=$this->get_start_date_epoch();?>/<?=$this->get_end_date_epoch();?>/<?=$this->get_first_extra_segment();?>";
	
		$("#frm_vouchers").prop("method","POST");
		$("#frm_vouchers").prop("action",url);
		$("#frm_vouchers").submit();
});	


</script>	