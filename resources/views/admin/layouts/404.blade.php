@extends('admin.layouts.default')

@section('content')
<!-- Main Container Start -->
	<div id="mws-container" class="clearfix" style="margin:auto">
		<!-- Inner Container Start -->
		<div class="container">
			<div id="mws-error-page">
				<h1>Error <span>404</span></h1>
				<h5>Oopss... this is embarassing, either you tried to access a non existing page, or our server has gone crazy.</h5>
				<p><a href="{{ URL::to('admin') }}">click here</a> to go back home</p>
			</div>
		</div>
		<!-- Inner Container End -->
	</div>
<!-- Main Container End -->
@stop
