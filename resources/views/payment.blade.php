@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="col-md-9 col-sm-8">
			<div class="delivery-details step-2 mt30">
			
				<div class="address-box">
					<h3>Pay Now</h3>
					<form class="form-inline" action="checkout">
						<div class="row">
							<div class="col-md-6">
								<div class="styled-selector">
									<input onchange="showPaymentDescription('zaad')" value="{{ config('constants.ZAAD_PAYMENT') }}" checked="checked" type="radio" name="payment_type" id="payment-type">

									<label for="payment-type">Zaad <img src="{{ asset('/img/zaad.png') }}"/></label>
								</div>
							</div>
						
							<div class="col-md-6">
								<div class="styled-selector">
									<input onchange="showPaymentDescription('edahab')"  value="{{ config('constants.EDAHAB_PAYMENT')}}" type="radio" name="payment_type" id="payment-type2" >
									
									<label for="payment-type2">E-dahab <img src="{{ config("constants.WEBSITE_IMG_URL").'e-dahab.png'}}"/></label>
								</div>
							</div>
					
						
							<div class="col-md-12 mt30">
								<div id="zaad_payment_description" class="restaurant-description no-height">
									{{ (!empty($blocks) && isset($blocks['zaad-service'])) ? $blocks['zaad-service']['description'] : '' }}
								</div>
								<div id="edahab_payment_description" class="restaurant-description no-height" style="display:none">
									{{ (!empty($blocks) && isset($blocks['e-dahab'])) ? $blocks['e-dahab']['description'] : '' }}
								</div>
							</div>
							
							<div class="clearfix mt50">&nbsp;</div>
							
							<div class="col-md-6">
								<p id="zaad_payment_hint" class="payment-hint">
									{{ (!empty($blocks) && isset($blocks['zaad-service-hint'])) ? $blocks['zaad-service-hint']['description'] : '' }}
								</p>
								<p id="edahab_payment_hint" style="display:none"  class="payment-hint">
									{{ (!empty($blocks) && isset($blocks['e-dahab-hint'])) ? $blocks['e-dahab-hint']['description'] : '' }}
								</p>
							</div>
						
							<div class="col-md-6">
								<input type="text" class="form-control width300" value="{{ Input::old('payment_phone_number')}}" name="payment_phone_number" placeholder="Please enter your phone number"/>
								<div class="error-message help-inline">
									<?php echo $errors->first('payment_phone_number'); ?>
								</div>
							</div>
						
							<div class="col-md-12 mt50">
								<div class="form-group submit-next">
									<a href="javascript:void(0)"><button id="confirm_button" class="btn btn-default" type="submit">Confirm Order</button></a>
								</div>
							</div>
							
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-md-3 col-sm-4" id="product_cart">
			@include('elements.cart')
		</div>
		
	</div>
</div>
<script type="text/javascript">
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
			var deliver_type	=	<?php echo  Input::get('delivertype'); ?>

		
		$.ajax({
			url: "<?php echo URL::to('add-to-cart');?>",
			data: {
				'specific_instruction':instruction,
				'type': type,
				'food_category': food_category,
				'meal_id': meal_id,
				'deliver_type': deliver_type
			},
			type: "POST",
			beforeSend:function(){
				$("#overlay").show();
			},
			success: function(r){
				$("#product_cart").html(r.htmlcart);
				if(r.countCart<1){
					$("#confirm_button").addClass('hide');
				}else{
					$("#confirm_button").removeClass('hide');
				}
				$("#overlay").hide();
			}
		});	
	}
	
	/**
	 * function for show hide payment gateway detail 
	 * @param null
	 * @return null
	 */
	 
	 function showPaymentDescription(type){
		if(type=='zaad'){
			$("#zaad_payment_description").show();
			$("#edahab_payment_description").hide();
			$("#zaad_payment_hint").show();
			$("#edahab_payment_hint").hide();
		}else{
			$("#zaad_payment_description").hide();
			$("#edahab_payment_description").show();
			$("#zaad_payment_hint").hide();
			$("#edahab_payment_hint").show();
		}
	 }
	 
	
</script>
@stop

