@extends('admin.layouts.default')
@section('content')
<!-- Searching div -->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['role' => 'form','url' => 'admin/order-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			{{  Form::label('from', 'Filter by delivery date', ['class' => 'mws-form-label']) }}
			<div id="mws-theme-presets-container" class="mws-themer-section">
				{{  Form::label('from', 'From', ['class' => 'mws-form-label']) }}
				{{ Form::text(
				'from',
				((isset($searchVariable['from'])) ? $searchVariable['from'] : ''),
				['class' => 'small','id'=>'order_start','readonly'=>'true'])
				}}
				<div class="error-message help-inline">
					{{ $errors->first('from') }}
				</div>
				{{  Form::label('from', 'To', ['class' => 'mws-form-label']) }}
				{{ Form::text(
				'to',
				((isset($searchVariable['to'])) ? $searchVariable['to'] : ''),
				['class' => 'small','id'=>'order_end','readonly'=>'true'])
				}}
				<div class="error-message help-inline">
					{{ $errors->first('to') }}
				</div>
			</div>
		</div>
		<div class="mws-themer-section">
				<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Filter by order id") }}</label><br/>
				{{ Form::text('id',((isset($searchVariable['id'])) ? $searchVariable['id'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Filter by order status") }}</label><br/>
				{{ Form::select('order_status',array(''=>'Select order status')+Config::get('order_status'),((isset($searchVariable['order_status'])) ? $searchVariable['order_status'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Filter by Order Type") }}</label><br/>
{{--				{{ Form::select('shipping_type',array(''=>'Select order type',COLLECTION=>'Collection',DELIVERY =>'Delivery'),((isset($searchVariable['shipping_type'])) ? $searchVariable['shipping_type'] : ''),['class' => 'small']) }}--}}
				{{ Form::select('shipping_type',array(''=>'Select order type',config("constants.COLLECTION"),config("constants.DELIVERY")),((isset($searchVariable['shipping_type'])) ? $searchVariable['shipping_type'] : ''),['class' => 'small']) }}

			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Filter by full name") }}</label><br/>
				{{ Form::text('full_name',((isset($searchVariable['full_name'])) ? $searchVariable['full_name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Payment Phone Number") }}</label><br/>
				{{ Form::text('payment_phone_number',((isset($searchVariable['payment_phone_number'])) ? $searchVariable['payment_phone_number'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Payment Type") }}</label><br/>
				{{ Form::select('payment_type',array(''=>'Select payment type',ZAAD_PAYMENT=>'Zaad',EDAHAB_PAYMENT =>'E-Dahab'),((isset($searchVariable['payment_type'])) ? $searchVariable['payment_type'] : ''),['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.user_managmt.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/order-manager')}}"  class="btn btn-default btn-small">{{ trans("messages.user_managmt.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!-- End here -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.order.order_text") }}
		</span>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable {{ (!$result->isEmpty()) ? '' : 'details' }}">
			<thead>
				<tr>
					<th>Res. id</th>
					<th>
						{{
							link_to_action(
								'OrderController@listOrder',
								'Order Id',
								array(
								'sortBy' => 'id',
								'order' => ($sortBy == 'id' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'id' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'id' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="12%">
						{{
							link_to_action(
								'OrderController@listOrder',
								'Full Name',
								array(
								'sortBy' => 'full_name',
								'order' => ($sortBy == 'full_name' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'full_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'full_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="10%">{{ 'Payment Phone Number'}}</th>
					<th width="10%">
						{{
							link_to_action(
								'OrderController@listOrder',
								'Order Date',
								array(
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="10%">
						{{
							link_to_action(
								'OrderController@listOrder',
								'Payment Type',
								array(
								'sortBy' => 'deliver_date',
								'order' => ($sortBy == 'payment_type' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'payment_type' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'payment_type' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{'Type'}}</th>
					<th width="10%">{{ 'Status' }}</th>
					<th width="5%">{{ 'Payment Status' }}</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $key => $record)
					<tr>
						<td data-th='Res.id'>{{ $record->restaurantDetail['id'] }}</td>
						<td data-th='Order Id'>{{ $record->id }}</td>
						<td data-th='Name'>{{ $record->full_name }}</td>
						<td data-th='Phone Number'>
							{{ $record->payment_phone_number }}
						</td>
						<td data-th='Order Date' >
							{{ date(Config::get("Reading.date_format") , strtotime($record->created_at)) }}
						</td>
						<td data-th='Payment Type' >
							@if($record->payment_type==ZAAD_PAYMENT)
								{{ 'Zaad'}}
							@endif
							
							@if($record->payment_type==EDAHAB_PAYMENT)
								{{ 'E-Dahab'}}
							@endif
						</td>
						<td data-th='Shipping Type'>{{ ($record->shipping_type == 0) ? 'Collection' : 'Delivery' }}</td>
						<td data-th='Status'>
							{{ ($record->order_status == PROCESSIONG) ? '<span class="label label-info">Processing</span>' : ''}}
							{{ ($record->order_status == ACCEPTED) ? '<span class="label label-warning">Accepted</span>' : ''}}
							{{ ($record->order_status == REJECTED) ? '<span class="label label-danger">Rejected</span>' : ''}}
							{{ ($record->order_status == COMPLETED) ? '<span class="label label-success">Completed</span>' : ''}}
							@if($record->is_recommended)
							<span class="label label-warning">{{ trans("messages.restaurant.recommended") }}</span>
							@endif
						</td>
						<td data-th='Payment Status' >
							@if($record->is_paid==PAID)
								<span class="label label-success">Paid</span>
							@else
								<span class="label label-warning">Unpaid</span>
							@endif
						</td>
						
						<td data-th='Action' class="text-center">
							<a href="{{URL::to('admin/order-manager/view-order/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("View Detail")  }} </a>
							
							@if($record->order_status != COMPLETED)
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle btn-small" data-toggle="dropdown" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">{{ trans("messages.user_managmt.action") }}</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									
									<li>
										<a href="{{URL::to('admin/order-manager/update-order-status/'.$record->id.'/'.ACCEPTED)}}" >{{ trans("Mark as accepted")  }} </a>
									</li>
									<li>
										<a href="{{URL::to('admin/order-manager/update-order-status/'.$record->id.'/'.REJECTED)}}" >{{ trans("Mark as rejected") }} </a>
									</li>
									
									<li>
										<a href="{{URL::to('admin/order-manager/update-order-status/'.$record->id.'/'.COMPLETED)}}" >{{ trans("Mark as completed")  }} </a>
									</li>
								</ul>
							</div>
							@endif
							<a href="{{URL::to('admin/order-manager/paid-status/'.$record->id)}}" class="btn btn-success btn-small">{{ trans("Mark as Paid")  }} </a>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td align="center" width="100%" colspan="7"> {{ trans("messages.no_records_found") }}</td>
					</tr>
					@endif
			</tbody>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
<style>
	#mws-themer{
	bottom:-35px!important;
	}
</style>
<!-- Datepicker js and css use  for datepicker-->
{{ HTML::script('js/admin/bootstrap-datepicker.js') }}
{{ HTML::style('css/admin/datepicker _ui.css') }}
<script>
	/* for datepicker used in searching */
	$( "#order_start" ).datepicker({
			changeMonth: true,
			changeYear : true,
			numberOfMonths	: 1,
			
		});
	   $( "#order_end" ).datepicker({
			changeMonth: true,
			changeYear : true,
			numberOfMonths	: 1,	
		});
</script>
@stop

