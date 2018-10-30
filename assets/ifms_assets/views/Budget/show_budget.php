<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	@media print {
    	.page-break {page-break-after: always;}
	}
</style>
<hr />
<div id="budget_print">
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="well" style="text-align: center;"><?=$this->get_project_id()."<br/> ". $this->l("fy")." ".$this->get_current_fy()." ".$this->l('budget');?></div>
	</div>	
</div>
<hr class="hidden-print" />
<div class="row hidden-print">
		<div class="<?=$this->get_column_size();?>">
			<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
				<div class="form-group hidden-print">
					<label class="col-xs-3 pull-left control-label"><?=$this->l('fy')?>: </label>	
						<div class="col-xs-5">
							<input class=" pull-left" type="text" id="spinner" name="fy_scrolled" value="<?php echo $this->get_current_fy();?>" readonly="readonly"/>
										
						</div>
					<div class="col-xs-2">
						<a href="#" class="btn btn-default pull-left" id="spinner_btn"><?=$this->l('go');?></a>
					</div>	
				</div>
			</form>
		</div>
</div>
<hr class="hidden-print" />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="<?=$this->get_url(array("assetview"=>"show_journal","start_date"=>$this->get_start_date_epoch(),
					"end_date"=>$this->get_end_date_epoch(),"scroll"=>$this->get_scroll()));?>" 
						class="btn btn-default btn-icon icon-left hidden-print">
							<?=$this->l('back');?>
			</a>
					
			<!-- <center> -->
			    <a onclick="PrintElem('#budget_print')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			        <?=$this->l('print');?>
			        <i class="entypo-print"></i>
			    </a>
			<!-- </center> -->
	</div>
</div>
<hr class="hidden-print" />
<div class="row hidden-print">
	<div class="<?=$this->get_column_size();?>">
		<ul class="nav nav-pills">
			<li>
				<div class="btn-group left-dropdown">
					<a class="btn btn-default" href="<?=$this->get_url(array("assetview"=>"create_budget_item","lib"=>"budget","scroll"=>$this->get_scroll()));?>"><?=$this->l('action');?></a>
						<button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
								
						<ul class="dropdown-menu dropdown-green" role="menu">
							<li><a href="<?=$this->get_url(array("assetview"=>"create_budget_item","lib"=>"budget"));?>"><?=$this->l('create_budget_item')?></a></li>
							<li class="divider"></li>
							<li><a href="#" onclick="mass_submit_budget_items('<?=$this->get_current_fy();?>');"><?=$this->l('mass_submit_budget')?></a></li>
							<li class="divider"></li>
							<li><a href="#" onclick="showAjaxModal('<?=$this->get_url(array("assetview"=>"clone_budget","lib"=>"budget"));?>')"><?=$this->l('clone_budget')?></a></li>
							<li class="divider"></li>
							<li><a href="#" onclick="confirm_ajax_action('<?=$this->get_url(array("assetview"=>"ajax_delete_budget","lib"=>"budget"));?>')"><?=$this->l('delete_budget')?></a></li>
							<li class="divider"></li>
						</ul>
				</div>	
	</div>
</div>
<hr />

<!--Show Summary-->
<?php include "show_budget_summary.php";?>
<hr />
<div class="page-break"></div>
<div>
	<!--Show Budget Schedules-->
	<?php include "show_budget_schedules.php";?>
</div>
</div>

<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		var url = '<?=$this->get_url(array('assetview'=>'show_budget','lib'=>'budget'));?>&fy='+$("#spinner").val();
		$(this).prop("href",url);
	});
	
	function PrintElem(elem)
    {
        $(elem).printThis({ 
		    debug: false,              
		    importCSS: true,             
		    importStyle: true,         
		    printContainer: false,       
		    loadCSS: "", 
		    pageTitle: "Annual Budget",             
		    removeInline: false,        
		    printDelay: 333,            
		    header: null,             
		    formValues: true          
		});
    }
    
    function mass_submit_budget_items(fy){
    	$.ajax({
			url:'<?=$this->get_url(array("assetview"=>'ajax_mass_submit_budget_items',"lib"=>"budget"));?>',
			data:{"fy":fy},
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(resp == "1"){
					
					$(".submitactionitem_0, .editactionitem_0").remove();
					$(".action_0").toggleClass("btn-warning btn-info");
					$(".action_0").html('<?=$this->l('action')?> - <?=$this->l('submitted');?> <span class="caret"></span>');
					$(".status_0").html('<?=$this->l("submitted");?>');
					$(".submitteddate_0").html('<?=date("Y-m-d h:i:s");?>');
				}else{
					alert(resp);
				}
			},
			error:function(){
				alert("Error Occurred!");
			}
		});
    }
</script>