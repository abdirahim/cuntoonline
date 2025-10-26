import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('@/layouts/App.vue'),
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('@/pages/HomePage.vue'),
      },
      {
        path: 'restaurants',
        name: 'restaurants',
        component: () => import('@/pages/RestaurantsPage.vue'),
      },
      {
        path: 'restaurants/:slug',
        name: 'restaurant-detail',
        component: () => import('@/pages/RestaurantDetailPage.vue'),
      },
      {
        path: 'cart',
        name: 'cart',
        component: () => import('@/pages/CartPage.vue'),
      },
      {
        path: 'checkout',
        name: 'checkout',
        component: () => import('@/pages/CheckoutPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'orders',
        name: 'orders',
        component: () => import('@/pages/OrdersPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'orders/:id',
        name: 'order-detail',
        component: () => import('@/pages/OrderDetailPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('@/pages/ProfilePage.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/pages/LoginPage.vue'),
    meta: { guest: true },
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/pages/RegisterPage.vue'),
    meta: { guest: true },
  },
  {
    path: '/admin',
    component: () => import('@/admin/layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        redirect: '/admin/dashboard',
      },
      {
        path: 'dashboard',
        name: 'admin-dashboard',
        component: () => import('@/admin/pages/DashboardPage.vue'),
      },
      {
        path: 'restaurants',
        name: 'admin-restaurants',
        component: () => import('@/admin/pages/RestaurantsPage.vue'),
      },
      {
        path: 'meals',
        name: 'admin-meals',
        component: () => import('@/admin/pages/MealsPage.vue'),
      },
      {
        path: 'orders',
        name: 'admin-orders',
        component: () => import('@/admin/pages/OrdersPage.vue'),
      },
      {
        path: 'users',
        name: 'admin-users',
        component: () => import('@/admin/pages/UsersPage.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (authStore.token && !authStore.user) {
    await authStore.fetchUser()
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } })
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next({ name: 'home' })
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'home' })
  } else {
    next()
  }
})

export default router
