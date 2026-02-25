import { defineStore } from 'pinia'
import api from '../services/api'

export const useGalleryStore = defineStore('gallery', {
  state: () => ({
    galleries: [],
    loading: false,
    error: null,
    meta: {
      current_page: 1,
      last_page: 1,
      per_page: 20,
      total: 0,
    },
  }),

  actions: {
    async fetchGalleries(params = {}) {
      this.loading = true
      this.error = null
      try {
        const { data } = await api.get('/v1/galleries', { params })
        this.galleries = data.data
        this.meta = data.meta
      } catch (err) {
        this.error = err.response?.data?.message || 'فشل في تحميل الصور'
        throw err
      } finally {
        this.loading = false
      }
    },

    async uploadImage(formData, onProgress) {
      const { data } = await api.post('/galleries', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onUploadProgress: onProgress,
      })
      // Prepend newly created item to gallery list
      if (data.data) {
        this.galleries.unshift(data.data)
        this.meta.total += 1
      }
      return data
    },

    async updateGallery(id, formData, onProgress) {
      const { data } = await api.post(`/galleries/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onUploadProgress: onProgress,
      })
      if (data.data) {
        const index = this.galleries.findIndex(g => g.id === id)
        if (index !== -1) {
          this.galleries[index] = data.data
        }
      }
      return data
    },

    async deleteGallery(id) {
      const { data } = await api.delete(`/galleries/${id}`)
      if (data.success) {
        this.galleries = this.galleries.filter(g => g.id !== id)
        this.meta.total = Math.max(0, this.meta.total - 1)
      }
      return data
    },
  },
})
