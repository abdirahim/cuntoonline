@extends('layouts.default')
@section('content')

    <!-- js for equal height of the div  -->
    {{ HTML::script('js/jquery.matchHeight-min.js') }}

    <!-- js for rating  -->
    {{ HTML::script('js/jquery.raty.js') }}

    <script>
        /* for equal height of the div */
        $(function() {
            $('.equal-height').matchHeight();

            /** for review rating   */
            $('.review_rating').raty({
                readOnly	: true,
                score		: function() {
                    return $(this).attr('data-rating');
                },
                path  		: '<?php echo config("constants.WEBSITE_URL");?>img',
                numberMax 	: 5,
                half		: true,
            });

            /* for share location  */
            <?php if(!Session::has('location')){ ?>
            getLocation();
            <?php } ?>

        });

        /* for get location  */
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        /* for show	position*/
        function showPosition(position) {
            if(position){
                $.ajax({
                    url : '<?php echo URL::to("setLatLong"); ?>',
                    method: 'POST',
                    data : {'lat' : position.coords.latitude, 'lng' : position.coords.longitude},
                    success : function(r){
                        window.location.reload(true);
                    }

                });
            }
        }

    </script>

<!--    --><?php //$name	=	Input::get('name');

$name = request('name');


?>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-3">
                    <div class="sidebar">
                        <div class="search-widget">
                            <form action="">
                                <div class="search-form">
                                    <h3>Search Restaurants</h3>
                                    <input type="text" name="name" value="{{ $name }}" class="form-control" placeholder="Search Restaurants">
                                    <button type="submit" class="btn btn-default">Search</button>
                                </div>
                            </form>
                        </div>
                        <div class="cuisine-list mt30">
                            <h3><span class="hidden-xs">By Cuisine</span>
                                <a href="javascript:void(0)" class="visible-xs cusine-dropdownlink">Browse By Cuisine</a>
                            </h3>
                            <ul>
                                <li @if($categoryId=='all') class="active" @endif ><a href='{{ URL::to("/")}}'> All Cuisine</a></li>
                                {{--							@if(!empty($cuisines))--}}
                                {{--								@foreach($cuisines as $key=> $cuisine)--}}
                                {{--								<li @if($categoryId==$cuisine->id) class="active" @endif ><a href='{{ URL::to("cuisine/$cuisine->slug")}}'>{{ $cuisine->name }} ({{$cuisine->count}})</a></li>--}}
                                {{--								@endforeach--}}
                                {{--							@endif--}}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-sm-8">
                    <div class="content-right">
                        <h2>Open for orders now /  Hudheelada diyaar u ah dalabkaaga</h2>
                        <div class="clearfix"></div>
                        <div class="cuisine-list">
                            <div class="row">
                                @if(!$restaurants->isEmpty())

{{--                                    @foreach($restaurants as $restaurant)--}}
{{--                                        @if(!empty($restaurant->restaurantTime->week_day))--}}
{{--                                        {{$restaurant->restaurantTime->week_day}}--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}


                                    @foreach($restaurants as $key=> $restaurant)

                                        <div class="col-sm-6 col-md-6 col-lg-4 col-xs-6 ">
                                            <div class="cuisine-box animate-plus equal-height" data-animations="fadeIn" data-animation-when-visible="true" data-animation-reset-offscreen="true">
                                                <div class="cuisine-top">
                                                    <h4>{{ $restaurant->name}}</h4>
                                                    <span class="cuisine-type">
											<img src="{{ config("constants.WEBSITE_IMG_URL") }}spoon-icon.png" alt="spoon">
											 <?php
                                                        $cuisineArray	=	explode(',',$restaurant->cuisine);
                                                      //  $cuisine		=	CustomHelper::getCuisineName($cuisineArray);
                                                       // echo implode(', ',$cuisine);
                                                        ?>
											</span>
                                                </div>
                                                <div class="cuisine-img">
                                                    <figure>

{{--                                                        @if (File::exists(RESTAURANT_IMAGE_ROOT_PATH.$restaurant->image) && $restaurant->image !='')--}}
                                                  @if (file_exists( public_path().'/uploads/restaurant_img/'.$restaurant->image) && $restaurant->image !='')
                                                            <a href='{{ URL::to("restaurant-detail/$restaurant->slug")}}'>
                                                                <img src="{{asset('uploads/restaurant_img/'.$restaurant->image)}}" alt="restaurant-image" />
                                                            </a>
                                                            @else
                                                            {{ 'dont exist' }}
                                                        @endif
                                                    </figure>
                                                    @if($restaurant->is_recommended)
                                                        <div class="recomend-text">Recommended</div>
                                                    @endif
                                                </div>
                                                <div class="cuisine-bottom">
                                                    <div class="cuisine-info">
                                                        @if($restaurant->collection)
                                                            <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}right-check.png" alt="right-check">Collection</div>
                                                        @endif
                                                        @if($restaurant->delivery)
                                                            <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}right-check.png" alt="right-check">Delivery</div>
                                                        @endif
                                                        @if(isset($restaurant->distance))
                                                            <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}map-icon.png" alt="map">{{ round($restaurant->distance, 2) }} Miles</div>
                                                        @endif
                                                    </div>
                                                    <div class="cuisine-review">
                                                        <ul class="rating">
                                                            <li class="review_rating" data-rating="@if($restaurant->avg_rating){{ ($restaurant->avgRating()->avg('rating'))}} @else 0 @endif"></li>
                                                        </ul>
                                                        <span>@if($restaurant->review_restaurant){{ ($restaurant->review_restaurant->count())}} {{($restaurant->review_restaurant->count()>1)?'reviews':'review'}}  @endif  </span>
                                                    </div>
{{--                                                    @if($restaurant->restaurant_time)--}}
                                                    @if(!empty($restaurant->restaurantTime->week_day))

                                                        <?php
//                                                        $openTime 		=	strtotime($restaurant->restaurant_time->open_time);
//                                                        $closeTime 		=	strtotime($restaurant->restaurant_time->close_time);
                                                        $openTime 		=	strtotime($restaurant->restaurantTime->open_time);
                                                        $closeTime 		=	strtotime($restaurant->restaurantTime->close_time);
                                                        $currentTime	=	time();
                                                        if($openTime > $closeTime){
                                                            // $closeTime	=	'   '.$closeTime+(24*3600);
                                                            $closeTime	=	$closeTime + (24*3600);
                                                        }

                                                        ?>
                                                        @if(($currentTime > $openTime) && ($currentTime < $closeTime))
                                                            <div class="cuisine-info availability">
                                                                <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}clock-icon.png" alt="clock-icon" title="clock-icon">
                                                                    {{ trans("messages.now_open")}}
                                                                </div>
                                                            </div>

                                                        @elseif(($currentTime < $openTime)  )
                                                            <div class="cuisine-info availability">
                                                                <div>
                                                                    <img src="{{ config("constants.WEBSITE_IMG_URL") }}clock-icon.png" alt="clock-icon" title="clock-icon">
                                                                    {{ trans("messages.opening")}} {{ date('g:i A',$openTime)}}
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="cuisine-info availability">
                                                                <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}clock-icon.png" alt="clock-icon" title="clock-icon">
                                                                    {{ trans("messages.now_close")}}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="cuisine-info availability">
                                                            <div><img src="{{ config("constants.WEBSITE_IMG_URL") }}clock-icon.png" alt="clock-icon" title="clock-icon">
                                                                {{ trans("messages.closed_today")}}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="view-link">
                                                        <a href='{{ URL::to("restaurant-detail/$restaurant->slug")}}' class="btn btn-default">View Menu</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="mt50 no-record">
                                        @if($name)
                                            {{ trans("messages.no_record_after_search") }}
                                        @elseif($categoryId && $categoryId != 'all')
                                            {{ trans("messages.no_record_in_cuisine") }}
                                        @else
                                            {{ trans("messages.no_records_front") }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
