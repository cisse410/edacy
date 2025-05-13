<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useProductStore } from '../../stores/products'

const router = useRouter()
const productStore = useProductStore()

const searchQuery = ref('')
const filteredProducts = ref([])

onMounted(async () => {
  await productStore.fetchProducts()
  filteredProducts.value = [...productStore.products]
})

const handleSearch = () => {
  if (!searchQuery.value.trim()) {
    filteredProducts.value = [...productStore.products]
    return
  }

  const query = searchQuery.value.toLowerCase()
  filteredProducts.value = productStore.products.filter(
    (product) =>
      product.name.toLowerCase().includes(query) ||
      product.description.toLowerCase().includes(query),
  )
}

const viewProduct = (id) => {
  router.push({ name: 'ProductDetail', params: { id } })
}

const editProduct = (id) => {
  router.push({ name: 'ProductEdit', params: { id } })
}

const confirmDelete = async (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet produit?')) {
    await productStore.deleteProduct(id)
  }
}

const createProduct = () => {
  router.push({ name: 'ProductCreate' })
}
</script>

<template>
  <div class="py-10">
    <header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Produits</h1>
        <button
          @click="createProduct"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Ajouter
        </button>
      </div>
    </header>
    <main>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Search bar -->
        <div class="px-4 py-5 sm:px-0">
          <div class="flex">
            <div class="flex-1 min-w-0">
              <label for="search" class="sr-only">Rechercher</label>
              <div class="relative rounded-md shadow-sm">
                <input
                  type="text"
                  name="search"
                  id="search"
                  v-model="searchQuery"
                  @input="handleSearch"
                  class="form-input block w-full pr-10 sm:text-sm"
                  placeholder="Rechercher un produit..."
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                  <!-- Search icon -->
                  <svg
                    class="h-5 w-5 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Products list -->
        <div class="mt-6">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Nom
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Prix
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Quantité
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-center text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Actions
                        </th>
                        <!-- <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Actions</span>
                        </th> -->
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="hover:bg-gray-50 transition-colors duration-150 ease-in-out"
                      >
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="ml-4">
                              <div class="text-sm font-medium text-gray-900">
                                {{ product.name }}
                              </div>
                              <div class="text-sm text-gray-500 truncate max-w-md">
                                {{ product.description }}
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{ product.price }} FCFA</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                            :class="[
                              product.quantity > 10
                                ? 'bg-green-100 text-green-800'
                                : product.quantity > 0
                                  ? 'bg-yellow-100 text-yellow-800'
                                  : 'bg-red-100 text-red-800',
                            ]"
                          >
                            {{ product.quantity }}
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <button
                            @click="viewProduct(product.id)"
                            class="text-primary-600 hover:text-primary-900 mr-4"
                          >
                            Détails
                          </button>
                          <button
                            @click="editProduct(product.id)"
                            class="text-secondary-600 hover:text-secondary-900 mr-4"
                          >
                            Modifier
                          </button>
                          <button
                            @click="confirmDelete(product.id)"
                            class="text-red-600 hover:text-red-900"
                          >
                            Supprimer
                          </button>
                        </td>
                      </tr>
                      <tr v-if="filteredProducts.length === 0">
                        <td
                          colspan="4"
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
                        >
                          {{
                            productStore.isLoading
                              ? 'Chargement des produits...'
                              : 'Aucun produits trouvés'
                          }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
