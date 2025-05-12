import { defineStore } from 'pinia'
import { authApi } from '../api/auth'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token'),
    isAuthenticated: false
  }),

  actions: {
    async login(credentials) {
        const response = await authApi.login(credentials)
        this.token = response.token
        this.user = response.user
        this.isAuthenticated = true

        localStorage.setItem('token', this.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`

        return response
    },

    async logout() {
      try {
        await authApi.logout()
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.token = null
        this.isAuthenticated = false

        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      try {
        const response = await authApi.getUser()
        this.user = response
        this.isAuthenticated = true
      } catch (error) {
        this.logout()
        console.log(error)
      }
    },

    initializeAuth() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        this.fetchUser()
      }
    }
  }
})
