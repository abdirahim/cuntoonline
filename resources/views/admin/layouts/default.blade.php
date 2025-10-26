<!DOCTYPE html>
<html>
	<head>
		<title><?php echo Config::get("Site.title"); ?></title>
		
			
		{{ HTML::style('css/admin/bootstrap.css') }}
		{{ HTML::style('css/admin/font-awesome/css/font-awesome.css') }}
		{{ HTML::style('css/admin/mws-style.css') }}
		{{ HTML::style('css/admin/icons/icol16.css') }}
		{{ HTML::style('css/admin/fonts/icomoon/style.css') }}
		{{ HTML::style('css/admin/icons/icol32.css') }}
		{{ HTML::style('css/admin/mws-theme.css') }}
		{{ HTML::style('css/admin/themer.css') }}
		{{ HTML::style('css/admin/custom.css') }}
		{{-- HTML::style('css/admin/jquery-ui.css') --}}	
		
		{{ HTML::script('js/admin/jquery-2.1.1.min.js') }}
		{{ HTML::script('js/admin/core/mws.js') }}
		{{ HTML::script('js/admin/core/themer.js') }}
		{{ HTML::script('js/admin/bootstrap/js/bootstrap.min.js') }}
		{{ HTML::script('js/admin/jquery-ui.min.js') }}
		
		{{ HTML::style('css/admin/styleres.css') }}
		{{-- HTML::style('css/admin/custom_res.css') --}}
		
		
		<script type="text/javascript">
			/* For set the time for flash messages */
			$(function(){
			
				window.setTimeout(function () { 
					$(".mws-form-message.success").hide('slow'); 
					$(".mws-form-message.error").hide('slow'); 
				}, 6000);
				
			});
		</script>
	</head>
	<body>
		<div id="mws-header" class="clearfix">
			<div id="mws-logo-container">
				<div id="mws-logo-wrap" style="color:#ffffff; font-size:22px">
					<a href="{{URL::to('admin/dashboard')}}" style="text-decoration:none; color:#c5d52b;"><?php echo Config::get("Site.title"); ?></a>
				</div>
			</div>
			<div id="mws-user-tools" class="clearfix">
				<div id="mws-user-info" class="mws-inset" style="padding:0; height:36px">
					 <div id="mws-user-functions">
						<div id="mws-username">
							Welcome Admin!
						</div>
						<ul>
							<li><a href="{{URL::to('admin/myaccount')}}">{{ 'Myaccount' }} </a></li>
							<li><a href="{{URL::to('admin/logout')}}">{{ 'Logout' }} </a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Start Main Wrapper -->
		<div id="mws-wrapper">
			<div id="mws-sidebar">
				<div id="mws-nav-collapse">
					<span></span>
					<span></span>
					<span></span>
				</div>
				<?php $segment2	=	Request::segment(2); 
				$segment3	=	Request::segment(3);?>
				<!-- Sidebar Wrapper -->
				<div id="mws-navigation">
					<ul>
						<li class="{{ ($segment2 == 'dashboard') ? 'active in' : '' }}"><a href="{{URL::to('admin/dashboard')}}"><i class="fa fa-home fa-2x {{ ($segment2 == 'dashboard') ? 'fa-spin' : '' }}"></i>{{ trans("messages.dashboard.dashboard_text") }} </a></li>
						
						<li class="{{ ($segment2 == 'users') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/users')}}"><i class="fa fa-users fa-2x {{ $segment2 == 'users' ? 'fa-spin' : '' }}"></i>{{ trans("messages.user_managmt.user_management_text") }} </a>
						</li>
					
						<li class="{{ ($segment2 == 'restaurant-manager'|| $segment3 == 'food-category') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/restaurant-manager')}}"><i class="icon-food fa-2x {{ $segment2 == 'restaurant-manager' ? 'fa-spin' : '' }}"></i>{{ trans("messages.restaurant.restaurants_eals") }} </a>
						</li>
						<li class="{{ ($segment2 == 'order-manager') ? 'active in' : '' }}">
							<a href="{{URL::to('admin/order-manager')}}"><i class="fa fa-shopping-cart fa-2x {{ $segment2 == 'order-manager' ? 'fa-spin' : '' }}"></i>{{ trans("messages.order.oreder_manager") }} </a>
						</li>
							
						<li class="{{ (in_array($segment2 ,array('dropdown-manager')) && $segment3 != 'food-category' ) ? 'active in' : 'offer-reports' }}">
							<a href="{{URL::to('admin/dropdown-manager/cuisine')}}"><i class="fa fa-server fa-2x {{ in_array($segment2 ,array('dropdown-manager')) ? 'fa-spin' : '' }}"></i>{{ trans("messages.cuisine.cuisine_manager") }} </a>
						</li>
						<li class="{{ in_array($segment2 ,array('email-manager','cms-manager','block-manager','faqs-manager','email-logs','text-setting')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-th fa-2x {{ in_array($segment2 ,array('email-manager','cms-manager','block-manager','faqs-manager','email-logs','text-setting')) ? 'fa-spin' : '' }}"></i>{{ trans("messages.management.management_text") }} </a>
							<ul class="{{ in_array($segment2 ,array('email-manager','cms-manager','block-manager','faqs-manager','email-logs','text-setting')) ? '' : 'closed' }}">
							
								<li><a href="{{URL::to('admin/email-manager')}}">{{ trans("messages.management.email_template_text") }} </a></li>
								<li><a href="{{URL::to('admin/cms-manager')}}">{{ trans("messages.management.cms_text") }} </a></li>
								<li><a href="{{URL::to('admin/faqs-manager')}}">{{ trans("messages.management.faq_text") }} </a></li>
								<li><a href="{{URL::to('admin/email-logs')}}">{{ trans("messages.management.email_logs_text") }} </a></li>
								<li><a href="{{URL::to('admin/text-setting')}}">Text setting </a></li>
								<li><a href="{{URL::to('admin/block-manager')}}">{{ 'Block Manager' }} </a></li>
							
							</ul>
						</li>
					
						<li class="{{ in_array($segment2 ,array('settings')) ? 'active in' : 'offer-reports' }}">
							<a href="javascript::void(0)"><i class="fa fa-cogs fa-2x {{ in_array($segment2 ,array('settings')) ? 'fa-spin' : '' }}"></i>{{ trans("messages.settings.setting_text")  }} </a>
							<ul class="{{ in_array($segment2 ,array('settings')) ? '' : 'closed' }}">
								<li>
									<a href="{{URL::to('admin/settings/prefix/Site')}}">{{ trans("messages.settings.site_text") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/Social')}}">{{ trans("messages.settings.social_text") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/setting_social')}}">{{ trans("Social Setting") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/Reading')}}">{{ trans("messages.settings.reading_text") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/Email')}}">{{ trans("messages.settings.email_text") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/Delivery')}}">{{ trans("messages.settings.delivery_text") }} </a>
								</li>
								<li>
									<a href="{{URL::to('admin/settings/prefix/Contact')}}">{{ 'Contact' }} </a>
								</li>

							</ul>
						</li>
						
					</ul>
				</div>         
			</div>
			  <!-- Main Container Start -->
			<div id="mws-container" class="clearfix">
				<div class="container" style="min-height:600px">
					
					@if(Session::has('error'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message error">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('error') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif
					@if(Session::has('success'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message success">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('success') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif
					@if(Session::has('flash_notice'))
					<div class="grid_8 mws-collapsible">
						<div class="mws-form-message success">
							<a href="javascript:void(0);" class="close pull-right">&times;</a>
							<p>{{ Session::get('flash_notice') }}</p>
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					@endif
					{{ isset($breadcrumbs) ? $breadcrumbs : ''}}
					
					@yield('content')
				</div>
			</div>
		</div>
	
		<div id="mws-footer-fix" class="mws-footer-fix clearfix">
			 <div id="mws-user-tools" class="clearfix" style="padding:5px 0;">
				<div id="mws-user-info" class="mws-inset" style="height:18px; padding:0">
					 <div id="mws-user-functions">
						<div id="mws-username" >
							<?php echo Config::get("Site.copyright_text"); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<style>
			.fa {  margin:0 8px; }
			.requireRed{
				color:red;
			}
			.text-center{
				text-align:center;
			}
		</style>
	</body>
</html>
