@extends('layouts.default')
@section('content')
<div class="content cms">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="cms-content  mt30 ">
					<div class="contact">
						<div class="row">
							<div class="col-md-6">
								<div class="page-heading">
									<h4>Contact Us</h4>
									<span>If there is a problem with your order, then please call us
									or use the chat facility at the bottom right of the scren</span>
								</div>
								<address>
										{{ (!empty($result->accordingLanguage->source_col_description) && isset($result->accordingLanguage->source_col_description)) ? str_replace('WEBSITE_IMG_URL',WEBSITE_IMG_URL,$result->accordingLanguage->source_col_description) : '' }}
								</address>
							</div>
							<div class="col-md-6">
								<div class="page-heading">
									<h4>Find Us</h4>
								</div>
								<iframe src="{{ Config::get('Contact.map')}}" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>       
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop