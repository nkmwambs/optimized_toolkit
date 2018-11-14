<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

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
								<?=$this->l('add_row');?>
							</a>	
						</li>
						
						<li>
							<a id="btnDelRow" class="btn btn-default btn-icon icon-left hidden hidden-print pull-left">
								<?=$this->l('remove_rows');?>
							</a>	
						</li>
						
						<li>
							<a id="resetBtn" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								<?=$this->l('reset');?>
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
		