@extends('admin.layouts.default')
@section('content')
<!-- View user detail div  -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header" style="height:8%">
		<span>
		<i class="icon-table"></i> {{ trans("messages.restaurant.view_restaurant") }} 
		</span>
		<a href="{{URL::to('admin/restaurant-manager')}}" class="btn btn-success btn-small align" style="margin-left:5px"> {{ trans("messages.user_managmt.back_to_listing") }}</a>
		<a href="{{URL::to('admin/restaurant-manager/edit-restaurant/'.$restroDetails->id)}}" class="btn btn-primary btn-small align">{{ trans("messages.user_managmt.edit") }}</a>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<tbody>
				<tr>
					<th width="20%">Cuisine</th>
					<td data-th='Cuisine Name'>{{ $cuisineName }}</td>
				</tr>
				<tr>
					<th width="20%">Restaurant Name</th>
					<td data-th='Restaurant Name'>{{ $restroDetails->name }}</td>
				</tr>
				<tr>
					<th width="20%">Description</th>
					<td data-th='Description'>{{ $restroDetails->description }}</td>
				</tr>
				<tr>
					<th width="20%">Image</th>
					<td data-th='Image'>
						@if(File::exists(RESTAURANT_IMAGE_ROOT_PATH.$restroDetails->image) && $restroDetails->image!='')
						{{ HTML::image( RESTAURANT_IMAGE_URL.$restroDetails->image, $restroDetails->image , array( 'width' => 70, 'height' => 70 )) }}
						@endif
					</td>
				</tr>
				<tr>
					<th width="20%">Opening Time</th>
					<td data-th='Opening Time'>
						<div>
							<table>
								<tbody>
									<tr>
										<td><strong> Mon : </strong></td>
										<td>@if(isset($editDetailArray[Monday])) {{ $editDetailArray[Monday]->open_time}} - {{ $editDetailArray[Monday]->close_time}}  @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Tue : </strong></td>
										<td>@if(isset($editDetailArray[Tuesday])) {{ $editDetailArray[Tuesday]->open_time}} - {{ $editDetailArray[Tuesday]->close_time}} @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Wed : </strong></td>
										<td>@if(isset($editDetailArray[Wednesday])) {{ $editDetailArray[Wednesday]->open_time}} - {{ $editDetailArray[Wednesday]->close_time}} @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Thurs :</strong></td>
										<td>@if(isset($editDetailArray[Thursday])) {{ $editDetailArray[Thursday]->open_time}} - {{ $editDetailArray[Thursday]->close_time}} @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Fri : </strong></td>
										<td>@if(isset($editDetailArray[Friday])) {{ $editDetailArray[Friday]->open_time}} - {{ $editDetailArray[Friday]->close_time}} @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Sat : </strong></td>
										<td>@if(isset($editDetailArray[Saturday])) {{ $editDetailArray[Saturday]->open_time}} - {{ $editDetailArray[Saturday]->close_time}} @else Close @endif</td>
									</tr>
									<tr>
										<td><strong>Sun : </strong></td>
										<td>@if(isset($editDetailArray[Sunday])) {{ $editDetailArray[Sunday]->open_time}} - {{ $editDetailArray[Sunday]->close_time}} @else Close @endif</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<th width="20%">Collection</th>
					<td data-th='Collection'>{{ ($restroDetails->collection == COLLECTION ) ? 'Available' : 'Not Available' }}</td>
				</tr>
				<tr>
					<th width="20%">Delivery</th>
					<td data-th='Delivery'>{{ ($restroDetails->delivery == DELIVERY ) ? 'Available' : 'Not Available' }}</td>
				</tr>
				<tr>
					<th width="20%">Created On</th>
					<td data-th='Created On'>{{ date(Config::get("Reading.date_format") , strtotime($restroDetails->created_at)) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--View user detail div end here -->
</div>
@stop
