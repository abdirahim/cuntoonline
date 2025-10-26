import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/utils/axios'
import type { User } from '@/types/models'
import type { LoginCredentials, RegisterData, AuthResponse } from '@/types/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isRestaurantOwner = computed(() => user.value?.role === 'restaurant_owner')

  async function login(credentials: LoginCredentials) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.post<AuthResponse>('/login', credentials)
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed'
      return false
    } finally {
      loading.value = false
    }
  }

  async function register(registerData: RegisterData) {
    loading.value = true
    error.value = null
    try {
      const { data } = await api.post<AuthResponse>('/register', registerData)
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      return true
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Registration failed'
      return false
    } finally {
      loading.value = false
    }
  }

  async function fetchUser() {
    if (!token.value) return

    try {
      const { data } = await api.get('/me')
      user.value = data.user
    } catch (err) {
      logout()
    }
  }

  function logout() {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    if (token.value) {
      api.post('/logout').catch(() => {})
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    isRestaurantOwner,
    login,
    register,
    logout,
    fetchUser,
  }
})
