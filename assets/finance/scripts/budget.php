<script>
	//JS for create_budget_item.php
	
	$(document).ready(function(){
	CKEDITOR.replace('notes');
});
	$(".calc_cost").change(function(){
		var cost = 1;
		$(".calc_cost").each(function(idx){
			cost*=parseFloat($(this).val());
		});	
		
		$("#totalCost").val(cost);
		
		$(".amount_spread").each(function(){
			$(this).val((cost/12).toFixed(2));
		});
	});
	
	$("#clr_spread").click(function(){
		$(".amount_spread").each(function(){
			$(this).val(0);
		});
	});
	
	$("#create_budget_item_form").submit(function(ev){
		var check_spread = false;
		var editor_data = CKEDITOR.instances.notes.getData();
		//alert(editor_data);
		$("#hidden_notes").val(editor_data);
		
		//Check if spread is correct
		var sum_spread = 0;
		$(".amount_spread").each(function(){
			sum_spread += parseFloat($(this).val());
		});
		
		check_spread = parseFloat($("#totalCost").val()) == parseFloat(Math.round(sum_spread));
		
		if(check_spread){
			
			$.ajax({
				url:'<?=$this->get_url(array("assetview"=>'ajax_budget_item_posting',"lib"=>'budget'));?>',
				beforeSend:function(){
					$("#overlay").css("display","block");
				},
				type:"POST",
				data:$(this).serializeArray(),
				success:function(resp){
					$("#overlay").css("display","none");
					$(".toclear").each(function(){
						$(this).val(0);
						if($(this).hasClass("textField")){
							$(this).val("");	
						}
					});
					CKEDITOR.instances.notes.setData("");
					alert(resp);
				},
				error:function(){
					
				}
			});
		}else{
			alert("Spread is incorrect");
		}
		
		ev.preventDefault();
	});
	
	
	$("#AccNo").change(function(){
		
		var expense_tracking_tags = <?=json_encode($expense_tracking_tags);?>;
		
		var selected_account = $(this).val();
		
		var accountHasTags = selected_account in expense_tracking_tags;
		
		var expense_tracking_tag_id = $("#expense_tracking_tag_id");
		
		//Remove all existing options except the first one
		expense_tracking_tag_id.find("option:gt(0)").remove();
		
		$("#tracking_tags_count").html(0);
		
		if(accountHasTags){
			$("#tracking_tags_count").html(expense_tracking_tags[selected_account].length);
			$.each(expense_tracking_tags[selected_account],function(indx,val){
				expense_tracking_tag_id.append("<option value='"+val.tag_id+"'>"+val.tag_desc+"<option>");
			});
		}	
		
		
		var hasChoice = $('option:selected').data('choices');
		
		if(hasChoice){
			//var option = "<option>Select a Description</option>";
			$.ajax({
				url:'<?=$this->get_url(array('assetview'=>'ajax_get_choices_for_account','lib'=>'budget'));?>',
				type:"POST",
				data:{'AccNo':$(this).val()},
				beforeSend:function(){
					$("#overlay").css("display","block");
				},
				success:function(option){
					$("#overlay").css("display","none");
					$("#description_holder").html("<select class='form-control' id='details' name='details' required='required'>"+option+"</select>");
				},
				error:function(){
					
				}
			});
			
		}else{
			$("#description_holder").html('<input type="text" class="form-control toclear textField" id="details" name="details" required="required" />');
		}
	});
	
</script>