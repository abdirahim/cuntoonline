<div class="order-sidebar">
    <h2>Your Order</h2>
    <div class="order-info-box">
        <div>
            <div class="table-responsive order-item-table-hold">
                <table class="order-item-table table">
                    <?php $subTotal 	=	0;?>
                    @if(!empty($cart['items']))
                    @foreach($cart['items'] as $key=>$item)
                    <tr>
                        <td>{{ $item['quantity'] }}</td>
                        <td>
                            <p>{{ $item['name'] }} </p>
                            <p class="additional-items">
                                {{ $item['specific_instruction'] }}
                            </p>
                        </td>
                        <td>{{ Config::get('Site.currency')}}{{ $item['price'] }}</td>
                        <td>
                            <a data-type="plus" data-food-category="{{ $item['food_category'] }}" data-meal="{{$key}}" onclick='addcart(this)' href="javascript:void(0)"><img src="{{ config("constants.WEBSITE_IMG_URL") }}add-item.png" alt="add-item"></a>
                            <a  data-type="minus" data-food-category="{{ $item['food_category'] }}" data-meal="{{$key}}" onclick='addcart(this)' href="javascript:void(0)"><img src="{{ config("constants.WEBSITE_IMG_URL") }}remove-item.png" alt="remove-item"></a>
                        </td>
                    </tr>
                    <?php  $subTotal	+= $item['price']*$item['quantity'];	?>
                    @endforeach
                    @endif
                </table>
            </div>

            <div class="oprder-total table-responsive ">
                <table class="order-total-table table">
                    <tr>
                        <td>Sub-total</td>
                        <td>{{ Config::get('Site.currency')}}<span id="sub_total">{{ $subTotal }}</span></td>
					</tr>
                        <?php
							$deliveryType	=	(isset($restaurantDetail->collection ) && $restaurantDetail->collection == 1) ? config("constants.COLLECTION") : config("constants.DELIVERY");
							
							if(Session::has('cart_item.deliver_type')){
								$deliveryType	=	Session::get('cart_item.deliver_type');
							}
							$deliveryType	;
                            $charges		= 	0;	
							if($deliveryType == config("constants.DELIVERY")){

                                $cuntoCharges = $subTotal*10/100;


                            	$charges		=	config('constants.Delivery_charge');
							}
                            if(empty($cart['items'])){
								$charges		=	0;
							}

                                     $cuntoCharges = $subTotal*10/100;
                                               $RestPaidOut = $subTotal*10/100;
                                               $RestPaidOut = $subTotal-$cuntoCharges;

                            $totalAmount	=	$subTotal + $charges + $cuntoCharges;
                            Session::put('total_amount',$totalAmount);
                            Session::put('delivery_charges',$charges);
                        ?>



                    <tr id="charges_tr">
                        <td>Delivery (minimum $3)</td>
                        <td>{{ Config::get('Site.currency')}}<span id="delivery_charges">{{ $charges }}</span></td>
                    </tr>

<tr id="charges_tr">
                        <td>Cunto Charges </td>
                        <td>{{ Config::get('Site.currency')}}<span id="delivery_charges">{{ $cuntoCharges }}</span></td>
                    </tr>

                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong >{{ Config::get('Site.currency')}}<span id="total_charges">{{ $totalAmount }}</span></strong>	</td>
                    </tr>
                </table>
            </div>
        </div>
			<div id="error_msg" style="color:red;"></div>
		@if(isset($displayDeliveryType))
			<div class="delvery-type mt30">
				<div class="row">
				@if((isset($restaurantDetail->delivery) && $restaurantDetail->delivery	==	1 ) || (isset($isDisplayDeliveryButton) && $isDisplayDeliveryButton == 1 ))
					<div class="col-md-6">
						<div class="styled-selector">
							<input onchange="showcharges()" value="{{ config("constants.DELIVERY") }}" type="radio" name="delvery-type" id="delvery-type"  @if($deliveryType == config("constants.DELIVERY")) checked @endif>
							<label for="delvery-type">Delivery</label>
                                                        <label for="delvery-type">Keenid</label>
						</div>
					</div>
				@endif
				@if((isset($restaurantDetail->collection) && $restaurantDetail->collection	==	1 ) || (isset($isDisplayCollectionButton) && $isDisplayCollectionButton == 1 ))
					<div class="col-md-6">
						<div class="styled-selector">
							<input onchange="showcharges()" value="{{ config("constants.COLLECTION") }}" type="radio" name="delvery-type" id="delvery-type2"  @if($deliveryType == config("constants.COLLECTION")) checked @endif>
							
							<label for="delvery-type2">Collection</label>
                                                        <label for="delvery-type2">So qadasho</label>
						</div>
					</div>
				@endif
				</div>
			</div>
		@endif
		@if(isset($checkoutButton))
			<div class="check-out-buttons mt30 @if(empty($cart['items'])) hide @endif" id="check_out_button" >
				<a href="javascript:void(0)"  onclick='return checkout("{{ $subTotal }}")' class="btn btn-default">Checkout / Hore u soco</a>
			</div>
		@endif
		<div class="coupon-box mt30">
             <a class="btn btn-default text-uppercase goback" href="{{ URL::previous()}}">Go Back</a>            
        </div>
    </div>
</div>
