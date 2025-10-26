<template>
  <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ restaurant ? 'Edit Restaurant' : 'Create Restaurant' }}
          </h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>

        <form @submit.prevent="handleSubmit">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Name *</label>
                <input
                  v-model="form.name"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Owner User ID *</label>
                <input
                  v-model.number="form.user_id"
                  type="number"
                  class="form-control"
                  required
                  :disabled="!!restaurant"
                />
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">Description</label>
                <textarea
                  v-model="form.description"
                  class="form-control"
                  rows="3"
                ></textarea>
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">Image</label>
                <input
                  type="file"
                  class="form-control"
                  accept="image/*"
                  @change="handleImageChange"
                />
                <img
                  v-if="imagePreview"
                  :src="imagePreview"
                  alt="Preview"
                  class="mt-2"
                  style="max-width: 200px; max-height: 200px"
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Address *</label>
                <input
                  v-model="form.address"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">City *</label>
                <input
                  v-model="form.city"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">State *</label>
                <input
                  v-model="form.state"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Zip Code *</label>
                <input
                  v-model="form.zip_code"
                  type="text"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Phone *</label>
                <input
                  v-model="form.phone"
                  type="tel"
                  class="form-control"
                  required
                />
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  class="form-control"
                />
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">Delivery Fee ($)</label>
                <input
                  v-model.number="form.delivery_fee"
                  type="number"
                  step="0.01"
                  class="form-control"
                />
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">Min Order ($)</label>
                <input
                  v-model.number="form.min_order_amount"
                  type="number"
                  class="form-control"
                />
              </div>

              <div class="col-md-4 mb-3">
                <label class="form-label">Delivery Time (min)</label>
                <input
                  v-model.number="form.estimated_delivery_time"
                  type="number"
                  class="form-control"
                />
              </div>

              <div class="col-md-12 mb-3">
                <div class="form-check">
                  <input
                    v-model="form.is_active"
                    type="checkbox"
                    class="form-check-input"
                    id="isActive"
                  />
                  <label class="form-check-label" for="isActive">
                    Active
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('close')">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { adminApi } from '@/api/admin'
import type { Restaurant } from '@/types/models'

interface Props {
  restaurant: Restaurant | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
  close: []
  saved: []
}>()

const loading = ref(false)
const imageFile = ref<File | null>(null)
const imagePreview = ref<string>('')

const form = reactive({
  user_id: 0,
  name: '',
  description: '',
  address: '',
  city: '',
  state: '',
  zip_code: '',
  phone: '',
  email: '',
  delivery_fee: 0,
  min_order_amount: 0,
  estimated_delivery_time: 30,
  is_active: true,
})

watch(
  () => props.restaurant,
  (restaurant) => {
    if (restaurant) {
      Object.assign(form, {
        user_id: restaurant.user_id || 0,
        name: restaurant.name,
        description: restaurant.description || '',
        address: restaurant.address,
        city: restaurant.city,
        state: restaurant.state,
        zip_code: restaurant.zip_code,
        phone: restaurant.phone,
        email: restaurant.email || '',
        delivery_fee: restaurant.delivery_fee,
        min_order_amount: restaurant.min_order_amount,
        estimated_delivery_time: restaurant.estimated_delivery_time,
        is_active: restaurant.is_active,
      })
      imagePreview.value = restaurant.image || ''
    }
  },
  { immediate: true }
)

const handleImageChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    imageFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const handleSubmit = async () => {
  loading.value = true
  
  try {
    const formData = new FormData()
    
    Object.entries(form).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        formData.append(key, value.toString())
      }
    })
    
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }

    if (props.restaurant) {
      await adminApi.updateRestaurant(props.restaurant.id, formData)
    } else {
      await adminApi.createRestaurant(formData)
    }

    emit('saved')
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to save restaurant')
  } finally {
    loading.value = false
  }
}
</script>
