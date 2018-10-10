<?php
include "utility_open_standalone.php";
?>
<div class="panel panel-primary"data-collapsed="1">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						In Transit Deposit
            	</div>
            	<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
				</div>
            </div>
			<div class="panel-body">
								
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

<?php
include "utility_close_standalone.php";
?>