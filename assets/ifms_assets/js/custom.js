$(document).ready(function(){
		var datatable = $("table").DataTable(
			{
				dom: 'Bfrtip',
				buttons: [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					'pdfHtml5'
				],
				"ordering": false,
		        stateSave: true,
		        "scrollX": true,
		     }
	);
	
	$("#select_btn").click(function(){
		
		if($(".check_voucher:checked").length === 0){
			$(".check_voucher").prop("checked",true);
		}else{
			$(".check_voucher").prop("checked",false);
		}
		
		
	});	
    
        
});

