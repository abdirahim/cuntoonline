<template>
  <div class="admin-layout">
    <AdminSidebar :collapsed="sidebarCollapsed" @toggle="toggleSidebar" />
    
    <div class="admin-content" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
      <AdminNavbar @toggle-sidebar="toggleSidebar" />
      
      <main class="main-content">
        <div class="container-fluid py-4">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import AdminSidebar from '../components/AdminSidebar.vue'
import AdminNavbar from '../components/AdminNavbar.vue'

const sidebarCollapsed = ref(false)

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
}

.admin-content {
  flex: 1;
  margin-left: 260px;
  transition: margin-left 0.3s;
}

.admin-content.sidebar-collapsed {
  margin-left: 80px;
}

.main-content {
  min-height: calc(100vh - 70px);
  background-color: #f8f9fa;
}
</style>
