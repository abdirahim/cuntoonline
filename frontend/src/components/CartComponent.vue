<template>
  <div class="cart-component">
    <div v-if="cartStore.isEmpty" class="empty-cart text-center py-5">
      <i class="bi bi-cart-x display-1 text-muted"></i>
      <h3 class="mt-3">Your cart is empty</h3>
      <router-link to="/restaurants" class="btn btn-primary mt-3">
        Browse Restaurants
      </router-link>
    </div>

    <div v-else>
      <div class="cart-items">
        <div
          v-for="item in cartStore.items"
          :key="item.meal.id"
          class="cart-item card mb-3"
        >
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-md-2">
                <img
                  :src="item.meal.image || '/placeholder.jpg'"
                  :alt="item.meal.name"
                  class="img-fluid rounded"
                />
              </div>
              <div class="col-md-4">
                <h5 class="mb-1">{{ item.meal.name }}</h5>
                <p class="text-muted mb-0">${{ item.meal.price.toFixed(2) }}</p>
                <small v-if="item.special_instructions" class="text-muted">
                  Note: {{ item.special_instructions }}
                </small>
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  <button
                    class="btn btn-outline-secondary"
                    @click="decreaseQuantity(item.meal.id)"
                  >
                    -
                  </button>
                  <input
                    type="number"
                    class="form-control text-center"
                    :value="item.quantity"
                    readonly
                  />
                  <button
                    class="btn btn-outline-secondary"
                    @click="increaseQuantity(item.meal.id)"
                  >
                    +
                  </button>
                </div>
              </div>
              <div class="col-md-2 text-end">
                <strong>${{ (item.meal.price * item.quantity).toFixed(2) }}</strong>
              </div>
              <div class="col-md-1 text-end">
                <button
                  class="btn btn-link text-danger"
                  @click="removeItem(item.meal.id)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-body">
          <h5>Order Summary</h5>
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <strong>${{ cartStore.cartSubtotal.toFixed(2) }}</strong>
          </div>
          <hr />
          <div class="d-flex justify-content-between">
            <h5>Total:</h5>
            <h5>${{ cartStore.cartSubtotal.toFixed(2) }}</h5>
          </div>
          <button class="btn btn-primary w-100 mt-3" @click="proceedToCheckout">
            Proceed to Checkout
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useCartStore } from '@/stores/cart'
import { useRouter } from 'vue-router'

const cartStore = useCartStore()
const router = useRouter()

const increaseQuantity = (mealId: number) => {
  const item = cartStore.items.find(i => i.meal.id === mealId)
  if (item) {
    cartStore.updateQuantity(mealId, item.quantity + 1)
  }
}

const decreaseQuantity = (mealId: number) => {
  const item = cartStore.items.find(i => i.meal.id === mealId)
  if (item && item.quantity > 1) {
    cartStore.updateQuantity(mealId, item.quantity - 1)
  }
}

const removeItem = (mealId: number) => {
  if (confirm('Remove this item from cart?')) {
    cartStore.removeFromCart(mealId)
  }
}

const proceedToCheckout = () => {
  router.push('/checkout')
}
</script>
