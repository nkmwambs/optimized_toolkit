<hr />
<?php
//print_r($project_bank);
//print_r($this->get_latest_cheque_book());
//print_r($last_cheque_book);
?>
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="#" onclick="javascript:go_back();" class="btn btn-default"><?=$this->l('back');?></a>
	</div>
</div>

<hr />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<?=$this->l('last_cheque_book');?>
	</div>
	<div class="<?=$this->get_column_size();?>"> 
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th><?=$this->l('bank');?></th>
					<th><?=$this->l('start_date');?></th>
					<th><?=$this->l('start_serial');?></th>
					<th><?=$this->l('leaves');?></th>
					<th><?=$this->l('last_used_cheque');?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?=$last_cheque_book->bankName;?></td>
					<td><?=$last_cheque_book->start_date;?></td>
					<td><?=$last_cheque_book->start_serial;?></td>
					<td><?=$last_cheque_book->pages;?></td>
					<td><?=$last_cheque_used;?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<hr />
	
<div class="row">
	<div class="<?=$this->get_column_size();?>">	
		<div class="panel panel-primary" data-collapsed="0">
			 <div class="panel-heading">
			     <div class="panel-title" >
			        <i class="entypo-plus-circled"></i>
						<?=$this->l('add_cheque_booklet');?>
			     </div>
			 </div>
			        
			<div class="panel-body" overflow: auto;">	
				<?php echo form_open($this->get_url("cheque_book"), array('id' => 'frm_booklet', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
					
					<div class="form-group">
						<div class="col-xs-12" style="color: red;">
							<?php
								if(isset($success)){
									echo $success;
								}
							?>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-xs-3"><?=$this->l('project');?></label>
						<div class="col-xs-6">
							<input type="text" class="form-control" name="icpNo" value="<?=$this->get_project_id();?>" id="icpNo" required="required" readonly="readonly" />
						</div>
					</div>	
					
					<div class="form-group">
						<label class="col-xs-3"><?=$this->l('bank');?></label>
						<div class="col-xs-6">
							<select class="form-control" name="bankID" id="bankID" required="required">
								<option value=""><?=$this->l('select');?>...</option>
								<?php
									foreach($banks as $bank){
								?>
									<option value="<?=$bank->bankID;?>" <?php if($project_bank_id == $bank->bankID) echo "selected";?>><?=$bank->bankName;?></option>
								<?php		
									}
								?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-xs-3"><?=$this->l('date');?></label>
						<div class="col-xs-6">
							<input type="text" readonly="readonly" data-date-format='yyyy-mm-dd' 
								data-date-start-date='<?=date("Y-m-d",$voucher_date['end_date']);?>' 
								value='<?=date("Y-m-d",$voucher_date['end_date']);?>' 
								class="form-control datepicker" data-provider='datepicker' name="start_date" id="start_date" 
								required="required"/>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-xs-3"><?=$this->l('start_serial_number');?></label>
						<div class="col-xs-6">
							<?php
								$new_booklet_start_serial = $last_cheque_book->start_serial+$last_cheque_book->pages;
							?>
							<input type="number" readonly="readonly" value="<?=$new_booklet_start_serial;?>" class="form-control" name="start_serial" id="start_serial" required="required" />
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-xs-3"><?=$this->l('number_of_leaves');?></label>
						<div class="col-xs-6">
							<input type="text" class="form-control" name="pages" id="pages" required="required"/>
						</div>
					</div>
					
					<?php
						$disabled = "";
						
						if($new_booklet_start_serial > $last_cheque_used+1){
							$disabled = 'disabled="disabled"';
						}
					?>
					
					<div class="form-group">
						<div class="col-xs-12">
							<button type="submit" id="create" class="btn btn-default btn-icon icon-left pull-left" <?=$disabled;?> ><?=$this->l('create');?> <i class="entypo-plus"></i></button>
						</div>
					</div>	
					
				</form>
			</div>
		</div>
	</div>
</div>				

<script>
	$(".datepicker").datepicker();
	
	$("#bankID").change(function(){
		var current_bank = <?=$last_cheque_book->bankID;?>;
		var new_bank = parseInt($(this).val());
		
		if(current_bank !== new_bank){
			$("#start_serial").removeAttr("readonly").val("0");
			$("#create").removeAttr("disabled");
			
		}else{
			if($("#start_serial").prop("readonly") == false){
				$("#start_serial").prop("readonly","readonly").val(<?=$last_cheque_book->start_serial+$last_cheque_book->pages;?>);
				
				if(<?=$new_booklet_start_serial;?> > <?=$last_cheque_used+1?>){
					$("#create").prop("disabled","disabled");
				}
			}
				
			
		}
	});
	
</script>