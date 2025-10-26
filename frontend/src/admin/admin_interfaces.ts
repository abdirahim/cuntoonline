export interface DashboardStats {
  total_users: number
  total_restaurants: number
  total_orders: number
  pending_orders: number
  total_revenue: number
  recent_orders: import('./models').Order[]
}

export interface AdminRestaurantForm {
  user_id: number
  name: string
  description?: string
  image?: File | null
  address: string
  city: string
  state: string
  zip_code: string
  phone: string
  email?: string
  delivery_fee?: number
  min_order_amount?: number
  estimated_delivery_time?: number
  is_active?: boolean
}

export interface AdminMealForm {
  restaurant_id: number
  name: string
  description?: string
  image?: File | null
  price: number
  category?: string
  is_available?: boolean
  is_vegetarian?: boolean
  is_vegan?: boolean
  is_gluten_free?: boolean
  preparation_time?: number
}

export interface AdminUserForm {
  name: string
  email: string
  password?: string
  phone?: string
  role: 'customer' | 'restaurant_owner' | 'admin'
}
