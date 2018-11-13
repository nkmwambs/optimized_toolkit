<?php
$this->show_header($this->l("budget_summary"),"year");
?>
<div class="row">
	<div class="col-xs-12">
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
							<?php
								foreach($this->get_range_of_fy_months() as $month){
							?>
									<th><?=$this->get_month_name_from_number(ltrim($month,"0"));?></th>
							<?php		
								}
							?>
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
						<?php
							$range = range(1, 12);
							foreach($range as $month){
						?>
								<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_".ltrim($month,"0")."_amount")),2);$sum_month_1+=array_sum(array_column($row,"month_".ltrim($month,"0")."_amount"));?></td>
						<?php		
							}
						?>
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

<?php
$this->show_footer();
?>