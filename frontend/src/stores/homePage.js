import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useHomePageStore = defineStore('homePage', () => {
    const item = ref(null)
    const loading = ref(false)
    const error = ref(null)

    function fetchSingleton() {
        loading.value = true
        error.value = null
        return api.get('/v1/home-singleton')
            .then((response) => {
                item.value = response.data.data
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء جلب البيانات'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function saveSingleton(formData) {
        loading.value = true
        error.value = null
        return api.post('/home-singleton', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then((response) => {
                item.value = response.data.data
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                if (err.response?.status === 422) {
                    return { success: false, errors: err.response.data.errors }
                }
                error.value = err.response?.data?.message || 'حدث خطأ أثناء الحفظ'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    return { item, loading, error, fetchSingleton, saveSingleton }
})
