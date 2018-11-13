<div class="row">
	<div class="<?=$this->get_column_size();?>">	
		<div class="panel panel-primary" data-collapsed="0">
			 <div class="panel-heading">
			     <div class="panel-title" >
			        <i class="entypo-plus-circled"></i>
						<?=$this->l('expense_tracking_tags');?>
			     </div>
			 </div>
			        
			<div class="panel-body" overflow: auto;">	
					<ul class="nav nav-pills">
						<li>
							<button id="add_row" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								Add Row
							</button>	
						</li>
						
						<li>
							<button id="reset" class="btn btn-default btn-icon icon-left hidden-print pull-left">
								Resets
							</button>	
						</li>
						
					</ul>	
					
					<hr />
					
					<table class="table table-striped datatable">
						<thead>
							<tr>
								<th>Account Number</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"><button class="btn btn-default btn-icon icon-left hidden-print pull-left">Create</button></td>
							</tr>
						</tfoot>
					</table>	
			</div>
		</div>
	</div>
</div>	

<script>
	//$(".table").DataTable();
</script>		