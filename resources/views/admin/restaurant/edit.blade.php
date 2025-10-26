@extends('admin.layouts.default')

@section('content')

{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
{{ HTML::style('css/admin/developer.css') }}

<!-- chosen select box css and js start here-->
{{ HTML::style('css/admin/chosen.css') }}
{{ HTML::script('js/admin/chosen.jquery.js') }}
<!-- chosen select box css and js end here-->

<div class="mws-panel grid_8">
	
	<div class="mws-panel-header">
		<span> {{ trans("messages.restaurant.edit_restaurant") }}</span>
		<a href="{{URL::to('admin/restaurant-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.user_managmt.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/update-restaurant/'.$restroDetails->id,'class' => 'mws-form','files'=>'true']) }}
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('username', 'Username<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('username',$restroDetails->username,['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('username') }}
					</div>
				</div>
			</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('password', 'Password<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::password('password',['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('password') }}
						</div>
					</div>
				</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('cuisine', 'Cuisine<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					<?php $cuisineArray	=explode(',',$restroDetails->cuisine); ?>
					{{ Form::select('cuisine[]',$cuisineList,$cuisineArray,['class' => 'small chzn-select','multiple'=>'multiple','data-placeholder'=>'Please select cuisine']) }}
					<div class="error-message help-inline">
						{{ $errors->first('cuisine') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('food_menu', 'Food Menu<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					<?php $menuArray	=explode(',',$restroDetails->food_menu); ?>
					{{ Form::select('food_menu[]',$foodCategories,$menuArray,['class' => 'small chzn-select','multiple'=>'multiple','data-placeholder'=>'Please select food menus.']) }}
					<div class="error-message help-inline">
						{{ $errors->first('food_menu') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('name', 'Restaurant Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('name',$restroDetails->name,['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('name') }}
					</div>
				</div>
			</div>
			
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('email', 'Email<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::email('email',$restroDetails->email,['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('email') }}
					</div>
				</div>
			</div>
			
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('description', 'Description<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::textarea("description",$restroDetails->description, ['class' => 'small','id' => 'body']) }}
					<span class="error-message help-inline">
					{{ $errors->first('description') }}
					</span>
				</div>
				<script type="text/javascript">
					/* For CKEDITOR */
					
						CKEDITOR.replace( 'description',
						{
							height: 200,
							width: 600,
							filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
							filebrowserImageWindowWidth : '640',
							filebrowserImageWindowHeight : '480',
							enterMode : CKEDITOR.ENTER_BR
						});
								
				</script>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('image', 'Image<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::file('image',['class' => 'small']) }}
					<br />
					<?php 
						$image	=	Input::old('image');
						$image	=	isset($image) ? $image : $restroDetails->image;
					?>
					@if($image && File::exists(config("constants.ESTAURANT_IMAGE_ROOT_PATH").$restroDetails->image))
						{{ HTML::image( config("constants.RESTAURANT_IMAGE_URL").$restroDetails->image, $restroDetails->image , array( 'width' => 70, 'height' => 70 )) }}
					@endif
					<div class="error-message help-inline">
						{{ $errors->first('image') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('delivery', 'Delivery<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::checkbox('delivery',1,($restroDetails->delivery ==1) ? 'checked' : '') }}
					<div class="error-message help-inline">
						{{ $errors->first('delivery') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('collection', 'Collection<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::checkbox('collection',1,($restroDetails->collection ==1) ? 'checked' : '') }}
					<div class="error-message help-inline">
						{{ $errors->first('collection') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('location', 'Location<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::radio('location', 'current_location', false, array('onclick' => 'getLocation()'),['id'=>'current']) }}  Choose current location
					&nbsp;
					&nbsp;
					&nbsp;
					{{ Form::radio('location', 'manual_location', true, array('onclick' => 'showmap()'),['id'=>'manual']) }}  I want to enter manually
					{{ Form::hidden('lat', $restroDetails->lat, array('id' => 'location_lat')) }}
					{{ Form::hidden('long', $restroDetails->lang, array('id' => 'location_long')) }}
					<div class="error-message help-inline" id="location-error">
						{{ $errors->first('long') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row" id="location-map">
				<div class="mws-form-item">
					<input id="pac-input" class="controls" type="text" placeholder="Enter a location">
					<div id="map" style="height:400px"></div>
				</div>
			</div>
		</div>
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/restaurant-manager/edit-restaurant/'.$restroDetails->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		
		//for cuisine dropdown 
		$(".chzn-select").chosen();
		// for check radio button value 
		var radioValue	= $("input[type=radio][name='location']:checked").val()
		if(radioValue == 'current_location'){
			$('#location-map').hide();
			getLocation();
		};
	});
	// for show map
	function showmap(){
		$('#location-map').show();
	}
	
	var x = document.getElementById("location-error");
	// for get current location 
	function getLocation() {
		$('#location-map').hide();
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition);
	    } else { 
	        x.innerHTML = "Geolocation is not supported by this browser.";
	    }
	}
	
	function showPosition(position) {
		$('#location_lat').val(position.coords.latitude);
		$('#location_long').val(position.coords.longitude);
	}
	
	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			center: {lat: 2.0333, lng: 45.3500},
			zoom: 7,
			draggable:true
		});
		var input = /** @type {!HTMLInputElement} */(
	    document.getElementById('pac-input'));
	
		var types = document.getElementById('type-selector');
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
	
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);
	
		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({
			map: map,
			anchorPoint: new google.maps.Point(0, -29),
			draggable:true
		});
	
		autocomplete.addListener('place_changed', function() {
			infowindow.close();
			marker.setVisible(false);
			var place = autocomplete.getPlace();
			
			$('#location_lat').val(place.geometry.location.lat());
			$('#location_long').val(place.geometry.location.lng());
		
			
			// console.log(place);
			if (!place.geometry) {
				window.alert("Autocomplete's returned place contains no geometry");
				return;
			}
	
			// If the place has a geometry, then present it on a map.
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);  // Why 17? Because it looks good.
			}
			marker.setIcon(/** @type {google.maps.Icon} */({
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(35, 35)
			}));
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);
	
			var address = '';
			if (place.address_components) {
				address = [
					(place.address_components[0] && place.address_components[0].short_name || ''),
					(place.address_components[1] && place.address_components[1].short_name || ''),
					(place.address_components[2] && place.address_components[2].short_name || '')
				].join(' ');
			}
	
			infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
			infowindow.open(map, marker);
		});
		
		google.maps.event.addListener(marker, "dragend", function(event) {
	
			var point = marker.getPosition();
			$('#location_lat').val(point.lat());
			$('#location_long').val(point.lng());
	
		});
			
	  // Sets a listener on a radio button to change the filter type on Places
	  // Autocomplete.
		function setupClickListener(id, types) {
			var radioButton = document.getElementById(id);
		
		}
		setupClickListener('changetype-all', []);
		setupClickListener('changetype-address', ['address']);
		setupClickListener('changetype-establishment', ['establishment']);
		setupClickListener('changetype-geocode', ['geocode']);
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap"></script>

@stop
