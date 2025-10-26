import api from '@/utils/axios'
import type { DashboardStats, AdminRestaurantForm, AdminMealForm, AdminUserForm } from '@/types/admin'
import type { PaginatedResponse, Restaurant, RestaurantMeal, Order, User } from '@/types/models'

export const adminApi = {
  // Dashboard
  getDashboardStats: () => 
    api.get<DashboardStats>('/admin/dashboard/stats'),

  // Restaurants
  getRestaurants: (params?: { page?: number; per_page?: number; search?: string; is_active?: boolean }) =>
    api.get<PaginatedResponse<Restaurant>>('/admin/restaurants', { params }),

  getRestaurant: (id: number) =>
    api.get<{ data: Restaurant }>(`/admin/restaurants/${id}`),

  createRestaurant: (data: FormData) =>
    api.post<{ data: Restaurant; message: string }>('/admin/restaurants', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),

  updateRestaurant: (id: number, data: FormData) =>
    api.post<{ data: Restaurant; message: string }>(`/admin/restaurants/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
      params: { _method: 'PUT' },
    }),

  deleteRestaurant: (id: number) =>
    api.delete<{ message: string }>(`/admin/restaurants/${id}`),

  toggleRestaurantStatus: (id: number) =>
    api.post<{ data: Restaurant; message: string }>(`/admin/restaurants/${id}/toggle-status`),

  // Meals
  getMeals: (params?: { page?: number; per_page?: number; restaurant_id?: number; search?: string; is_available?: boolean }) =>
    api.get<PaginatedResponse<RestaurantMeal>>('/admin/meals', { params }),

  getMeal: (id: number) =>
    api.get<{ data: RestaurantMeal }>(`/admin/meals/${id}`),

  createMeal: (data: FormData) =>
    api.post<{ data: RestaurantMeal; message: string }>('/admin/meals', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),

  updateMeal: (id: number, data: FormData) =>
    api.post<{ data: RestaurantMeal; message: string }>(`/admin/meals/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
      params: { _method: 'PUT' },
    }),

  deleteMeal: (id: number) =>
    api.delete<{ message: string }>(`/admin/meals/${id}`),

  toggleMealAvailability: (id: number) =>
    api.post<{ data: RestaurantMeal; message: string }>(`/admin/meals/${id}/toggle-availability`),

  // Orders
  getOrders: (params?: { page?: number; per_page?: number; status?: string; restaurant_id?: number; search?: string }) =>
    api.get<PaginatedResponse<Order>>('/admin/orders', { params }),

  getOrder: (id: number) =>
    api.get<{ data: Order }>(`/admin/orders/${id}`),

  updateOrderStatus: (id: number, status: string) =>
    api.put<{ data: Order; message: string }>(`/admin/orders/${id}/status`, { status }),

  // Users
  getUsers: (params?: { page?: number; per_page?: number; role?: string; search?: string }) =>
    api.get<PaginatedResponse<User>>('/admin/users', { params }),

  getUser: (id: number) =>
    api.get<{ data: User }>(`/admin/users/${id}`),

  createUser: (data: AdminUserForm) =>
    api.post<{ data: User; message: string }>('/admin/users', data),

  updateUser: (id: number, data: Partial<AdminUserForm>) =>
    api.put<{ data: User; message: string }>(`/admin/users/${id}`, data),

  deleteUser: (id: number) =>
    api.delete<{ message: string }>(`/admin/users/${id}`),
}
