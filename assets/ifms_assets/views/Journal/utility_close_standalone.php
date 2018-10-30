<?php
if($this->load_alone){
?>
	</div>
</div>


<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){

		var url = "<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>?assetview=<?=$this->get_view();?>&project=<?=$this->get_project_id();?>&startdate=<?=$this->get_start_date_epoch();?>&enddate=<?=$this->get_end_date_epoch();?>&scroll="+$("#spinner").val()+"&lib=report";
		
		$(this).prop("href",url);
		
	});
	function go_back(){
		window.history.back();
	}
</script>

<?php	
}
?>