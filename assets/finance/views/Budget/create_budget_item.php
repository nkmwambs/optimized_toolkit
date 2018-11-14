<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="#" onclick="javascript:go_back();" 
				class="btn btn-default btn-icon icon-left hidden-print">
					<?=$this->l('back');?>
		</a>
	</div>
</div>			
<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-plus-circle"></i>
                            <?php echo $this->l('new_bugdet_item');?>
                        </div>
                    </div>
                    <div class="panel-body">
                    	<?php 
							echo form_open("", array('id'=>'create_budget_item_form', 'class' => 'form-horizontal form-groups-bordered validate','enctype' => 'multipart/form-data'));
						
						?>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('account');?></label>
								<div class="col-xs-8">
									<select class="form-control" name="AccNo" id="AccNo" required="required">
										<option value=""><?=$this->l('select_account')?></option>
										<?php
											foreach($accounts as $income=>$schedule){
										?>
											<optgroup label="<?=$income;?>">
												<?php
													foreach($schedule as $row){
												?>
													<option value="<?=$row->AccNo;?>" data-choices="<?=$row->has_choices;?>"><?=$row->AccText.' - '.$row->AccName;?></option>
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
								<label class="col-xs-4 control-label"><?=$this->l('expense_tracking_tag');?> <span id="tracking_tags_count" class="badge badge-success">0</span></label>
								<div class="col-xs-8">
									<select class="form-control" id="expense_tracking_tag_id" name="expense_tracking_tag_id">
										<option value="0"><?=$this->l('select_tag')?></option>
										<?php
											foreach($expense_tracking_tags as $tags){
										?>
											
										<?php
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('details');?></label>
								<div class="col-xs-8" id="description_holder">
									<input type="text" class="form-control toclear textField" id="details" name="details" required="required" /><!--Consider switching the textbox to list based on if the account has restricted item choices-->
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('fy')?></label>
								<div class="col-xs-8">
									<input type="text" class="form-control" value="<?=$this->get_current_fy()?>" id="fy" name="fy" readonly="readonly" required="required" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('quantity')?></label>
								<div class="col-xs-8">
									<input type="number" class="form-control toclear calc_cost" id="qty" name="qty" value="0" required="required" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('unit_cost')?></label>
								<div class="col-xs-8">
									<input type="number" id="unitCost" name="unitCost" class="form-control toclear calc_cost" value="0" required="required" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('often');?></label>
								<div class="col-xs-8">
									<input type="number" id="often" name="often" class="form-control toclear calc_cost" value="0" required="required" />
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-xs-4 control-label"><?=$this->l('total');?></label>
								<div class="col-xs-8">
									<input type="number" class="form-control toclear" value="0" id="totalCost" name="totalCost" readonly="readonly" required="required" />
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-12">
									<table class="table table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<?php
												foreach($this->get_range_of_fy_months() as $month){
											?>
												<th><?=$this->get_month_name_from_number(ltrim($month,"0"));?></th>
											<?php		
												}
											?>
											
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><div class="btn btn-info" id="clr_spread">Clear</div></td>
											<?php
												$range = range(1, 12);
												foreach($range as $month){
											?>
												<td><input type="text" class="form-control amount_spread toclear" value="0" name="month_<?=ltrim($month,"0");?>_amount" id="month_<?=ltrim($month,"0");?>_amount" required="required" /></td>
											<?php		
												}
											?>
											
											
										</tr>
									</tbody>
								</table>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-12">
									<textarea rows="8" id="notes" class="form-control textField" placeholder="<?=$this->l("notes_here")?>" required="required"></textarea>
									<textarea name="notes" id="hidden_notes" class="toclear hidden"></textarea>
								</div>
								
							</div>
							
							<div class="form-group">
								<div class="col-xs-12">
									<button type="submit" class="btn btn-success"><?=$this->l('create');?></button>
								</div>
							</div>		
						
						</form>
						
					</div>	
	</div>
</div>		
