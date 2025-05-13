<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useProductStore } from '../../stores/products'

const router = useRouter()
const route = useRoute()
const productStore = useProductStore()

const product = ref({
  name: '',
  description: '',
  price: '',
  quantity: 0,
})

const isSubmitting = ref(false)
const errors = ref({})
const isLoading = ref(true)

onMounted(async () => {
  isLoading.value = true
  const productId = route.params.id

  try {
    await productStore.fetchProduct(productId)

    if (productStore.currentProduct) {
      product.value = {
        name: productStore.currentProduct.name,
        description: productStore.currentProduct.description,
        price: productStore.currentProduct.price,
        quantity: productStore.currentProduct.quantity,
      }
    }
  } catch (err) {
    console.error('Erreur lors de la recuperation des produits:', err)
  } finally {
    isLoading.value = false
  }
})

const validateForm = () => {
  const newErrors = {}

  if (!product.value.name.trim()) {
    newErrors.name = 'Ce champ est obligatoire'
  }

  if (!product.value.description.trim()) {
    newErrors.description = 'Ce champ est obligatoire'
  }

  if (
    !product.value.price ||
    isNaN(parseFloat(product.value.price)) ||
    parseFloat(product.value.price) <= 0
  ) {
    newErrors.price = 'Saisir un prix valide'
  }

  if (
    product.value.quantity === null ||
    isNaN(parseInt(product.value.quantity)) ||
    parseInt(product.value.quantity) < 0
  ) {
    newErrors.quantity = "La quantité n'est pas valide"
  }

  errors.value = newErrors
  return Object.keys(newErrors).length === 0
}

const handleSubmit = async () => {
  if (isSubmitting.value) return

  if (!validateForm()) {
    return
  }

  isSubmitting.value = true

  try {
    // Ensure price is formatted correctly
    const formattedProduct = {
      ...product.value,
      price: parseFloat(product.value.price).toFixed(2),
      quantity: parseInt(product.value.quantity),
    }

    const result = await productStore.updateProduct(route.params.id, formattedProduct)

    if (result.success) {
      router.push({ name: 'ProductDetail', params: { id: route.params.id } })
    }
  } catch (err) {
    console.error('Erreur lors de la modification du produit:', err)
  } finally {
    isSubmitting.value = false
  }
}

const handleCancel = () => {
  router.push({ name: 'ProductDetail', params: { id: route.params.id } })
}
</script>

<template>
  <div class="py-10">
    <header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Modification du produit</h1>
      </div>
    </header>
    <main>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
          <div v-if="isLoading" class="flex justify-center items-center py-12">
            <svg
              class="animate-spin h-8 w-8 text-primary-600"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
          </div>

          <div v-else-if="productStore.error" class="rounded-md bg-red-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <!-- Error icon -->
                <svg
                  class="h-5 w-5 text-red-400"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ productStore.error }}</p>
              </div>
            </div>
          </div>

          <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Name field -->
                <div>
                  <label for="name" class="form-label">Nom</label>
                  <div class="mt-1">
                    <input
                      type="text"
                      id="name"
                      v-model="product.name"
                      class="form-input"
                      :class="{
                        'border-red-300 focus:ring-red-500 focus:border-red-500': errors.name,
                      }"
                    />
                  </div>
                  <p v-if="errors.name" class="mt-2 text-sm text-red-600">{{ errors.name }}</p>
                </div>

                <!-- Description field -->
                <div>
                  <label for="description" class="form-label">Description</label>
                  <div class="mt-1">
                    <textarea
                      id="description"
                      v-model="product.description"
                      rows="4"
                      class="form-input"
                      :class="{
                        'border-red-300 focus:ring-red-500 focus:border-red-500':
                          errors.description,
                      }"
                    ></textarea>
                  </div>
                  <p v-if="errors.description" class="mt-2 text-sm text-red-600">
                    {{ errors.description }}
                  </p>
                </div>

                <!-- Price field -->
                <div>
                  <label for="price" class="form-label">Prix</label>
                  <div class="mt-1">
                    <div class="relative rounded-md shadow-sm">
                      <div
                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                      >
                        <span class="text-gray-500 sm:text-sm">FCFA</span>
                      </div>
                      <input
                        type="number"
                        step="0.01"
                        id="price"
                        v-model="product.price"
                        class="form-input pl-12"
                        :class="{
                          'border-red-300 focus:ring-red-500 focus:border-red-500': errors.price,
                        }"
                      />
                    </div>
                  </div>
                  <p v-if="errors.price" class="mt-2 text-sm text-red-600">{{ errors.price }}</p>
                </div>

                <!-- Quantity field -->
                <div>
                  <label for="quantity" class="form-label">Quantité</label>
                  <div class="mt-1">
                    <input
                      type="number"
                      id="quantity"
                      v-model="product.quantity"
                      class="form-input"
                      :class="{
                        'border-red-300 focus:ring-red-500 focus:border-red-500': errors.quantity,
                      }"
                    />
                  </div>
                  <p v-if="errors.quantity" class="mt-2 text-sm text-red-600">
                    {{ errors.quantity }}
                  </p>
                </div>

                <!-- Form actions -->
                <div class="flex justify-end space-x-3">
                  <button type="button" @click="handleCancel" class="btn-outline">Annuler</button>
                  <button type="submit" class="btn-primary" :disabled="isSubmitting">
                    <span v-if="isSubmitting" class="mr-2">
                      <!-- Loading spinner -->
                      <svg
                        class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle
                          class="opacity-25"
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          stroke-width="4"
                        ></circle>
                        <path
                          class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                      </svg>
                    </span>
                    {{ isSubmitting ? 'Modification...' : 'Modifier' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
