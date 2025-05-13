import { api } from '@/api/api'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useProductStore = defineStore('products', () => {
  const products = ref([])
  const currentProduct = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  const fetchProducts = async () => {
    isLoading.value = true
    error.value = null

    try {
      const response = await api.get('/products')
      products.value = response.data
      return { success: true }
    } catch (err) {
      console.error('Error fetching products:', err)
      error.value = 'Failed to load products. Please try again.'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const fetchProduct = async (id) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await api.get(`/products/${id}`)
      currentProduct.value = response.data.product
      return { success: true }
    } catch (err) {
      console.error(`Error fetching product ${id}:`, err)
      error.value = 'Failed to load product details. Please try again.'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const createProduct = async (productData) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await api.post('/products', productData)
      // Update the products list with the new product
      await fetchProducts()
      return { success: true, product: response.data.product }
    } catch (err) {
      console.error('Error creating product:', err)
      error.value = 'Failed to create product. Please try again.'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const updateProduct = async (id, productData) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await api.put(`/products/${id}`, productData)
      // Update the current product and products list
      currentProduct.value = response.data.product
      await fetchProducts()
      return { success: true, product: response.data.product }
    } catch (err) {
      console.error(`Error updating product ${id}:`, err)
      error.value = 'Failed to update product. Please try again.'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const deleteProduct = async (id) => {
    isLoading.value = true
    error.value = null

    try {
      await api.delete(`/products/${id}`)
      // Remove the product from the list
      products.value = products.value.filter((product) => product.id !== id)
      return { success: true }
    } catch (err) {
      console.error(`Error deleting product ${id}:`, err)
      error.value = 'Failed to delete product. Please try again.'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  return {
    products,
    currentProduct,
    isLoading,
    error,
    fetchProducts,
    fetchProduct,
    createProduct,
    updateProduct,
    deleteProduct,
  }
})
