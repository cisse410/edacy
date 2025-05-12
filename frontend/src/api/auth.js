import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const authApi = {
  login: async (credentials) => {
    const response = await axios.post(`${API_URL}/login`, credentials)
    return response.data
  },

  logout: async () => {
    const response = await axios.post(`${API_URL}/logout`)
    return response.data
  },

  getUser: async () => {
    const response = await axios.get(`${API_URL}/user`)
    return response.data
  }
}
