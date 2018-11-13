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
				<div class="btn btn-info" id="clone_row">Add Row</div>
				<hr	/>
				<?php echo form_open("", array('id' => 'frm_breakdown', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>

				<table class="table table-striped" id="tbl_breakdown">
					<thead>
						<tr>
							<th>Voucher Number</th>
							<th>Budget Item</th>
							<th>Reference Number</th>
							<th>Amount</th>
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