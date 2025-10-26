@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'Edit Setting' }}</span>
		<a href="{{URL::to('admin/settings')}}" class="btn btn-success btn-small align">{{ 'Back To Setting' }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/settings/edit-setting/'.$result->id,'class' => 'mws-form']) }}
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('title', 'Title<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('title', $result->title, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('title') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('key', 'Key<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('key', $result->key_value, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('key') }}
					</div><small>e.g., 'Site.title'</small>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('value', 'Value<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::textarea('value', $result->value, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('value') }}
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{ HTML::decode( Form::label('input_type', 'Input Type<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
				<div class="mws-form-item">
					{{ Form::text('input_type', $result->input_type, ['class' => 'small']) }}
					<div class="error-message help-inline">
						{{ $errors->first('input_type') }}
					</div><small><em><?php echo "e.g., 'text' or 'textarea'";?></em></small>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('editable', 'Editable', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					<div class="input-prepend">
						<span class="add-on"> 
							{{ Form::checkbox('editable',1,($result->editable == 1)?'checked': '' ) }}
						</span>
						<input type="text" size="16" name="prependedInput2" id="prependedInput2" value="<?php echo "Editable"; ?>" disabled="disabled"  class="small">
					</div>
				</div>
			</div>
		</div>
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="Save" class="btn btn-danger">
				
				<a href="{{URL::to('admin/settings/edit-setting/'.$result->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
			</div>
		</div>
		{{ Form::close() }}
	</div>    	
</div>
@stop
