<template>
  <aside class="admin-sidebar" :class="{ collapsed }">
    <div class="sidebar-header">
      <h4 v-if="!collapsed">Admin Panel</h4>
      <i v-else class="bi bi-speedometer2"></i>
    </div>

    <nav class="sidebar-nav">
      <router-link to="/admin/dashboard" class="nav-item">
        <i class="bi bi-speedometer2"></i>
        <span v-if="!collapsed">Dashboard</span>
      </router-link>

      <router-link to="/admin/restaurants" class="nav-item">
        <i class="bi bi-shop"></i>
        <span v-if="!collapsed">Restaurants</span>
      </router-link>

      <router-link to="/admin/meals" class="nav-item">
        <i class="bi bi-cup-hot"></i>
        <span v-if="!collapsed">Meals</span>
      </router-link>

      <router-link to="/admin/orders" class="nav-item">
        <i class="bi bi-receipt"></i>
        <span v-if="!collapsed">Orders</span>
      </router-link>

      <router-link to="/admin/users" class="nav-item">
        <i class="bi bi-people"></i>
        <span v-if="!collapsed">Users</span>
      </router-link>

      <hr class="my-3" />

      <router-link to="/" class="nav-item">
        <i class="bi bi-house"></i>
        <span v-if="!collapsed">Back to Site</span>
      </router-link>
    </nav>
  </aside>
</template>

<script setup lang="ts">
interface Props {
  collapsed: boolean
}

defineProps<Props>()
defineEmits<{ toggle: [] }>()
</script>

<style scoped>
.admin-sidebar {
  width: 260px;
  background-color: #2c3e50;
  color: white;
  position: fixed;
  height: 100vh;
  transition: width 0.3s;
  overflow-y: auto;
}

.admin-sidebar.collapsed {
  width: 80px;
}

.sidebar-header {
  padding: 20px;
  text-align: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h4 {
  margin: 0;
  font-size: 1.2rem;
}

.sidebar-nav {
  padding: 20px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s;
}

.nav-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-item.router-link-active {
  background-color: #3498db;
  color: white;
}

.nav-item i {
  font-size: 1.2rem;
  min-width: 30px;
}

.collapsed .nav-item {
  justify-content: center;
}

.collapsed .nav-item span {
  display: none;
}
</style>
