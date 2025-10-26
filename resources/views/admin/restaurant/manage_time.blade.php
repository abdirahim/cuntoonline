@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/developer.css') }}
{{ HTML::script('js/jquery.timepicker.js') }}
{{ HTML::style('css/jquery.timepicker.css') }}

<?php $oldData =	 Input::old();
 ?>
<!-- Searching div -->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
		<i class="icon-table"></i> {{ trans("messages.restaurant.restaurant_text") }}
		</span>
		<a href="{{URL::to('admin/restaurant-manager')}}" class="btn btn-success btn-small align">{{ 'Back To Listing' }} </a>
	</div>
	
	<div class="mws-panel-body no-padding dataTables_wrapper">
		@if(empty($openTimeDetail))
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/manage-time/'.$restaurantId,'class' => 'mws-form','files'=>'true','id'=>'my-form']) }}
				<div class="mws-form-row ">
					<label for="name" class="mws-form-label">Restaurant Opening Time<span class="requireRed"> * </span></label>
					<div class="mws-form-item">
						<input  name="open_time" type='text'  class="form-control" value="{{ $openTime}}" />
						<div class="error-message help-inline">
							<?php echo $errors->first('open_time'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					<label for="name" class="mws-form-label">Restaurant Closing Time<span class="requireRed"> * </span></label>
					<div class="mws-form-item">
						<input  name="close_time" type='text'  class="form-control" value="{{ $closeTime}}" />
						<div class="error-message help-inline">
							<?php echo $errors->first('close_time'); ?>
						</div>
					</div>
				</div>

			<table class="mws-table mws-datatable">
				<thead>
					<tr>
						<th>Day</th>
						<th width="25%">Open/Close</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-th='Day'>Monday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[0]',config("constants.Monday"),true)}}</td>
					</tr>
					<tr>
						<td data-th='Day'>Tuesday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[1]',config("constants.Tuesday"),true)}}</td>
					</tr>
					<tr>
						<td data-th='Day'>Wednesday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[2]',config("constants.Wednesday"),true)}}</td>
					</tr>
					<tr>
						<td data-th='Day'>Thursday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[3]',config("constants.Thursday"),true)}}</td>
					</tr>
					<tr>
						<td data-th='Day'>Friday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[4]',config("constants.Friday"),true)}}</td>
					</tr>
					<tr>
						<td data-th='Day'>Saturday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[5]', config("constants.Saturday"),true)}}</td>
						<!--<td data-th='Opening Time'>
							<div class='input-group date datetimepicker3 {{ Saturday}}' @if( (!empty($oldData)) && (!isset($oldData["day"][5])) ) style="display:none" @endif >
								<input  name="open_time[]" type='text'  class="form-control" value="@if(Session::has('error')) {{ $oldData['open_time'][5] }} @endif" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-time"></span>
								</span>
							</div>
						</td>
						<td data-th='Closing Time'>						
							<div class='input-group date datetimepicker3 {{ Saturday}}' @if( (!empty($oldData)) && (!isset($oldData["day"][5])) ) style="display:none" @endif >
								<input  name="close_time[]" type='text'  class="form-control" value="@if(Session::has('error')) {{ $oldData['close_time'][5] }} @endif" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-time"></span>
								</span>
							</div>
						</td>-->
					</tr>
					<tr>
						<td data-th='Day'>Sunday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[6]', config("constants.Sunday"),true)}}</td>
					</tr>
				</tbody>
			</table>
		<div class="mws-button-row buttons-position">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
				<a href="{{ URL::to('admin/restaurant-manager/manage-time/'.$restaurantId) }}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
			</div>
		</div>
		{{ Form::close() }}
		@else
			{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/manage-time/'.$restaurantId,'class' => 'mws-form','files'=>'true','id'=>'my-form']) }}
				
				<div class="mws-form-row ">
					<div class="mws-form-cols">
					<div class="mws-form-col-2-8">
						<label for="name" class="mws-form-label">Restaurant Opening Time<span class="requireRed"> * </span></label>
						<div class="mws-form-item">
							<input  name="open_time" type='text'  class="form-control" value="{{ $openTime}}" />
							<div class="error-message help-inline">
								<?php echo $errors->first('open_time'); ?>
							</div>
						</div>
					</div>
					
					<div class="mws-form-col-2-8">
				
						<label for="name" class="mws-form-label">Restaurant Closing Time<span class="requireRed"> * </span></label>
						<div class="mws-form-item">
							<input  name="close_time" type='text'  class="form-control" value="{{ $closeTime}}" />
							<div class="error-message help-inline">
								<?php echo $errors->first('close_time'); ?>
							</div>
						</div>
					</div>
					</div>
				</div>

			<table class="mws-table mws-datatable">
				<thead>
					<tr>
						<th>Day</th>
						<th width="25%">Open/Close</th>
						<th width="25%">Open/Close/Time</th>
				</thead>
				<tbody>
					<tr>
						<td data-th='Day'>Monday</td>
{{--						<td data-th='OPen/Close'>{{ Form::checkbox('day[0]',Monday,isset($editDetailArray[Monday]) ?true:false)}}</td>--}}
						<td data-th='OPen/Close'>{{ Form::checkbox('day[0]', 'Monday', isset($editDetailArray['Monday']) ?true:false, array('id'=>'days'))}}</td>

						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[0]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
							<input  name="day_close_time[0]" type='text'  class="form-control" value="{{ $closeTime}}" />
							<div class="error-message help-inline">
								<?php echo $errors->first('close_time'); ?>
							</div>
						</td>


					</tr>
					<tr>
						<td data-th='Day'>Tuesday</td>
							<td data-th='OPen/Close'>{{ Form::checkbox('day[1]','Tuesday',isset($editDetailArray['Tuesday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[1]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
								<input  name="day_close_time[1]" type='text'  class="form-control" value="{{ $closeTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('close_time'); ?>
								</div>
							</div>
						</td>

					</tr>
					<tr>
						<td data-th='Day'>Wednesday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[2]','Wednesday',isset($editDetailArray['Wednesday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[2]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
						</td>

					</tr>
					<tr>
						<td data-th='Day'>Thursday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[3]','Thursday',isset($editDetailArray['Thursday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[3]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td data-th='Day'>Friday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[4]','Friday',isset($editDetailArray['Friday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[4]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td data-th='Day'>Saturday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[5]','Saturday',isset($editDetailArray['Saturday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[5]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td data-th='Day'>Sunday</td>
						<td data-th='OPen/Close'>{{ Form::checkbox('day[6]','Sunday',isset($editDetailArray['Sunday']) ?true:false)}}</td>
						<td data-th='OPen/Close'>
							<div class="mws-form-item">
								<input  name="day_open_time[6]" type='text'  class="form-control" value="{{ $openTime}}" />
								<div class="error-message help-inline">
									<?php echo $errors->first('open_time'); ?>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="mws-button-row buttons-position">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_managmt.save') }}" class="btn btn-danger">
					<a href="{{ URL::to('admin/restaurant-manager/manage-time/'.$restaurantId) }}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_managmt.reset") }}</a>
				</div>
			</div>
		@endif
	</div>
</div>

<script>
//checkbox check, uncheck for open or close restaurant
	 $("document").ready(function(){
		$('.form-control').timepicker({
			'showDuration': true,
			'timeFormat': 'g:i A',
			'show2400': false,
		});
		$('input[type="checkbox"]').change(function() {
				if($(this).prop('checked')) {
					$("."+this.value).show();
				} else {
					$("."+this.value).hide();
				}
				
		});
	});
</script>
@stop

