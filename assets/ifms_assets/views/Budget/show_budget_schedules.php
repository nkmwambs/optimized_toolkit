<?php
include "utility_open_standalone.php";
?>
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
							<?php
								foreach($this->get_range_of_fy_months() as $month){
							?>
									<th><?=$this->get_month_name_from_number(ltrim($month,"0"));?></th>
							<?php		
								}
							?>
							<th><?=$this->l('status');?></th>
							<th><?=$this->l('submit_date');?></th>
							<th><?=$this->l('last_action_date');?></th>
						</tr>
					<?php	
					foreach($row as $cols){
					?>
						<tr id="row_<?=$cols['scheduleID']?>">
							<td class="hidden-print">
								<div class="btn-group">
									<?php
									//array("Draft","Submitted","Approved","Declined","Reinstated","Allow Delete");
										$btn_color = "default"; 
										$label = $this->l('allow_delete');
										switch ($cols['approved']){
											case 0:
												$btn_color = "warning";	
												$label = $this->l('draft');
												break;
											case 1:
												$btn_color = "info";
												$label = $this->l('submitted');
												break;
											case 2:
												$btn_color = "success";
												$label = $this->l('approved');
												break;	
											case 3:
												$btn_color = "danger";
												$label = $this->l('declined');
												break;		
											case 3:
												$btn_color = "info";
												$label = $this->l('reinstated');
												break;	
											default:
												$btn_color = "default";
												$label = $this->l('allow_delete');
																									
										}
									?>
							         <button type="button" title="<?=$label;?>" id="action_<?=$cols['scheduleID']?>" class="btn btn-<?=$btn_color;?> btn-sm dropdown-toggle action_<?=$cols['approved']?>" data-toggle="dropdown">
							               <?php echo $this->l('action');?> - <?=$label;?> <span class="caret"></span>
							         </button>
							         
							         <ul class="dropdown-menu dropdown-default pull-left" role="menu">
							            <?php
							            	if($cols['approved'] == 0 || $cols['approved'] == 3){
							            ?>
							            
							            <li class="editactionitem_<?=$cols['scheduleID']?> editactionitem_<?=$cols['approved']?>">
							            	<a href="#" onclick="">
							                 	<i class="fa fa-pencil"></i>
													<?php echo $this->l('edit');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider editactionitem_<?=$cols['scheduleID']?> editactionitem_<?=$cols['approved']?>"></li>
							            
							            <?php
											}
							            ?>
							            
							             
							             <?php
							             	if($cols['approved'] == 0){
							             ?>
							             	<li class="submitactionitem_<?=$cols['scheduleID']?> submitactionitem_<?=$cols['approved']?>">
								            	<a href="#row_<?=$cols['scheduleID']?>" onclick='submit_budget_item("<?=$cols['scheduleID']?>");'>
								                 	<i class="fa fa-send"></i>
														<?php echo $this->l('submit');?>
								                </a>
								             </li>
							             
							            	 <li class="divider submitactionitem_<?=$cols['scheduleID']?> submitactionitem_<?=$cols['approved']?>"></li>
							             <?php		
							             	}
							             ?>
							             
							             <li>
							            	<a href="#" onclick="">
							                 	<i class="fa fa-book"></i>
													<?php echo $this->l('notes');?>
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

							<?php
							$range = range(1, 12);
								foreach($range as $month){
							?>
									<td style="text-align: right;"><?=number_format($cols["month_".ltrim($month,"0")."_amount"],2);?></td>

							<?php		
								}
							?>
							<td id="status_<?=$cols['scheduleID']?>" class="status_<?=$cols['approved']?>"><?=ucfirst($budget_status[$cols['approved']]);?></td>
							<td id="submitteddate_<?=$cols['scheduleID']?>" class="submitteddate_<?=$cols['approved']?>"><?=$cols['submitDate'];?></td>
							<td><?=$cols['stmp'];?></td>
						</tr>
					<?php	
					}
					?>
						<tr>
						<td colspan="5" class=""><?=$this->l('total');?></td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"totalCost")),2);?></td>
						<td></td>
						<?php
							$range = range(1, 12);
								foreach($range as $month){
						?>
							<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_".ltrim($month,"0")."_amount")),2);?></td>

						<?php		
							}
						?>
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

<?php
include "utility_close_standalone.php";
?>	

<script>
	function submit_budget_item(scheduleID){
		$.ajax({
			url:'<?=$this->get_url(array("assetview"=>'ajax_submit_budget_item',"lib"=>"budget"));?>',
			data:{"scheduleID":scheduleID},
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(resp == "1"){
					
					$(".submitactionitem_"+scheduleID+", .editactionitem_"+scheduleID).remove();
					$("#action_"+scheduleID).toggleClass("btn-warning btn-info");
					$("#action_"+scheduleID).html('<?=$this->l('action')?> - <?=$this->l('submitted');?> <span class="caret"></span>');
					$("#status_"+scheduleID).html('<?=$this->l("submitted");?>');
					$("#submitteddate_"+scheduleID).html('<?=date("Y-m-d h:i:s");?>');
				}else{
					alert(resp);
				}
			},
			error:function(){
				alert("Error Occurred!");
			}
		});
	}
</script>