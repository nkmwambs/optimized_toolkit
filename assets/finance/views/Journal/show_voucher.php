<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<hr />
<?php
//print_r($voucher);
?>
<div class="row">
	<div class="<?=$this->get_column_size();?>">						
				
			<a href="#" onclick="javascript:go_back();" 
						class="btn btn-default btn-icon icon-left hidden-print">
							<?=$this->l('back');?>
			</a>
					
			<!-- <center> -->
			    <a onclick="PrintElem('#voucher_print')" class="btn btn-default btn-icon icon-left hidden-print pull-right">
			        <?=$this->l('print');?>
			        <i class="entypo-print"></i>
			    </a>
			<!-- </center> -->
			
			    <br><br>			    	
					   <div id="voucher_print"> 	
						<table  class="table table-striped datatable">
							<thead>
								<tr>
									<th colspan="8" style="text-align:center;"><?php echo $this->get_project_id();?><br><?=$this->l('transaction_voucher');?></th>
								</tr>
							</thead>
							<tbody>
								
								<tr>
									<td  colspan="5"><span style="font-weight: bold;"><?=$this->l('date');?>: </span> <?php echo $voucher['details']['TDate'];?></td>
									<td  colspan="3"><span style="font-weight: bold;"><?=$this->l('voucher_number');?>: </span> <?php echo $voucher['details']['VNumber'];?></td>
								</tr>
								
								<tr>
									<td colspan="5"><span style="font-weight: bold;"><?=$this->l('payee');?> </span> <?php echo $voucher['details']['Payee'];?></td>
									<?php $chqNo = explode("-",$voucher['details']['ChqNo']);?>
									<td  colspan="3"><span style="font-weight: bold;"><?=$this->l('cheque_number');?>: </span> <?php echo $chqNo[0];?></td>
								</tr>
								
								<tr>
									<td  colspan="5"><span style="font-weight: bold;"><?=$this->l('address');?>: </span> <?php echo $voucher['details']['Address'];?></td>
									<td  colspan="3"><span style="font-weight: bold;"><?=$this->l('voucher_type');?>: </span> <?php echo $voucher['details']['VType'];?></td>
								</tr>
								
								<tr>
									<td colspan="8"><span style="font-weight: bold;"><?=$this->l('details')?>: </span> <?php echo $voucher['details']['TDescription'];?></td>
								</tr>
								
								<tr style="font-weight: bold;">
									<td><?=$this->l('quantity');?></td>
									<td colspan="2"><?=$this->l('item_details');?></td>
									<td style="text-align: right;"><?=$this->l('unit_cost');?></td>
									<td style="text-align: right;"><?=$this->l('cost');?></td>
									<td  style="text-align: right;"><?=$this->l('account');?></td>
									<td  style="text-align: right;"><?=$this->l('budget_item');?></td>
									<td  style="text-align: right;"><?=$this->l('civ_id');?></td>
								</tr>
								<?php
									$total = 0;
									foreach($voucher['body'] as $row){
								?>
									<tr>
										<td><?=$row['Qty'];?></td>
										<td colspan="2">
											<?=$row['Details']?> 
											<?php
												if($row['tag_id']>0){
													echo "<span class='fa fa-binoculars' style='color:red;'></span></br>";
												}
												echo "<a href='".$this->get_url(array("assetview"=>'add_expensebreakdown','lib'=>'report',"voucher"=>$voucher['details']['VNumber']))."&scheduleID=".$row['scheduleID']."&budgetItem=".$row['tag_description']."'><u>[".$this->l('tracking_tag').": ".$row['tag_description']."]</u></a>";
											?>		
										</td>		
										<td style="text-align: right;"><?=number_format($row['UnitCost'],2);?></td>
										<td style="text-align: right;"><?=number_format($row['Cost'],2);?></td>
										<td style="text-align: right;"><?=$row['AccNo'];?></td>
										<td style="text-align: right;"><?=isset($row['scheduleDetail'])?$row['scheduleDetail']:"Budget Item Not Defined";?></td>
										<td style="text-align: right;"><?=$row['civaCode'];?></td>
									</tr>
								<?php
										$total+=$row['Cost'];
									}
								?>
								
								<tr>
									<td style="font-weight: bold;" colspan="5"><?=$this->l('total');?></td>
									<td style="font-weight: bold;text-align: right;"><?=number_format($total,2);?></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								
								<tr>
									<td><span style="font-weight: bold;"><?=$this->l('raised_by');?></span></td>
									<td colspan="5"><span style="font-weight: bold;">Name: </span>  </td>
									<td colspan="2"><span style="font-weight: bold;">Signature: </span></td>
								</tr>
								<tr>
									<td colspan="4"><span style="font-weight: bold;">Verified By</span></td>
									<td colspan="4"><span style="font-weight: bold;">Approved By</span></td>
								</tr>
								<tr>
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?>: </td> 
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?></td>
								</tr>
								<tr>
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?>: </td> 
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?></td>
								</tr>
								
								<tr>
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?>: </td> 
									<td colspan="3"><?=$this->l('name');?>: </td>
									<td colspan="1"><?=$this->l('signature');?></td>
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
