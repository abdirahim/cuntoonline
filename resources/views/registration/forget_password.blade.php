@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="login-signup-box mt30">
					<div class="page-heading" id="scroll_error">
						<div id="signup_error_div"></div>
						<h4>Forgot Password</h4>
						{{ Form::open(['role' => 'form','url' => '/forget_password','id'=>'forget_form']) }}
							<div class="form-group">
								<label>Email</label>
								{{ Form::email(
										'email', 
										null,
										['class'=>'form-control','placeholder'=>trans("messages.Email"), 'required','data-errormessage-value-missing' => trans("messages.The email field is required."),'data-errormessage-type-mismatch' => trans("messages.The email must be a valid email address.") ]
									) 
								}}
							</div>
							{{
							Form::submit(
									trans("messages.Submit"),
									['class'=>'btn btn-primary','id'=>'submit']
								) 
							}}
						{{ Form::close() }}
						<a class="btn btn-default" href="{{ URL::to('login')}}">Cancel</a>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-sm-6 text-center">
            	<div class="bx-right">
                	<img src="{{ WEBSITE_IMG_URL }}resturent-right.png" alt="restaurant">
                </div>
            </div>
		</div>
	</div>
</div>    

<script type="text/javascript">
	$(document).ready(function() {
		// prepare Options Object 
		var options = {
			beforeSubmit: function() { 
				$('#signup_error_div').hide();
				$("#submit").button('reset');
				$("#overlay").show();
			},
			success:function(data){ // on success
				
				$("#submit").val("<?php echo trans("messages.Submit"); ?>");
				$("#overlay").hide();
				
				if(data.success==1){
					notice('Forgot Password','<?php echo trans('messages.forgot.pswd.msg') ?>','success');
					setTimeout(function(){ location.assign("{{URL::to('login')}}"); }, 3000);
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
		$('#forget_form').ajaxForm(options);
	});
</script>
@stop
