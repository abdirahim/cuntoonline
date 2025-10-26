@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="login-signup-box mt30">
					<div class="page-heading" id="scroll_error">
						<div id="signup_error_div"></div>
						<h4>Log in</h4>
						{{ Form::open(['role' => 'form','url' => 'login','id'=>'login_form','method'=>'post','class'=>'login-form']) }}
						{{ csrf_field() }}
							<div class="form-group">
								<label>Email</label>
								{{ Form::email(
										'email', 
										null,
										['class'=>'form-control','placeholder'=>trans("messages.Email"), 'required','data-errormessage-value-missing' => trans("messages.The email field is required."),'data-errormessage-type-mismatch' => trans("messages.The email must be a valid email address.") ]
									) 
								}}
							</div>
							<div class="form-group">
								<label>Password</label>
								{{ Form::password(
										'password', 
										['class'=>'form-control','placeholder'=>trans("messages.Password"),'id'=>'password1','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans("messages.Minimum 6 letters or numbers required"),'required','data-errormessage-value-missing' => trans("messages.The password field is required.")]
									) 
								}}
							</div>
							<div class="form-group">
								<div class="styled-selector">
									<input type="checkbox" name="remember_me" id="rememberme">
									<label for="rememberme">Remember Me</label>
								</div>
							</div>
							<div class="form-group">
								<input type="submit" id="submit" class="btn btn-primary" value="Login" />
								<a href="{{ URL::to('forget_password')}}">Forgot Password? Click here</a>
							</div>
							<div class="or-text">
								<span>OR</span>
							</div>
							<a href="{{ URL::to('signup')}}" class="btn btn-primary">Signup</a>
							<div class=" social-login-box">
								<a href="{{ URL::to('login-facebook')}}"><img src="{{ config("constants.WEBSITE_IMG_URL") }}facebook-login.png" alt="fb-signin"></a>
								<a href="{{ URL::to('login-google')}}"><img src="{{ config("constants.WEBSITE_IMG_URL") }}google-login.png" alt="google-login"></a>
								<a href="{{ URL::to('login-twitter')}}"><img src="{{ config("constants.WEBSITE_IMG_URL") }}twitter-login.png" alt="twitter-login"></a>
							</div>
							{{ Form::hidden('redirect',$redirect_filter) }}	
							{{ Form::close() }}
					</div>
				</div>
			</div>
			 <div class="col-md-8 col-sm-6 text-center">
            	<div class="bx-right">
                	<img src="{{ config("constants.WEBSITE_IMG_URL") }}resturent-right.png" alt="restaurant">
                </div>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">
	// login  form 
	$(document).ready(function() {
		// prepare Options Object 
		  
		var options = { 
			beforeSubmit: function() { 
				$('#signup_error_div').hide();
				$("#submit").button('loading');
				$("#overlay").show();
			},

			success:function(data){



			$("#submit").button('reset');
			$("#overlay").hide();
				 if(data.success==1){

					if(data.redirect_filter==1){

						window.location.href	=	"<?php echo URL::previous(); ?>";
					}else{

						window.location.href	=	data.redirect;
					}
				}else{


					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
					$('#signup_error_div').hide();
					$('#signup_error_div').html(error_msg);
					
					// top position relative to the document
					var pos = $("#scroll_error").offset().top;

					// animated top scrolling
					$('body, html').animate({scrollTop: pos});
					$('#signup_error_div').show('slow');
				}
				return false;
			},
			// dataType: 'json',
			resetForm:false,


		}; 
		// pass options to ajaxForm 
		$('#login_form').ajaxForm(options);
	});
</script>
@stop
