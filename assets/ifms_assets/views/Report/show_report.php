<hr />
<?php
	//print_r($cleared_deposits);
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
			
		<div class="tabs-vertical-env">
						
			<ul class="nav nav-tabs bordered"><!-- available classes "right-aligned" -->
				<li class="active"><a href="#funds" data-toggle="tab">Fund Balance Report</a></li>
				<li><a href="#reconcile" data-toggle="tab">Bank Reconciliation</a></li>
				<li><a href="#variance" data-toggle="tab">Budget Variance</a></li>
				<li><a href="#ratios" data-toggle="tab">Financial Ratios</a></li>
				<li><a href="#breakdown" data-toggle="tab">Expense Breakdown</a></li>
				<li><a href="#statements" data-toggle="tab">Bank Statements</a></li>
				<li><a href="#submit" data-toggle="tab">Submit</a></li>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="funds">
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
					
					<hr />
					
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
				
				<div class="tab-pane" id="reconcile">
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-striped table-bordered">
								<theading>
									<tr>
										<th colspan="6">Outstanding Cheques</th>
									</tr>
									<tr>
										<?php 
											if($transacting_month['start_date'] == $this->get_start_date_epoch()){
										?>
										<th>Action</th>
										<?php
											}
										?>
										<th>Date</th>
										<th>Voucher Number</th>
										<th>Cheque Number</th>
										<th>Description</th>
										<th>Amount</th>
									</tr>
								</theading>
								<tbody>
									<?php
										$os_total = 0;
										foreach($oustanding_cheques as $row){
									?>
										<tr>
											<?php 
												if($transacting_month['start_date'] == $this->get_start_date_epoch()){
											?>
											<td><div class="btn btn-danger">Clear</div></td>
											<?php
												}
												$os_total +=$row->Cost;
											?>
											<td><?=$row->TDate;?></td>
											<td><?=$row->VNumber;?></td>
											<?php $chqno = explode("-",$row->ChqNo);?>
											<td><?=$chqno[0];?></td>
											<td><?=$row->TDescription;?></td>
											<td><?=$row->Cost;?></td>
										</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<?php 
											$colspan = 4;
											if($transacting_month['start_date'] == $this->get_start_date_epoch()) $colspan = 5;
										?>
										<th colspan="<?=$colspan;?>">Total</th>
										<th><?=number_format($os_total,2);?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-striped table-bordered cleared-effects">
								<theading>
									<tr>
										<th colspan="6">Cleared Cheques</th>
									</tr>
									<tr>
										<?php 
											if($transacting_month['start_date'] == $this->get_start_date_epoch()){
										?>
										<th>Action</th>
										<?php
											}
										?>
										<th>Date</th>
										<th>Voucher Number</th>
										<th>Cheque Number</th>
										<th>Description</th>
										<th>Amount</th>
									</tr>
								</theading>
								<tbody>
									<?php
										$os_total = 0;
										foreach($cleared_cheques as $row){
									?>
										<tr>
											<?php 
												if($transacting_month['start_date'] == $this->get_start_date_epoch()){
											?>
											<td><div class="btn btn-danger">Undo</div></td>
											<?php
												}
												$os_total +=$row->Cost;
											?>
											<td><?=$row->TDate;?></td>
											<td><?=$row->VNumber;?></td>
											<?php $chqno = explode("-",$row->ChqNo);?>
											<td><?=$chqno[0];?></td>
											<td><?=$row->TDescription;?></td>
											<td><?=$row->Cost;?></td>
										</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<?php 
											$colspan = 4;
											if($transacting_month['start_date'] == $this->get_start_date_epoch()) $colspan = 5;
										?>
										<th colspan="<?=$colspan;?>">Total</th>
										<th><?=number_format($os_total,2);?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-striped table-bordered">
								<theading>
									<tr>
										<th colspan="5">In Transit Deposit</th>
									</tr>
									<tr>
										<?php 
											if($transacting_month['start_date'] == $this->get_start_date_epoch()){
										?>
										<th>Action</th>
										<?php
											}
										?>
										<th>Date</th>
										<th>Voucher Number</th>
										<th>Description</th>
										<th>Amount</th>
									</tr>
								</theading>
								<tbody>
									<?php
										$os_total = 0;
										foreach($deposit_transit as $row){
									?>
										<tr>
											<?php 
												if($transacting_month['start_date'] == $this->get_start_date_epoch()){
											?>
											<td><div class="btn btn-danger">Clear</div></td>
											<?php
												}
												$os_total +=$row->Cost;
											?>
											<td><?=$row->TDate;?></td>
											<td><?=$row->VNumber;?></td>
											<td><?=$row->TDescription;?></td>
											<td><?=$row->Cost;?></td>
										</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<?php 
											$colspan = 3;
											if($transacting_month['start_date'] == $this->get_start_date_epoch()) $colspan = 4;
										?>
										<th colspan="<?=$colspan;?>">Total</th>
										<th><?=number_format($os_total,2);?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<table class="table table-striped table-bordered cleared-effects">
								<theading>
									<tr>
										<th colspan="5">Cleared Deposit</th>
									</tr>
									<tr>
										<?php 
											if($transacting_month['start_date'] == $this->get_start_date_epoch()){
										?>
										<th>Action</th>
										<?php
											}
										?>
										<th>Date</th>
										<th>Voucher Number</th>
										<th>Description</th>
										<th>Amount</th>
									</tr>
								</theading>
								<tbody>
									<?php
										$os_total = 0;
										foreach($cleared_deposits as $row){
									?>
										<tr>
											<?php 
												if($transacting_month['start_date'] == $this->get_start_date_epoch()){
											?>
											<td><div class="btn btn-danger">Undo</div></td>
											<?php
												}
												$os_total +=$row->Cost;
											?>
											<td><?=$row->TDate;?></td>
											<td><?=$row->VNumber;?></td>
											<td><?=$row->TDescription;?></td>
											<td><?=number_format($row->Cost,2);?></td>
										</tr>
									<?php
										}
									?>
								</tbody>
								<tfoot>
									<tr>
										<?php 
											$colspan = 3;
											if($transacting_month['start_date'] == $this->get_start_date_epoch()) $colspan = 4;
										?>
										<th colspan="<?=$colspan;?>">Total</th>
										<th><?=number_format($os_total,2);?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
				</div>
				
				<div class="tab-pane" id="variance">
					Variance Tables
				</div>
				
				<div class="tab-pane" id="ratios">
					Ratio Tables
				</div>
				
				<div class="tab-pane" id="breakdown">
					Expense Breakdown
				</div>
				
				<div class="tab-pane" id="statements">
					Bank Statements
				</div>
				
				<div class="tab-pane" id="submit">
					Submit
				</div>
				
			</div>				
		</div>					
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