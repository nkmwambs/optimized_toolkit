$(function($){
	
	$(".datepicker").datepicker();
	
	$(".datatable").DataTable(
		{
			dom: 'lBfrtip',
			buttons: [
            	'copy', 'csv', 'excel', 'pdf', 'print'
        	],
			"ordering": false,
		    "stateSave": true,
		    "scrollX": true
		 }
	);
	
});

	function sum_column_of_textboxes(textboxes_class){
		var total = 0;
		$('.'+textboxes_class).each(function(){
			total += parseFloat($(this).val());
		});
		
		return total;
	}
	
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
		    header: 'FCP Payment Voucher',             
		    formValues: true          
		});
    }
	
	function go_back(){
		window.history.back();
	}

	function clone_last_body_row(table_id,row_class){
		var $tr    = $("#"+table_id+" tbody tr:last").closest('.'+row_class);
		var $clone = $tr.clone();
		$clone.find('.'+row_class).children().val('');
		$tr.after($clone);
	}
	
	function remove_all_rows(tbl_id,td_hosting_checkbox_postion){
		if (td_hosting_checkbox_postion === undefined) {
	        td_hosting_checkbox_postion = 0;
	    }
		 $("#"+tbl_id+" tbody").find("tr:gt(0)").remove();
		 
		 var elem = $("select,input");
		 
		 //Clear values elements that are not readonly or disabled
		 $.each(elem,function(){
		 	if($(this).is('[readonly]') == false && $(this).is('[disabled]')== false)
		    {
		      $(this).val(null);
		    }
		 });
		 
		 
		 
		 //Uncheck the check box of the first row
		 $("#"+tbl_id+" tbody").find("tr:eq(0) td:eq("+td_hosting_checkbox_postion+")").children().prop("checked",false);		 
	}
	
	function show_hide_delete_button_on_check(checkbox_class,delete_button_id){
		var checked = $("."+checkbox_class+":checked").length;
		if(checked>0){
			$("#"+delete_button_id).removeClass("hidden");	
		}else{
			$("#"+delete_button_id).addClass("hidden");
		}
	}
	
	function remove_selected_rows(tbl_id,action_btn_id,checkbox_class){
		var elem = $("#"+tbl_id+" tbody");
		
		$("."+checkbox_class).each(function(){
			if($(this).is(":checked")){
				if(elem.children().length > 1){
					$(this).closest("tr").remove();//Replaced .parent().parent() to .closest()
				}else{
					alert("You need atleast one row in the table");
					
					//Uncheck the check box of the first row
					$("#"+tbl_id+" tbody").find("tr:eq(0) td:eq("+td_hosting_checkbox_postion+")").children().prop("checked",false);
				}
			}
			
			var checked = $(".check:checked").length;
			if(checked>0){
				$("#"+action_btn_id).removeClass("hidden");	
			}else{
				$("#"+action_btn_id).addClass("hidden");
			}
		});		
	}
	
	


/**Adopted as it is from https://sumtips.com/snippets/javascript/tab-in-textarea/*
 *	By default when you press the tab key in a textarea, it moves to the next focusable element. 
 * If you’d like to alter this behavior instead to insert a tab character, it can be done using the codes shown in this post
 **/
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



