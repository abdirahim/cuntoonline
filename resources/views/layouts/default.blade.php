<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (isset($pageTitle)) ? $pageTitle : 'Cunto Online'; ?></title>
    <meta name="keywords" content="<?php echo (isset($metaKeywords)) ? $metaKeywords : ''; ?>" />
    <meta name="description"  content="<?php echo (isset($metaDescription)) ? $metaDescription : ''; ?>" />
{{--    <meta name="csrf-token" content="{!! csrf_field() !!}">--}}

{{ HTML::style('css/bootstrap.css') }}

{{ HTML::style('fontawesome/css/font-awesome.css') }}

{!! Minify::stylesheet('/css/flexnav.css') !!}
{!! Minify::stylesheet('/css/developer.css') !!}
{!! Minify::stylesheet('/css/animate.css') !!}
{!! Minify::stylesheet('/css/pnotify.core.css') !!}
{!! Minify::stylesheet('/css/jquery.raty.css') !!}
{!! Minify::stylesheet('/css/bootstrap-datetimepicker.css') !!}



<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    {{ HTML::script('js/jquery-2.1.1.min.js') }}



    {!! Minify::javascript('/js/pnotify-master/pnotify.core.js') !!}
    {!! Minify::javascript('/js/bootstrap.min.js') !!}
    {!! Minify::javascript('/js/moment.js') !!}
    {!! Minify::javascript('/js/bootstrap-datetimepicker.js') !!}
    {!! Minify::javascript('/js/jquery.form.js') !!}
    {!! Minify::javascript('/js/civem-0.0.7.js') !!}
    {!! Minify::javascript('/js/jquery.flexnav.js') !!}
    {!! Minify::javascript('/js/animate-plus.js') !!}


    <script type="text/javascript">
        $(function(){
            /* For auto hide flash messgaes */
            window.setTimeout(function () {
                $(".alert.alert-success.alert-dismissible").hide('slow');
                $(".flash-message").hide('slow');
                //$(".alert.alert-danger.alert-dismissible").hide('slow');
            }, 6000);
        });

        /* For notification messages */
        function notice(title,message,type){
            new PNotify({
                title: title,
                addclass: "stack-bottomright",
                text: message,
                type : type,
                hide: true,
                shadow: true,
                delay: 6000,
                mouse_reset: true,
                buttons: {
                    closer: true ,
                    sticker:false
                }
            });
        }
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-64207067-2', 'auto');
        ga('send', 'pageview');

    </script>

</head>
<body id="body">
@include('elements.flash_message')
<div class="loading-hold" id="overlay">
    <div class="loader"></div>
</div>
<header>
    <div class="header-top ">
        <div class="container">
            <div class="row">

                <div id="carousel-example-generic" data-interval="3500" class="carousel slide" data-ride="carousel"><!--start of xaflada carousel-->
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">



                        <div class="item">
                            <div class="call-icon">
                                <i class="fa fa-calendar "></i>
                            </div>
                            Halkan ka dalbo cuntada munaasibadaha, aroosyada iyo xafladaha!
                        </div>



                        <div class="item">
                            <div class="call-icon">
                                <i class="fa fa-calendar "></i>
                            </div>
                            Order all your events food in here!
                        </div>

                        <div class="item">
                            <div class="call-icon">
                                <i class="fa fa-question fa-lg"></i>
                            </div>
                            Call us on: 063 388 0284

                        </div>

                        <div class="item active left">
                            <div class="call-icon">
                                <i class="fa fa-question fa-lg"></i>
                            </div>
                            Wixii faahfaahin ah: 063 388 0284

                        </div>


                    </div>
                </div> <!--end of xaflada carousel-->

                <div class="col-sm-6 col-xs-7">
                    <div class="header-contact xs-text-center">
                        <ul>
                            <li><img src="{{ URL::to('img/') }}/email.png" alt="email"> <a href="#">{{ config('constants.Contact_email') }}</a></li>
                            <li><img src="{{ URL::to('img/') }}/phone.png" alt="phone"> {{ config('constants.Contact_phone') }} </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-5 text-right">
                    <a href="javascript:void(0)"><img src="{{ URL::to('img/') }}/worldgif.gif" alt="world-remit"></a>
                    <div class="lang-hold">
                        <!--<a href="javascript:void(0);" class="language-dropdown-link"> Language <span class="caret"></span></a>-->
                        <ul>
                        <!--<a href="javascript:void(0)"><img src="{{ URL::to('img/') }}/worldgif.gif" alt="world-remit"></a>-->
                        <!--<li><a href="javascript:void(0)"><img src="{{ URL::to('img/') }}/english.jpg" alt="english">English</a></li>
									<li><a href="javascript:void(0)"><img src="{{ URL::to('img/') }}/somaliland.jpg" alt="somaliland">Somaliland</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-xs-4">
                    <div class="logo">
                        <a href="{{ URL::to('/') }}"><img src="{{ config("constants.WEBSITE_IMG_URL")}}logo.png" alt="logo" title="Cunto online" ></a>
                    </div>
                </div>
                <div class="col-md-2 fl-r col-sm-3">
                    <div class="header-banner fl-r">
                        <img src="{{ config("constants.WEBSITE_IMG_URL") }}zaad edahab.png" alt="banner">
                    </div>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12 xs-pd">
                    <div class="header-right fl-r">
                        <div class="navigation ">
                            <nav>
                                <div class="menu-button"></div>
                                <ul data-breakpoint="768" class="flexnav">
                                    <li @if(Request::segment(1)=='') class="active" @endif ><a href="{{ URL::to('/') }}">Home</a></li>
                                    <li @if(Request::segment(2)=='about-us') class="active" @endif ><a href="{{ URL::to('pages/about-us') }}">About Us</a></li>
                                    <li @if(Request::segment(2)=='blog') class="active" @endif ><a href="{{ URL::to('pages/blog') }}">How it works </a></li>
                                    <li @if(Request::segment(1)=='faq') class="active" @endif ><a href="{{ URL::to('faq') }}">FAQ’s</a></li>
                                    @if(!Auth::check())
                                        <li @if(Request::segment(1)=='login') class="active" @endif ><a href="{{ URL::to('login') }}">Log In </a></li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="signup-button">
                                @if(!Auth::check())
                                    <a href="{{ URL::to('signup') }}">Signup</a>
                                @else
                                    <div class="dropdown">
                                        <a id="dLabel" data-target="#" href="javascript:void(0)" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            My Account
                                            <span class="caret"></span>
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                                            <li><a href="{{ URL::to('edit-profile') }}">Edit Profile</a></li>
                                            <li><a href="{{ URL::to('logout') }}">Logout</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="clearfix"></div>

@yield('content')

<div class="clearfix"></div>
<footer>
    <div class="footer">
        <div class="container">
            <ul class="footer-nav">
                <li><a href="{{ URL::to('pages/about-us') }}">About Us</a></li>
                <li><a href="{{ URL::to('pages/blog') }}">Blog</a></li>
                <li><a href="{{ URL::to('contact-us') }}">Contact Us</a></li>
            </ul>
            <ul class="social-nav">
                @if(Config::get('Social.facebook')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.facebook'))}}"><img alt="facebook" title="facebook" src="{{ config("constants.WEBSITE_IMG_URL") }}facebook.png"></a></li>
                @endif

                @if(Config::get('Social.twitter')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.twitter'))}}"><img alt="twitter" title="twitter" src="{{ config("constants.WEBSITE_IMG_URL") }}twitter.png"></a></li>
                @endif

                @if(Config::get('Social.gplus')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.gplus'))}}"><img alt="google plus" title="google plus" src="{{ config("constants.WEBSITE_IMG_URL") }}google-plus.png"></a></li>
                @endif

                @if(Config::get('Social.pinterest')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.pinterest'))}}"><img alt="pinterest" title="pinterest" src="{{ config("constants.WEBSITE_IMG_URL") }}pintrest.png"></a></li>
                @endif

                @if(Config::get('Social.instagram')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.instagram'))}}"><img alt="instagram" title="instagram" src="{{ config("constants.WEBSITE_IMG_URL") }}instagram.png"></a></li>
                @endif

                @if(Config::get('Social.youtube')!='')
                    <li><a target="_blank" href="{{ URL::to(Config::get('Social.youtube'))}}"><img alt="youtube" title="youtube" src="{{ config("constants.WEBSITE_IMG_URL") }}youtube.png"></a></li>
                @endif
            </ul>
        <!--<div class="copyright">
						&copy; {{ Config::get('Site.copyright_text')}} <a href="{{ URL::to('pages/term-and-conditions') }}">Terms and Conditions</a> | <a href="{{ URL::to('pages/privacy-policy') }}">Privacy Policy</a>
					</div>-->
            <div class="copyright">
                &copy; {{ Config::get('Site.copyright_text')}}  <br>
                Web design and development by  <a href="http://responsivetech.co.uk">ResponsiveTech</a>
            </div>

        </div>
    </div>
</footer>
<!-- equal height js-->
<script type="text/javascript" > function resizeequalheight(){ equalHeight($(".cuisine-box animate-plus"));equalHeight($(".make-equal-height")); } function equalHeight(group) { tallest = 0; group.height(''); group.each(function() { thisHeight = $(this).height(); if(thisHeight > tallest) { tallest = thisHeight; } }); group.height(tallest); } $(function(){ $(window).resize(function() { setTimeout('resizeequalheight()',250) }); setTimeout('resizeequalheight()',250) }); </script>
<script type="text/javascript" >
     $(".flexnav").flexNav({'animationSpeed': 150});

    // for loader
    jQuery(window).load(function(){
        jQuery('.loading-hold').fadeOut(1000);

    });
    // cuisine drop down js
    $( ".cusine-dropdownlink" ).click(function() {
        $(this).parent().toggleClass("opendropdown",1000);
        $( ".cuisine-list ul" ).slideToggle( "slow", function() {
            // Animation complete.
        });
    });

    //$(".flexnav").flexNav({'animationSpeed': 150});
    // language  drop down js
    $( ".language-dropdown-link" ).click(function() {
        $(".lang-hold ul").slideToggle();
        $(this).toggleClass("open-langugage");
    });


</script>
</body>
</html>
