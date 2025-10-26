<!DOCTYPE html>
<html>
	<head>
		<title>Cunto Online</title>
		{{ HTML::style('css/admin/bootstrap.css') }}
		{{ HTML::style('css/admin/icons/icol16.css') }}
		{{ HTML::style('css/admin/fonts/icomoon/style.css') }}
		{{ HTML::style('css/admin/icons/icol32.css') }}
		{{ HTML::style('css/admin/mws-theme.css') }}
		{{ HTML::style('css/admin/themer.css') }}
		{{ HTML::style('css/admin/login.css') }}
		{{ HTML::style('css/admin/from.css') }}
		{{ HTML::script('js/admin/jquery-2.1.1.min.js') }}
		{{ HTML::script('js/admin/core/mws.js') }}
	</head>
	<body>
		
		@if(Session::has('flash_notice'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message success">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('flash_notice') }}
				</p>
			</div>
		</div>
		@endif
		
		@if(Session::has('error'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message error">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('error') }}
				</p>
			</div>
		</div>
		@endif
		
		@if(Session::has('success'))
		<div class="grid_8 mws-collapsible" style="padding:20px">
			<div class="mws-form-message success">
				<a href="javascript:void(0);" class="close pull-right">x</a>
				<p>
					{{ Session::get('success') }}
				</p>
			</div>
		</div>
		@endif
		<div id="mws-login-wrapper">
			<div id="mws-login">
				<h1>Forgot Your Password</h1>
				<div class="mws-login-lock"><i class="icon-lock"></i></div>
					<div id="mws-login-form">
						{{ Form::open(['role' => 'form','url' => 'admin/send_password']) }}
						<div class="mws-form-row">
							<div class="mws-form-item">
								{{ Form::text('email', null, ['placeholder' => 'Email', 'style'=>'width:100%','class'=>'mws-login-username']) }}
							</div>
							<div class="error-message help-inline">
								<?php echo $errors->first('email'); ?>
							</div>
						</div>
						<div align="right" class="mws-form-row">
							<input type="submit" value="Submit" class="btn btn-primary mws-login-button" style="width:35% !important;">
							<a style="width:65px !important;" class="btn btn-danger mws-info-button" href="{{ URL::to('/admin')}}">Cancel</a>
						</div>
						
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
