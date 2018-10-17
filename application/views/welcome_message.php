<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>IFMS | Libraries</title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Techsys Inc Softwares" />
	<meta name="author" content="Techsys Inc Softwares" />
	
	    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//dataTables.bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//custom.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//jquery-ui-themes/base/jquery-ui.min.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//jquery-ui-themes/base/theme.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/ifms_assets/css//font-icons/font-awesome/css/font-awesome.css" />

	<script src="<?=base_url();?>assets/ifms_assets/js//jquery-3.3.1.min.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//bootstrap.min.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//jquery.dataTables.min.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//buttons.bootstrap.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//custom.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//dataTables.bootstrap.min.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//jquery-ui.min.js"></script>
	<script src="<?=base_url();?>assets/ifms_assets/js//printThis.js"></script>


	
</head>
<body class="page-body">
	<hr/>
	<div class="container">
			
			<?php
				//echo date("Y-m-01",strtotime("first day of next month",strtotime("2018-05-10")));
			?>
			
			<!-- <p><a class="btn btn-default" href="<?=base_url();?>Welcome/finance/show_journal/KE345/">Show Journal</a></p> -->			
			<?php 
				echo form_open('', array('id'=>'frmLoad','class' => 'form-horizontal form-groups-bordered validate',"autocomplete"=>"off",'enctype' => 'multipart/form-data'));
			?>
				<div class="form-group">
					<label for="" class="control-label col-sm-2">Project</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="icpNo" id="icpNo" />
					</div>
					<div class="col-sm-2">
						<a href="#" id="journal" class="btn btn-default submit pull-left">Go To Journal</a>
					</div>
					<div class="col-sm-2">
						<a href="#" id="report" class="btn btn-default submit pull-left">Go To Report</a>
					</div>
					<div class="col-sm-2">
						<a href="#" id="budget" class="btn btn-default submit pull-left">Go To Budget</a>
					</div>
				</div>
				
				
			</form>
			
			
	</div>
<hr/>
	
<script>
	$(".submit").click(function(ev){
		var url = '<?=base_url();?>welcome/journal?assetview=show_journal&project='+$("#icpNo").val()+'&lib=journal';
		if($(this).attr("id") == "report"){
			url = '<?=base_url();?>welcome/journal?assetview=show_report&project='+$("#icpNo").val()+'&lib=report';
		}else if($(this).attr("id") == "budget"){
			url = '<?=base_url();?>welcome/journal?assetview=show_budget&project='+$("#icpNo").val()+'&lib=budget';
		}

		$(this).prop("href",url);
		
	});
	$(document).ready(function(){
		//var language = window.navigator.userLanguage || window.navigator.language;
		//alert(language);
	});
</script>	
</body>
</html>
