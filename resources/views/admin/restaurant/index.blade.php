@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/developer.css') }}

<!-- chosen select box css and js start here-->
{{ HTML::style('css/admin/chosen.css') }}
{{ HTML::script('js/admin/chosen.jquery.js') }}
<!-- chosen select box css and js end here-->

<script type="text/javascript">
	$(function(){
		
		//for cuisine dropdown 
			$(".chzn-select").chosen();
			
		/* For delete user detail */
		$('[data-delete]').click(function(e){
			
		     e.preventDefault();
			// If the user confirm the delete
			if (confirm('Do you really want to delete the restaurant ?')) {
				// Get the route URL
				var url = $(this).prop('href');
				// Get the token
				var token = $(this).data('delete');
				// Create a form element
				var $form = $('<form/>', {action: url, method: 'post'});
				// Add the DELETE hidden input method
				var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
				// Add the token hidden input
				var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
				// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
				$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
			} 
		});
	});
</script>

<!-- Searching div -->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Select Cuisine") }}</label><br/>
				{{ Form::select('cuisine[]',$cuisineList,((isset($searchVariable['cuisine'])) ? $searchVariable['cuisine'] : ''),['class' => 'small chzn-select','multiple'=>'multiple','data-placeholder'=>'Please select cuisine']) }}
			</div>
		</div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Restaurant Name") }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.user_managmt.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/restaurant-manager')}}"  class="btn btn-default btn-small">{{ trans("messages.user_managmt.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!-- End here -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.restaurant.restaurant_text") }}
		</span>
		<a href="{{URL::to('admin/dropdown-manager/food-category')}}" class="btn btn-success btn-small align">Food Category </a>
		<a href="{{URL::to('admin/restaurant-manager/add-restaurant')}}" class="btn btn-success btn-small align">{{ trans("messages.restaurant.add_restaurant") }} </a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable {{ (!$result->isEmpty()) ? '' : 'details' }} ">
			<thead>
				<tr>
					<th width="5%">{{ 'Rest. ID'}}</th>
					<th width="25%">
						ffsfsdfs
{{--						{{--}}
{{--							link_to_action(--}}
{{--								'RestaurantController@listRestaurant',--}}
{{--								'name',--}}
{{--								array(--}}
{{--								'sortBy' => 'name',--}}
{{--								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'--}}
{{--								),--}}
{{--								array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )--}}
{{--							)--}}
{{--						}}--}}
					</th>
					<th width="25%">{{ 'Cuisine'}}</th>
					<th width="15%">{{ 'Status' }}</th>
					<th width="30%">{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $key => $record)
						<tr>
							<td data-th='ID'>{{ $record->id }}</td>
							<td data-th='Name'>{{ $record->name }}</td>
							<td data-th='Cuisine'>
								<?php 
									$cuisineArray	=	explode(',',$record->cuisine);
									$cuisine		=	App\Libraries\CustomHelper::getCuisineName($cuisineArray);


									echo implode(', ',$cuisine);
									?> 
							</td>
							<td data-th='Status'>
								@if($record->is_active)
								<span class="label label-success">Activated</span>
								@else
								<span class="label label-danger">Deactivated</span>
								@endif
								@if($record->is_recommended)
								<span class="label label-warning">{{ trans("messages.restaurant.recommended") }}</span>
								@endif
							</td>
							<td data-th='Action'>
								<a href="{{URL::to('admin/restaurant-manager/add-meal/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("Meals")  }} </a>
								<div class="btn-group">
									<button type="button" class="btn btn-primary dropdown-toggle btn-small" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">{{ trans("messages.user_managmt.action") }}</span>
									</button>
									<ul class="dropdown-menu" role="menu">
										@if($record->is_recommended)
											<li>
												<a href="{{URL::to('admin/restaurant-manager/recommended-status/'.$record->id.'/0')}}"  >{{ trans("Remove as recommended") }} </a>
											</li>
										@else
											<li>
												<a href="{{URL::to('admin/restaurant-manager/recommended-status/'.$record->id.'/1')}}" >{{ trans("Mark as recommended")  }} </a>
											</li>
										@endif
										@if($record->is_active)
											<li>
												<a href="{{URL::to('admin/restaurant-manager/update-restaurant-status/'.$record->id.'/0')}}" >{{ trans("messages.user_managmt.mark_as_inactive") }} </a>
											</li>
										@else
											<li>
												<a href="{{URL::to('admin/restaurant-manager/update-restaurant-status/'.$record->id.'/1')}}" >{{ trans("messages.user_managmt.mark_as_active")  }} </a>
											</li>
										@endif
										<li class="divider"></li>
										<li>
											<a href="{{URL::to('admin/restaurant-manager/view-restaurant/'.$record->id)}}" class="">{{ trans("messages.user_managmt.view") }} </a>
										</li>
										<li>
											<a href="{{URL::to('admin/restaurant-manager/edit-restaurant/'.$record->id)}}" class="">{{ trans("messages.user_managmt.edit") }}</a>
										</li>
										<li>
											<a href="{{ URL::to('admin/restaurant-manager/delete-restaurant/'.$record->id) }}" data-delete="delete" class="">{{ trans("messages.user_managmt.delete") }} </a>
										</li>
										<li>
											
										</li>
									</ul>
								</div>
								<a class="btn  btn-small btn-success " href="{{ URL::to('admin/restaurant-manager/manage-time/'.$record->id) }}" >Manage Time</a>
																
								<a class="btn btn-warning btn-small" href="{{URL::to('admin/restaurant-manager/reviews/'.$record->id)}}" class="">Reviews</a>
										
							</td>
						</tr>
						
					@endforeach
				@else
					<tr>
						<td align="center" width="100%" colspan="4"> {{ trans("messages.no_records_found") }}</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
<style>
	.chosen-container ul li{
	color:black !important;
	margin: 0px 0px !important;
	}
	.chosen-container ul li span{
	float:none !important;
	}
</style>
@stop

