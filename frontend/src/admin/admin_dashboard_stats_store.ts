import { defineStore } from 'pinia'
import { ref } from 'vue'
import { adminApi } from '@/api/admin'
import type { DashboardStats } from '@/types/admin'

export const useAdminStore = defineStore('admin', () => {
  const stats = ref<DashboardStats | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  async function fetchDashboardStats() {
    loading.value = true
    error.value = null
    try {
      const { data } = await adminApi.getDashboardStats()
      stats.value = data
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to load dashboard stats'
    } finally {
      loading.value = false
    }
  }

  return {
    stats,
    loading,
    error,
    fetchDashboardStats,
  }
})
