<?php
include "utility_open_standalone.php";
?>
<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						Proof Of Cash
            	</div>
            </div>
			<div class="panel-body">
				
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

<?php
include "utility_close_standalone.php";
?>