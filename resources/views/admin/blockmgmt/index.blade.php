@extends('admin.layouts.default')

@section('content')

<script type="text/javascript">
/* For delete block */
$(function(){
	$('[data-delete]').click(function(e){
		
	     e.preventDefault();
		// If the user confirm the delete
		if (confirm('Do you really want to delete this block ?')) {
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
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/block-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Page Name' }}</label><br/>
				{{ Form::text('page_name',((isset($searchVariable['page_name'])) ? $searchVariable['page_name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/block-manager')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ 'Blocks list' }} </span>
			
			<a href="{{URL::to('admin/block-manager/add-block')}}" class="btn btn-success btn-small align">{{ 'Add New Block' }} </a>
			
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="35%">
						{{
							link_to_action(
								'BlockController@listBlock',
								'Page Name',
								array(
									'sortBy' => 'page_name',
									'order' => ($sortBy == 'page_name' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'page_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'page_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="35%">
						{{
							link_to_action(
								'BlockController@listBlock',
								'Block Name',
								array(
									'sortBy' => 'block_name',
									'order' => ($sortBy == 'block_name' && $order == 'desc') ? 'asc' : 'desc'
								),
							   array('class' => (($sortBy == 'block_name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'block_name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tblock>
				@if(!$result->isEmpty())
					@foreach($result as $record)
					<tr>
						<td>{{ $record->page_name }}</td>
						<td>{{ strip_tags(Str::limit($record->block_name, 300)) }}</td>
						<td>
							<a href="{{URL::to('admin/block-manager/edit-block/'.$record->id)}}" class="btn btn-info btn-small">{{ 'Edit' }} </a>
							
							@if(Config::get('app.debug'))
							<a href="{{URL::to('admin/block-manager/delete-block/'.$record->id)}}" data-delete="delete" class="btn btn-danger btn-small">{{ 'Delete' }} </a>
							@endif
						</td>
					</tr>
					 @endforeach  
					@else
						<table class="mws-table mws-datatable">	
							<tr>
								<td align="center" width="100%"> {{ 'No Records Found' }}</td>
							</tr>	
						</table>
					@endif 
			</tblock>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
@stop
