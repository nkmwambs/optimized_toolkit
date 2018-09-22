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
			
			<div class="col-sm-6" style="text-align: center;">
				<a href="<?php echo base_url().$segments[1].'/'.$segments[2];?>/<?=isset($segments[8])?"scroll_journal":"show_journal";?>/<?=$segments[4];?>/<?=$segments[5];?>/<?=$segments[6];?>/<?=isset($segments[8])?$segments[8]:0;?>" class="btn btn-default" class="btn btn-default">Back</a>
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
								
						
								<div style="display: none" id='btnDelRow' class="btn btn-default btn-icon icon-left hidden-print pull-left">
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
		
						<?php echo form_open("", array('id' => 'frm_voucher', 'class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data')); ?>
		
						<div class="row">	
						<div class="col-sm-12">		
						            <input type="hidden" value="<?php echo $segments[4];?>" id="KENo" name="KENo"/>
						            
						            <table id='tblVch' class='table'>
						            	<thead>
							                <tr>
							                    <th colspan="8"  style="text-align: center;"><?php echo $segments[4]; ?><br>Payment Voucher</th>
							                </tr>
										</thead>
										
										<tbody>
											<tr>
												<td id="error_msg" style="color:red;text-align: center;">
													<?php
													if (isset($msg))
														echo $msg;
													?>
												</td>
											</tr>
						                <tr>		               
								            <td colspan="3">
								            	<div class="form-group">
													<label class="control-label"><span style="font-weight: bold;">Date: </span></label>
													
														<div class="input-group">
															<input type="text"  data-provide="datepicker" name="TDate" id="TDate" class="form-control datepicker accNos" data-validate="required"  
																data-date-format="yyyy-mm-dd" data-start-date="<?=$segments[5];?>" 
																	data-end-date="<?=$segments[6];?>" readonly="readonly">
															
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
								            		<input type="text" class="form-control accNos" id="VNumber" name="VNumber" data-validate="required"  value="" readonly/>
								            	</div>
								            </td>
						
						                </tr>
						                <tr>
						                    <td colspan="8">
						                    	<div class="form-group">
						                    		<label for="Payee" class="control-label"><span style="font-weight: bold;">Payee/Vendor: </span></label>
						                    		<input type="text" class="form-control accNos" data-validate="required"  id="Payee" name="Payee"/>
						                    	</div>
						                    </td>
						                </tr>
						                <tr>
						                   <td colspan="8">
						                    	<div class="form-group">
						                    			<label for="Address" class="control-label"><span style="font-weight: bold;">Address: </span></label>
						                    		<input type="text" class="form-control accNos" data-validate="required"  id="Address" name="Address"/>
						                    	</div>
						                    </td>
						                </tr>    
						                <tr>
						                    
						                    <td colspan="4">
						                    	<div class="col-sm-10 form-group" id='VType'>
						                    		<label for="VTypeMain" class="control-label"><span style="font-weight: bold;">Voucher Type:</span></label>
								                        <select name="VTypeMain" id="VTypeMain" class="form-control accNos" data-validate="required">
								                            <option value="#">Select Voucher Type</option>
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
						                    		<input type="text" class="form-control accNos" data-validate="required" id="TDescription" name="TDescription"/>
						                    	</div>
						                    	
						                    </td>
						                </tr>
						                
						                </tbody>
						            </table>
					    
					    	</div>
					    </div>
		    
		    
		    <div class="row">
		    	<div class="col-sm-12">   
				        <table id="bodyTable" class="table table-bordered">
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
						            			<input class="form-control" type="text" id="totals" name="totals" readonly/>
						            		</div>
						            	</td>
						            </tr>
						        </table>
						    </div> 
					   </div>
					        <INPUT type="hidden" id="hidden" value=""/>
					    
					
	
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
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		startDate:'<?=$segments[5];?>',
		endDate:'<?=$segments[6];?>'
	});
</script>