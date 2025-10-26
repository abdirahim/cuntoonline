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
		
		<div id="mws-login-wrapper" style="top: 30%;">
			<div class="mws-form-row" style="text-align:center;">
				<img src="<?php echo config("constants.WEBSITE_URL") .'image.php?width=550px&height=180px&image='. config("constants.WEBSITE_IMG_URL"); ?>logo.png" alt="logo">
			</div>
			<div class="clearfix">&nbsp;</div>
			<div id="mws-login">
				<h1>Admin Login</h1>
				<div class="mws-login-lock"><i class="icon-lock"></i></div>
					
					<div id="mws-login-form">
						{{ Form::open(['role' => 'form','url' => 'admin/login']) }}
						<div class="mws-form-row">
							<div class="mws-form-item">
								{{ Form::text('email', null, ['placeholder' => 'Email', 'class' => 'mws-login-username required']) }}
							</div>
						</div>
						<div class="mws-form-row">
							<div class="mws-form-item">
								{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'mws-login-password required']) }}
						</div>
						</div>
						<div class="mws-form-row">
							<input type="submit" value="Login" class="btn btn-primary mws-login-button">
						</div>
						<div class="mws-form-row mws-inset" id="mws-login-remember">
							<a href="{{ URL::to('admin/forget_password')}}" style="color:#fff">Forgot your password?</a>					
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</body>
</html>

<script>
	// for close the message 
$(document).ready(function(){
	$(".close pull-right").click(function(){
		$(".mws-form-message").hide();
	})
});
</script>
