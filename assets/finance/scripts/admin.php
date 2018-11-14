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

	function change_item_status(elem,tag_id,tag_status){
		var url = '<?=$this->get_url(array('assetview'=>'ajax_update_expense_tracking_tag_status','lib'=>'admin'));?>';
		var status = tag_status==1?0:1;

		/**Alternative to the Ajaxsetup in the assets/finance/scripts/setup.php
		 * var csrf_test_name = $("input[name=csrf_test_name]").val();
		 * var data = {'csrf_test_name' : csrf_test_name, 'tag_id':id,'tag_status':status};
		 */
		
		var data = {'tag_id':tag_id,'tag_status':status};//Use AjaxSetup to add csrf hash to prevent Forbidden requests
		
		//Set $config['csrf_regenerate'] = false; in the Codeigniter Config file
		$.ajax({
			url:url,
			data:data,
			type:"POST",
			beforeSend:function(){
				$("#overlay").css("display","block");
			},
			success:function(resp){
				$("#overlay").css("display","none");
				if($(elem).hasClass('active')){
					$(elem).closest("tr").find("td:last").html("<?=$this->l('suspended');?>");
					$(elem).toggleClass('active suspended');
					$(elem).html("<i class='fa fa-eye'></i> <?=$this->l('activate');?>");
				}else{
					$(elem).closest("tr").find("td:last").html("<?=$this->l('active');?>");
					$(elem).toggleClass('suspended active');
					$(elem).html("<i class='fa fa-eye-slash'></i> <?=$this->l('suspend');?>");
				}

				alert(resp);
			},
			error:function(xhr,err,msg){
				alert(msg);
				$("#overlay").css("display","none");
			}
		});
		
	}
	
//JS for edit_expense_tracking_tag.php

$("#edit_record").on('click',function(){
	//alert('frm_edit_expense_tracking');
	var url = '<?=$this->get_url(array('assetview'=>'ajax_edit_expense_tracking_tag','lib'=>'admin'))?>';
	var data = $("#frm_edit_expense_tracking").serializeArray();
	
	$.ajax({
		url:url,
		data:data,
		type:"POST",
		beforeSend:function(){
			$("#overlay").css('display','block');
		},
		success:function(resp){
			$("#overlay").css('display','none');
			alert(resp);
			location.replace('<?=$this->get_url(array('assetview'=>'show_expense_tracking_tags','lib'=>'admin'));?>');
		},
		error:function(xhr,err){
			alert(err);
		}
	});
})	
</script>