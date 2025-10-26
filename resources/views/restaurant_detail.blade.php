@extends('layouts.default')
@section('content')
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-8" id="content">
				<div class="details-hold">
					<div class="page-title text-center">
						<h2>we deliver amazing food to your door</h2>
					</div>
					<div class="restaurant-details mt30">
						<div class="restaurant-name">
							<h2>{{ $restaurantDetail->name }}</h2>
						</div>
						<div class="cuisine-info">
							@if($restaurantDetail->collection)
								<div><span><img src="{{ config("constants.WEBSITE_IMG_URL") }}spoon-icon.png" alt="spoon">Collection / So qadasho</span></div>
							@endif
							@if($restaurantDetail->delivery)
								<div><span><img src="{{ config("constants.WEBSITE_IMG_URL") }}map-icon.png" alt="map">Delivery / Keenid</span></div>
							@endif

							<span class="pull-right">
								<ul class="rating">
									<li class="review_rating" data-rating="{{ $avgRating}}"></li>
								</ul>
							{{ count($review_list)}}  {{(count($review_list)>1)?'reviews':'review'}}
							</span>
						</div>
						<div class="clearfix"></div>
						<div class="restaurant-box">
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<div class="restaurant-image-box">
										@if (file_exists( public_path().'/uploads/restaurant_img/'.$restaurantDetail->image) && $restaurantDetail->image !='')
											<img src="{{asset('uploads/restaurant_img/'.$restaurantDetail->image)}}" alt="restaurant-image" />
									@endif
									</div>
								</div>

								<div class="col-md-7 col-sm-12">
									<div class="restaurant-description-hold">
										<h2>About the Restaurant</h2>
										<div class="restaurant-description">
											{{ $restaurantDetail->description }}

										</div>
										<div class="restaurant-button text-right">

										@if(!empty($editDetailArray))

											<a class="btn btn-default opening-button" data-container="body" data-toggle="popover" data-placement="top" data-animation="true" data-html="true"
											data-content="<div>
												<table>
													<tbody>
														<tr>
															<td><strong> Mon :    </strong></td>
															<td>@if(isset($editDetailArray['Monday'])) {{ $editDetailArray['Monday']->open_time}} - {{ $editDetailArray['Monday']->close_time}}  @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Tue : </strong></td>
															<td>@if(isset($editDetailArray['Tuesday'])) {{ $editDetailArray['Tuesday']->open_time}} - {{ $editDetailArray['Tuesday']->close_time}} @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Wed : </strong></td>
															<td>@if(isset($editDetailArray['Wednesday'])) {{ $editDetailArray['Wednesday']->open_time}} - {{ $editDetailArray['Wednesday']->close_time}} @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Thurs : </strong></td>
															<td>@if(isset($editDetailArray['Thursday'])) {{ $editDetailArray['Thursday']->open_time}} - {{ $editDetailArray['Thursday']->close_time}} @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Fri : </strong></td>
															<td>@if(isset($editDetailArray['Friday'])) {{ $editDetailArray['Friday']->open_time}} - {{ $editDetailArray['Friday']->close_time}} @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Sat : </strong></td>
															<td>@if(isset($editDetailArray['Saturday'])) {{ $editDetailArray['Saturday']->open_time}} - {{ $editDetailArray['Saturday']->close_time}} @else Close @endif</td>
														</tr>
														<tr>
															<td><strong>Sun : </strong></td>
															<td>@if(isset($editDetailArray['Sunday'])) {{ $editDetailArray['Sunday']->open_time}} - {{ $editDetailArray['Sunday']->close_time}} @else Close @endif</td>
														</tr>
													</tbody>
												</table>
											</div>">
												Opening Times
											</a>
										@endif

											<a href="{{ URL::previous() }}" class="btn btn-default back-button"	>Go Back</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="restaurant-menu mt30">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php

								?>
								@if(!empty($foodCategories))
								<?php $i=0;?>
								@foreach($foodCategories as $key=> $foodCategorie)
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingStarters">
										<h4 class="panel-title">
											<a role="button" @if($i!=0) class="collapsed" @endif data-toggle="collapse" data-parent="#accordion" href="#starter{{ $key}}" aria-expanded="true" aria-controls="starter">
											{{ $foodCategorie }}
											</a>
										</h4>
									</div>
									<div id="starter{{ $key}}" class="panel-collapse collapse @if($i==0) in @endif" role="tabpanel" aria-labelledby="headingStarters">
										<div class="panel-body">
											@if(!empty($restaurantDetail->meals))
											@foreach($restaurantDetail->meals as $key2=> $meal)
												@if($meal->food_category==$key)
													<div class="restaurant-menu-item">
														<div class="row">
															<div class="col-md-6">
																<div class="menu-des">
																	<div class="equal-height">
																		<h3>{{ $meal->name }}</h3>
																		<p>{{ $meal->description }}</p>
																	</div>
																	<div class="collapse" id="collapseExample{{ $key2 }}">
																		<div class="order-instruction-box well">
																			<form>
																				<label>Tailor this dish to your specific taste</label>
																				<textarea id="specific_ins{{$meal->id}}" class="form-control" name="order-instruction-box"></textarea>
																			</form>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-3 col-sm-6 col-xs-6">
																<div class="menu-price">
																	<div class="equal-height text-center ">
																		<strong>{{ Config::get('Site.currency')}}{{ $meal->price }}</strong>
																	</div>
																</div>
															</div>
															<div class="col-md-3 col-sm-6 col-xs-6">
																<div class="restaurant-menu-action">
																	<div class="equal-height text-center ">
																		<div class="restaurant-menu-buttons">
																			<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample{{ $key2 }}" aria-expanded="false" aria-controls="collapseExample">
																			<img src="{{ config("constants.WEBSITE_IMG_URL") }}instructions-icon.png" alt="instruction"> Specific instructions <br>Faahfaahin Dheerad</a>
																			<a data-type="new" data-food-category="{{ $foodCategorie }}" data-meal="{{$meal->id}}" onclick='addcart(this)' class="btn btn-default">ADD / DALBO</a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												@endif
											@endforeach
											@endif
										</div>
									</div>
								</div>
								<?php $i++;?>
								@endforeach
								@endif
							</div>
						</div>
					</div>

			<!--  review  list  start here-->
			<?php $start=1; ?>
				@if(!empty($review_list))
					<div class="review-hold" id="for_comment">
						<div class="page-title text-center">
							<h2>Reviews on {{ $restaurantDetail->name }}  <span>{{ count($review_list)}} Reviews</span></h2>
						</div>
						<div class="row">
							@foreach($review_list as $review)
								@if($start == 6)
									<div id="more_comment" style="display:none">
								@endif
										<div class="col-sm-12">
											<div class="comment_box">
												<div class="comment-head">
													<strong>
													@if(Auth::check())
														@if($review->user_id == Auth::user()->id)
															{{ 'You' }}
														@else
															{{ $review->name }}
														@endif
													@else
														{{ $review->name }}
													@endif
													</strong>
													<div class="comment-head-right">
														 <span>{{ date(Config::get("Reading.date_format") , strtotime($review->created_at)) }}</span>
														@if(!empty($review->rating))
															<ul class="rating">
																<li class="review_rating" data-rating="{{ $review->rating}}"></li>
															</ul>
														@endif
													</div>

												</div>
												 <div class="comment-text">
													<p>
														{{ $review->comment }}
													</p>
												 </div>
											</div>
										</div>
									<?php $start++; ?>
							@endforeach
							@if($start >6)
								</div>
								<div class="showmore text-center">
									<a href="javascript:void(0)" id="seeMoreComment" class="open_arw btn btn-default" onclick="toggleSeeMoreComment()">{{ trans("messages.Show all comments") }}</a>
								</div>
								<div class="clearfix pt10">&nbsp;</div>
							@endif
						</div>
					</div>
				@endif
				<!--  review  list  end here-->

				<!--if user  is not  login then login button  will be  display-->
				@if(!Auth::check())
					<div class="row">
						<div class="col-sm-12 text-center">
							<div class="comment_box_notice">
								{{ trans("messages.To submit Review Please") }} <a href="{{ URL::to('login?redirect=1')}}" class="btn btn-default"> {{ trans("messages.Login") }}</a>
							</div>
						</div>
					</div>
				@endif
				<!--if user  is not  login end here -->

				<!--if  user  is  login then  commment   box  display-->
				@if(Auth::check())
					@if(Auth::user()->id!=config("constants.ADMIN_ID"))
						{{ Form::open(['role' => 'form','url'=>'restaurant/add-review/'.$restaurantDetail->id]) }}
						<div class="comment comment_box" id="commentform">
						<div class="page-title text-center">
								<h2>Submit Your Reviews</h2>
							</div>

							<div class="row">
							<!--div class="col-sm-3">
									<label for="rating">Ratings</label>
								</div-->
									@if($check_rating_exist == 0)
										<div class="col-sm-7">
											<label>Select Your Rating</label>
											<div class="rating">
												<div id="precision" class="rat"></div>
											</div>
										</div>

									@endif
								<div class="error-message help-inline text-center">
									<?php echo $errors->first('rating'); ?>
								</div>
							</div>
							<div class="row">
									<!--div class="col-sm-3">
										<label for="textfield2">{{ trans("messages.Comments") }}</label>
									</div-->
									<div class="col-sm-12">
										{{ Form::textarea("comment",'', ['id' => 'comment','class' => 'form-control','placeholder' => 'Enter your review','required'=>'required']) }}
										<div class="error-message help-inline">
											<?php echo $errors->first('comment'); ?>
										</div>
									</div>
							</div>
							<div class="row">
								<div class="col-sm-12 text-right">
									<button type="submit" class="btn  btn-default">{{ trans("messages.Submit") }}</button>
								</div>
							</div>
							<div class="clearfix pt20"></div>
						</div>
						{{ Form::close() }}
					@endif
				@endif
				<!-- comment box end here-->
				</div>
			</div>
			<div class="col-md-3 col-sm-4" id="product_cart">
				@include('elements.cart')
			</div>
		</div>
	</div>
</div>



{{ HTML::script('js/jquery.raty.js') }}
{{ HTML::style('css/jquery.raty.css')}}


<script type="text/javascript">

	// for open popover on opening time button
	$(function () {
		setDeliveryType();
		$('[data-toggle="popover"]').popover();
		//sideBar();
	})

	// if any restaurant do not have meal than set message
	$(document).ready(function(){

		console.log('readyyyyyy');

		$('.panel-body').each(function(i,e) {
			if ($(e).html().trim() == "")
				($(e).html('<div class="restaurant-menu-item"><div class="no-record mt30"><?php echo trans("messages.meal_not_available"); ?></div></div>'));
		});

	})

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
				'displayDeliveryType': true,
				'checkoutButton': true,
				'deliver_button':@if($restaurantDetail->delivery) 1 @else 0 @endif,
				'collection_button':@if($restaurantDetail->collection) 1 @else 0 @endif,
				"_token": "{{ csrf_token() }}",



			},
			type: "POST",
			beforeSend:function(){
				$("#overlay").show();
			},
			success: function(r){

				$("#product_cart").html(r.htmlcart);
				$('.order-sidebar').stickySidebar({
					sidebarTopMargin: 20,
					footerThreshold: 100
				});
				if(r.countCart<1){
					$("#check_out_button").addClass('hide');
				}else{
					$("#check_out_button").removeClass('hide');
				}
				$("#overlay").hide();
			}
		});
	}

	/**
	 * function for add delivery charge and remove on onclick radio button
	 * @param null
	 * @return null
	 */
	function showcharges(){
		var delivery_type;
		if($("#delvery-type").is(":checked")){
			var preVal =	$("#total_charges").html();
			if(preVal!=0){
				$("#delivery_charges").html("<?php echo config("constants.Delivery_charge"); ?>");
				var finalPrice	=	parseInt(preVal)+<?php echo config("constants.Delivery_charge"); ?>;
				$("#total_charges").html(finalPrice);
			}
			delivery_type	=	{{ config("constants.DELIVERY") }}
		}else

		{
			$("#delivery_charges").html(0);
			var preVal 		=	$("#total_charges").html();
			if(preVal!=0){
				var finalPrice	=	preVal-<?php echo config("constants.Delivery_charge"); ?>;
				$("#total_charges").html(finalPrice);
			}
			delivery_type	=	{{ config("constants.COLLECTION") }}
		}
		$.ajax({
			url: "<?php echo URL::to('update-delivery-type');?>",
			data: {'deliver_type':delivery_type, "_token": "{{ csrf_token() }}"},
			type: "POST",
			beforeSend:function(){
				$("#overlay").show();
			},
			success: function(r){
				$("#overlay").hide();
			}
		});
	}

	/**
	 * function for set delivery type on page load (in session)
	 * @param null
	 * @return null
	 */

	function setDeliveryType(){
		var delivery_type;
		if($("#delvery-type").is(":checked")){
			delivery_type	=	{{ config("constants.DELIVERY")  }}
		}else{
			delivery_type	=	{{ config("constants.COLLECTION") }}
		}
		$.ajax({
			url: "<?php echo URL::to('update-delivery-type');?>",
			data: {'deliver_type':delivery_type},
			type: "POST",
			beforeSend:function(){
				$("#overlay").show();
			},
			success: function(r){
				$("#overlay").hide();
			}
		});
	}

	/**
	 * function for redirect page base on shipping select
	 * @param null
	 * @return null
	 */
	function checkout(subtotal){
		if(subtotal<5){
			$('#error_msg').html("The minimim order for this restaurant is {{ Config::get('Site.currency')}}5.");
			alert("The minimim order for this restaurant is {{ Config::get('Site.currency')}}5.");
			return false;
		}
		if($("#delvery-type").is(":checked")){
			window.location.href = "{{ URL::to('deliver-detail')}}";
		}else{
			window.location.href = "{{ URL::to('payment')}}";
		}
	}

	/* For toggle show more/hide more link for comment section */
	function toggleSeeMoreComment() {
		if(document.getElementById("more_comment").style.display == 'none') {
			$('#seeMoreComment').addClass('close_arw');
			document.getElementById("more_comment").style.display = 'block';
			document.getElementById("seeMoreComment").innerHTML = '<?php echo trans("messages.Show less comments"); ?>';
		}
		else {
			$('#seeMoreComment').removeClass('close_arw');
			document.getElementById("more_comment").style.display = 'none';
			document.getElementById("seeMoreComment").innerHTML = '<?php echo trans("messages.Show all comments"); ?>';
		}

	}
</script>

{{ HTML::script('js/stickySidebar.js') }}

<script>
	$(document).ready(function() {

		$('.order-sidebar').stickySidebar({
			sidebarTopMargin: 20,
			footerThreshold: 100
		});
	});

	/** for review rating   */
	$('.review_rating').raty({
		readOnly	: true,
		score		: function() {
			return $(this).attr('data-rating');
		},
		path  		: '<?php echo config("constants.WEBSITE_URL") ;?>img',
		numberMax 	: 5,
		half		: true,
	});

	$('#precision').raty({
		path       : '<?php echo config("constants.WEBSITE_URL") ;?>img',
		targetKeep : true,
		precision  : false,
		//hints	   : ['1', '2', '3', '4', '5'],
		half       : false,
	});


</script>

@stop
