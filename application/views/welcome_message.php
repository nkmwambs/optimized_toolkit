<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>IFMS | Libraries</title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Techsys Inc Softwares" />
	<meta name="author" content="Techsys Inc Softwares" />
	
	    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//dataTables.bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//custom.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//jquery-ui-themes/base/jquery-ui.min.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//jquery-ui-themes/base/theme.css" />
    <link type="text/css" rel="stylesheet" href="<?=base_url();?>assets/finance/css//font-icons/font-awesome/css/font-awesome.css" />

	<script src="<?=base_url();?>assets/finance/js//jquery-3.3.1.min.js"></script>
	<script src="<?=base_url();?>assets/finance/js//bootstrap.min.js"></script>
	<script src="<?=base_url();?>assets/finance/js//jquery.dataTables.min.js"></script>
	<script src="<?=base_url();?>assets/finance/js//buttons.bootstrap.js"></script>
	<script src="<?=base_url();?>assets/finance/js//custom.js"></script>
	<script src="<?=base_url();?>assets/finance/js//dataTables.bootstrap.min.js"></script>
	<script src="<?=base_url();?>assets/finance/js//jquery-ui.min.js"></script>
	<script src="<?=base_url();?>assets/finance/js//printThis.js"></script>


	
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
					
					<!-- <div class="col-sm-4">
						<select class="form-control">
							<option value="">Select Your Role</option>
							<option value="">Partnership Facilitator</option>
							<option value="">FCP Staff</option>
							<option value="">Other National Office Staff</option>
						</select>
					</div> -->
					
					<div class="col-sm-4">
						<input type="text" class="form-control" name="icpNo" id="icpNo" placeholder="Your FCP ID" />
					</div>
					
					<div class="col-sm-2">
						<div class="btn btn-default" id="go-btn">Go</div>
					</div>
					
				</div>
				
				
			</form>
			
			
	</div>
<hr/>
	
<script>
	$("#go-btn").click(function(){
		//alert("Hello");
		var url = '<?=base_url();?>welcome/journal?assetview=show_journal&project='+$("#icpNo").val()+'&lib=journal';
		
		if($("#icpNo").val()==""){
			alert("Please provide a Project Number");
			return false;
		}
		
		location.href=url;
	});
	
</script>	
</body>
</html>
