export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  phone?: string
}

export interface AuthResponse {
  user: import('./models').User
  token: string
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

export interface CreateOrderRequest {
  restaurant_id: number
  user_address_id: number
  items: {
    meal_id: number
    quantity: number
    special_instructions?: string
  }[]
  special_instructions?: string
  payment_method: 'cash' | 'card' | 'online'
}
