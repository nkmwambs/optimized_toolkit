<?php
//print_r($cheques_utilized);
//echo $success;
?>
<hr/>
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		
		<div class="row">
			<div class="col-sm-6" style="text-align: center;">
				<div class="form-group">
					<label class="control-label col-sm-3">Search a Voucher</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="VNumber" placeholder="Enter a voucher number" />
					</div>
					<div class="col-sm-2">
						<div id="go_btn" class="btn btn-default">Go</div>
					</div>
				</div>
			</div>
			
			<div class="col-sm-6">
				<a href="<?php echo base_url().$this->get_controller().'/'.$this->get_method();?>/<?=$this->get_second_extra_segment()!=""?"scroll_journal":"show_journal";?>/<?=$this->get_project_id();?>/<?=$this->get_start_date_epoch();?>/<?=$this->get_start_date_epoch();?>/<?=isset($segments[8])?$segments[8]:0;?>" class="btn btn-default btn-icon icon-left hidden-print pull-right" class="btn btn-default">Back <i class="entypo-left-dir"></i></a>
			</div>
				
		</div>
		
		<hr />
		
		<div class="row">
			<div class="col-sm-12">
	
				<div class="panel panel-primary" data-collapsed="0">
			       	<div class="panel-heading">
			           	<div class="panel-title" >
			           		<i class="entypo-plus-circled"></i>
								Payment Voucher
			           	</div>
			        </div>
			        
					<div class="panel-body" overflow: auto;">	
		
						
		
						<?php echo form_open("", array('id' => 'frm_voucher', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
		
						<div class="row">	
						<div class="col-sm-12">		
						            <input type="hidden" value="<?php echo $this->get_project_id();?>" id="icpNo" name="icpNo"/>
						            
						            <table id='tblVch' class='table'>
						            	<thead>
							                <tr>
							                    <th colspan="8"  style="text-align: center;"><?php echo $this->get_project_id(); ?><br>Payment Voucher</th>
							                </tr>
										</thead>
										
										<tbody>
											<tr>
												<td id="error_msg" style="color:red;text-align: center;">
													<?php
													if (isset($success))
														echo $success;
													?>
												</td>
											</tr>
						                <tr>		               
								            <td colspan="3">
								            	<div class="form-group">
													<label class="control-label"><span style="font-weight: bold;">Date: </span></label>
													
														<div class="input-group">
															<input type="text" name="TDate" id="TDate" class="form-control datepicker required" 
															data-validate="required" data-provide="datepicker" data-date-format="yyyy-mm-dd" 
															data-date-start-date="<?=date('Y-m-d',$voucher_date_range['end_date']);?>" 
															data-date-end-date="<?=date('Y-m-t',$voucher_date_range['end_date']);?>"  readonly="readonly" 
															value="<?=date('Y-m-d',$voucher_date_range['end_date']);?>"/>
															
															<div class="input-group-addon">
																<a href="#"><i class="entypo-calendar"></i></a>
															</div>
														</div>
													
												</div>
								            </td>
								            <td colspan="2">&nbsp;</td>
								            <td colspan="3">
								            	<div class="form-group">
								            		<label for='VNumber' class="control-label"><span style="font-weight: bold;">Voucher Number</span></label>	
								            		<input type="text" class="form-control required" id="VNumber" name="VNumber" data-validate="required"  value="<?=$voucher_number;?>" readonly/>
								            	</div>
								            </td>
						
						                </tr>
						                <tr>
						                    <td colspan="8">
						                    	<div class="form-group">
						                    		<label for="Payee" class="control-label"><span style="font-weight: bold;">Payee/Vendor: </span></label>
						                    		<input type="text" class="form-control required" data-validate="required"  id="Payee" name="Payee"/>
						                    	</div>
						                    </td>
						                </tr>
						                <tr>
						                   <td colspan="8">
						                    	<div class="form-group">
						                    			<label for="Address" class="control-label"><span style="font-weight: bold;">Address: </span></label>
						                    		<input type="text" class="form-control required" data-validate="required"  id="Address" name="Address"/>
						                    	</div>
						                    </td>
						                </tr>    
						                <tr>
						                    
						                    <td colspan="4">
						                    	<div class="col-sm-10 form-group" id='VType'>
						                    		<label for="VTypeMain" class="control-label"><span style="font-weight: bold;">Voucher Type:</span></label>
								                        <select name="VType" id="VTypeMain" class="form-control required" data-validate="required">
								                            <option value="">Select Voucher Type</option>
								                            <option value="PC">Payment By Cash</option>
								                            <option value="CHQ">Payment By Cheque</option>
								                            <option value="BCHG">Bank Adjustments</option>
								                            <option value="CR">Cash Received</option>					                            
								                            <option value="PCR">Petty Cash Rebanking</option>
								                        </select>
						                        </div>
						                    </td>
						                    
						                    
						                    <td colspan="2">
						                    	<div class="col-sm-10 form-group">
						                    		<label for="ChqNo" class="control-label"><span style="font-weight: bold;">Cheque Number:</span></label>
						                    			<input class="form-control" type="text" id="ChqNo" name="ChqNo" data-validate="number,minlength[2]"  readonly="readonly"/>
						                    	</div>
						                    </td>
						                    
						                 	<td colspan="2">
						                    	<div id="label-toggle-switch" for="reversal" class="col-sm-6"><span style="font-weight: bold;">Cheque Reversal</span> 
													<div class="make-switch switch-small" data-on-label="Yes" data-off-label="No">
															<input type="checkbox" id="reversal" name="reversal"/>
													</div>
												</div>		
						                    </td>
						                    
						                </tr>
						                
						                <tr>
						                   
						                    <td colspan="8">
						                    	<div class="form-group">
						                    			<label for="TDescription" class="control-label"><span style="font-weight: bold;">Description</span></label>
						                    		<input type="text" class="form-control required" data-validate="required" id="TDescription" name="TDescription"/>
						                    	</div>
						                    	
						                    </td>
						                </tr>
						                
						                </tbody>
						            </table>
					    
					    	</div>
					    </div>
					   	    
		    	
		    	<div class="row">
							<div class="col-sm-12">
								<div class="col-sm-1">
									<a href="#"   id="resetBtn" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								      	Reset
								    	 <i class="entypo-plus-circled"></i>
									</a>
								</div>
								
								<div class="col-sm-1">		
								<button type="submit" id="btnPostVch" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								     Post
								     <i class="entypo-thumbs-up"></i>
								</button>
								</div>
								
						
								<div id='btnDelRow' class="btn btn-default btn-icon icon-left hidden hidden-print pull-left">
								      Remove Item Row
								     <i class="entypo-minus-circled"></i>
								</div>
													
								<div class="col-sm-1">		
									<div id='addrow' class="btn btn-default btn-icon icon-left hidden-print pull-left">
									      New Item Row
									     <i class="entypo-plus-circled"></i>
									</div>
								</div>	
							</div>
						
						</div>
		    
		    <hr />
		    <div class="row">
		    	<div class="col-sm-12">   
				        <table id="bodyTable" class="table table-striped">
				        	<thead>
					            <tr style="font-weight: bold;">
					                <th>Check</th><th>Quantity</th><th>Items Purchased/ Services Received</th><th>Unit Cost</th><th>Cost</th><th>Account</th><th>Budget Item</th></th><th>CIV Code</th>
					            </tr>
					         </thead>
					         <tbody>
					         	
					         </tbody>   
				        </table>
				       
				    </div>
				</div> 
			    
			    
					 <div class="row">
					        <div class="col-sm-12">
						        <table id="" class="table">
						            <tr>
						            	<td colspan="5">
						            		<div class="form-group pull-right">
						            			<label for='totals' class="control-label"><span style="font-weight: bold;">Totals:</span></label>
						            			<input class="form-control" type="text" id="totals" name="totals" value="0" readonly="readonly" />
						            		</div>
						            	</td>
						            </tr>
						        </table>
						    </div> 
					   </div>					    
					
	
						</form>    
					</div>
					
					
					<div class="panel-footer">
						
						<div class="row">
							<div class="col-sm-12">
						
								<div data-toogle="modal" data-target=""  id="resetBtn"  class="btn btn-default btn-icon icon-left hidden-print pull-left">
								      Reset
								     <i class="entypo-plus-circled"></i>
								</div>
						
							</div>
			
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<hr />
	</div>
</div>	

<script>

	$('.datepicker').datepicker();

	
	$("#VTypeMain").mousedown(function(ev){
		var details_length = $("#bodyTable tbody").children().length;
		var detail = $(".detail");
		
		if(details_length > 0 ){
			alert("You can't change the voucher type when there is a detail row");
			detail.css("border","1px red solid");
			ev.preventDefault();
		}else{
			detail.prop("style","");
		}
		
	});
	
	$("#VTypeMain").change(function(){
		
		var val = $(this).val();
		add_row();
		if(val==='CHQ'){
			$('#ChqNo').removeAttr('readonly');
		}else{
			$('#ChqNo').val("");
			$('#ChqNo').prop('readonly','readonly');
		}
	});
	
	$("#addrow").click(function(){
		add_row();
	});
	
	function add_row(){
		var elem = $("#bodyTable tbody");
		var VType = $("#VTypeMain");
		var accounts_option = "";
		
				if(VType.val() == "PC"){
					<?php $accounts_array = json_encode($accounts['expense']);?>
					var obj = <?=$accounts_array;?>;	
					for (i=0;i<obj.length;i++){
					 		accounts_option+="<option value='"+obj[i].AccNo+"'>"+obj[i].AccText+" - "+obj[i].AccName+"</option>";
					}
				}
				else if(VType.val() == "CR"){
					<?php $accounts_array = json_encode($accounts['revenue']);?>
					var obj = <?=$accounts_array;?>;	
					for (i=0;i<obj.length;i++){
					 		accounts_option+="<option value='"+obj[i].AccNo+"'>"+obj[i].AccText+" - "+obj[i].AccName+"</option>";
					}
				}
				
				else if(VType.val() == "CHQ"){
					<?php $accounts_array = json_encode($accounts['expense']);?>
					var obj = <?=$accounts_array;?>;	
					for (i=0;i<obj.length;i++){
					 		accounts_option+="<option value='"+obj[i].AccNo+"'>"+obj[i].AccText+" - "+obj[i].AccName+"</option>";
					}
				}
				
				else if(VType.val() == "BCHG"){
					<?php $accounts_array = json_encode($accounts['expense']);?>
					var obj = <?=$accounts_array;?>;	
					for (i=0;i<obj.length;i++){
					 		accounts_option+="<option value='"+obj[i].AccNo+"'>"+obj[i].AccText+" - "+obj[i].AccName+"</option>";
					}
				}
				
				else if(VType.val() == "PCR"){
					<?php $accounts_array = json_encode($accounts['rebanking']);?>
					var obj = <?=$accounts_array;?>;	
					for (i=0;i<obj.length;i++){
					 		accounts_option+="<option value='"+obj[i].AccNo+"'>"+obj[i].AccText+" - "+obj[i].AccName+"</option>";
					}
				}	
				
								 
					
				
		
		var row = "<tr>"
					+"<td><input type='checkbox' class='check detail'/></td>"
					+"<td><input type='text' class='form-control detail qty calculate required' name='Qty[]'/></td>"
					+"<td><input type='text' class='form-control detail desc required' name='Details[]'/></td>"
					+"<td><input type='text' class='form-control detail unit calculate required' name='UnitCost[]'/></td>"
					+"<td><input type='text' class='form-control detail cost required' readonly='readonly' value='0' name='Cost[]'/></td>"
					+"<td><select class='form-control detail accounts required' name='AccNo[]'>"
					+"<option value=''>Select Account</option>"
					+accounts_option
					+"</select></td>"
					+"<td><select class='form-control detail budget_items required' name='scheduleID[]'>"
					+"<option value=''>Select Bugdet Item</option>"
					+"</select></td>"
					+"<td><input type='text' class='form-control detail civ' name='civaCode[]' readonly='readonly'/></td>"
					+"</tr>";
		
		
		if(VType.val()){
			elem.append(row);
			VType.prop("style","");
		}else{
			VType.css("border","1px red solid");
			alert("Please choose an account");
		}

	}
	
	$(document).on("click",".check",function(){
		
		var checked = $(".check:checked").length;
		if(checked>0){
			$("#btnDelRow").removeClass("hidden");	
		}else{
			$("#btnDelRow").addClass("hidden");
		}
	});

	
	$(document).on("change", ".accounts", function(){
		
		var account = $(this).val();
		var budget_item_select = $(this).parent().next();
		
		var budget_item_options = "";
		
		var budget_item_options_default = "";
		
		<?php $accounts_array = json_encode($accounts['expense']);?>
		
		var obj_accounts = <?=$accounts_array;?>;
		
		var budgeted_account = false;
		
		for(i=0;i<obj_accounts.length;i++){
			if(obj_accounts[i].AccNo == account){
				if(obj_accounts[i].budget == 1){
					budgeted_account = true;
				}
			}
		}
		
		if(budgeted_account == false){
			budget_item_options_default+="<option value='0'>Budget Not Required</option>";
		}else{
			budget_item_options_default+="<option value=''>Missing Budget</option>";
		}
		
		budget_item_select.html("<select class='form-control detail required' name='scheduleID[]'>"
								+"<option value=''>Select Bugdet Item</option>"
								+budget_item_options_default
								+"</select>");
								
								
				
		<?php $approved_budget = json_encode($approved_budget);?>
		
		var obj = <?=$approved_budget;?>;
		var select_obj = obj[account];	

		for (i=0;i<select_obj.length;i++){
		 	budget_item_options+="<option value='"+select_obj[i].scheduleID+"'>"+select_obj[i].details+"</option>";
		}
				
		
		var select_budget_items = 	"<select class='form-control detail required' name='scheduleID[]'>"
									+"<option value=''>Select Bugdet Item</option>"
									+ budget_item_options
									+"</select>";
		
									
		budget_item_select.html(select_budget_items);								
		
	} );
		
	
	$("#btnDelRow").click(function(){
		var elem = $("#bodyTable tbody");
		
		$(".check").each(function(){
			if($(this).is(":checked")){
				$(this).parent().parent().remove();
			}
			
			var checked = $(".check:checked").length;
			if(checked>0){
				$("#btnDelRow").removeClass("hidden");	
			}else{
				$("#btnDelRow").addClass("hidden");
			}
		});
		
		calculate_voucher_totals();
		
	});
	
	
	
	$(window).keyup(function(e) {
	  	if (e.ctrlKey) {
	        if (e.keyCode == 73) { //CTRL + i
	            add_row();
	        }
	        if(e.keyCode == 88){// CTRL + x
	        	$("#bodyTable tbody tr:last").remove();
	        	
	        	calculate_voucher_totals();
	        }
	    }
	});		
	
	
	$("#ChqNo").change(function(){
		var chqno = $(this).val();
		var bank_code = <?=$bank_code;?>;
		var code_chqno = chqno+"-"+bank_code;
		
		var obj = <?=json_encode($cheques_utilized);?>;
		
		var chqno_exists = false;
		
		for(i=0;i<obj.length;i++){
			if(obj[i] == code_chqno){
				chqno_exists = true;
			}
		}
		
		if(chqno_exists){
			alert("Cheque Number "+chqno+" already exists!");
			$("#ChqNo").val("");
		}
		
	});
	
	$(document).on("change",".calculate",function(){

		var total_input = $("#totals");
		
		if($(this).hasClass("qty")){
			var qty = $(this);
			var unit = $(this).parent().nextAll().eq(1).children();
			var cost = $(this).parent().nextAll().eq(2).children();
		}else if($(this).hasClass("unit")){
			var unit = $(this);
			var qty = $(this).parent().prev().prev().children();// To Try prevAll.eq(2)
			var cost = $(this).parent().next().children();
		}
		
		var total = 0;
		
		if($.isNumeric(unit.val()) && $.isNumeric(qty.val())){
			total = unit.val()*qty.val();
			cost.val(total);
		}
		

		calculate_voucher_totals();
				

	});
	
	
	function calculate_voucher_totals(){
		var voucher_total = 0;

		$(".cost").each(function(){
			if($.isNumeric($(this).val())){
				voucher_total+=parseFloat($(this).val());
			}
					
		});
				
		$("#totals").val(voucher_total);
	}
	
	
	$(document).on("submit","#frm_voucher",function(){
		
		if($("#bodyTable tbody").length == 0){
			alert("Voucher lacks details");
			return false;
		}
		
		if($("#VTypeMain").val()=="CHQ" && $("#ChqNo").val()==""){
			alert("You must provide a cheque number");
			$("#ChqNo").css("border","1px red solid");
			return false;
		}
		
		var required_count = 0;
		
		$(".required").each(function(){
			if($(this).val()==""){
				required_count++;
				$(this).css("border","1px red solid");
			}
		});
		
		if(required_count>0){
			alert(required_count+" required fields are empty");
			return false;
		}
	});
</script>