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
			<p>
				<?php
					//echo strtotime("1970-01-01 00:00:00");
				?>
			</p>
			<!-- <p><a class="btn btn-default" href="<?=base_url();?>Welcome/finance/show_journal/KE345/<?=strtotime("2018-01-01");?>/<?=strtotime("2018-01-31");?>">Show Journal</a></p> -->
			<p><a class="btn btn-default" href="<?=base_url();?>Welcome/finance/show_journal/KE345/">Show Journal</a></p>
			<!-- <p><a class="btn btn-default" href="<?=base_url();?>Welcome/finance/create_voucher/KE345/<?=strtotime("2018-01-01");?>/<?=strtotime("2018-01-31");?>">Create a Voucher</a></p> -->
			
	</div>
<hr/>
	
<script>
	$("#submit").click(function(ev){
		
		var url = $("#frmLoad").attr('action')+$("#icp").val()+'/'+$("#start_date").val()+'/'+$("#end_date").val();
		
		$("#frmLoad").prop('action',url);
		$("#frmLoad").submit();

	});
</script>	
</body>
</html>
