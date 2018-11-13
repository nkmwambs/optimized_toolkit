<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	@media print {
    	.page-break {page-break-after: always;}
    	table{font-size:7pt;}
    	@page {size: A4 landscape;}
	}
	
	@media screen{
		table{font-size:7pt;}
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
			<?php $this->show_spinner("year",'budget','show_budget');?>
		</div>
</div>
<hr class="hidden-print" />
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<a href="#" onclick="javascript:go_back();"  
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

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<!--Show Summary-->
		<?php include "show_budget_summary.php";?>
	</div>
</div>

<hr />
<div class="page-break"></div>
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<!--Show Budget Schedules-->
		<?php include "show_budget_schedules.php";?>
	</div>
</div>
</div>

<script>
	
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