<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	@media print {
    	.page-break {page-break-after: always;}
	}
</style>
<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="well" style="text-align: center;"><?=$this->get_project_id()."<br/> ". $this->l("fy")." ".$this->get_current_fy()." ".$this->l('budget');?></div>
	</div>	
</div>
<hr />
<div class="row">
		<div class="<?=$this->get_column_size();?>">
			<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
				<div class="form-group hidden-print">
					<label class="col-xs-3 pull-left control-label"><?=$this->l('fy')?>: </label>	
						<div class="col-xs-5">
							<input class=" pull-left" type="text" id="spinner" name="fy_scrolled" value="<?php echo $this->get_current_fy();?>" readonly="readonly"/>
										
						</div>
					<div class="col-xs-2">
						<a href="#" class="btn btn-default pull-left" id="spinner_btn"><?=$this->l('go');?></a>
					</div>	
				</div>
			</form>
		</div>
</div>
<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url(array("assetview"=>"show_journal","start_date"=>$this->get_start_date_epoch(),
					"end_date"=>$this->get_end_date_epoch(),"scroll"=>$this->get_scroll()));?>" 
						class="btn btn-default btn-icon icon-left hidden-print">
							<?=$this->l('back');?>
			</a>
					
			<!-- <center> -->
			    <a onclick="PrintElem('#budget_print')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			        <?=$this->l('print');?>
			        <i class="entypo-print"></i>
			    </a>
			<!-- </center> -->
	</div>
</div>
<hr />
<div id="budget_print">
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<table class="table table-bordered table-striped">
			<thead>
				<tr><th colspan="14"><?=$this->l("budget_summary");?></th></tr>
			</thead>
			<tbody>
				<?php
					foreach($budget_items as $parentAccID=>$schedule){
				?>
						<tr>
							<th colspan="14"><?=$income_accounts[$parentAccID]['AccText'].' - '.$income_accounts[$parentAccID]['AccName'];?></th>
						</tr>
						<tr>
							<th><?=$this->l('account');?></th>
							<th><?=$this->l('total');?></th>
							<th><?=$this->l('jul');?></th>
							<th><?=$this->l('aug');?></th>
							<th><?=$this->l('sep');?></th>
							<th><?=$this->l('oct');?></th>
							<th><?=$this->l('nov');?></th>
							<th><?=$this->l('dec');?></th>
							<th><?=$this->l('jan');?></th>
							<th><?=$this->l('feb');?></th>
							<th><?=$this->l('mar');?></th>
							<th><?=$this->l('apr');?></th>
							<th><?=$this->l('may');?></th>
							<th><?=$this->l('jun');?></th>
						<tr>	
				<?php	
					$sum = 0;$sum_month_1 = 0;$sum_month_2 = 0;$sum_month_3 = 0;$sum_month_4 = 0;$sum_month_5 = 0;
					$sum_month_6 = 0;$sum_month_7 = 0;$sum_month_8 = 0;$sum_month_9 = 0;$sum_month_10 = 0;
					$sum_month_11 = 0;$sum_month_12 = 0;
					foreach($schedule as $row){
				?>
					<tr>
						<td nowrap="nowrap"><?=$row[0]['AccText'].' - '.$row[0]['AccName'];?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"totalCost")),2);$sum+=array_sum(array_column($row,"totalCost"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_1_amount")),2);$sum_month_1+=array_sum(array_column($row,"month_1_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_2_amount")),2);$sum_month_2+=array_sum(array_column($row,"month_2_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_3_amount")),2);$sum_month_3+=array_sum(array_column($row,"month_3_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_4_amount")),2);$sum_month_4+=array_sum(array_column($row,"month_4_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_5_amount")),2);$sum_month_5+=array_sum(array_column($row,"month_5_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_6_amount")),2);$sum_month_6+=array_sum(array_column($row,"month_6_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_7_amount")),2);$sum_month_7+=array_sum(array_column($row,"month_7_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_8_amount")),2);$sum_month_8+=array_sum(array_column($row,"month_8_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_9_amount")),2);$sum_month_9+=array_sum(array_column($row,"month_9_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_10_amount")),2);$sum_month_10+=array_sum(array_column($row,"month_10_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_11_amount")),2);$sum_month_11+=array_sum(array_column($row,"month_11_amount"));?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_12_amount")),2);$sum_month_12+=array_sum(array_column($row,"month_12_amount"));?></td>
					</tr>
								
				<?php
					}
				?>
					<tr>
						<td>Total</td>
						<td style="text-align: right;"><?=number_format($sum,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_1,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_2,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_3,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_4,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_5,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_6,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_7,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_8,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_9,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_10,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_11,2);?></td>
						<td style="text-align: right;"><?=number_format($sum_month_12,2);?></td>
					</tr>
				<?php	
					}
				?>			
			</tbody>
		</table>
	</div>
</div>
<hr />
<div class="page-break"></div>
<div>
	<div class="row">
		<div class="<?=$this->get_column_size();?>">
			<table class="table table-bordered table-striped">
				<thead>
					<tr><th colspan="21"><?=$this->l("budget_schedules");?></th></tr>
				</thead>
				<tbody>
					<?php
					foreach($budget_items as $parentAccID=>$schedule){
					?>
						<tr>
							<th colspan="21"><?=$income_accounts[$parentAccID]['AccText'].' - '.$income_accounts[$parentAccID]['AccName'];?></th>
						</tr>
						
					<?php	
					foreach($schedule as $row){
					?>
						<tr>
							<td colspan="<?=count($row[0]);?>"><?=$row[0]['AccText'].' - '.$row[0]['AccName'];?></td>
						</tr>
						
						<tr>
							<th class="hidden-print"><?=$this->l('action');?></th>
							<th><?=$this->l('details');?></th>
							<th><?=$this->l('quantity');?></th>
							<th><?=$this->l('unit_cost');?></th>
							<th><?=$this->l('often');?></th>
							<th><?=$this->l('total');?></th>
							<th class=""><?=$this->l('validate');?></th>
							<th><?=$this->l('jul');?></th>
							<th><?=$this->l('aug');?></th>
							<th><?=$this->l('sep');?></th>
							<th><?=$this->l('oct');?></th>
							<th><?=$this->l('nov');?></th>
							<th><?=$this->l('dec');?></th>
							<th><?=$this->l('jan');?></th>
							<th><?=$this->l('feb');?></th>
							<th><?=$this->l('mar');?></th>
							<th><?=$this->l('apr');?></th>
							<th><?=$this->l('may');?></th>
							<th><?=$this->l('jun');?></th>
							<th><?=$this->l('status');?></th>
							<th><?=$this->l('submit_date');?></th>
							<th><?=$this->l('last_action_date');?></th>
						</tr>
					<?php	
					foreach($row as $cols){
					?>
						<tr>
							<td class="hidden-print">
								<div class="btn-group">
							         <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
							               <?php echo $this->l('action');?> <span class="caret"></span>
							         </button>
							         
							         <ul class="dropdown-menu dropdown-default pull-left" role="menu">
							            <li>
							            	<a href="#" onclick="">
							                 	<i class="fa fa-pencil"></i>
													<?php echo $this->l('edit');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider"></li>
         
							         </ul>
							      </div> 
							</td>
							<td><?=$cols['details'];?></td>
							<td><?=$cols['qty'];?></td>
							<td style="text-align: right;"><?=number_format($cols['unitCost'],2);?></td>
							<td><?=$cols['often'];?></td>
							<td style="text-align: right;"><?=number_format($cols['totalCost'],2);?></td>
							<td class=""><div class="label label-success">Valid</div></td>
							<td style="text-align: right;"><?=number_format($cols['month_1_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_2_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_3_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_4_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_5_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_6_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_7_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_8_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_9_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_10_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_11_amount'],2);?></td>
							<td style="text-align: right;"><?=number_format($cols['month_12_amount'],2);?></td>
							<td><?=ucfirst($budget_status[$cols['approved']]);?></td>
							<td><?=$cols['submitDate'];?></td>
							<td><?=$cols['stmp'];?></td>
						</tr>
					<?php	
					}
					?>
						<tr>
						<td colspan="5" class=""><?=$this->l('total');?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"totalCost")),2);?></td>
						<td></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_1_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_2_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_3_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_4_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_5_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_6_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_7_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_8_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_9_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_10_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_11_amount")),2);?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_12_amount")),2);?></td>
						<td colspan="3">&nbsp;</td>

					</tr>
					
					<?php
					}
					
					}

					?> 
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>

<script>
		var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		var url = '<?=$this->get_url(array('assetview'=>'show_budget','lib'=>'budget'));?>&fy='+$("#spinner").val();
		$(this).prop("href",url);
	});
	
	function PrintElem(elem)
    {
        $(elem).printThis({ 
		    debug: false,              
		    importCSS: true,             
		    importStyle: true,         
		    printContainer: false,       
		    loadCSS: "", 
		    pageTitle: "Payment Voucher",             
		    removeInline: false,        
		    printDelay: 333,            
		    header: null,             
		    formValues: true          
		});
    }
</script>