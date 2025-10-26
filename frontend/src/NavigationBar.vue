<template>
  <header>
    xfxfxgxgx
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
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const cartStore = useCartStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/')
}
</script>
