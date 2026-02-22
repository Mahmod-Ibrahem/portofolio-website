import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useVideosStore = defineStore('videos', () => {
    const videos = ref([])
    const video = ref(null)
    const loading = ref(false)
    const error = ref(null)
    const meta = ref({})

    function fetchVideos(params = {}) {
        loading.value = true
        error.value = null
        return api.get('/v1/videos', { params })
            .then((response) => {
                videos.value = response.data.data
                meta.value = response.data.meta || {}
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء جلب الفيديوهات'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function fetchVideo(id) {
        loading.value = true
        error.value = null
        return api.get(`/v1/videos/${id}`)
            .then((response) => {
                video.value = response.data.data
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء جلب الفيديو'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function createVideo(formData) {
        loading.value = true
        error.value = null
        return api.post('/videos', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then((response) => {
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                if (err.response?.status === 422) {
                    return { success: false, errors: err.response.data.errors }
                }
                error.value = err.response?.data?.message || 'حدث خطأ أثناء إنشاء الفيديو'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function updateVideo(id, formData) {
        loading.value = true
        error.value = null
        return api.post(`/videos/${id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then((response) => {
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                if (err.response?.status === 422) {
                    return { success: false, errors: err.response.data.errors }
                }
                error.value = err.response?.data?.message || 'حدث خطأ أثناء تحديث الفيديو'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function deleteVideo(id) {
        loading.value = true
        error.value = null
        return api.delete(`/videos/${id}`)
            .then((response) => {
                videos.value = videos.value.filter(v => v.id !== id)
                return { success: true, message: response.data.message }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء حذف الفيديو'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    return {
        videos, video, loading, error, meta,
        fetchVideos, fetchVideo, createVideo, updateVideo, deleteVideo,
    }
})
