@extends('admin.layouts.default')
@section('content')
<!-- CKeditor start here-->
{{-- HTML::style('css/admin/custom_li_bootstrap.css') --}}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.management.edit_new_cms") }} </span>
		<a href="{{URL::to('admin/cms-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.management.back_to_listing") }} </a>
	</div>
	@if(count($languages) > 1)
	<div  class="default_language_color">
		{{ Config::get('default_language.message') }}
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
	{{ Form::open(['role' => 'form','url' => 'admin/cms-manager/edit-cms/'.$adminCmspage->id,'class' => 'mws-form']) }}
	<div class="mws-panel-body no-padding">
		@if(count($languages) > 1)
		<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
			<b>{{ trans("messages.common_text.same_fields") }}</b>
		</div>
		@endif
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('name', 'Page Name<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('name',$adminCmspage->name, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('name') }}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mws-panel-body no-padding tab-content">
		<?php $i = 1 ; ?>
		@foreach($languages as $value)
		<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
			<div class="mws-form-inline">
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label($i.'.title', 'Page Title<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text("data[$i][title]",isset($multiLanguage[$i]['title'])?$multiLanguage[$i]['title']:'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('title') : '' }}
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label($i.'_body', 'Page Description<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::textarea("data[$i][body]",isset($multiLanguage[$i]['body'])?$multiLanguage[$i]['body']:'', ['class' => 'small','id' => 'body_'.$i]) }}
						<span class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('body') : '' }}
						</span>
					</div>
					<script type="text/javascript">
						/* For CKEDITOR */
							
							CKEDITOR.replace( <?php echo 'body_'.$i; ?>,
							{
								height: 350,
								width: 600,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
								filebrowserImageWindowWidth : '640',
								filebrowserImageWindowHeight : '480',
								enterMode : CKEDITOR.ENTER_BR
							});
								
					</script>
				</div>
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('meta_title', 'Meta Title<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::textarea("data[$i][meta_title]",isset($multiLanguage[$i]['meta_title'])?$multiLanguage[$i]['meta_title']:'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('meta_title') : '' }}
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('meta_description', 'Meta Description<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::textarea("data[$i][meta_description]",isset($multiLanguage[$i]['meta_description'])?$multiLanguage[$i]['meta_description']:'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('meta_description') : '' }}
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('meta_keywords', 'Meta Keywords<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::textarea("data[$i][meta_keywords]",isset($multiLanguage[$i]['meta_keywords'])?$multiLanguage[$i]['meta_keywords']:'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ ($i ==  $language_code ) ? $errors->first('meta_keywords') : '' }}
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $i++ ; ?>
		@endforeach
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/cms-manager/edit-cms/'.$adminCmspage->id)}}" class="btn primary">
				<i class=\"icon-refresh\"></i> {{ trans('messages.management.reset') }}
				</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop

