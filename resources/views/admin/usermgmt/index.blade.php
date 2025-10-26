@extends('admin.layouts.default')
@section('content')
{{ HTML::style('css/admin/user/list.css') }}
{{ HTML::style('css/admin/developer.css') }}
<!--
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
-->
<script type="text/javascript">
	$(function(){
		/* For delete user detail */
		$('[data-delete]').click(function(e){
			
		     e.preventDefault();
			// If the user confirm the delete
			if (confirm('Do you really want to delete the element ?')) {
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
	
		/* For activate/deactivate user detail */
		$('[data-status]').click(function(e){
		
		     e.preventDefault();
			// If the user confirm the delete
			if (confirm('Do you really want to mark status as '+$(this).attr('rel')+'?')) {
				// Get the route URL
				var url = $(this).prop('href');
				// Get the token
				var token = $(this).data('status');
				// Create a form element
				var $form = $('<form/>', {action: url, method: 'get'});
				// Add the DELETE hidden input method
				var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'status'});
				// Add the token hidden input
				var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
				// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
				$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
			} 
		});
	});
	
</script>
<div  class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.user_managmt.users_list") }}
		</span>
		<a href="{{URL::to('admin/users/add-user')}}" class="btn btn-success btn-small align">{{ trans("messages.user_managmt.add_user") }} </a>
	</div>
	<div role="content" class="inner-spacer">
		<div id="items" class="items-switcher items-view-list">
				<!-- Search Box starts from here -->
			<div style="padding:10px;">
				<div class="col-md-12">
					<div class="input-group" id="search_div" >
						{{ Form::open(['role' => 'form','url' => 'admin/users','class' => 'mws-form']) }}
						{{ Form::hidden('display') }}
						{{ Form::text('full_name',((isset($searchVariable['full_name'])) ? $searchVariable['full_name'] : ''), ['class' => 'small','placeholder'=>'Search By Name']) }}
						
						{{ Form::text('email',((isset($searchVariable['email'])) ? $searchVariable['email'] : ''), ['class' => 'small','placeholder'=>'Search By Email']) }}
				
						<span class="input-group-btn ">
						<button type="submit" class="btn btn-default">Go!</button>
						<a href="{{URL::to('admin/users')}}"  class="btn btn-default">Reset</a>
						</span> 
						{{ Form::close() }}
					</div>
				</div>
			</div>
			<!-- Search Box starts from here -->
			<ul>
				@if(!$result->isEmpty())	
				@foreach($result as $key => $record)					
				<li>
					<div class="items-inner clearfix">
						<a class="items-image" href="javascript:void(0)"><img class="img-circle" src="{{ WEBSITE_IMG_URL}}admin/user.png" alt=="user-icon"></a>
						
						<h3 class="items-title" >{{ $record->full_name }}</h3>
						
						<div class="items-details">
							<strong>Email:</strong> {{ $record->email }}<br/>
							<strong>Registered:</strong>{{ date(Config::get('Reading.date_format'),strtotime($record->created_at)) }}<br/>
							<strong>Last Login:</strong> 
							@if($record->userLastLogin['created_at']!='')
								{{ date(Config::get('Reading.date_format'),strtotime($record->userLastLogin['created_at'])) }}
							@else
								{{ 'User not login'}}
							@endif
						</div>
						@if($record->active)
							<span class="label label-success">Activated</span>
						@else
							<span class="label label-danger">Deactivated</span>
						@endif	
						<div class="control-buttons">
							@if($record->active)
								<a rel = "deactivate" data-status="status"  title="{{ trans('messages.user_managmt.mark_as_inactive') }}" href="{{URL::to('admin/users/update-status/'.$record->id.'/0')}}">
								<i class="fa fa-ban red-icon"></i>
								</a>
							@else
								<a rel = "activate" data-status="status"  title="{{ trans('messages.user_managmt.mark_as_active') }}" href="{{URL::to('admin/users/update-status/'.$record->id.'/1')}}">
								<i class="fa fa-check-circle green-icon"></i>
								</a>
							@endif

							<a title="{{ trans('messages.user_managmt.edit') }}" href="{{URL::to('admin/users/edit-user/'.$record->id)}}">
							<i class="fa fa-cog blue-icon"></i>
							</a>
							<a   data-delete="delete" title="{{ trans('messages.user_managmt.delete') }}" href="{{ URL::to('admin/users/delete-user/'.$record->id) }}">
							<i class="fa fa-times-circle red-icon"></i>
							</a> 
							
						</div>
					</div>
				</li>
				@endforeach
				@else
					<li>
						<div class="items-inner clearfix">
							<h4 align="center">  {{"No Records Found "}} </h4>
						</div>
					</li>
				@endif                 
			</ul>
		</div>
	</div>
		{{ $result->appends($searchVariable)->links() }}
</div>
@stop

