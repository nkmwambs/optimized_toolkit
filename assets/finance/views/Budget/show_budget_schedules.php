<?php
include "utility_open_standalone.php";
?>
<style>
	.selected{
		background-color: #E5E7E9  ;
	}
</style>
<?php
//print_r($budget_items);
?>
<div class="row hidden-print">
	<div class="col-xs-12">
		<div class="form-group">
			<!-- <div class="btn btn-default" id="merge_items">Merge Items</div> -->
			<label class="col-xs-2 control-label">Status Filters:</label>
								
				<div class="col-xs-6">
					<select id="approval_status" name="approval_status[]" class="form-control select2" multiple>
						<option value="0" selected="selected">Draft</option>
						<option value="1" selected="selected">Submitted</option>
						<option value="2" selected="selected">Approved</option>
						<option value="3" selected="selected">Declined</option>
						<option value="4" selected="selected">Reinstated</option>
						<option value="5" selected="selected">Allow Delete</option>	
					</select>
									
				</div>
				<div class="col-xs-2">
					<button class="btn btn-default" id="btn_filter">Filter</button>
				</div>
		</div>
	</div>
</div>
<hr/>
<div class="row">
		<div class="col-xs-12">
			<table class="table table-bordered" id="schedules_table">
				<thead>
					<tr><th colspan="22"><?=$this->l("budget_schedules");?></th></tr>
				</thead>
				<tbody>
					<?php
					foreach($budget_items as $parentAccID=>$schedule){
					?>
						<tr>
							<th colspan="22"><?=$income_accounts[$parentAccID]['AccText'].' - '.$income_accounts[$parentAccID]['AccName'];?></th>
						</tr>
						
					<?php	
										
					foreach($schedule as $account=>$row){
					?>
						<tr>
							<td colspan="22"><?=$row[0]['AccText'].' - '.$row[0]['AccName'];?></td>
						</tr>
						
						<tr>
							<th class="hidden-print"><?=$this->l('action');?></th>
							<th><?=$this->l('details');?></th>
							<th><?=$this->l('quantity');?></th>
							<th><?=$this->l('unit_cost');?></th>
							<th><?=$this->l('often');?></th>
							<th><?=$this->l('total');?></th>
							<th class="hidden-print"><?=$this->l('validate');?></th>
							<?php
								foreach($this->get_range_of_fy_months() as $month){
							?>
									<th><?=$this->get_month_name_from_number(ltrim($month,"0"));?></th>
							<?php		
								}
							?>
							<th><?=$this->l('status');?></th>
							<th class="hidden-print"><?=$this->l('submit_date');?></th>
							<th class="hidden-print"><?=$this->l('last_action_date');?></th>
						</tr>
					<?php	
					$i = 0;
					foreach($row as $cols){
					?>
						<tr id="row_<?=$cols['scheduleID']?>" class="row_<?=$cols['approved']?> itemrows hide_row">
							<td class="hidden-print">
								<div class="btn-group">
									<?php
									//array("Draft","Submitted","Approved","Declined","Reinstated","Allow Delete");
										$btn_color = "default"; 
										$label = $this->l('allow_delete');
										switch ($cols['approved']){
											case 0:
												$btn_color = "warning";	
												$label = $this->l('draft');
												break;
											case 1:
												$btn_color = "info";
												$label = $this->l('submitted');
												break;
											case 2:
												$btn_color = "success";
												$label = $this->l('approved');
												break;	
											case 3:
												$btn_color = "danger";
												$label = $this->l('declined');
												break;		
											case 4:
												$btn_color = "primary";
												$label = $this->l('reinstated');
												break;	
											default:
												$btn_color = "danger";
												$label = $this->l('allow_delete');
																									
										}
									?>
							         <button type="button" title="<?=$label;?>" id="action_<?=$cols['scheduleID']?>" class="btn btn-<?=$btn_color;?> btn-sm dropdown-toggle action_<?=$cols['approved']?>" data-toggle="dropdown">
							               <?php echo $this->l('action');?> - <?=$label;?> <span class="caret"></span>
							         </button>
							         
							         <ul class="dropdown-menu dropdown-default pull-left" role="menu">
							            <?php
							            	if($cols['approved'] == 0 || $cols['approved'] == 4){
							            ?>
							            
							            <li class="editactionitem_<?=$cols['scheduleID']?> editactionitem_<?=$cols['approved']?>">
							            	<a href="<?=$this->get_url(array("assetview"=>'edit_budget_item','lib'=>'budget',"voucher"=>$cols['scheduleID']));?>" onclick=''>
							                 	<i class="fa fa-pencil"></i>
													<?php echo $this->l('edit');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider editactionitem_<?=$cols['scheduleID']?> editactionitem_<?=$cols['approved']?>"></li>
							            
							            <?php
											}
							            ?>
							            
							            <?php
							            	if($cols['approved'] == 3){
							            ?>
							            
							            <li class="reinstateactionitem_<?=$cols['scheduleID']?> reinstateactionitem_<?=$cols['approved']?>">
							            	<a href="<?=$this->get_url(array("assetview"=>'reinstate_budget_item','lib'=>'budget',"voucher"=>$cols['scheduleID']));?>" onclick=''>
							                 	<i class="fa fa-reply"></i>
													<?php echo $this->l('reinstate');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider reinstateactionitem_<?=$cols['scheduleID']?> reinstateactionitem_<?=$cols['approved']?>"></li>
							            
							            <?php
											}
							            ?>
							             
							             <?php
							             	if($cols['approved'] == 0){
							             ?>
							             	<li class="submitactionitem_<?=$cols['scheduleID']?> submitactionitem_<?=$cols['approved']?>">
								            	<a href="#row_<?=$cols['scheduleID']?>" onclick='submit_budget_item("<?=$cols['scheduleID']?>");'>
								                 	<i class="fa fa-send"></i>
														<?php echo $this->l('submit');?>
								                </a>
								             </li>
							             
							            	 <li class="divider submitactionitem_<?=$cols['scheduleID']?> submitactionitem_<?=$cols['approved']?>"></li>
							             <?php		
							             	}
							             ?>
							             
							             <?php
							             	if($cols['approved'] == 0){
							             ?>
							             	<li class="deleteactionitem_<?=$cols['scheduleID']?> deleteactionitem_<?=$cols['approved']?>">
								            	<a href="#row_<?=$cols['scheduleID']?>" onclick='delete_budget_item(this,"<?=$cols['scheduleID']?>");'>
								                 	<i class="fa fa-trash"></i>
														<?php echo $this->l('delete');?>
								                </a>
								             </li>
							             
							            	 <li class="divider deleteactionitem_<?=$cols['scheduleID']?> deleteactionitem_<?=$cols['approved']?>"></li>
							             <?php		
							             	}
							             ?>
							             
							             <?php
							             	if($cols['approved'] == 5){
							             ?>
							             	<li class="allowdeleteactionitem_<?=$cols['scheduleID']?> allowdeleteactionitem_<?=$cols['approved']?>">
								            	<a href="#row_<?=$cols['scheduleID']?>" onclick='delete_budget_item(this,"<?=$cols['scheduleID']?>");'>
								                 	<i class="fa fa-eraser"></i>
														<?php echo $this->l('allow_delete');?>
								                </a>
								             </li>
							             
							            	 <li class="divider allowdeleteactionitem_<?=$cols['scheduleID']?> allowdeleteactionitem_<?=$cols['approved']?>"></li>
							             <?php		
							             	}
							             ?>
							             
							             <?php
							             	if($cols['approved'] == 1){
							             ?>
							             <li class="approvalactionitem_<?=$cols['scheduleID']?> approvalactionitem_<?=$cols['approved']?>">
							            	
							            	<a href="#row_<?=$cols['scheduleID']?>" id="link_<?=$cols['scheduleID']?>_<?=$parentAccID."_".$account."_".$i;?>" onclick="approve_budget_item(this);">							                 								            
							                 	<i class="fa fa-thumbs-up"></i>
													<?php echo $this->l('approval');?>
							                </a>
							             </li>
							             
							             <li class="divider approvalactionitem_<?=$cols['scheduleID']?> approvalactionitem_<?=$cols['approved']?>"></li>
							             
							             <li class="declineactionitem_<?=$cols['scheduleID']?> declineactionitem_<?=$cols['approved']?>">
							            	
							            	<a href="#row_<?=$cols['scheduleID']?>" id="link_<?=$cols['scheduleID']?>_<?=$parentAccID."_".$account."_".$i;?>" onclick="decline_budget_item(this);">							                 								            
							                 	<i class="fa fa-thumbs-down"></i>
													<?php echo $this->l('decline');?>
							                </a>
							             </li>
							             
							             <li class="divider declineactionitem_<?=$cols['scheduleID']?> declineactionitem_<?=$cols['approved']?>"></li>
							             
							             <?php
											}
							             ?>
							             <li>
							            	
							            	<a href="#row_<?=$cols['scheduleID']?>" id="link_<?=$cols['scheduleID']?>_<?=$parentAccID."_".$account."_".$i;?>" onclick="show_budget_notes(this);">							                 								            
							                 	<i class="fa fa-book"></i>
													<?php echo $this->l('notes');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider"></li>
							             
							             <li>
							            	<a href="#row_<?=$cols['scheduleID']?>" onclick="showAjaxModal('<?=$this->get_url(array("assetview"=>'show_budget_comments','lib'=>'budget','voucher'=>$cols['scheduleID']));?>');">
							                 	<i class="fa fa-comments-o"></i>
													<?php echo $this->l('comments');?>
							                </a>
							             </li>
							             							                        
							             <li class="divider"></li>
         
							         </ul>
							      </div> 
							</td>
							<td><?=$cols['details'];?></td>
							<td><?=$cols['qty'];?></td>
							<td style="text-align: right;"><?=number_format($cols['unitCost'],2);?></td>
							<td><?=$cols['often'];?></td>
							<td style="text-align: right;"><?=number_format($cols['totalCost'],2);?></td>
							<td class="hidden-print"><div class="label label-success">Valid</div></td>

							<?php
							$range = range(1, 12);
								foreach($range as $month){
							?>
									<td style="text-align: right;"><?=number_format($cols["month_".ltrim($month,"0")."_amount"],2);?></td>

							<?php		
								}
							?>
							<td id="status_<?=$cols['scheduleID']?>" class="status_<?=$cols['approved']?>"><?=ucfirst($budget_status[$cols['approved']]);?></td>
							<td id="submitteddate_<?=$cols['scheduleID']?>" class="hidden-print submitteddate_<?=$cols['approved']?>"><?=$cols['submitDate'];?></td>
							<td class="hidden-print"><?=$cols['stmp'];?></td>
						</tr>
						
						<tr id="notesrow_<?=$cols['scheduleID'];?>" class="hidden-print hidden notesrows">
							<td colspan="22">
								<!-- <textarea id="notes_<?=$cols['scheduleID'];?>"   
									class="form-control notes" style="overflow: hidden;resize: none;">
										<?=$cols['notes'];?>
								</textarea> -->
							</td>
						</tr>
					<?php
					$i++;	
					}
					?>
					<tr class="total">
						<td colspan="4" class=""><?=$this->l('total');?></td>
						<td  class="hidden-print">&nbsp;</td>
						<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"totalCost")),2);?></td>
						<td class="hidden-print">&nbsp;</td>
						<?php
							$range = range(1, 12);
								foreach($range as $month){
						?>
							<td style="text-align: right;"><?=number_format(array_sum(array_column($row,"month_".ltrim($month,"0")."_amount")),2);?></td>

						<?php		
							}
						?>
						<td>&nbsp;</td>
						<td  class="hidden-print" colspan="2">&nbsp;</td>

					</tr>
					
					<?php
						
					}
					
					}

					?> 
					
				</tbody>
			</table>
		</div>
	</div>


<?php
include "utility_close_standalone.php";
?>	

<script>
$(document).ready(function() {
    $('#approval_status').select2();
    
   	show_filtered_rows();
   	
   	$('tbody').sortable();
   	
    $('table tbody').on( 'click', '.itemrows', function () {
        $(this).toggleClass('selected');
    } );
 

   
});

function approve_budget_item(elem){
	var id = $(elem).attr('id');
	var obj = id.split('_');
	var scheduleID = obj[1];
	
	var data = {"scheduleID":scheduleID};
	
	var cnf_trackable = confirm("Is this item tackable?");
	
	if(cnf_trackable){
		data = {"scheduleID":scheduleID,"trackable":'1'};
	}
	
			$.ajax({
			url:'<?=$this->get_url(array("assetview"=>'ajax_approve_budget_item',"lib"=>"budget"));?>',
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(resp == "1"){
					
					$(".submitactionitem_"+scheduleID+", .editactionitem_"+scheduleID).remove();
					
					$(".notesrows").each(function(){
						if(!$(this).hasClass('hidden')){
							$(this).addClass("hidden");
						}
					});
					
					$("#action_"+scheduleID).toggleClass("btn-info btn-success");
					$("#action_"+scheduleID).html('<?=$this->l('action')?> - <?=$this->l('approved');?> <span class="caret"></span>');
					$("#status_"+scheduleID).html('<?=$this->l("approved");?>');
				}else{
					alert(resp);
				}
			},
			error:function(){
				alert("Error Occurred!");
			}
		});
}

function show_budget_notes(elem){
	<?php $budget_array = json_encode($budget_items);;?>
	
	var id = $(elem).attr('id');
	var obj = id.split("_");
	var scheduleID = obj[1];
	var income = obj[2];
   	var expense = obj[3];
   	var scheduleitem = obj[4];
	var budget = <?=$budget_array;?>;
	var notes = budget[income][expense][scheduleitem].notes;

	$(".notesrows").each(function(){

		if($(this).attr('id')!== "notesrow_"+scheduleID){
			$(this).addClass('hidden');
		}
	});
	
	$("#notesrow_"+obj[1]).children().html('<textarea style="overflow: scroll;" id="notes_'+scheduleID+'" class="notes">'+notes+'</textarea>');
	$("#notesrow_"+obj[1]).removeClass("hidden");
	
		CKEDITOR.replace("notes_"+obj[1]).on('key',function(e){	    			    			    	
		    	$.ajax({
					url:'<?=$this->get_url(array("assetview"=>'ajax_post_budget_notes',"lib"=>"budget"));?>',
					data:{"scheduleID":scheduleID,"notes":e.editor.getData()},
					type:"POST",
					error:function(){
						alert("Error Occurred!");
					}
				});		        
		    });
	
}

$("#btn_filter").click(function(){
	show_filtered_rows();
});


function show_filtered_rows(){
	var state_string = $('#approval_status').val();
	var size = $('#approval_status option').length; 
	
	$(".itemrows").hide();
	
	if(state_string.length < size){
		$(".total, .notes").hide();
	}else{
		$(".total, .notes").show();
	}
	
	$.each(state_string,function(idx,val){
		$(".row_"+val).show()
	});
}

function submit_budget_item(scheduleID){
		$.ajax({
			url:'<?=$this->get_url(array("assetview"=>'ajax_submit_budget_item',"lib"=>"budget"));?>',
			data:{"scheduleID":scheduleID},
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(resp == "1"){
					
					$(".submitactionitem_"+scheduleID+", .editactionitem_"+scheduleID).remove();
					
					$(".notesrows").each(function(){
						if(!$(this).hasClass('hidden')){
							$(this).addClass("hidden");
						}
					});
					
					$("#action_"+scheduleID).toggleClass("btn-warning btn-info");
					$("#action_"+scheduleID).html('<?=$this->l('action')?> - <?=$this->l('submitted');?> <span class="caret"></span>');
					$("#status_"+scheduleID).html('<?=$this->l("submitted");?>');
					$("#submitteddate_"+scheduleID).html('<?=date("Y-m-d h:i:s");?>');
				}else{
					alert(resp);
				}
			},
			error:function(){
				alert("Error Occurred!");
			}
		});
	}

function delete_budget_item(elem,scheduleID){
	
	var cnfm = confirm("Are you sure you want to delete this item?");
	
	if(!cnfm){
		alert("Process Aborted!");
		return false;
	}	
		$.ajax({
			url:'<?=$this->get_url(array("assetview"=>'ajax_delete_budget_item',"lib"=>"budget"));?>',
			data:{"scheduleID":scheduleID},
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(resp == "1"){
					elem.closest('tr').remove();
					alert("Item deleted successful");
					
				}else{
					alert(resp);
				}
			},
			error:function(){
				alert("Error Occurred!");
			}
		});
	}	

/**Adopted as it is from https://sumtips.com/snippets/javascript/tab-in-textarea/*
 *	By default when you press the tab key in a textarea, it moves to the next focusable element. 
 * If you’d like to alter this behavior instead to insert a tab character, it can be done using the codes shown in this post
 *
 * --Not used after installing CKEditor ---
 * */
$("textarea").keydown(function(e) {
    if(e.keyCode === 9) { // tab was pressed
        // get caret position/selection
        var start = this.selectionStart;
            end = this.selectionEnd;

        var $this = $(this);

        // set textarea value to: text before caret + tab + text after caret
        $this.val($this.val().substring(0, start)
                    + "\t"
                    + $this.val().substring(end));

        // put caret at right position again
        this.selectionStart = this.selectionEnd = start + 1;

        // prevent the focus lose
        return false;
    }
});

</script>