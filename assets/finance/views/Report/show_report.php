<hr />
<?php
	//print_r($cleared_deposits);
	//echo date('Y-m-d',strtotime('first day of previous month',$this->get_start_date_epoch()));
?>
<style>
	.align-right{
		text-align:right;
	}
	
</style>

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div style="text-align:center;font-weight: bolder;" class="well well-lg">
			<?=$this->get_project_id();?> <br/> Monthly Financial Report <br/> As at <?=date("j<\s\u\p>S</\s\u\p> F Y",strtotime("last day of this month",$this->get_start_date_epoch()));?>
		</div>
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
		<div class="col-xs-6">
			<form id="scroll" role="form" class="form-horizontal form-groups-bordered">
				<div class="form-group hidden-print">
					<label class="col-xs-3 pull-left control-label"><?=$this->l('scroll_months')?>: </label>	
						<div class="col-xs-5">
							<input class=" pull-left" type="text" id="spinner" name="spinned_months" value="<?php echo $this->get_scroll();?>" readonly="readonly"/>
										
						</div>
					<div class="col-xs-2">
						<a class="btn btn-default pull-left" id="spinner_btn"><?=$this->l('go');?></a>
					</div>	
				</div>
			</form>	
		</div>
		
		<div class="col-xs-6">
			<div class="btn btn-default pull-right" onclick="javascript:go_back();">Back</div>
		</div>
		
	</div>
</div>

<hr />

<div class="row">
	<div class="<?=$this->get_column_size();?>">
			
		<div class="tabs-vertical-env">
						
			<ul class="nav nav-tabs bordered"><!-- available classes "right-aligned" -->
				<li class="active"><a href="#funds" data-toggle="tab">Fund Balance Report</a></li>
				<li><a href="#reconcile" data-toggle="tab">Bank Reconciliation</a></li>
				<li><a href="#variance" data-toggle="tab">Budget Variance</a></li>
				<li><a href="#ratios" data-toggle="tab">Financial Ratios</a></li>
				<li><a href="#breakdown" data-toggle="tab">Expense Breakdown</a></li>
				<li><a href="#statements" data-toggle="tab">Bank Statements</a></li>
				<li><a href="#submit" data-toggle="tab">Submit</a></li>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="funds">
					<?php include "show_fundbalance.php";?>
					<hr />
					<?php include "show_proofcash.php";?>					
				</div>
				
				<div class="tab-pane" id="reconcile">
					
					<div class="row">
						<div class="col-sm-12">
							<?php include "show_bankreconcile.php";?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<?php include "show_outstandingcheques.php";?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<?php include "show_clearedcheques.php";?>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-sm-12">
							<?php include "show_transitdeposit.php";?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<?php include "show_cleareddeposits.php";?>
						</div>
					</div>
					
				</div>
				
				<div class="tab-pane" id="variance">
					<?php include "show_budgetvariance.php";?>
				</div>
				
				<div class="tab-pane" id="ratios">
					Ratio Tables
				</div>
				
				<div class="tab-pane" id="breakdown">
					<?php include "show_expensebreakdown.php";?>
				</div>
				
				<div class="tab-pane" id="statements">
					Bank Statements
				</div>
				
				<div class="tab-pane" id="submit">
					Submit
				</div>
				
			</div>				
		</div>					
	</div>
</div>



<script>
	var spinner = $( "#spinner" ).spinner();
	
	$("#spinner_btn").click(function(ev){
		//alert($("#spinner").val());
		var url = "<?=base_url();?><?=$this->get_controller();?>/<?=$this->get_method();?>?assetview=show_report&project=<?=$this->get_project_id();?>&startdate=<?=$this->get_start_date_epoch();?>&enddate=<?=$this->get_end_date_epoch();?>&scroll="+$("#spinner").val()+"&lib=report";
		
		$(this).prop("href",url);
		
	});
	
	 function go_back(){
		window.history.back();
	}
	
	$(document).ready(function(){
		 if (location.hash) {
			        $("a[href='" + location.hash + "']").tab("show");
			    }
			    $(document.body).on("click", "a[data-toggle]", function(event) {
			        location.hash = this.getAttribute("href");
			    });

			$(window).on("popstate", function() {
			    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
			    $("a[href='" + anchor + "']").tab("show");

		});
	});
</script>