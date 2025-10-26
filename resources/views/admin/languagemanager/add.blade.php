@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.language_manager.add_language") }}</span>
		<a href="{{URL::to('admin/language')}}" class="btn btn-success btn-small align">{{ trans("messages.language_manager.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' =>'admin/language/save-language','class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('Title', 'Title', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('title', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('title') }}
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{  Form::label('Order', 'Language Code', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('languagecode', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('languagecode') }}
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{  Form::label('Order', 'Folder Code', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('foldercode', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('foldercode') }}
						</div>
					</div>
				</div>
				
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.language_manager.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/language/add-language')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.language_manager.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

@stop
