@extends('admin.layouts.default')
@section('content')

<script type="text/javascript">
	$(function(){
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
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/language','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Title' }}</label><br/>
				{{ Form::text('title',((isset($searchVariable['title'])) ? $searchVariable['title'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul>
				<li class="clearfix">
					<span></span> 
					<div id="mws-textglow-op"></div>
				</li>
			</ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/language')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.language_manager.language_list") }}
		</span>
		<a href="{{URL::to('admin/language/add-language')}}" class="btn btn-success btn-small align">{{ trans("messages.language_manager.add_language") }} </a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="20%">
						{{
						link_to_action(
						'LanguageController@listLanguage',
						'Title',
						array(
						'sortBy' => 'title',
						'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc'
						),
						array('class' => (($sortBy == 'title' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
						}}
					</th>
					<th width="20%">
						{{
						link_to_action(
						'LanguageController@listLanguage',
						'Folder Code',
						array(
						'sortBy' => 'folder_code',
						'order' => ($sortBy == 'folder_code' && $order == 'desc') ? 'asc' : 'desc'
						),
						array('class' => (($sortBy == 'folder_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'folder_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
						}}
					</th>
					<th width="20%">
						{{
							link_to_action(
								'LanguageController@listLanguage',
								'Language Code',
								array(
								'sortBy' => 'lang_code',
								'order' => ($sortBy == 'lang_code' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'lang_code' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'lang_code' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
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
						<td data-th='Title'>{{ $record->title }}</td>
						<td data-th='Folde Code'>{{ $record->folder_code }}</td>
						<td data-th='Created At'>{{ $record->lang_code }}</td>
						<td data-th='Action'>
							@if($record->active)
							<a href="{{URL::to('admin/language/update-status/'.$record->id.'/0')}}" class="btn btn-success btn-small ">{{ trans("messages.language_manager.mark_as_inactive") }} </a>
							@else
							<a href="{{URL::to('admin/language/update-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small">{{ trans("messages.language_manager.mark_as_active") }}</a>
							@endif
							@if($default_lang == $record->id )
							<a href="javascript:void(0)" class="btn btn-primary  btn-small">{{ trans("messages.language_manager.default") }}</a>
							@elseif($record->active==1)
							<a href="{{URL::to('admin/language/default/'.$record->id.'/'.$record->title.'/'.$record->folder_code)}}" class="btn btn  btn-small">{{ trans("messages.language_manager.mark_as_default") }}</a>
							@endif
							<a href="{{URL::to('admin/language/delete-language/'.$record->id)}}" class="btn btn-danger btn-small " data-delete="delete" >{{ trans("messages.language_manager.delete")  }} </a>
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
	{{ $result->links() }}
</div>
@stop

