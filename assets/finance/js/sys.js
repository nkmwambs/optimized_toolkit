$(document).ready(function(){
        
});

$(".datepicker").datepicker();

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



