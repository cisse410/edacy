import { useAuthStore } from '@/stores/auth'
import { computed } from 'vue'
import { useRouter } from 'vue-router'

export function useAuth() {
  const authStore = useAuthStore()
  const router = useRouter()

  const user = computed(() => authStore.user)
  const isAuthenticated = computed(() => authStore.isAuthenticated)

  const login = async (credentials) => {
    await authStore.login(credentials)
    router.push('/dashboard')
  }

  const logout = async () => {
    await authStore.logout()
    router.push('/login')
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
  }
}
