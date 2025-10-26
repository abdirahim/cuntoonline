<template>
  <div class="dashboard-page">
    <h1 class="mb-4">Dashboard</h1>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status"></div>
    </div>

    <div v-else-if="stats" class="row">
      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <div class="stat-icon bg-primary">
            <i class="bi bi-people"></i>
          </div>
          <div class="stat-info">
            <h3>{{ stats.total_users }}</h3>
            <p>Total Users</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <div class="stat-icon bg-success">
            <i class="bi bi-shop"></i>
          </div>
          <div class="stat-info">
            <h3>{{ stats.total_restaurants }}</h3>
            <p>Restaurants</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <div class="stat-icon bg-warning">
            <i class="bi bi-receipt"></i>
          </div>
          <div class="stat-info">
            <h3>{{ stats.total_orders }}</h3>
            <p>Total Orders</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card">
          <div class="stat-icon bg-info">
            <i class="bi bi-currency-dollar"></i>
          </div>
          <div class="stat-info">
            <h3>${{ stats.total_revenue.toFixed(2) }}</h3>
            <p>Revenue</p>
          </div>
        </div>
      </div>
    </div>

    <div v-if="stats" class="card mt-4">
      <div class="card-header">
        <h5 class="mb-0">Recent Orders</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Restaurant</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in stats.recent_orders" :key="order.id">
                <td>{{ order.order_number }}</td>
                <td>{{ order.user?.name || 'N/A' }}</td>
                <td>{{ order.restaurant?.name || 'N/A' }}</td>
                <td>
                  <span :class="`badge bg-${getStatusColor(order.status)}`">
                    {{ order.status }}
                  </span>
                </td>
                <td>${{ order.total.toFixed(2) }}</td>
                <td>{{ formatDate(order.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'
import { storeToRefs } from 'pinia'

const adminStore = useAdminStore()
const { stats, loading } = storeToRefs(adminStore)

onMounted(() => {
  adminStore.fetchDashboardStats()
})

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    pending: 'warning',
    confirmed: 'info',
    preparing: 'primary',
    out_for_delivery: 'secondary',
    delivered: 'success',
    cancelled: 'danger',
  }
  return colors[status] || 'secondary'
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString()
}
</script>

<style scoped>
.stat-card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 20px;
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
}

.stat-info h3 {
  margin: 0;
  font-size: 2rem;
  font-weight: bold;
}

.stat-info p {
  margin: 0;
  color: #6c757d;
}
</style>
