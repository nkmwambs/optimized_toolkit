<?php
include "utility_open_standalone.php";
?>
<div class="panel panel-primary"data-collapsed="1">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						Fund Balance Report
            	</div>
            	<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
				</div>
            </div>
			<div class="panel-body">
				
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
									<th>Totals</th>
									<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Opening')),2);?></th>
									<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Income')),2);?></th>
									<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Expense')),2);?></th>
									<th class="align-right"><?=number_format(array_sum(array_column($fund_balances, 'Ending')),2);?></th>
								</tr>
							</tfoot>
					</table>

		</div>
</div>

<?php
include "utility_close_standalone.php";
?>