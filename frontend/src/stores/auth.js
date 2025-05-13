import { api } from '@/api/api'
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(JSON.parse(localStorage.getItem('user')) || null)
  const token = ref(localStorage.getItem('token') || null)

  const isAuthenticated = () => !!token.value

  const saveAuthData = (userData, tokenValue) => {
    user.value = userData
    token.value = tokenValue
    localStorage.setItem('user', JSON.stringify(userData))
    localStorage.setItem('token', tokenValue)
  }

  const login = async (credentials) => {
    try {
      const response = await api.post('/login', credentials)
      const { user: userData, access_token, token_type } = response.data

      saveAuthData(userData, access_token)
      api.setAuthHeader(token_type, access_token)

      return { success: true, message: response.data.message }
    } catch (error) {
      console.error('Login error:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de la connexion',
      }
    }
  }

  const register = async (userData) => {
    try {
      const response = await api.post('/register', userData)
      const { user: newUser, access_token, token_type } = response.data

      saveAuthData(newUser, access_token)
      api.setAuthHeader(token_type, access_token)

      return { success: true, message: response.data.message }
    } catch (error) {
      console.error('Registration error:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Erreur lors de l\'inscription.',
      }
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    api.removeAuthHeader()
  }

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
  }
})
