<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url(array('assetview'=>'add_expense_tracking_tags','lib'=>'admin'));?>" class="btn btn-default pull-left">Add Record</a>
	</div>
</div>

<hr />

<div>
	<div class="<?=$this->get_column_size();?>">
		<table class="table table striped">
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
										<a href="#">
											<?php
												if($tag->tag_status == 1){
											?>
												<i class="fa fa-eye-slash"></i>
													<?=$this->l('suspend');
												}else{
											?>
												<i class="fa fa-eye-slash"></i>
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