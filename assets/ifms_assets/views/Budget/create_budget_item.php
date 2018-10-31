<hr />
<?php
//print_r($choices);
?>
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url(array("assetview"=>"show_budget","lib"=>'Budget',"start_date"=>$this->get_start_date_epoch(),
					"end_date"=>$this->get_end_date_epoch(),"scroll"=>$this->get_scroll()));?>" 
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
								<label class="col-xs-4 control-label"><?=$this->l('budget_tag');?></label>
								<div class="col-xs-8">
									<select class="form-control" id="plan_item_tag_id" name="plan_item_tag_id">
										<option value=""><?=$this->l('select_tag')?></option>
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

<script>
$(document).ready(function(){
	CKEDITOR.replace('notes');
});
	$(".calc_cost").change(function(){
		var cost = 1;
		$(".calc_cost").each(function(idx){
			cost*=parseFloat($(this).val());
		});	
		
		$("#totalCost").val(cost);
		
		$(".amount_spread").each(function(){
			$(this).val((cost/12).toFixed(2));
		});
	});
	
	$("#clr_spread").click(function(){
		$(".amount_spread").each(function(){
			$(this).val(0);
		});
	});
	
	$("#create_budget_item_form").submit(function(ev){
		var check_spread = false;
		var editor_data = CKEDITOR.instances.notes.getData();
		//alert(editor_data);
		$("#hidden_notes").val(editor_data);
		
		//Check if spread is correct
		var sum_spread = 0;
		$(".amount_spread").each(function(){
			sum_spread += parseFloat($(this).val());
		});
		
		check_spread = parseFloat($("#totalCost").val()) == parseFloat(Math.round(sum_spread));
		
		if(check_spread){
			
			$.ajax({
				url:'<?=$this->get_url(array("assetview"=>'ajax_budget_item_posting',"lib"=>'budget'));?>',
				beforeSend:function(){
					$("#overlay").css("display","block");
				},
				type:"POST",
				data:$(this).serializeArray(),
				success:function(resp){
					$("#overlay").css("display","none");
					$(".toclear").each(function(){
						$(this).val(0);
						if($(this).hasClass("textField")){
							$(this).val("");	
						}
					});
					CKEDITOR.instances.notes.setData("");
					alert(resp);
				},
				error:function(){
					
				}
			});
		}else{
			alert("Spread is incorrect");
		}
		
		ev.preventDefault();
	});
	
	
	$("#AccNo").change(function(){
		var hasChoice = $('option:selected').data('choices');
		
		if(hasChoice){
			//var option = "<option>Select a Description</option>";
			$.ajax({
				url:'<?=$this->get_url(array('assetview'=>'ajax_get_choices_for_account','lib'=>'budget'));?>',
				type:"POST",
				data:{'AccNo':$(this).val()},
				beforeSend:function(){
					$("#overlay").css("display","block");
				},
				success:function(option){
					$("#overlay").css("display","none");
					$("#description_holder").html("<select class='form-control' id='details' name='details' required='required'>"+option+"</select>");
				},
				error:function(){
					
				}
			});
			
		}else{
			$("#description_holder").html('<input type="text" class="form-control toclear textField" id="details" name="details" required="required" />');
		}
	});
	
</script>