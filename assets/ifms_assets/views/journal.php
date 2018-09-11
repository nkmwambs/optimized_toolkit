<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<hr/>
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		<div class="well" style="text-align: center;"><?=$this->get_project_id();?> Cash Journal
			for the month ending <?=date("tS F Y",strtotime($this->get_end_date()));?></div>
	</div>	
</div>

<?php
//print_r($segments);
?>

<hr />
<div class="row">
	<div class="col-sm-offset-1 col-sm-5">
		<div class="btn btn-default">Show Spread</div>
		<div class="btn btn-default" id="select_btn">Select All Vouchers</div>
		<div class="btn btn-default">Print Selected Vouchers</div>
				
	</div>


	<div class="col-sm-5 col-sm-offset-1">
		<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
			<div class="form-group">
				<label class="col-sm-3 control-label">Scroll Months: </label>	
					<div class="col-sm-5">
						<input type="text" id="spinner" name="spinned_months" value="<?php echo isset($segments[4])?0:0;?>" readonly="readonly"/>
									
					</div>
				<div class="col-sm-4">
					<button class="btn btn-default" id="spinner_btn">Go</button>
				</div>	
			</div>
		</form>
	</div>
</div>
<hr />		

<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1" id="display">
		<?php
			if(!isset($records[0])){
				include("error.php");		
			}else{
		?>
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
					<th colspan="<?=count($details);?>">Balance Carried Forward: </th>
					<th colspan="2">Bank:</th>
					<th><?=number_format($opening_bank_balance,2);?></th>
					
					<th colspan="2">Cash:</th>
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
									<td><a href="<?=base_url().$state_info['controller'].'/'.$state_info['method'].'/show_voucher/'.$state_info['project'];?>/<?php echo $segments[5];?>/<?php echo strtotime(date("Y-m-t",$segments[5]));?>/<?=$col;?>" id="voucher_<?=$col;?>" class='btn btn-default'><input type='checkbox' class="check_voucher"/><?=$col;?></a></td>
							
							<?php
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
		<?php
		}
		?>
	</div>
</div>
<hr />
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		<?php echo "Time Elapsed to load page: ".$this->get_layout()->profiler." seconds";?>
	</div>
</div>

<hr />
<?php
//echo $this->start_date;
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		//alert($("#spinner").val());
		var url = "<?=base_url();?><?=$state_info['controller'];?>/<?=$state_info['method'];?>/scroll_journal/"+$("#spinner").val();
		$("#scroll").prop("method","POST");
		$("#scroll").prop("action",url);
		$("#scroll").submit();
		
		//ev.preventDefault();
	});
</script>	
