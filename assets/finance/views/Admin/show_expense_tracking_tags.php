<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url(array('assetview'=>'add_expense_tracking_tags','lib'=>'admin'));?>" class="btn btn-default pull-left">Add Record</a>
	</div>
</div>

<hr />

<div>
	<div class="<?=$this->get_column_size();?>">
		<table class="table table striped datatable">
			<thead>
				<tr>
					<th><?=$this->l('action');?></th>
					<th><?=$this->l('account');?></th>
					<th><?=$this->l('account_description');?></th>
					<th><?=$this->l('details');?></th>
					<th><?=$this->l('status');?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($tags as $tag){
				?>
				<tr>	
					<td>
						<div class="btn-group left-dropdown">
								<a class="btn btn-default" href="#"><?=$this->l('action');?></a>
								<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="<?=$this->get_url(array('assetview'=>'edit_expense_tracking_tags','lib'=>'admin'));?>&tag_id=<?=$tag->tag_id;?>">
											<i class="fa fa-pencil"></i>
												<?=$this->l('edit')?>
										</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#" onclick="change_item_status(this,'<?=$tag->tag_id;?>','<?=$tag->tag_status;?>');">
											<?php
												if($tag->tag_status == 1){
											?>
												<i class="fa fa-eye-slash"></i>
													<?=$this->l('suspend');
												}else{
											?>
												<i class="fa fa-eye"></i>
													<?=$this->l('activate');
											
												}
											?>	
										</a>
									</li>
									<li class="divider"></li>
								</ul>	
					</td>
					<td><?=$tag->account_name;?></td>
					<td><?=$tag->account_desc;?></td>
					<td><?=$tag->tag_desc;?></td>
					<?php
						$status = array('Suspended','Active');
					?>
					<td><?=$status[$tag->tag_status];?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>	
</div>

<script>
	function change_item_status(elem,id,status){
		var url = '<?=$this->get_url(array('assetview'=>'ajax_update_expense_tracking_tag_status','lib'=>'admin'));?>';
		var data = {'tag_id':id,'tag_status':status};
		var status_tag = $(elem).closest("tr").find("td:last").html().trim();
		//alert(status_tag);
		
		$.ajax({
			url:url,
			data:data,
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(status == '1' || status_tag == 'Active'){
					$(elem).closest("tr").find("td:last").html("<?=$this->l('suspended');?>");
					$(elem).html("<i class='fa fa-eye'></i> <?=$this->l('activate');?>");
					
				}else if(status == '0' || status_tag == 'Suspended'){
					$(elem).closest("tr").find("td:last").html("<?=$this->l('active');?>");
					$(elem).html("<i class='fa fa-eye-slash'></i> <?=$this->l('suspend');?>");
				}
			},
			error:function(xhr,err){
				alert(err);
			}
		});
		
	}
</script>