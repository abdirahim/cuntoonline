@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.settings.add_text") }}</span>
		<a href="{{URL::to('admin/text-setting')}}" class="btn btn-success btn-small align">{{ trans("messages.management.back_to_listing") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' =>'admin/text-setting/save-new-text','class' => 'mws-form']) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('key', 'Key<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					
					<div class="mws-form-item">
						{{ Form::text('key', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('key') }}
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{ HTML::decode( Form::label('value', 'Value<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					
					<div class="mws-form-item">
						{{ Form::text('value', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							{{ $errors->first('value') }}
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.common_text.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/text-setting/add-new-text')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.common_text.reset") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	
	</div>    	
</div>

@stop
