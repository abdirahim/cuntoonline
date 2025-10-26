@extends('admin.layouts.default')
@section('content')

<script type="text/javascript">
	// for delete FAQ 
	$(function(){
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
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/faqs-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Question' }}</label><br/>
				{{ Form::text('question',((isset($searchVariable['question'])) ? $searchVariable['question'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Answer' }}</label><br/>
				{{ Form::text('answer',((isset($searchVariable['answer'])) ? $searchVariable['answer'] : ''), ['class' => 'small']) }}
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
			<a href="{{URL::to('admin/faqs-manager')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i>{{ trans("messages.management.faq_manager") }}</span>
		<a href="{{URL::to('admin/faqs-manager/add-faqs')}}" class="btn btn-success btn-small align">{{ trans("messages.management.add_new_faq") }} </a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="30%">
						{{
							link_to_action(
								'FaqsController@listFaq',
								'Question',
								array(
								'sortBy' => 'question',
								'order' => ($sortBy == 'question' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'question' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'question' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
							)
						}}
					</th>
					<th width="50%">
						{{
							link_to_action(
								'FaqsController@listFaq',
								'Answer',
								array(
								'sortBy' => 'answer',
								'order' => ($sortBy == 'answer' && $order == 'desc') ? 'asc' : 'desc'
								),
								array('class' => (($sortBy == 'answer' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'answer' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
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
					<td data-th='Question'>{{ $record->question }}</td>
					<td data-th='Answer'>{{ strip_tags(Str::limit($record->answer, 300)) }}</td>
					<td data-th='Action'>
						@if($record->is_active)
						<a href="{{URL::to('admin/faqs-manager/update-status/'.$record->id.'/0')}}" class="btn btn-success btn-small">{{ trans("messages.management.mark_as_inactive") }}</a>
						@else
						<a href="{{URL::to('admin/faqs-manager/update-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small">{{ trans("messages.management.mark_as_active") }} </a>
						@endif
						<br>
						<a href="{{URL::to('admin/faqs-manager/edit-faqs/'.$record->id)}}" class="btn btn-info btn-small mt5">{{ trans("messages.management.edit") }} </a>
						<a href="{{URL::to('admin/faqs-manager/delete-faqs/'.$record->id)}}" data-delete="delete" class="btn btn-danger btn-small no-ajax mt5">{{ trans("messages.management.delete") }} </a>
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

