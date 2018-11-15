<?php
//print_r($this->get_tag_id_from_budget_schedule());
?>
<div class="panel panel-primary"data-collapsed="1">
      	<div class="panel-heading">
          	<div class="panel-title" >
           		<i class="entypo-clipboard"></i>
					<?=$this->l('add_expense_breakdown');?>
           	</div>
           	<div class="panel-options">
				<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
				<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
			</div>
           </div>
		<div class="panel-body">
			
			<ul class="nav nav-pills">
				<li>
					<a id="" href="#" onclick="go_back();" class="btn btn-default btn-icon icon-left hidden-print pull-left">
						<?=$this->l('back');?>
					</a>
				</li>
				<li>
					<a href="#" class="btn btn-default" id="clone_row"><?=$this->l('add_row');?></a>	
				</li>
				<li>
					<a href="#" class="btn btn-default hidden" id="btnDelRow"><?=$this->l('delete_row');?></a>	
				</li>
				<!-- <li>
					<a href="#" class="btn btn-default" id="btnReset"><?=$this->l('reset');?></a>	
				</li> -->
			</ul>
			
			<hr />
			
			<?php echo form_open("", array('id' => 'frm_breakdown', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>

				<table class="table table-striped" id="tbl_breakdown">
					<thead>
						<tr>
							<th><?=$this->l('action');?></th>
							<th><?=$this->l('voucher_number');?></th>
							<th><?=$this->l('expense_tracking_tag')?></th>
							<th><?=$this->l('reference_number');?></th>
							<th><?=$this->l('amount');?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if(count($expense_breakdown)>0){
								foreach($expense_breakdown as $row){
								?>
									<tr class="tr_clone">
										<td><input type="checkbox" class="check" disabled="disabled" /></td>
										<td><input type="number" required="required" readonly="readonly" name="VNumber[]" id="" class="form-control VNumber" value="<?=$row['VNumber'];?>"/></td>
										<input type="hidden" required="required" readonly="readonly" name="scheduleID[]" id="" class="form-control scheduleID" value="<?=$row['scheduleID'];?>"/>
										<td><input type="text" required="required" readonly="readonly" name="" id="" class="form-control details" value="<?=$row['tag_description'];?>"/></td>
										<td><input type="text" required="required" name="referenceNo[]" id="" class="form-control toempty referenceNo" value="<?=$row['referenceNo'];?>" /></td>
										<td><input type="number" required="required" name="amount[]" id="" class="form-control amount" value="<?=$row['amount'];?>" /></td>
									</tr>	
								<?php	
								}
							}
						?>
						<tr class="tr_clone">
							<td><input type="checkbox" class="check" /></td>
							<td><input type="number" required="required" readonly="readonly" name="VNumber[]" id="" class="form-control voucher" value="<?=$voucher;?>"/></td>
							<input type="hidden" required="required" readonly="readonly" name="scheduleID[]" id="" class="form-control scheduleID" value="<?=$scheduleID;?>"/>
							<td><input type="text" required="required" readonly="readonly" name="" id="" class="form-control details" value="<?=$budgetItem;?>"/></td>
							<td><input type="text" required="required" name="referenceNo[]" id="" class="form-control toempty referenceNo" /></td>
							<td><input type="number" required="required" name="amount[]" id="" class="form-control amount"  value="0"/></td>
						</tr>
					</tbody>
				
				<tfoot>
					<tr>
						<td colspan="4"><?=$this->l('total_spent');?></td>
						<td id="spent"><?=$spent_for_tracking;?></td>
					</tr>
					<tr>
						<td colspan="4"><?=$this->l('total_tracked');?></td>
						<td id="tracked">0</td>
					</tr>
					<tr>
						<td colspan="4"><?=$this->l('balance');?></td>
						<td id="balance">0</td>
					</tr>
					<tr>
						<?php
							if(count($expense_breakdown)>0){
						?>
								<td colspan="5"><button type="submit" class="btn btn-default" id="btn_update"><?=$this->l('update');?></button></td>					
						<?php		
							}else{
						?>
								<td colspan="5"><button type="submit" class="btn btn-default" id="btn_create"><?=$this->l('create')?></button></td>
						<?php		
							}
						?>

					</tr>
				</tfoot>
				</table>
			</form>
		</div>
</div>		

<script>
	$(function(){
		var spent = <?=$spent_for_tracking;?>;
		var tracked = sum_column_of_textboxes("amount")
		var balance = parseFloat(spent) - parseFloat(tracked);
		
		$("#tracked").html(tracked);
		$("#balance").html(balance);
		
	})
	
	$(".amount").on('change',function(){
		var spent = <?=$spent_for_tracking;?>;
		var tracked = sum_column_of_textboxes("amount")
		var balance = parseFloat(spent) - parseFloat(tracked);
		
		$("#tracked").html(tracked);
		$("#balance").html(balance);
	})
	

	$("#clone_row").click(function(){
		clone_last_body_row("tbl_breakdown","tr_clone");
	});
	
	$("#btnDelRow").on('click',function(){
		remove_selected_rows("tbl_breakdown","btnDelRow","check");
	})
	
	$("#btnReset").click(function(){
		remove_all_rows("tbl_breakdown");
	});
	
	$(document).on('click','.check',function(){
		show_hide_delete_button_on_check("check","btnDelRow");
	});	
	
	$("#btn_create").click(function(ev){
		
		var url = '<?=$this->get_url(array("assetview"=>'ajax_post_expensebreakdown','lib'=>'report'))?>';
		var data = $("#frm_breakdown").serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				alert(resp);
				//remove_all_rows("tbl_breakdown");
				go_back();
			},
			error:function(){
				
			}
		});
		
		ev.preventDefault();
	});
	
	$("#btn_update").click(function(ev){
		
		var url = '<?=$this->get_url(array("assetview"=>'ajax_update_expensebreakdown','lib'=>'report'))?>';
		var data = $("#frm_breakdown").serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				alert(resp);
				//remove_all_rows("tbl_breakdown");
				go_back();
			},
			error:function(){
				
			}
		});
		
		ev.preventDefault();
	});
</script>