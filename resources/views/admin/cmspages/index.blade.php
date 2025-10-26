@extends('admin.layouts.default')
@section('content')
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/cms-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Name' }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
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
			<a href="{{URL::to('admin/cms-manager')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.management.cms_manager") }} </span>
		@if(Config::get('app.debug'))
		<a href="{{URL::to('admin/cms-manager/add-cms')}}" class="btn btn-success btn-small align">{{ trans("messages.management.add_new_cms") }} </a>
		@endif
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="20%">
						{{
						link_to_action(
						'CmspagesController@listCms',
						'Name',
						array(
						'sortBy' => 'name',
						'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
						),
						array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
						}}
					</th>
					<th width="60%">{{ 'Description' }}</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr>
					<td data-th='Name'>{{ $record->name }}</td>
					<td data-th='Description'>{{ strip_tags(Str::limit($record->body, 300)) }}</td>
					<td data-th='Action'>
						<a href="{{URL::to('admin/cms-manager/edit-cms/'.$record->id)}}" class="btn btn-info btn-small">{{ trans("messages.management.edit") }} </a>
					</td>
				</tr>
				@endforeach
				@else
				<table class="mws-table mws-datatable details">
					<tr>
						<td align="center" width="100%"> {{ 'No Records Found' }}</td>
					</tr>
				</table>
				@endif 
			</tbody>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
@stop

