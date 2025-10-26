@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/developer.css') }}

<!-- chosen select box css and js start here-->
{{ HTML::style('css/admin/chosen.css') }}
{{ HTML::script('js/admin/chosen.jquery.js') }}
<!-- chosen select box css and js end here-->

<script type="text/javascript">
			
$(function(){
	
	//for cuisine dropdown 
		$(".chzn-select").chosen();
		
	/* For delete user detail */
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete the element ?')) {
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

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/text-setting','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Search by module' }}</label><br/>
				{{ Form::select('module',array(''=>'Select Module')+Config::get('text_search'),((isset($searchVariable['module'])) ? $searchVariable['module'] : ''), ['class' => 'chzn-select']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Key' }}</label><br/>
				{{ Form::text('key_value',((isset($searchVariable['key_value'])) ? $searchVariable['key_value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Value' }}</label><br/>
				{{ Form::text('value',((isset($searchVariable['value'])) ? $searchVariable['value'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/text-setting')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.settings.text_setting") }} 
		</span>
		<a href="{{URL::to('admin/text-setting/add-new-text')}}" class="btn btn-success btn-small align">{{ 'Add Text' }} </a>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="35%">
					{{
						link_to_action(
							'TextSettingController@textList',
							'Key',
							array(
								'sortBy' => 'key_value',
								'order' => ($sortBy == 'key_value' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'key_value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'key_value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th width="35%">
						{{
						link_to_action(
							'TextSettingController@textList',
							'Value',
							array(
								'sortBy' => 'value',
								'order' => ($sortBy == 'value' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'value' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'value' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
						
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
					@foreach($result as $record)
						<tr>
							<td data-th='Key'>{{ $record->key_value }}</td>
							<td data-th='Value'>{{ $record->value }}</td>
							<td data-th='Action'>
								<a href="{{URL::to('admin/text-setting/edit-new-text/'.$record->id)}}" class="btn btn-primary btn-small "  >{{ trans("messages.common_text.edit") }} </a>
								<a href="{{URL::to('admin/text-setting/delete-text/'.$record->id)}}" class="btn btn-danger btn-small " data-delete="delete" >{{ trans("messages.common_text.delete") }} </a>
							</td>
						</tr>
					@endforeach
					@else
						<table class="mws-table mws-datatable details">	
							<tr>
								<td align="center" width="100%" > {{ 'No Records Found' }}</td>
							</tr>	
						</table>
					@endif 
			</tbody>
		</table>
	</div>
		{{ $result->appends($searchVariable)->links() }}
</div>
@stop
