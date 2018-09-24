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
			
			<!-- <p><a class="btn btn-default" href="<?=base_url();?>Welcome/finance/show_journal/KE345/">Show Journal</a></p> -->
			
			<?php 
				echo form_open(base_url() . 'Welcome/finance/show_journal/', array('id'=>'frmLoad','class' => 'form-horizontal form-groups-bordered validate',"autocomplete"=>"off",'enctype' => 'multipart/form-data'));
			?>
				<div class="form-group">
					<label for="" class="control-label col-sm-2">Project</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="icpNo" id="icpNo" />
					</div>
					<div class="col-sm-2">
						<button id="submit" class="btn btn-default">Go</button>
					</div>
				</div>
				
				
			</form>
			
			
	</div>
<hr/>
	
<script>
	$("#submit").click(function(ev){
		
		var url = $("#frmLoad").attr('action')+$("#icpNo").val();
		
		$("#frmLoad").prop('action',url);
		$("#frmLoad").submit();

	});
</script>	
</body>
</html>
