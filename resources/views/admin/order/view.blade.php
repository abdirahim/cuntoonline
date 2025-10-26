@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/developer.css') }}

<!-- View order detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
		<i class="icon-table"></i> {{ trans("messages.order.view_order") }} 
		</span>
		<a href="{{URL::to('admin/order-manager')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_managmt.back_to_listing") }}</a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
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
					<th width="20%">Payment Phone Number</th>
					<td data-th='Phone Number'> {{ ($orderDetail->payment_phone_number!='')? $orderDetail->payment_phone_number:'NA' }}</td>
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
					<td data-th='Specific Instruction'> {{ ($orderDetail->specific_instruction!='')? $orderDetail->specific_instruction:'NA' }}</td>
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

<!--View order detail div end here -->
@if(!empty($restaurantDetail))
	<!-- View restaurant detail div  -->
	<div  class="mws-panel grid_8 mws-collapsible">
		<div class="mws-panel-header" style="height:8%">
			<span>
				<i class="icon-table"></i> {{ trans("Restaurant Detail") }} 
			</span>
			<a  target="_blank" href="{{URL::to('admin/restaurant-manager/view-restaurant/'.$restaurantDetail->id)}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("view restautant") }}</a>
		</div>
		<div class="mws-panel-body no-padding dataTables_wrapper">
			<table class="mws-table mws-datatable">
				<tbody>
					<tr>
						<th width="20%">Restaurant Name</th>
						<td data-th='Restaurant Name'>{{ $restaurantDetail->name }}</td>
					</tr>
					<tr>
						<th width="20%">Description</th>
						<td data-th='Description'>{{ $restaurantDetail->description }}</td>
					</tr>
					<tr>
						<th width="20%">Cuisine</th>
						<td data-th='Cuisine'>
						<?php 
							$cuisineArray	=	explode(',',$restaurantDetail->cuisine);
							$cuisine	=	CustomHelper::getCuisineName($cuisineArray); 
							echo implode(', ',$cuisine);
						 ?> 
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!--View restaurant detail div end here -->
@endif
<!-- View item detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
			<i class="icon-table"></i> {{ trans("Item Detail") }} 
		</span>
	</div>

		@if(!empty($foodCategories))
			@foreach($foodCategories as $key=> $foodCategorie)
				@if(in_array($foodCategorie,$foodCategory))
					<h3>{{ $foodCategorie }}</h3>
				@endif
				@if(!empty($orderDetail->orderItem))
					@foreach($orderDetail->orderItem as $key => $item)
						@if($item->meal_category == $foodCategorie)
							<?php $set	=	true;  ?>
							<div class="restaurant-menu-item grid_8">
								<div class="grid_4">
									<div class="menu-des">
										<div class="equal-height">
											<h4>{{ $item->meal_name }}</h4>
										</div>
									</div>
								</div>
								<div class="grid_2">
									<div class="menu-price">
										<div class="equal-height text-center ">
											<strong> {{ $item->quantity }}</strong>
										</div>
									</div>
								</div>
								<div class="grid_2">
									<div class="menu-price">
										<div class="equal-height text-center ">
											<strong><?php $price	=	CustomHelper::numberFormat( $item->price); ?>
												{{ Config::get('Site.currency') }}{{ $price }}</strong>
										</div>
									</div>
								</div>
								@if($item->specific_instruction)
									<div class="grid_8">
										<h5>Specific Instruction</h5>
										<p>{{ ($item->specific_instruction!='')? $item->specific_instruction:'NA' }}</p>
									</div>
								@endif
							</div>
						@endif
					@endforeach
				@endif
			@endforeach
		@endif
		
		@if(!isset($set))
		<div class="restaurant-menu-item grid_8 text-center">
			<h4>{{ 'Record does not exists.' }}</h4>
		</div>
		@endif
</div>
<!--View item detail div end here -->

@stop
