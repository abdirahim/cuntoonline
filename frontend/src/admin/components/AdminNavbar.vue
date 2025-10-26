<template>
  <nav class="admin-navbar">
    <div class="navbar-left">
      <button class="btn btn-link" @click="$emit('toggle-sidebar')">
        <i class="bi bi-list fs-4"></i>
      </button>
    </div>

    <div class="navbar-right">
      <div class="dropdown">
        <button
          class="btn btn-link dropdown-toggle"
          type="button"
          data-bs-toggle="dropdown"
        >
          <i class="bi bi-person-circle fs-4"></i>
          {{ authStore.user?.name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <router-link to="/profile" class="dropdown-item">
              <i class="bi bi-person"></i> Profile
            </router-link>
          </li>
          <li><hr class="dropdown-divider" /></li>
          <li>
            <a class="dropdown-item" href="#" @click.prevent="handleLogout">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

defineEmits<{ 'toggle-sidebar': [] }>()

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.admin-navbar {
  height: 70px;
  background-color: white;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.navbar-left,
.navbar-right {
  display: flex;
  align-items: center;
}

.btn-link {
  color: #2c3e50;
  text-decoration: none;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 10px;
}
</style>
