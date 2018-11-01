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
					
					<!--<div class="col-sm-4">
						<select class="form-control" id="load_module">
							<option value="">Select a Module</option>
							<optgroup label="Journal Module">
								<option value="show_journal-journal">Cash Journal</option>
								<option value="create_voucher-journal">Create a Voucher</option>
								<option value="cheque_book-journal">New Cheque Book</option>
							</optgroup>
							<optgroup label="Budget">
								<option value="show_budget_summary-budget">Budget Summary</option>
								<option value="show_budget_schedules-budget">Budget Schedules</option>
								<option value="show_budget-budget">Full Budget</option>
								<option value="create_budget_item-budget">Create Budget Item</option>
							</optgroup>
							<optgroup label="Financial Report">
								<option value="show_fundbalance-report">Fund Balance Report</option>
								<option value="show_proofcash-report">Proof Of Cash</option>
								<option value="show_outstandingcheques-report">Outstanding Cheques</option>
								<option value="show_transitdeposit-report">Transit Deposit</option>
								<option value="show_clearedcheques-report">Cleared Cheques</option>
								<option value="show_cleareddeposits-report">Cleared Deposits</option>
								<option value="show_bankreconcile-report">Bank Reconciliation</option>
								<option value="">Financial Ratios</option>
								<option value="">Expense Breakdown</option>
								<option value="">Bank Statements</option>
								<option value="">Submit Report</option>
							</optgroup>
							
						</select>
					</div> -->
				</div>
				
				
			</form>
			
			
	</div>
<hr/>
	
<script>
	$("#go-btn").click(function(){
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
