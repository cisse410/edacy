import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const productsApi = {
  getAll: async () => {
    const response = await axios.get(`${API_URL}/products`)
    return response.data
  },

  getOne: async (id) => {
    const response = await axios.get(`${API_URL}/products/${id}`)
    return response.data
  },

  create: async (product) => {
    const response = await axios.post(`${API_URL}/products`, product)
    return response.data
  },

  update: async (id, product) => {
    const response = await axios.put(`${API_URL}/products/${id}`, product)
    return response.data
  },

  delete: async (id) => {
    const response = await axios.delete(`${API_URL}/products/${id}`)
    return response.data
  },
}
