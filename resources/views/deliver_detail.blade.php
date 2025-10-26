@extends('layouts.default')
@section('content')

<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<div class="delivery-details step-1 mt30">
					<div class="page-heading">
						<h4>Delivery Details</h4>
					</div>
					<div class="address-box" id="scroll_error">
						<div id="address_form" @if($countAddress>0) style="display:none" @else style="display:block"  @endif >
						<h3>Add new address</h3>
						<div id="signup_error_div"></div>
						{{ Form::open(['role' => 'form','url' => 'add-address','id'=>'add_address_form','novalidate'=>'novalidate','class'=>'form']) }}
						
							<div id="add_address" >
								<div class="row">	
								<div class="col-sm-6 ">
									<div class="form-group">
										<label for="name">Full Name:</label>
										{{ Form::text(
										'full_name', 
										'' ,
										['class'=>'form-control','placeholder'=>trans("Full Name") , 'required','data-errormessage-value-missing' => trans("The Full name field is required.") ]
										) 
										}}
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label for="exampleInputName2">Phone Number:</label>
										{{ Form::text(
										'phone_number', 
										null,
										['class'=>'form-control','placeholder'=>trans("messages.Phone Number"),'data-errormessage-value-missing'=>trans("The Phone number field is required.")]
										) 
										}}
									</div>
								</div>
								</div>
								<div class="row">
								<div class="col-sm-12 ">
									<div class="form-group">
										<label for="exampleInputName2">Address:</label>
										{{ Form::textarea(
										'address', 
										'',
										['class'=>'form-control','rows'=>'2','placeholder'=>trans("messages.Address"), 'required','data-errormessage-value-missing' => trans("messages.The Address field is required.")]
										) 
										}}
									</div>
								</div>
								<div class="row">
								<div class="col-sm-6 pull-right">
									<div class="form-group submit-address">
										<label class="hidden-sm">&nbsp;</label>
										<input id="submit" type="submit" value="Add address"  class="btn btn-default" />
										<input @if($countAddress>0) style="display:block" @else style="display:none"  @endif onclick="showDeliver()" id="cancel" type="button" value="Cancel"  class="btn btn-primary" />
									</div>
								</div>
								</div>
							</div>
							</div>
						
						{{ Form::close() }}
						<div class="clearfix"></div>
					</div>
					<!--second -->
				<div id="deliver_form" @if($countAddress==0) style="display:none" @endif>
					<div class="row">
						<div class='col-sm-12 '>
							<h3>
								Choose from existing address 
								<span onclick="showAddress()" class="btn btn-default">
									Add new address
								</span>
							</h3>
						</div>
					</div>
					
						{{ Form::open(['role' => 'form','url' => 'deliver-detail?delivertype=','id'=>'deliver_form','novalidate'=>'novalidate','class'=>'form-inline']) }}
						<div id="signup_error_div_deliver"></div>
						<div class="row" id="scroll_error_deliver">
							<div id="user_addresses">
								@if(!$userAddresses->isEmpty())
									@foreach($userAddresses as $key=> $address)
										<div class='col-md-4 col-sm-6'>
											<div class="radio styled-selector equal-height">
												<input id="{{ $address->id }}" value="{{ $address->id }}" type="radio" name="deliver_address" @if($key==0) checked @endif>
												<label for="{{ $address->id }}">
													{{ $address->full_name }}
													<br>
													{{ $address->address }}
													<br>
													{{ $address->pin_code}}
													<br>
													{{ $address->phone_number}}
												</label>
											</div>
										</div>
										
									@endforeach
								@endif
                               <div class="clearfix"></div>
                                <div class="form-seprator" id="sep"> <img src="{{ config("constants.WEBSITE_IMG_URL") }}form-sep.png" alt="form-seprator"> </div>
							</div>
							
							<div class="col-sm-6">
								<div class="form-group">
									<label for="date">Date:</label>
									<input name="deliver_date" id="datepicker" type="text" class="form-control" id="date" placeholder="23/10/2015">
								</div>
							</div>
							<div class='col-sm-6 '>
								<div class="form-group time">
									<label>Time</label>
									<div class='input-group date' id='datetimepicker3'>
										<input  name="deliver_time" type='text'  class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group delivery-notes">
									<label for="notes">Special Instructions::</label>
									<textarea name="instruction" class="form-control" id="notes" ></textarea>
								</div>
							</div>
							<?php $deliverType	=	Input::get('delivertype'); ?>
							<input name="deliver_type" value="{{ $deliverType }}"  type="hidden" class="form-control"  >
							@if(!empty($cart))
								<div class="col-sm-6">
									<div class="form-group submit-next">
										<label class="hidden-sm">&nbsp;</label>
										<input id="submit2" type="submit" value="Next"  class="btn btn-default" />
									</div>
								</div>
							@endif
							
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
		
	</div>
		<div class="col-md-3 col-sm-4" id="product_cart">
			@include('elements.cart')
		</div>
		</div>
	</div>
</div>
<!-- js for equal height of the div  -->
{{ HTML::script('js/jquery.matchHeight-min.js') }}

<script type="text/javascript">
	// equal height js
	$(function() {
		$('.equal-height').matchHeight();
	});

	/**
	 * function for show  add address form and hide deliver
	 * @param null
	 * @return null
	 */
	function showAddress(){
		document.getElementById("add_address_form").reset();
		$("#address_form").show();
		$("#deliver_form").hide();
	}
		
	// date picker
	$(function () {
		$('#datetimepicker3').datetimepicker({
			format: 'LT',
		});
		$('#datepicker').datetimepicker({
			 format: 'YYYY/MM/DD',
			 minDate: new Date()
		});
	});
	
	/**
	 * function for add item in cart and remove
	 * @param null
	 * @return null
	 */
	function addcart(e){
		var meal_id 		= 	$(e).attr('data-meal');
		var instruction 	=	$("#specific_ins"+meal_id).val();
		
		// type for new , plus , minus
		var type			= 	$(e).attr('data-type');
		var food_category 	= 	$(e).attr('data-food-category');
		
		$.ajax({
			url: "<?php echo URL::to('add-to-cart');?>",
			data: {
				'specific_instruction':instruction,
				'type': type,
				'food_category': food_category,
				'meal_id': meal_id,
			},
			type: "POST",
			beforeSend:function(){
				$("#overlay").show();
			},
			success: function(r){
				$("#product_cart").html(r.htmlcart);
				if(r.countCart<1){
					$("#submit2").addClass('hide');
				}else{
					$("#submit2").removeClass('hide');
				}
				$("#overlay").hide();
			}
		});	
	}
	
	// Ajax calling for address form
	// add address form
	var options = {
		beforeSubmit: function() { 
			$('#signup_error_div').hide();
			$("#submit").button('loading');
			$("#overlay").show();
		},
		success:function(data) {// on success
			$("#submit").button('reset');
			$("#overlay").hide();
			if(data.success==1){
				$('#signup_error_div').hide();
				$("#address_form").hide();
						$.ajax({
							url: "<?php echo URL::to('user-addresses');?>",
							data: {
							},
							type: "POST",
							beforeSend:function(){
								$("#overlay").show();
							},
							success: function(r){
								$("#user_addresses").html(r);
								$("#overlay").hide();
							}
						});	
				$("#deliver_form").show();
			
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
	$('#add_address_form').ajaxForm(options);
	
	// Ajax calling for deliver form
	var options2 = {
		beforeSubmit: function() { 
			$('#signup_error_div_deliver').hide();
			$("#submit2").button('loading');
			$("#overlay").show();
		},
		success:function(data){// on success 
			$("#submit").button('reset');
			$("#overlay").hide();
			if(data.success==1){
				$('#signup_error_div_deliver').hide();
				var deliverType			= 	<?php echo Input::get('delivertype');  ?>	
				window.location.href	=	"<?php echo URL::to('payment'); ?>";
			}else{
				$("#submit2").button('reset');
				$('#signup_error_div_deliver').hide();
				error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
				$('#signup_error_div_deliver').html(error_msg);
				// top position relative to the document
				var pos = $("#scroll_error_deliver").offset().top;
	
				// animated top scrolling
				$('body, html').animate({scrollTop: pos});
			
				$('#signup_error_div_deliver').show('slow');
			}
			return false;
		},
		resetForm:false
	}; 
	$('#deliver_form').ajaxForm(options2);
	
	/**
	 * function for show deliver form
	 * @param null
	 */
	function showDeliver(){
		$("#address_form").hide();
		$("#deliver_form").show();
	}
	
</script>
@stop

