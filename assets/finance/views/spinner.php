<?php
 echo form_open('' , array('id'=>'scroll', 'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));
?>
	<div class="form-group hidden-print">
<?php if($spinner_type=="month"){ ?>
		<label class="col-xs-3 pull-left control-label"><?=$this->l('scroll_months')?>: </label>	
		
		<div class="col-xs-5">
			<input class=" pull-left" type="text" id="spinner" name="spinned_months" value="<?php echo $this->get_scroll();?>" readonly="readonly"/>										
		</div>
		
		<div class="col-xs-2">
			<a href="#" class="btn btn-default pull-left" id="month_spinner_btn"><?=$this->l('go');?></a>
		</div>

<?php }elseif($spinner_type=="year"){ ?>

		<label class="col-xs-3 pull-left control-label"><?=$this->l('fy')?>: </label>	
		
		<div class="col-xs-5">
			<input class=" pull-left" type="text" id="spinner" name="fy_scrolled" value="<?php echo $this->get_current_fy();?>" readonly="readonly"/>				
		</div>
		
		<div class="col-xs-2">
			<a href="#" class="btn btn-default pull-left" id="fy_spinner_btn"><?=$this->l('go');?></a>
		</div>
					
<?php } ?>
			
	</div>
</form>

<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#fy_spinner_btn").click(function(ev){
		var url = '<?=$this->get_url(array('assetview'=>$assetview,'lib'=>$lib,'project'=>$this->get_project_id()));?>&fy='+$("#spinner").val();
		$(this).prop("href",url);
	});
	
	
	$("#month_spinner_btn").click(function(ev){
		
		var url = '<?=$this->get_url(array("assetview"=>'show_journal','project'=>$this->get_project_id()));?>&scroll='+$("#spinner").val();
		
		$(this).prop("href",url);
	});
	
</script>