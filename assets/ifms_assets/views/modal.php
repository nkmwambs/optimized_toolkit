 <style>
 	@media print {
    /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body */
    body.modal-open {
        visibility: hidden;
    }

    body.modal-open .modal .modal-header,
    body.modal-open .modal .modal-body {
        visibility: visible; /* make visible modal body and header */
    }
}

/*.modal-dialog {
  min-width: 80%;
  max-height: 100%;
  margin: 0;
  padding: 0;
}*/

.modal-content {
	height: 60%;
    max-height: 60%;
    height: auto;
    border-radius: 0;
   
}

.modal-body {
	 overflow-y:auto;
	 height: 540px;
}
 </style>
 
 <script type="text/javascript">
	
	function showAjaxModal(url){
		//alert(url);		
		jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
		
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax .modal-body').html(response);
			}
		});
			
		
			
	}
		
	</script>
	
	
	
    <div class="modal fade" id="modal_ajax">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Voucher</h4>
                </div>
                
                <div class="modal-body" style="height:500px; overflow:auto;">
                	   
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>