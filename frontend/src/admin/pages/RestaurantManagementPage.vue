
<template>
  <div class="restaurants-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Restaurants Management</h1>
      <button class="btn btn-primary" @click="openCreateModal">
        <i class="bi bi-plus-lg"></i> Add Restaurant
      </button>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <input
              v-model="searchQuery"
              type="text"
              class="form-control"
              placeholder="Search restaurants..."
              @input="debouncedSearch"
            />
          </div>
          <div class="col-md-3">
            <select v-model="filterActive" class="form-select" @change="loadRestaurants">
              <option value="">All Status</option>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border" role="status"></div>
        </div>

        <div v-else>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>City</th>
                  <th>Phone</th>
                  <th>Rating</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="restaurant in restaurants" :key="restaurant.id">
                  <td>
                    <img
                      :src="restaurant.image || '/placeholder.jpg'"
                      alt="Restaurant"
                      style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px"
                    />
                  </td>
                  <td>{{ restaurant.name }}</td>
                  <td>{{ restaurant.city }}</td>
                  <td>{{ restaurant.phone }}</td>
                  <td>
                    <span class="text-warning">
                      ⭐ {{ restaurant.rating.toFixed(1) }}
                    </span>
                  </td>
                  <td>
                    <span :class="`badge ${restaurant.is_active ? 'bg-success' : 'bg-danger'}`">
                      {{ restaurant.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <button
                        class="btn btn-outline-primary"
                        @click="openEditModal(restaurant)"
                      >
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button
                        class="btn btn-outline-warning"
                        @click="toggleStatus(restaurant.id)"
                      >
                        <i class="bi bi-toggle-on"></i>
                      </button>
                      <button
                        class="btn btn-outline-danger"
                        @click="deleteRestaurant(restaurant.id)"
                      >
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <PaginationComponent
            v-if="meta"
            :meta="meta"
            @page-change="changePage"
          />
        </div>
      </div>
    </div>

    <!-- Modal would go here -->
    <RestaurantFormModal
      v-if="showModal"
      :restaurant="selectedRestaurant"
      @close="closeModal"
      @saved="handleSaved"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminApi } from '@/api/admin'
import type { Restaurant, PaginationMeta } from '@/types/models'
import PaginationComponent from '@/components/PaginationComponent.vue'
import RestaurantFormModal from '../components/RestaurantFormModal.vue'

const restaurants = ref<Restaurant[]>([])
const meta = ref<PaginationMeta | null>(null)
const loading = ref(false)
const searchQuery = ref('')
const filterActive = ref('')
const currentPage = ref(1)
const showModal = ref(false)
const selectedRestaurant = ref<Restaurant | null>(null)

const loadRestaurants = async (page = 1) => {
  loading.value = true
  try {
    const { data } = await adminApi.getRestaurants({
      page,
      per_page: 15,
      search: searchQuery.value || undefined,
      is_active: filterActive.value ? Boolean(Number(filterActive.value)) : undefined,
    })
    restaurants.value = data.data
    meta.value = data.meta
    currentPage.value = page
  } catch (error) {
    console.error('Failed to load restaurants', error)
  } finally {
    loading.value = false
  }
}

let searchTimeout: number
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadRestaurants(1)
  }, 500)
}

const changePage = (page: number) => {
  loadRestaurants(page)
}

const openCreateModal = () => {
  selectedRestaurant.value = null
  showModal.value = true
}

const openEditModal = (restaurant: Restaurant) => {
  selectedRestaurant.value = restaurant
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedRestaurant.value = null
}

const handleSaved = () => {
  closeModal()
  loadRestaurants(currentPage.value)
}

const toggleStatus = async (id: number) => {
  try {
    await adminApi.toggleRestaurantStatus(id)
    loadRestaurants(currentPage.value)
  } catch (error) {
    console.error('Failed to toggle status', error)
  }
}

const deleteRestaurant = async (id: number) => {
  if (!confirm('Are you sure you want to delete this restaurant?')) return

  try {
    await adminApi.deleteRestaurant(id)
    loadRestaurants(currentPage.value)
  } catch (error) {
    console.error('Failed to delete restaurant', error)
  }
}

onMounted(() => {
  loadRestaurants()
})
</script>
