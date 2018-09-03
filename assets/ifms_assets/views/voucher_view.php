<hr />
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">						
			
			<a href="<?php echo base_url().$segments[1].'/'.$segments[2];?>" class="btn btn-default">Back</a>
			
			<!-- <center> -->
			    <a onclick="PrintElem('#voucher_print')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			        Print Voucher
			        <i class="entypo-print"></i>
			    </a>
			<!-- </center> -->
			
			    <br><br>			    	
					   <div id="voucher_print"> 	
						<table  class="table table-striped datatable">
							<thead>
								<tr>
									<th colspan="6" style="text-align:center;"><?php echo $segments[4];?><br>Transaction Voucher</th>
								</tr>
							</thead>
							<tbody>
								
								<tr>
									<td  colspan="3"><span style="font-weight: bold;">Date: </span> <?php echo $voucher['details']['TDate'];?></td>
									<td  colspan="3"><span style="font-weight: bold;">Number: </span> <?php echo $voucher['details']['VNumber'];?></td>
								</tr>
								
								<tr>
									<td colspan="3"><span style="font-weight: bold;">Vendor/Payee: </span> <?php echo $voucher['details']['Payee'];?></td>
									<?php $chqNo = explode("-",$voucher['details']['ChqNo']);?>
									<td  colspan="3"><span style="font-weight: bold;">Cheque Number: </span> <?php echo $chqNo[0];?></td>
								</tr>
								
								<tr>
									<td  colspan="3"><span style="font-weight: bold;">Address: </span> <?php echo $voucher['details']['Address'];?></td>
									<td  colspan="3"><span style="font-weight: bold;">Voucher Type: </span> <?php echo $voucher['details']['VType'];?></td>
								</tr>
								
								<tr>
									<td colspan="6"><span style="font-weight: bold;">Description: </span> <?php echo $voucher['details']['TDescription'];?></td>
								</tr>
								
								<tr style="font-weight: bold;">
									<td>Quantity</td>
									<td colspan="2">Items Purchased/ Service Received</td>
									<td style="text-align: right;">Unit Cost</td>
									<td style="text-align: right;">Cost</td>
									<td  style="text-align: right;">Account</td>
								</tr>
								<?php
									$total = 0;
									foreach($voucher['body'] as $row){
								?>
									<tr>
										<td><?=$row['Qty'];?></td>
										<td colspan="2"><?=$row['Details']?></td>
										<td style="text-align: right;"><?=number_format($row['UnitCost'],2);?></td>
										<td style="text-align: right;"><?=number_format($row['Cost'],2);?></td>
										<td style="text-align: right;"><?=$row['AccNo'];?></td>
									</tr>
								<?php
										$total+=$row['Cost'];
									}
								?>
								
								<tr>
									<td style="font-weight: bold;" colspan="4">Totals</td>
									<td style="font-weight: bold;text-align: right;"><?=number_format($total,2);?></td>
									<td>&nbsp;</td>
								</tr>
								
								<tr>
									<td><span style="font-weight: bold;">Raised By</span></td>
									<td colspan="2"><span style="font-weight: bold;">Name: </span>  </td>
									<td colspan="3"><span style="font-weight: bold;">Signature: </span></td>
								</tr>
								<tr>
									<td colspan="3"><span style="font-weight: bold;">Verified By</span></td>
									<td colspan="3"><span style="font-weight: bold;">Approved By</span></td>
								</tr>
								<tr>
									<td>Name: </td><td colspan="2">Signature: </td> <td>Name: </td><td colspan="2">Signature</td>
								</tr>
								<tr>
									<td>Name: </td><td colspan="2">Signature: </td> <td>Name: </td><td colspan="2">Signature</td>
								</tr>
								
								<tr>
									<td>Name: </td><td colspan="2">Signature: </td> <td>Name: </td><td colspan="2">Signature</td>
								</tr>
							</tbody>
						</table>
						
				</div>
			</div>		
           
        </div>
    </div>

        </div>
    </div>
    
<hr />
<div class="row">
	<div class="col-sm-offset-1 col-sm-10 col-sm-offset-1">
		<?php echo "Time Elapsed to load page: ".$this->get_layout()->profiler." seconds";?>
	</div>
</div>

<hr />    
    

<script type="text/javascript" src="<?php echo $this->default_javascript_path.'/printThis.js';?>"></script>

<script type="text/javascript">
	function PrintElem(elem)
    {
        $(elem).printThis({ 
		    debug: false,              
		    importCSS: true,             
		    importStyle: true,         
		    printContainer: false,       
		    loadCSS: "", 
		    pageTitle: "Payment Voucher",             
		    removeInline: false,        
		    printDelay: 333,            
		    header: null,             
		    formValues: true          
		});
    }
</script>
