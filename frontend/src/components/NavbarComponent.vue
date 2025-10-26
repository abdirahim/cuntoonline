<template>
  <header>
    <div class="header-top ">
      <div class="container">
        <div class="row">

          <div id="carousel-example-generic" data-interval="3500" class="carousel slide" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner">

              <div class="item">
                <div class="call-icon">
                  <i class="fa fa-calendar "></i>
                </div>
                Halkannn ka dalbo cuntada munaasibadaha, aroosyada iyo xafladaha!
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
          </div>

          <div class="col-sm-6 col-xs-7">
            <div class="header-contact xs-text-center">
              <ul>
                <li><img src="/img/email.png" alt="email"> <a href="#">info@cuntoonline.com</a></li>
                <li><img src="/img/phone.png" alt="phone"> 063 388 0284</li>
              </ul>
            </div>
          </div>
          <div class="col-sm-6 col-xs-5 text-right">
            <a href="javascript:void(0)"><img src="/img/worldgif.gif" alt="world-remit"></a>
            <div class="lang-hold">
              <ul>
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
              <router-link to="/"><img src="/img/logo.png" alt="logo" title="Cunto online" ></router-link>
            </div>
          </div>
          <div class="col-md-2 fl-r col-sm-3">
            <div class="header-banner fl-r">
              <img src="/img/zaad edahab.png" alt="banner">
            </div>
          </div>
          <div class="col-md-8 col-sm-12 col-xs-12 xs-pd">
            <div class="header-right fl-r">
              <div class="navigation ">
                <nav>
                  <div class="menu-button"></div>
                  <ul data-breakpoint="768" class="flexnav">
                    <li :class="{ active: $route.path === '/' }">
                      <router-link to="/">Home</router-link>
                    </li>
                    <li :class="{ active: $route.path === '/about-us' }">
                      <router-link to="/about-us">About Us</router-link>
                    </li>
                    <li :class="{ active: $route.path === '/how-it-works' }">
                      <router-link to="/how-it-works">How it works</router-link>
                    </li>
                    <li :class="{ active: $route.path === '/faq' }">
                      <router-link to="/faq">FAQ's</router-link>
                    </li>
                    <li v-if="!authStore.isAuthenticated" :class="{ active: $route.path === '/login' }">
                      <router-link to="/login">Log In</router-link>
                    </li>
                  </ul>
                </nav>
                <div class="signup-button">
                  <router-link v-if="!authStore.isAuthenticated" to="/signup">Signup</router-link>
                  <div v-else class="dropdown">
                    <a id="dLabel" data-target="#" href="javascript:void(0)" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                      My Account
                      <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                      <li><router-link to="/edit-profile">Edit Profile</router-link></li>
                      <li><a href="javascript:void(0)" @click="handleLogout">Logout</a></li>
                    </ul>
                  </div>
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