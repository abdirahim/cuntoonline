@extends('layouts.default')
@section('content')
<div class="container">
	<div id="feedequal" class="row">
		<div class="col-md-12 col-sm-12 feedbox">
		   <div class="p10 text-center">
				<h3 class="bluecolor">
					<strong style="font-size:60px;" class="f38">ERROR <b>404</b></strong><br><br>
					Sorry, this page doesn't exist
				</h3>
					The link you followed may be broken, or the page may have been removed.<br>
				<br><br><br><br>				
				<a class="btn btn btn-default" href="{{ URL::to('/')}}" class="go-back">Go back to the Home Page</a>  
				<br><br><br><br>
			</div>
		</div>
	</div>
</div>
@stop