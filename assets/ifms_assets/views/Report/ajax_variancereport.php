<?php
//print_r($this->test());
//echo $this->get_fy_start_date();
//echo "<br/>";
//echo $this->get_current_fy();
?>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Account</th>
			<th>Month's Expense</th>
			<th>Expense To Date</th>
			<th>Budget To Date</th>
			<th>Variance</th>
			<th>% Variance</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$sum_month_expenses = 0;
			$sum_to_date_expenses = 0;
			$sum_to_date_budget = 0;
			
			foreach($variancegrid as $rows){
				extract($rows);
				$sum_month_expenses+=$month_expenses;
				$sum_to_date_expenses+=$expenses_to_date;
				$sum_to_date_budget+=$budget_to_date;
				
		?>
			<tr>
				<td><?=$account['AccText'];?> - <?=$account['AccName'];?></td>
				<td style="text-align: right;"><?=number_format($month_expenses,2);?></td>
				<td style="text-align: right;"><?=number_format($expenses_to_date,2);?></td>
				<td style="text-align: right;"><?=number_format($budget_to_date,2);?></td>
				<td style="text-align: right;"><?=number_format($variance,2);?></td>
				<td style="text-align: right;"><?=number_format($per_variance);?>%</td>
			</tr>
		<?php
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<th>Totals</th>
			<th style="text-align: right;"><?=number_format($sum_month_expenses,2);?></th>
			<th style="text-align: right;"><?=number_format($sum_to_date_expenses,2);?></th>
			<th style="text-align: right;"><?=number_format($sum_to_date_budget,2);?></th>
			<?=$sum_variance = $sum_to_date_budget- $sum_to_date_expenses;?>
			<th style="text-align: right;"><?=number_format($sum_variance,2)?></th>
			<?php
				$sum_per_variance = 0;
				if($sum_to_date_budget!==0){
					$sum_per_variance = ($sum_variance/$sum_to_date_budget)*100;
				}elseif($sum_to_date_budget==0 && $sum_to_date_expenses !== 0){
					$sum_per_variance = -100;
				}
			?>
			<th style="text-align: right;"><?=number_format($sum_per_variance);?>%</th>
		</tr>
	</tfoot>
</table>