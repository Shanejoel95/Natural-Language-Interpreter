import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8080/api'

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: { 'Content-Type': 'application/json' }
})

export const interpretQuery = async (query, type = 'date') => {
  try {
    const response = await api.post('/interpret', { query, type })
    return response.data
  } catch (error) {
    throw error.response?.data || error
  }
}

export const getHistory = async () => {
  try {
    const response = await api.get('/history')
    return response.data
  } catch (error) {
    throw error.response?.data || error
  }
}

export default api


