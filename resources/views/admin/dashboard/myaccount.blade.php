@extends('admin.layouts.default')
@section('content')
<?php
	$userInfo	=	Auth::user();
	$full_name	=	(isset($userInfo->full_name)) ? $userInfo->full_name : '';
	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
	?>
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'My Account' }}</span>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/myaccount','class' => 'mws-form']) }}
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('full_name', 'Full Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('full_name',$full_name, ['class' => 'small']) }} 
					<div class="error-message help-inline">
						{{ $errors->first('full_name') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('email', 'Email<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('email', $email, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('email') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('old_password', 'Old Password', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::password('old_password',['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('old_password') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('new_password', 'New Password', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::password('new_password', ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('new_password') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row ">
				{{  Form::label('confirm_password', 'Confirm Password', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::password('confirm_password', ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('confirm_password') }}
					</div>
				</div>
			</div>
		</div>
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="Save" class="btn btn-danger">
				<a href="{{URL::to('admin/myaccount')}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
@stop

