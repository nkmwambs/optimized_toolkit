<?php
if($this->load_alone){
?>
<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div style="text-align:center;font-weight: bolder;" class="well well-lg">
			<?=$this->get_project_id();?> <br/> Monthly Financial Report <br/> As at <?=date("j<\s\u\p>S</\s\u\p> F Y",strtotime("last day of this month",$this->get_start_date_epoch()));?>
		</div>
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="col-xs-6">
			<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
				<div class="form-group hidden-print">
					<label class="col-xs-3 pull-left control-label"><?=$this->l('scroll_months')?>: </label>	
						<div class="col-xs-5">
							<input class=" pull-left" type="text" id="spinner" name="spinned_months" value="<?php echo $this->get_scroll();?>" readonly="readonly"/>
										
						</div>
					<div class="col-xs-2">
						<a class="btn btn-default pull-left" id="spinner_btn"><?=$this->l('go');?></a>
					</div>	
				</div>
			</form>	
		</div>
		
		<div class="col-xs-6">
			<div class="btn btn-default pull-right" onclick="javascript:go_back();">Back</div>
		</div>
		
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">

<?php	
}
?>
