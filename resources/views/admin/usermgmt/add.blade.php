@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">	
	<div class="mws-panel-header">
		<span> {{ trans("messages.user_managmt.add_user") }} </span>
		<a href="{{URL::to('admin/users')}}" class="btn btn-success btn-small align">{{ trans("messages.user_managmt.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/users/add-user','class' => 'mws-form']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
				{{ HTML::decode( Form::label('full_name', 'Full Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('full_name','',['class' => 'small']) }}
						<div class="error-message help-inline">
							{{  $errors->first('full_name') }}
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('email', 'Email<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('email', '', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('email') }}
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('password', 'Password<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::password('password',['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('password') }}
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('confirm_password', 'Confirm Password<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::password('confirm_password',['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('confirm_password') }}
						</div>
					</div>
				</div>	
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/users/add-user')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

@stop
