<div class="row">
	<div class="<?=$this->get_column_size();?>">	
		<div class="panel panel-primary" data-collapsed="0">
			 <div class="panel-heading">
			     <div class="panel-title" >
			        <i class="entypo-plus-circled"></i>
						<?=$this->l('edit_tracking_tags');?>
			     </div>
			 </div>
			        
			<div class="panel-body" overflow: auto;">	
					<div class="row">
						<div class="col-xs-12">
							<a id="" href="<?=$this->get_url(array('assetview'=>'show_expense_tracking_tags','lib'=>'admin'));?>" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								<?=$this->l('back');?>
							</a>	
						</div>
					</div>
					<hr />
					
					<?php echo form_open("", array('id' => 'frm_edit_expense_tracking', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
						
						<div class="form-group">
							<label class="control-label col-xs-4"><?=$this->l('account');?></label>
							<div class="col-xs-8">
								<select style="width: 100%;" class="form-control required" id="account" name="account">
										<option value=""><?=$this->l('select_account');?></option>
										<?php
											foreach($accounts as $income_account=>$expense_accounts){
										?>
											<optgroup label="<?=$income_account;?>">
												<?php
													foreach($expense_accounts as $expense){
												?>
													<option value="<?=$expense['AccNo'];?>" <?php if($expense['AccNo'] == $tag->tag_account) echo "selected";?>><?=$expense['AccText'].' - '.$expense['AccName']?></option>
												<?php
													}
												?>
											</optgroup>
										<?php
											}
										?>
									</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-4"><?=$this->l('details');?></label>
							<div class="col-xs-8">
								<input class="form-control required" name="description" id="description" value="<?=$tag->tag_desc;?>" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="control-label col-xs-4"><?=$this->l('status');?></label>
							<div class="col-xs-8">
								<select class="form-control required" id="status" name="status">
									<option value=""><?=$this->l('select');?></option>
									<option value="0" <?php if($tag->tag_status == '0') echo "selected";?> ><?=$this->l('suspended');?></option>
									<option value="1" <?php if($tag->tag_status == '1') echo "selected";?> ><?=$this->l('active');?></option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-xs-12">
								<div id="edit_record" class="btn btn-default"><?=$this->l('edit');?></div>
							</div>
						</div>
						<input type="hidden" name="tag_id" value="<?=$tag_id;?>" />
					</form>
			</div>
		</div>
	</div>
</div>	
	