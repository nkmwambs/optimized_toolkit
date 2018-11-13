<?php
if($this->load_alone){
?>
<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div style="text-align:center;font-weight: bolder;" class="well well-lg">
			<?=$this->get_project_id();?> <br/> <?=$title;?>
		</div>
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="col-xs-6">
			<?php
				$this->show_spinner($spinner_type);
			?>	
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
