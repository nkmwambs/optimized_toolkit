<?php
include "utility_open_standalone.php";
//print_r($this->get_statementbal());
?>
<div class="panel panel-primary"data-collapsed="1">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						Bank Reconciliation
            	</div>
            	<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
				</div>
            </div>
			<div class="panel-body">
				<?php echo form_open('' , array('id'=> '',  'class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Reconciliation Month</label>
                		<div class="col-xs-8">
                			<input type="text" class="form-control datepicker" readonly="readonly" value="<?=$month;?>" id="month" name="month" />
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Bank Statement Balance</label>
                		<div class="col-xs-8">
                			<input type="number" class="form-control" value="<?=$statement_balance;?>" id="statementbal" name="statementbal" <?php if(!$this->is_transacting_month) echo "readonly='readonly'";?> />
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Journal Bank Balance</label>
                		<div class="col-xs-8">
                			<input type="text" class="form-control" readonly="readonly" value="<?=$journal_balance;?>" id="journalbal"  name="journalbal" />
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Outstanding Cheques</label>
                		<div class="col-xs-8">
                			<input type="text" class="form-control" readonly="readonly" value="<?=$outstanding_cheques;?>" id="oschq" name="oschq" />
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Transit Deposit</label>
                		<div class="col-xs-8">
                			<input type="text" class="form-control" readonly="readonly" value="<?=$transit_deposit;?>" id="deptrans" name="deptrans"/>
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4">Adjusted Bank Balance</label>
                		<div class="col-xs-8">
                			<input type="text" class="form-control" readonly="readonly" id="adjbal" name="adjbal"/>
                		</div>
                	</div>
                	
                	<div class="form-group">
                		<label class="control-label col-xs-4"></label>
                		<div class="col-xs-8">
                			<div class="label label-danger">Incorrect</div>
                		</div>
                	</div>
                	<?php
                		if($this->is_transacting_month){
                	?>
	                	<div class="form-group">
	                		<div class="col-xs-12">
	                			<div class="btn btn-default">Save</div>
	                		</div>
	                	</div>
                	<?php
						}
                	?>	
                </form>         			
			</div>
</div>		
<?php
include "utility_close_standalone.php";
?>
<script>
	var datepicker = $(".datepicker").datepicker({
		format:"yyyy-mm-dd",
		startDate:'<?=$this->get_end_date();?>',
		endDate:'<?=$this->get_end_date();?>',
	});
	
	$("#statementbal").keyup(function(){
		calculate_adjustedbalance();
	});
	
	$(document).ready(function(){
		calculate_adjustedbalance();
	});
	
	function calculate_adjustedbalance(){
		var statementbal = $("#statementbal").val();
		var journalbal = $("#journalbal").val();
		var oschq = $("#oschq").val();
		var deptrans = $("#deptrans").val();
		var adjbal = parseFloat(statementbal)+ parseFloat(deptrans) - parseFloat(oschq);
			if(!isNaN(adjbal)) {
				$("#adjbal").val(adjbal)
			} else {
				$("#adjbal").val(0);
				adjbal = 0;	
			} 
			
		if(adjbal == parseFloat(journalbal)){
			$(".label").removeClass("label-danger").addClass("label-success");
			$(".label").html("Correct");
		}else{
			if($(".label").hasClass('label-success')) $(".label").removeClass("label-success").addClass("label-danger");
			$(".label").html("Incorrect");
		}	
	}
</script>