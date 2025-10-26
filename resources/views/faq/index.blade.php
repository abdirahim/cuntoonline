@extends('layouts.default')
@section('content')
<div class="content cms">
<div class="container">
	<div class="row">
		<div class="col-md-3">
			@include('elements.cms_left')
		</div>
		<div class="col-md-9">
			<div class="cms-content  mt30 ">
				<div class="page-heading">
					<h4>FAQ’s</h4>
				</div>
				<div class="faq">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						@if(!$faq->isEmpty())
						<?php $i=0;?>
							@foreach($faq as $key=> $result)
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingFaq1">
										<h4 class="panel-title">
											<a role="button" @if($i!=0) class="collapsed" @endif  data-toggle="collapse" data-parent="#accordion" href="#faq{{ $key}}" aria-expanded="true" aria-controls="faq1">
											{{ $result->accordingLanguage->question}}
											</a>
										</h4>
									</div>
									<div id="faq{{$key}}" class="panel-collapse collapse @if($key==0) in @endif " role="tabpanel" aria-labelledby="headingFaq1">
										<div class="panel-body">
											<p>	{{ $result->accordingLanguage->answer}}</p>
										</div>
									</div>
								</div>
									<?php $i++;?>
							@endforeach
						@else
							<div>{{ trans("messages.No Topic Found.") }}</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@stop