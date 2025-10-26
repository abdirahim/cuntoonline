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
		
		<div id="mws-login-wrapper" style="margin-top: -210px;">
			<div id="mws-login">
				<h1>Reset Password</h1>
				<div class="mws-login-lock"><i class="icon-lock"></i></div>
					<div id="mws-login-form">
						{{ Form::open(['role' => 'form','url' => 'admin/save_password']) }}
						{{ Form::hidden('validate_string',$validate_string, []) }}
						<div class="mws-form-row">
							<div class="mws-form-item">
								{{ Form::password('new_password',  ['placeholder' => 'New Password', 'class' => 'mws-login- required','style'=>'width:100%']) }}
							</div>
							<div class="error-message help-inline">
									{{ $errors->first('new_password') }}
							</div>
						</div>
						<div class="mws-form-row">
							<div class="mws-form-item">
									{{ Form::password('new_password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'mws-login- required','style'=>'width:100%']) }}
							</div>
							<div class="error-message help-inline">
									{{ $errors->first('new_password_confirmation') }}
							</div>
						</div>
					</div>
						<div class="mws-form-row">
							<input type="submit" value="Submit" class="btn btn-primary mws-login-button">
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</body>
</html>
