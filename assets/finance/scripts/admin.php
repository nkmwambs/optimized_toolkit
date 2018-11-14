<script>
	
//JS for add_expense_tracking_tags.php

	$("#add_row").on("click",function(){
		clone_last_body_row('tbl_expensetrackingtags','tr_clone');
	});
	
	
	$(document).on("click",".check",function(){
		
		var checked = $(".check:checked").length;
		if(checked>0){
			$("#btnDelRow").removeClass("hidden");	
		}else{
			$("#btnDelRow").addClass("hidden");
		}
	});
	
	
	$("#btnDelRow").click(function(){
		remove_selected_rows("tbl_expensetrackingtags",'btnDelRow','check');
	});
	
	
	$("#resetBtn").on('click',function(){
		remove_all_rows("tbl_expensetrackingtags");
	});
	
	
	$("#btnCreate").on('click',function(ev){
		
		if($("input select").val("")) return false;
		
		var url = '<?=$this->get_url(array('assetview'=>'ajax_post_expense_tracking_tag','lib'=>'admin'));?>';
		var data = $("#frm_create_expense_tracking").serializeArray();
		
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display",'block');
			},
			success:function(resp){
				alert(resp);
				$("#overlay").css("display",'none');
				remove_all_rows("tbl_expensetrackingtags");
			},
			error:function(){
				alert('Error Occurred');
			}
		});
		
		ev.preventDefault();
	});
	
	
//JS for show_expense_tracking_tag.php

	function change_item_status(elem,id,status){
		var url = '<?=$this->get_url(array('assetview'=>'ajax_update_expense_tracking_tag_status','lib'=>'admin'));?>';
		var data = {'tag_id':id,'tag_status':status};
		var status_tag = $(elem).closest("tr").find("td:last").html().trim();
		//alert(status_tag);
		
		$.ajax({
			url:url,
			data:data,
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if(status == '1' || status_tag == 'Active'){
					$(elem).closest("tr").find("td:last").html("<?=$this->l('suspended');?>");
					$(elem).html("<i class='fa fa-eye'></i> <?=$this->l('activate');?>");
					
				}else if(status == '0' || status_tag == 'Suspended'){
					$(elem).closest("tr").find("td:last").html("<?=$this->l('active');?>");
					$(elem).html("<i class='fa fa-eye-slash'></i> <?=$this->l('suspend');?>");
				}
			},
			error:function(xhr,err){
				alert(err);
			}
		});
		
	}
</script>