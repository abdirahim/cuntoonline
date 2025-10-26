{{ HTML::style('css/admin/button.css') 
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a data-dismiss="modal" class="close" href="javascript:void(0)">
			<span style="float:right" class="no-ajax" aria-hidden="true">x</span>
			<span class="sr-only no-ajax"></span></a>	
			<h4 class="modal-title" id="myModalLabel">
				Email Logs Detail
			</h4>
		</div>
		
		<div class="modal-body">
			<div class="mws-panel-body no-padding dataTables_wrapper">
				<table class="mws-table mws-datatable" style="border:1px solid #cccccc;" >
					<tbody>
					<?php 
					if(!empty($result)){  
						foreach($result as $value){ ?>
						<tr>
							<th>Email To</th>
							<td data-th='Email To'> <?php echo $value->email_to;  ?></td>
						</tr>
						<tr>
							<th>Email From</th>
							<td data-th='Email From'><?php  echo $value->email_from; ?></td>
						</tr>
						<tr>
							<th>Subject</th>
							<td data-th='Subject'><?php echo  $value->subject; ?></td>
						</tr>
						<tr>
							<th valign='top'>Message</th>
							<td data-th='Message'><?php  echo  $value->message; ?></td>
						</tr>
					<?php }	} ?>
					</tbody>		
				</table>
			</div>
			<div class="clearfix">&nbsp;</div>
		</div>
	</div>
</div>
