@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/developer.css') }}

<script type="text/javascript">
$(function(){
	/* For delete user detail */
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete the meal ?')) {
			// Get the route URL
			var url = $(this).prop('href');
			// Get the token
			var token = $(this).data('delete');
			// Create a form element
			var $form = $('<form/>', {action: url, method: 'post'});
			// Add the DELETE hidden input method
			var $inputMethod = $('<input/>', {type: 'hidden', name: '_method', value: 'delete'});
			// Add the token hidden input
			var $inputToken = $('<input/>', {type: 'hidden', name: '_token', value: token});
			// Append the inputs to the form, hide the form, append the form to the <body>, SUBMIT !
			$form.append($inputMethod, $inputToken).hide().appendTo('body').submit();
		} 
	});
});
</script>
 <!-- Searching div -->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/add-meal/'.$restroId,'class' => 'mws-form']) }}
		{!! csrf_field() !!}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ trans("Meal Name") }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="{{ trans('messages.user_managmt.search') }}" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/restaurant-manager/add-meal/'.$restroId)}}"  class="btn btn-default btn-small">{{ trans("messages.user_managmt.reset") }}</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!-- End here -->

<div class="mws-panel grid_8">	
	<div id="overlay" style="display:none;">
		<img src="<?php echo config("constants.WEBSITE_IMG_URL");  ?>admin/ajax-loader.GIF" class="loading_circle" alt="Loading..." />
	</div>			
	<div class="mws-panel-header"  style="height: 25px;">
		<span> {{ trans("Add Meal") }} </span>
		<a href="{{URL::to('admin/restaurant-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.user_managmt.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/add-meal/'.$restroId,'class' => 'mws-form']) }}
			{{ Form::hidden('add-meal') }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
				{!!   HTML::decode( Form::label('category', 'Category<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::select('category',array(''=>'Select Category')+$foodCategoryList->toArray(),'',['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('category'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
				{!! HTML::decode( Form::label('name', 'Meal Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::text('name','',['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('name'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{!!  HTML::decode( Form::label('description', 'Description<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						{{ Form::textarea("description",'', ['class' => 'small','id' => 'body']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('description'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{!!  HTML::decode( Form::label('price', 'Price<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) !!}
					<div class="mws-form-item">
						<div class="InputAddOn">
							 <span class="InputAddOn-item">$</span>
							{{ Form::text('price', null, ['class' => 'small InputAddOn-field']) }}
						</div>
						<div class="error-message help-inline">
								<?php echo $errors->first('price'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row buttons-position">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/restaurant-manager/add-meal/'.$restroId)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>

<div class="mws-panel grid_8">
	<div id="overlay" style="display:none;">
		<img src="<?php echo config("constants.WEBSITE_IMG_URL");  ?>admin/ajax-loader.GIF" class="loading_circle" alt="Loading..." />
	</div>
	<div class="mws-panel-header"  style="height: 25px;">
		<span> {{ trans("Import Meal") }} </span>
	</div>
	<div class="mws-form-message info">
		<a href="javascript:void(0);" class="close pull-right">×</a>
		<ul style="padding-left:12px">
			<li>Allowed file type is csv (file must contain name,category,price and description colum.)</li>
			<li>
			@if(!empty($foodCategoryList))
				Please add one of these (@foreach($foodCategoryList as $key=> $value) {{ $key  }} @if($value != end($foodCategoryList) ), @endif @endforeach) in Category field .
				@foreach($foodCategoryList as $key=> $value)
					{{ $key }} = {{ $value }} @if( $value != end($foodCategoryList) ), @endif
				@endforeach
			@endif
			</li>
			<li>Large files may take some time to upload so please be patient and do not hit reload or your back button</li>
		</ul>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/import-meal/'.$restroId,'class' => 'mws-form','files'=>'true']) }}
			{{ Form::hidden('add-meal') }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
				{{ HTML::decode( Form::label('file', 'Import File<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::file('file') }}
						<span>To Download Sample File <a href="{{config("constants.RESTAURANT_IMAGE_URL").'sample_meal.csv'}}">Click here</a></span>
						<div class="error-message help-inline">
							<?php echo $errors->first('file'); ?>
						</div>
					</div>
				</div>

			<div class="mws-button-row buttons-position">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/restaurant-manager/add-meal/'.$restroId)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("Meals Listing") }}
		</span>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="25%">{{ 'Meal Name' }}
					</th>
					<th width="30%">{{ 'Description'}}
					</th>
					<th width="15%">{{ 'Price' }}
					</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				<?php

				if(!$result->isEmpty()){

				foreach($result as $key => $record){

				?>
				<tr>
					<td data-th='name'>{{ $record->name }}</td>

					<td data-th='Description'>{{ strip_tags(Str::limit($record->description , 120)) }}</td>
					<td data-th='Price'><?php $total_price	=	App\Libraries\CustomHelper::numberFormat($record->price)?>
									{{ Config::get('Site.currency')}} {{ $total_price }}</td>

					<td data-th=Action>

						@if($record->is_active)
							<a href="{{URL::to('admin/restaurant-manager/update-meal-status/'.$record->id.'/0')}}" class="btn btn-success btn-small ">{{ trans("messages.user_managmt.mark_as_inactive") }} </a>
						@else
							<a href="{{URL::to('admin/restaurant-manager/update-meal-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small">{{ trans("messages.user_managmt.mark_as_active")  }} </a>
						@endif

						<a href="{{URL::to('admin/restaurant-manager/edit-meal/'.$restroId.'/'.$record->id)}}" class="btn btn-primary btn-small">{{ trans("messages.user_managmt.edit") }}</a>

						<a href="{{ URL::to('admin/restaurant-manager/delete-meal/'.$record->id) }}" data-delete="delete" class="btn btn-danger btn-small">{{ trans("messages.user_managmt.delete") }} </a>

					</td>

				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php }else{ ?>
		<table class="mws-table mws-datatable details">
			<tr>
				<td align="center" width="100%"> {{'No Records Found'}}</td>
			</tr>
			<?php  } ?>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
@stop
