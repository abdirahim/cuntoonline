<div class="sidebar mt50 pages">
	<ul>
		<li  @if(Request::segment(2)=='about-us') class="active" @endif><a href="{{URL::to('pages/about-us')}}">About Us</a></li>
		<li  @if(Request::segment(1)=='faq') class="active" @endif><a href="{{URL::to('faq')}}">FAQ's</a></li>
		<li  @if(Request::segment(2)=='blog') class="active" @endif><a href="{{URL::to('pages/blog')}}">How it works</a></li>
		<li  @if(Request::segment(2)=='contact-us') class="active" @endif><a href="{{URL::to('contact-us')}}">Contact</a></li>
	</ul>	
</div>