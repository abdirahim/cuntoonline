@extends('admin.layouts.default')
@section('content')

<script type="text/javascript">
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


<!-- ######################################################  search  form  start  here##################################################################################-->
<div id="mws-themer">
	<div id="mws-themer-content" style="<?php echo ($searchVariable) ? 'right: 256px;' : ''; ?>">
		<div id="mws-themer-ribbon"></div>
		<div id="mws-themer-toggle" class="<?php echo ($searchVariable) ? 'opened' : ''; ?>">
			<i class="icon-bended-arrow-left"></i> 
			<i class="icon-bended-arrow-right"></i>
		</div>
		{{ Form::open(['role' => 'form','url' => 'admin/restaurant-manager/reviews/'.$restaurantDetail->id,'class' => 'mws-form']) }}
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
			<a href="{{URL::to('admin/restaurant-manager/reviews/'.$restaurantDetail->id)}}"  class="btn btn-default btn-small">Reset</a>
		</div>
		{{ Form::close() }}
	</div>
</div>
<!--  ##########################################################  serach  form  end  here##################################################################################-->



<!-- ##########################################################  offer detail start  here#################################################################################-->
 <div class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-tags"></i> Restaurant Details	</span>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper anchor_style">
		<table width="100%" align="left" class="mws-table mws-datatable">
			<tbody>
				<tr>
					<td class="text-right" >
						<label class="mws-form-label" for="total_active_user">Restaurant Name:</label>				
					</td>
					<td >
						{{ $restaurantDetail->name }}	
					</td>
				</tr>
				<tr>
					<td class="text-right" >
						<label class="mws-form-label" for="total_inactive_user">Avg Rating:</label>
					</td>
					<td>
						<ul class="rating" style="list-style:none">
							<li class="review_rating" data-rating="{{ $avgRating}}"></li>
						</ul> 		
					</td>
				</tr>
			</tbody>
		</table>	
	</div>
</div>
<!-- ##########################################################  offer detail end here  here###################################-->


<!-- ########################################################## Review  List Start   here ##############################-->
<div  class="mws-panel grid_8 mws-collapsible">
	<div class="mws-panel-header">
		<span>
			<i class="icon-table"></i> {{ 'Reviews List' }}
		</span>
		<a href="{{URL::to('admin/restaurant-manager')}}" class="btn btn-success btn-small align">{{ 'Back To Listing' }} </a>
	</div>
	<div class="dataTables_wrapper" style="background-color:#CCCCCC">
		<div id="DataTables_Table_0_length" class="dataTables_length">
			<?php //echo $this->element('paging_info'); ?>
		</div>
	</div>
	<div class="mws-panel-body no-padding dataTables_wrapper">
		<table class="mws-table mws-datatable">
			<thead>
				<tr>
					<th width="15%">{{ 'User Name' }}</th>
					<th width="28%">{{ 'Comment' }}</th>
					<th width="15%">{{ 'Rating' }}</th>
					<th width="20%">{{ 'Date' }}</th>
					<th>{{ 'Action' }}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(!$result->isEmpty()){
					foreach($result as $record){?>
						<tr>
							<td data-th="User Name">{{ $record->name  }}</td>
							<td data-th="Comment">{{ $record->comment }}</td>
							<td data-th="Rating" >
							@if($record->rating=='0')
								{{ 'Not Rated' }}
							@else
								<div class="rating">
									<ul class="rating" style="list-style:none">
										<li class="review_rating" data-rating="{{ $record->rating}}"></li>
									</ul> 	
								</div>
							@endif
							</td>
							<td data-th="Date">{{ date(Config::get("Reading.date_format"),strtotime($record->created_at)) }}</td>
							<td data-th="Action" >								
								@if($record->is_display)
									<a href="{{URL::to('admin/restaurant-manager/update-review-status/'.$record->id.'/0')}}" class="btn btn-success btn-small">{{ 'Hide  From Front End' }} </a>
								@else
									<a href="{{URL::to('admin/restaurant-manager/update-review-status/'.$record->id.'/1')}}" class="btn btn-warning btn-small">{{ 'Show  On  Front End' }} </a>
								@endif
								
								<a href="{{URL::to('admin/restaurant-manager/delete-review/'.$record->id)}}" data-delete="delete" class="btn btn-danger btn-small no-ajax">{{ 'Delete' }} </a>
							</td>
						</tr>
					<?php } ?>
			</tbody>
		</table>
		<?php echo $result->links(); ?>
		
		<?php }else{ ?>
		<table class="mws-table mws-datatable">	
			<tr>
				<td align="center" width="100%"> {{'No Records Found'}}</td>
			</tr>	
			<?php  } ?>
		</table>
	</div>
</div>
<!-- ########################################################## Review  List End   here ##################################################################################-->

<!-- star rating js end css start here-->
{{ HTML::style('css/jquery.raty.css') }}
{{ HTML::script('js/jquery.raty.js') }}
<!-- star rating css and  js  end here-->
<script type="text/javascript">
	/** for review rating   */
	$('.review_rating').raty({ 
		readOnly	: true, 
		score		: function() {  
						return $(this).attr('data-rating');  
					  },
		path  		: '<?php echo WEBSITE_URL;?>img',
		numberMax 	: 5,
		half		: true,
	});
</script>

@stop


