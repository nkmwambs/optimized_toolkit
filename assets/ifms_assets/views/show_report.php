<hr />
<?php
	//print_r($this->group_special_accounts_transactions());
	//echo date('Y-m-d',strtotime('first day of previous month',$this->get_start_date_epoch()));
?>
<style>
	.align-right{
		text-align:right;
	}
</style>

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div style="text-align:center;font-weight: bolder;" class="well well-lg">
			<?=$this->get_project_id();?> <br/> Monthly Financial Report <br/> As at <?=date("j<\s\u\p>S</\s\u\p> F Y",$this->get_end_date_epoch());?>
		</div>
	</div>
</div>

<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
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

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<table class="table table-striped table-bordered" id="report">				
			<theading>
				<tr><th colspan="5">Fund Balance Report</th></tr>
					<tr>
						<th>Account</th>
						<th>Beginining Balance</th>
						<th>Month Income</th>
						<th>Month Expense</th>
						<th>Ending Balance</th>
					</tr>
			</theading>
			<tbody>
				<?php
					foreach($fund_balances as $row){
				?>
					<tr>
						<td><?=$row['AccText'];?> - <?=$row['AccName'];?></td>
						<td class="align-right"><?=number_format($row['Opening'],2);?></td>
						<td class="align-right"><?=number_format($row['Income'],2);?></td>
						<td class="align-right"><?=number_format($row['Expense'],2);?></td>
						<td class="align-right"><?=number_format($row['Ending'],2);?></td>
					</tr>
				<?php
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<th>Totals</td>
					<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Opening')),2);?></td>
					<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Income')),2);?></td>
					<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Expense')),2);?></td>
					<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Ending')),2);?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<table class="table table-striped table-bordered">
			<theading>
				<tr><th colspan="2">Proof Of Cash</th></tr>
				<tr>
					<th>Cash At Bank</th>
					<td class="align-right"><?=number_format($bank_balance,2);?></td>
				</tr>
			</theading>
			<tbody>
				<tr>	
					<th>Cash At Hand</th>
					<td class="align-right"><?=number_format($petty_balance,2);?></td>
				</tr>	
			</tbody>
			<tfoot>
				<tr>
					<th>Total</th><th class="align-right"><?=number_format($sum_cash,2);?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>


<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		//alert($("#spinner").val());
		var url = "<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>/show_report/<?=$this->get_project_id();?>/<?=$this->get_start_date_epoch();?>/<?=$this->get_end_date_epoch();?>/"+$("#spinner").val();
		$("#scroll").prop("method","POST");
		$("#scroll").prop("action",url);
		$("#scroll").submit();
		
		//ev.preventDefault();
	});
	
</script>