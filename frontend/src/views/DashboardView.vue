<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useProductStore } from '../stores/products'

const router = useRouter()
const authStore = useAuthStore()
const productStore = useProductStore()

const user = computed(() => authStore.user)
const recentProducts = ref([])

onMounted(async () => {
  await productStore.fetchProducts()
  recentProducts.value = productStore.products.slice(0, 5)
})

const navigateToProducts = () => {
  router.push({ name: 'Products' })
}

const navigateToCreateProduct = () => {
  router.push({ name: 'ProductCreate' })
}
</script>

<template>
  <div class="py-10">
    <header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Tableau de bord</h1>
      </div>
    </header>
    <main>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome section -->
        <div class="px-4 py-6 sm:px-0">
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h2 class="text-lg leading-6 font-medium text-gray-900">
                Bienvenue, {{ user?.name || 'User' }}!
              </h2>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Gestion simplifiée de vos produits.
              </p>
            </div>
          </div>
        </div>

        <!-- Action cards -->
        <div class="mt-8">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Products card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-primary-100 rounded-md p-3">
                    <!-- Box icon -->
                    <svg
                      class="h-6 w-6 text-primary-600"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                      />
                    </svg>
                  </div>
                  <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500">Produits</dt>
                    <dd class="flex items-baseline">
                      <div class="text-2xl font-semibold text-gray-900">
                        {{ productStore.products.length }}
                      </div>
                    </dd>
                  </div>
                </div>
                <div class="mt-4">
                  <button
                    @click="navigateToProducts"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                  >
                    Voir plus
                  </button>
                </div>
              </div>
            </div>

            <!-- Add product card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                  <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <!-- Plus icon -->
                    <svg
                      class="h-6 w-6 text-green-600"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                  </div>
                  <div class="ml-5 w-0 flex-1">
                    <dt class="text-sm font-medium text-gray-500">Ajouter un nouveau produit</dt>
                    <dd class="flex items-baseline">
                      <div class="text-lg font-semibold text-gray-900">
                        Créer un nouveau produit
                      </div>
                    </dd>
                  </div>
                </div>
                <div class="mt-4">
                  <button
                    @click="navigateToCreateProduct"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                  >
                    Ajouter
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent products section -->
        <div class="mt-8">
          <h2 class="text-lg leading-6 font-medium text-gray-900 px-4 sm:px-0">Produits récents</h2>
          <div class="mt-2 flex flex-col">
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
                        <th scope="col" class="relative px-6 py-3">
                          <span class="sr-only">Modifier</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                      <tr v-for="product in recentProducts" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{ product.price }} FCFA</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{ product.quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <router-link
                            :to="{ name: 'ProductDetail', params: { id: product.id } }"
                            class="text-primary-600 hover:text-primary-900"
                          >
                            Détails
                          </router-link>
                        </td>
                      </tr>
                      <tr v-if="recentProducts.length === 0">
                        <td
                          colspan="4"
                          class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"
                        >
                          Aucun produits trouvés
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4 px-4 sm:px-0" v-if="productStore.products.length > 5">
            <button
              @click="navigateToProducts"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              Tous les produits
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
