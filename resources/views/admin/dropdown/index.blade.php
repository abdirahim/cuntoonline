@extends('admin.layouts.default')
@section('content')
<script type="text/javascript">
	/* For delete dropdown */
	$(function(){
		$('[data-delete]').click(function(e){
			e.preventDefault();
			// If the user confirm the delete
			if (confirm("Do you really want to delete this dropdown ?")) {
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
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/dropdown-manager/'.$type,'class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Name' }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul>
				<li class="clearfix">
					<span></span> 
					<div id="mws-textglow-op"></div>
				</li>
			</ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/dropdown-manager/'.$type)}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ studly_case($type) }} </span>
		<a href="{{URL::to('admin/dropdown-manager/add-dropdown/'.$type)}}" class="btn btn-success btn-small align">{{ 'Add New ' }} {{ (studly_case($type)=='FoodCategory') ? 'Food Category':studly_case($type)}} </a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable {{ (!$result->isEmpty()) ? '' : 'details' }} ">
			<thead>
				<tr>
					<th width="40%">
						{{
							link_to_action(
								'DropDownController@listDropDown',
								'Name',
								array(
								$type,
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="30%">
						{{
							link_to_action(
								'DropDownController@listDropDown',
								'Created ',
								array(
								$type,
								'sortBy' => 'created_at',
								'order' => ($sortBy == 'created_at' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'created_at' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'created_at' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr>
						<td data-th='Name'>{{ $record->name }}</td>
						<td data-th='Created At'>{{ date(Config::get("Reading.date_format") , strtotime($record->created_at)) }}</td>
						<td data-th='Action'>
							<a href="{{URL::to('admin/dropdown-manager/edit-dropdown/'.$record->id.'/'.$type)}}" class="btn btn-info btn-small">{{ 'Edit' }} </a>
							@if($record->is_display)
								<a href="{{URL::to('admin/dropdown-manager/update-cusine-status/'.$record->id.'/0')}}" class="btn btn-success btn-small">{{ 'Hide  From Front End' }} </a>
							@else
								<a href="{{URL::to('admin/dropdown-manager/update-cusine-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small">{{ 'Show  On  Front End' }} </a>
							@endif
						</td>
					</tr>
					@endforeach  
				@else
					<tr>
						<td align="center" width="100%" colspan="3"> {{ trans("messages.no_records_found") }}</td>
					</tr>
				@endif
			</tbody>
		</table>
		
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
@stop

