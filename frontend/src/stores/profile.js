import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useProfileStore = defineStore('profile', () => {
    const profile = ref(null)
    const loading = ref(false)
    const error = ref(null)

    function fetchProfile() {
        loading.value = true; error.value = null
        return api.get('/v1/profile')
            .then((r) => { profile.value = r.data.data; return { success: true, data: r.data.data } })
            .catch((e) => { error.value = e.response?.data?.message || 'حدث خطأ'; return { success: false, message: error.value } })
            .finally(() => { loading.value = false })
    }

    function updateProfile(data) {
        loading.value = true; error.value = null
        return api.put('/v1/profile', data)
            .then((r) => { profile.value = r.data.data; return { success: true, data: r.data.data } })
            .catch((e) => {
                if (e.response?.status === 422) return { success: false, errors: e.response.data.errors }
                error.value = e.response?.data?.message || 'حدث خطأ'; return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    return { profile, loading, error, fetchProfile, updateProfile }
})
