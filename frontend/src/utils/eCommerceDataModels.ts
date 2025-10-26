export interface User {
  id: number
  name: string
  email: string
  phone?: string
  role: 'customer' | 'restaurant_owner' | 'admin'
  email_verified_at?: string
  created_at: string
}

export interface Restaurant {
  id: number
  name: string
  slug: string
  description?: string
  image?: string
  address: string
  city: string
  state: string
  zip_code: string
  phone: string
  email?: string
  delivery_fee: number
  min_order_amount: number
  estimated_delivery_time: number
  rating: number
  total_reviews: number
  is_active: boolean
  times?: RestaurantTime[]
  meals?: RestaurantMeal[]
  created_at: string
}

export interface RestaurantMeal {
  id: number
  restaurant_id: number
  name: string
  slug: string
  description?: string
  image?: string
  price: number
  category?: string
  is_available: boolean
  is_vegetarian: boolean
  is_vegan: boolean
  is_gluten_free: boolean
  preparation_time: number
  restaurant?: Restaurant
  created_at: string
}

export interface RestaurantTime {
  id: number
  day_of_week: string
  open_time: string
  close_time: string
  is_closed: boolean
}

export interface UserAddress {
  id: number
  label?: string
  address_line_1: string
  address_line_2?: string
  city: string
  state: string
  zip_code: string
  country: string
  is_default: boolean
  full_address: string
}

export interface Order {
  id: number
  order_number: string
  status: OrderStatus
  subtotal: number
  delivery_fee: number
  tax: number
  total: number
  special_instructions?: string
  payment_method: PaymentMethod
  payment_status: PaymentStatus
  estimated_delivery_time?: string
  delivered_at?: string
  restaurant?: Restaurant
  address?: UserAddress
  items?: OrderItem[]
  created_at: string
}

export interface OrderItem {
  id: number
  meal_name: string
  price: number
  quantity: number
  subtotal: number
  special_instructions?: string
  meal?: RestaurantMeal
}

export interface CartItem {
  meal: RestaurantMeal
  quantity: number
  special_instructions?: string
}

export type OrderStatus = 'pending' | 'confirmed' | 'preparing' | 'out_for_delivery' | 'delivered' | 'cancelled'
export type PaymentMethod = 'cash' | 'card' | 'online'
export type PaymentStatus = 'pending' | 'paid' | 'refunded'

export interface PaginationMeta {
  current_page: number
  from: number
  last_page: number
  per_page: number
  to: number
  total: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
}
