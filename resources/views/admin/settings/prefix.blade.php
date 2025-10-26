@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span>{{ $prefix_camel }} </span>
	</div>
	<div class="mws-panel-body no-padding">
	
		{{ Form::open(['role' => 'form','url' => 'admin/settings/prefix/'.$prefix,'class' => 'mws-form']) }}
	
			<div class="mws-form-inline">
				<?php $i = 0;
		if(!empty($result)){
			foreach ($result AS $setting) {
				$text_extention 	= 	'';
				$key				= 	$setting['key_value'];
				$keyE 				= 	explode('.', $key);
				$keyTitle 			= 	$keyE['1'];
		
				$label = $keyTitle;
				if ($setting['title'] != null) {
					$label = $setting['title'];
				}

				$inputType = 'text';
				if ($setting['input_type'] != null) {
					$inputType = $setting['input_type'];
				} ?>
				
				{{ Form::hidden("Setting[$i][".'type'."]",$inputType) }}
				{{ Form::hidden("Setting[$i][".'id'."]",$setting['id']) }}
				{{ Form::hidden("Setting[$i][".'key_value'."]",$setting['key_value']) }}
				<?php 
					
					switch($inputType){
						case 'checkbox':
				?>	
				
				<div class="mws-form-row">
					<label class="mws-form-label" style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item clearfix">
						<ul class="mws-form-list inline">
							<?php 	
								$checked = ($setting['value'] == 1 )? true: false;
								$val	 = (!empty($setting['value'])) ? $setting['value'] : 0;
							?>
							{{ Form::checkbox("Setting[$i][".'value'."]",$val,$checked) }} 
						</ul>
					</div>
				</div>
				
				<?php
						break;	
						case 'textarea':	
						case 'text':
						
				?>
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item">
						@if($key == 'Reading.date_format')
							<select name="<?php echo "Setting[$i]['value']"; ?>">
								<option value="d/M/Y H:i:s" <?php echo ($setting['value'] == 'd/M/Y H:i:s') ? 'selected' : '';  ?> > DD/MM/YYYY HH:MM::SS</option>
								<option value="d/M/Y H:ia" <?php echo ($setting['value'] == 'd/M/Y H:ia') ? 'selected' : '';  ?> >DD/MM/YYYY HH:MM(AM/PM)</option>
								<option value="M/d/Y H:i:s" <?php echo ($setting['value'] == 'M/d/Y H:i:s') ? 'selected' : '';  ?> >MM/DD/YYYY HH:MM::SS</option>
								<option value="M/d/Y H:ia" <?php echo ($setting['value'] == 'M/d/Y H:ia') ? 'selected' : '';  ?> >MM/DD/YYYY HH:MM(AM/PM)</option>
								
								<option value="Y/M/d H:i:s" <?php echo ($setting['value'] == 'Y/M/d H:i:s') ? 'selected' : '';  ?> >YYYY/MM/DD HH:MM::SS</option>
								<option value="Y/M/d H:ia" <?php echo ($setting['value'] == 'Y/M/d H:ia') ? 'selected' : '';  ?> >YYYY/MM/DD HH:MM(AM/PM)</option>
								<option value="d-M-Y h:ia" <?php echo ($setting['value'] == 'd-M-Y h:ia') ? 'selected' : '';  ?> >DD-MM-YYYY HH:MM(AM/PM)</option>
							</select>			
						@else
							{{ Form::{$inputType}("Setting[$i][".'value'."]",$setting['value'], ['class' => 'small']) }} 
						@endif	
					</div>
				</div>
				<?php
					break;	
					default:
				?>
				
				<div class="mws-form-row">
					<label class="mws-form-label"  style="width:300px;"><?php echo $label; ?></label>
					<div class="mws-form-item">
						{{ Form::textarea("Setting[$i][".'value'."]",$setting['value'], ['class' => 'small']) }} 
					</div>
				</div>
				<?php	
					break;
						
				}
				$i++;
			}
		}
		?>	
			</div>
			<div class="mws-button-row">
				<input type="submit" value="{{ trans('messages.settings.submit') }}" class="btn btn-danger">
			</div>
		{{ Form::close() }} 
	</div>    	
</div>

@stop