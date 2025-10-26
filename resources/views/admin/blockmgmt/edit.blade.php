@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ 'Edit block' }} </span>
		<a href="{{URL::to('admin/block-manager')}}" class="btn btn-success btn-small align">{{ 'Back' }} </a>
	</div>
	@if(count($languages) > 1)
		<div  class="default_language_color">
			{{  Config::get('default_language.message') }}
		</div>
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">
				<?php $i = 1 ; ?>
				@foreach($languages as $value)
					<li class=" {{ ($i ==  $language_code )?'active':'' }}">
						<a data-toggle="tab" href="#{{ $i }}div">
							{{ $value -> title }}
						</a>
					</li>
					<?php $i++;  ?>
				@endforeach
			</ul>
		</div>
	@endif
	{{ Form::open(['role' => 'form','url' => 'admin/block-manager/edit-block/'.$block->id,'class' => 'mws-form']) }}
	<div class="mws-panel-body no-padding">
		@if(count($languages) > 1)
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
				<b>{{ 'These fields are same in all languages' }}</b>
			</div>
		@endif
		<div class="mws-form-inline">			
			<div class="mws-form-row">
				{{  Form::label('page_name', 'Page Name', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('page_name',$block->page_name, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('page_name'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row ">
				{{  Form::label('block_name', 'Block Name', ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text("block_name",$block->block_name, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('block_name'); ?>
					</div>
				</div>
			</div>		
		</div>
	</div>
	
	<div class="mws-panel-body no-padding tab-content"> 
		<?php $i = 1 ; ?>
		@foreach($languages as $value)
			<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  Form::label($i.'_body', 'Description', ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$i][description]",isset($multiLanguage[$i]['description'])?$multiLanguage[$i]['description']:'', ['class' => 'small','id' => 'description'.$i]) }}
							<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('description') : ''; ?>
							</span>
						</div>
						<script type="text/javascript">
							/* CKEDITOR for description */
							CKEDITOR.replace( <?php echo 'description'.$i; ?>,
							{
								height: 350,
								width: 600,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
								filebrowserImageWindowWidth : '640',
								filebrowserImageWindowHeight : '480',
								enterMode : CKEDITOR.ENTER_BR
							});
							
						</script>
					</div>
				</div>
			</div>  
			<?php $i++ ; ?>
		@endforeach
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="Save" class="btn btn-danger">
					
				<a href="{{URL::to('admin/block-manager/edit-block/'.$block->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop
