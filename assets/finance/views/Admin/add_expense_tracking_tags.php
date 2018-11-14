<?php
//print_r($this->group_expense_account_to_incomes());
?>
<div class="row">
	<div class="<?=$this->get_column_size();?>">	
		<div class="panel panel-primary" data-collapsed="0">
			 <div class="panel-heading">
			     <div class="panel-title" >
			        <i class="entypo-plus-circled"></i>
						<?=$this->l('add_tracking_tags');?>
			     </div>
			 </div>
			        
			<div class="panel-body" overflow: auto;">	
					<ul class="nav nav-pills">
						<li>
							<a id="add_row" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								Add Row
							</a>	
						</li>
						
						<li>
							<a id="btnDelRow" class="btn btn-default btn-icon icon-left hidden hidden-print pull-left">
								Remove Rows
							</a>	
						</li>
						
						<li>
							<a id="resetBtn" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								Reset
							</a>	
						</li>
						
						<li>
							<a id="" href="<?=$this->get_url(array('assetview'=>'show_expense_tracking_tags','lib'=>'admin'));?>" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								<?=$this->l('back');?>
							</a>	
						</li>
						
					</ul>	
					
					<hr />
					
					<?php echo form_open("", array('id' => 'frm_create_expense_tracking', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
					
					<table class="table table-striped datatable" id="tbl_expensetrackingtags">
						<thead>
							<tr>
								<th><?=$this->l('action');?></th>
								<th><?=$this->l('account');?></th>
								<th><?=$this->l('details');?></th>
							</tr>
						</thead>
						<tbody>
							<tr class="tr_clone">
								<td>
									<input type="checkbox" id="" class="check" />
								</td>
								<td>
									<select style="width: 100%;" class="form-control required" id="" name="account[]">
										<option value=""><?=$this->l('select_account');?></option>
										<?php
											foreach($accounts as $income_account=>$expense_accounts){
										?>
											<optgroup label="<?=$income_account;?>">
												<?php
													foreach($expense_accounts as $expense){
												?>
													<option value="<?=$expense['AccNo']?>"><?=$expense['AccText'].' - '.$expense['AccName']?></option>
												<?php
													}
												?>
											</optgroup>
										<?php
											}
										?>
									</select>
								</td>
								<td>
									<input style="width: 100%;" type="text" id="" class="form-control required" name="description[]" />
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"><button id="btnCreate" class="btn btn-default btn-icon icon-left hidden-print pull-left">Create</button></td>
							</tr>
						</tfoot>
					</table>
					</form>	
			</div>
		</div>
	</div>
</div>	

<script>
	$("#add_row").on("click",function(){
		clone_last_body_row('tbl_expensetrackingtags','tr_clone');
	})
	
	
	$(document).on("click",".check",function(){
		
		var checked = $(".check:checked").length;
		if(checked>0){
			$("#btnDelRow").removeClass("hidden");	
		}else{
			$("#btnDelRow").addClass("hidden");
		}
	});
	
	
	$("#btnDelRow").click(function(){
		remove_selected_rows("tbl_expensetrackingtags",'btnDelRow','check');
	});
	
	
	$("#resetBtn").on('click',function(){
		remove_all_rows("tbl_expensetrackingtags");
	})
	
	
	$("#btnCreate").on('click',function(ev){
		
		if($("input select").val("")) return false;
		
		var url = '<?=$this->get_url(array('assetview'=>'ajax_post_expense_tracking_tag','lib'=>'admin'));?>';
		var data = $("#frm_create_expense_tracking").serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display",'block');
			},
			success:function(resp){
				alert(resp);
				$("#overlay").css("display",'none');
				remove_all_rows("tbl_expensetrackingtags");
			},
			error:function(){
				alert('Error Occurred');
			}
		});
		
		ev.preventDefault();
	});
		
</script>		