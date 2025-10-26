@extends('admin.layouts.default')

@section('content')

<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
	
		{{ Form::open(['method' => 'get','role' => 'form','url' => 'admin/email-manager','class' => 'mws-form']) }}
		{{ Form::hidden('display') }}
		<div class="mws-themer-section">
			<div id="mws-theme-presets-container" class="mws-themer-section">
				<label>{{ 'Name' }}</label><br/>
				{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => 'small']) }}
			</div>
		</div>
		<div class="mws-themer-separator"></div>
		<div class="mws-themer-section" style="height:0px">
			<ul><li class="clearfix"><span></span> <div id="mws-textglow-op"></div></li> </ul>
		</div>
		<div class="mws-themer-section">
			<input type="submit" value="Search" class="btn btn-primary btn-small">
			<a href="{{URL::to('admin/email-manager')}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>

<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ trans("messages.management.email_templates") }} </span>
			@if(Config::get('app.debug'))
			<a href="{{URL::to('admin/email-manager/add-template')}}" class="btn btn-success btn-small align">{{ trans("messages.management.add_email_template") }} </a>
			@endif
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th>
					{{
						link_to_action(
							'EmailtemplateController@listTemplate',
							'Name',
							array(
								'sortBy' => 'name',
								'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'name' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}
					</th>
					<th>
					{{
						link_to_action(
							'EmailtemplateController@listTemplate',
							'Subject',
							array(
								'sortBy' => 'subject',
								'order' => ($sortBy == 'subject' && $order == 'desc') ? 'asc' : 'desc'
							),
						   array('class' => (($sortBy == 'subject' && $order == 'desc') ? 'sorting desc' : (($sortBy == 'subject' && $order == 'asc') ? 'sorting asc' : 'sorting')) )
						)
					}}</th>
					<th>{{ 'Modified'}}</th>
					<th>{{ 'Created' }}</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
			@if(!$result->isEmpty())
				@foreach($result as $record)
				<tr>
					<td data-th='Name'>{{ $record->name }}</td>
					<td data-th='Subject'>{{ $record->subject }}</td>
					<td data-th='Modified'>{{ date(Config::get("Reading.date_format"),strtotime($record->updated_at)) }}</td>
					<td data-th='Created'>{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
					<td data-th='Action'>
						<a href="{{URL::to('admin/email-manager/edit-template/'.$record->id)}}" class ="btn btn-info btn-small" >
							{{ 'Edit' }} 
						</a>
					</td>
				</tr>
				@endforeach
			@else 
				<table class="mws-table mws-datatable details">	
					<tr>
						<td align="center" width="100%"> {{'No Records Found'}}</td>
					</tr>	
				</table>	
			@endif 
			</tbody>
		</table>
	</div>
	{{ $result->appends($searchVariable)->links() }}
</div>
@stop
