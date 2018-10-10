<?php
include "utility_open_standalone.php";
//print_r($this->get_month_transactions_by_accno());
?>
<div class="panel panel-primary"data-collapsed="1">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-clipboard"></i>
						Budget Variance
            	</div>
            	<div class="panel-options">
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
					<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
				</div>
            </div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<form>
							<div class="form-group">
								<label class="control-label col-xs-4">Choose Account</label>
								<div class="col-xs-6">
									<select id="rev_accounts" class="form-control">
										<option value="">Select Account</option>
										<?php
											foreach($revenue_accounts as $rows){
										?>
											<option value="<?=$rows->accID;?>"><?=$rows->AccText;?> - <?=$rows->AccName;?></option>
										<?php
											}
										?>
										
									</select>
								</div>
								<div class="col-xs-2">
									<div id="retrieve" class="btn btn-default">Go</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				
				<hr />
				
				<div class="row">
					<div class="col-xs-12">
						<div id="result" class="hidden">
							
						</div>
					</div>
				</div>
				
			</div>
</div>	
<?php
include "utility_close_standalone.php";
?>		

<script>
	$("#retrieve").click(function(){
		var accID = $("#rev_accounts").val();
		var url = "<?=$this->get_url(array("assetview"=>"ajax_variancereport","lib"=>"report","scroll"=>$this->get_scroll()));?>";
		var data = {"accID":accID};
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				if($("#result").hasClass("hidden")) $("#result").removeClass("hidden");
				$("#result").html("<?=$this->preloader()?>");
			},
			success:function(resp){
				$("#result").html(resp);
			},
			error:function(errObj,errMsg,xhr){
				alert(xhr);
			}
		});
		
	});
</script>