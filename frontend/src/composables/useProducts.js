// import { useProductsStore } from '@/stores/products'
// import { computed } from 'vue'

// export function useProducts() {
//   const productsStore = useProductsStore()

//   const products = computed(() => productsStore.products)
//   const loading = computed(() => productsStore.loading)
//   const error = computed(() => productsStore.error)

//   const fetchProducts = () => productsStore.fetchProducts()
//   const createProduct = (product) => productsStore.createProduct(product)
//   const updateProduct = (id, product) => productsStore.updateProduct(id, product)
//   const deleteProduct = (id) => productsStore.deleteProduct(id)

//   return {
//     products,
//     loading,
//     error,
//     fetchProducts,
//     createProduct,
//     updateProduct,
//     deleteProduct,
//   }
// }
