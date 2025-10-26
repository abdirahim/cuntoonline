@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="login-signup-box mt30">
					<div class="page-heading" id="scroll_error">
						<div id="signup_error_div"></div>
						<h4>Edit Profile</h4>
						{{ Form::open(['role' => 'form','url' => 'edit-profile','id'=>'edit-form','novalidate']) }}
							<div class="form-group">
								<label>Full Name</label>
								{{ Form::text(
										'full_name', 
										Auth::user()->full_name,
										['class'=>'form-control','id'=>'exampleInputEmail1','placeholder'=>trans("messages.Full Name") , 'required','data-errormessage-value-missing' => trans("messages.The full name field is required.") ]
									) 
								}}
							</div>
							<div class="form-group">
								<label>Email</label>
								{{ Form::email(
										'email', 
										Auth::user()->email,
										['class'=>'form-control',($email) ? 'readonly': '','placeholder'=>trans("messages.Email"), 'required','data-errormessage-value-missing' => trans("messages.The email field is required."),'data-errormessage-type-mismatch' => trans("messages.The email must be a valid email address.") ]
									) 
								}}
							</div>
							@if($email)
								<div class="form-group">
									<label>Password</label>
									{{ Form::password(
											'password', ['class'=>'form-control','placeholder'=>trans("messages.Password"),'id'=>'password1','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans("messages.Minimum 6 letters or numbers required"),'required','data-errormessage-value-missing' => trans("messages.The password field is required.")]
										) 
									}}
								</div>
								<div class="form-group">
									<label>Re-Type Password</label>
									{{ Form::password(
											'confirm_password', 
											['class'=>'form-control','id'=>'cnfm_password','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans("messages.Minimum 6 letters or numbers required"),'placeholder'=>trans("messages.Confirm Password"), 'required','data-errormessage-value-missing' => trans("messages.The confirm password field is required.")]
										) 
									}}
								</div>
							@endif
							<div class="form-group">
								<input type="submit" id="submit" class="btn btn-primary" value="Save" />
							</div>
							
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
	// edit account form 
	$(document).ready(function() {
		// prepare Options Object 
		var options = {
			beforeSubmit: function() { 
				$('#signup_error_div').hide();
				$("#submit").button('loading');
				$("#overlay").show();
			},
			success:function(data){// on success 
				$("#submit").button('reset');
				$("#overlay").hide();
				if(data.success==1){
					$('#signup_error_div').hide();	
					$('#cnfm_password').val('');
					$('#password1').val('');
					notice('<?php echo trans("Update Profile"); ?>','<?php echo trans("messages.update_profile_msg"); ?>' , 'success');				
				}else{
					$('#signup_error_div').hide();
					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
					$('#signup_error_div').html(error_msg);
					// top position relative to the document
					var pos = $("#scroll_error").offset().top;

					// animated top scrolling
					$('body, html').animate({scrollTop: pos});
				
					$('#signup_error_div').show('slow');
				}
				return false;
			},
			resetForm:false
		}; 
		 
		// pass options to ajaxForm 
		$('#edit-form').ajaxForm(options);
	});
</script>
@stop
