<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<link rel="stylesheet" href="assets/ifms_assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/ifms_assets/css/jquery.dataTables.min.css" />
<!-- <link rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css/buttons.bootstrap.min.js" /> -->
<hr/>
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		<div class="well">Cash Journal</div>
	</div>	
</div>

<hr />
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		<div class="btn btn-default">Show Spread</div>
		<div class="btn btn-default" id="select_btn">Select All Vouchers</div>
		<div class="btn btn-default">Print Selected Vouchers</div>
	</div>
</div>
<hr />

<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1" id="display">
		
		<table class="table table-striped" id="ifms_journal_view">
			<thead>
				<tr>
					<?php
						$details = array_keys($records[0]['details']);
						$bank = array_keys($records[0]['bank']);
						$petty = array_keys($records[0]['petty']);
						$income = array_keys($records[0]['income_spread']);
						$expense = array_keys($records[0]['expense_spread']);
						
						foreach($details as $col){ echo "<th>".$labels[$col]."</th>";}
						foreach($bank as $col){ echo "<th>".$labels[$col]."</th>";}
						foreach($petty as $col){ echo "<th>".$labels[$col]."</th>";}
						foreach($income as $col){ echo "<th>".$all_accounts_labels[$col]."</th>";}
						foreach($expense as $col){ echo "<th>".$all_accounts_labels[$col]."</th>";}
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
						$income_values = $records[$i]['income_spread'];
						$expense_values = $records[$i]['expense_spread'];
					
						echo "<tr>";
							foreach($details_values as $key=>$col){
								if($key == "VNumber"){
							?>		
									<td><a href="#" id="voucher_<?=$col;?>" onclick='showAjaxModal("<?=base_url();?>assets/ifms_assets/journal/views/voucher_view.php?vnum=<?=$col;?>");' class='btn btn-default view_voucher check_voucher'><input type='checkbox'/><?=$col;?></a></td>
							
							<?php
								}else{
									echo "<td>".$col."</td>";
								}
								 
							}
							foreach($bank_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							foreach($petty_values as $col){ echo "<td>".number_format($col,2)."</td>";}
							$j = 0;
							$cnt =  count($income_values)-1;
							
							foreach($income_values as $col){
									
								$style = '';
								
								if($j == $cnt){ $style = 'style="border-right:1px black solid;"';}
								if($j == 0){ $style = 'style="border-left:1px black solid;"';}
								
								 echo "<td ".$style.">".number_format($col,2)."</td>";
								
								$j++;
							}
							foreach($expense_values as $col){ echo "<td>".number_format($col,2)."</td>";}
						echo "</tr>";	
					}
				?>
								
			</tbody>
		</table>
		
	</div>
</div>

<?php
//echo $vouchers;
?>

<script src="assets/ifms_assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/ifms_assets/js/bootstrap.min.js"></script>
<script src="assets/ifms_assets/js/jquery.dataTables.min.js"></script>
<!-- <script src="<?=base_url();?>assets/ifms_assets/js/buttons.bootstrap.js"></script> -->
<?php
	include $this->CI->config->item('ifms_assets_location')."journal".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."modal.php";
?>
<script>
	var datatable = $("#ifms_journal_view").DataTable(
		{
				"ordering": false,
		        dom: 'Bfrtip',
		        buttons: [
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdfHtml5'
		        ]
		    }
	);
	
	$("#select_btn").click(function(){
		
		if($(".check_voucher:checked").length === 0){
			$(".check_voucher").prop("checked",true);
		}else{
			$(".check_voucher").prop("checked",false);
		}
		
		
	});
	
	

</script>
