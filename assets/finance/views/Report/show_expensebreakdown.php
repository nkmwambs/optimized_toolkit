<?php
include "utility_open_standalone.php";
?>

		<div class="panel panel-primary"data-collapsed="1">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						Expense Breakdown
            	</div>
            	<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
				</div>
            </div>
			<div class="panel-body">
				
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Schedule Item</th>
							<th>Voucher Number</th>
							<th>Cost Spent</th>
							<th>Total Breakdown</th>
							<th>Breaksdown Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							//print_r($trackable_expense);
							foreach($trackable_expense as $row){
						?>
							<tr>
								<td><?=$row['details'];?></td>
								<td><?=$row['VNumber'];?></td>
								<td><?=$row['Cost'];?></td>
								<td>0</td>
								<td><button class="btn btn-danger" onclick="showAjaxModal('<?=$this->get_url(array("assetview"=>'add_expensebreakdown','lib'=>'report',"voucher"=>$row['VNumber']));?>&scheduleID=<?=$row['scheduleID']?>&budgetItem=<?=$row['details'];?>')">Not Available</button></td>
							</tr>
						<?php
							}
						?>
						
					</tbody>
				</table>
			</div>
		</div>							
					


<?php
include "utility_close_standalone.php";
?>