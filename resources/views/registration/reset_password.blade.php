@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-6">
				<div class="login-signup-box mt30">
					<div class="page-heading" id="scroll_error">
						<div id="signup_error_div"></div>
						<h4>{{ trans('messages.Reset Password') }}</h4>
							{{ Form::open(['role' => 'form','url' => '/save_password','id'=>'reset_form']) }}
							{{ Form::hidden('validate_string',$validate_string, []) }}
							<div class="form-group">
								<label>{{ trans('messages.New Password') }}</label>
								{{ Form::password('new_password', ['class'=>'form-control','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans("messages.Minimum 6 letters or numbers required"),'required','data-errormessage-value-missing' => trans("messages.The new password field is required.")]) }}
							</div>
							<div class="form-group">
								<label>{{ trans('messages.Confirm Password') }}</label>
								{{ Form::password('new_password_confirmation', ['class'=>'form-control','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans("messages.Minimum 6 letters or numbers required"),'required','data-errormessage-value-missing' => trans("messages.The confirm password field is required.")]) }}
							</div>
							<div class="form-group">
								<button type="submit" id="submit" class="btn btn-primary">{{ trans('messages.Submit') }}</button>
								</p>
							</div>
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
				$("#submit").val('loading...');
				$("#overlay").show();
			},
			success:function(data){ 
				$("#submit").val("<?php echo trans("messages.Submit"); ?>");
				$("#overlay").hide();
				
				if(data.success==1){					
					notice('<?php echo trans("messages.Reset Password"); ?>','<?php echo trans("messages.reset_pswd_msg"); ?>' , 'success');
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
		$('#reset_form').ajaxForm(options);
	});
</script>
@stop
