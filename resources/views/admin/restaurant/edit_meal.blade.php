@extends('admin.layouts.default')
@section('content')
{{ HTML::style('css/admin/developer.css') }}
<div class="mws-panel grid_8">
	<div id="overlay" style="display:none;">
		<img src="<?php echo WEBSITE_IMG_URL;  ?>admin/ajax-loader.GIF" class="loading_circle" alt="Loading..." />
	</div>
	<div class="mws-panel-header">
		<span> {{ trans("messages.restaurant.edit_meal") }} </span>
		<a href="{{URL::to('admin/restaurant-manager/add-meal/'.$restroId)}}" class="btn btn-success btn-small align">{{ trans("messages.user_managmt.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/edit-meal/'.$restroId.'/'.$mealDetail->id,'class' => 'mws-form']) }}
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('category', 'Category<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::select('category',array(''=>'Select Category')+$foodCategoryList,$mealDetail->food_category,['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('category') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('name', 'Meal Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('name',$mealDetail->name,['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('name') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('body', 'Description<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::textarea("description",$mealDetail->description, ['class' => 'small','id' => 'body']) }}
					<div class="error-message help-inline">
						{{ $errors->first('description') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row ">
				{{ HTML::decode( Form::label('price', 'Price<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					<div class="InputAddOn">
						<span class="InputAddOn-item">{{ Config::get('Site.currency')}}</span>
						{{ Form::text('price', $mealDetail->price, ['class' => 'small InputAddOn-field']) }}
					</div>
					<div class="error-message help-inline">
						{{ $errors->first('price') }}
					</div>
				</div>
			</div>
		</div>
		<div class="mws-button-row buttons-position">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/restaurant-manager/edit-meal/'.$restroId.'/'.$mealDetail->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
@stop

