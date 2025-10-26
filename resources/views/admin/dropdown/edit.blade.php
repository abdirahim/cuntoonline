@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{-- HTML::style('css/admin/custom_li_bootstrap.css') --}}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'Edit '.studly_case($type)}} </span>
		<a href="{{URL::to('admin/dropdown-manager/'.$type)}}" class="btn btn-success btn-small align">{{ trans("messages.management.back_to_listing") }}  </a>
	</div>
	@if(count($languages) > 1)
	<div  class="default_language_color">
		{{  Config::get('default_language.message') }}
	</div>
	<div class="wizard-nav wizard-nav-horizontal">
		<ul class="nav nav-tabs">
			<?php $i = 1 ; ?>
			@foreach($languages as $value)
			<li class=" {{ ($i ==  $language_code )?'active':'' }}">
				<a data-toggle="tab" href="#{{ $i }}div">
				{{ $value -> title }}
				</a>
			</li>
			<?php $i++;  ?>
			@endforeach
		</ul>
	</div>
	@endif
	{{ Form::open(['role' => 'form','url' => 'admin/dropdown-manager/edit-dropdown/'.$dropdown->id.'/'.$type,'class' => 'mws-form','files' => true]) }}
	<div class="mws-panel-body no-padding tab-content">
		<?php $i = 1 ; ?>
		@foreach($languages as $value)
		<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
			<div class="mws-form-inline">
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('name', 'Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text("data[$i][name]",isset($multiLanguage[$i]['name'])?$multiLanguage[$i]['name']:'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('name') : '' }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $i++ ; ?>
		@endforeach
		<!-- image section -->
		@if($type=='resource')
		<div class="mws-form-inline">
			<div class="mws-form-row ">
				{{  Form::label('Image', 'Image', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::file(
						'image',
						(input::file('image')!='')?input::file('image'):'',
						['class' => 'small url_text']
						) 
					}}
					@if(File::exists(MASTERS_IMAGE_ROOT_PATH.$dropdown->image)) 
					{{ HTML::image(MASTERS_IMAGE_URL.$dropdown->image, $dropdown->image , array( 'width' => 70, 'height' => 70 )) }}
					@endif
					<div class="error-message help-inline">
						{{ $errors->first('image') }}
					</div>
				</div>
			</div>
		</div>
		@endif
		<!-- image section-->
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/dropdown-manager/edit-dropdown/'.$dropdown->id.'/'.$type)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.management.reset") }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop

