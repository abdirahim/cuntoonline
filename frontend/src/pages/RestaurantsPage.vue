<template>
  <div class="content">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-sm-4 col-md-3">
          <div class="sidebar">
            <!-- Search Widget -->
            <div class="search-widget">
              <form @submit.prevent="handleSearch">
                <div class="search-form">
                  <h3>Search Restaurants</h3>
                  <input
                      v-model="searchQuery"
                      type="text"
                      class="form-control"
                      placeholder="Search Restaurants"
                  >
                  <button type="submit" class="btn btn-default">Search</button>
                </div>
              </form>
            </div>

            <!-- Cuisine Filter -->
            <div class="cuisine-list mt30">
              <h3>
                <span class="hidden-xs">By Cuisine</span>
                <a href="javascript:void(0)" class="visible-xs cusine-dropdownlink">
                  Browse By Cuisine
                </a>
              </h3>
              <ul>
                <li :class="{ active: selectedCuisine === 'all' }">
                  <a href="javascript:void(0)" @click="selectCuisine('all')">All Cuisine</a>
                </li>
                <li
                    v-for="cuisine in cuisines"
                    :key="cuisine.id"
                    :class="{ active: selectedCuisine === cuisine.slug }"
                >
                  <a href="javascript:void(0)" @click="selectCuisine(cuisine.slug)">
                    {{ cuisine.name }} ({{ cuisine.count || 0 }})
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-sm-8">
          <div class="content-right">
            <h2>Open for orders now / Hudheelada diyaar u ah dalabkaaga</h2>
            <div class="clearfix"></div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center mt50">
              <div class="loader"></div>
              <p>Loading restaurants...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="alert alert-danger mt30">
              {{ error }}
            </div>

            <!-- Restaurants Grid -->
            <div v-else class="cuisine-list">
              <div class="row">
                <template v-if="restaurants.length > 0">
                  <div
                      v-for="restaurant in restaurants"
                      :key="restaurant.id"
                      class="col-sm-6 col-md-6 col-lg-4 col-xs-6"
                  >
                    <div class="cuisine-box animate-plus equal-height">
                      <!-- Restaurant Header -->
                      <div class="cuisine-top">
                        <h4>{{ restaurant.name }}</h4>
                        <span class="cuisine-type">
                          <img src="/img/spoon-icon.png" alt="spoon">
                        </span>
                      </div>

                      <!-- Restaurant Image -->
                      <div class="cuisine-img">
                        <figure>
                          <router-link :to="`/restaurants/${restaurant.slug}`">
                            <img
                                :src="getRestaurantImage(restaurant.image)"
                                :alt="restaurant.name"
                                class="img-responsive"
                            />
                          </router-link>
                        </figure>
                        <div v-if="restaurant.is_recommended" class="recomend-text">
                          Recommended
                        </div>
                      </div>

                      <!-- Restaurant Details -->
                      <div class="cuisine-bottom">
                        <!-- Service Info -->
                        <div class="cuisine-info">
                          <div>
                            <img src="/img/right-check.png" alt="right-check">Collection
                          </div>
                          <div>
                            <img src="/img/right-check.png" alt="right-check">Delivery
                          </div>
                        </div>

                        <!-- Rating -->
                        <div class="cuisine-review">
                          <ul class="rating">
                            <li class="stars">
                              <i
                                  v-for="star in 5"
                                  :key="star"
                                  class="fa"
                                  :class="getStarClass(star, restaurant.rating || 0)"
                              ></i>
                            </li>
                          </ul>
                          <span>
                            {{ restaurant.total_reviews || 0 }}
                            {{ restaurant.total_reviews === 1 ? 'review' : 'reviews' }}
                          </span>
                        </div>

                        <!-- Opening Hours -->
                        <div class="cuisine-info availability">
                          <div>
                            <img src="/img/clock-icon.png" alt="clock-icon" title="clock-icon">
                            <span :class="getStatusClass(restaurant)">
                              {{ getOpeningStatus(restaurant) }}
                            </span>
                          </div>
                        </div>

                        <!-- View Menu Button -->
                        <div class="view-link">
                          <router-link
                              :to="`/restaurants/${restaurant.slug}`"
                              class="btn btn-default"
                          >
                            View Menu
                          </router-link>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>

                <!-- Empty State -->
                <div v-else class="mt50 no-record">
                  <template v-if="searchQuery">
                    No restaurants found matching "{{ searchQuery }}"
                  </template>
                  <template v-else-if="selectedCuisine !== 'all'">
                    No restaurants found in this cuisine
                  </template>
                  <template v-else>
                    No restaurants available at the moment
                  </template>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="pagination && restaurants.length > 0" class="pagination-wrapper text-center mt30">
              <nav>
                <ul class="pagination">
                  <li :class="{ disabled: pagination.current_page === 1 }">
                    <a href="javascript:void(0)" @click="goToPage(pagination.current_page - 1)">
                      Previous
                    </a>
                  </li>
                  <li
                      v-for="page in paginationPages"
                      :key="page"
                      :class="{ active: page === pagination.current_page }"
                  >
                    <a href="javascript:void(0)" @click="goToPage(page)">
                      {{ page }}
                    </a>
                  </li>
                  <li :class="{ disabled: pagination.current_page === pagination.last_page }">
                    <a href="javascript:void(0)" @click="goToPage(pagination.current_page + 1)">
                      Next
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import api from '@/utils/axios'

interface Restaurant {
  id: number
  name: string
  slug: string
  description?: string
  image?: string
  address: string
  phone: string
  delivery_fee: number
  min_order_amount: number
  estimated_delivery_time: number
  rating: number
  total_reviews: number
  is_active: boolean
  is_recommended?: boolean
  times?: RestaurantTime[]
  created_at: string
}

interface RestaurantTime {
  id: number
  restaurant_id: number
  week_day: string
  open_time: string
  close_time: string
  is_closed: boolean
}

interface Cuisine {
  id: number
  name: string
  slug: string
  count?: number
}

interface PaginationMeta {
  current_page: number
  from: number
  last_page: number
  per_page: number
  to: number
  total: number
}

const restaurants = ref<Restaurant[]>([])
const cuisines = ref<Cuisine[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const searchQuery = ref('')
const selectedCuisine = ref('all')
const pagination = ref<PaginationMeta | null>(null)

// Computed
const paginationPages = computed(() => {
  if (!pagination.value) return []
  const pages: number[] = []
  const current = pagination.value.current_page
  const last = pagination.value.last_page

  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }

  return pages
})

// Methods
const fetchRestaurants = async (page = 1) => {
  try {
    loading.value = true
    error.value = null

    const params: any = {
      page,
      per_page: 12,
      is_active: true
    }
    if (searchQuery.value) params.search = searchQuery.value
    if (selectedCuisine.value !== 'all') params.cuisine = selectedCuisine.value

    const { data } = await api.get('/restaurants', { params })

    // Handle different response structures
    if (data.data && Array.isArray(data.data)) {
      restaurants.value = data.data
      pagination.value = data.meta || null
    } else if (Array.isArray(data)) {
      restaurants.value = data
      pagination.value = null
    } else {
      restaurants.value = []
    }
  } catch (err: any) {
    console.error('Failed to load restaurants', err)
    error.value = err.response?.data?.message || 'Failed to load restaurants. Please try again later.'
    restaurants.value = []
  } finally {
    loading.value = false
  }
}

const fetchCuisines = async () => {
  try {
    const { data } = await api.get('/cuisines')
    cuisines.value = data.data || data || []
  } catch (err) {
    console.error('Failed to load cuisines', err)
    cuisines.value = []
  }
}

const handleSearch = () => {
  fetchRestaurants(1)
}

const selectCuisine = (slug: string) => {
  selectedCuisine.value = slug
  fetchRestaurants(1)
}

const goToPage = (page: number) => {
  if (!pagination.value) return
  if (page < 1 || page > pagination.value.last_page) return
  fetchRestaurants(page)
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const getRestaurantImage = (image?: string): string => {
  if (image) {
    // Check if it's a full URL
    if (image.startsWith('http')) return image
    // Otherwise, construct the path
    return `/uploads/restaurant_img/${image}`
  }
  return '/img/placeholder.jpg'
}

const getStarClass = (star: number, rating: number): string => {
  if (rating >= star) return 'fa-star'
  if (rating >= star - 0.5) return 'fa-star-half-o'
  return 'fa-star-o'
}

const getOpeningStatus = (restaurant: Restaurant): string => {
  if (!restaurant.times || restaurant.times.length === 0) {
    return 'Hours not available'
  }

  const now = new Date()
  const dayOfWeek = now.toLocaleDateString('en-US', { weekday: 'short' }).toLowerCase()
// Returns: 'mon', 'tue', 'wed', etc. // mon, tue, etc.
  const currentMinutes = now.getHours() * 60 + now.getMinutes()

  // Find today's schedule
  const todaySchedule = restaurant.times.find(
      t => t.week_day === dayOfWeek || t.week_day === 'all'
  )

  if (!todaySchedule || todaySchedule.is_closed) {
    return 'Closed Today'
  }

  const openTime = parseTimeToMinutes(todaySchedule.open_time)
  let closeTime = parseTimeToMinutes(todaySchedule.close_time)

  // Handle overnight closing time
  if (closeTime < openTime) {
    closeTime += 24 * 60
  }

  if (currentMinutes >= openTime && currentMinutes < closeTime) {
    return 'Now Open'
  } else if (currentMinutes < openTime) {
    return `Opening ${formatTime(todaySchedule.open_time)}`
  } else {
    return 'Now Closed'
  }
}

const getStatusClass = (restaurant: Restaurant): string => {
  const status = getOpeningStatus(restaurant)
  if (status === 'Now Open') return 'text-success'
  if (status.includes('Opening')) return 'text-info'
  return 'text-danger'
}

const parseTimeToMinutes = (timeStr: string): number => {
  const [hours, minutes] = timeStr.split(':').map(Number)
  return hours * 60 + minutes
}

const formatTime = (timeStr: string): string => {
  const [hours, minutes] = timeStr.split(':').map(Number)
  const period = hours >= 12 ? 'PM' : 'AM'
  const hour12 = hours % 12 || 12
  return `${hour12}:${minutes.toString().padStart(2, '0')} ${period}`
}

// Lifecycle
onMounted(() => {
  fetchRestaurants()
  fetchCuisines()

  // Initialize equal height after DOM is ready
  setTimeout(() => {
    const equalHeight = (group: string) => {
      let tallest = 0
      const elements = document.querySelectorAll(group)
      elements.forEach((el: any) => {
        el.style.height = ''
        const height = el.offsetHeight
        if (height > tallest) tallest = height
      })
      elements.forEach((el: any) => {
        el.style.height = `${tallest}px`
      })
    }

    equalHeight('.equal-height')
  }, 500)
})
</script>

<style scoped>
.content {
  padding: 40px 0;
  background-color: #f8f9fa;
  min-height: 80vh;
}

.mt30 {
  margin-top: 30px;
}

.mt50 {
  margin-top: 50px;
}

.no-record {
  text-align: center;
  padding: 60px 20px;
  font-size: 18px;
  color: #666;
  background: white;
  border-radius: 8px;
  width: 100%;
}

.sidebar {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.search-form h3 {
  font-size: 18px;
  margin-bottom: 15px;
  font-weight: bold;
}

.search-form input {
  margin-bottom: 10px;
}

.search-form .btn {
  width: 100%;
}

.cuisine-list ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.cuisine-list li {
  margin-bottom: 10px;
}

.cuisine-list li.active a {
  color: #3498db;
  font-weight: bold;
}

.cuisine-list a {
  color: #333;
  text-decoration: none;
  transition: color 0.3s ease;
  display: block;
  padding: 5px 0;
}

.cuisine-list a:hover {
  color: #3498db;
}

.content-right h2 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 30px;
  color: #333;
}

.cuisine-box {
  margin-bottom: 30px;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: auto;
}

.cuisine-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.cuisine-top {
  padding: 15px;
  border-bottom: 1px solid #eee;
}

.cuisine-top h4 {
  margin: 0 0 5px;
  font-size: 18px;
  font-weight: bold;
  color: #333;
}

.cuisine-type {
  font-size: 14px;
  color: #666;
  display: flex;
  align-items: center;
  gap: 5px;
}

.cuisine-type img {
  width: 16px;
  height: 16px;
}

.cuisine-img {
  position: relative;
  height: 200px;
  overflow: hidden;
  background: #f0f0f0;
}

.cuisine-img figure {
  margin: 0;
  height: 100%;
}

.cuisine-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.recomend-text {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #f39c12;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
}

.cuisine-bottom {
  padding: 15px;
}

.cuisine-info {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 10px;
  font-size: 13px;
  color: #666;
}

.cuisine-info div {
  display: flex;
  align-items: center;
  gap: 5px;
}

.cuisine-info img {
  width: 16px;
  height: 16px;
}

.cuisine-review {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
  font-size: 14px;
}

.cuisine-review .rating {
  list-style: none;
  padding: 0;
  margin: 0;
}

.stars {
  color: #f39c12;
}

.stars i {
  margin-right: 2px;
  font-size: 14px;
}

.availability {
  margin-bottom: 15px;
  padding: 8px 0;
}

.text-success {
  color: #27ae60;
  font-weight: bold;
}

.text-info {
  color: #3498db;
  font-weight: bold;
}

.text-danger {
  color: #e74c3c;
  font-weight: bold;
}

.view-link {
  margin-top: 10px;
}

.view-link .btn {
  width: 100%;
}

.pagination-wrapper {
  margin: 30px 0;
}

.pagination {
  display: flex;
  justify-content: center;
  list-style: none;
  padding: 0;
}

.pagination li {
  margin: 0 5px;
}

.pagination li a {
  display: block;
  padding: 8px 12px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  color: #333;
  text-decoration: none;
  transition: all 0.3s ease;
}

.pagination li.active a {
  background: #3498db;
  color: white;
  border-color: #3498db;
}

.pagination li.disabled a {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

.pagination li a:hover:not(.disabled) {
  background: #3498db;
  color: white;
  border-color: #3498db;
}

@media (max-width: 768px) {
  .cuisine-box {
    margin-bottom: 20px;
  }

  .sidebar {
    margin-bottom: 30px;
  }

  .content-right h2 {
    font-size: 20px;
  }
}
</style>