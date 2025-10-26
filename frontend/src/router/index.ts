import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/Home.vue'),
    },
    {
        path: '/restaurants',
        name: 'restaurants',
        component: () => import('@/pages/RestaurantsPage.vue'),
    },
    {
        path: '/cart',
        name: 'cart',
        component: () => import('@/pages/CartPage.vue'),
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/LoginPage.vue'),
        meta: { guest: true },
    },
    // {
    //     path: '/about-us',
    //     name: 'about-us',
    //     component: () => import('@/pages/AboutPage.vue'),
    // },
    // {
    //     path: '/how-it-works',
    //     name: 'how-it-works',
    //     component: () => import('@/pages/HowItWorksPage.vue'),
    // },
    // {
    //     path: '/faq',
    //     name: 'faq',
    //     component: () => import('@/pages/FaqPage.vue'),
    // },
    // Uncomment when ready
    // {
    //   path: '/restaurants/:slug',
    //   name: 'restaurant-detail',
    //   component: () => import('@/pages/RestaurantDetailPage.vue'),
    // },
    // {
    //   path: '/checkout',
    //   name: 'checkout',
    //   component: () => import('@/pages/CheckoutPage.vue'),
    //   meta: { requiresAuth: true },
    // },
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
    } else if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'home' })
    } else {
        next()
    }
})

export default router