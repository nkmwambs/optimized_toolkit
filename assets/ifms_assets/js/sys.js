$(document).ready(function(){
        
});

function go_back(){
	window.history.back();
}


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

