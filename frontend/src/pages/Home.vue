<template>
  <div class="home-page">
    <section class="hero bg-primary text-white text-center py-5">
      <div class="container">
        <h1 class="display-4">Order Food Online</h1>
        <p class="lead">Discover the best restaurants in your area</p>
        <router-link to="/restaurants" class="btn btn-light btn-lg mt-3">
          Browse Restaurants
        </router-link>
      </div>
    </section>

    <section class="container my-5">
      <h2 class="text-center mb-4">Featured Restaurants</h2>
      <div v-if="loading" class="text-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
      <div v-else class="row">
        <div
          v-for="restaurant in restaurants"
          :key="restaurant.id"
          class="col-md-4 mb-4"
        >
          <div class="card h-100">
            <img
              :src="restaurant.image || '/placeholder.jpg'"
              class="card-img-top"
              :alt="restaurant.name"
              style="height: 200px; object-fit: cover"
            />
            <div class="card-body">
              <h5 class="card-title">{{ restaurant.name }}</h5>
              <p class="card-text">{{ restaurant.description }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-warning">
                  ⭐ {{ restaurant.rating.toFixed(1) }} ({{ restaurant.total_reviews }})
                </span>
                <span class="text-muted">{{ restaurant.estimated_delivery_time }} min</span>
              </div>
              <router-link
                :to="`/restaurants/${restaurant.slug}`"
                class="btn btn-primary w-100 mt-3"
              >
                View Menu
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/utils/axios'
import type { Restaurant } from '@/types/models'

const restaurants = ref<Restaurant[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const { data } = await api.get('/restaurants', { params: { per_page: 6 } })
    restaurants.value = data.data
  } catch (error) {
    console.error('Failed to load restaurants', error)
  } finally {
    loading.value = false
  }
})
</script>
