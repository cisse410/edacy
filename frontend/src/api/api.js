import axios from 'axios'

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

// Add a request interceptor to include auth token
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  },
  (error) => {
    return Promise.reject(error)
  },
)

// Add a response interceptor to handle errors
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle 401 Unauthorized errors
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      window.location.href = '/login'
    }

    return Promise.reject(error)
  },
)

export const api = {
  // Auth header management
  setAuthHeader(tokenType, token) {
    apiClient.defaults.headers.common['Authorization'] = `${tokenType} ${token}`
  },

  removeAuthHeader() {
    delete apiClient.defaults.headers.common['Authorization']
  },

  // REST methods
  get(url, config = {}) {
    return apiClient.get(url, config)
  },

  post(url, data = {}, config = {}) {
    return apiClient.post(url, data, config)
  },

  put(url, data = {}, config = {}) {
    return apiClient.put(url, data, config)
  },

  delete(url, config = {}) {
    return apiClient.delete(url, config)
  },
}
