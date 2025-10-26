<style>

.mws-table {
    border: 0 none;
    border-collapse: collapse;
    margin: 0;
    width: 100%;
}
.mws-table thead tr {
    background: #e8e8e8 -moz-linear-gradient(center top , #f7f7f7, #e8e8e8) repeat-x scroll left top;
}
.mws-table thead tr th:first-child {
    border-left: medium none;
}
.mws-table thead tr th {
    border-bottom: 1px solid #cccccc;
    border-left: 1px solid #cccccc;
    padding: 10px 16px;
}
.mws-table .checkbox-column {
    text-align: center;
    width: 32px;
}
.mws-table tbody td, .mws-table tfoot td {
    border-left-color: #bebebe !important;
    border-left-style: dotted;
    border-left-width: 1px;
    padding: 8px 16px;
}
.mws-table tbody td:first-child, .mws-table tfoot td:first-child {
    border-left: medium none;
}
.mws-table tbody tr:nth-child(2n+1) {
    background-color: #f2f2f2;
}
.mws-table tbody tr:nth-child(2n) {
    background-color: #fafafa;
}
.mws-table tbody tr.odd {
    background-color: #f2f2f2;
}
.mws-table tbody tr.even {
    background-color: #fafafa;
}
.mws-table tbody tr.odd td.sorting_1 {
    background-color: #cccccc;
}
.mws-table tbody tr.even td.sorting_1 {
    background-color: #e1e1e1;
}
.mws-table tbody tr {
    border-bottom: 1px solid #cccccc;
}
.mws-table tbody tr:last-child {
    border-bottom: 0 none;
}
</style>
</br>
<!-- View item detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-body no-padding dataTables_wrapper">
	</br>	
	<p> <p>
	 <b>Hi {{ $userName}}</b>,</br>
		<p>
			@if($status==1)
				{{ trans("messages.order_accepted_message")}}
			@elseif($status==1)
				{{ trans("messages.order_rejected_message")}}
			@else
				{{ trans("messages.order_completed_message")}}
			@endif
		</p>

		<div class="mws-panel-header" style="height:8%">
			<h3> Item Detail</h3>
		</div>
	
		<table class="mws-table mws-datatable" border="1" style="border:solid 1px;">
			<tbody>
				<tr>
					<th>Food category</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th>Price</th>	
					<th>Specific instruction</th>
				</tr>
				@if(!empty($foodCategories))
					@foreach($foodCategories as $key=> $foodCategorie)
						@if(!empty($orderDetail->orderItem))
							@foreach($orderDetail->orderItem as $key => $item)
								@if($item->meal_category == $foodCategorie)
									<tr>
										<td data-th='Name'>{{ $foodCategorie }}</td>
										<td data-th='Meal Name'>{{ $item->meal_name }}</td>
										<td data-th='Quantity'> {{ $item->quantity }}</td>
										<td data-th='Currency'>{{ Config::get('Site.currency') }}{{  $item->price }}</td>
										<td data-th='Specific Instruction'>{{ $item->specific_instruction }}</td>
									</tr>
								@endif
							@endforeach
						@endif
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
<!--View item detail div end here -->

</br>
<h3>User Detail</h3>
<!-- View order detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable" border="1" style="border:solid 1px;">
			<tbody>
				<tr>
					<th width="20%">User Name</th>
					<td data-th='Name'>{{ $orderDetail->full_name }}</td>
				</tr>
				<tr>
					<th width="20%">Address</th>
					<td data-th='Address'>
						<?php
								$address	 =	'';
								$address	.=	($orderDetail->address != '') ? $orderDetail->address.', ': '';
								$address	.=	($orderDetail->pin_code != '') ? $orderDetail->pin_code.', ': '';
								$address	 =	rtrim($address,', ');
								echo $address;
								
						 ?>
						@if($address=='')
							{{ 'NA'}}
						 @endif
					</td>
				</tr>
				<tr>
					<th width="20%">Phone Number</th>
					<td data-th='Phone Number'> {{ ($orderDetail->phone_number!='')? $orderDetail->phone_number:'NA' }}</td>
				</tr>
				<tr>
					<th width="20%">Payment Phone Number</th>
					<td data-th='Phone Number'> {{ ($orderDetail->payment_phone_number!='')? $orderDetail->payment_phone_number:'NA' }} </td>
				</tr>
				<tr>
					<th width="20%">Payment Type</th>
					<td data-th='Phone Number'>							
						@if($orderDetail->payment_type==ZAAD_PAYMENT)
							{{ 'Zaad'}}
						@endif
						
						@if($orderDetail->payment_type==EDAHAB_PAYMENT)
							{{ 'E-Dahab'}}
						@endif
					</td>
				</tr>
				<tr>
					<th width="20%">Total Amount</th>
					<td data-th='Total Amount'>
						<?php $total_price	=	CustomHelper::numberFormat( $orderDetail->total_amount)?>
						{{ Config::get('Site.currency')}}{{ $total_price }}
					</td>
				</tr>
				<tr>
					<th width="20%">Specific Instruction</th>
					<td data-th='Specific Instruction'>{{ ($orderDetail->specific_instruction!='')? $orderDetail->specific_instruction:'NA' }}</td>
				</tr>
				<tr>
					<th width="20%">Deliver date</th>
					<td data-th='deliver Date'>
						@if($orderDetail->deliver_date!='')
						<?php 
							$time	=	str_replace('/','-',$orderDetail->deliver_date.$orderDetail->deliver_time);
						?>
						{{ date(Config::get("Reading.date_format") , strtotime($time)) }}
						@else 
							{{ 'NA'}}
						@endif
					</td>
				</tr>
				<tr>
					<th width="20%">Order placed On</th>
					<td data-th='Order placed On'>{{ date(Config::get("Reading.date_format") , strtotime($orderDetail->created_at)) }}</td>
				</tr>
				<tr>
					<th width="20%">Shipping Type</th>
					<td data-th='Shipping Type'>
					{{ ($orderDetail->shipping_type == COLLECTION) ? 'Collection' : 'Delivery' }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</br></br>
