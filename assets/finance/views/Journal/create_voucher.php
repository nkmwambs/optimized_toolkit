<?php
//print_r($this->budget_grouped_items());
?>
<style> .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; } .toggle.ios .toggle-handle { border-radius: 20px; } </style>
<hr/>
<div class="row">
	<div class="<?=$this->get_column_size();?>">
		
		<div class="row">
			<div class="col-sm-6" style="text-align: center;">
				<div class="form-group">
					<label class="control-label col-sm-3">Search a Voucher</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="SearchVNumber" placeholder="<?=$this->l('enter_voucher_number');?>" />
					</div>
					<div class="col-sm-2">
						<div id="go_btn" class="btn btn-default">Go</div>
					</div>
				</div>
			</div>
			
			<div class="col-sm-6">
					<a href="#" onclick="javascript:go_back();" 
						class="btn btn-default btn-icon icon-left hidden-print pull-right">
							<?=$this->l('back');?>
					</a>
			</div>
				
		</div>
		
		<hr />
		
		<div class="row">
			<div class="col-sm-12">
	
				<div class="panel panel-primary" data-collapsed="0">
			       	<div class="panel-heading">
			           	<div class="panel-title" >
			           		<i class="entypo-plus-circled"></i>
								<?=$this->l('transaction_voucher');?>
			           	</div>
			        </div>
			        
					<div class="panel-body" overflow: auto;">	
		
						
		
						<?php echo form_open($this->get_url(array('assetview'=>'create_voucher')), array('id' => 'frm_voucher', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
		
						<div class="row">	
						<div class="col-sm-12">		
						            <input type="hidden" value="<?php echo $this->get_project_id();?>" id="icpNo" name="icpNo"/>
						            
						            <table id='tblVch' class='table'>
						            	<thead>
							                <tr>
							                    <th colspan="8"  style="text-align: center;"><?php echo $this->get_project_id(); ?><br><?=$this->l("transaction_voucher")?></th>
							                </tr>
										</thead>
										
										<tbody>
											<tr>
												<td colspan="8" id="error_msg" style="text-align: center;">
													<?php
														if (isset($success)){
													?>
														<div class="label label-danger"><?php echo $success;?></div>
													<?php	
														unset($success);
														}
														//$this->CI->session->flashdata('flash_message');
													?>
												</td>
											</tr>
										
										<tr>
						                    
						                    <td colspan="2">
						                    	<div class="col-sm-12 form-group" id='VType'>
						                    		<label for="VTypeMain" class="control-label"><span style="font-weight: bold;"><?=$this->l('voucher_type')?>:</span></label>
								                        <select name="VType" id="VTypeMain" class="form-control required" data-validate="required">
								                            <option value=""><?=$this->l("select_voucher_type")?></option>
								                            <option value="PC"><?=$this->l('payment_by_cash');?></option>
								                            <option value="CHQ"><?=$this->l('payment_by_cheque')?></option>
								                            <option value="BCHG"><?=$this->l('bank_adjustments');?></option>
								                            <option value="CR"><?=$this->l('cash_received');?></option>					                            
								                            <option value="PCR"><?=$this->l('petty_cash_rebanking')?></option>
								                        </select>
						                        </div>
						                    </td>
						                    
						                    
						                    <td colspan="2">
						                    	<div class="col-sm-12 form-group">
						                    		<label for="ChqNo" class="control-label"><span style="font-weight: bold;"><?=$this->l('cheque_number');?>:</span></label>
						                    			<input class="form-control" type="text" id="ChqNo" name="ChqNo" data-validate="number,minlength[2]"  readonly="readonly"/>
						                    	</div>
						                    </td>
						                    
						                    <td colspan="2">
						                    	<div class="col-sm-12 form-group">
						                    		<label class="control-label"><?=$this->l('cheque_leaves');?></label>
						                    		<select id="cheque_selector" disabled="disabled" class="form-control">
						                    			<option><?=$this->l('select_cheque');?></option>
						                    			<?php 
						                    				foreach($unused_cheque_leaves as $cheque){
						                    			?>
						                    				<option><?=$cheque;?></option>
						                    			<?php
															}
						                    			?>
						                    		</select>
						                    	</div>	
						                    </td>
						                    
						                 	<td colspan="2">
						                    	<label  for="reversal" class="col-sm-12"><span style="font-weight: bold;"><?=$this->l('cheque_reversal');?></span> 
													<div class="col-sm-12">
															<input type="checkbox" data-on="<?=$this->l('enabled');?>" data-off="<?=$this->l('disabled');?>" data-onstyle="danger" 
																data-offstyle="info" data-style="ios" data-toggle="toggle" id="reversal" 
																	disabled name="reversal"/>
													</div>
												</label>		
						                    </td>
						                    
						                </tr>
											
						                <tr>		               
								            <td colspan="3">
								            	<div class="form-group">
													<label class="control-label"><span style="font-weight: bold;"><?=$this->l('date');?>: </span></label>
													
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
								            		<label for='VNumber' class="control-label"><span style="font-weight: bold;"><?=$this->l('voucher_number');?></span></label>	
								            		<input type="text" class="form-control required" id="VNumber" name="VNumber" data-validate="required"  value="<?=$voucher_number;?>" readonly/>
								            	</div>
								            </td>
						
						                </tr>
						                <tr>
						                    <td colspan="8">
						                    	<div class="form-group">
						                    		<label for="Payee" class="control-label"><span style="font-weight: bold;"><?=$this->l('payee');?>: </span></label>
						                    		<input type="text" class="form-control required" data-validate="required"  id="Payee" name="Payee"/>
						                    	</div>
						                    </td>
						                </tr>
						                <tr>
						                   <td colspan="8">
						                    	<div class="form-group">
						                    			<label for="Address" class="control-label"><span style="font-weight: bold;"><?=$this->l('address');?>: </span></label>
						                    		<input type="text" class="form-control required" data-validate="required"  id="Address" name="Address"/>
						                    	</div>
						                    </td>
						                </tr>    
						                
						                
						                <tr>
						                   
						                    <td colspan="8">
						                    	<div class="form-group">
						                    			<label for="TDescription" class="control-label"><span style="font-weight: bold;"><?=$this->l('details')?></span></label>
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
								      	<?=$this->l('reset');?>
								    	 <i class="entypo-plus-circled"></i>
									</a>
								</div>
								
								<div class="col-sm-1">		
								<button type="submit" id="btnPostVch" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								     <?=$this->l('post');?>
								     <i class="entypo-thumbs-up"></i>
								</button>
								</div>
								
						
								<div id='btnDelRow' class="btn btn-default btn-icon icon-left hidden hidden-print pull-left">
								      <?=$this->l('remove_row');?>
								     <i class="entypo-minus-circled"></i>
								</div>
													
								<div class="col-sm-1">		
									<div id='addrow' class="btn btn-default btn-icon icon-left hidden-print pull-left">
									      <?=$this->l('new_row');?>
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
					                <th><?=$this->l('check');?></th><th><?=$this->l('quantity');?></th>
					                <th><?=$this->l('item_details');?></th>
					                <th><?=$this->l('unit_cost');?></th>
					                <th><?=$this->l('cost');?></th>
					                <th><?=$this->l('account');?></th>
					                <th><?=$this->l('budget_item');?></th>
					                <th><?=$this->l('civ_id');?></th>
					            </tr
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
						            			<label for='totals' class="control-label"><span style="font-weight: bold;"><?=$this->l('total');?>:</span></label>
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
	
	$("#cheque_selector").change(function(){
		$('#ChqNo').val($(this).val());
	});
	
	$("#VTypeMain").change(function(){
		
		var val = $(this).val();
		add_row();
		if(val==='CHQ'){
			$('#ChqNo').removeAttr('readonly');
			$("#cheque_selector").removeAttr("disabled");
		}else{
			$('#ChqNo').val("");
			$('#ChqNo').prop('readonly','readonly');
			$("#cheque_selector").prop("disabled","disabled");
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
					+"<option value=''><?=$this->l('select_account');?></option>"
					+accounts_option
					+"</select></td>"
					+"<td><select class='form-control detail budget_items required' name='scheduleID[]'>"
					+"<option value=''><?=$this->l('select_bugdet_item');?></option>"
					+"</select></td>"
					+"<td><input type='text' class='form-control detail civ' name='civaCode[]' readonly='readonly' value='0'/></td>"
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
		
		//$(".selectBoxIt").selectBoxIt();
		
		var account = $(this).val();
		
		var civa_input = $(this).parent().next().next().children();
		
		var all_accounts = <?=json_encode($accounts);?>
		
		var civaCode = 0;
		
		$.each(all_accounts,function(i,elem){
			$.each(elem,function(j,el){
				if(el.AccNo == account && el.civaCode){
					civaCode = el.civaCode;
				}
			});
		});
		
		civa_input.val(civaCode);
		
		
		
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
			var expense_tracking_tag = "";
			if(!!select_obj[i].tag_id){
				expense_tracking_tag = " [<?=$this->l('tracking_tag');?>: "+select_obj[i].tag_description+"]";
			}
		 	budget_item_options+="<option value='"+select_obj[i].scheduleID+"'>"+select_obj[i].details+expense_tracking_tag+"</option>";
		}
				
		
		var select_budget_items = 	"<select class='form-control selectBoxIt detail required' name='scheduleID[]'>"
									+"<option value=''>Select Bugdet Item</option>"
									+ budget_item_options
									+"</select>";
		
									
		budget_item_select.html(select_budget_items);	
									
		
	} );
	
	// $(document).on("change",".accs",function(){
		// alert($(this).val());
		// var msg = "Revenue";
		// if($("#VTypeMain").val() == "PC" || $("#VTypeMain").val() == "CHQ"){
			// msg = "Expense";
		// }
			// alert(msg);
// 		
	// });
		
	
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
		
		var booklet_range = <?=json_encode($this->list_range_of_cheque_leaves());?>
		
		
		var obj = <?=json_encode($cheques_utilized);?>;

		var chqno_exists = false;
		
		var totals = 0;
		
		for(i=0;i<obj.length;i++){
			if(obj[i].ChqNo == code_chqno){
				chqno_exists = true;
				//totals = obj[i].totals;
			}
		}
		
		if(chqno_exists){

			var cnf = confirm("Cheque Number "+chqno+" already exists! Do you want to reverse it?");
			
			if(!cnf){
				alert("Process Aborted!");
				$("#ChqNo").val("");
			}else{
				$("#reversal").removeAttr("disabled");
				$('#reversal').bootstrapToggle('on')	
				$("#reversal").prop("disabled","disabled");
				//alert(totals);
				$("#Payee").val("<?=$this->get_project_id();?>");$("#Payee").prop("readonly","readonly");
				$("#Address").val("<?=$this->get_project_id();?>");$("#Address").prop("readonly","readonly");
				$("#ChqNo").val("0");$("#ChqNo").prop("readonly","readonly");
				$("#TDescription").val("Reversal of Cheque Number "+chqno);$("#TDescription").prop("readonly","readonly");
				$("#addrow").addClass("hidden");
				
				var body = $("#bodyTable tbody");
				
				//Remove extra rows first
				$("#bodyTable tbody tr:last").remove();
				
				if(body.children().length == 0){
				
					var data = {"icpNo":"<?=$this->get_project_id();?>","ChqNo":chqno};
						$.ajax({
							url:'<?php echo base_url($this->get_controller().'/'.$this->get_method().'/?assetview=ajax_get_cheque_details/');?>',
							data:data,
							type:"POST",
							success:function(resp){
								var resp_obj = JSON.parse(resp);
								var total_cost = 0;
								var row = "";
								for(i=0;i<resp_obj.length;i++){
									total_cost+=parseFloat(resp_obj[i].Cost);
									row += "<tr>"
												+"<td><input disabled type='checkbox' class='check detail'/></td>"
												+"<td><input readonly='readonly' value='"+resp_obj[i].Qty+"' type='text' class='form-control detail qty calculate required' name='Qty[]'/></td>"
												+"<td><input readonly='readonly' value='"+resp_obj[i].Details+"' type='text' class='form-control detail desc required' name='Details[]'/></td>"
												+"<td><input readonly='readonly' value='-"+resp_obj[i].UnitCost+"' type='text' class='form-control detail unit calculate required' name='UnitCost[]'/></td>"
												+"<td><input readonly='readonly' value='-"+resp_obj[i].Cost+"' type='text' class='form-control detail cost required' name='Cost[]'/></td>"
												+"<td><input readonly='readonly' value='"+resp_obj[i].AccNo+"' type='text' class='form-control detail accounts required' name='AccNo[]' /></td>"
												+"<td><input readonly='readonly' value='"+resp_obj[i].scheduleID+"' type='text' class='form-control detail budget_items required' name='scheduleID[]' /></td>"
												+"<td><input readonly='readonly' value='"+resp_obj[i].civaCode+"' type='text' class='form-control detail civ' name='civaCode[]'/></td>"
												+"</tr>";
									body.append(row);			
								}
								$("#TDescription").val($("#TDescription").val()+". Voucher Number "+resp_obj[0].VNumber);
								$("#totals").val(-total_cost);
							},
							error:function(xhr,msg,error){
								alert(error);
							}	
						});	
				}
				
				
			}
		}
		else{
			chqno_exists = false;
			for(i=0;i<booklet_range.length;i++){
				if(booklet_range[i] == chqno){
					chqno_exists = true;
					return false;
				}
			}
			
			if(!chqno_exists){
				alert("You can't use a cheque number not in the current cheque booklet or your leaves are all used");
				$(this).val("");
			}
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
		
		var url ='<?php echo $this->get_url(array('assetview'=>"show_voucher"));?>&voucher='+$("#VNumber").val();
		
		if($("#bodyTable tbody").length == 0){
			//alert("Voucher lacks details");
			show_alert("Voucher lacks details");
			return false;
		}
		
		if($("#VTypeMain").val()=="CHQ" && $("#ChqNo").val()==""){
			//alert("You must provide a cheque number");
			show_alert("You must provide a cheque number");
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
			//alert(required_count+" required fields are empty");
			show_alert(required_count+" required fields are empty");
			return false;
		}
	});
	
	$("#reversal").change(function(){
		//alert($(this).is(":checked"));
	});

$("#go_btn").click(function(){
	var url ='<?php echo $this->get_url(array('assetview'=>"show_voucher"));?>&voucher='+$("#SearchVNumber").val();
	showAjaxModal(url);
})	


$('#error_msg').delay(3000).fadeOut('slow');

</script>

<script> $(function() { $('#reversal').bootstrapToggle(); }) </script>

