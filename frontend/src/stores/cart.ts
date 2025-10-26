import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { CartItem, RestaurantMeal } from '@/types/models'

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])
  const restaurantId = ref<number | null>(null)

  const cartCount = computed(() => 
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  )

  const cartSubtotal = computed(() =>
    items.value.reduce((sum, item) => sum + (item.meal.price * item.quantity), 0)
  )

  const isEmpty = computed(() => items.value.length === 0)

  function addToCart(meal: RestaurantMeal, quantity = 1, specialInstructions?: string) {
    // If cart has items from different restaurant, clear it
    if (restaurantId.value && restaurantId.value !== meal.restaurant_id) {
      if (!confirm('Your cart contains items from another restaurant. Clear cart and add this item?')) {
        return false
      }
      clearCart()
    }

    restaurantId.value = meal.restaurant_id

    const existingItem = items.value.find(item => item.meal.id === meal.id)
    
    if (existingItem) {
      existingItem.quantity += quantity
      if (specialInstructions) {
        existingItem.special_instructions = specialInstructions
      }
    } else {
      items.value.push({
        meal,
        quantity,
        special_instructions: specialInstructions,
      })
    }

    saveToLocalStorage()
    return true
  }

  function updateQuantity(mealId: number, quantity: number) {
    const item = items.value.find(item => item.meal.id === mealId)
    if (item) {
      if (quantity <= 0) {
        removeFromCart(mealId)
      } else {
        item.quantity = quantity
        saveToLocalStorage()
      }
    }
  }

  function removeFromCart(mealId: number) {
    const index = items.value.findIndex(item => item.meal.id === mealId)
    if (index > -1) {
      items.value.splice(index, 1)
      if (items.value.length === 0) {
        restaurantId.value = null
      }
      saveToLocalStorage()
    }
  }

  function clearCart() {
    items.value = []
    restaurantId.value = null
    saveToLocalStorage()
  }

  function saveToLocalStorage() {
    localStorage.setItem('cart', JSON.stringify({
      items: items.value,
      restaurantId: restaurantId.value,
    }))
  }

  function loadFromLocalStorage() {
    const saved = localStorage.getItem('cart')
    if (saved) {
      const data = JSON.parse(saved)
      items.value = data.items || []
      restaurantId.value = data.restaurantId || null
    }
  }

  // Load cart on initialization
  loadFromLocalStorage()

  return {
    items,
    restaurantId,
    cartCount,
    cartSubtotal,
    isEmpty,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
  }
})
