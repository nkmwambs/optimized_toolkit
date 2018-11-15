<div class="panel panel-primary"data-collapsed="1">
      	<div class="panel-heading">
          	<div class="panel-title" >
           		<i class="entypo-clipboard"></i>
					Add Expense Breakdown
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
					<a href="#" class="btn btn-default" id="clone_row">Add Row</a>	
				</li>
			</ul>
			
			<hr />
			
			<?php echo form_open("", array('id' => 'frm_breakdown', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>

				<table class="table table-striped" id="tbl_breakdown">
					<thead>
						<tr>
							<th><?=$this->l('voucher_number');?></th>
							<th><?=$this->l('expense_tracking_tag')?></th>
							<th><?=$this->l('reference_number');?></th>
							<th><?=$this->l('amount');?></th>
						</tr>
					</thead>
					<tbody>
						<tr class="tr_clone">
							<td><input type="number" required="required" readonly="readonly" name="VNumber[]" id="" class="form-control" value="<?=$voucher;?>"/></td>
							<input type="hidden" required="required" readonly="readonly" name="scheduleID[]" id="" class="form-control" value="<?=$scheduleID;?>"/><
							<td><input type="text" required="required" readonly="readonly" name="" id="" class="form-control" value="<?=$budgetItem;?>"/></td>
							<td><input type="text" required="required" name="referenceNo[]" id="" class="form-control toempty" /></td>
							<td><input type="number" required="required" name="amount[]" id="" class="form-control" /></td>
						</tr>
					</tbody>
				
				<tfoot>
					<tr>
						<td><button type="submit" class="btn btn-default" id="btn_create">Create</button></td>
					</tr>
				</tfoot>
				</table>
		</div>
</div>		

<script>
	$("#clone_row").click(function(){
		var $tr    = $("#tbl_breakdown tbody tr:last").closest('.tr_clone');
		var $clone = $tr.clone();
		$clone.find('.toempty').val('');
		$tr.after($clone);
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
			},
			error:function(){
				
			}
		});
		
		ev.preventDefault();
	});
</script>